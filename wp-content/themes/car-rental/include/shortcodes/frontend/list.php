<?php
/*
 *
 * @File : List
 * @retrun
 *
 */
if (!function_exists('cs_list_shortcode')) {

    function cs_list_shortcode($atts, $content = "") {
        global $cs_border, $cs_list_type;
        $defaults = array(
			 'column_size' =>'', 
			 'cs_list_section_title' =>'',
			 'cs_list_type' =>'', 
			 'cs_list_icon' =>'', 
			 'cs_border' =>'', 
			 'cs_list_item' =>'', 
			 'cs_list_class' =>''
			 
			 );
        extract(shortcode_atts($defaults, $atts));
        $customID = '';
        if (isset($column_size) && $column_size != '') {
            $column_class = cs_custom_column_class($column_size);
        } else {
            $column_class = '';
        }
        if (isset($cs_list_class) && $cs_list_class != '') {
            $customID = 'id="' . $cs_list_class . '"';
        }
        $html = "";
        $cs_list_typeClass = '';
        $section_title = '';
        if ($cs_list_section_title && trim($cs_list_section_title) != '') {
            $section_title = '<div class="cs-section-title"><h2>' . $cs_list_section_title . '</h2></div>';
        }
        $cs_list_type = $cs_list_type ? $cs_list_type : 'cs-bulletslist';
        if ($cs_list_type == 'none') {
            $cs_list_typeClass = 'cs-unorderedlist';
        } else if ($cs_list_type == 'icon') {
            $cs_list_typeClass = 'cs-iconlist';
        } else if ($cs_list_type == 'built') {
            $cs_list_typeClass = 'cs-bulletslist';
        } else if ($cs_list_type == 'decimal') {
            $cs_list_typeClass = 'cs-number-list';
        } else if ($cs_list_type == 'alphabetic') {
            $cs_list_typeClass = 'cs-upper-alphalist';
        } else if ($cs_list_type == 'numeric-icon') {
            $cs_list_typeClass = 'cs-num-iconlist';
        }
		
		 if ($cs_list_type == 'terms') {
            $liststyle = 'terms-conditions';
        }else{
			 $liststyle = 'liststyle';
		}
		
        $html .= '
       <div ' . $customID . ' class="' . $column_class . '  ' . $cs_list_class . '">';
        $html .= $section_title;
        $html .= '<div class="'.$liststyle.'">';
        $html .= '<ul class="' . $cs_list_typeClass . '">';
        $html .= do_shortcode($content);
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    if (function_exists('cs_short_code')) cs_short_code(CS_SC_LIST, 'cs_list_shortcode');
}

if (!function_exists('cs_list_item_shortcode')) {

    function cs_list_item_shortcode($atts, $content = "") {
        global $cs_border, $cs_list_type;
        $html = '';
        $defaults = array('cs_list_icon' =>
            '', 'cs_list_item' =>
            '', 'cs_cusotm_class' =>
            '', 'cs_custom_animation' =>
            '', 'cs_custom_animation' =>
            '');
        extract(shortcode_atts($defaults, $atts));
        if ($cs_border == 'yes') {
            $border = 'has_border';
        } else {
            $border = '';
        }
        if ($cs_list_icon && $cs_list_type == 'icon') {
            $html .= '<li class="' . $border . '"><i class="' . $cs_list_icon . '"></i>' . do_shortcode(esc_html($content)) . '</li>';
        } else if ($cs_list_icon && $cs_list_type == 'numeric-icon') {
            $html .= '<li class="' . $border . '">' . do_shortcode(esc_html($content)) . '<i class="cs-color ' . $cs_list_icon . '"></i></li>';
        } else {
            $html .= '<li class="' . $border . '">' . do_shortcode(esc_html($content)) . '</li>';
        }
        return $html;
    }

    if (function_exists('cs_short_code')) cs_short_code(CS_SC_LISTITEM, 'cs_list_item_shortcode');
}