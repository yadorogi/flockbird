$('#submit_delete').click(function() {
	submit_after_confirm('delete');
});
$('#submit_post').click(function() {
	submit_after_confirm('post');
});
function submit_after_confirm(action) {
	$('#clicked_btn').val(action);

	if (check_is_checked()) {
		var confirmMessage = action == 'delete' ? __('message_delete_all_confirm') : __('message_edit_all_confirm');
		var name        = $.trim($('#form_name').val());// for legacy IE.
		var public_flag = $('input[name="public_flag"]:checked').val();
		var shot_at     = $.trim($('#form_shot_at').val());// for legacy IE.
		var lat         = $.trim($('#input_lat').val());// for legacy IE.
		var lng         = $.trim($('#input_lng').val());// for legacy IE.
		if (action == 'post' && !name.length && public_flag == 99 && !shot_at.length && !lat.length && !lng.length) {
			apprise(__('message_please_input'));
			return false;
		}
		apprise(confirmMessage, {'confirm':true}, function(r) {
			if (r == true) $("form#form_album_edit_images").submit();
		});
	} else {
		apprise(__('message_not_selected'));
		return false;
	}
}

function check_is_checked() {
	var is_checked = false;
	$('.album_image_ids').each(function() {
		if ($(this).is(':checked')) is_checked = true;
	});

	return is_checked;
}

$('table#album_image_list td:not(.image)').click(function() {
	var c = $(this).parent('tr').children('td').children('input[type=checkbox]');
	if (c.prop('checked')) {
		c.prop('checked', '');
	} else {
		c.prop('checked', 'checked');
	}
});
$('table#album_image_list td input[type=checkbox]').click(function(){
	if ($(this).prop('checked')) {
		$(this).prop('checked', '');
	} else {
		$(this).prop('checked', 'checked');
	}
});

$('input.album_image_all').click(function() {
	if (this.checked) {
		$('input.album_image_ids').prop('checked', 'checked');
		$('input.album_image_all').prop('checked', 'checked');
	} else {
		$('input.album_image_ids').prop('checked', '');
		$('input.album_image_all').prop('checked', '');
	}
});
