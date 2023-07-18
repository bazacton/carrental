<?php

/*
 *
 * @Shortcode Name : Progressbar
 * @retrun
 *
 */

if (!function_exists('cs_progressbars_shortcode')) {

    function cs_progressbars_shortcode($atts, $content = "") {
        global $cs_progressbars_style;
        $defaults = array(
            'column_size' => '1/1',
            'cs_progressbars_style' => 'skills-sec',
            'progressbars_class' => ''
        );
        extract(shortcode_atts($defaults, $atts));
        $column_class = cs_custom_column_class($column_size);
        $CustomId = '';
        if (isset($progressbars_class) && $progressbars_class) {
            $CustomId = 'id="' . $progressbars_class . '"';
        }

        cs_skillbar_script();

        $output = '';
        
        $output .= '<div ' . $CustomId . ' class="' . $column_class . '"><div class="skills-element ">';
        $output .= do_shortcode($content);
        $output .= '</div></div>';
        return $output;
    }

    if (function_exists('cs_short_code'))
        cs_short_code(CS_SC_PROGRESSBAR, 'cs_progressbars_shortcode');
}

if (!function_exists('cs_progressbar_item_shortcode')) {

    function cs_progressbar_item_shortcode($atts, $content = "") {
        global $cs_progressbars_style;
        $defaults = array('progressbars_title' => '', 'progressbars_color' => '#4d8b0c', 'progressbars_percentage' => '50');
        extract(shortcode_atts($defaults, $atts));
        $output = '';
        $output_title = '';
        $progressbars_style_class = '';
        $heading_size = 'h5';

        $progressbars_bar_class = '';

        if (isset($progressbars_title) && $progressbars_title <> '') {
            $output_title .= '<' . $heading_size . '>' . $progressbars_title . '</' . $heading_size . '>';
        }
        if (isset($progressbars_percentage) && $progressbars_percentage <> '') {

            $output .= '<div class="skills-sec ' . $progressbars_bar_class . '">';

            $output .=$output_title
                    . '<div class="skills-sec"  data-percent="' . $progressbars_percentage . '%">'
                    . '<div class="skillbar-bar" style="background: ' . $progressbars_color . ' !important;width:0%;">'
                    . '<small>' . $progressbars_percentage . '%</small>'
                    . '</div>'
                    . '</div>';
            $output .= '</div>';
        }
        return $output;
    }

    if (function_exists('cs_short_code'))
        cs_short_code(CS_SC_PROGRESSBARITEM, 'cs_progressbar_item_shortcode');
}
?>