<?php

/*
 *
 * @Shortcode Name : Price Table
 * @retrun
 *
 */

if (!function_exists('cs_pricetable_shortcode')) {

    function cs_pricetable_shortcode($atts, $content = "") {
        global $pricetable_style;

        $defaults = array(
            'column_size' => '1/1',
            'pricetable_style' => '',
            'pricetable_style' => '',
            'pricetable_title' => '',
            'pricetable_title_bgcolor' => '',
            'pricetable_price' => '',
            'currency_symbols' => '',
            'pricetable_period' => '',
            'pricetable_bgcolor' => '',
            'btn_text' => '',
            'feature_style' => '',
            'cs_price_icon' => '',
            'cs_btn_text' => '',
            'btn_link' => '',
            'btn_txt_color' => '',
            'pricetable_featured' => '',
            'pricetable_class' => '',
            'button_bg_color' => ''
        );
        extract(shortcode_atts($defaults, $atts));
        $column_class = cs_custom_column_class($column_size);
        $CustomId = '';
        $background = '';
        if (isset($feature_style) and $feature_style == "no") {
            if ($pricetable_title_bgcolor <> '') {
                $background = 'style="background-image:url(' . $pricetable_title_bgcolor . ') ;"';
            }
        }

        if (isset($pricetable_class) && $pricetable_class) {
            $CustomId = 'id="' . $pricetable_class . '"';
        }


        $cs_btn_text = isset($cs_btn_text) ? $cs_btn_text : '';
        $pricetable_title_bgcolor = isset($pricetable_title_bgcolor) ? $pricetable_title_bgcolor : '';
        $pricetable_title = isset($pricetable_title) ? $pricetable_title : '';
        $currency_symbols = isset($currency_symbols) ? $currency_symbols : '';
        $pricetable_style = isset($pricetable_style) ? $pricetable_style : '';
        $cs_price_icon = isset($cs_price_icon) ? $cs_price_icon : '';
        $feature_style = isset($feature_style) ? $feature_style : '';

        if (isset($feature_style) and $feature_style == "yes") {
            $feature = 'featured';
        } else {
            $feature = '';
        }

        $html = '';
        $html .= '<div class="cs-price-table classic">';
        $html .= '<article class="' . $feature . '" ' . $background . '>';
        $html .= '<figure>';
        $html .= '<i class="' . $cs_price_icon . '"></i>';
        $html .= '<figcaption>';
        $html .= '<span>' . $pricetable_title . '</span>';
        $html .= '</figcaption>';
        $html .= '</figure>';
        $html .= '<div class="cs-price">';
        $html .= '<p>';
        $html .= '<span>' . $currency_symbols . '</span>';
        $html .= $pricetable_price;
        $html .= '</p>';
        $html .= '</div>';
        $html .= '<div class="features">';
        $html .= '<a href="' . esc_url($btn_link) . '" class="sign-btn" style=" color: ' . $btn_txt_color . '; background: ' . $button_bg_color . ';">' . $btn_text . '</a>';
        $html .= '<ul>';
        $html .= do_shortcode($content);
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</article>';
        $html .= '</div>';


        return '<div ' . $CustomId . ' class="' . $column_class . '">' . $html . '</div>';
    }

    if (function_exists('cs_short_code')) {
        cs_short_code(CS_SC_PRICETABLE, 'cs_pricetable_shortcode');
    }
}

/*
 *
 * @Price Table Item
 * @retrun
 *
 */
if (!function_exists('cs_pricing_item')) {

    function cs_pricing_item($atts, $content = "") {
        global $pricetable_style;
        $defaults = array('pricing_feature' => '');
        extract(shortcode_atts($defaults, $atts));
        $html = '';
        $priceCheck = '';
        if ($pricetable_style == 'classic' || $pricetable_style == 'clean') {
            $priceCheck = '';
        }

        if (isset($pricing_feature) && $pricing_feature != '') {
            $html .= '<li>' . $pricing_feature . '</li>';
        }

        return $html;
    }

    if (function_exists('cs_short_code')){
        cs_short_code(CS_SC_PRICETABLEITEM, 'cs_pricing_item');
    }
}