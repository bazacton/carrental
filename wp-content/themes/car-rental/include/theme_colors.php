<?php
/**
 * The template Theme Colors
 */
if (!function_exists('cs_theme_colors')) {

    function cs_theme_colors() {
        global $post, $cs_theme_options;
        $cs_theme_color = $cs_theme_options['cs_theme_color'];
        $sub_header_border_color = isset($cs_theme_options['cs_sub_header_border_color']) ? $cs_theme_options['cs_sub_header_border_color'] : '';
        $main_header_border_color = isset($cs_theme_options['cs_header_border_color']) ? $cs_theme_options['cs_header_border_color'] : '';
        $page_header_style = '';
        $page_header_border_colr = '';
        $page_subheader_border_color = '';

        if (is_page() || is_single()) {
            $cs_post_type = get_post_type($post->ID);
            switch ($cs_post_type) {
                case 'product':
                    $post_type_meta = 'product';
                    break;
                default:
                    $post_type_meta = 'cs_full_data';
            }
            $cs_page_bulider = get_post_meta($post->ID, "$post_type_meta", true);
            if (isset($cs_page_bulider) and $cs_page_bulider <> '') {
                $page_header_style = isset($cs_page_bulider['cs_header_banner_style']) ? $cs_page_bulider['cs_header_banner_style'] : '';
                $page_header_border_colr = isset($cs_page_bulider['cs_page_main_header_border_color']) ? $cs_page_bulider['cs_page_main_header_border_color'] : '';
                $page_subheader_border_color = isset($cs_page_bulider['cs_page_subheader_border_color']) ? $cs_page_bulider['cs_page_subheader_border_color'] : '';
            }
        }
        ?>
        <style type="text/css">
            /*!
            * Theme Color File */

            /*!
            * Theme Color */
            .cs-color,.cs-services.classic figure i, .our-deals .text h6,.tabs-controls .nav-tabs > li.active > a, .tabs-controls .nav-tabs > li:hover > a,.tabs-controls li.active::before, .tabs-controls li:hover::before,
            #footer .widget.widget_categories ul li:hover a, .cs-services.box figure i, .cs-services.classic figure i, .breadcrumbs ul li.active, /* widget */ .widget_categories ul li:hover a, 
            .widget_categories ul li:hover:before, .widget_archive ul li:hover a, .widget_archive ul li:hover:before,
            .widget_recent li:hover .text h6 a, .thumblist .text h6 a, .prev-next-post article a:hover i, .widget_nav_menu ul li a:hover, .widget_nav_menu ul li a:hover:before,
            .widget_meta ul li:hover a, .widget_meta ul li:hover:before, .widget_pages ul li a:hover:before, .widget_pages ul li a:hover, .widget_recent_entries ul li:hover a,
            .cs-listing.simple-view .listing-text h3 a:hover, .widget.related-post h6 a:hover,
            .information:hover,
            .information ul li span,
            .email, .cs-location-list.simple-list .phone-number i,
            .date-holder .date input,
            .date-holder .time input,
            /* Booking */
            ul.booking-tabs li:hover a h4,
            ul.booking-tabs li.active a h4,
            .booking-check-box input[type="checkbox"]:checked + label::after,
            ul.pick-retrun li em, .cs-contact-info li span, .pagination ul li a.active:hover,
            .pagination ul li a.active, .widget .widget-section-title h2, .panel-group.cs-default.simple .panel-heading a, .location-listing h5 a:hover, .cs-blog h3 a:hover, .copyright-text a{
                color:<?php echo cs_allow_special_char($cs_theme_color); ?> !important;
            }
            /*!
            * Theme Background Color */
            .cs-bgcolor,
            .navigation,
            .navigation > ul > li > a,
            .info-btn .book-btn,
            .btn-search, .cs-tags ul li a:hover, .cs-tags i:hover, .page-section .pagination ul li a:hover, .page-section .pagination ul li a.active:hover,
            .page-section .pagination ul li a.active, .widget_tag_cloud .tagcloud a:hover,
            .widget.widget-searchform form label input[type="submit"],.our-deals article:hover .text, .panel-group.cs-default.simple .panel-heading a::before,
            .countdown-alt-2 .item, /* Booking */
            ul.booking-tabs li:hover a span,
            ul.booking-tabs li.active a span,
            .btn-step,
            .bootstrap-timepicker-widget table td a:hover, .widget_form input[type="submit"], .panel-group.cs-default.simple .panel-heading a:before,
            ul.tab-list li.active, .table-condensed tbody tr td.active, .slicknav_nav{
                background-color:<?php echo cs_allow_special_char($cs_theme_color); ?> !important;
            }
            /*!
            * Theme Border Color */
            .csborder-color,.csborder-hovercolor:hover,#header{
                border-color:<?php echo cs_allow_special_char($cs_theme_color);
        ?> !important;
            }

            <?php
            if ((is_page() || is_single()) and ( $page_header_style == 'breadcrumb_header' and $page_subheader_border_color <> '')) {
                ?>
                .breadcrumb-sec {
                    border-top: 1px solid <?php echo cs_allow_special_char($page_subheader_border_color); ?>;
                    border-bottom: 1px solid <?php echo cs_allow_special_char($page_subheader_border_color); ?>;
                }
                <?php
            } else {
                if ($sub_header_border_color <> '') {
                    ?>
                    .breadcrumb-sec {
                        border-top: 1px solid <?php echo cs_allow_special_char($sub_header_border_color); ?>;
                        border-bottom: 1px solid <?php echo cs_allow_special_char($sub_header_border_color); ?>;
                    }
                    <?php
                }
            }

            if ((is_page() || is_single()) and ( $page_header_style == 'no-header' and $page_header_border_colr <> '')) {
                ?>
                #main-header {
                    border-bottom: 1px solid <?php echo cs_allow_special_char($page_header_border_colr); ?>;
                }
                <?php
            } else {
                if (isset($cs_theme_options['cs_default_header']) and $cs_theme_options['cs_default_header'] == 'No sub Header') {
                    if ($main_header_border_color <> '') {
                        ?>
                        #main-header {
                            border-bottom: 1px solid <?php echo cs_allow_special_char($main_header_border_color); ?>;
                        }
                        <?php
                    }
                }
            }
            ?>

        </style>
        <?php
    }

}


