<div class="well">
<?php echo render('_parts/form/description', array('exists_required_fields' => true)); ?>
<?php echo $html_form; ?>
</div>
<?php if (!IS_ADMIN): ?>
<?php echo render('member/setting/_parts/footer_navi'); ?>
<?php endif; ?>
