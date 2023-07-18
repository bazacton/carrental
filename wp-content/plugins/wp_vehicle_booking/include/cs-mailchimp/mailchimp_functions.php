<?php	
if ( ! function_exists( 'cs_custom_mailchimp' ) ) {
	function cs_custom_mailchimp(){
		global $cs_theme_option,$counter;
		$counter++;

		?>
        
        <form action="javascript:cs_mailchimp_submit('<?php echo get_template_directory_uri()?>','<?php echo esc_js($counter); ?>','<?php echo admin_url('admin-ajax.php'); ?>')" id="mcform_<?php echo intval($counter);?>" class="cs-mailchimp" method="post">
        
        <div id="newsletter_mess_<?php echo intval($counter);?>" style="display:none" class="cs-error-msg"></div>
        <input id="cs_list_id" type="hidden" name="cs_list_id" value="<?php if(isset($cs_theme_option['cs_mailchimp_list'])){ echo esc_attr($cs_theme_option['cs_mailchimp_list']); }?>" />
        <label><i class="icon-mail"></i></label>
        <input id="mc_email" type="text" name="mc_email" value="<?php _e('Your Email','rental'); ?>" onblur="if(this.value == '') { this.value ='<?php _e('Your Email','rental'); ?>'; }" onfocus="if(this.value =='<?php _e('Your Email','rental'); ?>') { this.value = ''; }"  />
        <input name="submit" id="btn_newsletter_<?php echo intval($counter);?>" type="submit" value="<?php _e('SUBSCRIBE NOW','rental'); ?>">
        <div id="process_<?php echo intval($counter);?>" class="cs-show-msg"></div>     
      
        </form>
        
         
        <?php        
	}
}