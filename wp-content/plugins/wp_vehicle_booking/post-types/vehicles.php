<?php

/**
 * File Type: Vehicle Post Type
 */
if (!class_exists('post_type_vehicle')) {

    class post_type_vehicle {

        // The Constructor
        public function __construct() {
            add_action('init', array(&$this, 'cs_vehicle_init'));

            // Adding columns
            add_filter('manage_vehicles_posts_columns', array(&$this, 'cs_vehicle_columns_add'));
            add_action('manage_vehicles_posts_custom_column', array(&$this, 'cs_vehicle_columns'), 10, 2);

            // Removing add new Vehicle menu
            add_action('admin_menu', array(&$this, 'add_new_vehicle_menu'));
        }

        // Hook into WP's init action hook
        public function cs_vehicle_init() {
            // Initialize Post Type
            $this->cs_vehicle_register();
        }

        public function cs_vehicle_register() {
            $labels = array(
                'name' => __('Vehicles', 'rental'),
                'menu_name' => __('Vehicles', 'rental'),
                'add_new_item' => __('Add New Vehicle', 'rental'),
                'edit_item' => __('Edit Vehicle', 'rental'),
                'new_item' => __('New Vehicle Item', 'rental'),
                'add_new' => __('Add New Vehicle', 'rental'),
                'view_item' => __('View Vehicle Item', 'rental'),
                'search_items' => __('Search', 'rental'),
                'not_found' => __('Nothing found', 'rental'),
                'not_found_in_trash' => __('Nothing found in Trash', 'rental'),
                'parent_item_colon' => ''
            );
            $args = array(
                'labels' => $labels,
                'public' => false,
                'publicly_queryable' => false,
                'show_ui' => true,
                'query_var' => false,
                'menu_icon' => 'dashicons-admin-post',
                'rewrite' => true,
                'capability_type' => 'post',
                'hierarchical' => false,
                'menu_position' => null,
                'supports' => array('title', 'editor')
            );
            register_post_type('vehicles', $args);
        }

        // Adding columns Title
        public function cs_vehicle_columns_add($columns) {
            unset($columns['date']);
            $columns['vehicle_type'] = __('Vehicle Type', 'rental');
            $columns['passengers'] = __('No of Passengers', 'rental');
            $columns['price'] = __('Price', 'rental');
            $columns['gallery'] = __('Gallery', 'rental');

            return $columns;
        }

        // Adding columns
        public function cs_vehicle_columns($name) {
            global $post, $cs_form_fields;
            $cs_type_options = get_option("cs_type_options");
            $cs_vehicle_type = get_post_meta($post->ID, 'cs_vehicle_type', true);
            $price = get_post_meta($post->ID, 'cs_vehicle_price', true);
            $passengers = get_post_meta($post->ID, 'cs_vehicle_max_passengers', true);

            if (isset($cs_type_options[$cs_vehicle_type])) {
                $cs_type_name = $cs_type_options[$cs_vehicle_type]['cs_type_name'];
            } else {
                $cs_type_name = '';
            }

            if ($price == '') {
                $price = 0;
            }

            if (metadata_exists('post', $post->ID, 'cs_vehicle_image_gallery')) {
                $gallery = get_post_meta($post->ID, 'cs_vehicle_image_gallery', true);
            } else {
                // Backwards compat
                $attachment_ids = get_posts('post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&&meta_value=0');
                $attachment_ids = array_diff($attachment_ids, array(get_post_thumbnail_id()));
                $gallery = implode(',', $attachment_ids);
            }

            $attachments = array_filter(explode(',', $gallery));

            // return payment gateway name
            switch ($name) {
                case 'vehicle_type':
                    echo esc_attr($cs_type_name);
                    break;

                case 'passengers':
                    echo esc_attr($passengers);
                    break;

                case 'price':
                    echo number_format($price, 2);
                    break;
                case 'gallery':
                    if ($attachments) {
                        $counter = 0;
                        foreach ($attachments as $attachment_id) {
                            $counter++;
                            if ($counter < 6) {

                                $cs_img_s = wp_get_attachment_image_src($attachment_id, array(150, 150));

                                if (isset($cs_img_s['1']) && $cs_img_s['1'] == 150) {
                                    $attachment_data = $cs_form_fields->cs_get_icon_for_attachment($attachment_id, 'custom');
                                } else {
                                    $attachment_data = '<img src="' . wp_car_rental::plugin_url() . '/assets/images/no-img-vechile.jpg" width="50" alt="">';
                                }

                                echo '<span class="list-thumb">' . $attachment_data . '</span>';
                            }
                        }
                    }
                    break;
            }
        }

        public function add_new_vehicle_menu() {
            global $submenu;
            unset($submenu['edit.php?post_type=vehicles'][10]);
        }

        // End of class	
    }

    // Initialize Object
    $vehicle_object = new post_type_vehicle();
}