<?php
/**
 * @Theme option function
 * @return
 *
 */
if (!function_exists('cs_options_page')) {

    function cs_options_page() {
        global $cs_theme_options, $cs_options;
        //$cs_theme_options=get_option('cs_theme_options');
        ?>

        <div class="theme-wrap fullwidth">
            <div class="inner">
                <div class="outerwrapp-layer">
                    <div class="loading_div"> <i class="icon-circle-o-notch icon-spin"></i> <br>
                        <?php esc_html_e('Saving changes...', "car-rental"); ?>
                    </div>
                    <div class="form-msg"> 
                        <i class="icon-check-circle-o"></i>
                        <div class="innermsg"></div>
                    </div>
                </div>
                <div class="row">
                    <form id="frm" method="post">
                        <?php
                        $theme_options_fields = new theme_options_fields();
                        $return = $theme_options_fields->cs_fields($cs_options);
                        ?>
                        <div class="col1">
                            <nav class="admin-navigtion">
                                <div class="logo"> <a href="#" class="logo1"><img src="<?php echo esc_url(get_template_directory_uri() . '/include/assets/images/logo-themeoption.png') ?>" /></a> <a href="#" class="nav-button"><i class="icon-align-justify"></i></a> </div>
                                <ul>
                                    <?php echo cs_remove_force_tag_theme($return[1]); ?>
                                </ul>
                            </nav>
                        </div>
                        <div class="col2">
                            <?php echo cs_remove_force_tag_theme($return[0]); /* Settings */ ?>
                        </div>
                        <div class="clear"></div>
                        <div class="footer">
                            <input type="button" id="submit_btn" name="submit_btn" class="bottom_btn_save" value="<?php esc_html_e('Save All Settings', "car-rental"); ?>" onclick="javascript:theme_option_save('<?php echo esc_js(admin_url('admin-ajax.php')) ?>');" />
                            <input type="hidden" name="action" value="theme_option_save"  />
                            <input class="bottom_btn_reset" name="reset" type="button" value="<?php esc_html_e('Reset All Options', 'car-rental'); ?>" onclick="javascript:cs_rest_all_options('<?php echo esc_js(admin_url('admin-ajax.php')) ?>');" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <!--wrap--> 
        <script type="text/javascript">
            // Sub Menus Show/hide
            jQuery(document).ready(function ($) {
                jQuery(".sub-menu").parent("li").addClass("parentIcon");
                $("a.nav-button").click(function () {
                    $(".admin-navigtion").toggleClass("navigation-small");
                });

                $("a.nav-button").click(function () {
                    $(".inner").toggleClass("shortnav");
                });

                $(".admin-navigtion > ul > li > a").click(function () {
                    var a = $(this).next('ul')
                    $(".admin-navigtion > ul > li > a").not($(this)).removeClass("changeicon")
                    $(".admin-navigtion > ul > li ul").not(a).slideUp();
                    $(this).next('.sub-menu').slideToggle();
                    $(this).toggleClass('changeicon');
                });
            });

            function show_hide(id) {
                var link = id.replace('#', '');
                jQuery('.horizontal_tab').fadeOut(0);
                jQuery('#' + link).fadeIn(400);
            }

            function toggleDiv(id) {
                jQuery('.col2').children().hide();
                jQuery(id).show();
                location.hash = id + "-show";
                var link = id.replace('#', '');
                jQuery('.categoryitems li').removeClass('active');
                jQuery(".menuheader.expandable").removeClass('openheader');
                jQuery(".categoryitems").hide();
                jQuery("." + link).addClass('active');
                jQuery("." + link).parent("ul").show().prev().addClass("openheader");
            }
            jQuery(document).ready(function () {
                jQuery(".categoryitems").hide();
                jQuery(".categoryitems:first").show();
                jQuery(".menuheader:first").addClass("openheader");
                jQuery(".menuheader").live('click', function (event) {
                    if (jQuery(this).hasClass('openheader')) {
                        jQuery(".menuheader").removeClass("openheader");
                        jQuery(this).next().slideUp(200);
                        return false;
                    }
                    jQuery(".menuheader").removeClass("openheader");
                    jQuery(this).addClass("openheader");
                    jQuery(".categoryitems").slideUp(200);
                    jQuery(this).next().slideDown(200);
                    return false;
                });

                var hash = window.location.hash.substring(1);
                var id = hash.split("-show")[0];
                if (id) {
                    jQuery('.col2').children().hide();
                    jQuery("#" + id).show();
                    jQuery('.categoryitems li').removeClass('active');
                    jQuery(".menuheader.expandable").removeClass('openheader');
                    jQuery(".categoryitems").hide();
                    jQuery("." + id).addClass('active');
                    jQuery("." + id).parent("ul").slideDown(300).prev().addClass("openheader");
                }
            });
            jQuery(function ($) {
                $("#cs_launch_date").datepicker({
                    defaultDate: "+1w",
                    dateFormat: "dd/mm/yy",
                    changeMonth: true,
                    numberOfMonths: 1,
                    onSelect: function (selectedDate) {
                        $("#cs_launch_date").datepicker();
                    }
                });
            });
        </script>
        <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/include/assets/css/jquery_ui_datepicker.css') ?>">
        <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/include/assets/css/jquery_ui_datepicker_theme.css') ?>">
        <?php
    }

}

/**
 * @Background Count function
 * @return
 *
 */
if (!function_exists('cs_bgcount')) {

    function cs_bgcount($name, $count) {
        for ($i = 0; $i <= $count; $i++) {
            $pattern['option' . $i] = $name . $i;
        }
        return $pattern;
    }

}

/**
 * @Theme Options Initilize
 * @return
 *
 */