/**
 * @Set Header color Css
 *
 *
 */
if (!function_exists('cs_header_color')) {

    function cs_header_color() {
        global $cs_theme_options;

        $cs_header_bgcolor = (isset($cs_theme_options['cs_header_bgcolor']) and $cs_theme_options['cs_header_bgcolor'] <> '') ? $cs_theme_options['cs_header_bgcolor'] : '';

        $cs_nav_bgcolor = (isset($cs_theme_options['cs_nav_bgcolor']) and $cs_theme_options['cs_nav_bgcolor'] <> '') ? $cs_theme_options['cs_nav_bgcolor'] : '';

        $cs_menu_color = (isset($cs_theme_options['cs_menu_color']) and $cs_theme_options['cs_menu_color'] <> '') ? $cs_theme_options['cs_menu_color'] : '';

        $cs_menu_active_color = (isset($cs_theme_options['cs_menu_active_color']) and $cs_theme_options['cs_menu_active_color'] <> '') ? $cs_theme_options['cs_menu_active_color'] : '';

        $cs_submenu_bgcolor = (isset($cs_theme_options['cs_submenu_bgcolor']) and $cs_theme_options['cs_submenu_bgcolor'] <> '' ) ? $cs_theme_options['cs_submenu_bgcolor'] : '';

        $cs_submenu_color = (isset($cs_theme_options['cs_submenu_color']) and $cs_theme_options['cs_submenu_color'] <> '') ? $cs_theme_options['cs_submenu_color'] : '';

        $cs_submenu_hover_color = (isset($cs_theme_options['cs_submenu_hover_color']) and $cs_theme_options['cs_submenu_hover_color'] <> '') ? $cs_theme_options['cs_submenu_hover_color'] : '';
        $cs_topstrip_bgcolor = (isset($cs_theme_options['cs_topstrip_bgcolor']) and $cs_theme_options['cs_topstrip_bgcolor'] <> '') ? $cs_theme_options['cs_topstrip_bgcolor'] : '';

        $cs_topstrip_text_color = (isset($cs_theme_options['cs_topstrip_text_color']) and $cs_theme_options['cs_topstrip_text_color'] <> '') ? $cs_theme_options['cs_topstrip_text_color'] : '';

        $cs_topstrip_link_color = (isset($cs_theme_options['cs_topstrip_link_color']) and $cs_theme_options['cs_topstrip_link_color'] <> '') ? $cs_theme_options['cs_topstrip_link_color'] : '';

        $cs_menu_activ_bg = (isset($cs_theme_options['cs_theme_color'])) ? $cs_theme_options['cs_theme_color'] : '';

        /* logo margins */
        $cs_logo_margint = (isset($cs_theme_options['cs_logo_margint']) and $cs_theme_options['cs_logo_margint'] <> '') ? $cs_theme_options['cs_logo_margint'] : '0';
        $cs_logo_marginb = (isset($cs_theme_options['cs_logo_marginb']) and $cs_theme_options['cs_logo_marginb'] <> '') ? $cs_theme_options['cs_logo_marginb'] : '0';

        $cs_logo_marginr = (isset($cs_theme_options['cs_logo_marginr']) and $cs_theme_options['cs_logo_marginr'] <> '') ? $cs_theme_options['cs_logo_marginr'] : '0';
        $cs_logo_marginl = (isset($cs_theme_options['cs_logo_marginl']) and $cs_theme_options['cs_logo_marginl'] <> '') ? $cs_theme_options['cs_logo_marginl'] : '0';

        /* font family */
        $cs_content_font = (isset($cs_theme_options['cs_content_font'])) ? $cs_theme_options['cs_content_font'] : '';
        $cs_content_font_att = (isset($cs_theme_options['cs_content_font_att'])) ? $cs_theme_options['cs_content_font_att'] : '';

        $cs_mainmenu_font = (isset($cs_theme_options['cs_mainmenu_font'])) ? $cs_theme_options['cs_mainmenu_font'] : '';
        $cs_mainmenu_font_att = (isset($cs_theme_options['cs_mainmenu_font_att'])) ? $cs_theme_options['cs_mainmenu_font_att'] : '';

        $cs_heading_font = (isset($cs_theme_options['cs_heading_font'])) ? $cs_theme_options['cs_heading_font'] : '';
        $cs_heading_font_att = (isset($cs_theme_options['cs_heading_font_att'])) ? $cs_theme_options['cs_heading_font_att'] : '';

        $cs_widget_heading_font = (isset($cs_theme_options['cs_widget_heading_font'])) ? $cs_theme_options['cs_widget_heading_font'] : '';
        $cs_widget_heading_font_att = (isset($cs_theme_options['cs_widget_heading_font_att'])) ? $cs_theme_options['cs_widget_heading_font_att'] : '';

        // setting content fonts
        $cs_content_fonts = preg_split('#(?<=\d)(?=[a-z])#i', $cs_content_font_att);

        $cs_content_font_atts = cs_get_font_att_array($cs_content_fonts);

        // setting main menu fonts
        $cs_mainmenu_fonts = preg_split('#(?<=\d)(?=[a-z])#i', $cs_mainmenu_font_att);

        $cs_mainmenu_font_atts = cs_get_font_att_array($cs_mainmenu_fonts);

        // setting heading fonts
        $cs_heading_fonts = preg_split('#(?<=\d)(?=[a-z])#i', $cs_heading_font_att);

        $cs_heading_font_atts = cs_get_font_att_array($cs_heading_fonts);

        // setting widget heading fonts
        $cs_widget_heading_fonts = preg_split('#(?<=\d)(?=[a-z])#i', $cs_widget_heading_font_att);

        $cs_widget_heading_font_atts = cs_get_font_att_array($cs_widget_heading_fonts);

        /* font size */
        $cs_content_size = (isset($cs_theme_options['cs_content_size'])) ? $cs_theme_options['cs_content_size'] : '';
        $cs_mainmenu_size = (isset($cs_theme_options['cs_mainmenu_size'])) ? $cs_theme_options['cs_mainmenu_size'] : '';
        $cs_heading_1_size = (isset($cs_theme_options['cs_heading_1_size'])) ? $cs_theme_options['cs_heading_1_size'] : '';
        $cs_heading_2_size = (isset($cs_theme_options['cs_heading_2_size'])) ? $cs_theme_options['cs_heading_2_size'] : '';
        $cs_heading_3_size = (isset($cs_theme_options['cs_heading_3_size'])) ? $cs_theme_options['cs_heading_3_size'] : '';
        $cs_heading_4_size = (isset($cs_theme_options['cs_heading_4_size'])) ? $cs_theme_options['cs_heading_4_size'] : '';
        $cs_heading_5_size = (isset($cs_theme_options['cs_heading_5_size'])) ? $cs_theme_options['cs_heading_5_size'] : '';
        $cs_heading_6_size = (isset($cs_theme_options['cs_heading_6_size'])) ? $cs_theme_options['cs_heading_6_size'] : '';

        /* font Color */
        $cs_heading_h1_color = (isset($cs_theme_options['cs_heading_h1_color']) and $cs_theme_options['cs_heading_h1_color'] <> '') ? $cs_theme_options['cs_heading_h1_color'] : '';
        $cs_heading_h2_color = (isset($cs_theme_options['cs_heading_h2_color']) and $cs_theme_options['cs_heading_h2_color'] <> '') ? $cs_theme_options['cs_heading_h2_color'] : '';
        $cs_heading_h3_color = (isset($cs_theme_options['cs_heading_h3_color']) and $cs_theme_options['cs_heading_h3_color'] <> '') ? $cs_theme_options['cs_heading_h3_color'] : '';
        $cs_heading_h4_color = (isset($cs_theme_options['cs_heading_h4_color']) and $cs_theme_options['cs_heading_h4_color'] <> '') ? $cs_theme_options['cs_heading_h4_color'] : '';
        $cs_heading_h5_color = (isset($cs_theme_options['cs_heading_h5_color']) and $cs_theme_options['cs_heading_h5_color'] <> '') ? $cs_theme_options['cs_heading_h5_color'] : '';
        $cs_heading_h6_color = (isset($cs_theme_options['cs_heading_h6_color']) and $cs_theme_options['cs_heading_h6_color'] <> '') ? $cs_theme_options['cs_heading_h6_color'] : '';
        $cs_text_color = $cs_theme_options['cs_text_color'];

        $cs_widget_heading_size = (isset($cs_theme_options['cs_widget_heading_size'])) ? $cs_theme_options['cs_widget_heading_size'] : '';
        $cs_section_heading_size = (isset($cs_theme_options['cs_section_heading_size'])) ? $cs_theme_options['cs_section_heading_size'] : '';


        if (
                ( isset($cs_theme_options['cs_custom_font_woff']) && $cs_theme_options['cs_custom_font_woff'] <> '' ) &&
                ( isset($cs_theme_options['cs_custom_font_ttf']) && $cs_theme_options['cs_custom_font_ttf'] <> '' ) &&
                ( isset($cs_theme_options['cs_custom_font_svg']) && $cs_theme_options['cs_custom_font_svg'] <> '' ) &&
                ( isset($cs_theme_options['cs_custom_font_eot']) && $cs_theme_options['cs_custom_font_eot'] <> '' )
        ):

            $font_face_html = "
        @font-face {
            font-family: 'cs_custom_font';
            src: url('" . $cs_theme_options['cs_custom_font_eot'] . "');
            src:
                url('" . $cs_theme_options['cs_custom_font_eot'] . "?#iefix') format('eot'),
                url('" . $cs_theme_options['cs_custom_font_woff'] . "') format('woff'),
                url('" . $cs_theme_options['cs_custom_font_ttf'] . "') format('truetype'),
                url('" . $cs_theme_options['cs_custom_font_svg'] . "#cs_custom_font') format('svg');
            font-weight: 400;
            font-style: normal;
        }";

            $custom_font = true;
        else: $custom_font = false;
        endif;

        if ($custom_font != true) {
            cs_get_font_family($cs_content_font, $cs_content_font_att);
            cs_get_font_family($cs_mainmenu_font, $cs_mainmenu_font_att);
            cs_get_font_family($cs_heading_font, $cs_heading_font_att);
            cs_get_font_family($cs_widget_heading_font, $cs_widget_heading_font_att);
        }
        ?>
        <style type="text/css">

            <?php
            if ($custom_font == true) {
                echo cs_allow_special_char($font_face_html);
            }
            ?>
            body,.main-section p {
                <?php
                if ($custom_font == true) {
                    echo 'font-family: cs_custom_font;';
                    echo 'font-size: ' . $cs_content_size . ';';
                } else {
                    echo cs_font_font_print($cs_content_font_atts, $cs_content_size, $cs_content_font);
                }
                ?>
                color:<?php echo cs_allow_special_char($cs_text_color); ?>;
            }
            header .logo{
                margin:<?php echo cs_allow_special_char($cs_logo_margint); ?>px  <?php echo cs_allow_special_char($cs_logo_marginr); ?>px <?php echo cs_allow_special_char($cs_logo_marginb); ?>px <?php echo cs_allow_special_char($cs_logo_marginl); ?>px !important;
            }
            .nav li a,.navigation ul li{
                <?php
                if ($custom_font == true) {
                    echo 'font-family: cs_custom_font;';
                    echo 'font-size: ' . $cs_mainmenu_size . ';';
                } else {
                    echo cs_font_font_print($cs_mainmenu_font_atts, $cs_mainmenu_size, $cs_mainmenu_font, true);
                }
                ?>
            }
            h1{
                <?php
                if ($custom_font == true) {
                    echo 'font-family: cs_custom_font;';
                    echo 'font-size: ' . $cs_heading_1_size . ';';
                } else {
                    echo cs_font_font_print($cs_heading_font_atts, $cs_heading_1_size, $cs_heading_font, true);
                }
                ?>}
            h2{
                <?php
                if ($custom_font == true) {
                    echo 'font-family: cs_custom_font;';
                    echo 'font-size: ' . $cs_heading_2_size . ';';
                } else {
                    echo cs_font_font_print($cs_heading_font_atts, $cs_heading_2_size, $cs_heading_font, true);
                }
                ?>}
            h3{
                <?php
                if ($custom_font == true) {
                    echo 'font-family: cs_custom_font;';
                    echo 'font-size: ' . $cs_heading_3_size . ';';
                } else {
                    echo cs_font_font_print($cs_heading_font_atts, $cs_heading_3_size, $cs_heading_font, true);
                }
                ?>}
            h4{
                <?php
                if ($custom_font == true) {
                    echo 'font-family: cs_custom_font;';
                    echo 'font-size: ' . $cs_heading_4_size . ';';
                } else {
                    echo cs_font_font_print($cs_heading_font_atts, $cs_heading_4_size, $cs_heading_font, true);
                }
                ?>}
            h5{
                <?php
                if ($custom_font == true) {
                    echo 'font-family: cs_custom_font;';
                    echo 'font-size: ' . $cs_heading_5_size . ';';
                } else {
                    echo cs_font_font_print($cs_heading_font_atts, $cs_heading_5_size, $cs_heading_font, true);
                }
                ?>}
            h6{
                <?php
                if ($custom_font == true) {
                    echo 'font-family: cs_custom_font;';
                    echo 'font-size: ' . $cs_heading_6_size . ';';
                } else {
                    echo cs_font_font_print($cs_heading_font_atts, $cs_heading_6_size, $cs_heading_font, true);
                }
                ?>}

            .main-section h1, .main-section h1 a {color: <?php echo cs_allow_special_char($cs_heading_h1_color); ?> !important;}
            .main-section h2, .main-section h2 a{color: <?php echo cs_allow_special_char($cs_heading_h2_color); ?> !important;}
            .main-section h3, .main-section h3 a{color: <?php echo cs_allow_special_char($cs_heading_h3_color); ?> !important;}
            .main-section h4, .main-section h4 a{color: <?php echo cs_allow_special_char($cs_heading_h4_color); ?> !important;}
            .main-section h5, .main-section h5 a{color: <?php echo cs_allow_special_char($cs_heading_h5_color); ?> !important;}
            .main-section h6, .main-section h6 a{color: <?php echo cs_allow_special_char($cs_heading_h6_color); ?> !important;}
            .widget .widget-section-title h2{
                <?php
                if ($custom_font == true) {
                    echo 'font-family: cs_custom_font;';
                    echo 'font-size: ' . $cs_widget_heading_size . ';';
                } else {
                    echo cs_font_font_print($cs_widget_heading_font_atts, $cs_widget_heading_size, $cs_widget_heading_font, true);
                }
                ?>
            }
            .cs-section-title h2{
                <?php
                echo 'font-size:' . $cs_section_heading_size . 'px !important;';
                ?>
            }
            .top-bar,#lang_sel ul ul {background-color:<?php echo cs_allow_special_char($cs_topstrip_bgcolor); ?> !important;}
            #lang_sel ul ul:before { border-bottom-color: <?php echo cs_allow_special_char($cs_topstrip_bgcolor); ?>; }
            .top-bar p{color:<?php echo cs_allow_special_char($cs_topstrip_text_color); ?> !important;}
            .top-bar a,.top-bar i{color:<?php echo cs_allow_special_char($cs_topstrip_link_color); ?> !important;}
            .logo-section,.main-head{background:<?php echo cs_allow_special_char($cs_header_bgcolor); ?> !important;}
            .main-navbar,#main-header .btn-style1,.wrapper:before {background:<?php echo cs_allow_special_char($cs_nav_bgcolor); ?> !important;}
            .navigation {background:<?php echo cs_allow_special_char($cs_nav_bgcolor); ?> !important;}
            .navigation ul > li > a {color:<?php echo cs_allow_special_char($cs_menu_color); ?> !important;}
            .sub-dropdown { background-color:<?php echo cs_allow_special_char($cs_submenu_bgcolor); ?> !important;}
            .navigation > ul ul li > a {color:<?php echo cs_allow_special_char($cs_submenu_color); ?> !important;}
            .navigation > ul ul li:hover > a {color:<?php echo cs_allow_special_char($cs_submenu_hover_color); ?>;color:<?php echo cs_allow_special_char($cs_submenu_hover_color); ?> !important;}
            .navigation > ul > li:hover > a {color:<?php echo cs_allow_special_char($cs_menu_active_color); ?> !important;}
            .sub-dropdown:before {border-bottom:8px solid <?php echo cs_allow_special_char($cs_menu_active_color); ?> !important;}

            .navigation .sub-dropdown > li:hover > a,
            .navigation > ul > li.parentIcon:hover > a:before { background-color:<?php echo cs_allow_special_char($cs_menu_active_color); ?> !important; }
            .cs-user,.cs-user-login { border-color:<?php echo cs_allow_special_char($cs_menu_active_color); ?> !important; }
            {
                box-shadow: 0 4px 0 <?php echo cs_allow_special_char($cs_topstrip_bgcolor); ?> inset !important;
            }
            .header_2 .nav > li:hover > a,.header_2 .nav > li.current-menu-ancestor > a {

            }
        </style>
        <?php
    }

}



