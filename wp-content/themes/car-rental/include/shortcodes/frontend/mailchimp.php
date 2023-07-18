<?php
/*
 *
 *@Shortcode Name : Mail Chimp
 *@retrun
 *
 */

if (!function_exists('cs_mailchimp_shortcode')) {
    function cs_mailchimp_shortcode($atts, $content = "") {
        global $mailchimp_style;
         $defaults = array( 'column_size'=>'1/1','cs_mailchimp_sec_title'=> '','cs_mailchimp_title'=> 'Login for Newsletter','cs_mailchimp_subtitle' => '','cs_mailchimp_bg_color' => '#323536','cs_mailchimp_txt_color' => '#ffffff','cs_mailchimp_text'=> 'Stay as productive on the road as you are in your office with our fantastic services.');

        extract( shortcode_atts( $defaults, $atts ) );
		
		$column_class = cs_custom_column_class($column_size);
		 
		$section_title = '';
        if(isset($cs_mailchimp_title) && trim($cs_mailchimp_title) <> ''){
            $section_title = $cs_mailchimp_title;
        }
		
		$cs_mailchimp_bg_color = $cs_mailchimp_bg_color <> '' ? ' style="background-color:'.$cs_mailchimp_bg_color.';"' : '';
		$cs_mailchimp_txt_color = $cs_mailchimp_txt_color <> '' ? ' style="color:'.$cs_mailchimp_txt_color.';"' : '';
		
		if(isset($cs_mailchimp_subtitle) && trim($cs_mailchimp_subtitle) <> ''){
            $cs_subtitle = '<h3'.$cs_mailchimp_txt_color.'>'.$cs_mailchimp_subtitle.'</h3>';
        }
		ob_start();
		if(isset($cs_mailchimp_sec_title) && trim($cs_mailchimp_sec_title) <> ''){
            ?>
            <div class="cs-section-title col-md-12">
                <h2><?php echo cs_allow_special_char($cs_mailchimp_sec_title); ?></h2>
            </div>
            <?php
        }
		?>
        <div class="<?php echo sanitize_html_class($column_class); ?>">
            <div class="cs-mailchimp">
                 <div class="user-signup cs-newsletter"<?php echo cs_allow_special_char($cs_mailchimp_bg_color); ?>>
                        <span class="news-title"><?php echo cs_allow_special_char($section_title); ?></span>
                           <?php if(isset( $cs_subtitle)) {  echo cs_allow_special_char($cs_subtitle); } ?>
                           <?php echo '<p'.$cs_mailchimp_txt_color.'>'.do_shortcode($content).'</p>'; ?>
                           <?php   cs_custom_mailchimp(); ?>	
                  </div>
            </div>
        </div>
        <?php
		$html = ob_get_clean();
        return $html;
    }
    if (function_exists('cs_short_code')) cs_short_code(CS_SC_MAILCHIMP, 'cs_mailchimp_shortcode');
}

?>