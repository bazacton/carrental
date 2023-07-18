<?php
/**
 * The template for Settings up Functions
 */
/**
 * @Get logo
 *
 */
global $cs_theme_options;
if (!function_exists('cs_logo')) {

    function cs_logo() {
        global $cs_theme_options;
         $logo = isset($cs_theme_options['cs_custom_logo']) && $cs_theme_options['cs_custom_logo'] ? $cs_theme_options['cs_custom_logo'] : '';
        if (!file_exists($logo)) {
            $logo = get_template_directory_uri() . '/assets/images/logo.png';
        }
        ?>
        <a href="<?php echo esc_url(home_url('/')); ?>">    
            <img src="<?php echo esc_url($logo); ?>" style="width:<?php echo cs_allow_special_char($cs_theme_options['cs_logo_width']); ?>px; height: <?php echo cs_allow_special_char($cs_theme_options['cs_logo_height']); ?>px;" alt="<?php bloginfo('name'); ?>">
        </a>
        <?php
    }

}



if (!function_exists('cs_sticky_logo')) {

    function cs_sticky_logo() {
        global $cs_theme_options;
        $stickey_logo = isset($cs_theme_options['cs_sticky_logo']) ? $cs_theme_options['cs_sticky_logo'] : '';
        ?>
        <a href="<?php echo esc_url(home_url('/')); ?>">    
            <img src="<?php echo esc_url($stickey_logo); ?>" alt="<?php bloginfo('name'); ?>">
        </a>
        <?php
    }

}

/**
 * @Set Header Position
 *
 *
 */
if (!function_exists('cs_header_postion_class')) {

    function cs_header_postion_class() {
        global $cs_theme_options;
        return 'header-' . $cs_theme_options['cs_header_position'];
    }

}


/**
 * @Top and Main Navigation
 *
 *
 */
if (!function_exists('cs_navigation')) {

    function cs_navigation($nav = '', $menus = 'menus', $menu_class = '', $depth = '0') {
        global $cs_theme_options;
        if (has_nav_menu($nav)) {
            $defaults = array(
                'theme_location' => "$nav",
                'menu' => '',
                'container' => '',
                'container_class' => '',
                'container_id' => '',
                'menu_class' => "$menu_class",
                'menu_id' => "$menus",
                'echo' => false,
                'fallback_cb' => 'wp_page_menu',
                'before' => '',
                'after' => '',
                'link_before' => '',
                'link_after' => '',
                'items_wrap' => '<ul>%3$s</ul>',
                'depth' => "$depth",
                'walker' => '',);
            echo do_shortcode(str_replace('sub-menu', 'sub-dropdown', (wp_nav_menu($defaults))));
        } else {
            $defaults = array(
                'theme_location' => "",
                'menu' => '',
                'container' => '',
                'container_class' => '',
                'container_id' => '',
                'menu_class' => "$menu_class",
                'menu_id' => "$menus",
                'echo' => false,
                'fallback_cb' => 'wp_page_menu',
                'before' => '',
                'after' => '',
                'link_before' => '',
                'link_after' => '',
                'items_wrap' => '<ul>%3$s</ul>',
                'depth' => "$depth",
                'walker' => '',);
            echo do_shortcode(str_replace('sub-menu', 'sub-dropdown', (wp_nav_menu($defaults))));
        }
    }

}


