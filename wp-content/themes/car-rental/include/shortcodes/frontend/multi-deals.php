<?php
/*
*
*@Shortcode Name : Multiple Service
*@retrun
*
*/

if (!function_exists('cs_multiple_deals_shortcode')){
  global $post,$column_attributes;
    function cs_multiple_deals_shortcode($atts, $content = "") {
        $defaults = array(
            'column_size' => '1/1',
           	'cs_multiple_deals_section_title' => ''
           
        );

        global $cs_multiple_deals_view, $multiple_deals_element_size,$column_attributes,$slider_counter,$cs_class;
        extract(shortcode_atts($defaults, $atts));
        $column_class = cs_custom_column_class($column_size);
       $cs_multiple_deals_section_title = isset($cs_multiple_deals_section_title) ? $cs_multiple_deals_section_title : '';
    
     $cs_page_layout	= get_post_meta(get_the_ID(),'cs_page_layout',true);
     $cs_page_sidebar_right= get_post_meta(get_the_ID(),'cs_page_sidebar_right',true);
     $cs_page_sidebar_left	= get_post_meta(get_the_ID(),'cs_page_sidebar_left',true);
     $cs_section_layout = $column_attributes->cs_layout;
 	 
  if($cs_page_layout == 'left' || $cs_page_layout == 'right') {
	  
     $cs_class = 'element-size-50';
	 
    } elseif($cs_section_layout == 'left' || $cs_section_layout == 'right' ){
		
		 $cs_class = 'element-size-50';
		 
	} else {
		
	 $cs_class = 'element-size-33';
     }
 
		$html    = '';
	 	$html   .= '<div class="col-md-12">';
		$html   .= '<div class="cs-section-title">';
		$html   .= '<h2>'.$cs_multiple_deals_section_title.'</h2>';
		$html   .= '</div>';
	 	$html   .= '</div>';
		$html   .= '<div class="our-deals">';
	 	$html   .= do_shortcode($content);
		$html   .= '</div>';
	
	   return $html;
	   
	  }

    if (function_exists('cs_short_code')) cs_short_code(CS_SC_MULTPLEDEALS, 'cs_multiple_deals_shortcode');
	
    }

/*
*
*@Multiple Service Item
*@retrun
*
*/

if (!function_exists('cs_multiple_deals_item_shortcode'))
    {
    function cs_multiple_deals_item_shortcode($atts, $content = "")
        {
       $defaults = array(
	   'cs_title_color'=>'',
	   'cs_text_color'=>'',
	   'cs_bg_color'=>'',
	   'cs_website_url'=>'',
	   'cs_multiple_deals_title'=>'',
	   'cs_multiple_deals_logo'=>'',
	   'cs_multiple_deals_btn'=>'',
	   'cs_multiple_deals_btn_link'=>'',
	   'cs_multiple_deals_btn_bg_color'=>'',
	   'cs_multiple_deals_btn_txt_color'=>'',
	   'cs_multi_deals_bg_image'=>'',
	   'cs_fontawsome_color'=>'',
	   'cs_multiple_from'=>'',
	   'cs_multi_deals_icon' => ''
	   );
        global $cs_multiple_deals_view, $multiple_deals_element_size,$slider_counter,$cs_class;
		
        extract(shortcode_atts($defaults, $atts));
		
	   $cs_multi_deals_bg_image = isset($cs_multi_deals_bg_image) ? $cs_multi_deals_bg_image : '';
	   $cs_multiple_deals_title = isset($cs_multiple_deals_title) ? $cs_multiple_deals_title : '';
	   $cs_multiple_from = isset($cs_multiple_from) ? $cs_multiple_from : '';
	   $cs_website_url = isset($cs_website_url) ? $cs_website_url : '';
	   $cs_multiple_deals_btn_link = isset($cs_multiple_deals_btn_link) ? $cs_multiple_deals_btn_link : '';
	 
        $html  = '';
		$html .= '<div class="'.$cs_class.'">';
		$html .= '<div class="col-md-12">';
		$html .= '<article>';
		$html .= '<figure>';
		$html .= '<img src="'.esc_url($cs_multi_deals_bg_image).'" alt="#">';
		$html .= '<figcaption>';
		$html .= '<div class="caption-inner">';
		$html .= '<p ' . $cs_text_color . '>' .do_shortcode($content).'</p>';
		$html .= '</div>';
		$html .= '</figcaption>';
		$html .= '</figure>';
		$html .= '<div class="text">';
		$html .= '<h6>'.$cs_multiple_deals_title.'</h6>';
		$html .= '<div class="price-sec">';
		$html .= '<span>'.$cs_multiple_from.'</span>';
		$html .= '<p>';
		$html .= '<span>'.$cs_website_url.'</span>';
		$html .= ''.$cs_multiple_deals_btn_link.'';
		$html .= '</p>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</article>';
		$html .= '</div>';
		$html .= '</div>';

	 	
       return $html;
	   
        }

    if (function_exists('cs_short_code')) cs_short_code(CS_SC_MULTPLEDEALSITEM, 'cs_multiple_deals_item_shortcode');
    }

?>