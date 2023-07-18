<?php

/*
 *
 * @Shortcode Name : Clients
 * @retrun
 *
 */

if (!function_exists('cs_clients_shortcode')) {

    function cs_clients_shortcode($atts, $content = "") {
        global $cs_clients_view, $cs_client_border, $cs_client_gray,$grayScale;
        $grayScale = isset($grayScale) ? $grayScale : '';
        $defaults = array(
            'column_size' => '',
            'cs_clients_view' => 'Grid View',
            'cs_client_gray' => 'Yes',
            'cs_client_border' => 'Yes',
            'cs_client_head_style' => 'heading-style-1',
            'cs_client_section_title' => '',
            'cs_client_class' => ''
        );
        extract(shortcode_atts($defaults, $atts));

        $CustomId = '';
        if (isset($cs_client_class) && $cs_client_class) {
            $CustomId = 'id="' . $cs_client_class . '"';
        }

        $column_class = cs_custom_column_class($column_size);
        $cs_client_border = $cs_client_border == 'yes' ? 'has_border' : 'no-clients-border';
        $owlcount = rand(40, 9999999);
        $section_title = isset($cs_client_section_title) ? $cs_client_section_title : '';



        $html = '';
        $html .= '<div ' . $CustomId . ' class="' . $column_class . ' ' . $cs_client_class . '">';
        $html .= '<div class="cs-partner  ' . $cs_client_border . '">';
        $html .= '<div class="cs-section-title">';
        $html .= '<h2>' . $cs_client_section_title . '</h2>';
        $html .= '</div>';
        $html .= '<ul class="row">';
        $html .= do_shortcode($content);
        $html .= '</ul>';
        $html .= '</div>';

        $html .= '</div>';
        return $html;
    }

    if (function_exists('cs_short_code'))
        cs_short_code(CS_SC_CLIENTS, 'cs_clients_shortcode');
}

/*
 *
 * @Clinets Item
 * @retrun
 *
 */
if (!function_exists('cs_clients_item_shortcode')) {

    function cs_clients_item_shortcode($atts, $content = "") {
        global $cs_clients_view, $cs_client_border, $cs_client_gray,$grayScale;
        $defaults = array('cs_bg_color' => '', 'cs_website_url' => '', 'cs_client_title' => '', 'cs_client_logo' => '');
        extract(shortcode_atts($defaults, $atts));
        $html = '';
        
        $grayScale = (isset($cs_client_gray) && $cs_client_gray == 'yes') ? 'grayscale' : '';
        

        $cs_url = $cs_website_url ? $cs_website_url : 'javascript:;';
        if (isset($cs_client_logo) && !empty($cs_client_logo)) {

            $html .= '<li class="col-md-2" style="background-color:' . $cs_bg_color . '"><figure><a href="' . esc_url($cs_url) . '"><img class="' . ($grayScale) . '" title="'.$cs_client_title.'" alt="' . $cs_client_title . '" src="' . esc_url($cs_client_logo) . '" ></a></figure></li>';
        }
        return $html;
    }

    if (function_exists('cs_short_code'))
        cs_short_code(CS_SC_CLIENTSITEM, 'cs_clients_item_shortcode');
}