//===============
//@ Header 
//===============
if (!function_exists('cs_get_headers')) {

    function cs_get_headers() {
        global $cs_theme_options;

        $cs_header_style = isset($cs_theme_options['cs_header_style']) ? $cs_theme_options['cs_header_style'] : '';
        $cs_wpml_switch = isset($cs_theme_options['cs_wpml_switch']) ? $cs_theme_options['cs_wpml_switch'] : '';
        $cs_header_top_strip = isset($cs_theme_options['cs_header_top_strip']) ? $cs_theme_options['cs_header_top_strip'] : '';
        $cs_help_text = isset($cs_theme_options['cs_help_text']) ? $cs_theme_options['cs_help_text'] : '';
        $cs_help_phone = isset($cs_theme_options['cs_help_phone']) ? $cs_theme_options['cs_help_phone'] : '';
        $cs_help_email = isset($cs_theme_options['cs_help_email']) ? $cs_theme_options['cs_help_email'] : '';
        $cs_blog_title = get_bloginfo('description');

        $cs_headr_class = '';
        $cs_info_class = 'headerinfo';
        if ($cs_header_style == 'header-2') {
            $cs_headr_class = 'header-v2';
            $cs_info_class = 'cs_nav_bar';
        }
        ?>
        <!-- Header 1 Start -->
        <header id="header">
            <div class="container">
                <div class="logo-area">
                    <div class="logo">
                        <h1><?php cs_logo(); ?></h1>
                    </div>
                    <div class="header-right">
                        <ul class="help-list">
                            <?php
                            $cs_allowed_tags = array(
                                'a' => array('href' => array()),
                                'b' => array(),
                                'i' => array('class' => array()),
                            );

                            if ($cs_help_text <> '') {
                                ?>
                                <li><?php echo wp_kses(wp_specialchars_decode($cs_help_text), $cs_allowed_tags); ?></li>
                            <?php } ?>
                            <?php if ($cs_help_phone <> '') { ?>
                                <li><?php echo wp_kses(wp_specialchars_decode($cs_help_phone), $cs_allowed_tags) ?></li>
                            <?php } ?>
                            <?php if ($cs_help_email <> '') { ?>
                                <li><?php echo wp_kses(wp_specialchars_decode($cs_help_email), $cs_allowed_tags) ?></li>
                            <?php } ?>
                        </ul>
                        <?php cs_booking_btn() ?>

                    </div>
                </div>
                <nav class="navigation">
                    <?php cs_header_main_navigation(); ?>
                </nav>
            </div>
        </header>

        <?php
    }

}

/**
 *
 * @ Reservation Button For Responsive
 *
 */
if (!function_exists('cs_booking_btn')) {

    function cs_booking_btn() {
        global $cs_theme_options, $current_user;
        $cs_booking_title = isset($cs_theme_options['cs_booking_title']) ? $cs_theme_options['cs_booking_title'] : esc_html__('Make a Booking', 'car-rental');
        $cs_booking_link = isset($cs_theme_options['cs_booking_link']) ? $cs_theme_options['cs_booking_link'] : '';
        if (isset($cs_booking_title) and $cs_booking_link) {

            echo '<a href="' . esc_url($cs_booking_link) . '" class="btn-medium" target="_blank">' . $cs_booking_title . '</a>';
        }
    }

}

//=================
// @Main navigation
//=================
if (!function_exists('cs_header_main_navigation')) {

    function cs_header_main_navigation() {
        global $post, $post_meta;
        $post_type = get_post_type(get_the_ID());
        $meta_element = 'cs_full_data';
        $post_ID = get_the_ID();
        $post_meta = get_post_meta($post_ID, "$meta_element", true);

        if (function_exists("is_shop") and ! is_shop()) {
            if (is_author() || is_search() || is_archive() || is_category() || is_404()) {

                $cs_header_banner_style = '';
            }
        } else if (!function_exists("is_shop")) {
            if (is_author() || is_search() || is_archive() || is_category() || is_404()) {

                $cs_header_banner_style = '';
            }
        }
        cs_navigation('main-menu', 'navbar-nav');
    }

}


