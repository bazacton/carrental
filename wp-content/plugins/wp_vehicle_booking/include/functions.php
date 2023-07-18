<?php
/**
 * File Type: Plugin Functions
 */
if (!class_exists('cs_booking_functions')) {

    class cs_booking_functions {

        // The single instance of the class
        protected static $_instance = null;

        public function __construct() {
            add_action('save_post', array($this, 'cs_save_post_option'));
        }

        // Main Fuunctions Instance
        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        // Saving Post Meta
        public function cs_save_post_option($post_id = '') {

            global $post, $cs_plugin_options;

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            // Saving Booking Data
            if (isset($_POST['cs_admin_booking']) && $_POST['cs_admin_booking'] == 'new') {
                $reserved_vehicles = $_SESSION['admin_reserved_vehicles'];
                $cs_booked_vehicles = '';
                foreach ($reserved_vehicles as $key => $value) {
                    $cs_booked_vehicles = $key;
                    break;
                }

                $cs_payment_vat = isset($cs_plugin_options['cs_payment_vat']) && $cs_plugin_options['cs_payment_vat'] != '' ? $cs_plugin_options['cs_payment_vat'] : '0';
                update_post_meta($post_id, 'cs_invoice', $post_id);
                update_post_meta($post_id, 'cs_bkng_vat_percentage', $cs_payment_vat);
                update_post_meta($post_id, 'cs_booked_vehicle_data', $reserved_vehicles);
                update_post_meta($post_id, 'cs_booked_vehicle', $cs_booked_vehicles);

                $check_in_date = $_POST['cs_check_in_date'];
                $check_out_date = $_POST['cs_check_out_date'];

                $start_time = $_POST['cs_pickup_time'];
                $end_time = $_POST['cs_dropup_time'];

                $start_date_time = $check_in_date . ' ' . $start_time;
                $end_date_time = $check_out_date . ' ' . $end_time;
                update_post_meta($post_id, 'start_date_time', strtotime($start_date_time));
                update_post_meta($post_id, 'end_date_time', strtotime($end_date_time));
            }

            // Saving Data
            $data = array();

            foreach ($_POST as $key => $value) {

                if (strstr($key, 'cs_')) {
                    if ($key == 'cs_check_in_date' || $key == 'cs_check_out_date') {
                        $value = strtotime($value);
                        $_POST[$key] = $value;
                        update_post_meta($post_id, $key, $value); //exit;
                    } else {
                        //print_r($value);
                        $data[$key] = $value;
                        update_post_meta($post_id, $key, $value);
                    }
                }
            }

            update_post_meta($post_id, 'cs_array_data', $data);

            if (isset($_POST['cs_vehicle_meta'])) {

                $vehicles_data = array();
                for ($i = 0; $i < $_POST['cs_vehicle_num']; $i++) {
                    if ($_POST['cs_vehicle_meta'][$i] != '') {

                        if ($_POST['cs_vehicle_key'][$i] == '') {
                            $id = $this->cs_generate_random_string(5);
                        } else {
                            $id = $_POST['cs_vehicle_key'][$i];
                        }

                        $id = strtolower($id);
                        $vehicles_data[$id]['id'] = strtolower($_POST['cs_vehicle_meta'][$i]);
                        $vehicles_data[$id]['reference_no'] = $_POST['cs_vehicle_meta'][$i];
                        $vehicles_data[$id]['status'] = $_POST['cs_vehicle_status'][$i];
                        $vehicles_data[$id]['reason'] = $_POST['cs_vehicle_reason'][$i];
                    }
                }

                update_post_meta($post_id, 'cs_vehicle_meta_data', $vehicles_data);
            }

            if (isset($_POST['cs_off_day_status']) && $_POST['cs_off_day_status'] == 'on') {

                $json = array();
                $json_off = array();
                $flag = 0;
                if (isset($_POST['off_day'])) {
                    foreach ($_POST['off_day'] as $key => $value) {
                        $json[$flag]['title'] = __('Off', 'rental');
                        $json[$flag]['start'] = $value;
                        $json[$flag]['end'] = $value;
                        $flag++;
                    }
                }

                $schedule_array['schedule'] = $json;
                update_post_meta($post_id, 'cs_off_days', $schedule_array);
            }

            if (isset($_POST['cs_timing_array']) && $_POST['cs_timing_array'] != '') {
                $starttime = array();
                $endtime = array();
                $days = array();
                $week_day = array(1 => 'monday', 2 => 'tuesday', 3 => 'wednesday', 4 => 'thursday', 5 => 'friday', 6 => 'saturday', 7 => 'sunday');

                for ($i = 1; $i <= 7; $i++) {
                    $starttime[$i] = $_POST['starttime'][$i];
                    $endtime[$i] = $_POST['endtime'][$i];

                    if (trim($_POST['days'][$week_day[$i]]) == '') {
                        $value = 'on';
                    } else {
                        $value = $_POST['days'][$week_day[$i]];
                    }

                    $days[$week_day[$i]] = $value;
                }

                update_post_meta($post_id, 'cs_location_start_time', $starttime);
                update_post_meta($post_id, 'cs_location_end_time', $endtime);
                update_post_meta($post_id, 'cs_days_data', $days);
            }
        }

        // Special Characters
        public function cs_special_chars($input = '') {
            $output = $input;
            return $output;
        }

        // Convert Hours To minuts
        public function cs_hoursToMinutes($hours) {
            $minutes = 0;
            if (strpos($hours, ':') !== false) {
                // Split hours and minutes. 
                list($hours, $minutes) = explode(':', $hours);
            }
            return $hours * 60 + $minutes;
        }

        // Slugify the Text
        public function cs_slugy_text($str) {
            $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
            $clean = strtolower(trim($clean, '_'));
            $clean = preg_replace("/[\/_|+ -]+/", '_', $clean);
            return $clean;
        }

        // Random Id
        public function cs_rand_id() {
            $output = rand(12345678, 98765432);
            return $output;
        }

        // Advance Deposit
        public function cs_percent_return($num) {

            if (is_numeric($num) && $num > 0 && $num <= 100) {
                $num = $num;
            } else if (is_numeric($num) && $num > 0 && $num > 100) {
                $num = 100;
            } else {
                $num = 0;
            }

            return $num;
        }

        // Number Format
        public function cs_num_format($num) {
            $cs_number = number_format((float) $num, 2, '.', '');
            return $cs_number;
        }

        // Calculate Percentage
        public function cs_calc_percentage($number, $perc) {
            $cs_number = 0;
            if (is_numeric($number) && $number > 0 && is_numeric($perc) && $perc > 0) {
                $cs_number = ($number / 100) * $perc;
            }
            return $cs_number;
        }

        // Get Image Src
        public function cs_attach_image_src($attachment_id, $width, $height) {
            $image_url = wp_get_attachment_image_src($attachment_id, array($width, $height), true);
            if ($image_url[1] == $width and $image_url[2] == $height)
                ;
            else
                $image_url = wp_get_attachment_image_src($attachment_id, "full", true);
            $parts = explode('/uploads/', $image_url[0]);
            if (count($parts) > 1)
                return $image_url[0];
        }

        // Get Gallery First Image Src
        public function cs_gallery_image_src($post_id, $width = 150, $height = 150) {

            $image_url = get_post_meta($post_id, '_vehicle_image_gallery', true);
            $image_url = explode(',', $image_url);
            if (is_array($image_url) && sizeof($image_url) > 0) {
                $image_url = isset($image_url[0]) ? $this->cs_attach_image_src((int) $image_url[0], $width, $height) : '';
            } else {
                $image_url = '';
            }
            return $image_url;
        }

        // Get Post id through meta key
        public function cs_get_post_id_by_meta_key($key, $value) {
            global $wpdb;
            $meta = $wpdb->get_results("SELECT * FROM `" . $wpdb->postmeta . "` WHERE meta_key='" . $key . "' AND meta_value='" . $value . "'");

            if (is_array($meta) && !empty($meta) && isset($meta[0])) {
                $meta = $meta[0];
            }
            if (is_object($meta)) {
                return $meta->post_id;
            } else {
                return false;
            }
        }

        public function cs_show_all_cats($parent, $separator, $selected = "", $taxonomy) {
            if ($parent == "") {
                global $wpdb;
                $parent = 0;
            } else
                $separator .= " &ndash; ";
            $args = array(
                'parent' => $parent,
                'hide_empty' => 0,
                'taxonomy' => $taxonomy
            );
            $categories = get_categories($args);
            foreach ($categories as $category) {
                ?>
                <option <?php if ($selected == $category->slug) echo "selected"; ?> value="<?php echo esc_attr($category->slug); ?>"><?php echo esc_attr($separator . $category->cat_name); ?></option>
                <?php
                cs_show_all_cats($category->term_id, $separator, $selected, $taxonomy);
            }
        }

        // Excerpt
        public function cs_get_the_excerpt($charlength = '255', $readmore = 'true', $readmore_text = 'Read More') {
            global $post, $cs_theme_option;

            $excerpt = trim(preg_replace('/<a[^>]*>(.*)<\/a>/iU', '', get_the_excerpt()));

            if (strlen($excerpt) > $charlength) {
                if ($charlength > 0) {
                    $excerpt = substr($excerpt, 0, $charlength);
                } else {
                    $excerpt = $excerpt;
                }
                if ($readmore == 'true') {
                    $more = '<a href="' . esc_url(get_permalink()) . '" class="read-more">::' . esc_attr($readmore_text) . '</a>';
                } else {
                    $more = '...';
                }
                return $excerpt . $more;
            } else {
                return $excerpt;
            }
        }

        /**
         *
         * Get Post Image
         *
         */
        public function cs_get_post_img($post_id, $width, $height) {
            $image_url = wp_get_attachment_image_src($post_id, array($width, $height), true);
            return $image_url[0];
        }

        /**
         *
         * Get Post Image
         *
         */
        public function cs_icomoons($icon_value = '', $id = '', $name = '') {
            ob_start();
            ?>
            <script>
                jQuery(document).ready(function ($) {

                    var e9_element = $('#e9_element_<?php echo cs_allow_special_char($id); ?>').fontIconPicker({
                        theme: 'fip-bootstrap'
                    });
                    // Add the event on the button
                    $('#e9_buttons_<?php echo cs_allow_special_char($id); ?> button').on('click', function (e) {
                        e.preventDefault();
                        // Show processing message
                        $(this).prop('disabled', true).html('<i class="icon-cog demo-animate-spin"></i> Please wait...');
                        $.ajax({
                            url: '<?php echo wp_car_rental::plugin_url(); ?>/assets/icomoon/js/selection.json',
                            type: 'GET',
                            dataType: 'json'
                        })
                                .done(function (response) {
                                    // Get the class prefix
                                    var classPrefix = response.preferences.fontPref.prefix,
                                            icomoon_json_icons = [],
                                            icomoon_json_search = [];
                                    $.each(response.icons, function (i, v) {
                                        icomoon_json_icons.push(classPrefix + v.properties.name);
                                        if (v.icon && v.icon.tags && v.icon.tags.length) {
                                            icomoon_json_search.push(v.properties.name + ' ' + v.icon.tags.join(' '));
                                        } else {
                                            icomoon_json_search.push(v.properties.name);
                                        }
                                    });
                                    // Set new fonts on fontIconPicker
                                    e9_element.setIcons(icomoon_json_icons, icomoon_json_search);
                                    // Show success message and disable
                                    $('#e9_buttons_<?php echo cs_allow_special_char($id); ?> button').removeClass('btn-primary').addClass('btn-success').text('Successfully loaded icons').prop('disabled', true);
                                })
                                .fail(function () {
                                    // Show error message and enable
                                    $('#e9_buttons_<?php echo cs_allow_special_char($id); ?> button').removeClass('btn-primary').addClass('btn-danger').text('Error: Try Again?').prop('disabled', false);
                                });
                        e.stopPropagation();
                    });

                    jQuery("#e9_buttons_<?php echo cs_allow_special_char($id); ?> button").click();
                });


            </script>
            <input type="text" id="e9_element_<?php echo cs_allow_special_char($id); ?>" name="<?php echo cs_allow_special_char($name); ?>[]" value="<?php echo cs_allow_special_char($icon_value); ?>"/>
            <span id="e9_buttons_<?php echo cs_allow_special_char($id); ?>" style="display:none">
                <button autocomplete="off" type="button" class="btn btn-primary">Load from IcoMoon selection.json</button>
            </span>
            <?php
            $fontawesome = ob_get_clean();
            return $fontawesome;
        }

        /**
         * @ render Random ID
         *
         *
         */
        public static function cs_generate_random_string($length = 3) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $randomString;
        }

        public function cs_location() {
            global $cs_plugin_options, $post;

            $cs_post_loc_latitude = get_post_meta($post->ID, 'cs_post_loc_latitude', true);
            $cs_post_loc_longitude = get_post_meta($post->ID, 'cs_post_loc_longitude', true);
            $cs_post_loc_zoom = get_post_meta($post->ID, 'cs_post_loc_zoom', true);
            $cs_location_address = get_post_meta($post->ID, 'cs_location_address', true);
            $cs_add_new_loc = get_post_meta($post->ID, 'cs_add_new_loc', true);

            $cs_post_loc_longitude = $cs_post_loc_longitude != '' ? $cs_post_loc_longitude : '';
            $cs_post_loc_latitude = $cs_post_loc_latitude != '' ? $cs_post_loc_latitude : '';
            $cs_post_loc_zoom = $cs_post_loc_zoom != '' ? $cs_post_loc_zoom : '';
            $cs_location_address = $cs_location_address != '' ? $cs_location_address : '';
            $cs_add_new_loc = $cs_add_new_loc != '' ? $cs_add_new_loc : '';

            if ($cs_post_loc_latitude == '')
                $cs_post_loc_latitude = '0.1275';
            if ($cs_post_loc_longitude == '')
                $cs_post_loc_longitude = '51.5072';
            if ($cs_post_loc_zoom == '')
                $cs_post_loc_zoom = '11';

            $cs_rental = new wp_car_rental();
            $cs_rental->cs_location_gmap_script();
            $cs_rental->cs_google_place_scripts();
            $cs_rental->cs_autocomplete_scripts();
            ?>
            <script>

                function cs_gl_search_map() {
                    var vals;
                    vals = jQuery('#loc_address').val();
                    jQuery('.gllpSearchField').val(vals);
                }

                (function ($) {
                    $(function () {
            <?php
            $cs_rental->cs_google_place_scripts();
            ?>
                        var autocomplete;
                        autocomplete = new google.maps.places.Autocomplete(document.getElementById('loc_address'));

                    });
                })(jQuery);

            </script>

            <fieldset class="gllpLatlonPicker"  style="width:100%; float:left;">
                <div class="page-wrap page-opts left" style="overflow:hidden; position:relative;" id="locations_wrap" data-themeurl="<?php echo wp_car_rental::plugin_url(); ?>" data-plugin_url="<?php echo wp_car_rental::plugin_url(); ?>" data-ajaxurl="<?php echo esc_js(admin_url('admin-ajax.php'), 'rental'); ?>" data-map_marker="<?php echo wp_car_rental::plugin_url(); ?>/assets/images/map-marker.png">
                    <div class="option-sec" style="margin-bottom:0;">
                        <div class="opt-conts">  
                            <ul class="form-elements">
                                <li class="to-label">
                                    <label><?php _e('Location Address', 'rental'); ?></label>
                                </li>
                                <li class="to-field">
                                    <input name="cs_location_address" autocomplete="on" class="directory-search-location" id="loc_address" type="text" value="<?php echo htmlspecialchars($cs_location_address) ?>" onkeypress="cs_gl_search_map(this.value)" />
                                </li>
                            </ul>
                            <ul class="form-elements" style="display:none">
                                <li class="to-label">
                                    <label><?php _e('Latitude', 'rental'); ?></label>
                                </li>
                                <li class="to-field">
                                    <input type="hidden" name="cs_post_loc_latitude" value="<?php echo esc_attr($cs_post_loc_latitude); ?>" class="gllpLatitude" />
                                </li>
                            </ul>
                            <ul class="form-elements"  style="display:none">
                                <li class="to-label">
                                    <label><?php _e('Longitude', 'rental'); ?></label>
                                </li>
                                <li class="to-field">
                                    <input type="hidden" name="cs_post_loc_longitude" value="<?php echo esc_attr($cs_post_loc_longitude); ?>" class="gllpLongitude" />
                                </li>
                            </ul>
                            <ul class="form-elements">
                                <li class="to-label">
                                    <label></label>
                                </li>
                                <li class="to-field">
                                    <input type="button" class="gllpSearchButton" value="<?php _e('Search This Location on Map', 'rental'); ?>" onClick="cs_gl_search_map()">
                                </li>
                            </ul>
                            <div style="padding:.5%; margin-top:25px; margin-bottom:25px; float:left; border:1px solid #CCC; width:98%;">
                                <ul style="float: left; width:100%; margin:0px;" >
                                    <li>
                                        <div class="clear"></div>
                                        <input type="hidden" name="cs_add_new_loc" value="<?php esc_attr($cs_add_new_loc); ?>"  class="gllpSearchField" style="margin-bottom:10px;">
                                        <input type="hidden" name="cs_post_loc_zoom" value="<?php echo esc_attr($cs_post_loc_zoom); ?>" class="gllpZoom" />
                                        <input type="button" class="gllpUpdateButton" value="update map" style="display:none">
                                        <div class="clear"></div>
                                        <div style="float:left; width:100%; height:100%;">
                                            <div class="gllpMap" id="cs-map-location-id"></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <?php
        }

        /**
         * @Locations
         *
         */
        public function cs_location_fields($post_id) {
            global $cs_plugin_options, $post;

            if (isset($post_id) && $post_id != '') {
                $get_address = get_post_meta($post_id, 'cs_location_address', true);
            } else {
                $get_address = 'empty';
            }
            if ($get_address == 'empty' || empty($get_address)) {
                $data_options = 'cs_dummy_location';
            } else {
                $data_options = 'cs_location_address';
            }

            $prepAddr = str_replace(' ', '+', $get_address);
            $geocode = file_get_contents(cs_server_protocol() . 'google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');
            $output = json_decode($geocode);
            $Latitude = '';
            $Longitude = '';
            if(isset($output->results[0]->geometry->location->lat)){
            $Latitude = $output->results[0]->geometry->location->lat;
            $Longitude = $output->results[0]->geometry->location->lng;
            }
            $cs_post_loc_zoom = get_post_meta($post_id, 'cs_post_loc_zoom', true);
            ?>


            <input type="hidden" value="London, United Kingdom" id="cs_dummy_location" /> 

            <div class="row">
                <div style="float:left; border:1px solid #CCC; width:100%;">
                    <?php echo do_shortcode('[cs_map column_size="1/1" map_height="250" map_lat="' . $Latitude . '" map_lon="' . $Longitude . '" map_zoom="' . $cs_post_loc_zoom . '" map_type="ROADMAP" map_info="' . $get_address . '" map_info_width="250" map_info_height="100" map_marker_icon1xis="Browse" map_show_marker="true" map_controls="false" map_draggable="true" map_scrollwheel="true" map_border="yes"]'); ?>
                </div></div>
            <?php
        }

        /**
         * @get user ID
         *
         */
        public function cs_get_user_id() {
            global $current_user;
            get_currentuserinfo();
            return $current_user->ID;
        }

    }

    /**
     *
     * Design Pattern for Object initilization
     *
     */
    function CS_FUNCTIONS() {
        return cs_booking_functions::instance();
    }

    $GLOBALS['cs_booking_functions'] = CS_FUNCTIONS();
}