/**
 * @Set Footer colors
 *
 *
 */
if (!function_exists('cs_footer_color')) {

    function cs_footer_color() {
        global $cs_theme_options;
        $cs_footerbg_color = (isset($cs_theme_options['cs_footerbg_color']) and $cs_theme_options['cs_footerbg_color'] <> '') ? $cs_theme_options['cs_footerbg_color'] : '';

        $cs_footerbg_image = (isset($cs_theme_options['cs_footer_background_image']) and $cs_theme_options['cs_footer_background_image'] <> '') ? $cs_theme_options['cs_footer_background_image'] : '';
        $footer_bg_color = cs_hex2rgb($cs_footerbg_color);

        $cs_bg_footer_color = 'background-color:rgba(' . $footer_bg_color[0] . ', ' . $footer_bg_color[1] . ', ' . $footer_bg_color[2] . ', 0.95) !important;';
        $cs_title_color = (isset($cs_theme_options['cs_title_color']) and $cs_theme_options['cs_title_color'] <> '') ? $cs_theme_options['cs_title_color'] : '';

        $cs_footer_text_color = (isset($cs_theme_options['cs_footer_text_color']) and $cs_theme_options['cs_footer_text_color'] <> '') ? $cs_theme_options['cs_footer_text_color'] : '';
        $cs_link_color = (isset($cs_theme_options['cs_link_color']) and $cs_theme_options['cs_link_color'] <> '') ? $cs_theme_options['cs_link_color'] : '';
        $cs_sub_footerbg_color = (isset($cs_theme_options['cs_sub_footerbg_color']) and $cs_theme_options['cs_sub_footerbg_color'] <> '') ? $cs_theme_options['cs_sub_footerbg_color'] : '';

        $cs_copyright_text_color = (isset($cs_theme_options['cs_copyright_text_color']) and $cs_theme_options['cs_copyright_text_color'] <> '') ? $cs_theme_options['cs_copyright_text_color'] : '';
        ?>
        <style type="text/css">

            #footer {
                background-color:<?php echo cs_allow_special_char($cs_footerbg_color); ?> !important;
            }

            .footer-content {
                background-color:<?php echo cs_allow_special_char($cs_footerbg_color); ?> !important;
            }
            footer #copyright p {
                color:<?php echo cs_allow_special_char($cs_copyright_text_color); ?> !important;
            }
            footer a,footer .widget-form ul li input[type='submit'],footer.group .tagcloud a,footer.group .widget ul li a {
                color:<?php echo cs_allow_special_char($cs_link_color); ?> !important;
            }
            #footer .widget{
                background-color:<?php echo cs_allow_special_char($cs_bg_footer_color); ?> !important;
            }
            #footer .widget h2, #footer .widget h5,footer.group h2,#footer h3,#footer h4,#footer h5,#footer h6 {
                color:<?php echo cs_allow_special_char($cs_title_color); ?> !important;
            }
            #newslatter-sec,#newslatter-sec span,#footer .widget ul li, #footer .widget_calendar tr td,footer.group,footer .widget_latest_post .post-options li, .widget-form ul li i {
                color:<?php echo cs_allow_special_char($cs_footer_text_color); ?> !important;
            }
        </style>
        <?php
    }

}