//====================
// @Subheader Style
//====================
if (!function_exists('cs_subheader_style')) {

    function cs_subheader_style($post_ID = '') {
        global $post, $wp_query, $cs_theme_options, $post_meta;
        $post_type = get_post_type(get_the_ID());
        $post_ID = get_the_ID();
        $meta_element = 'cs_full_data';

        $post_meta = get_post_meta((int) $post_ID, "$meta_element", true);
        $cs_header_banner_style = get_post_meta((int) $post_ID, "cs_header_banner_style", true);
        $post_meta = get_post_meta((int) $post_ID, "$meta_element", true);

        if (function_exists("is_shop") and ! is_shop()) {
            if (is_author() || is_search() || is_archive() || is_category()) {
                $cs_header_banner_style = '';
            }
        } else if (!function_exists("is_shop")) {
            if (is_author() || is_search() || is_archive() || is_category()) {

                $cs_header_banner_style = '';
            }
        }
        if (isset($cs_header_banner_style) && $cs_header_banner_style == 'no-header') {
            // Do Nothing
        } else if (isset($cs_header_banner_style) && $cs_header_banner_style == 'breadcrumb_header') {
            cs_breadcrumb_header($post_ID);
        } else if (isset($cs_header_banner_style) && $cs_header_banner_style == 'custom_slider') {
            cs_shortcode_slider('pages', $post_ID);
        } else if (isset($cs_header_banner_style) && $cs_header_banner_style == 'map') {
            cs_shortcode_map($post_ID);
        } else if ($cs_theme_options['cs_default_header']) {
            if ($cs_theme_options['cs_default_header'] == 'No sub Header') {
                // Do Noting          				
            } else if ($cs_theme_options['cs_default_header'] == 'Breadcrumbs Sub Header') {
                cs_breadcrumb_header($post_ID);
                //cs_breadcrumbs(); 
            } else if ($cs_theme_options['cs_default_header'] == 'Revolution Slider') {
                cs_shortcode_slider('default-pages', $post_ID);
            }
        }
    }

}
//====================
// @Below Header Style 
//====================
if (!function_exists('cs_below_header_style')) {

    function cs_below_header_style() {
        global $cs_theme_options;
        $cs_header_position = isset($cs_theme_options['cs_header_position']) ? $cs_theme_options['cs_header_position'] : '';
        $cs_absolute_view = isset($cs_theme_options['cs_headerbg_options']) ? $cs_theme_options['cs_headerbg_options'] : '';
        $cs_absolute_slider = isset($cs_theme_options['cs_headerbg_slider']) ? $cs_theme_options['cs_headerbg_slider'] : '';
        $cs_absolute_image = isset($cs_theme_options['cs_headerbg_image']) ? $cs_theme_options['cs_headerbg_image'] : '';
        $cs_absolute_color = isset($cs_theme_options['cs_headerbg_color']) ? $cs_theme_options['cs_headerbg_color'] : '';
        if ($cs_header_position == 'absolute') {
            if (is_author() || is_search() || is_archive() || is_category() || is_home() || is_404()) {
                if ($cs_absolute_view == 'cs_rev_slider') {
                    ?>
                    <div class="cs-banner"> <?php echo do_shortcode('[rev_slider ' . $cs_absolute_slider . ']'); ?> </div>
                    <?php
                } else if ($cs_absolute_view == 'cs_bg_image_color') {
                    $cs_style_elements = 'style="background:url(' . $cs_absolute_image . ') center top ' . $cs_absolute_color . ';"';
                    ?>
                    <div class="breadcrumb-sec" <?php echo cs_allow_special_char($cs_style_elements); ?>>&nbsp;</div>
                    <?php
                }
            }
        }
    }

}
/**
 * @Custom Slider by using shortcode
 *
 *
 */
if (!function_exists('cs_shortcode_slider')) {

    function cs_shortcode_slider($type = '', $post_ID = '') {
        global $post, $post_meta, $cs_theme_options;
        $cs_custom_slider_id = get_post_meta((int) $post_ID, "cs_custom_slider_id", true);

        if ($type == 'pages') {
            if (empty($cs_custom_slider_id))
                $custom_slider_id = "";
            else
                $custom_slider_id = htmlspecialchars(
                        $cs_custom_slider_id);
        } else {
            if (empty($cs_custom_slider_id))
                $custom_slider_id = "";
            else
                $custom_slider_id = htmlspecialchars(
                        $cs_custom_slider_id);
        }
        if (isset($custom_slider_id) && $custom_slider_id != '') {
            ?>
            <div class="cs-banner"> <?php echo do_shortcode('[rev_slider ' . $custom_slider_id . ']'); ?> </div>
            <?php
        }
    }

}
/**
 * @Custom Map by using shortcode
 *
 *
 */
