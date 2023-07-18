<?php
/*
 *
 *@Shortcode Name : Infobox
 *@retrun
 *
 */

if (!function_exists('cs_infobox_shortcode')) {
    function cs_infobox_shortcode($atts, $content = "") {
        global $cs_infobox_list_text_color;
        $defaults = array(
		'column_size'=>'1/1', 
		'cs_infobox_section_title' => '', 
		'cs_infobox_title' => '',
		'cs_infobox_bg_color' => '',
		'cs_infobox_list_text_color'=>'',
		'cs_infobox_class' => ''
		);
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        
        $CustomId    = '';
        if ( isset( $cs_infobox_class ) && $cs_infobox_class ) {
            $CustomId    = 'id="'.$cs_infobox_class.'"';
        }
        
        $html             = '';
        $cs_infobox_list_text_color_style = '';
        if($cs_infobox_list_text_color != ''){
            $cs_infobox_list_text_color_style = 'style="color: '.$cs_infobox_list_text_color.' !important;"';
        }
        $section_title = '';
        if ($cs_infobox_section_title && trim($cs_infobox_section_title) !='') {
            $section_title    = '<h2>'.$cs_infobox_section_title.'</h2>';
        }
        $cs_infobox_bg_color_style = '';
        if($cs_infobox_bg_color != ''){
            $cs_infobox_bg_color_style = 'style="background-color: '.$cs_infobox_bg_color.'"';
        }
        
       
        
         $html     .= '<div class="cs-contact-info"  '.$cs_infobox_bg_color_style.'>';
		 $html    .= $section_title;
            
            if($cs_infobox_title != ''){
                $html    .= '<h3 '.$cs_infobox_list_text_color_style.'>'.$cs_infobox_title.'</h3>';
            }
            $html    .= '<div class="liststyle">';
                $html    .= '<ul>';
                    $html    .= do_shortcode($content);
                $html    .= '</ul>';
            $html    .= '</div>';
        $html    .= '</div>';
        return '<div '.$CustomId.' class="'.$column_class.'">' . $html . '</div>';
    }
    if (function_exists('cs_short_code')) cs_short_code(CS_SC_INFOBOX, 'cs_infobox_shortcode');
}

/*
 *
 *@Infobox Item
 *@retrun
 *
 */
if (!function_exists('cs_infobox_item_shortcode')) {
    function cs_infobox_item_shortcode($atts, $content = "") {
        global $cs_infobox_list_text_color;
        $defaults = array('cs_infobox_list_icon'=>'','cs_infobox_list_color'=>'','cs_infobox_list_title'=>'');
        extract( shortcode_atts( $defaults, $atts ) );
        $html = '<li>';
            $cs_infobox_icon_color_style = '';
            $cs_infobox_list_text_color_style = '';
            if($cs_infobox_list_color != ''){
                $cs_infobox_icon_color_style = 'style="color: '.$cs_infobox_list_color.'"';
            }
            if($cs_infobox_list_text_color != ''){
                $cs_infobox_list_text_color_style = 'style="color: '.$cs_infobox_list_text_color.' !important;"';
            }
            if($cs_infobox_list_icon != ''){
                $html    .= '<i class="'.$cs_infobox_list_icon.'" '.$cs_infobox_icon_color_style.'></i>';
            }
            if($cs_infobox_list_title != ''){
				
              $html    .= '<span>'.$cs_infobox_list_title.'</span>';
			  
            }
            if($content != ''){
                $html    .= '<p'.$cs_infobox_list_text_color_style.'>'.do_shortcode($content).'</p>';
            }

        $html    .= '</li>';
        
        return $html;
    }
    if (function_exists('cs_short_code')) cs_short_code(CS_SC_INFOBOXITEM, 'cs_infobox_item_shortcode');
}
?>
