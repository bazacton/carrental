<?php

/*
 *
 * @Shortcode Name : Teams
 * @retrun
 *
 */
if (!function_exists('cs_teams_shortcode')) {

    function cs_teams_shortcode($atts, $content = "") {
        $defaults = array('column_size' => '1/1', 'cs_team_section_title' => '', 'cs_team_name' => '', 'cs_team_designation' => '', 'cs_team_title' => '', 'cs_team_profile_image' => '', 'cs_team_fb_url' => '', 'cs_team_twitter_url' => '', 'cs_team_googleplus_url' => '', 'cs_team_linkedin_url' => '', 'cs_team_skype_url' => '', 'cs_team_email' => '');
        extract(shortcode_atts($defaults, $atts));
        $column_class = cs_custom_column_class($column_size);

        $cs_team_name = isset($cs_team_name) ? $cs_team_name : '';
        $cs_team_profile_image = isset($cs_team_profile_image) ? $cs_team_profile_image : '';
        $cs_team_fb_url = isset($cs_team_fb_url) ? $cs_team_fb_url : '';
        $cs_team_twitter_url = isset($cs_team_twitter_url) ? $cs_team_twitter_url : '';
        $cs_team_linkedin_url = isset($cs_team_linkedin_url) ? $cs_team_linkedin_url : '';
        $cs_team_googleplus_url = isset($cs_team_googleplus_url) ? $cs_team_googleplus_url : '';
        $cs_team_email = isset($cs_team_email) ? $cs_team_email : '';
        $cs_team_designation = isset($cs_team_designation) ? $cs_team_designation : '';
        $cs_team_skype_url = isset($cs_team_skype_url) ? $cs_team_skype_url : '';


        $section_title = '';
        if (trim($cs_team_section_title) <> '') {
            $section_title = '<div class="cs-section-title"><h2>' . $cs_team_section_title . '</h2></div>';
        }

        $html = '';
        $html .= '<div class="cs-team grid">';
        $html .= '<div class="col-md-12">';
        $html .= '<article>';
        $html .= '<figure>';

        if ($cs_team_profile_image <> '') {

            $html .= '<img alt="' . $cs_team_name . '" src="' . esc_url($cs_team_profile_image) . '">';
        }
        $html .= '<figcaption>';

        $html .= '<div class="social-media">';
        if ($cs_team_fb_url <> '') {
            $html .= '<a href="' . esc_url($cs_team_fb_url) . '" data-original-title="fb">';
            $html .= '<i class="icon-facebook7"></i>';
            $html .= '</a>';
        }
        if ($cs_team_twitter_url <> '') {
            $html .= '<a href="' . esc_url($cs_team_twitter_url) . '" data-original-title="tw">';
            $html .= '<i class="icon-twitter6"></i>';
            $html .= '</a>';
        }if ($cs_team_linkedin_url <> '') {
            $html .= '<a href="' . esc_url($cs_team_linkedin_url) . '" data-original-title="link">';
            $html .= '<i class="icon-linkedin4"></i>';
            $html .= '</a>';
        }if ($cs_team_googleplus_url <> '') {
            $html .= '<a href="' . esc_url($cs_team_googleplus_url) . '" data-original-title="google">';
            $html .= '<i class="icon-googleplus7"></i>';
            $html .= '</a>';
        }
        if ($cs_team_skype_url <> '') {
            $html .= '<a href="' . esc_url($cs_team_skype_url) . '" data-original-title="skype">';
            $html .= '<i class="icon-skype"></i>';
            $html .= '</a>';
        }
        if ($cs_team_email <> '') {
            $html .= '<a href="mailto:' . sanitize_email($cs_team_email) . '" target="_blank"><i class="icon-envelope4"></i></a>';
        }
        $html .= '</div>';
        $html .= '</figcaption>';
        $html .= '</figure>';
        $html .= '<div class="text">';
        if ($cs_team_name <> '') {
            $html .= '<h5>' . $cs_team_name . '</h5>';
        }
        if ($cs_team_designation <> '') {
            $html .= '<span>' . $cs_team_designation . '</span>';
        }
        $html .= '</div>';
        $html .= '</article>';
        $html .= '</div>';
        $html .= '</div>';

        return $section_title . ' ' . $html;
    }

    if (function_exists('cs_short_code')) {
        cs_short_code(CS_SC_TEAM, 'cs_teams_shortcode');
    }
}