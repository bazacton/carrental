<?php

/*
 *
 * @Shortcode Name : Multiple Service
 * @retrun
 *
 */

if (!function_exists('cs_multiple_services_shortcode')) {

    function cs_multiple_services_shortcode($atts, $content = "") {
        $defaults = array(
            'column_size' => '1/1',
            'cs_multiple_service_section_title' => '',
            'multiple_services_element_size' => '',
            'cs_multiple_services_view' => ''
        );

        global $cs_multiple_services_view, $multiple_services_element_size, $slider_counter;
        extract(shortcode_atts($defaults, $atts));
        $column_class = cs_custom_column_class($column_size);
        $cs_section_title = '';
        if (isset($cs_multiple_service_section_title) && trim($cs_multiple_service_section_title) <> '' && $cs_multiple_services_view != 'service-default-three') {
            $cs_section_title = '<div class="cs-section-title"><h2 style="margin-bottom:10px;">' . $cs_multiple_service_section_title . '</h2></div>';
        }

        $html = '';

        if (isset($cs_multiple_services_view) and $cs_multiple_services_view == "service-modren") {

            $html .= '<div class="col-md-12">';
            $html .= '<div class="cs-services box service-modren">';
            $html .= do_shortcode($content);
            $html .= '</div>';
            $html .= '</div>';
        } elseif (isset($cs_multiple_services_view) and $cs_multiple_services_view == "service-classic") {

            $html .= '<div class="col-md-12">';
            $html .= '<div class="cs-services box">';
            $html .= do_shortcode($content);
            $html .= '</div>';
            $html .= '</div>';
        } else {

            $html .= '<div class="cs-services classic">';
            $html .= do_shortcode($content);
            $html .= '</div>';
        }
        return $html;
    }

    if (function_exists('cs_short_code'))
        cs_short_code(CS_SC_MULTPLESERVICES, 'cs_multiple_services_shortcode');
}

/*
 *
 * @Multiple Service Item
 * @retrun
 *
 */

if (!function_exists('cs_multiple_services_item_shortcode')) {

    function cs_multiple_services_item_shortcode($atts, $content = "") {
        $defaults = array(
            'cs_title_color' => '',
            'cs_text_color' => '',
            'cs_bg_color' => '',
            'cs_website_url' => '',
            'cs_multiple_service_title' => '',
            'cs_multiple_service_logo' => '',
            'cs_multi_service_icon' => '',
            'cs_multiple_service_btn' => '',
            'cs_multiple_services_border' => '',
            'cs_fontawsome_color' => '',
            'style_type' => '',
            'cs_multi_service_bg_image' => '',
            'cs_multiple_service_btn_link' => '',
            'cs_multiple_service_btn_bg_color' => '',
            'cs_multiple_service_btn_txt_color' => '',
            'icon_size' => ''
        );
        global $cs_multiple_services_view, $multiple_services_element_size, $slider_counter;
        extract(shortcode_atts($defaults, $atts));
        $html = '';
        $cs_title_color = $cs_title_color <> '' ? ' style="color:' . $cs_title_color . ' !important;"' : '';
        $cs_text_color = $cs_text_color <> '' ? ' style="color:' . $cs_text_color . ' !important;"' : '';
        $cs_bg_color = $cs_bg_color <> '' ? ' style="background-color:' . $cs_bg_color . ' !important;"' : '';
        $cs_multiple_service_btn_color = '';
        if ($cs_multiple_service_btn_txt_color <> '') {
            $cs_multiple_service_btn_color .= 'color:' . $cs_multiple_service_btn_txt_color . ' !important;';
        }
        if ($cs_multiple_service_btn_bg_color <> '') {
            $cs_multiple_service_btn_color .= 'background-color:' . $cs_multiple_service_btn_bg_color . ' !important;';
        }
        $cs_multiple_service_btn_color = $cs_multiple_service_btn_color <> '' ? ' style="'.$cs_multiple_service_btn_color.'"' : '';
        $cs_multiple_service_logo = isset($cs_multiple_service_logo) ? $cs_multiple_service_logo : '';
        $cs_website_url = isset($cs_website_url) ? $cs_website_url : '';
        $cs_multiple_service_btn_link = isset($cs_multiple_service_btn_link) ? $cs_multiple_service_btn_link : '';
        $cs_fontawsome_color = $cs_fontawsome_color <> '' ? ' style="color:' . $cs_fontawsome_color . ' !important;"' : '';
        $cs_multi_service_bg_image = isset($cs_multi_service_bg_image) ? $cs_multi_service_bg_image : '';
        $icon_size = isset($icon_size) ? $icon_size : '';
        
        $style_type = isset($style_type) ? $style_type : '';

        if (isset($style_type) and $style_type == "icon") {

            $cs_image = '<i class="' . $cs_multi_service_icon . ' '.esc_html($icon_size).'"' . $cs_fontawsome_color . '></i>';
        } else {

            $cs_image = '<img src="' . esc_url($cs_multi_service_bg_image) . '" alt="multi_service_bg_image">';
        }


        $html = '';
        if (isset($cs_multiple_services_view) and $cs_multiple_services_view == "service-modren") {

            $html .= '<article class="col-md-4 "  ' . $cs_bg_color . '>';
            $html .= '<div class="inner">';
            $html .= '<figure>';
            $html .= $cs_image;
            $html .= '</figure>';
            $html .= '<div class="text">';
            $html .= '<h5 ' . $cs_title_color . '>' . $cs_multiple_service_title . '</h5>';
            $html .= '<p ' . $cs_text_color . '>' . do_shortcode($content) . '</p>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</article>';
        } elseif (isset($cs_multiple_services_view) and $cs_multiple_services_view == "service-classic") {

            $html .= '<article class="col-md-4"  ' . $cs_bg_color . '>';
            $html .= '<div class="inner">';
            $html .= '<figure>';
            $html .= '<i class="' . $cs_multi_service_icon . ' '.esc_html($icon_size).'" ' . $cs_fontawsome_color . '></i>';
            $html .= '<figcaption>';
            $html .= '<h5 ' . $cs_title_color . '>' . $cs_multiple_service_title . '</h5>';
            $html .= '</figcaption>';
            $html .= '</figure>';
            $html .= '<div class="text">';
            $html .= '<h5>' . $cs_multiple_service_title . '</h5>';
            $html .= '<p ' . $cs_text_color . '>' . do_shortcode($content) . '</p>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</article>';
        } else {

            $html .= '<article class="col-md-4" ' . $cs_bg_color . '>';
            $html .= '<figure>';
            $html .= '<i class="' . $cs_multi_service_icon . ' '.esc_html($icon_size).' "' . $cs_fontawsome_color . '></i>';
            $html .= '</figure>';
            $html .= '<div class="text">';
            $html .= '<h5 ' . $cs_title_color . '>' . $cs_multiple_service_title . '</h5>';
            $html .= '<p ' . $cs_text_color . '>' . do_shortcode($content) . '</p>';
            if ($cs_multiple_service_btn_link <> '') {
                $html .= '<a href="' . esc_url($cs_multiple_service_btn_link) . '" class="read-more"  ' . $cs_multiple_service_btn_color . '>' . $cs_website_url . '</a>';
            }
            $html .= '</div>';
            $html .= '</article>';
        }


        return $html;
    }

    if (function_exists('cs_short_code'))
        cs_short_code(CS_SC_MULTPLESERVICESITEM, 'cs_multiple_services_item_shortcode');
}
?>