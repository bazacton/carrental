<?php

/**
 * File Type: Location Post Type
 */
if (!class_exists('post_type_locations')) {

    class post_type_locations {

        // The Constructor
        public function __construct() {
            global $pagenow;
            add_action('init', array(&$this, 'cs_locations_init'));

            // Adding columns
            add_filter('manage_locations_posts_columns', array(&$this, 'cs_locations_columns_add'));
            add_action('manage_locations_posts_custom_column', array(&$this, 'cs_locations_columns'), 10, 2);
        }

        // Hook into WP's init action hook
        public function cs_locations_init() {
            // Initialize Post Type
            $this->cs_locations_register();
        }

        public function cs_locations_register() {
            $labels = array(
                'name' => __('Locations', 'rental'),
                'all_items' => __('Locations', 'rental'),
                'singular_name' => __('Locations', 'rental'),
                'add_new' => __('Add Location', 'rental'),
                'add_new_item' => __('Add New Location', 'rental'),
                'edit' => __('Edit', 'rental'),
                'edit_item' => __('Edit Location', 'rental'),
                'new_item' => __('New Location', 'rental'),
                'view' => __('View Location', 'rental'),
                'view_item' => __('View Location', 'rental'),
                'search_items' => __('Search Location', 'rental'),
                'not_found' => __('No Location found', 'rental'),
                'not_found_in_trash' => __('No Location found in trash', 'rental'),
                'parent' => __('Parent Location', 'rental'),
            );
            $args = array(
                'labels' => $labels,
                'description' => __('This is where you can add new Location', 'rental'),
                'public' => true,
                'supports' => array('title', 'thumbnail', 'editor'),
                'show_ui' => true,
                'capability_type' => 'post',
                'map_meta_cap' => true,
                'publicly_queryable' => true,
                'exclude_from_search' => false,
                'hierarchical' => false,
                'show_in_menu' => 'edit.php?post_type=vehicles',
                'rewrite' => array('slug' => 'locations', 'with_front' => true),
                'query_var' => false,
                'has_archive' => 'false',
            );
            register_post_type('locations', $args);
        }

        // Adding columns Title
        public function cs_locations_columns_add($columns) {
            unset($columns['date']);
            $columns['phone'] = __('Phone', 'rental');
            $columns['email'] = __('Email', 'rental');
            $columns['address'] = __('Address', 'rental');
            return $columns;
        }

        // Adding columns
        public function cs_locations_columns($name) {
            global $post;

            $phone = get_post_meta($post->ID, 'cs_phone_no', true);
            $address = get_post_meta($post->ID, 'cs_location_address', true);
            $email = get_post_meta($post->ID, 'cs_email', true);

            switch ($name) {
                case 'phone':
                    echo esc_attr($phone);
                    break;

                case 'email':
                    echo esc_attr($email);
                    break;

                case 'address':
                    echo esc_attr($address);
                    break;
            }
        }

    }

    // Initialize Object
    new post_type_locations();
}