add_action('init', 'cs_theme_options');
if (!function_exists('cs_theme_options')) {

    function cs_theme_options() {
        global $cs_options, $cs_header_colors, $cs_theme_options;
        //$cs_theme_options		= get_option('cs_theme_options');
        $on_off_option = array("show" => "on", "hide" => "off");
        $navigation_style = array("left" => "left", "center" => "center", "right" => "right");
        $temperature_setting = array("Celsius" => esc_html__("Celsius", "car-rental"), "Fahrenheit" => esc_html__("Fahrenheit", "car-rental"), "both" => esc_html__("Both", "car-rental"));
        $google_fonts = array('google_font_family_name' => array('', '', ''), 'google_font_family_url' => array('', '', ''));
        $social_network = array('social_net_icon_path' => array('', '', '', '', ''), 'social_net_awesome' => array('icon-facebook9', 'icon-dribbble7', 'icon-twitter2', 'icon-behance2', 'icon-google-plus'), 'social_net_url' => array('https://www.facebook.com/', 'https://dribbble.com/', 'https://www.twitter.com/', 'https://www.behance.net/', 'https://plus.google.com'), 'social_net_tooltip' => array('Facebook', 'Dribbble', 'Twitter', 'Behance', 'Google Plus'), 'social_font_awesome_color' => array('#cccccc', '#cccccc', '#cccccc', '#cccccc', '#cccccc'));

        $banner_fields = array('banner_field_title' => array('Banner 1'), 'banner_field_style' => array('top_banner'), 'banner_field_type' => array('code'), 'banner_field_image' => array(''), 'banner_field_url' => array('#'), 'banner_field_url_target' => array('_self'), 'banner_adsense_code' => array(''), 'banner_field_code_no' => array('0'));


        $sidebar = array(
            'sidebar' => array(
                'blogs_sidebar' => esc_html__('Blogs Sidebar', "car-rental"),
                'contact' => esc_html__('Contact', "car-rental"),
                'widgets' => esc_html__('Widgets', "car-rental"),
                'faq_sidebar' => esc_html__('Faq Sidebar', "car-rental"),
                'about_us_sidebar' => esc_html__('About Us Sidebar', "car-rental"),
                'terms_and_conditions' => esc_html__('Term and conditions', "car-rental"),
                'price_plan' => esc_html__('Price Plan', "car-rental"),
                'team_sidebar' => esc_html__('Team sidebar', "car-rental"),
                'location_sidebar' => esc_html__('Location sidebar', "car-rental"),
                'default_page_sidebar' => esc_html__('Default page sidebar', "car-rental"),
                'car_listing' => esc_html__('Car Listing', "car-rental"),
            )
        );

        $menus_locations = array_flip(get_nav_menu_locations());
        $breadcrumb_option = array("option1" => "option1", "option2" => "option2", "option3" => "option3");
        $deafult_sub_header = array('breadcrumbs_sub_header' => esc_html__('Breadcrumbs Sub Header', "car-rental"), 'slider' => esc_html__('Revolution Slider', "car-rental"), 'no_header' => esc_html__('No sub Header', "car-rental"));
        $padding_sub_header = array('Default' => 'default', 'Custom' => 'custom');

        #Menus List
        $menu_option = get_registered_nav_menus();
        foreach ($menu_option as $key => $menu) {
            $menu_location = $key;
            $menu_locations = get_nav_menu_locations();
            $menu_object = (isset($menu_locations[$menu_location]) ? wp_get_nav_menu_object($menu_locations[$menu_location]) : null);
            $menu_name[] = (isset($menu_object->name) ? $menu_object->name : '');
        }

        #Mailchimp List
        $mail_chimp_list[] = '';
        if (isset($cs_theme_options['cs_mailchimp_key'])) {
            $mailchimp_option = $cs_theme_options['cs_mailchimp_key'];
            if ($mailchimp_option <> '') {
                $mc_list = cs_mailchimp_list($mailchimp_option);
                if (is_array($mc_list) && isset($mc_list['data'])) {
                    foreach ($mc_list['data'] as $list) {
                        $mail_chimp_list[$list['id']] = $list['name'];
                    }
                }
            }
        }

        #Map Search Pages
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-ad-search.php',
            'hierarchical' => 0
        ));

        $map_options = array();
        $map_options[] = 'Default';
        foreach ($pages as $page) {
            $map_options[$page->ID] = $page->post_title;
        }

        #google fonts array
        $g_fonts = cs_googlefont_list();
        $g_fonts_atts = cs_get_google_font_attribute();

        global $cs_theme_options;
        $cs_sidebar = '';   
        if (isset($cs_theme_options) and $cs_theme_options <> '') {
            if (isset($cs_theme_options['sidebar']) && is_array($cs_theme_options['sidebar']) && count($cs_theme_options['sidebar']) > 0) {
                $cs_sidebar = array('sidebar' => $cs_theme_options['sidebar']);
            } elseif (!isset($cs_theme_options['sidebar'])) {
                $cs_sidebar = array('sidebar' => array());
            }
        } else {
            $cs_sidebar = $sidebar;
        }

        #Set the Options Array
        $cs_options = array();
        $cs_header_colors = cs_header_setting();

        #general setting options
        $cs_options[] = array(
            "name" => esc_html__("General", "car-rental"),
            "fontawesome" => 'icon-cog3',
            "type" => "heading",
            "options" => array(
                'tab-global-setting' => esc_html__("global", "car-rental"),
                'tab-header-options' => esc_html__("Header", "car-rental"),
                'tab-sub-header-options' => esc_html__("Sub Header", "car-rental"),
                'tab-footer-options' => esc_html__("Footer", "car-rental"),
                'tab-social-setting' => esc_html__("social icons", "car-rental"),
                'tab-social-network' => esc_html__("social sharing", "car-rental"),
                'tab-custom-code' => esc_html__("custom code", "car-rental"),
                'banner-fields' => esc_html__('Ads Unit Settings', 'car-rental'),
            )
        );
        $cs_options[] = array(
            "name" => esc_html__("color", "car-rental"),
            "fontawesome" => 'icon-magic',
            "hint_text" => "",
            "type" => "heading",
            "options" => array(
                'tab-general-color' => esc_html__("general", "car-rental"),
                'tab-header-color' => esc_html__("Header", "car-rental"),
                'tab-footer-color' => esc_html__("Footer", "car-rental"),
                'tab-heading-color' => esc_html__("headings", "car-rental"),
            )
        );
        $cs_options[] = array(
            "name" => esc_html__("typography / fonts", "car-rental"),
            "fontawesome" => 'icon-font',
            "desc" => "",
            "hint_text" => "",
            "type" => "heading",
            "options" => array(
                'tab-custom-font' => esc_html__('Custom Font', "car-rental"),
                'tab-font-family' => esc_html__('font family', "car-rental"),
                'tab-font-size' => esc_html__('Font Size', "car-rental"),
            )
        );
        $cs_options[] = array(
            "name" => esc_html__("sidebar", "car-rental"),
            "fontawesome" => 'icon-columns',
            "id" => "tab-sidebar",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );
        $cs_options[] = array(
            "name" => esc_html__("SEO", "car-rental"),
            "fontawesome" => 'icon-globe6',
            "id" => "tab-seo",
            "std" => "",
            "type" => "main-heading",
            "options" => ""
        );
        $cs_options[] = array(
            "name" => esc_html__("global", "car-rental"),
            "id" => "tab-global-setting",
            "type" => "sub-heading"
        );
        $cs_options[] = array(
            "name" => esc_html__("Layout", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Layout type", "car-rental"),
            "id" => "cs_layout",
            "std" => "full_width",
            "options" => array(
                "boxed" => esc_html__("Boxed", "car-rental"),
                "full_width" => esc_html__("Full width", "car-rental")
            ),
            "type" => "layout",
        );

        $cs_options[] = array(
            "name" => "",
            "id" => "cs_horizontal_tab",
            "class" => "horizontal_tab",
            "type" => "horizontal_tab",
            "std" => "",
            "options" => array('Background' => 'background_tab', 'Pattern' => 'pattern_tab', 'Custom Image' => 'custom_image_tab')
        );

        $cs_options[] = array(
            "name" => esc_html__("Background image", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Choose from Predefined Background images.", "car-rental"),
            "id" => "cs_bg_image",
            "class" => "cs_background_",
            "path" => "background",
            "tab" => "background_tab",
            "std" => "bg1",
            "type" => "layout_body",
            "display" => "block",
            "options" => cs_bgcount('bg', '10')
        );

        $cs_options[] = array("name" => esc_html__("Background pattern", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Choose from Predefined Pattern images.", "car-rental"),
            "id" => "cs_bg_image",
            "class" => "cs_background_",
            "path" => "patterns",
            "tab" => "pattern_tab",
            "std" => "bg1",
            "type" => "layout_body",
            "display" => "none",
            "options" => cs_bgcount('pattern', '27')
        );
        $cs_options[] = array(
            "name" => esc_html__("Custom image", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("This option can be used only with Boxed Layout.", "car-rental"),
            "id" => "cs_custom_bgimage",
            "std" => "",
            "tab" => "custom_image_tab",
            "display" => "none",
            "type" => "upload logo"
        );
        $cs_options[] = array("name" => esc_html__("Background image position", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Choose image position for body background", "car-rental"),
            "id" => "cs_bgimage_position",
            "std" => esc_html__("Center Repeat", "car-rental"),
            "type" => "select",
            "options" => array(
                "option1" => "no-repeat center top",
                "option2" => "repeat center top",
                "option3" => "no-repeat center",
                "option4" => "Repeat Center",
                "option5" => "no-repeat left top",
                "option6" => "repeat left top",
                "option7" => "no-repeat fixed center",
                "option8" => "no-repeat fixed center / cover"
            )
        );

        if (!function_exists('has_site_icon') || !wp_site_icon()) {
            $cs_options[] = array("name" => esc_html__("Custom favicon", "car-rental"),
                "desc" => "",
                "hint_text" => esc_html__("Custom favicon for your site", "car-rental"),
                "id" => "cs_custom_favicon",
                "std" => get_template_directory_uri() . "/assets/images/favicon.png",
                "type" => "upload logo"
            );
        }

        $cs_options[] = array("name" => esc_html__("Smooth Scroll", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Lightweight Script for Page Scrolling animation", "car-rental"),
            "id" => "cs_smooth_scroll",
            "std" => "off",
            "type" => "checkbox",
            "options" => $on_off_option
        );


        $cs_options[] = array("name" => esc_html__("Responsive", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set responsive design layout for mobile devices On/Off here", "car-rental"),
            "id" => "cs_responsive",
            "std" => "off",
            "type" => "checkbox",
            "options" => $on_off_option
        );

        if (class_exists('cs_framework')) {
            $cs_options[] = array("name" => "Language Settings",
                "id" => "tab-general-options",
                "std" => esc_html__("Language Settings", "car-rental"),
                "type" => "section",
                "options" => ""
            );


            $dir = cs_framework::plugin_dir() . '/languages/';
            $cs_plugin_language[''] = esc_html__("Select Language File", 'car-rental');
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        $ext = pathinfo($file, PATHINFO_EXTENSION);
                        if ($ext == 'mo') {
                            $cs_plugin_language[$file] = $file;
                        }
                    }
                    closedir($dh);
                }
            }

            $cs_options[] = array("name" => esc_html__("Select Language", "car-rental"),
                "desc" => "",
                "hint_text" => "",
                "id" => "cs_language_file",
                "std" => "30",
                "type" => "select",
                "options" => $cs_plugin_language,
            );
        }

        // Header options start
        $cs_options[] = array("name" => esc_html__("header", "car-rental"),
            "id" => "tab-header-options",
            "type" => "sub-heading"
        );

        $cs_options[] = array("name" => esc_html__("Select Header", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_header_style",
            "std" => "header-1",
            "type" => "select_values",
            "options" => array('header-1' => esc_html__("Header 1", "car-rental"), 'header-2' => esc_html__("Header 2", "car-rental")),
        );

        $cs_options[] = array("name" => esc_html__("Logo", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Upload your custom logo in .png .jpg .gif formats only.", "car-rental"),
            "id" => "cs_custom_logo",
            "std" => get_template_directory_uri() . "/assets/images/logo.png",
            "type" => "upload logo"
        );
        $cs_options[] = array("name" => esc_html__("Logo Height", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set exact logo height otherwise logo will not display normally.", "car-rental"),
            "id" => "cs_logo_height",
            "min" => '0',
            "max" => '100',
            "std" => "",
            "type" => "range"
        );
        $cs_options[] = array("name" => esc_html__("logo width", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set exact logo width otherwise logo will not display normally.", "car-rental"),
            "id" => "cs_logo_width",
            "min" => '0',
            "max" => '210',
            "std" => "",
            "type" => "range"
        );

        $cs_options[] = array("name" => esc_html__("Logo margin top", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Logo spacing margin from top", "car-rental"),
            "id" => "cs_logo_margint",
            "min" => '0',
            "max" => '200',
            "std" => "0",
            "type" => "range"
        );
        $cs_options[] = array("name" => esc_html__("Logo margin bottom", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Logo spacing margin from bottom.", "car-rental"),
            "id" => "cs_logo_marginb",
            "min" => '-60',
            "max" => '200',
            "std" => "0",
            "type" => "range"
        );
        $cs_options[] = array("name" => esc_html__("Logo margin right", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Logo spacing margin from right.", "car-rental"),
            "id" => "cs_logo_marginr",
            "min" => '0',
            "max" => '200',
            "std" => "0",
            "type" => "range"
        );
        $cs_options[] = array("name" => esc_html__("Logo margin left", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Logo spacing margin from left", "car-rental"),
            "id" => "cs_logo_marginl",
            "min" => '-20',
            "max" => '200',
            "std" => "0",
            "type" => "range"
        );
        /* header element settings */

        $cs_options[] = array("name" => esc_html__("Header Elements", "car-rental"),
            "id" => "tab-header-options",
            "std" => esc_html__("Header Elements", "car-rental"),
            "type" => "section",
            "options" => ""
        );


        if (function_exists('is_woocommerce')) {
            $cs_options[] = array(
                "name" => esc_html__("Cart Count", "car-rental"),
                "desc" => "",
                "hint_text" => esc_html__("Enable/Disable Woocommerce Cart Count", "car-rental"),
                "id" => "cs_woocommerce_switch",
                "std" => "off",
                "type" => "checkbox",
                "options" => $on_off_option
            );
        }

        $cs_options[] = array("name" => esc_html__("WPML", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set WordPress Multi Language switcher On/Off in header", "car-rental"),
            "id" => "cs_wpml_switch",
            "std" => "on",
            "type" => "wpml",
            "options" => $on_off_option
        );

        $cs_options[] = array("name" => esc_html__("Sticky Header On/Off", "car-rental"),
            "desc" => "",
            "id" => "cs_sitcky_header_switch",
            "hint_text" => esc_html__("If you enable this option , header will be fixed on top of your browser window.", "car-rental"),
            "std" => "",
            "type" => "checkbox",
            "options" => $on_off_option
        );
        $cs_options[] = array("name" => esc_html__("Sticky Logo", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set sticky logo Image", "car-rental"),
            "id" => "cs_sticky_logo",
            "std" => get_template_directory_uri() . "/assets/images/logo.png",
            "type" => "upload logo");


        $cs_options[] = array("name" => esc_html__("Booking", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_booking_title",
            "std" => esc_html__("Booking", "car-rental"),
            "type" => "text",
        );
        $cs_options[] = array("name" => esc_html__("Booking link", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_booking_link",
            "std" => "http://",
            "type" => "text",
        );

        $cs_options[] = array("name" => esc_html__("Help Text", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_help_text",
            "std" => esc_html__("Need help?", "car-rental"),
            "type" => "text",
        );
        $cs_options[] = array("name" => esc_html__("Booking Number", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_help_phone",
            "std" => "",
            "type" => "textarea",
        );
        $cs_options[] = array("name" => esc_html__("Booking link", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_help_email",
            "std" => "",
            "type" => "textarea",
        );

        /* sub header element settings */
        $cs_options[] = array("name" => esc_html__("sub header", "car-rental"),
            "id" => "tab-sub-header-options",
            "type" => "sub-heading"
        );

        $cs_options[] = array("name" => esc_html__("Default", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Sub Header settings made here will be implemented on all pages.", "car-rental"),
            "id" => "cs_default_header",
            "std" => esc_html__("Breadcrumbs Sub Header", "car-rental"),
            "type" => "default header",
            "options" => $deafult_sub_header
        );

        $cs_options[] = array("name" => esc_html__("Header Border Color", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_header_border_color",
            "std" => "",
            "type" => "color"
        );

        $cs_options[] = array("name" => esc_html__("Revolution Slider", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Please select Revolution Slider if already included in package. Otherwise buy Sliders from Code canyon But its optional", "car-rental"),
            "id" => "cs_custom_slider",
            "std" => "",
            "type" => "slider code",
            "options" => ''
        );
        $cs_options[] = array("name" => esc_html__("Padding Top", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set custom padding for sub header content top area.", "car-rental"),
            "id" => "cs_sh_paddingtop",
            "min" => '0',
            "max" => '200',
            "std" => "0",
            "type" => "range"
        );
        $cs_options[] = array("name" => esc_html__("Padding Bottom", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set custom padding for sub header content bottom area.", "car-rental"),
            "id" => "cs_sh_paddingbottom",
            "min" => '0',
            "max" => '200',
            "std" => "0",
            "type" => "range"
        );
        $cs_options[] = array("name" => esc_html__("Page Title", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set page title On/Off in sub header", "car-rental"),
            "id" => "cs_title_switch",
            "std" => "on",
            "type" => "checkbox"
        );

        $cs_options[] = array("name" => esc_html__("Breadcrumbs", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_breadcrumbs_switch",
            "std" => "on",
            "type" => "checkbox"
        );

        $cs_options[] = array("name" => esc_html__("Text Color", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_sub_header_text_color",
            "std" => "#333333",
            "type" => "color"
        );
        $cs_options[] = array("name" => esc_html__("Border Color", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_sub_header_border_color",
            "std" => "",
            "type" => "color"
        );


        // start footer options    

        $cs_options[] = array("name" => esc_html__("footer options", "car-rental"),
            "id" => "tab-footer-options",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => esc_html__("Footer section", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("enable/disable footer area", "car-rental"),
            "id" => "cs_footer_switch",
            "std" => "on",
            "type" => "checkbox"
        );
        $cs_options[] = array("name" => esc_html__("Footer Widgets", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("enable/disable footer widget area", "car-rental"),
            "id" => "cs_footer_widget",
            "std" => "on",
            "type" => "checkbox"
        );


        $cs_options[] = array("name" => esc_html__("Footer Logo", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Like footer logo or Credits Cards Images", "car-rental"),
            "id" => "cs_footer_logo",
            "std" => get_template_directory_uri() . "/assets/images/logo.png",
            "type" => "upload logo");


        $cs_options[] = array("name" => esc_html__("Footer logo Link", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("set custom footer logo link", "car-rental"),
            "id" => "cs_footer_logo_link",
            "std" => "",
            "type" => "text");


        $cs_options[] = array("name" => esc_html__("Footer App Logo", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Like footer logo or Credits Cards Images", "car-rental"),
            "id" => "cs_footer_app",
            "std" => get_template_directory_uri() . "/assets/images/app-store.jpg",
            "type" => "upload logo");

        $cs_options[] = array("name" => esc_html__("Footer App link", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("set custom footer logo link", "car-rental"),
            "id" => "cs_footer_app_link",
            "std" => "",
            "type" => "text");

        $cs_options[] = array("name" => esc_html__("Footer App logo", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set custom Footer Background Image", "car-rental"),
            "id" => "cs_footer_google_play",
            "std" => get_template_directory_uri() . "/assets/images/google-play.jpg",
            "type" => "upload logo");

        $cs_options[] = array("name" => esc_html__("Google Play link", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("set custom footer logo link", "car-rental"),
            "id" => "cs_footer_google_app",
            "std" => "",
            "type" => "text");

        $cs_options[] = array("name" => esc_html__("Copyright Text", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("write your own copyright text", "car-rental"),
            "id" => "cs_copy_right",
            "std" => "&copy; 2014 Hotel Name All rights reserved. Design by <a class='cscolor' href='#'>Chimp Studio</a>",
            "type" => "textarea"
        );
        $cs_options[] = array("name" => esc_html__("Footer Widgets", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set footer widgets sidebar", "car-rental"),
            "id" => "cs_footer_widget_sidebar",
            "std" => "footer-widget-1",
            "type" => "select_sidebar",
            "options" => $cs_sidebar,
        );
        // End footer tab setting
        /* general colors */
        $cs_options[] = array("name" => esc_html__("general colors", "car-rental"),
            "id" => "tab-general-color",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => esc_html__("Theme Color", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Choose theme skin color", "car-rental"),
            "id" => "cs_theme_color",
            "std" => "#00285f",
            "type" => "color"
        );

        $cs_options[] = array("name" => esc_html__("Background Color", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Choose Body Background Color", "car-rental"),
            "id" => "cs_bg_color",
            "std" => "#ffffff",
            "type" => "color"
        );

        $cs_options[] = array("name" => esc_html__("Body Text Color", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Choose text color", "car-rental"),
            "id" => "cs_text_color",
            "std" => "#555555",
            "type" => "color"
        );

        // start top strip tab options
        $cs_options[] = array("name" => esc_html__("header colors", "car-rental"),
            "id" => "tab-header-color",
            "type" => "sub-heading"
        );


        // start header color tab options
        $cs_options[] = array("name" => esc_html__("Header Colors", "car-rental"),
            "id" => "tab-header-color",
            "std" => esc_html__("Header Colors", "car-rental"),
            "type" => "section",
            "options" => ""
        );
        $cs_options[] = array("name" => esc_html__("Background Color", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Change Header background color", "car-rental"),
            "id" => "cs_header_bgcolor",
            "std" => "",
            "type" => "color"
        );
        $cs_options[] = array(
            "name" => esc_html__("Navigation Background Color", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Change Header Navigation Background color", "car-rental"),
            "id" => "cs_nav_bgcolor",
            "std" => "#00285f",
            "type" => "color"
        );

        $cs_options[] = array("name" => esc_html__("Menu Link color", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Change Header Menu Link color", "car-rental"),
            "id" => "cs_menu_color",
            "std" => "#ffffff",
            "type" => "color"
        );

        $cs_options[] = array("name" => esc_html__("Menu Active Link color", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Change Header Menu Active Link color", "car-rental"),
            "id" => "cs_menu_active_color",
            "std" => "#f78b00",
            "type" => "color"
        );


        $cs_options[] = array("name" => esc_html__("Submenu Background", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Change Submenu Background color", "car-rental"),
            "id" => "cs_submenu_bgcolor",
            "std" => "#fffff",
            "type" => "color",
        );

        $cs_options[] = array("name" => esc_html__("Submenu Link Color", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Change Submenu Link color", "car-rental"),
            "id" => "cs_submenu_color",
            "std" => "#444444",
            "type" => "color"
        );

        $cs_options[] = array("name" => esc_html__("Submenu Hover Link Color", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Change Submenu Hover Link color", "car-rental"),
            "id" => "cs_submenu_hover_color",
            "std" => "#ffffff",
            "type" => "color"
        );

        /* footer colors */
        $cs_options[] = array("name" => esc_html__("footer colors", "car-rental"),
            "id" => "tab-footer-color",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => esc_html__("Footer Background Color", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_footerbg_color",
            "std" => "#fff",
            "type" => "color"
        );

        $cs_options[] = array("name" => esc_html__("Footer Title Color", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_title_color",
            "std" => "#000",
            "type" => "color"
        );

        $cs_options[] = array("name" => esc_html__("Footer Text Color", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_footer_text_color",
            "std" => "#444",
            "type" => "color"
        );

        $cs_options[] = array("name" => esc_html__("Footer Link Color", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_link_color",
            "std" => "#444",
            "type" => "color"
        );



        $cs_options[] = array("name" => esc_html__("Copyright Text", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_copyright_text_color",
            "std" => "#444",
            "type" => "color"
        );

        /* heading colors */
        $cs_options[] = array("name" => esc_html__("heading colors", "car-rental"),
            "id" => "tab-heading-color",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => esc_html__("heading h1", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_h1_color",
            "std" => "#333333",
            "type" => "color"
        );

        $cs_options[] = array("name" => esc_html__("heading h2", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_h2_color",
            "std" => "#333333",
            "type" => "color"
        );

        $cs_options[] = array("name" => esc_html__("heading h3", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_h3_color",
            "std" => "#333333",
            "type" => "color"
        );

        $cs_options[] = array("name" => esc_html__("heading h4", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_h4_color",
            "std" => "#333333",
            "type" => "color"
        );

        $cs_options[] = array("name" => esc_html__("heading h5", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_h5_color",
            "std" => "#333333",
            "type" => "color"
        );

        $cs_options[] = array("name" => esc_html__("heading h6", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_h6_color",
            "std" => "#333333",
            "type" => "color"
        );

        /* start custom font family */
        $cs_options[] = array("name" => esc_html__("Custom Font", "car-rental"),
            "id" => "tab-custom-font",
            "type" => "sub-heading"
        );

        $cs_options[] = array("name" => esc_html__("Custom Font .woff", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Custom font for your site upload .woff format file.", "car-rental"),
            "id" => "cs_custom_font_woff",
            "std" => "",
            "type" => "upload font"
        );

        $cs_options[] = array("name" => esc_html__("Custom Font .ttf", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Custom font for your site upload .ttf format file.", "car-rental"),
            "id" => "cs_custom_font_ttf",
            "std" => "",
            "type" => "upload font"
        );

        $cs_options[] = array("name" => esc_html__("Custom Font .svg", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Custom font for your site upload .svg format file.", "car-rental"),
            "id" => "cs_custom_font_svg",
            "std" => "",
            "type" => "upload font"
        );

        $cs_options[] = array("name" => esc_html__("Custom Font .eot", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Custom font for your site upload .eot format file.", "car-rental"),
            "id" => "cs_custom_font_eot",
            "std" => "",
            "type" => "upload font"
        );

        /* start font family */
        $cs_options[] = array("name" => esc_html__("font family", "car-rental"),
            "id" => "tab-font-family",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => esc_html__("Content Font", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set fonts for Body text", "car-rental"),
            "id" => "cs_content_font",
            "std" => "Archivo Narrow",
            "type" => "gfont_select",
            "options" => $g_fonts
        );
        $cs_options[] = array("name" => esc_html__("Content Font Attribute", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set Font Attribute", "car-rental"),
            "id" => "cs_content_font_att",
            "std" => "regular",
            "type" => "gfont_att_select",
            "options" => $g_fonts_atts
        );
        $cs_options[] = array("name" => esc_html__("Main Menu Font", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set font for main Menu. It will be applied to sub menu as well", "car-rental"),
            "id" => "cs_mainmenu_font",
            "std" => "Archivo Narrow",
            "type" => "gfont_select",
            "options" => $g_fonts
        );
        $cs_options[] = array("name" => esc_html__("Main Menu Font Attribute", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set Font Attribute", "car-rental"),
            "id" => "cs_mainmenu_font_att",
            "std" => "regular",
            "type" => "gfont_att_select",
            "options" => $g_fonts_atts
        );
        $cs_options[] = array("name" => esc_html__("Headings Font", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Select font for Headings. It will apply on all posts and pages headings", "car-rental"),
            "id" => "cs_heading_font",
            "std" => "Montserrat",
            "type" => "gfont_select",
            "options" => $g_fonts
        );
        $cs_options[] = array("name" => esc_html__("Headings Font Attribute", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set Font Attribute", "car-rental"),
            "id" => "cs_heading_font_att",
            "std" => "700",
            "type" => "gfont_att_select",
            "options" => $g_fonts_atts
        );
        $cs_options[] = array("name" => esc_html__("Widget Headings Font", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set font for Widget Headings", "car-rental"),
            "id" => "cs_widget_heading_font",
            "std" => "Montserrat",
            "type" => "gfont_select",
            "options" => $g_fonts
        );
        $cs_options[] = array("name" => esc_html__("Widget Headings Font Attribute", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set Font Attribute", "car-rental"),
            "id" => "cs_widget_heading_font_att",
            "std" => "700",
            "type" => "gfont_att_select",
            "options" => $g_fonts_atts
        );
        /* start font size */
        $cs_options[] = array("name" => esc_html__("Font size", "car-rental"),
            "id" => "tab-font-size",
            "type" => "sub-heading"
        );

        $cs_options[] = array("name" => esc_html__("Content", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_content_size",
            "min" => '6',
            "max" => '50',
            "std" => "14",
            "type" => "range"
        );
        $cs_options[] = array("name" => esc_html__("Main Menu", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_mainmenu_size",
            "min" => '6',
            "max" => '50',
            "std" => "14",
            "type" => "range"
        );
        $cs_options[] = array("name" => esc_html__("Heading 1", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_1_size",
            "min" => '6',
            "max" => '50',
            "std" => "24",
            "type" => "range"
        );
        $cs_options[] = array("name" => esc_html__("Heading 2", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_2_size",
            "min" => '6',
            "max" => '50',
            "std" => "18",
            "type" => "range"
        );
        $cs_options[] = array("name" => esc_html__("Heading 3", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_3_size",
            "min" => '6',
            "max" => '50',
            "std" => "16",
            "type" => "range"
        );
        $cs_options[] = array("name" => esc_html__("Heading 4", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_4_size",
            "min" => '6',
            "max" => '50',
            "std" => "16",
            "type" => "range"
        );
        $cs_options[] = array("name" => esc_html__("Heading 5", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_5_size",
            "min" => '6',
            "max" => '50',
            "std" => "14",
            "type" => "range"
        );
        $cs_options[] = array("name" => esc_html__("Heading 6", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_6_size",
            "min" => '6',
            "max" => '50',
            "std" => "14",
            "type" => "range"
        );

        $cs_options[] = array("name" => esc_html__("Widget Heading", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_widget_heading_size",
            "min" => '6',
            "max" => '50',
            "std" => "15",
            "type" => "range"
        );
        $cs_options[] = array("name" => esc_html__("Section Heading", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_section_heading_size",
            "min" => '6',
            "max" => '50',
            "std" => "24",
            "type" => "range"
        );
        /* social icons setting */
        $cs_options[] = array("name" => esc_html__("social icons", "car-rental"),
            "id" => "tab-social-setting",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => esc_html__("Social Icon", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_social_network_setting",
            "std" => "",
            "type" => "checkbox"
        );

        $cs_options[] = array("name" => esc_html__("Social Network", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_social_network",
            "std" => "",
            "type" => "networks",
            "options" => $social_network
        );

        /* social Network setting */
        $cs_options[] = array("name" => esc_html__("social Sharing", "car-rental"),
            "id" => "tab-social-network",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => esc_html__("Facebook", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_facebook_share",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => esc_html__("Twitter", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_twitter_share",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => esc_html__("Google Plus", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_google_plus_share",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => esc_html__("Tumblr", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_tumblr_share",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => esc_html__("Dribbble", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_dribbble_share",
            "std" => "off",
            "type" => "checkbox");

        $cs_options[] = array("name" => esc_html__("Instagram", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_instagram_share",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => esc_html__("StumbleUpon", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_stumbleupon_share",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => esc_html__("youtube", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_youtube_share",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => esc_html__("share more", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_share_share",
            "std" => "off",
            "type" => "checkbox");

        /* custom code setting */
        $cs_options[] = array("name" => esc_html__("custom code", "car-rental"),
            "id" => "tab-custom-code",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => esc_html__("Custom Css", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("write you custom css without style tag", "car-rental"),
            "id" => "cs_custom_css",
            "std" => "",
            "type" => "textarea"
        );

        $cs_options[] = array("name" => esc_html__("Custom JavaScript", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("write you custom js without script tag", "car-rental"),
            "id" => "cs_custom_js",
            "std" => "",
            "type" => "textarea"
        );

        $cs_options[] = array("name" => esc_html__("Ads Unit", 'car-rental'),
            "id" => "banner-fields",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => esc_html__("Ads Unit Settings", 'car-rental'),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_banner_fields",
            "std" => "",
            "type" => "banner_fields",
            "options" => $banner_fields
        );



        /* sidebar tab */
        $cs_options[] = array("name" => esc_html__("sidebar", "car-rental"),
            "id" => "tab-sidebar",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => esc_html__("Sidebar", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Select a sidebar from the list already given. (Nine pre-made sidebars are given)", "car-rental"),
            "id" => "cs_sidebar",
            "std" => $sidebar,
            "type" => "sidebar",
            "options" => $sidebar
        );

        $cs_options[] = array("name" => esc_html__("post layout", "car-rental"),
            "id" => "cs_non_metapost_layout",
            "std" => esc_html__("single post layout", "car-rental"),
            "type" => "section",
            "options" => ""
        );
        $cs_options[] = array("name" => esc_html__("Single Post Layout", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Use this option to set default layout. It will be applied to all posts", "car-rental"),
            "id" => "cs_single_post_layout",
            "std" => "sidebar_right",
            "type" => "layout",
            "options" => array(
                "no_sidebar" => esc_html__("full width", "car-rental"),
                "sidebar_left" => esc_html__("sidebar left", "car-rental"),
                "sidebar_right" => esc_html__("sidebar right", "car-rental"),
            )
        );

        $cs_options[] = array("name" => esc_html__("Single Layout Sidebar", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Select Single Post Layout of your choice for sidebar layout. You cannot select it for full width layout", "car-rental"),
            "id" => "cs_single_layout_sidebar",
            "std" => "Default Pages",
            "type" => "select_sidebar",
            "options" => $cs_sidebar
        );

        $cs_options[] = array("name" => esc_html__("default pages", "car-rental"),
            "id" => "default_pages",
            "std" => esc_html__("default pages", "car-rental"),
            "type" => "section",
            "options" => ""
        );
        $cs_options[] = array("name" => esc_html__("Default Pages Layout", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set Sidebar for all pages like Search, Author Archive, Category Archive etc", "car-rental"),
            "id" => "cs_default_page_layout",
            "std" => "sidebar_right",
            "type" => "layout",
            "options" => array(
                "no_sidebar" => esc_html__("full width", "car-rental"),
                "sidebar_left" => esc_html__("sidebar left", "car-rental"),
                "sidebar_right" => esc_html__("sidebar right", "car-rental"),
            )
        );
        $cs_options[] = array("name" => esc_html__("Sidebar", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Select pre-made sidebars for default pages on sidebar layout. Full width layout cannot have sidebars", "car-rental"),
            "id" => "cs_default_layout_sidebar",
            "std" => esc_html__("Default Pages", "car-rental"),
            "type" => "select_sidebar",
            "options" => $cs_sidebar
        );
        $cs_options[] = array("name" => esc_html__("Excerpt", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Set excerpt length/limit from here. It controls text limit for post's content", "car-rental"),
            "id" => "cs_excerpt_length",
            "std" => "175",
            "type" => "text"
        );

        /* SEO */
        $cs_options[] = array("name" => esc_html__("SEO", "car-rental"),
            "id" => "tab-seo",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => '<b>' . esc_html__("Attention for External SEO Plugins!", "car-rental") . '</b>',
            "id" => "header_postion_attention",
            "std" => '<strong>' . esc_html__("  If you are using any external Seo plugin, Turn OFF these options", "car-rental") . '</strong>',
            "type" => "announcement"
        );

        $cs_options[] = array("name" => esc_html__("Built-in SEO fields", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Turn Seo options On/Off", "car-rental"),
            "id" => "cs_builtin_seo_fields",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => esc_html__("Meta Description", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("HTML attributes that explain the contents of web pages commonly used on search engine result pages (SERPs) for pages snippets", "car-rental"),
            "id" => "cs_meta_description",
            "std" => "",
            "type" => "text"
        );

        $cs_options[] = array("name" => esc_html__("Meta Keywords", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Attributes of meta tags, a list of comma-separated words included in the HTML of a Web page that describe the topic of that page", "car-rental"),
            "id" => "cs_meta_keywords",
            "std" => "",
            "type" => "text"
        );


        /* maintenance mode */
        $cs_options[] = array("name" => esc_html__("Maintenance Mode", "car-rental"),
            "fontawesome" => 'icon-tasks',
            "id" => "tab-maintenace-mode",
            "std" => "",
            "type" => "main-heading",
            "options" => ""
        );
        $cs_options[] = array("name" => esc_html__("Maintenance Mode", 'car-rental'),
            "id" => "tab-maintenace-mode",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => esc_html__("Maintenace Page", 'car-rental'),
            "desc" => "",
            "hint_text" => esc_html__("Users will see Maintenance page & logged in Admin will see normal site.", 'car-rental'),
            "id" => "cs_maintenance_page_switch",
            "std" => "off",
            "type" => "checkbox");

        $cs_options[] = array("name" => esc_html__("Show Logo", 'car-rental'),
            "desc" => "",
            "hint_text" => esc_html__("Show/Hide logo on Maintenance. Logo can be uploaded from General > Header in CS Theme options.", 'car-rental'),
            "id" => "cs_maintenance_logo_switch",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => esc_html__("Show Newsletter", 'car-rental'),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_maintenance_newsletter_switch",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => esc_html__("Newsletter Text", 'car-rental'),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_newsletter_text",
            "std" => esc_html__("Weekly Newsletter", "car-rental"),
            "type" => "text"
        );

        $cs_options[] = array("name" => esc_html__("Show Social", 'car-rental'),
            "desc" => "",
            "hint_text" => esc_html__("Show/Hide logo on Maintenance. Logo can be uploaded from General > Header in CS Theme options.", 'car-rental'),
            "id" => "cs_maintenance_social_switch",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => esc_html__("Social Text", 'car-rental'),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_social_text",
            "std" => "Follow Us",
            "type" => "text"
        );

        $cs_options[] = array("name" => esc_html__("Maintenance background Image", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Upload your maintenance page logo in .png .jpg .gif formats only.", "car-rental"),
            "id" => "cs_maintenance_bg_img",
            "std" => get_template_directory_uri() . "/assets/images/undrconstruction.png",
            "type" => "upload logo"
        );

        $cs_options[] = array("name" => esc_html__("Maintenance Page Logo", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Upload your maintenance page logo in .png .jpg .gif formats only.", "car-rental"),
            "id" => "cs_maintenance_custom_logo",
            "std" => get_template_directory_uri() . "/assets/images/under-logo.png",
            "type" => "upload logo"
        );

        $cs_options[] = array("name" => esc_html__("Maintenance Text", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Text for Maintenance page. Insert some basic HTML or use shortcodes here.", "car-rental"),
            "id" => "cs_maintenance_text",
            "std" => "<h1>Sorry, We are down for maintenance </h1><p>We're currently under maintenance, if all goas as planned we'll be back in</p>",
            "type" => "textarea"
        );

        $cs_options[] = array("name" => esc_html__("Launch Date", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Estimated date for completion of site on Maintenance page.", "car-rental"),
            "id" => "cs_launch_date",
            "std" => gmdate("dd/mm/yy"),
            "type" => "text"
        );


        /* api options tab */
        $cs_options[] = array("name" => esc_html__("Api settings", "car-rental"),
            "fontawesome" => 'icon-chain',
            "id" => "tab-api-options",
            "std" => "",
            "type" => "main-heading",
            "options" => ""
        );
        //Start Twitter Api    
        $cs_options[] = array("name" => esc_html__("All api settings", "car-rental"),
            "id" => "tab-api-options",
            "type" => "sub-heading"
        );

        $cs_options[] = array("name" => esc_html__("Attention for API Settings!", "car-rental"),
            "id" => "header_postion_attention",
            "std" => esc_html__("API Settings allows admin of the site to show their activity on site semi-automatically. Set your social account API once, it will be update your social activity automatically on your site.", "car-rental"),
            "type" => "announcement"
        );

        //start mailChimp api
        $cs_options[] = array("name" => esc_html__("Mail Chimp", "car-rental"),
            "id" => "mailchimp",
            "std" => esc_html__("Mail Chimp", "car-rental"),
            "type" => "section",
            "options" => ""
        );
        $cs_options[] = array("name" => esc_html__("Mail Chimp Key", "car-rental"),
            "desc" => esc_html__("Enter a valid Mail Chimp API key here to get started. Once you've done that, you can use the Mail Chimp Widget from the Widgets menu. You will need to have at least Mail Chimp list set up before the using the widget. You can get your mail chimp activation key", "car-rental"),
            "hint_text" => esc_html__("Get your mailchimp key by <a href='https://login.mailchimp.com/' target='_blank'>Clicking Here </a>", "car-rental"),
            "id" => "cs_mailchimp_key",
            "std" => "90f86a57314446ddbe87c57acc930ce8-us2",
            "type" => "text"
        );

        $cs_options[] = array("name" => esc_html__("Mail Chimp List", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_mailchimp_list",
            "std" => "on",
            "type" => "mailchimp",
            "options" => $mail_chimp_list
        );

        $cs_options[] = array("name" => esc_html__("Flickr API Setting", "car-rental"),
            "id" => "flickr_api_setting",
            "std" => esc_html__("Flickr API Setting", "car-rental"),
            "type" => "section",
            "options" => ""
        );
        $cs_options[] = array("name" => esc_html__("Flickr key", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "flickr_key",
            "std" => "",
            "type" => "text");
        $cs_options[] = array("name" => esc_html__("Flickr secret", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "flickr_secret",
            "std" => "",
            "type" => "text");
        $cs_options[] = array("name" => esc_html__("Twitter", "car-rental"),
            "id" => "Twitter",
            "std" => esc_html__("Twitter", "car-rental"),
            "type" => "section",
            "options" => ""
        );
        $cs_options[] = array("name" => esc_html__("Show Twitter", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Turn Twitter option On/Off", "car-rental"),
            "id" => "cs_twitter_api_switch",
            "std" => "on",
            "type" => "checkbox");
        $cs_options[] = array("name" => __("Cache Time Limit", 'car-rental'),
            "desc" => "",
            "hint_text" => "Please enter the time limit in minutes for refresh cache",
            "id" => "cs_cache_limit_time",
            "std" => "",
            "type" => "text");

        $cs_options[] = array("name" => __("Number of tweet", 'car-rental'),
            "desc" => "",
            "hint_text" => "Please enter number of tweet that you get from twitter for chache file.",
            "id" => "cs_tweet_num_post",
            "std" => "",
            "type" => "text");

        $cs_options[] = array("name" => __("Date Time Formate", 'car-rental'),
            "desc" => "",
            "hint_text" => __("Select date time formate for every tweet.", 'car-rental'),
            "id" => "cs_twitter_datetime_formate",
            "std" => "",
            "type" => "select_values",
            "options" => array(
                'default' => __('Displays November 06 2012', 'car-rental'),
                'eng_suff' => __('Displays 6th November', 'car-rental'),
                'ddmm' => __('Displays 06 Nov', 'car-rental'),
                'ddmmyy' => __('Displays 06 Nov 2012', 'car-rental'),
                'full_date' => __('Displays Tues 06 Nov 2012', 'car-rental'),
                'time_since' => __('Displays in hours, minutes etc', 'car-rental'),
            )
        );

        $cs_options[] = array("name" => esc_html__("Consumer Key", "car-rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_consumer_key",
            "std" => "",
            "type" => "text");

        $cs_options[] = array("name" => esc_html__("Consumer Secret", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Insert consumer key. To get your account key, <a href='https://dev.twitter.com/' target='_blank'>Click Here </a>", "car-rental"),
            "id" => "cs_consumer_secret",
            "std" => "",
            "type" => "text");

        $cs_options[] = array("name" => esc_html__("Access Token", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Insert Twitter Access Token for permissions. When you create your Twitter App, you get this Token", "car-rental"),
            "id" => "cs_access_token",
            "std" => "",
            "type" => "text");

        $cs_options[] = array("name" => esc_html__("Access Token Secret", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Insert Twitter Access Token Secret here. When you create your Twitter App, you get this Token", "car-rental"),
            "id" => "cs_access_token_secret",
            "std" => "",
            "type" => "text");
        $cs_options[] = array("name" => esc_html__("Google API Setting", 'car-rental'),
            "std" => esc_html__("Google API Setting", 'car-rental'),
            "id" => "google_api_setting",
            "type" => "section"
        );
        $cs_options[] = array("name" => esc_html__("Google API Key", "car-rental"),
            "desc" => "",
            "hint_text" => '',
            "id" => "google_api_key",
            "std" => "",
            "type" => "text"
        );
        //end Twitter Api
        #import and export theme options tab
        $cs_options[] = array("name" => esc_html__("import & export", "car-rental"),
            "fontawesome" => 'icon-database',
            "id" => "tab-import-export-options",
            "std" => "",
            "type" => "main-heading",
            "options" => ""
        );
        $cs_options[] = array("name" => esc_html__("import & export", "car-rental"),
            "id" => "tab-import-export-options",
            "type" => "sub-heading"
        );

        $cs_options[] = array("name" => esc_html__("Theme Backup Options", 'car-rental'),
            "std" => esc_html__("Theme Backup Options", 'car-rental'),
            "id" => "theme-bakups-options",
            "type" => "section"
        );
        $cs_options[] = array("name" => esc_html__("Backup", "car-rental"),
            "desc" => "",
            "hint_text" => esc_html__("Backup", "car-rental"),
            "id" => "cs_backup_options",
            "std" => "",
            "type" => "generate_backup"
        );

        if (class_exists('cs_widget_data')) {

            $cs_options[] = array("name" => esc_html__("Widgets Backup Options", 'car-rental'),
                "std" => esc_html__("Widgets Backup Options", 'car-rental'),
                "id" => "widgets-bakups-options",
                "type" => "section"
            );

            $cs_options[] = array("name" => esc_html__("Widgets Backup", "car-rental"),
                "desc" => "",
                "hint_text" => '',
                "id" => "cs_widgets_backup",
                "std" => "",
                "type" => "widgets_backup"
            );
        }

        update_option('cs_theme_data', $cs_options);
    }

}

/**
 *
 *
 * Header Colors Setting
 */
function cs_header_setting() {
    global $cs_header_colors;
    $cs_header_colors = array();
    $cs_header_colors['header_colors'] = array(
        'header_1' => array(
            'color' => array(
                'cs_topstrip_bgcolor' => '#00799F',
                'cs_topstrip_text_color' => '#ffffff',
                'cs_topstrip_link_color' => '#ffffff',
                'cs_header_bgcolor' => '',
                'cs_nav_bgcolor' => '#00799F',
                'cs_menu_color' => '#ffffff',
                'cs_menu_active_color' => '#ffffff',
                'cs_submenu_bgcolor' => '#ffffff',
                'cs_submenu_color' => '#333333',
                'cs_submenu_hover_color' => '#00799F',
            ),
            'logo' => array(
                'cs_logo_with' => '300',
                'cs_logo_height' => '48',
                'cs_logo_margintb' => '0',
                'cs_logo_marginlr' => '0',
            )
        ),
    );
    return $cs_header_colors;
}