if (!function_exists('cs_shortcode_map')) {

    function cs_shortcode_map($post_ID = '') {
        global $post, $post_meta, $header_map;
        $cs_custom_map = get_post_meta((int) $post_ID, "cs_custom_map", true);
        if (empty($cs_custom_map))
            $custom_map = "";
        else
            $custom_map = html_entity_decode($cs_custom_map);
        if (isset($custom_map) && $custom_map != '') {
            $header_map = true;
            ?>
            <div class="cs-map"> <?php echo do_shortcode($custom_map); ?> </div>
            <?php
        }
    }

}
/**
 * @Breadcrumb Header
 *
 * 
 */
if (!function_exists('cs_breadcrumb_header')) {

    function cs_breadcrumb_header($post_ID = '') {

        global $post, $wp_query, $cs_theme_options, $post_meta;
        $breadcrumSectionStart = '';
        $breadcrumSectionEnd = '';
        $post_type = '';
        if (is_page() || is_single()) {
            if (isset($post) && $post <> '') {
                $post_ID = $post->ID;
            } else {
                $post_ID = '';
            }
            $post_type = get_post_type($post_ID);
        }

        $cs_header_banner_style = get_post_meta((int) $post_ID, "cs_header_banner_style", true);
        $cs_page_subheader_text_color = get_post_meta((int) $post_ID, "cs_page_subheader_text_color", true);
        $cs_subheader_padding_top = get_post_meta((int) $post_ID, "cs_subheader_padding_top", true);
        $cs_subheader_padding_bottom = get_post_meta((int) $post_ID, "cs_subheader_padding_bottom", true);


        $staticContainerStart = '';
        $staticContainerEnd = '';
        $banner_image_height = 'auto';
        $cs_sh_paddingtop = '';
        $cs_sh_paddingbottom = '';
        $isDeafultSubHeader = 'false';

        if (is_author() || is_search() || is_archive() || is_category() || is_home() || is_404() || $post_type == 'vehicles') {
            $isDeafultSubHeader = 'true';
        }
        if (isset($cs_header_banner_style) && ( $cs_header_banner_style == 'default_header' || $cs_header_banner_style == '' )) {
            //Padding Top & Bottom 
            $cs_sh_paddingtop = ( isset($cs_theme_options['cs_sh_paddingtop']) ) ? 'padding-top:' . $cs_theme_options['cs_sh_paddingtop'] . 'px;' : '';
            $cs_sh_paddingbottom = ( isset($cs_theme_options['cs_sh_paddingbottom']) ) ? 'padding-bottom:' . $cs_theme_options['cs_sh_paddingbottom'] . 'px;' : '';
            $page_subheader_text_color = ( isset($cs_theme_options['cs_sub_header_text_color']) ) ? $cs_theme_options['cs_sub_header_text_color'] : '';
        } else {
            if ($isDeafultSubHeader == 'true') {

                $cs_sh_paddingtop = ( isset($cs_theme_options['cs_sh_paddingtop']) ) ? 'padding-top:' . $cs_theme_options['cs_sh_paddingtop'] . 'px;' : '';
                $cs_sh_paddingbottom = ( isset($cs_theme_options['cs_sh_paddingbottom']) ) ? 'padding-bottom:' . $cs_theme_options['cs_sh_paddingbottom'] . 'px;' : '';
                $page_subheader_text_color = (isset($cs_theme_options['cs_sub_header_text_color']) and $cs_theme_options['cs_sub_header_text_color'] <> '' ) ? $cs_theme_options['cs_sub_header_text_color'] : '';
            } else {
                if (empty($cs_page_subheader_text_color))
                    $page_subheader_text_color = "";
                else
                    $page_subheader_text_color = $cs_page_subheader_text_color;

                //Padding Top & Bottom
                if (empty($cs_subheader_padding_top)) {
                    $cs_sh_paddingtop = "";
                } else {
                    $cs_sh_paddingtop = 'padding-top:' . $cs_subheader_padding_top . 'px;';
                }
                if (empty($cs_subheader_padding_bottom)) {
                    $cs_sh_paddingbottom = "";
                } else {
                    $cs_sh_paddingbottom = 'padding-bottom:' . $cs_subheader_padding_bottom . 'px';
                }
            }
        }

        $subheader_style_elements = '';

        $breadcrumSectionStart = '<div class="absolute-sec">';
        $breadcrumSectionEnd = '</div>';

        $parallax_class = '';
        $parallax_data_type = '';

        if ($subheader_style_elements) {
            $subheader_style_elements = 'style="' . $subheader_style_elements . ' min-height:' . $banner_image_height . '!important; ' . $cs_sh_paddingtop . ' ' . $cs_sh_paddingbottom . '  "';
        } else {
            $subheader_style_elements = 'style="min-height:' . $banner_image_height . '; ' . $cs_sh_paddingtop . ' ' . $cs_sh_paddingbottom . ' "';
        }
        $page_tile_align = '';
        ?>
        <div class="breadcrumb-sec <?php echo cs_allow_special_char($page_tile_align) . ' ' . cs_allow_special_char($parallax_class); ?>" <?php echo cs_allow_special_char($subheader_style_elements); ?> 
             <?php echo cs_allow_special_char($parallax_data_type); ?>> 
            <!-- Container --> 
            <?php echo cs_remove_force_tag_blnc_theme($breadcrumSectionStart, false); ?>
            <div class="container" style="height:<?php echo esc_attr($banner_image_height); ?>">
                <div class="cs-main-title">
                    <?php
                    if (is_page()) {
                        get_subheader_title();
                    } else if (is_single() && $post_type == 'vehicles') {
                        get_default_post_title();
                    } else if (is_single() && $post_type != 'post' && $post_type != 'vehicles') {
                        get_subheader_title();
                    } else if (is_single() && $post_type == 'post') {
                        get_subheader_title();
                    } else {
                        get_default_post_title();
                    }
                    ?>
                    <?php get_subheader_breadcrumb(); ?>
                </div>
            </div>
            <?php echo cs_remove_force_tag_blnc_theme($breadcrumSectionEnd, false); ?> 
        </div>
        <div class="clear"></div>
        <?php
    }

}
/**
 * @Page Sub header title and subtitle 
 *
 *
 */
