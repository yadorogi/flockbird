<?php
namespace Album;

class UploadHandler extends \JqueryFileUpload
{

	public function get($album_id)
	{
		$file_name = isset($_REQUEST['file']) ? basename(stripslashes($_REQUEST['file'])) : null;
		if ($file_name)
		{
			$info = $this->get_file_object($file_name);
		}
		else
		{
			$info = $this->get_file_objects($album_id);
		}

		return json_encode($info);
	}

	protected function get_file_objects($album_id)
	{
		$info = array();
		$album_images = Model_AlbumImage::find()->where('album_id', $album_id)->related('album')->order_by('created_at')->get();
		foreach ($album_images as $album_image)
		{
			$info[] = $this->get_file_object($album_image->image, $album_image->id);
		}

		return $info;
	}

	protected function get_file_object($file_name, $album_image_id = 0)
	{
		$file_path = $this->options['upload_dir'].$file_name;
		if (is_file($file_path) && $file_name[0] !== '.')
		{
			$file = new \stdClass();
			$file->name = $file_name;
			$file->size = filesize($file_path);
			$file->url = $this->options['upload_url'].rawurlencode($file->name);
			foreach($this->options['image_versions'] as $version => $options)
			{
				if (is_file($options['upload_dir'].$file_name))
				{
					$file->{$version.'_url'} = $options['upload_url'].rawurlencode($file->name);
				}
			}

			if ($album_image_id) $file->album_image_id = $album_image_id;
			$this->set_file_delete_url($file);

			return $file;
		}

		return null;
	}

	protected function set_file_delete_url($file)
	{
		$file->delete_url = $this->options['script_url'].'?id='.rawurlencode($file->album_image_id);
		$file->delete_type = $this->options['delete_type'];
		if ($file->delete_type !== 'DELETE') {
			$file->delete_url .= '&_method=DELETE';
		}
	}

	protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $album_image_id = 0)
	{
		$file = new \stdClass();
		$file->name = $this->trim_file_name($name, $type, $index);
		$file->size = intval($size);
		$file->type = $type;
		if ($this->validate($uploaded_file, $file, $error, $index))
		{
			$this->handle_form_data($file, $index);
			$file_path = $this->options['upload_dir'].$file->name;
			$append_file = !$this->options['discard_aborted_uploads'] && is_file($file_path) && $file->size > filesize($file_path);
			clearstatcache();
			if ($uploaded_file && is_uploaded_file($uploaded_file))
			{
				// multipart/formdata uploads (POST method uploads)
				if ($append_file)
				{
					file_put_contents(
						$file_path,
						fopen($uploaded_file, 'r'),
						FILE_APPEND
					);
				}
				else
				{
					move_uploaded_file($uploaded_file, $file_path);
				}
			}
			else
			{
				// Non-multipart uploads (PUT method support)
				file_put_contents(
					$file_path,
					fopen('php://input', 'r'),
					$append_file ? FILE_APPEND : 0
				);
			}
			$file_size = filesize($file_path);
			if ($file_size === $file->size)
			{
				if ($this->options['orient_image'])
				{
					$this->orient_image($file_path);
				}
				$file->url = $this->options['upload_url'].rawurlencode($file->name);
				foreach($this->options['image_versions'] as $version => $options)
				{
					if ($this->create_scaled_image($file->name, $options))
					{
						if ($this->options['upload_dir'] !== $options['upload_dir'])
						{
							$file->{$version.'_url'} = $options['upload_url'].rawurlencode($file->name);
						}
						else
						{
							clearstatcache();
							$file_size = filesize($file_path);
						}
					}
				}
			}
			elseif ($this->options['discard_aborted_uploads'])
			{
				unlink($file_path);
				$file->error = 'abort';
			}
			$file->size = $file_size;
			$file->album_image_id = $album_image_id;
			$this->set_file_delete_url($file);
		}

		return $file;
	}

	public function post($album_id)
	{
		$_method = \Input::post('_method');
		if (isset($_method) && $_method === 'DELETE')
		{
			return $this->delete();
		}

		$upload = \Input::file($this->options['param_name'], null);

		$HTTP_X_FILE_NAME = \Input::server('HTTP_X_FILE_NAME');
		$prefix = 'ai_'.$album_id;
		$info = array();
		if ($upload && is_array($upload['tmp_name']))
		{
			// param_name is an array identifier like "files[]",
			// $_FILES is a multi-dimensional array:
			foreach ($upload['tmp_name'] as $index => $value)
			{
				if (!$extention = \Util_file::check_image_type($upload['tmp_name'][$index], array('jpeg', 'jpg', 'png', 'gif'), $upload['type'][$index]))
				{
					continue;
				}
				$filename = \Util_file::make_filename(\Input::server('HTTP_X_FILE_NAME', $upload['name'][$index]), $extention, $prefix);

				// filename の保存
				$album_image = Model_AlbumImage::forge(array(
					'album_id' => (int)$album_id,
					'image' => $filename,
				));
				$album_image->save();

				$result = $this->handle_file_upload(
					$upload['tmp_name'][$index],
					$filename,
					\Input::server('HTTP_X_FILE_SIZE', $upload['size'][$index]),
					\Input::server('HTTP_X_FILE_TYPE', $upload['type'][$index]),
					$upload['error'][$index],
					$index,
					$album_image->id
				);
				$info[] = $result;

				$album_image->image = $result->name;
				$album_image->save();
			}
		}
		elseif ($upload || isset($HTTP_X_FILE_NAME))
		{
			if (!$extention = \Util_file::check_image_type($upload['tmp_name'], array('jpeg', 'jpg', 'png', 'gif'), $upload['type']))
			{
				return;
			}
			$filename = \Util_file::make_filename(\Input::server('HTTP_X_FILE_NAME', $upload['name']), $prefix);
			// filename の保存
			$album_image = Model_AlbumImage::forge(array(
				'album_id' => (int)$album_id,
				'image' => $filename,
			));
			$album_image->save();

			// param_name is a single object identifier like "file",
			// $_FILES is a one-dimensional array:
			$result = $this->handle_file_upload(
				isset($upload['tmp_name']) ? $upload['tmp_name'] : null,
				$filename,
				\Input::server('HTTP_X_FILE_SIZE', isset($upload['size'])? $upload['size'] : null),
				\Input::server('HTTP_X_FILE_TYPE', isset($upload['type'])? $upload['type'] : null),
				isset($upload['error']) ? $upload['error'] : null,
				null,
				$album_image->id
			);
			$info[] = $result;

			$album_image->image = $result->name;
			$album_image->save();
		}

		header('Vary: Accept');
		$json = json_encode($info);
		$redirect = \Input::post(stripslashes('redirect'), null);
		if ($redirect)
		{
			\Response::redirect(sprintf($redirect, rawurlencode($json)));
			return;
		}

		return $json;
	}

	public function delete($album_id)
	{
		$album_image_id = (int)\Input::get('id');
		if (!$album_image = Model_AlbumImage::check_authority($album_image_id, $this->current_user->id))
		{
			throw new \HttpNotFoundException;
		}
		$file_name = $album_image->image;

		if (isset($file_name)) $file_name = basename(stripslashes($file_name));
		$file_path = $this->options['upload_dir'].$file_name;
		$success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);
		if ($success)
		{
			foreach($this->options['image_versions'] as $version => $options)
			{
				$file = $options['upload_dir'].$file_name;
				if (is_file($file))
				{
					unlink($file);
				}
			}
		}

		$album_image->delete();

		return json_encode($success);
	}
}