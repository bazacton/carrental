<?php
/*
 *
 *@Shortcode Name : FAQ
 *@retrun
 *
 */

if (!function_exists('cs_faq_shortcode')) {
    function cs_faq_shortcode($atts, $content = "") {
        global $acc_counter,$cs_faq_view_title,$cs_faq_view;
        $acc_counter = rand(40, 9999999);
        $html    = '';
        $defaults = array(
		'column_size'=>'1/1', 
		'class' => 'cs-faq',
		'faq_class' => '',
		'cs_faq_section_title'=>'',
		'cs_faq_view'=>'simple'
		);
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
 
        $CustomId = '';
        if ( isset( $faq_class ) && $faq_class ) {
            $CustomId    = 'id="'.$faq_class.'"';
        }
		
         if(isset($cs_faq_section_title) && trim($cs_faq_section_title) <> ''){
			 
            $cs_faq_section_title = '<div class="cs-section-title"><h2>'.$cs_faq_section_title.'</h2></div>';
        }
		
		if(isset($cs_faq_view) && $cs_faq_view == 'simple'){
			
            $faq_view = 'simple';
				  
        } else {	
		
			$faq_view = 'modern';
		}
		 
		 $html ='';
		 $html .='<div class="col-md-12">';
		 $html  .=  $cs_faq_section_title;
		 $html  .= '<div id="accordion-' . $acc_counter . '" class="panel-group cs-default simple">';
         $html  .= do_shortcode($content);
         $html  .= '</div>';
		 $html  .= '</div>';
 
        return $html;
    }
    
    if (function_exists('cs_short_code')) cs_short_code(CS_SC_FAQ, 'cs_faq_shortcode');
}

/*
 *
 *@FAQ Item
 *@retrun
 *
 */
if (!function_exists('cs_faq_item_shortcode')) {
    function cs_faq_item_shortcode($atts, $content = "") {
        global $acc_counter,$faq_animation,$cs_faq_view_title,$cs_faq_view;
        $defaults = array( 'faq_title' => 'Title','faq_active' => 'yes','cs_faq_icon' => '', 'cs_faq_view'=>'view-1');
        extract( shortcode_atts( $defaults, $atts ) );
        $faq_count = 0;
        $faq_count = rand(40, 9999999);
        $html = "";
        $active_in = '';
        $active_class = '';
        $styleColapse = '';
        $styleColapse    = 'collapse collapsed';
		
        if(isset($faq_active) && $faq_active == 'yes'){
			
            $styleColapse    = '';
            $active_in = 'in';
			
        } else {
            $active_class = 'collapsed';
        }
        $cs_faq_icon_class = '';
        if(isset($cs_faq_icon) and $cs_faq_icon <> ''){
            $cs_faq_icon_class = '<i class="'.$cs_faq_icon.'"></i>';
        }		
		if(isset($cs_faq_view) && $cs_faq_view == 'simple'){
            $faq_view = 'simple';
				  
        }else {	
			$faq_view = 'modern';
		}
		
		$html  ='';
		$html .= '<div class="panel panel-default"><div class="panel-heading">';
		$html .= '<h4 class="panel-title"> <a class="collapsed" href="#accordion-'.$faq_count.'" data-parent="#accordion-' . $acc_counter . '" data-toggle="collapse" aria-expanded="false">' . $cs_faq_icon_class . esc_attr($faq_title) . '</a> </h4>';
		$html .= '</div>';
		$html .= '<div role="main" class="panel-collapse collapse '.$active_in.'" id="accordion-'.$faq_count.'" aria-expanded="false" style="height: 0px;">';
		$html .= '<div class="panel-body">';
		$html .= do_shortcode( $content );
	 	$html .= '</div></div></div>';
		return $html;
    }
    if (function_exists('cs_short_code')) cs_short_code(CS_SC_FAQITEM, 'cs_faq_item_shortcode');
}
?>