<script type="text/javascript" charset="utf-8">
var GL = {};
GL.execute_flg = false;
function get_baseUrl() {return '<?php echo Uri::base(false); ?>';}
function get_token_key() {return '<?php echo Config::get('security.csrf_token_key'); ?>';}
function get_token() {return '<?php echo Util_security::get_csrf(); ?>';}
function is_sp() {return <?php echo (IS_SP)? 'true' : 'false'; ?>;}
function get_term(key) {
	var terms = {};
	terms['public_flag'] = '<?php echo \Config::get('term.public_flag.label'); ?>';
	terms['album_image'] = '<?php echo \Config::get('term.album_image'); ?>';
	terms['timeline']    = '<?php echo \Config::get('term.timeline'); ?>';
	return terms[key];
}
</script>