<?php
if (!function_exists('cs_heading_shortcode')) {

    function cs_heading_shortcode($atts, $content = "") {
		
        $defaults = array(
		'column_size' => '1/1', 
		'heading_title' => '', 
		'color_title' => '', 
		'heading_color' => '#000', 
		'class' => 'cs-heading-shortcode', 
		'heading_style' => '1', 
		'heading_style_type' =>'1', 
		'heading_size' => '', 
		'font_weight' => '', 
		'heading_font_style' => '', 
		'heading_align' => 'center', 
		'heading_divider' => '', 
		'heading_color' => '', 
		'heading_content_color' => ''
		);
        extract(shortcode_atts($defaults, $atts));
        $column_class = cs_custom_column_class($column_size);
        $html = '';
        $css = '';
        $he_font_style = '';
        if ($heading_font_style <> '') {
        	$he_font_style = ' font-style:' . $heading_font_style;
        }
        echo cs_remove_force_tag_blnc_theme($css, false);
		
        $cs_heading_class = (isset($heading_style) and $heading_style == 'fancy') ? 'main-heading top-center' : '';
		$html .= '<div class="cs-heading-style '.$cs_heading_class.'">';
	    if ($heading_style == 'fancy') {

            if ($color_title <> '') {
                $color_title = '&nbsp;<span class="cs-color">' . $color_title . '</span>';
            }
               $html .= '<h1 style="color:' . $heading_color . ' !important; font-size: ' . $heading_size . 'px !important; text-align: ' . $heading_align. ';' . $he_font_style . ';">';
			   
              $html .=$heading_title . $color_title . '</h1>';
			  
        } else {
			
            if ($heading_title <> '') {
          if ($color_title <> '') {
              $color_title = '&nbsp;<span class="cs-color">' . $color_title . '</span>';
			  
                }
				
             $html .= '<h' . $heading_style . ' style="color:' . $heading_color . ' !important; font-size: ' . $heading_size . 'px !important; text-align:' . $heading_align . ';' . $he_font_style . ';">';
             $html .=$heading_title . $color_title . '</h' . $heading_style . '>';
            }
        }
        if ($content <> '') {
            $html .= '<div class="heading-description" style="color:' . $heading_content_color . ' !important; text-align: ' . $heading_align . ';' . $he_font_style . ';">' . do_shortcode($content) . '</div>';
        }
        
           $html .= '</div>';
           return do_shortcode($html);
    }
    if (function_exists('cs_short_code')) cs_short_code(CS_SC_HEADING, 'cs_heading_shortcode');
}