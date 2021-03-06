<?php

class Controller_Member_Setting_Lang extends \Controller_Site
{
	protected $check_not_auth_action = array();

	public function before()
	{
		parent::before();

		if (!is_enabled_i18n()) throw new HttpNotFoundException;
	}

	/**
	 * Mmeber setting lang
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		$val = \Form_MemberConfig::get_validation($this->u->id, 'lang');
		if (\Input::method() == 'POST')
		{
			\Util_security::check_csrf();

			try
			{
				if (!$val->run()) throw new \ValidationFailedException($val->show_errors());
				$post = $val->validated();
				\DB::start_transaction();
				\Form_MemberConfig::save($this->u->id, $val, $post);
				\DB::commit_transaction();

				\Site_Lang::reset_lang($post['lang']);
				\Session::set_flash('message', __('message_update_complete_for', array('label' => term('site.lang', 'site.setting'))));
				\Response::redirect('member/setting');
			}
			catch(\ValidationFailedException $e)
			{
				\Session::set_flash('error', $e->getMessage());
			}
			catch(\FuelException $e)
			{
				if (\DB::in_transaction()) \DB::rollback_transaction();
				\Session::set_flash('error', $e->getMessage());
			}
		}
		$this->set_title_and_breadcrumbs(term('site.lang', 'site.setting'), array('member/setting' => term('site.setting')), $this->u);
		$this->template->content = \View::forge('member/setting/_parts/form', array(
			'val' => $val,
			'label_size' => 5,
			'form_params' => array('common' => array('radio' => array('layout_type' => 'grid'))),
		));
	}
}
