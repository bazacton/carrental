<?php

/*
  Plugin Name: WP CAR RENTAL
  Plugin URI: http://themeforest.net/user/Chimpstudio/
  Description: Autos Booking System
  Version: 1.6
  Author: ChimpStudio
  Author URI: http://themeforest.net/user/Chimpstudio/
  License: GPL2
  Copyright 2015  chimpgroup  (email : info@chimpstudio.co.uk)
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, United Kingdom
 */

require_once('settings/global-variables.php');


if (!class_exists('wp_car_rental')) {

    class wp_car_rental {

        public $plugin_url;

        //=====================================================================
        // Construct
        //=====================================================================
        public function __construct() {

            global $post, $wp_query, $cs_plugin_options, $booking_menu_name;
            
            add_action('init', array($this, 'load_plugin_textdomain'), 0);
            
            //$cs_plugin_options = get_option('cs_plugin_options');
            $this->plugin_url = plugin_dir_url(__FILE__);
            $this->plugin_dir = plugin_dir_path(__FILE__);
            
            require_once('include/wpml_functions.php');
            require_once('templates/class_reservation_templates.php');
            require_once('templates/templates_functions.php');
            require_once('payments/class-payments.php');
            require_once('payments/class-payments.php');
            require_once('payments/config.php');
            require_once('post-types/locations.php');
            require_once('include/location_meta.php');
            require_once('include/customers.php');
            ;
            require_once('include/types_meta.php');
            require_once('include/transactions_meta.php');
            require_once('post-types/vehicles.php');
            require_once('include/vahicles_meta.php');

            //Locations
            require_once('locations/functions.php');
            require_once('locations/locations_element.php');
            require_once('locations/locations_template.php');

            require_once('vehicles/functions.php');
            require_once('vehicles/vehicles_element.php');
            require_once('vehicles/vehicles_template.php');

            require_once('widgets/vehicle_search.php');
            require_once('include/functions.php');
            require_once('include/form_fields.php');
            require_once('helpers/notifications/notification-helper.php');
            require_once('helpers/emails/email-helper.php');
            require_once('include/block_meta.php');
            require_once('include/pricing_meta.php');
            require_once('settings/plugin_settings.php');
            require_once('settings/includes/plugin_options.php');
            require_once('settings/includes/plugin_options_functions.php');
            require_once('settings/includes/plugin_options_fields.php');
            require_once('settings/includes/plugin_options_array.php');

            require_once('shortcodes/admin/vehicle_search.php');
            require_once('shortcodes/vehicle_search.php');
            require_once('post-types/booking.php');
            require_once('include/booking_meta.php');

            // Theme Importer
            require_once('include/cs-importer/theme_importer.php');
            require_once('include/cs-importer/class-widget-data.php');

            // Mailchimp Functions
            require_once('include/cs-mailchimp/mailchimp.class.php');
            require_once('include/cs-mailchimp/mailchimp_functions.php');

            // Mailchimp Widget
            require_once('widgets/mailchimp.php');

            add_filter('template_include', array(&$this, 'cs_single_template'));
            add_action('wp_enqueue_scripts', array(&$this, 'cs_defaultfiles_plugin_enqueue'));
            add_action('admin_enqueue_scripts', array(&$this, 'cs_defaultfiles_plugin_enqueue'));
            add_action('init', array($this, 'cs_add_custom_role'));
            add_action('admin_menu', array($this, 'edit_admin_menus'));
            add_action('admin_menu', array($this, 'rename_admin_menus'));
        }

        /**
         *
         * @Menu Rename
         */
        public function edit_admin_menus() {
            global $menu, $submenu;

            foreach ($menu as $key => $menu_item) {
                if ($menu_item[2] == 'edit.php?post_type=vehicles') {
                    $menu[$key][0] = __('Car Rental', 'rental');
                }
            }
        }

        /**
         *
         * @Sub Menu Rename
         */
        public function rename_admin_menus() {
            global $menu, $submenu;
            $arr = array();
            $arr[] = $submenu['edit.php?post_type=vehicles'][5];
            $arr[] = $submenu['edit.php?post_type=vehicles'][14];
            $arr[] = $submenu['edit.php?post_type=vehicles'][16];
            $arr[] = $submenu['edit.php?post_type=vehicles'][11];
            $arr[] = $submenu['edit.php?post_type=vehicles'][12];
            $arr[] = $submenu['edit.php?post_type=vehicles'][13];
            $arr[] = $submenu['edit.php?post_type=vehicles'][15];
            $arr[] = $submenu['edit.php?post_type=vehicles'][17];
            $arr[] = $submenu['edit.php?post_type=vehicles'][18];
            $submenu['edit.php?post_type=vehicles'] = $arr;

            return $submenu;
        }

        /**
         *
         * @Text Domain
         */
        public function load_plugin_textdomain() {
            
            global $cs_plugin_options;
            
            if (function_exists('icl_object_id')) {

                global $sitepress, $wp_filesystem;

                require_once ABSPATH . '/wp-admin/includes/file.php';

                $backup_url = '';

                if (false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) )) {

                    return true;
                }

                if (!WP_Filesystem($creds)) {
                    request_filesystem_credentials($backup_url, '', true, false, array());
                    return true;
                }

                $cs_languages_dir = wp_car_rental::plugin_dir() . '/languages/';

                $cs_all_langs = $wp_filesystem->dirlist($cs_languages_dir);

                $cs_mo_files = array();
                if (is_array($cs_all_langs) && sizeof($cs_all_langs) > 0) {

                    foreach ($cs_all_langs as $file_key => $file_val) {

                        if (isset($file_val['name'])) {

                            $cs_file_name = $file_val['name'];

                            $cs_ext = pathinfo($cs_file_name, PATHINFO_EXTENSION);

                            if ($cs_ext == 'mo') {
                                $cs_mo_files[] = $cs_file_name;
                            }
                        }
                    }
                }

                $cs_active_langs = $sitepress->get_current_language();
                
                foreach ($cs_mo_files as $mo_file) {
                    if (strpos($mo_file, $cs_active_langs.'.mo') !== false) {
                        $cs_lang_mo_file = $mo_file;
                    }
                }
            }
            
            $languageFile = isset($cs_plugin_options['cs_language_file']) ? $cs_plugin_options['cs_language_file'] : '';
            
            $locale = apply_filters('plugin_locale', get_locale(), 'rental');
            $dir = trailingslashit(WP_LANG_DIR);
            
            if (isset($cs_lang_mo_file) && $cs_lang_mo_file != '') {
                load_textdomain('rental', plugin_dir_path(__FILE__) . "languages/" . $cs_lang_mo_file);
            } else if (isset($languageFile) && $languageFile != '') {
                load_textdomain('rental', plugin_dir_path(__FILE__) . "languages/" . $cs_plugin_options['cs_language_file']);
            }
        }

        /**
         *
         * @Add Custom Roles
         */
        public function cs_add_custom_role() {
            add_role('guest', 'Guest', array(
                'read' => true, // True allows that capability
                'edit_posts' => true,
                'delete_posts' => false, // Use false to explicitly deny
            ));
        }

        /**
         *
         * @PLugin URl
         */
        public static function plugin_url() {
            return plugin_dir_url(__FILE__);
        }

        /**
         *
         * @Plugin Images Path
         */
        public static function plugin_img_url() {
            return plugin_dir_url(__FILE__);
        }

        /**
         *
         * @Plugin URL
         */
        public static function plugin_dir() {
            return plugin_dir_path(__FILE__);
        }

        /**
         *
         * @Activate the plugin
         */
        public static function activate() {
            global $cs_settings_init;
            add_option('cs_booking_plugin_activation', 'installed');
            add_option('cs_booking', '1');
            add_action('init', 'cs_plugin_activation_data');
        }

        /**
         *
         * @Deactivate the plugin
         */
        static function deactivate() {
            delete_option('cs_plugin_activation_data');
            delete_option('cs_booking', false);
        }

        /**
         *
         * @ Include Template
         */
        public function cs_single_template($single_template) {
            global $post;
            if (get_post_type() == 'locations') {
                $single_template = plugin_dir_path(__FILE__) . '/locations/single-lcoations.php';
            }
            return $single_template;
        }

        /**
         *
         * @ Include Default Scripts and styles
         */
        public function cs_defaultfiles_plugin_enqueue() {
            wp_enqueue_media();
            wp_enqueue_script('my-upload', '', array('jquery', 'media-upload', 'thickbox', 'jquery-ui-droppable', 'jquery-ui-datepicker', 'jquery-ui-slider', 'wp-color-picker'));
            wp_register_script( 'booking_functions_js', $this->plugin_url . 'assets/scripts/booking_functions.js');

            // Localize the script with new data
            $translation_array = array(
              'vat' => __('Vat', 'rental')
            );
            wp_localize_script( 'booking_functions_js', 'cs_v_translations', $translation_array );
            wp_enqueue_script( 'booking_functions_js');
            wp_enqueue_script('booking_exra_js', plugins_url('/assets/scripts/extra_functions.js', __FILE__), '', '', true);
            wp_enqueue_style('cs_fontawesome_styles', plugins_url('/assets/css/font-awesome.min.css', __FILE__));

            if (is_admin()) {
                wp_enqueue_style('booking_admin_styles', plugins_url('/assets/css/admin_style.css', __FILE__));
                wp_enqueue_style('cs_datepicker_css', plugins_url('/assets/css/jquery_datetimepicker.css', __FILE__));
                wp_enqueue_script('cs_datepicker_js', plugins_url('/assets/scripts/jquery_datetimepicker.js', __FILE__), '', '', true);
            }
            if (!is_admin()) {
                //wp_enqueue_style('bootstrap_css', plugins_url( '/assets/css/bootstrap.min.css' , __FILE__ ));
            }

            if (is_admin()) {
                wp_enqueue_script('fonticonpicker_js', plugins_url('/assets/icomoon/js/jquery.fonticonpicker.min.js', __FILE__), '', '', true);
                wp_enqueue_style('fonticonpicker_css', plugins_url('/assets/icomoon/css/jquery.fonticonpicker.min.css', __FILE__));
                wp_enqueue_style('iconmoon_css', plugins_url('/assets/icomoon/css/iconmoon.css', __FILE__));
                wp_enqueue_style('fonticonpicker_bootstrap_css', plugins_url('/assets/icomoon/theme/bootstrap-theme/jquery.fonticonpicker.bootstrap.css', __FILE__));

                // Full Calender
                wp_enqueue_style('calander_css', plugins_url('/assets/full-calender/css/fullcalendar.css', __FILE__));
                wp_enqueue_script('calander_moment_js', plugins_url('/assets/full-calender/js/moment.min.js', __FILE__), '', '', true);
                wp_enqueue_script('calander_js', plugins_url('/assets/full-calender/js/fullcalendar.js', __FILE__), '', '', true);
            }
        }

        /**
         *
         * @Rating Styles and Scripts
         */
        public static function cs_enqueue_rating_style_script() {
            wp_enqueue_script('jquery.rating_js', plugins_url('/assets/scripts/jRating.jquery.js', __FILE__), '', '', true);
            wp_enqueue_style('jquery.rating_css', plugins_url('/assets/css/jRating.jquery.css', __FILE__));
        }

        /**
         *
         * @Scroll Scripts
         */
        public static function cs_enqueue_scroll_script() {
            wp_enqueue_script('jquery.scroll_js', plugins_url('/assets/scripts/jquery.mCustomScrollbar.concat.min.js', __FILE__), '', '', true);
            wp_enqueue_style('jquery.scroll_css', plugins_url('/assets/css/jquery.mCustomScrollbar.css', __FILE__));
        }

        /**
         *
         * @Date Picker
         */
        public static function cs_enqueue_datepicker_script() {
            wp_enqueue_script('cs_bootstrap_datepicker_js', plugins_url('/assets/scripts/bootstrap-datepicker.js', __FILE__), '', '', true);
            wp_enqueue_script('bootstrap_timepicker_min_js', plugins_url('/assets/scripts/bootstrap-timepicker.min.js', __FILE__), '', '', true);
            wp_enqueue_style('bootstrap_timepicker_min_css', plugins_url('/assets/css/bootstrap-timepicker.min.css', __FILE__));
        }

        /**
         *
         * @Date Range Style Scripts
         */
        public static function cs_date_range_style_script() {
            wp_enqueue_script('moment.min_js', plugins_url('/assets/scripts/moment.min.js', __FILE__), '', '', true);
            wp_enqueue_script('jquery.daterangepicker', plugins_url('/assets/scripts/jquery.daterangepicker.js', __FILE__), '', '', true);
            wp_enqueue_style('cs_daterangepicker_css', plugins_url('/assets/css/daterangepicker.css', __FILE__));
        }

        /**
         *
         * @Data Table Style Scripts
         */
        public static function cs_data_table_style_script() {
            wp_enqueue_script('jquery.datatable', plugins_url('/assets/scripts/jquery.data_tables.js', __FILE__), '', '', true);
            wp_enqueue_style('datatable_css', plugins_url('/assets/css/jquery.data_tables.css', __FILE__));
        }

        /**
         *
         * @Data Table Style Scripts
         */
        public static function cs_owl_carousel_script() {
            wp_enqueue_script('pg_jquery_carousel', plugins_url('/assets/scripts/owl.carousel.min.js', __FILE__), '', '', true);
            wp_enqueue_style('pg_carousel', plugins_url('/assets/css/owl.carousel.css', __FILE__));
        }

        /**
         *
         * @Pretty Photo
         */
        public static function cs_prettyphoto_script() {
            wp_enqueue_script('pg_jquery_prettyphoto', plugins_url('/assets/scripts/jquery.prettyphoto.js', __FILE__), '', '', true);
            wp_enqueue_style('pg_prettyphoto', plugins_url('/assets/css/prettyphoto.css', __FILE__));
        }

        /**
         *
         * @Location Gmap
         */
        public static function cs_location_gmap_script() {
            wp_enqueue_script('pg_jquery_carousel', plugins_url('/assets/scripts/owl.carousel.min.js', __FILE__), '', '', true);
            wp_enqueue_script('jquery.latlon_picker', plugins_url('/assets/scripts/jquery_latlon_picker.js', __FILE__), '', '', true);
        }

        /**
         *
         * @Google Places
         */
        public static function cs_google_place_scripts() {
           // wp_enqueue_script('jquery.googleapis_js', 'http://maps.google.com/maps/api/js?sensor=false&libraries=places', '', '', true);
        }

        /**
         *
         * @Range Slider
         */
        public static function cs_ranges_slider_scripts() {
            wp_enqueue_script('jquery-ui-slider');
            wp_enqueue_style('rangeslider_css', plugins_url('/assets/css/ranges_slider.css', __FILE__));
        }

        /**
         *
         * @Time Picker UI
         */
        public static function cs_timepicker_scripts() {
            wp_enqueue_script('cs_datetimepicker_js', plugins_url('/assets/scripts/jquery_datetimepicker.js', __FILE__), '', '', true);
            wp_enqueue_style('cs_datetimepicker_css', plugins_url('/assets/css/jquery_datetimepicker.css', __FILE__));
        }

        /**
         *
         * @Slick Slider
         */
        public static function cs_slick_slider_scripts() {
            wp_enqueue_script('jquery.slickslider', plugins_url('/assets/scripts/slick.js', __FILE__), '', '', false);
            wp_enqueue_script('jquery.slickslider_min', plugins_url('/assets/scripts/slick.min.js', __FILE__), '', '', false);
        }

        /**
         *
         * @Auto Complete
         */
        public static function cs_autocomplete_scripts() {

            wp_enqueue_script('geocomplete_js', 'http://ubilabs.github.io/geocomplete/jquery.geocomplete.js', '', '', true);
        }

        /**
         *
         * @Tags Input
         */
        public static function cs_tagsinput_scripts() {
            wp_enqueue_script('tagsinput_js', plugins_url('/assets/scripts/bootstrap-tagsinput.js', __FILE__), '', '', true);
            wp_enqueue_style('tagsinput_css', plugins_url('/assets/css/bootstrap-tagsinput.css', __FILE__));
        }

    }

}

/**
 *
 * @Create Object of class To Activate Plugin
 */
if (class_exists('wp_car_rental')) {
    $cs_booking = new wp_car_rental();
    register_activation_hook(__FILE__, array('wp_car_rental', 'activate'));
    register_deactivation_hook(__FILE__, array('wp_car_rental', 'deactivate'));
}