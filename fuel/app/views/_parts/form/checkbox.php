<?php
if ($is_required)
{
	$label .= '<span class="required">*</span>';
	$atter['required'] = 'required';
}

$label_class = 'col-sm-'.$label_col_sm_size;
$input_col_sm_size = 12 - $label_col_sm_size;
if ($label_col_sm_size == 12)
{
	$input_col_sm_size = 12;
}
else
{
	$label_class .= ' control-label';
}
?>
<div class="form-group<?php if ($val->error($name)): ?> has-error<?php endif; ?>">
	<?php echo Form::label($label, $name, array('class' => $label_class)); ?>
	<div class="col-sm-<?php echo $input_col_sm_size; ?>">
		<div class="checkbox">
			<label>
<?php
$atter['id'] = sprintf('form_%s_1', $name);
if (in_array('1', Input::post($name, array()))) $atter['checked'] = 'checked';
echo Form::checkbox($name.'[]', '1', $atter);
?>
			</label>
		</div>
<?php if ($val->error($name)): ?>
		<span class="help-block error_msg"><?php echo $val->error($name)->get_message(); ?></span>
<?php endif; ?>
	</div>
</div>