<?php
/*
 *
 *@File : Call to action
 *@retrun
 *
 */	
if (!function_exists('cs_call_to_action_shortcode')) {
    function cs_call_to_action_shortcode($atts, $content = "") {
        $defaults = array(
		'column_size' => '1/1',
		'cs_call_to_action_section_title'=>'',
		'cs_content_type'=>'',
		'cs_call_action_title'=>'',
		'cs_call_action_contents'=>'',
		'cs_contents_color'=>'', 
		'cs_call_action_icon'=>'',
		'cs_icon_color'=>'#FFF',
		'cs_call_to_action_icon_background_color'=>'',
		'cs_call_to_action_button_text'=>'',
		'cs_call_to_action_button_link'=>'#',
		'cs_call_to_action_bg_img'=>'',
		'animate_style'=>'slide',
		'class'=>'cs-article-box',
		'cs_call_to_action_class'=>''
		);
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        $cell_button = '';
        $CustomId    = '';

		$cs_call_to_action_button_text = isset($cs_call_to_action_button_text) ? $cs_call_to_action_button_text : '';
		$cs_call_to_action_button_link = isset($cs_call_to_action_button_link) ? $cs_call_to_action_button_link : '';
		
	    if ( isset( $cs_call_to_action_class ) && $cs_call_to_action_class ) {
            $CustomId    = 'id="'.$cs_call_to_action_class.'"';
        }
        
        $section_title = '';
        if(isset($cs_call_to_action_section_title) && trim($cs_call_to_action_section_title) <> ''){
            $section_title = '<div class="cs-section-title"><h2 class="">'.$cs_call_to_action_section_title.'</h2></div>';
        }
		
        if(isset($cs_call_action_title) && trim($cs_call_action_title) <> ''){
            $cs_call_action_title = '<h1 style="color:'.$cs_contents_color.' !important;">'.$cs_call_action_title.'</h1>';
        }
		
        $image = '';
        if (trim($cs_call_to_action_bg_img)) {
            $image    = 'background-image:url('.$cs_call_to_action_bg_img.');';
        }
       
    $html = '<div class="call-actions ac-modren">
            <div class="col-md-12">
             <div class="inner-sec" style="background-image: url('.$cs_call_to_action_bg_img.'); background-size:cover; background-repeat:no-repeat;">
              <div class="cell heading">
               <div class="ac-text">
              	<h1>'.$cs_call_to_action_section_title.'</h1>
                <p>'.do_shortcode($content).'</p>
               </div>
              </div>
              <div class="cell call-btn">
               <a href="'.esc_url($cs_call_to_action_button_link).'">'.$cs_call_to_action_button_text.'</a>
              </div>
             </div>
            </div>
           </div>';
		    $html ='<div class="section-fullwidth">
           <div class="call-actions ac-modren">
            <div class="col-md-12">
			 '.$section_title.'
             <div style="background: url('.$cs_call_to_action_bg_img.') no-repeat 0 0 / cover '.$cs_call_to_action_icon_background_color.';" class="inner-sec">
              <div class="cell heading">
               <div class="ac-text">
              	'.$cs_call_action_title.'
                <p style="color:'.$cs_contents_color.';">'.do_shortcode($content).'</p>
               </div>
              </div>
              <div class="cell call-btn">
               <a href="'.esc_url($cs_call_to_action_button_link).'">'.$cs_call_to_action_button_text.'</a>
              </div>
             </div>
            </div>
           </div>
          </div>'; 
        return '<div ' . $CustomId . ' class="' . sanitize_html_class($column_class) . '">' . $html . '</div>';
    }
    if (function_exists('cs_short_code')) cs_short_code(CS_SC_CALLTOACTION, 'cs_call_to_action_shortcode');
}