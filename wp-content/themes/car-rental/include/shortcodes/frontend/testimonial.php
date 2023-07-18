<?php

/*
 *
 * @Shortcode Name : Testimonial
 * @retrun
 *
 */

if (!function_exists('cs_testimonials_shortcode')) {

    function cs_testimonials_shortcode($atts, $content = null) {
        global $testimonial_style, $cs_testimonial_class, $column_class, $testimonial_text_color, $section_title;
        $randomid = rand(0, 999);
        $defaults = array('column_size' => '1/1', 'testimonial_style' => '', 'testimonial_text_color' => '', 'cs_testimonial_text_align' => '', 'cs_testimonial_section_title' => '', 'cs_testimonial_class' => '');
        extract(shortcode_atts($defaults, $atts));
        $column_class = cs_custom_column_class($column_size);
        $html = '';
        $section_title = '';

        $html = '';
        $html .= '<div class="cs-testimonials">';
        $html .= '<div class="col-md-12">';
        $html .= '<div class="cs-section-title">';
        $html .= '<h2>' . $cs_testimonial_section_title . '</h2>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= do_shortcode($content);
        $html .= '</div>';

        return $html;
    }

    if (function_exists('cs_short_code'))
        cs_short_code(CS_SC_TESTIMONIALS, 'cs_testimonials_shortcode');
}
/*
 *
 * @Shortcode Name : Testimonial Item
 * @retrun
 *
 */
if (!function_exists('cs_testimonial_item')) {

    function cs_testimonial_item($atts, $content = null) {
        global $testimonial_style, $cs_testimonial_class, $column_class, $testimonial_text_color;
        $defaults = array('testimonial_author' => '', 'testimonial_img' => '', 'cs_testimonial_text_align' => '', 'testimonial_company' => '');
        extract(shortcode_atts($defaults, $atts));
        $figure = '';
        $html = '';


        if (isset($testimonial_img) && $testimonial_img <> '') {
            $testimonial_img_id = cs_get_attachment_id_from_url($testimonial_img);
            $width = 150;
            $height = 150;
            $testimonial_img_url = cs_attachment_image_src($testimonial_img_id, $width, $height);
            $figure = '';

            if ($testimonial_img_url <> '') {
                $figure = '<figure> <img src="' . esc_url($testimonial_img_url) . '" alt="testimonial_img_url" /></figure>';
            }
        }



        $tc_color = '';
        $html = '';
        $html .= '<article class="col-md-6">';
        $html .= '<div class="text">';
        $html .= '<p ' . $tc_color . '>' . do_shortcode($content) . ' </p>';
        $html .= '</div>';
        $html .= '<div class="cs-author">';
        $html .= $figure;
        $html .= '<h5>' . $testimonial_author . '</h5>';
        $html .= '<span>' . $testimonial_company . '</span>';
        $html .= '</div>';
        $html .= '</article>';


        return $html;
    }

    if (function_exists('cs_short_code'))
        cs_short_code(CS_SC_TESTIMONIALSITEM, 'cs_testimonial_item');
}