if (!function_exists('get_subheader_breadcrumb')) {

    function get_subheader_breadcrumb() {
        global $post, $wp_query, $cs_theme_options, $post_meta;
        $meta_element = 'cs_full_data';
        $post_ID = get_the_ID();
        $post_type = get_post_type(get_the_ID());
        $post_meta = get_post_meta((int) $post_ID, "$meta_element", true);
        $cs_header_banner_style = get_post_meta((int) $post_ID, "cs_header_banner_style", true);
        $cs_page_breadcrumbs = get_post_meta((int) $post_ID, "cs_page_breadcrumbs", true);
        $cs_page_subheader_text_color = get_post_meta((int) $post_ID, "cs_page_subheader_text_color", true);

        $cs_brec_chk = false;
        $cs_header_banner_style = isset($cs_header_banner_style) ? $cs_header_banner_style : '';

        if (isset($post_meta) and $post_meta <> '') {

            if (isset($cs_header_banner_style) && $cs_header_banner_style == 'breadcrumb_header' && $cs_page_breadcrumbs == 'on') {
                $cs_brec_chk = true;
            } else if (isset($cs_theme_options['cs_default_header']) && $cs_header_banner_style != 'breadcrumb_header' && (isset($cs_theme_options['cs_breadcrumbs_switch']) and $cs_theme_options['cs_breadcrumbs_switch'] == 'on')) {
                $cs_brec_chk = true;
            } else if (isset($cs_theme_options['cs_default_header']) && $post_type == 'vehicles' && (isset($cs_theme_options['cs_breadcrumbs_switch']) && $cs_theme_options['cs_breadcrumbs_switch'] == 'on')) {
                $cs_brec_chk = true;
            }
        } else {
            $cs_brec_chk = true;
        }

        if ($cs_brec_chk == true) {
            ?>
            <!-- BreadCrumb -->
            <?php
            if (is_author() || is_search() || is_archive() || is_category() || is_home() || $post_type == 'vehicles' || $post_meta == '') {
                if (isset($cs_theme_options['cs_sub_header_text_color']) && $cs_theme_options['cs_sub_header_text_color'] <> '') {
                    ?>

                    <?php
                }
            } else {

                if (isset($cs_header_banner_style) and $cs_header_banner_style == 'default_header') {

                    if (isset($cs_theme_options['cs_sub_header_text_color']) && $cs_theme_options['cs_sub_header_text_color'] <> '') {
                        ?>


                        <?php
                    }
                } else if (isset($cs_page_subheader_text_color) && $cs_page_subheader_text_color != '') {
                    ?>

                    <?php
                }
            }

            cs_breadcrumbs();
        }
    }

}

