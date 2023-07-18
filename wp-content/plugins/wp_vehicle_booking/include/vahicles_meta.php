<?php
/**
 * @Add Meta Box For Vehicles Post
 * @return
 *
 */
add_action('add_meta_boxes', 'cs_meta_vehicles_add');

function cs_meta_vehicles_add() {
    add_meta_box('cs_meta_vehicles', __('Vehicles Options', 'rental'), 'cs_meta_vehicles', 'vehicles', 'normal', 'high');
    add_meta_box('vehicle-gallery-images', __('Vehicle Gallery', 'rental'), 'cs_vehicles_gallery', 'vehicles', 'side');
}

function cs_vehicles_gallery($post) {
    global $post, $cs_form_fields;
    $cs_plugin_options = get_option('cs_plugin_options', true);
    $cs_form_fields->cs_gallery_render(
            array('name' => __('Add Vehicle Gallery', 'rental'),
                'id' => 'vehicle_image_gallery',
                'classes' => '',
                'std' => '',
                'description' => '',
                'hint' => ''
            )
    );
}

function cs_meta_vehicles($post) {
    global $post;
    ?>
    <div class="page-wrap page-opts left">
        <div class="option-sec" style="margin-bottom:0;">
            <div class="opt-conts">
                <div class="elementhidden">
                    <?php
                    if (function_exists('cs_vehicle_options')) {
                        cs_vehicle_options();
                    }
                    ?>
                </div>
                <script>
                    jQuery(document).ready(function ($) {
                        cs_check_availabilty();
                    });
                </script>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <?php
}

/**
 * @Vehicles options
 * @return html
 *
 */
if (!function_exists('cs_vehicle_options')) {

    function cs_vehicle_options() {

        global $post, $cs_form_fields, $cs_plugin_options;

        $cs_vehicle_types = array();

        $cs_args = array('posts_per_page' => '-1', 'post_type' => 'locations', 'orderby' => 'ID', 'post_status' => 'publish');
        $cust_query = get_posts($cs_args);

        $cs_locations[''] = __('Select Location', 'rental');

        if (isset($cust_query) && is_array($cust_query) && !empty($cust_query)) {
            foreach ($cust_query as $key => $location) {
                if(isset($location->ID)){
                $cs_locations[$location->ID] = get_the_title($location->ID);
                }
            }
        }

        // Vehicle Types
        $cs_type_data = get_option("cs_type_options");
        $cs_types[''] = __('Select Vehicle Type', 'rental');

        if (isset($cs_type_data) && is_array($cs_type_data) && !empty($cs_type_data)) {
            foreach ($cs_type_data as $key => $type) {
                if(isset($type['cs_type_name'])){
                $cs_types[$key] = $type['cs_type_name'];
                }
            }
        }
        // Vehicle

        $limit = isset($cs_plugin_options['cs_total_vehicles']) && $cs_plugin_options['cs_total_vehicles'] != '' && absint($cs_plugin_options['cs_total_vehicles']) ? $cs_plugin_options['cs_total_vehicles'] : 100;
        $cs_vehicle_limit[''] = __('select No of Vehicles', 'rental');
        for ($i = 1; $i <= $limit; $i++) {
            $cs_vehicle_limit[$i] = $i;
        }

        $cs_args = array('posts_per_page' => '-1', 'post_type' => 'vehicles', 'orderby' => 'ID', 'post_status' => 'publish');
        $cust_query = get_posts($cs_args);

        $cs_vehicles = array();
        foreach ($cust_query as $type) {
            if(isset($type->ID)){
            $cs_vehicles[$type->ID] = get_the_title($type->ID);
            }
        }

        $cs_form_fields->cs_form_select_render(
                array('name' => __('Vehicle Type', 'rental'),
                    'id' => 'vehicle_type',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'options' => $cs_types,
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_form_multiselect_render(
                array('name' => __('Pickup Location', 'rental'),
                    'id' => 'pickup_locations',
                    'classes' => '',
                    'std' => array(),
                    'description' => '',
                    'options' => $cs_locations,
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_form_multiselect_render(
                array('name' => __('Drop up Locations', 'rental'),
                    'id' => 'drop_location',
                    'classes' => '',
                    'std' => array(),
                    'description' => '',
                    'options' => $cs_locations,
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Price', 'rental'),
                    'id' => 'vehicle_price',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Vehicles Prefix', 'rental'),
                    'id' => 'vehicles_prefix',
                    'classes' => '',
                    'std' => 'HT-',
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_form_select_render(
                array('name' => __('Number of Vehicles', 'rental'),
                    'id' => 'vehicle_num',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'options' => $cs_vehicle_limit,
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Number of Passengers', 'rental'),
                    'id' => 'vehicle_max_passengers',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_file_attachments(
                array('name' => __('File Attachments', 'rental'),
                    'id' => 'vehicle_file_attach',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_booking_feature_list(
                array('name' => __('Features List', 'rental'),
                    'id' => 'vehicle_features',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_booking_property_list(
                array('name' => __('Extra Features List', 'rental'),
                    'id' => 'vehicle_properties',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => ''
                )
        );
    }

}
?>