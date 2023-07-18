<?php

/*
 *
 * @Shortcode Name : Promobox
 * @retrun
 *
 */
if (!function_exists('cs_promobox_shortcode')) {

    function cs_promobox_shortcode($atts, $content = "") {
        $defaults = array(
            'cs_promobox_section_title' => '',
            'cs_promo_image_url' => '',
            'cs_promobox_title' => '',
            'cs_promobox_contents' => '',
            'cs_promobox_btn_bg_color' => '',
            'cs_promobox_title_color' => '',
            'cs_promobox_background_color' => '',
            'cs_promobox_content_color' => '',
            'cs_link_title' => '', 'text_align' => '',
            'cs_link' => '#',
            'column_size' => '',
            'cs_promobox_class' => '',
            'bg_repeat' => '',
            'text_align' => '',
            'target' => '_self'
        );
        extract(shortcode_atts($defaults, $atts));
        $column_class = cs_custom_column_class($column_size);


        if (isset($bg_repeat) && $bg_repeat == "yes") {
            $repeat = '';
        } else {
            $repeat = 'no-repeat';
        }
        $html = '';
        $section_title = '';
        $background = '';
        $column_size = isset($column_size) ? $column_size : '';
        if ($cs_promobox_section_title && trim($cs_promobox_section_title) != '') {
            $section_title = '<div class="cs-section-title"><h2>' . cs_allow_special_char($cs_promobox_section_title) . '</h2></div>';
        }

        if (isset($cs_promo_image_url) && $cs_promo_image_url != "") {
            $background = 'style="background:url(' . $cs_promo_image_url . ') ' . $repeat . ';';
        }
        if (isset($cs_promobox_background_color) && $cs_promobox_background_color != "") {
            $background = 'style="background:' . $cs_promobox_background_color . '"';
        }
        
        $html .= '<div class="col-md-12">';
        $html .= $section_title;
        $html .= '<div class="promo-box simple" ' . $background . ' text-align:'.$text_align.';">';
        $html .= '<h2 style="color:' . $cs_promobox_title_color . ' !important;">' . cs_allow_special_char($cs_promobox_title) . '</h2>';
        $html .= '<p style="color:' . $cs_promobox_content_color . ' !important;">' . do_shortcode($content) . '</p>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    if (function_exists('cs_short_code'))
        cs_short_code(CS_SC_PROMOBOX, 'cs_promobox_shortcode');
}