/**
 * @Page Sub header title and subtitle 
 *
 *
 */
if (!function_exists('get_subheader_title')) {

    function get_subheader_title($shop_id = '') {
        global $post, $cs_theme_options;
        $meta_element = 'cs_full_data';
        $post_ID = get_the_ID();
        $post_meta = get_post_meta($post_ID, "$meta_element", true);
        $post_ID = $post->ID;
        $text_color = '';

        $cs_header_banner_style = get_post_meta((int) $post_ID, "cs_header_banner_style", true);
        $cs_sub_header_text_color = get_post_meta((int) $post_ID, "cs_page_subheader_text_color", true);
        $cs_page_title = get_post_meta((int) $post_ID, "cs_page_title", true);
        $cs_page_subheading_title = get_post_meta((int) $post_ID, "cs_page_subheading_title", true);

        $color = '';
        $text_color = '';

        $cs_page_title = (isset($cs_page_title) and $cs_page_title <> '') ? $cs_page_title : '';

        if (isset($cs_header_banner_style) and $cs_header_banner_style == 'breadcrumb_header') {
            $text_color = $cs_sub_header_text_color;
        } else {
            if (isset($cs_sub_header_text_color) and $cs_sub_header_text_color <> '') {
                $text_color = isset($cs_theme_options['cs_sub_header_text_color']) ? $cs_theme_options['cs_sub_header_text_color'] : '';
            } else {
                $text_color = isset($cs_theme_options['cs_sub_header_text_color']) ? $cs_theme_options['cs_sub_header_text_color'] : '';
            }
        }

        $color = 'style="color:' . $text_color . ' !important"';

        if (isset($cs_header_banner_style) && $cs_header_banner_style == 'breadcrumb_header') {
            if (isset($cs_page_title) && $cs_page_title == 'on') {

                echo '<h2 ' . $color . '>';
                echo get_the_title($post_ID);
                echo '</h2>';
            }

            if (isset($cs_page_subheading_title) && $cs_page_subheading_title != '') {
                echo '<p ' . $color . '>';
                echo do_shortcode($cs_page_subheading_title);
                echo '</p>';
            }
        } else {

            $cs_title_switch = $cs_theme_options['cs_title_switch'];
            if (isset($cs_title_switch) && $cs_title_switch == 'on') {

                echo '<h2 ' . $color . '>';
                echo get_the_title($post_ID);
                echo '</h2>';
            }
        }
    }

}

/**
 * @ Default page title function
 *
 *
 */
if (!function_exists('get_default_post_title')) {

    function get_default_post_title() {
        global $post, $cs_theme_options;
        $post_type = '';
        if (is_single()) {
            $post_type = get_post_type(get_the_ID());
        }

        if (empty($cs_theme_options['cs_sub_header_text_color']))
            $text_color = "";
        else
            $text_color = 'style="color:' . $cs_theme_options['cs_sub_header_text_color'] . '"';

        echo '<div class="pageinfo><h2 ' . cs_remove_force_tag_blnc_theme($text_color, false) . '>';
        if ($post_type == 'vehicles') {
            the_title();
        } else {
            cs_post_page_title();
        }
        echo '</h2></div>';
    }

}