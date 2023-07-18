<?php
/**
 *@Shortcode Name : Image Freame
 *@retrun
 *
 */
if (!function_exists('cs_image_shortcode')) {
    function cs_image_shortcode($atts, $content = "") {
   $defaults = array( 
         'column_size'=>'',
		 'cs_image_section_title' => '',
		 'image_style' => '',
		 'cs_image_url' => '#',
		 'cs_image_title' => '',
		 'cs_image_caption' => '',
		 'cs_image_custom_class'=>''
		 );
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class = cs_custom_column_class($column_size);
 
        $CustomId    = '';
        if ( isset( $cs_image_custom_class ) && $cs_image_custom_class ) {
            $CustomId    = 'id="'.$cs_image_custom_class.'"';
        }

        $html = '';
        $section_title = '';
        
        if ($cs_image_section_title && trim($cs_image_section_title) !='') {
            $section_title    = '<div class="cs-section-title"><h2>'.$cs_image_section_title.'</h2></div>';
        }        
        $column_class     = cs_custom_column_class($column_size);
        $image_border_cls = (isset($image_style) and $image_style == 'frame-classic') ? 'has-shadow' : '';
		$image_modren_cls = (isset($image_style) and $image_style == 'frame-plane') ? 'cs-modren' : '';
        $html  .= '<article '.$CustomId.' class="image-frame cs-img-frame '.$image_style.' '.$image_border_cls.' '.$image_modren_cls.'">';
       
        if( isset( $cs_image_url ) && $cs_image_url !='' ) {
            $html .= '<figure> <img alt="cs_image_url" src="'.esc_url($cs_image_url).'"> </figure>';
        }        
        $html .= '<section>';
        
        if( isset( $cs_image_title ) && $cs_image_title !='' ) {
            $html    .= '<h4>'.$cs_image_title.'</h4>';
        } 
        if( isset( $content ) && $content !='' ) {
            $html    .= ''.do_shortcode($content).'';
        }        
        $html .= '</section>';
        $html .= '</article>';
		
		$html = '<div '.$CustomId.' class="' . $column_class . ' ' . $cs_image_custom_class . '">'.$section_title.' '.$html.'</div>';

        return do_shortcode($html);
    }
    if (function_exists('cs_short_code')) cs_short_code(CS_SC_IMAGE, 'cs_image_shortcode');
}