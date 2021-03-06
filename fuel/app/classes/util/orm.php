<?php
class Util_Orm
{
	public static function get_model_name($table, $namespace = '')
	{
		$model = '\Model_'.Inflector::camelize($table);
		if ($namespace) $model = sprintf('\%s%s', ucfirst($namespace), $model);

		return $model;
	}

	public static function conv_col2array($objs, $column, $is_check_array = false)
	{
		$return = array();
		foreach ($objs as $obj)
		{
			$return[] = ($is_check_array && is_array($obj)) ? $obj[$column] : $obj->$column;
		}

		return $return;
	}

	public static function conv_cols2assoc($objs, $key_col, $value_col)
	{
		$return = array();
		foreach ($objs as $obj)
		{
			$return[$obj->$key_col] = $obj->$value_col;
		}

		return $return;
	}

	public static function get_relational_numeric_key_prop($is_required = true)
	{
		$field = array(
			'data_type' => 'integer',
			'validation' => array('valid_string' => array('numeric')),
			'form' => array('type' => false),
		);
		if ($is_required) $field['validation'][] = 'required';

		return $field;
	}

	// recommend to use \MyOrm\Model::get_count.
	public static function get_count_all($model_name, $conditions = array())
	{
		$query = $model_name::query();
		if ($conditions) $query = $query->where($conditions);

		return $query->count();
	}

	// recommend to use \MyOrm\Model::get_last.
	public static function get_last_row($model_name, $conditions = array(), $sort_col = 'id')
	{
		$query = $model_name::query();
		if ($conditions) $query->where($conditions);
		$query->order_by($sort_col, 'desc')->rows_limit(1);

		return $query->get_one();
	}

	public static function check_ids_in_models($target_ids, $objs, $id_column_name = 'id')
	{
		return Util_Array::array_in_array($target_ids, self::conv_col2array($objs, $id_column_name));
	}

	public static function get_related_table_values_recursive(\Orm\Model$obj, $related_table_props = array())
	{
		$values = array();
		foreach ($related_table_props as $related_table => $prop)
		{
			if (is_array($prop))
			{
				$value = array_merge($values, self::get_related_table_values_recursive($obj->{$related_table}, $prop));
				continue;
			}

			$values[] = $obj->{$related_table}->{$prop};
		}

		return $values;
	}

	/**
	 * Get related models member_ids
	 * 
	 * @access  public
	 * @param   object  $related_obj  related orm model object
	 * @param   array   $parent_member_id_relateds  related table and property array
	 * @return  array   related models member_ids 
	 */
	public static function get_related_member_ids(\Orm\Model $related_obj, $related_member_id_relateds = array())
	{
		if ($related_member_id_relateds)
		{
			$related_member_ids = static::get_related_table_values_recursive($related_obj, $related_member_id_relateds);
		}
		else
		{
			$related_member_ids[] = $related_obj->member_id;
		}

		return $related_member_ids;
	}

	public static function check_is_changed(\Orm\Model $obj, array $target_properties, array $before_values)
	{
		foreach ($target_properties as $property)
		{
			if ($obj->{$property} != $before_values[$property]) return true;
		}

		return false;
	}

	public static function get_changed_values(\Orm\Model $obj, $property = null)
	{
		$values = $obj->get_diff();
		if (!$values) return false;

		if (!$property) return $values;

		return array($values[0][$property], $values[1][$property]);
	}

	public static function check_is_updated(\Orm\Model $obj, $check_properties = array(), $ignore_properties = array(), $check_is_changed = true)
	{
		if (empty($check_properties) && empty($ignore_properties))
		{
			return true;
		}

		if (\Util_Orm::check_properties_updated($obj, $check_properties, $check_is_changed)) return true;
		if (\Util_Orm::check_properties_updated_without_ignores($obj, $ignore_properties)) return true;

		return false;
	}

	public static function check_properties_updated(\Orm\Model $obj, $check_properties, $check_is_changed)
	{
		if (empty($check_properties)) return false;

		$check_properties = (array)$check_properties;
		foreach ($check_properties as $key => $property)
		{
			if (is_array($property))
			{
				$conditions = $property;
				$property = $key;
				foreach ($conditions as $condition => $value)
				{
					if ($check_is_changed && !$obj->is_changed($property)) continue;

					if ($condition == 'value')
					{
						if (is_array($value))
						{
							if (! in_array($obj->{$property}, $value)) continue;
						}
						else
						{
							if ($obj->{$property} != $value) continue;
						}

						return true;
					}
					if ($condition == 'ignore_property')
					{
						if ($obj->is_changed($value)) continue;

						return true;
					}
					if ($condition == 'ignore_value')
					{
						list($before, $after) = \Util_Orm::get_changed_values($obj, $property);
						if ($value == 'reduced_public_flag_range')
						{
							if (Site_Util::check_is_reduced_public_flag_range($before, $after)) continue;
						}
						elseif ($value == 'reduced_num')
						{
							if (preg_match('/`'.$property.'`\s+\-\s+1/', $after)) continue;
							if (is_numeric($before) && is_numeric($after) && $before > $after) continue;
						}

						return true;
					}
				}
			}
			else
			{
				if ($obj->is_changed($property)) return true;
			}
		}

		return false;
	}

	public static function check_properties_updated_without_ignores(\Orm\Model $obj, $ignore_properties)
	{
		if (empty($ignore_properties)) return false;

		$ignore_properties = (array)$ignore_properties;
		$all_properties = \Util_Db::get_columns($obj::get_table_name());
		foreach ($all_properties as $property)
		{
			if (in_array($property, $ignore_properties)) continue;
			if ($obj->is_changed($property)) return true;
		}

		return false;
	}

	public static function add_query_where(\Orm\Query $query, $conditions = array())
	{
		if (!$conditions) return $query;

		if (count($conditions) == 3)
		{
			$query->where($conditions[0], $conditions[1], $conditions[2]);
		}
		else
		{
			$query->where($conditions);
		}

		return $query;
	}

	public static function check_included($check_value, $property, $objs)
	{
		if (!$objs) return false;

		foreach ($objs as $obj)
		{
			if ($obj->{$property} == $check_value) return true;
		}

		return false;
	}

	public static function get_where_cond_depended_on_param_count($prop, $params)
	{
		if (!is_array($params)) $params = (array)$params;

		return (count($params) == 1) ? array($prop, array_shift($params)) : array($prop, 'in', $params);
	}
}
