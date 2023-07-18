<?php/** * File Type: Booking Templates */if (!class_exists('cs_booking_templates')) {    class cs_booking_templates {        public function __construct() {            $this->templates = array();            // Add a filter to the page attributes metabox to inject our template into the page template cache.            add_filter('page_attributes_dropdown_pages_args', array($this, 'booking_register_templates'));            add_filter('theme_page_templates', array($this, 'theme_page_templates_callback'));            // Add a filter to the save post in order to inject out template into the page cache            add_filter('wp_insert_post_data', array($this, 'booking_register_templates'));            // Add a filter to the template include in order to determine if the page has our template assigned and return it's path            add_filter('template_include', array($this, 'booking_page_templates'));            // Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.            register_deactivation_hook(__FILE__, array($this, 'deactivate'));            // Add your templates to this array.            $this->templates = array(                'page_reservation.php' => __('Reservation', 'rental'),            );            // adding support for theme templates to be merged and shown in dropdown            $templates = wp_get_theme()->get_page_templates();            $templates = array_merge($templates, $this->templates);        }// end constructor        public function theme_page_templates_callback($post_templates) {            $post_templates = array_merge($this->templates, $post_templates);            return $post_templates;        }        /**         * Adds our template to the pages cache in order to trick WordPress         * into thinking the template file exists where it doens't really exist.         */        public function booking_register_templates($atts) {            $cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());            $templates = wp_cache_get($cache_key, 'themes');            if (empty($templates)) {                $templates = array();            } // end if            wp_cache_delete($cache_key, 'themes');            $templates = array_merge($templates, $this->templates);            wp_cache_add($cache_key, $templates, 'themes', 1800);            return $atts;        }// end booking_register_templates        /**         * Checks if the template is assigned to the page         */        public function booking_page_templates($template) {            global $post;            if (!isset($post))                return $template;            if (!isset($this->templates[get_post_meta($post->ID, '_wp_page_template', true)])) {                return $template;            }            $file = plugin_dir_path(__FILE__) . get_post_meta($post->ID, '_wp_page_template', true);            if (file_exists($file)) {                return $file;            }            return $template;        }// end booking_page_templates        /* --------------------------------------------*         * deactivate the plugin         * --------------------------------------------- */        static function deactivate($network_wide) {            foreach ($this as $value) {                cs_delete_template($value);            }        }// end deactivate        /* --------------------------------------------*         * Delete Templates from Theme         * --------------------------------------------- */        public function booking_delete_template($filename) {            $theme_path = get_template_directory();            $template_path = $theme_path . '/' . $filename;            if (file_exists($template_path)) {                unlink($template_path);            }            // we should probably delete the old cache            wp_cache_delete($cache_key, 'themes');        }    }    // end class    // Initialize Object    $cs_booking_templates = new cs_booking_templates();}