function cs_short_code($name = '', $function = '') {

    if ($name != '' && $function != '') {
        add_shortcode($name, $function);
    }
}

//Submit Form

function cs_contact_form_submit() {
    define('WP_USE_THEMES', false);
    $subject = '';
    $cs_contact_error_msg = '';
    $subject_name = 'Subject';
    $contact_name = '';
    $contact_email = '';
    $subject = '';
    $contact_msg = '';
    $cs_contact_succ_msg = '';
    $cs_contact_error_msg = '';
    $cs_contact_email = '';
    foreach ($_REQUEST as $keys => $values) {
        $$keys = $values;
    }

    if (isset($phone) && $phone <> '') {
        $subject_name = 'Phone';
        $subject = $phone;
    }
    $bloginfo = get_bloginfo();
    $subjecteEmail = "(" . $bloginfo . ") Contact Form Received";
    $message = '
            <table width="100%" border="1">
              <tr>
                <td width="100"><strong>' . __('Name:', 'car-rental') . '</strong></td>
                <td>' . $contact_name . '</td>
              </tr>
              <tr>
                <td><strong>' . __('Email:', 'car-rental') . '</strong></td>
                <td>' . $contact_email . '</td>
              </tr>
              <tr>
                <td><strong>' . $subject_name . ':</strong></td>
                <td>' . $subject . '</td>
              </tr>
              <tr>
                <td><strong>' . __('Message:', 'car-rental') . '</strong></td>
                <td>' . $contact_msg . '</td>
              </tr>
              <tr>
                <td><strong>' . __('IP Address', 'car-rental') . '</strong></td>
                <td>' . $_SERVER["REMOTE_ADDR"] . '</td>
              </tr>
            </table>';

    $headers = "From: " . $contact_email . "\r\n";
    $headers .= "Reply-To: " . $cs_contact_email . "\r\n";
    $headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $attachments = '';
    if (wp_mail($cs_contact_email, $subject_name, $message)) {
        $json = array();
        $json['type'] = "success";
        $json['message'] = '<p>' . cs_textarea_filter($cs_contact_succ_msg) . '</p>';
    } else {
        $json['type'] = "error";
        $json['message'] = '<p>' . cs_textarea_filter($cs_contact_error_msg) . '</p>';
    };

    echo json_encode($json);
    die();
}

add_action('wp_ajax_nopriv_cs_contact_form_submit', 'cs_contact_form_submit');
add_action('wp_ajax_cs_contact_form_submit', 'cs_contact_form_submit');

if (!function_exists('cs_remove_force_tag_theme')) {

    function cs_remove_force_tag_theme($content = '' ) {

        return force_balance_tags($content);
    }

}
if (!function_exists('cs_remove_force_tag_blnc_theme')) {

    function cs_remove_force_tag_blnc_theme($content = '', $force = false  ) {

        return balanceTags($content, $force);
    }

}
if (!function_exists('cs_check_host_theme')) {

    function cs_check_host_theme($content = '' ) {

        return $_SERVER[$content];
    }

}


if (!function_exists('cs_meta_box')) {
    function cs_meta_box($id, $title, $callback, $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null) {
		add_meta_box( $id, __($title,'car-rental'), $callback, $screen , $context , $priority, $callback_args);
    }
}

if (!function_exists('cs_widget_register')) {

    function cs_widget_register($name) {

        add_action('widgets_init', function() use ($name) {
            return register_widget($name);
        });
    }

}