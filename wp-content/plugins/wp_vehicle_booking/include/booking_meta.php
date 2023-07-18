<?php
/**
 * @Add Meta Box For Booking Post
 * @return
 *
 */
add_action('add_meta_boxes', 'cs_meta_booking_add');

function cs_meta_booking_add() {
    add_meta_box('cs_meta_booking', __('Booking Options', 'rental'), 'cs_meta_booking', 'booking', 'normal', 'high');
}

function cs_meta_booking($post) {
    global $post;
    ?>
    <div class="page-wrap page-opts left">
        <div class="option-sec" style="margin-bottom:0;">
            <div class="opt-conts">
                <div class="elementhidden">
                    <div class="tab-content">
                        <div id="tab-booking-settings" class="tab-pane fade active in">
                            <?php
                            wp_car_rental::cs_date_range_style_script();
                            wp_car_rental::cs_timepicker_scripts();

                            if (function_exists('cs_booking_options')) {
                                cs_booking_options();
                            }

                            if (isset($_GET['post']) && $_GET['post'] != '') {
                                // Do Nothing
                            } else {
                                ?>
                                <script type="text/javascript">

                                    jQuery(function () {
                                        jQuery("#wrapper_datepicker").dateRangePicker({
                                            separator: " to ",
                                            format: 'YYYY-MM-DD',
                                            getValue: function ()
                                            {
                                                if (jQuery("cs_check_in_date").val() && jQuery("#cs_check_out_date").val())
                                                    return jQuery("#cs_check_in_date").val() + " to " + jQuery("#cs_check_out_date").val();
                                                else
                                                    return "";
                                            },
                                            setValue: function (s, s1, s2)
                                            {

                                                jQuery("#cs_check_in_date").val(s1);
                                                jQuery("#cs_check_out_date").val(s2);

                                                var start = new Date(s1)
                                                var end = new Date(s2)

                                                if (!start || !end)
                                                    return;
                                                var days = (end - start) / 1000 / 60 / 60 / 24;
                                                jQuery("#cs_booking_num_days").val(days);

                                            },
                                        });

                                        //jQuery('#cs_pickup_time').timepicker();
                                        //jQuery('#cs_dropup_time').timepicker();

                                        jQuery('#cs_pickup_time').datetimepicker({
                                            datepicker: false,
                                            format: 'H:i',
                                            formatTime: 'H:i',
                                            step: 15,
                                            onShow: function (at) {
                                                this.setOptions({
                                                    maxTime: jQuery('#cs_dropup_time').val() ? jQuery('#cs_dropup_time').val() : false
                                                })
                                            }
                                        });

                                        jQuery('#cs_dropup_time').datetimepicker({
                                            datepicker: false,
                                            format: 'H:i:s',
                                            formatTime: 'H:i:s',
                                            step: 15,
                                            onShow: function (at) {
                                                this.setOptions({
                                                    minTime: jQuery('#cs_pickup_time').val() ? jQuery('#cs_pickup_time').val() : false
                                                })
                                            }
                                        });

                                    });



                                </script>
                                <?php
                            }

                            if (function_exists('cs_booking_payments')) {
                                cs_booking_payments();
                            }

                            if (function_exists('cs_guest_details')) {
                                cs_guest_details();
                            }

                            if (function_exists('cs_general_settings')) {
                                cs_general_settings();
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <?php
}

/**
 * @Booking options
 * @return html
 *
 */
if (!function_exists('cs_booking_options')) {

    function cs_booking_options() {

        global $post, $cs_form_fields;

        unset($_SESSION['admin_reserved_vehicles']);
        unset($_SESSION['admin_reservation']);

        $active = '';
        if (isset($_GET['post']) && $_GET['post'] != '') {
            $active = 'in-active';
        }

        $cs_form_fields->cs_form_edit_id(
                array(
                    'name' => __('Booking Id', 'rental'),
                    'id' => 'booking_id',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_type_options = get_option('cs_type_options');


        $vehicle_type_list = array();
        $type_list = array();
        if (isset($cs_type_options) && is_array($cs_type_options)) {
            foreach ($cs_type_options as $key => $type) {
                $type_list[$key] = $type['cs_type_name'];
            }
        }

        //Location
        $cs_args = array('posts_per_page' => '-1', 'post_type' => 'locations', 'orderby' => 'ID', 'post_status' => 'publish');
        $cust_query = get_posts($cs_args);

        $cs_locations = array();
        $cs_locations[''] = __('Select Location', 'rental');

        if (isset($cust_query) && is_array($cust_query) && !empty($cust_query)) {
            foreach ($cust_query as $key => $location) {
                $cs_locations[$location->ID] = get_the_title($location->ID);
            }
        }

        if (isset($_GET['post']) && $_GET['post'] != '') {
            $status = get_post_meta($post->ID, 'cs_station', true);
            if ($status == 'on') {
                $status = 'hide';
            } else {
                $status = 'show';
            }

            // Days
            $num_days = get_post_meta($post->ID, 'cs_booking_num_days', true);
        } else {
            $status = 'show';
            $num_days = 0;
        }
        
        $cs_form_fields->cs_form_select_render(
                array('name' => __('Select Vehicle Type', 'rental'),
                    'id' => 'type_id',
                    'classes' => 'cs_type_id',
                    'std' => '',
                    'description' => '',
                    'options' => $type_list,
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_form_select_render(
                array('name' => __('Pickup Location', 'rental'),
                    'id' => 'pickup_location',
                    'classes' => 'pickup_location',
                    'std' => '',
                    'description' => '',
                    'options' => $cs_locations,
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_form_select_render(
                array('name' => __('Drop up Location', 'rental'),
                    'id' => 'dropup_location',
                    'classes' => 'dropup_location',
                    'std' => '',
                    'description' => '',
                    'options' => $cs_locations,
                    'hint' => '',
                    'status' => $status
                )
        );

        $cs_form_fields->cs_form_checkbox_render(
                array('name' => __('Return car to the same station', 'rental'),
                    'id' => 'station',
                    'classes' => 'cs_station',
                    'std' => '',
                    'description' => '',
                    'return' => false,
                    'hint' => '',
                )
        );

        $cs_form_fields->cs_wrapper_start_render(
                array('name' => __('Wrapper', 'rental'),
                    'id' => 'datepicker',
                    'status' => 'show',
                )
        );

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Check-in Date', 'rental'),
                    'id' => 'check_in_date',
                    'classes' => '',
                    'active' => $active,
                    'std' => '',
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Check-out Date', 'rental'),
                    'id' => 'check_out_date',
                    'classes' => '',
                    'active' => $active,
                    'std' => '',
                    'description' => '',
                    'hint' => ''
                )
        );



        $cs_form_fields->cs_wrapper_end_render(
                array('name' => __('Wrapper', 'rental'),
                    'id' => 'datepicker_wrapper',
                    'status' => '',
                )
        );

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Pickup Time', 'rental'),
                    'id' => 'pickup_time',
                    'classes' => '',
                    'active' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Drop up Time', 'rental'),
                    'id' => 'dropup_time',
                    'classes' => '',
                    'active' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_form_hidden_render(array('id' => 'booking_num_days', 'std' => $num_days, 'type' => '', 'return' => 'echo'));

        // Vehicles Data
        $cs_form_fields->cs_wrapper_start_render(
                array('name' => __('Wrapper', 'rental'),
                    'id' => 'vehicles_list',
                    'status' => 'show',
                )
        );


        $cs_form_fields->cs_wrapper_end_render(
                array('name' => __('Wrapper', 'rental'),
                    'id' => 'vehicles_list',
                    'status' => '',
                )
        );


        if (isset($_GET['post']) && $_GET['post'] != '') {

            $cs_form_fields->cs_wrapper_start_render(
                    array('name' => __('Wrapper', 'rental'),
                        'id' => 'vehicle_detail',
                        'status' => 'show',
                    )
            );

            $cs_form_fields->cs_booking_detail_render(
                    array('name' => __('Booking Detail', 'rental'),
                        'id' => 'vehicle_detail',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );

            $cs_form_fields->cs_wrapper_end_render(
                    array('name' => __('Wrapper', 'rental'),
                        'id' => 'booking_detail',
                        'status' => '',
                    )
            );
        } else {
            $cs_form_fields->cs_form_button_render(
                    array('name' => __('Search Vehicle', 'rental'),
                        'id' => 'search_vehicle',
                        'classes' => '',
                        'std' => __('Search Vehicle', 'rental'),
                        'description' => '',
                        'hint' => ''
                    )
            );
        }

        $cs_form_fields->cs_wrapper_start_render(
                array('name' => __('Wrapper', 'rental'),
                    'id' => 'vehicle_availability',
                    'status' => 'show',
                )
        );


        $cs_form_fields->cs_wrapper_end_render(
                array('name' => __('Wrapper', 'rental'),
                    'id' => 'vehicle_availability',
                    'status' => '',
                )
        );

        $cs_form_fields->cs_wrapper_start_render(
                array('name' => __('Wrapper', 'rental'),
                    'id' => 'vehicle_detail',
                    'status' => 'show',
                )
        );


        $cs_form_fields->cs_wrapper_end_render(
                array('name' => __('Wrapper', 'rental'),
                    'id' => 'vehicle_detail',
                    'status' => '',
                )
        );

        $cs_form_fields->cs_wrapper_start_render(
                array('name' => __('Wrapper', 'rental'),
                    'id' => 'vehicle_extras',
                    'status' => 'show',
                )
        );

        if (isset($_GET['post']) && $_GET['post'] != '') {

            $cs_form_fields->cs_booking_extras_list(
                    array('name' => __('Extras', 'rental'),
                        'id' => 'booking_extras',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );
        }

        $cs_form_fields->cs_wrapper_end_render(
                array('name' => __('Wrapper', 'rental'),
                    'id' => 'vehicle_extras',
                    'status' => '',
                )
        );

        if (isset($_GET['post']) && $_GET['post'] != '') {
            // Do Nothing	
        } else {
            $cs_form_fields->cs_form_hidden_render(array('id' => 'admin_booking', 'std' => 'new', 'type' => '', 'return' => 'echo'));
        }
    }

}

/**
 * @Guest options
 * @return html
 *
 */
if (!function_exists('cs_guest_details')) {

    function cs_guest_details() {
        global $post, $cs_form_fields;
        $cs_form_fields->cs_heading_render(
                array('name' => __('Guest Detail', 'rental'),
                    'id' => 'guest_detail',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                )
        );

        $cs_cstmr_data = get_option("cs_customer_options");
        $cs_cstmr_counter = 0;
        $customers_options[''] = __('Select a customer', 'rental');
        if (is_array($cs_cstmr_data) && sizeof($cs_cstmr_data) > 0) {
            foreach ($cs_cstmr_data as $key => $cstmr) {
                if (isset($cs_cstmr_data[$key])) {
                    $cs_cstmr_fields = $cs_cstmr_data[$key];
                    if (isset($cs_cstmr_fields)) {
                        if ($cs_cstmr_fields['cus_f_name'] || $cs_cstmr_fields['cus_l_name']) {
                            $customers_options[$cs_cstmr_fields['cus_id']] = $cs_cstmr_fields['cus_f_name'] . ' ' . $cs_cstmr_fields['cus_l_name'];
                        }
                    }
                }
            }
        }

        $cs_form_fields->cs_form_select_render(
                array('name' => __('Select Guest', 'rental'),
                    'id' => 'select_guest',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'options' => $customers_options,
                    'hint' => ''
                )
        );
    }

}

/**
 * @Guest options
 * @return html
 *
 */
if (!function_exists('cs_general_settings')) {

    function cs_general_settings() {
        global $post, $cs_form_fields;
        $cs_form_fields->cs_heading_render(
                array('name' => __('Booking Status', 'rental'),
                    'id' => 'status',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                )
        );

        $cs_form_fields->cs_form_select_render(
                array('name' => __('Select Booking Status', 'rental'),
                    'id' => 'booking_status',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'options' => array('pending' => __('Pending', 'rental'), 'confirmed' => __('Confirmed', 'rental')),
                    'hint' => ''
                )
        );
    }

}
/**
 * @Booking options
 * @return html
 *
 */
if (!function_exists('cs_booking_payments')) {

    function cs_booking_payments() {

        global $post, $cs_form_fields, $cs_plugin_options;

        $cs_vat_switch = isset($cs_plugin_options['cs_vat_switch']) && $cs_plugin_options['cs_vat_switch'] == 'on' ? $cs_plugin_options['cs_vat_switch'] : 'off';
        $active = 'in-active';
        if (isset($_GET['post']) && $_GET['post'] != '') {
            $active = 'in-active';
        }

        $cs_form_fields->cs_heading_render(
                array('name' => __('Payment Detail', 'rental'),
                    'id' => 'payemnt_detail',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                )
        );

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Gross Total', 'rental'),
                    'id' => 'bkng_gross_total',
                    'classes' => '',
                    'std' => '',
                    'active' => $active,
                    'description' => '',
                    'hint' => ''
                )
        );

        if ($cs_vat_switch == 'on') {

            $cs_form_fields->cs_form_text_render(
                    array('name' => __('VAT', 'rental'),
                        'id' => 'bkng_tax',
                        'classes' => '',
                        'std' => '',
                        'active' => $active,
                        'description' => '',
                        'hint' => ''
                    )
            );
        }

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Grand Total', 'rental'),
                    'id' => 'bkng_grand_total',
                    'classes' => '',
                    'std' => '',
                    'active' => $active,
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Advance Payment', 'rental'),
                    'id' => 'bkng_advance',
                    'classes' => '',
                    'std' => '',
                    'active' => $active,
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Remaining', 'rental'),
                    'id' => 'bkng_remaining',
                    'classes' => '',
                    'std' => '',
                    'active' => '',
                    'description' => '',
                    'hint' => ''
                )
        );
    }

}

if (!class_exists('booking_meta')) {

    class booking_meta {

        private $cs_plugin_options;

        public function __construct() {
            $cs_plugin_options = get_option('cs_plugin_option');
            add_action('wp_ajax_cs_get_available_vehicles', array(&$this, 'cs_get_available_vehicles'));
            add_action('wp_ajax_nopriv_cs_get_available_vehicles', array(&$this, 'cs_get_available_vehicles'));
            add_action('wp_ajax_cs_get_vehicle_detail', array(&$this, 'cs_get_vehicle_detail'));
            add_action('wp_ajax_cs_get_vehicle_extras_detail', array(&$this, 'cs_get_vehicle_extras_detail'));
        }

        /**
         *
         * @Set Session
         *
         */
        public function cs_set_session($params = array()) {

            extract($params);

            $cs_post_data = array();

            $cs_post_data['cs_vehicle_type_id'] = $cs_vehicle_type;
            $cs_post_data['start_date'] = $start_date;
            $cs_post_data['end_date'] = $end_date;
            $cs_post_data['total_adults'] = $total_adults;
            $cs_post_data['total_childs'] = $total_childs;
            $cs_post_data['booking_id'] = $booking_id;
            $cs_post_data['vehicle_id'] = $vehicle_id;
            $cs_post_data['capacity'] = $capacity;
            $cs_post_data['total_days'] = $total_days;
            $cs_post_data['no_of_vehicles'] = $no_of_vehicles;
            $cs_post_data['member_data'] = $member_data['vehicle_data'];

            $_SESSION['admin_reservation'] = $cs_post_data;
        }

        /**
         *
         * @Get Available Vehicles
         *
         */
        public function cs_get_available_vehicles() {
            global $post;

            unset($_SESSION['admin_reserved_vehicles']);
            unset($_SESSION['admin_reservation']);

            $json = array();
            $json['output'] = '';
            $params = array();

            $json['output'] = '';
            $date_from = $_REQUEST['date_from'];
            $date_to = $_REQUEST['date_to'];
            $pickup_time = $_REQUEST['pickup_time'];
            $dropup_time = $_REQUEST['dropup_time'];
            $total_days = $_REQUEST['total_days'];
            $pickup_location = $_REQUEST['pickup_location'];
            $dropup_location = $_REQUEST['dropup_location'];
            $cs_type_id = $_REQUEST['cs_type_id'];
            $station = $_REQUEST['cs_station'];

            $vehicle_type = $cs_type_id;
            $start_time = $pickup_time;
            $end_time = $dropup_time;

            $params['cs_vehicle_type'] = $cs_type_id;
            $params['start_date'] = $date_from;
            $params['end_date'] = $date_to;
            $params['pickup_time'] = $pickup_time;
            $params['dropup_time'] = $dropup_time;
            $params['booking_id'] = '';
            $params['vehicle_id'] = '';
            $params['total_days'] = $total_days;
            $params['pickup_location'] = $pickup_location;
            $params['dropup_location'] = $dropup_location;


            if ($date_from == '' || $date_to == '' || $pickup_time == '' || $dropup_time == '' || $pickup_location == '') {
                $json['type'] = 'error';
                $json['message'] = __('Some error occur, pleae try again later.', 'rental');
            } else {
                //Check Bookings
                $output = '';

                $cs_vehicles = array();

                $output = '';
                $cs_vehicles = array();
                $cs_vehicle_capacity_data = array();
                $temp_data = array();
                $cs_args = array('posts_per_page' => '-1', 'post_type' => 'vehicles', 'orderby' => 'ID', 'post_status' => 'publish');

                $meta_fields_array = array('relation' => 'AND',);

                if (isset($vehicle_type) && $vehicle_type != '') {
                    $meta_fields_array[] = array(
                        'key' => 'cs_vehicle_type',
                        'value' => $vehicle_type,
                        'compare' => '=',
                    );
                }

                if (isset($pickup_location) && $pickup_location != '') {
                    $meta_fields_array[] = array(
                        'key' => 'cs_pickup_locations',
                        'value' => serialize(strval($pickup_location)),
                        'compare' => 'like',
                    );
                }

                if (isset($station) && $station = 'off' && $dropup_location != '') {
                    $meta_fields_array[] = array(
                        'key' => 'cs_drop_location',
                        'value' => serialize(strval($dropup_location)),
                        'compare' => 'like',
                    );
                }

                if (is_array($meta_fields_array) && count($meta_fields_array) > 1) {
                    $cs_args['meta_query'] = $meta_fields_array;
                }

                $query = new WP_Query($cs_args);
                $post_count = $query->post_count;
                $width = '300';
                $height = '300';
                $title_limit = 46;
                $excerpt = 255;
                $flag = false;
                $data_vehicle = '';
                $cs_type_data = get_option('cs_type_options');

                if ($query->have_posts() <> "") {
                    while ($query->have_posts()): $query->the_post();

                        $hide = 'false';
                        $thumbnail = '';
                        $cs_post_id = $post->ID;
                        $cs_postObject = get_post_meta(get_the_id(), "cs_array_data", true);
                        $cs_gallery = get_post_meta($post->ID, "cs_vehicle_image_gallery", true);
                        $cs_gallery = explode(',', $cs_gallery);

                        $cs_vehicle_price = get_post_meta($post->ID, "cs_vehicle_price", true);
                        $cs_vehicle_type = get_post_meta($post->ID, "cs_vehicle_type", true);

                        if (is_array($cs_gallery) && count($cs_gallery) > 0 && $cs_gallery[0] != '') {
                            $thumbnail = CS_FUNCTIONS()->cs_get_post_img($cs_gallery[0], $width, $height);
                        }

                        if ($thumbnail == '') {
                            $thumbnail = wp_car_rental::plugin_url() . '/assets/images/no-image.png';
                        }

                        $vehicles_type = isset($cs_type_data[$cs_vehicle_type]['cs_type_name']) ? $cs_type_data[$cs_vehicle_type]['cs_type_name'] : '';

                        // Day Off Checking
                        $is_day_off = $this->cs_check_day_off($pickup_location, $date_from, $date_to);

                        if ($is_day_off == 'exist') {
                            $hide = 'true';
                        } else {
                            if (isset($station) && $station = 'off' && $dropup_location != '') {
                                $is_day_off = $this->cs_check_day_off($dropup_location, $date_from, $date_to);

                                if ($is_day_off == 'exist') {
                                    $hide = 'true';
                                }
                            }
                        }

                        //Time Availabilty
                        if ($hide == 'false') {
                            $is_time_available = $this->cs_check_time_availabilty($params);
                            if ($is_time_available == 'exist') {
                                $hide = 'true';
                            }
                        }

                        //Booking Check
                        $cs_vehicles = get_post_meta($cs_post_id, 'cs_vehicle_meta_data', true);
                        if ($hide == 'false') {
                            if (isset($cs_vehicles) && !empty($cs_vehicles)) {

                                $cs_availabilty_counter = 0;
                                $start_date_time = $date_from . ' ' . $start_time;
                                $end_date_time = $date_to . ' ' . $end_time;

                                foreach ($cs_vehicles as $key => $cs_vehicle_refernce) {
                                    if ($cs_vehicle_refernce['status'] == 'active') {
                                        $booking_count = cs_check_booking($key, $start_date_time, $end_date_time);
                                        if ($booking_count <= 0) {
                                            //Vehicle Available
                                            $data_vehicle = $key;
                                            $cs_availabilty_counter++;
                                            $hide = 'false';
                                            break;
                                        } else {
                                            $hide = 'true';
                                        }
                                    } else {
                                        $hide = 'true';
                                        //No Vehicle Found
                                    }
                                }
                            }
                        }

                        $params['post_vehicle_id'] = $cs_post_id;

                        $json['output'] .= '<div class="bk-vehicle-availabilty">';
                        $json['output'] .= '<figure><a href="javascript:;"><img src="' . esc_url($thumbnail) . '" alt=""  /></a></figure>';
                        $json['output'] .= '<div class="bk-vehicle-detail">';
                        $json['output'] .= '<div class="bk-vehicle-name">';
                        $json['output'] .= get_the_title($cs_post_id);
                        $json['output'] .= '</div>';
                        $json['output'] .= '<div class="bk-vehicle-price"><span>' . __('Price', 'rental') . '</span>';
                        $json['output'] .= $this->cs_get_pricing_breakdown($params);
                        $json['output'] .= '</div>';
                        $json['output'] .= '<div class="bk-vehicle-capacity">';

                        if (isset($hide) && $hide == 'false') {
                            $json['output'] .= '<a href="javascript:;" data-vehicle="' . esc_attr($data_vehicle) . '" data-type="' . esc_attr($cs_vehicle_type) . '" data-post="' . absint($cs_post_id) . '" class="book-btn cs-select-vehicle">' . __('Book Now', 'rental') . '</a></div>';
                        }
                        $json['output'] .= '';
                        $json['output'] .= '</div>';
                        $json['output'] .= '</div>';
                        $json['output'] .= '</div>';


                    endwhile;
                    wp_reset_postdata();

                    $json['type'] .= __('success', 'rental');
                    $json['message'] .= __('Vehicle Found', 'rental');
                }
            }


            $json['output_vehicles'] = '<script>jQuery(document).ready(function() { cs_select_vehicle(); });</script>';
            $json['output_vehicles'] .= '<div class="wrapper_vehicle_detail" id="wrapper_vehicle_detail" style="display:none; margin-left: 0px; margin-top: 5px;">';
            $json['output_vehicles'] .= '<div class="bk-vehicle-deail cs-gross-calculation  cs-current-vehicle" data-key="0"></div>';
            $json['output_vehicles'] .= '</div>';
            echo json_encode($json);
            die();
        }

        /**
         *
         * @Check Day OFF
         *
         */
        public function cs_check_day_off($post_id = '', $start_date, $end_date) {
            global $post;

            $session_data = isset($_SESSION['cs_reservation']) ? $_SESSION['cs_reservation'] : '';
            $date_from = $start_date;
            $date_to = $end_date;

            $start_date = strtotime($date_from);
            $end_date = strtotime($date_to);

            $brk_counter = 0;
            $get_posts = array($post_id);

            for ($i = $start_date; $i <= $end_date; $i = $i + 86400) {
                $brk_counter++;
                $thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
                $cs_args = array('posts_per_page' => '-1', 'post_type' => 'locations', 'post__in' => $get_posts, 'orderby' => 'ID', 'post_status' => 'publish');

                $meta_fields_array = array('relation' => 'AND',);
                $meta_fields_array[] = array(
                    'key' => 'cs_off_days',
                    'value' => serialize(strval($date_from)),
                    'compare' => 'LIKE',
                );

                if (is_array($meta_fields_array) && count($meta_fields_array) > 1) {
                    $cs_args['meta_query'] = $meta_fields_array;
                }

                /* echo '<pre>';
                  print_r( $cs_args );
                  echo '</pre>'; */

                $day_query = new WP_Query($cs_args);
                $day_count = $day_query->post_count;
                wp_reset_postdata();

                if ($day_count > 0) {
                    return 'exist';
                    break;
                } else {
                    return 'null';
                    break;
                }
            }
        }

        /**
         *
         * @Pricing Breakdown
         *
         */
        public function cs_get_pricing_breakdown($params) {
            global $post, $cs_plugin_options;
            extract($params);


            $currency_sign = isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] != '' ? $cs_plugin_options['currency_sign'] : '$';

            $date_from = $start_date;
            $date_to = $end_date;

            $start_date = strtotime($date_from);
            $end_date = strtotime($date_to);

            // Loop between timestamps, 24 hours at a time
            $total_price = '';
            $adult_price = 0;

            $pricings = get_option('cs_price_options');
            $cs_offers_options = get_option("cs_offers_options");

            $pricings_array = $pricings[$post_vehicle_id];

            if (isset($pricings[$post_vehicle_id]['cs_plan_days'])) {
                $cs_sp_days = $pricings[$post_vehicle_id]['cs_plan_days'];
            }

            $pricing_data = array();
            $brk_counter = 0;
            $total_orignal = 0;
            $price['total_price'] = 0;
            $price['total_sum'] = 0;
            $total_days = 0;
            $price_breakdown = '';
            $output = '';
            $flag = false;

            for ($i = $start_date; $i <= $end_date; $i = $i + 86400) {
                $total_days++;
                $brk_counter++;
                $thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
                $day = strtolower(date('D', strtotime($thisDate)));

                $adult_price = $pricings_array['cs_pricing_branches']['adult_' . $day . '_price'][0];


                $adult_temp_price = $adult_price != '' ? $adult_price : 0;
                $adult_price = $adult_temp_price;
                $to_check_date = strtotime(date('Y-m-d', $i));

                // Special Prices Calculations
                if (isset($cs_sp_days['start_date'][0]) && $cs_sp_days['start_date'][0] != '') {
                    foreach ($cs_sp_days['start_date'] as $key => $sp_price_date) {
                        $sp_start_date = $cs_sp_days['start_date'][$key];
                        $sp_end_date = $cs_sp_days['end_date'][$key];

                        $sp_start_date = date('Y-m-d', strtotime($sp_start_date));
                        $sp_end_date = date('Y-m-d', strtotime($sp_end_date));

                        if (isset($sp_start_date) && isset($sp_end_date)) {
                            $sp_start_date = strtotime($sp_start_date);
                            $sp_end_date = strtotime($sp_end_date);

                            if ($to_check_date >= $sp_start_date && $to_check_date <= $sp_end_date) {

                                if (isset($pricings[$post_id]['cs_plan_prices'])) {
                                    $flag = true;
                                    $cs_plan_prices = $pricings[$post_vehicle_id]['cs_plan_prices'][$key];
                                    $cs_plan_prices['adult_' . $day . '_price'][0];
                                    $adult_price = $cs_plan_prices['adult_' . $day . '_price'][0];
                                }
                            }
                        }
                    }
                }

                //Offers Calculations, Note: It will override special Prices if date exist
                if (isset($cs_offers_options) && !empty($cs_offers_options)) {
                    foreach ($cs_offers_options as $key => $offer_data) {

                        $offer_start_date = date('Y-m-d', strtotime($offer_data['start_date']));
                        $offer_end_date = date('Y-m-d', strtotime($offer_data['end_date']));

                        if (isset($offer_start_date) && isset($offer_end_date)) {
                            $offer_start_date = strtotime($offer_start_date);
                            $offer_end_date = strtotime($offer_end_date);

                            if ($to_check_date >= $offer_start_date && $to_check_date <= $offer_end_date) {
                                $offer_discount = $offer_data['discount'];
                                if ($cs_booking_days <= $offer_data['min_days']) {
                                    $adult_price = $pricings_array['cs_pricing_branches']['adult_' . $day . '_price'][0];
                                    $flag = true;
                                    $discount_adult_price = ( $adult_temp_price / 100 ) * $offer_discount;
                                    $adult_price = $adult_temp_price - $discount_adult_price;
                                }
                            }
                        }
                    }
                }

                //Total By Person
                $cs_total = $adult_price;
                $total_temp = $adult_temp_price;
                $total_orignal += $total_temp;

                //For Discount

                $total_price = $price['total_price'] + $cs_total;

                $price['total_price'] = $total_price;

                $total_sum = $adult_temp_price;
                $total_price = $price['total_sum'] + $total_sum;

                $price['total_sum'] = $total_price;
            }

            if ($flag == true) {
                $output .= '<em class="new-price">' . $currency_sign . number_format($price['total_price'], 2) . '</em><em class="old-price">' . $currency_sign . number_format($total_orignal, 2) . '</em>';
            } else {
                $output .= '<em class="new-price">' . $currency_sign . number_format($price['total_price'], 2) . '</em>';
                ;
            }

            return $output;
        }

        /**
         *
         * @Check Time Availabilty
         *
         */
        public function cs_check_time_availabilty($params = '') {
            global $post;

            extract($params);

            $cs_days_data = get_post_meta($pickup_location, "cs_days_data", false);
            $cs_days_data = $cs_days_data[0];

            $cs_location_start_time = get_post_meta($pickup_location, "cs_location_start_time", false);
            $cs_location_start_time = $cs_location_start_time[0];

            $cs_location_end_time = get_post_meta($pickup_location, "cs_location_end_time", false);
            $cs_location_end_time = $cs_location_end_time[0];

            //Time
            $start_time = $pickup_time;
            $end_time = $dropup_time;
            $start_time = strtotime($start_time);
            $end_time = strtotime($end_time);

            //Date
            $date_from = $start_date;
            $date_to = $end_date;
            $start_date = strtotime($date_from);
            $end_date = strtotime($date_to);

            $brk_counter = 0;
            $check_time = false;
            $week_day = array(1 => 'monday', 2 => 'tuesday', 3 => 'wednesday', 4 => 'thursday', 5 => 'friday', 6 => 'saturday', 7 => 'sunday');
            for ($i = $start_date; $i <= $end_date; $i = $i + 86400) {

                $brk_counter++;
                $thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
                $day = strtolower(date('l', strtotime($thisDate)));
                $day_status = $cs_days_data[$day];

                $key = array_search($day, $week_day);

                if ($day_status == 'off') {
                    $check_time = true;
                    return 'exist';
                    break;
                }

                if ($check_time == false) {

                    // End Time
                    $location_start_time = $cs_location_start_time[$key];
                    $temp_start_time = date("h:i A", strtotime($location_start_time));
                    $db_start_time = strtotime($temp_start_time);
                    // End Time
                    $location_end_time = $cs_location_end_time[$key];
                    $temp_end_time = date("h:i A", strtotime($location_end_time));
                    $db_end_time = strtotime($temp_end_time);

                    if ($db_start_time <= $start_time && $db_end_time >= $end_date) {
                        $check_time = true;
                    }

                    if ($check_time == true) {
                        return 'exist';
                    }
                }
            }

            return 'null';
        }

        /**
         *
         * @Get Vehicle Detail
         *
         */
        public function cs_get_vehicle_detail() {

            global $post, $cs_plugin_options;

            $currency_sign = isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] != '' ? $cs_plugin_options['currency_sign'] : '$';
            $cs_payment_vat = isset($cs_plugin_options['cs_payment_vat']) && $cs_plugin_options['cs_payment_vat'] != '' ? $cs_plugin_options['cs_payment_vat'] : '0';
            $cs_vat_switch = isset($cs_plugin_options['cs_vat_switch']) && $cs_plugin_options['cs_vat_switch'] == 'on' ? $cs_plugin_options['cs_vat_switch'] : 'off';

            $post_id = $_REQUEST['post_id'];
            $vehicle_id = $_REQUEST['vehicle_id'];
            $vehicle_type = $_REQUEST['vehicle_type'];

            $date_from = $_REQUEST['date_from'];
            $date_to = $_REQUEST['date_to'];
            $start_time = $_REQUEST['start_time'];
            $end_time = $_REQUEST['end_time'];

            $json = array();

            $json['selected_vehicle'] = '';
            $json['selection_done'] = '';
            $json['grand_total'] = '';
            $json['vat_price'] = '';
            $price_breakdown = '';
            $total_days = 0;
            $price['total_price'] = 0;
            $price['total_sum'] = 0;
            $offer_discount = 0;
            $total_temp = 0;
            $total_orignal = 0;

            $currency_sign = isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] != '' ? $cs_plugin_options['currency_sign'] : '$';
            $cs_page_id = isset($cs_plugin_options['cs_reservation']) && $cs_plugin_options['cs_reservation'] != '' && absint($cs_plugin_options['cs_reservation']) ? $cs_plugin_options['cs_reservation'] : '';

            $search_link = add_query_arg(array('action' => 'rental'), esc_url(get_permalink($cs_page_id)));

            $start_date = strtotime($date_from);
            $end_date = strtotime($date_to);

            $datetime1 = date_create($date_from);
            $datetime2 = date_create($date_to);
            $interval = date_diff($datetime1, $datetime2);

            $cs_booking_days = $interval->days;

            // Loop between timestamps, 24 hours at a time
            $total_price = '';
            $adult_price = 0;

            $pricings = get_option('cs_price_options');
            $cs_offers_options = get_option("cs_offers_options");

            $pricings_array = $pricings[$post_id];

            if (isset($pricings[$post_id]['cs_plan_days'])) {
                $cs_sp_days = $pricings[$post_id]['cs_plan_days'];
            }

            $pricing_data = array();
            $brk_counter = 0;
            $total_orignal = 0;
            $price['total_price'] = 0;
            $flag = false;

            for ($i = $start_date; $i <= $end_date; $i = $i + 86400) {
                $total_days++;
                $brk_counter++;
                $thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
                $day = strtolower(date('D', strtotime($thisDate)));

                $adult_price = $pricings_array['cs_pricing_branches']['adult_' . $day . '_price'][0];


                $adult_temp_price = $adult_price != '' ? $adult_price : 0;
                $adult_price = $adult_temp_price;
                $to_check_date = strtotime(date('Y-m-d', $i));

                // Special Prices Calculations
                if (isset($cs_sp_days['start_date'][0]) && $cs_sp_days['start_date'][0] != '') {
                    foreach ($cs_sp_days['start_date'] as $key => $sp_price_date) {
                        $sp_start_date = $cs_sp_days['start_date'][$key];
                        $sp_end_date = $cs_sp_days['end_date'][$key];

                        $sp_start_date = date('Y-m-d', strtotime($sp_start_date));
                        $sp_end_date = date('Y-m-d', strtotime($sp_end_date));

                        if (isset($sp_start_date) && isset($sp_end_date)) {
                            $sp_start_date = strtotime($sp_start_date);
                            $sp_end_date = strtotime($sp_end_date);

                            if ($to_check_date >= $sp_start_date && $to_check_date <= $sp_end_date) {

                                if (isset($pricings[$vehicle_id]['cs_plan_prices'])) {
                                    $flag = true;
                                    $cs_plan_prices = $pricings[$vehicle_id]['cs_plan_prices'][$key];
                                    $cs_plan_prices['adult_' . $day . '_price'][0];
                                    $adult_price = $cs_plan_prices['adult_' . $day . '_price'][0];
                                }
                            }
                        }
                    }
                }

                //Offers Calculations, Note: It will override special Prices if date exist

                if (isset($cs_offers_options) && !empty($cs_offers_options)) {
                    foreach ($cs_offers_options as $key => $offer_data) {

                        $offer_start_date = date('Y-m-d', strtotime($offer_data['start_date']));
                        $offer_end_date = date('Y-m-d', strtotime($offer_data['end_date']));

                        if (isset($offer_start_date) && isset($offer_end_date)) {
                            $offer_start_date = strtotime($offer_start_date);
                            $offer_end_date = strtotime($offer_end_date);

                            if ($to_check_date >= $offer_start_date && $to_check_date <= $offer_end_date) {
                                $offer_discount = $offer_data['discount'];
                                if ($cs_booking_days <= $offer_data['min_days']) {
                                    $adult_price = $pricings_array['cs_pricing_branches']['adult_' . $day . '_price'][0];
                                    $flag = true;
                                    $discount_adult_price = ( $adult_temp_price / 100 ) * $offer_discount;
                                    $adult_price = $adult_temp_price - $discount_adult_price;
                                }
                            }
                        }
                    }
                }

                //Total By Person
                $cs_total = $adult_price;
                $total_temp = $adult_temp_price;
                $total_orignal += $total_temp;

                //For Discount

                $total_price = $price['total_price'] + $cs_total;

                $price['total_price'] = $total_price;
                $total_sum = $adult_temp_price;
                $total_price = $price['total_sum'] + $total_sum;

                $price['total_sum'] = $total_price;

                //price Breakdown

                $price_breakdown .= '<input type="hidden" name="cs_adult_price[' . $brk_counter . '][]" value="' . $adult_price . '" />';
                $price_breakdown .= '<input type="hidden" name="cs_date[' . $brk_counter . '][]" value="' . $thisDate . '" />';
            }

            $cs_type_data = get_option('cs_type_options');
            $vehicles_type = isset($cs_type_data[$vehicle_type]['cs_type_name']) ? $cs_type_data[$vehicle_type]['cs_type_name'] : '';

            $json['selected_vehicle'] .= '<div class="bk-vehicle-wrap reservation-inner" data-price="' . $price['total_price'] . '" data-vat_switch="' . $cs_vat_switch . '"  data-vat="' . $cs_payment_vat . '">';
            $json['selected_vehicle'] .= '<div class="bk-vehicle-name">';
            $json['selected_vehicle'] .= get_the_title($post_id) . ' #' . $vehicle_id;
            $json['selected_vehicle'] .= '</div>';
            $json['selected_vehicle'] .= '<div class="bk-vehicle-capacity">';
            $json['selected_vehicle'] .= '<span> <b>' . __('Price', 'rental') . '</b>: ' . $currency_sign . number_format($price['total_price'], 2) . '</span>';
            $json['selected_vehicle'] .= '<input type="hidden" name="cs_booked_vehicle_id" value="' . $post_id . '" />';
            $json['selected_vehicle'] .= $price_breakdown;
            $json['selected_vehicle'] .= '</div>';
            $json['selected_vehicle'] .= '<script>jQuery(document).ready(function() { cs_vehicle_extras(); });</script>';

            $json['total_price'] = number_format($price['total_price'], 2);

            $cs_payment_vat = isset($cs_plugin_options['cs_payment_vat']) && $cs_plugin_options['cs_payment_vat'] != '' ? $cs_plugin_options['cs_payment_vat'] : '0';
            $cs_vat_switch = isset($cs_plugin_options['cs_vat_switch']) && $cs_plugin_options['cs_vat_switch'] == 'on' ? $cs_plugin_options['cs_vat_switch'] : 'off';

            if ($cs_vat_switch == 'on') {
                $vat = number_format(( $price['total_price'] / 100 ) * $cs_payment_vat, 2);
            } else {
                $vat = 0.00;
            }

            // Advance and Remainings if Transactions exist
            $transaction_amount = 0;
            if (isset($cs_booking_id) && $cs_booking_id != '') {
                $cs_transactions = get_option('cs_transactions');
                if (is_array($cs_transactions) && sizeof($cs_transactions) > 0) {

                    foreach ($cs_transactions as $key => $trans) {
                        if ($trans['cs_booking_id'] == $cs_booking_id) {
                            if ($trans['cs_trans_status'] == 'approved') {
                                $transaction_amount += $trans['cs_trans_amount'];
                            }
                        }
                    }
                }
            }



            $gross_total = $price['total_price'];
            $grand_total = number_format($price['total_price'] + $vat, 2);

            $json['grand_total'] .= str_replace(',', '', $grand_total);
            $json['vat_price'] .= number_format($vat, 2);

            $json['advance'] = number_format($transaction_amount, 2);
            $json['remaining'] = number_format($grand_total - $transaction_amount, 2);

            $vehicles_array = array();
            $vehicles_array[strtolower($vehicle_id)]['key'] = $vehicle_id;
            $vehicles_array[strtolower($vehicle_id)]['vehicle_id'] = $vehicle_id;
            $vehicles_array[strtolower($vehicle_id)]['vehicle_type'] = $vehicle_type;
            $vehicles_array[strtolower($vehicle_id)]['price'] = $price['total_price'];
            $vehicles_array[strtolower($vehicle_id)]['orignal_price'] = $total_orignal;
            $vehicles_array[strtolower($vehicle_id)]['discount'] = $offer_discount;


            $_SESSION['admin_reserved_vehicles'] = $vehicles_array;
            $cs_form_fields = new cs_form_fields();
            $json['selection_done'] .= $cs_form_fields->cs_booking_extras_list_ajax(
                    array('name' => __('Extras', 'rental'),
                        'id' => 'booking_extras',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'guests' => '',
                        'days' => $total_days,
                        'post_id' => '',
                        'hint' => '',
                        'extra' => $price['total_price'],
                    )
            );

            $json['status'] = 'completed';

            echo json_encode($json);
            die;
        }

        /**
         *
         * @Check Booking
         *
         */
        public function cs_get_vehicle_extras_detail() {
            global $post, $cs_plugin_options;

            $extra_id = $_REQUEST['extra_id'];
            $guests = $_REQUEST['guests'];
            $days = $_REQUEST['days'];

            $cs_currency = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '';
            $cs_extras_options = isset($cs_plugin_options['cs_extra_features_options']) ? $cs_plugin_options['cs_extra_features_options'] : '';
            $extras = $cs_extras_options[$extra_id];
            if (is_array($extras) && !empty($extras)) {
                $price = $extras['cs_extra_feature_price'];
                if ($price != '') {

                    if (isset($days) && !empty($days)) {
                        $json['price'] = $json['price'] * $days;
                    }

                    $json['total_price'] = $json['price'];
                    $json['price'] = $cs_currency . $json['price'];
                }
            } else {
                $json['type'] = 'error';
                $json['message'] = __('Some error occur,please try again later.', 'rental');
            }

            echo json_encode($json);
            die();
        }

    }

    new booking_meta();
}