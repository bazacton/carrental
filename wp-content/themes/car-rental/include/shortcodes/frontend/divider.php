<?php

/*
 *
 * @Shortcode Name : Divider
 * @retrun
 *
 */
if (!function_exists('cs_divider_shortcode')) {

    function cs_divider_shortcode($atts) {
        $defaults = array(
		'column_size' => '1/1', 
		'divider_style' => 'crossy', 
		'divider_height' => '1', 
		'divider_backtotop' => '', 
		'divider_margin_top' => '', 
		'divider_margin_bottom' => '', 
		'line' => 'Wide', 
		'color' => '#000', 
		'cs_divider_class' => ''
		);
        extract(shortcode_atts($defaults, $atts));
        $column_class = cs_custom_column_class($column_size);
        $html = '';
        $backtotop = '';

         if ($divider_backtotop == 'yes') {
			 
            $backtotop = ' ';
			
        } if ($divider_style == 'plain') {
			
            $divider_style_class = 'cs-seprator';
            $div_html = '<div class="devider3"></div>';
			
        } else if ($divider_style == 'fancy') {
			
            $divider_style_class = 'cs-seprator';
            $div_html = '<div class="devider4"><span><img alt="devider4" src="' . esc_url(get_template_directory_uri()) . '/assets/images/devider4-img.png"></span></div>';
						 
        } else if ($divider_style == 'fancy large') {
            $divider_style_class = 'cs-seprator';
            $div_html = '<div class="devider4 fullwidth">
	                   <span><img alt="devider4" src="' . esc_url(get_template_directory_uri()) . '/assets/images/devider4-img.png">
	                   </span>
                      </div>';
        } else {
            $divider_style_class = 'spreater';
            $div_html = '<div class="fullwidth-sepratore"><div class="dividerstyle"></div></div>';
        }  
		  $cs_divider_class_id = '';
		  
        if ($cs_divider_class <>
                '') 
		{
            $cs_divider_class_id = ' id="' . $cs_divider_class . '"';
        } 
		   $html = '<div class="' . $column_class . ' ' . $cs_divider_class_id . '" style=" margin-top:' . $divider_margin_top . 'px; margin-bottom:' . $divider_margin_bottom . 'px;height:' .$divider_height. 'px;">
	 ';
        if ($divider_style == '3box') {
            $html .= '<div class="box_spreater">';
        }   $html .= '<div class="' . sanitize_html_class($divider_style_class) . '">' . $div_html;
        if ($divider_style != '3box') {
            $html .= $backtotop;
        } $html .= '</div>';
        if ($divider_style == '3box') {
            $html .= $backtotop;
            $html .= '
	</div>
	 ';
        } $html .= '
</div>
';
        return do_shortcode($html);
    }

    if (function_exists('cs_short_code')) cs_short_code(CS_SC_DIVIDER, 'cs_divider_shortcode');
}