<?php
/**
 * File Type: Template Functions
 */
/**
 *
 * @Check Booking
 *
 */
if ( ! function_exists( 'cs_check_booking' ) ) {

    function cs_check_booking( $key = '', $date_from = '', $date_to = '' ) {
        global $wpdb;

        $check_in_date = strtotime( $date_from );
        $check_out_date = strtotime( $date_to );
        $vehicle_key = strtolower( $key );

        $sql = "SELECT {$wpdb->prefix}posts.* FROM {$wpdb->prefix}posts 
				INNER JOIN {$wpdb->prefix}postmeta ON ( {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id ) 
				INNER JOIN {$wpdb->prefix}postmeta AS mt1 ON ( {$wpdb->prefix}posts.ID = mt1.post_id ) 
				INNER JOIN {$wpdb->prefix}postmeta AS mt2 ON ( {$wpdb->prefix}posts.ID = mt2.post_id )
				INNER JOIN {$wpdb->prefix}postmeta AS mt3 ON ( {$wpdb->prefix}posts.ID = mt3.post_id )
				INNER JOIN {$wpdb->prefix}postmeta AS mt4 ON ( {$wpdb->prefix}posts.ID = mt4.post_id )
				
				WHERE 1=1 
				
				AND (
						(
								( mt1.meta_key = 'start_date_time' AND mt1.meta_value  <= '" . $check_in_date . "' ) 
							AND 
								( mt2.meta_key = 'end_date_time' AND mt2.meta_value  >= '" . $check_in_date . "' )
						)
					
						OR (
									( mt1.meta_key = 'start_date_time' AND mt1.meta_value  <= '" . $check_out_date . "' ) 
								AND 
									( mt2.meta_key = 'end_date_time' AND mt2.meta_value  >= '" . $check_out_date . "' )
						)
						
						OR (
									( mt1.meta_key = 'start_date_time' AND mt1.meta_value  <= '" . $check_in_date . "' ) 
								AND 
									( mt2.meta_key = 'end_date_time' AND mt2.meta_value  >= '" . $check_out_date . "' )
						)
				)
				
				AND ( mt3.meta_key = 'cs_booking_status' AND CAST(mt3.meta_value AS CHAR) = 'confirmed' ) 
				AND ( mt4.meta_key = 'cs_booked_vehicle' AND CAST(mt4.meta_value AS CHAR) = '" . $vehicle_key . "' )
				
				AND {$wpdb->prefix}posts.post_type = 'booking' 
				AND (({$wpdb->prefix}posts.post_status = 'publish')) 
				GROUP BY {$wpdb->prefix}posts.ID 
				ORDER BY {$wpdb->prefix}posts.post_date DESC";

        $vehicle_query = $wpdb->get_results( $sql, OBJECT );

        if ( isset( $wpdb->num_rows ) && $wpdb->num_rows > 0 ) {
            return '1';
        } else {
            return '0';
        }
    }

}

/**
 *
 * @Set Session
 *
 */
function cs_set_session( $params ) {
    global $post;
    extract( $params );

    $cs_post_data = array();

    $cs_post_data['vehicle_type'] = $vehicle_type;
    $cs_post_data['start_date'] = $start_date;
    $cs_post_data['end_date'] = $end_date;
    $cs_post_data['start_time'] = $start_time;
    $cs_post_data['end_time'] = $end_time;
    $cs_post_data['booking_id'] = $booking_id;
    $cs_post_data['pickup_location'] = $pickup_location;
    $cs_post_data['dropup_location'] = $dropup_location;
    $cs_post_data['station'] = $station;

    $_SESSION['cs_reservation'] = $cs_post_data;
}

/**
 *
 * @Get Extras
 *
 */
if ( ! function_exists( 'cs_booking_extras' ) ) {

    function cs_booking_extras( $params = '' ) {
        global $post, $cs_plugin_options;
        extract( $params );
        $cs_meta_key = 'cs_booking_extras';

        $extrasList = array();
        $cs_currency = isset( $cs_plugin_options['currency_sign'] ) ? $cs_plugin_options['currency_sign'] : '';
        $cs_extras_options = isset( $cs_plugin_options['cs_extra_features_options'] ) ? $cs_plugin_options['cs_extra_features_options'] : '';
        $cs_extras_switch = isset( $cs_plugin_options['cs_extras_switch'] ) ? $cs_plugin_options['cs_extras_switch'] : '';
        $days = $days == 0 ? 1 : $days;
        $cs_output = '';
        if ( $cs_extras_switch <> '' and $cs_extras_switch == "on" ) {
            if ( is_array( $cs_extras_options ) && sizeof( $cs_extras_options ) > 0 ) {
                $cs_output .= '<div class="booking-step">';
                $cs_output .= '<ul class="assentioal-list">';
                $cs_extras_counter = 0;
                foreach ( $cs_extras_options as $extra_key => $extras ) {

                    if ( isset( $extra_key ) && $extra_key <> '' ) {
                        $extras_title = isset( $extras['cs_extra_feature_title'] ) ? $extras['cs_extra_feature_title'] : '';
                        $feature_desc = isset( $extras['cs_extra_feature_desc'] ) ? $extras['cs_extra_feature_desc'] : '';
                        if ( function_exists( 'icl_t' ) ) {
                            $extras_title = icl_t( 'Vehicle Extras', 'Extra "' . $extras_title . '" - Title field' );
                            $feature_desc = icl_t( 'Vehicle Extras', 'Extra "' . $feature_desc . '" - Description field' );
                        }
                        $extras_price = isset( $extras['cs_extra_feature_price'] ) ? $extras['cs_extra_feature_price'] : '';
                        $extras_id = isset( $extras['extra_feature_id'] ) ? $extras['extra_feature_id'] : '';
                        $checked = '';
                        $feature_type = isset( $extras['cs_extra_feature_type'] ) ? $extras['cs_extra_feature_type'] : '';

                        if ( is_array( $extrasList ) && in_array( $extras_id, $extrasList ) ) {
                            $checked = 'checked="checked"';
                        }

                        $cs_output .= '<li class="extras-list" data-price="' . $extras_price . '">';
                        $cs_output .= '<div class="booking-check-box">';
                        $cs_output .= '<input type="checkbox" class="cs-extras-check" id="extra_' . $extras_id . '" name="cs_' . sanitize_html_class( $id ) . '[' . $extras_id . '][]" ' . $checked . ' value="' . $extras_id . '">';

                        $cs_output .= '<label for="extra_' . $extras_id . '"></label>';
                        $cs_output .= '</div>';
                        $cs_output .= '<div class="booking-heading">';
                        $cs_output .= '<h5>' . esc_attr( $extras_title ) . '</h5>';
                        $cs_output .= '<p>' . esc_attr( $feature_desc ) . '</p>';
                        $cs_output .= '</div>';
                        $cs_output .= '<div class="booking-price">';
                        $cs_output .= '<input type="hidden" class="cs_currency_type" id="cs_currency_type" value="' . esc_attr( $cs_currency ) . '" />';
                        $cs_output .= '<input type="hidden" id="cs_extras_price" class="cs_extras_price" name="cs_extras_price[' . $extras_id . '][]" value="' . $extras_price . '" />';
                        $cs_output .= '<div class="select-booking">';
                        $cs_output .= '<select name="cs_days[' . $extras_id . '][]" disabled="disabled" id="cs-total-days" class="cs-total-days"  data-extra_id="' . $extras_id . '">';
                        for ( $i = 1; $i <= $days; $i ++ ) {
                            $cs_output .= '<option value="' . $i . '">' . $i . '</option>';
                        }
                        $cs_output .= '</select>';
                        $cs_output .= '</div>';
                        $cs_output .= '<span>' . esc_attr( $cs_currency ) . $extras_price . '</span></div>';
                        $cs_output .= '</li>';
                        $cs_extras_counter ++;
                    }
                }
                $cs_output .= '</ul>';
                $cs_output .= '</div>';
            }
        }
        return force_balance_tags( $cs_output );
    }

}

/**
 *
 * @Check Vehicle Availability
 *
 */
if ( ! function_exists( 'cs_check_availabilty' ) ) {

    function cs_check_availabilty() {
        global $post, $cs_plugin_options;

        $cs_charge_base = isset( $cs_plugin_options['cs_charge_base'] ) ? $cs_plugin_options['cs_charge_base'] : '';

        $post_id = $_REQUEST['post_id'];
        $vehicle_id = $_REQUEST['vehicle_id'];
        $vehicle_type = $_REQUEST['vehicle_type'];

        $session_data = isset( $_SESSION['cs_reservation'] ) ? $_SESSION['cs_reservation'] : array();

        $date_from = $session_data['start_date'];
        $date_to = $session_data['end_date'];
        $start_time = $session_data['start_time'];
        $end_time = $session_data['end_time'];

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
        $pricings_array = array();

        $currency_sign = isset( $cs_plugin_options['currency_sign'] ) && $cs_plugin_options['currency_sign'] != '' ? $cs_plugin_options['currency_sign'] : '$';
        $cs_page_id = isset( $cs_plugin_options['cs_reservation'] ) && $cs_plugin_options['cs_reservation'] != '' && absint( $cs_plugin_options['cs_reservation'] ) ? $cs_plugin_options['cs_reservation'] : '';

        $search_link = add_query_arg( array( 'action' => 'booking' ), esc_url( get_permalink( $cs_page_id ) ) );

        $start_date = strtotime( $date_from );
        $end_date = strtotime( $date_to );

        // start to end dates with time
        if ( $cs_charge_base == 'hourly' ) {
            $cs_start_date_time = strtotime( $date_from . ' ' . $start_time );
            $cs_end_date_time = strtotime( $date_to . ' ' . $end_time );
        } else {
            $cs_start_date_time = strtotime( $date_from . ' ' . $start_time );
            $cs_end_date_time = strtotime( $date_to . ' ' . $start_time );
        }

        $datetime1 = date_create( $date_from );
        $datetime2 = date_create( $date_to );

        $cs_booking_days = $cs_bking_days = ( ($end_date - $start_date) / (60 * 60 * 24) + 1 );

        //Loop between timestamps, 24 hours at a time
        $total_price = '';
        $adult_price = 0;

        $pricings = get_option( 'cs_price_options' );
        $cs_offers_options = get_option( "cs_offers_options" );

        $pricings_array = isset( $pricings[$post_id] ) ? $pricings[$post_id] : '';

        if ( isset( $pricings[$post_id]['cs_plan_days'] ) ) {
            $cs_sp_days = $pricings[$post_id]['cs_plan_days'];
        }

        $pricing_data = array();
        $brk_counter = 0;
        $total_orignal = 0;
        $price['total_price'] = 0;
        $flag = false;

        $cs_counter_plus_plus = 86400;
        if ( $cs_charge_base == 'hourly' ) {
            $cs_counter_plus_plus = 3600;
            if ( $cs_end_date_time > 3600 ) {
                $cs_end_date_time = (int) $cs_end_date_time - 3600;
            }
        }

        $cs_inner_l_count = 0;

        $cs_rent_total_hrs = 0;
        for ( $i = $cs_start_date_time; $i <= $cs_end_date_time; $i = $i + $cs_counter_plus_plus ) {
            $total_days ++;
            $brk_counter ++;
            $thisDate = date( 'Y-m-d', $i ); // 2010-05-01, 2010-05-02, etc
            $day = strtolower( date( 'D', strtotime( $thisDate ) ) );

            $adult_price = isset( $pricings_array['cs_pricing_branches']['adult_' . $day . '_price'][0] ) ? $pricings_array['cs_pricing_branches']['adult_' . $day . '_price'][0] : '';

            $adult_temp_price = $adult_price != '' ? $adult_price : 0;
            $adult_price = $adult_temp_price;
            $to_check_date = strtotime( date( 'Y-m-d', $i ) );

            // Special Prices Calculations
            if ( isset( $cs_sp_days['start_date'][0] ) && $cs_sp_days['start_date'][0] != '' ) {
                foreach ( $cs_sp_days['start_date'] as $key => $sp_price_date ) {
                    $sp_start_date = $cs_sp_days['start_date'][$key];
                    $sp_end_date = $cs_sp_days['end_date'][$key];

                    $sp_start_date = date( 'Y-m-d', strtotime( $sp_start_date ) );
                    $sp_end_date = date( 'Y-m-d', strtotime( $sp_end_date ) );

                    if ( isset( $sp_start_date ) && isset( $sp_end_date ) ) {
                        $sp_start_date = strtotime( $sp_start_date );
                        $sp_end_date = strtotime( $sp_end_date );

                        if ( $to_check_date >= $sp_start_date && $to_check_date <= $sp_end_date ) {
                            //var_dump($pricings[$post_id]);
                            if ( isset( $pricings[$post_id]['cs_plan_prices'] ) ) {

                                $flag = true;
                                $cs_plan_prices = $pricings[$post_id]['cs_plan_prices'][$key];
                                $cs_plan_prices['adult_' . $day . '_price'][0];
                                $adult_price = $cs_plan_prices['adult_' . $day . '_price'][0];

                                $adult_temp_price = $adult_price;
                            }
                        }
                    }
                }
            }

            //Offers Calculations, Note: It will override special Prices if date exist

            if ( isset( $cs_offers_options ) && ! empty( $cs_offers_options ) ) {

                $min_dys_array = $high_low_keys = array();
                foreach ( $cs_offers_options as $key => $offer_data ) {
                    $min_days_req = isset( $offer_data['min_days'] ) && $offer_data['min_days'] != '' ? absint( $offer_data['min_days'] ) : 0;
                    $min_dys_array[$key] = $min_days_req;
                }

                arsort( $min_dys_array );

                foreach ( $min_dys_array as $h_key => $h_val ) {
                    $high_low_keys[] = $h_key;
                }

                $cs_loop_count = 0;
                foreach ( $cs_offers_options as $key => $offer_data ) {

                    $cs_of_strt_date = isset( $cs_offers_options[$high_low_keys[$cs_loop_count]]['start_date'] ) ? $cs_offers_options[$high_low_keys[$cs_loop_count]]['start_date'] : '';
                    $cs_of_end_date = isset( $cs_offers_options[$high_low_keys[$cs_loop_count]]['end_date'] ) ? $cs_offers_options[$high_low_keys[$cs_loop_count]]['end_date'] : '';

                    $offer_start_date = date( 'Y-m-d', strtotime( $cs_of_strt_date ) );
                    $offer_end_date = date( 'Y-m-d', strtotime( $cs_of_end_date ) );

                    //echo date('Y-m-d', $to_check_date).'--';

                    $offer_start_date = strtotime( $offer_start_date );
                    $offer_end_date = strtotime( $offer_end_date );

                    $min_days_req = isset( $cs_offers_options[$high_low_keys[$cs_loop_count]]['min_days'] ) ? $cs_offers_options[$high_low_keys[$cs_loop_count]]['min_days'] : '';

                    if ( (int) $cs_bking_days > 0 && (int) $cs_bking_days >= (int) $min_days_req ) {

                        if ( $to_check_date >= $offer_start_date && $to_check_date <= $offer_end_date ) {

                            if ( $cs_inner_l_count == 0 ) {

                                $offer_discount = isset( $cs_offers_options[$high_low_keys[$cs_loop_count]]['discount'] ) ? $cs_offers_options[$high_low_keys[$cs_loop_count]]['discount'] : '';
                            }

                            $flag = true;
                            $discount_adult_price = ( $adult_temp_price / 100 ) * $offer_discount;
                            $adult_price = $adult_temp_price - $discount_adult_price;

                            $cs_inner_l_count ++;
                        }
                    }
                    $cs_loop_count ++;
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

            $cs_rent_total_hrs ++;
        }

//        for ($i = $start_date; $i <= $end_date; $i = $i + 86400) {
//            
//        }

        $cs_type_data = get_option( 'cs_type_options' );
        $vehicles_type = isset( $cs_type_data[$vehicle_type]['cs_type_name'] ) ? $cs_type_data[$vehicle_type]['cs_type_name'] : '';

        $json['selected_vehicle'] .= '<span data-price="' . $price['total_price'] . '">';

        if ( ! empty( $vehicles_type ) ) {
            $json['selected_vehicle'] .= '<div class="premium-heading"><span>' . $vehicles_type . '</span></div>';
        }

        $json['selected_vehicle'] .= '<h4>' . get_the_title( $post_id ) . ' #' . $vehicle_id . '</h4>';

        if ( $flag == true ) {
            $json['selected_vehicle'] .= '<div class="price-bar"><span>' . __( 'Price', 'rental' ) . '</span><em class="new">' . $currency_sign . number_format( (float) $price['total_price'], 2 ) . '</em><em class="old">' . $currency_sign . number_format( (float) $total_orignal, 2 ) . '</em> </div>';
        } else {
            $json['selected_vehicle'] .= '<div class="price-bar"><span>' . __( 'Price', 'rental' ) . '</span><em class="new">' . $currency_sign . number_format( (float) $price['total_price'], 2 ) . '</em></div>';
        }

        $json['selected_vehicle'] .= $price_breakdown;
        $json['selected_vehicle'] .= '</span>';
        $json['total_price'] = $currency_sign . number_format( (float) $price['total_price'], 2 );

        $cs_payment_vat = isset( $cs_plugin_options['cs_payment_vat'] ) && $cs_plugin_options['cs_payment_vat'] != '' ? $cs_plugin_options['cs_payment_vat'] : '0';
        $cs_vat_switch = isset( $cs_plugin_options['cs_vat_switch'] ) && $cs_plugin_options['cs_vat_switch'] == 'on' ? $cs_plugin_options['cs_vat_switch'] : 'off';

        if ( $cs_vat_switch == 'on' ) {
            $vat = number_format( (float) ( $price['total_price'] / 100 ) * $cs_payment_vat, 2 );
        } else {
            $vat = 0;
        }

        $gross_total = $price['total_price'];
        $grand_total = number_format( (float) $price['total_price'] + $vat, 2 );

        $json['grand_total'] .= $grand_total;
        $json['vat_price'] .= $currency_sign . number_format( (float) $vat, 2 );

        $vehicles_array = array();
        $vehicles_array[strtolower( $vehicle_id )]['key'] = $vehicle_id;
        $vehicles_array[strtolower( $vehicle_id )]['vehicle_id'] = $vehicle_id;
        $vehicles_array[strtolower( $vehicle_id )]['booked_vehicle_id'] = $post_id;
        $vehicles_array[strtolower( $vehicle_id )]['vehicle_type'] = $vehicle_type;
        $vehicles_array[strtolower( $vehicle_id )]['price'] = $price['total_price'];
        $vehicles_array[strtolower( $vehicle_id )]['orignal_price'] = $total_orignal;
        $vehicles_array[strtolower( $vehicle_id )]['discount'] = $offer_discount;


        $_SESSION['reserved_vehicles'] = $vehicles_array;

        $json['selection_done'] .= cs_booking_detail();
        $json['status'] = 'completed';

        echo json_encode( $json );
        die;
    }

    add_action( 'wp_ajax_cs_check_availabilty', 'cs_check_availabilty' );
    add_action( 'wp_ajax_nopriv_cs_check_availabilty', 'cs_check_availabilty' );
}



/**
 *
 * @Add Booking
 *
 */
if ( ! function_exists( 'cs_add_booking' ) ) {

    function cs_add_booking() {
        global $post, $cs_plugin_options, $gateways;

        $json = array();
        $f_name = $_REQUEST['cs_f_name'];
        $l_name = $_REQUEST['cs_l_name'];
        $phone_no = $_REQUEST['cs_phone_no'];
        $email = $_REQUEST['cs_email'];
        $country = $_REQUEST['cs_country'];
        $address = $_REQUEST['cs_address'];
        $city = $_REQUEST['cs_city'];

        $cs_date = $_REQUEST['cs_date'];
        $cs_adult_price = $_REQUEST['cs_adult_price'];

        if ( isset( $_REQUEST['cs_extras_price'] ) ) {
            $cs_extras_price = $_REQUEST['cs_extras_price'];
        } else {
            $cs_extras_price = array();
        }

        $cs_payment_gateway = $_REQUEST['cs_payment_gateway'];
        $gross_price = $_REQUEST['gross_price'];
        $vat_price = $_REQUEST['vat_price'];
        $grand_total = $_REQUEST['grand_total'];
        $gateway = $_REQUEST['gateway'];
        $payment_type = $_REQUEST['payment_type'];

        $gateway_name = '';
        if ( isset( $_REQUEST['cs_payment_gateway'] ) ) {
            $gateway_name = $gateways[strtoupper( $_REQUEST['cs_payment_gateway'] )];
        }

        $vat_price = $vat_price;

        $cs_payment_vat = isset( $cs_plugin_options['cs_payment_vat'] ) && $cs_plugin_options['cs_payment_vat'] != '' ? $cs_plugin_options['cs_payment_vat'] : '0';

        if ( isset( $_REQUEST['cs_booking_extras'] ) ) {
            $cs_booking_extras = $_REQUEST['cs_booking_extras'];
        } else {
            $cs_booking_extras = array();
        }

        if ( isset( $_REQUEST['cs_days'] ) ) {
            $cs_days = $_REQUEST['cs_days'];
        } else {
            $cs_days = array();
        }

        if ( isset( $_REQUEST['cs_guests'] ) ) {
            $cs_guests = $_REQUEST['cs_guests'];
        } else {
            $cs_guests = array();
        }


        if ( $f_name == '' || $f_name == '' || $email == '' ) {
            $json['type'] = 'error';
            $json['message'] = __( 'Please fill required fileds', 'rental' );
        } else {
            $json['gateway'] = 'pay';
            $json['type'] = 'success';
            $json['message'] = __( 'Booking Processing....', 'rental' );
            $booking_id = 'RT-' . CS_FUNCTIONS()->cs_generate_random_string( 5 );

            $reserved_vehicles = $_SESSION['reserved_vehicles'];
            $cs_booked_vehicles = '';
            $booked_vehicle_id = '';
            foreach ( $reserved_vehicles as $key => $value ) {
                $cs_booked_vehicles = $key;
                $booked_vehicle_id = $value['booked_vehicle_id'];
                break;
            }

            $post_title = $booking_id;

            $booking_post = array(
                'post_title' => $post_title,
                'post_content' => '',
                'post_status' => 'publish',
                'post_author' => '',
                'post_type' => 'booking',
                'post_date' => current_time( 'Y-m-d h:i:s' )
            );

            $post_id = wp_insert_post( $booking_post );

            if ( isset( $post_id ) ) {

                // Add Customer
                $cs_cstmr_data = get_option( "cs_customer_options" );
                $customer_id = CS_FUNCTIONS()->cs_generate_random_string( 10 );
                $cs_new_customer = array();

                $cs_customer = get_option( 'cs_customer_options' );

                if ( isset( $cs_customer ) && ! is_array( $cs_customer ) && empty( $cs_customer ) ) {
                    $cs_customer = array();
                }

                $cs_new_customer[$customer_id]['cus_id'] = $customer_id;
                $cs_new_customer[$customer_id]['cus_name'] = '';
                $cs_new_customer[$customer_id]['cus_f_name'] = $f_name;
                $cs_new_customer[$customer_id]['cus_l_name'] = $l_name;
                $cs_new_customer[$customer_id]['cus_email'] = $email;
                $cs_new_customer[$customer_id]['cus_phone_no'] = $phone_no;
                $cs_new_customer[$customer_id]['cus_address'] = $address;
                $cs_new_customer[$customer_id]['cus_city'] = $city;
                $cs_new_customer[$customer_id]['cus_country'] = $country;

                $cs_all_customer = array_merge( $cs_customer, $cs_new_customer );
                update_option( 'cs_customer_options', $cs_all_customer );

                //Calculate Prices
                $pricings = get_option( 'cs_price_options' );
                $price['total_price'] = $gross_price;
                $total_days = 0;

                $check_in_date = $_SESSION['cs_reservation']['start_date'];
                $check_out_date = $_SESSION['cs_reservation']['end_date'];

                $start_time = $_SESSION['cs_reservation']['start_time'];
                $end_time = $_SESSION['cs_reservation']['end_time'];
                $station = $_SESSION['cs_reservation']['station'];
                $pickup_location = $_SESSION['cs_reservation']['pickup_location'];
                $dropup_location = $_SESSION['cs_reservation']['dropup_location'];

                $start_date_time = $check_in_date . ' ' . $start_time;
                $end_date_time = $check_out_date . ' ' . $end_time;

                $datetime1 = date_create( $check_in_date );
                $datetime2 = date_create( $check_out_date );
                $interval = date_diff( $datetime1, $datetime2 );
                $total_days = $interval->days;

                $currency_sign = isset( $cs_plugin_options['currency_sign'] ) && $cs_plugin_options['currency_sign'] != '' ? $cs_plugin_options['currency_sign'] : '$';
                $cs_payment_vat = isset( $cs_plugin_options['cs_payment_vat'] ) && $cs_plugin_options['cs_payment_vat'] != '' ? $cs_plugin_options['cs_payment_vat'] : '0';
                $cs_vat_switch = isset( $cs_plugin_options['cs_vat_switch'] ) && $cs_plugin_options['cs_vat_switch'] == 'on' ? $cs_plugin_options['cs_vat_switch'] : 'off';

                $full_pay = isset( $cs_plugin_options['cs_allow_full_pay'] ) && $cs_plugin_options['cs_allow_full_pay'] == 'on' ? $cs_plugin_options['cs_allow_full_pay'] : 'off';
                $cs_advance_deposit = isset( $cs_plugin_options['cs_advnce_deposit'] ) && $cs_plugin_options['cs_advnce_deposit'] != '' ? $cs_plugin_options['cs_advnce_deposit'] : '100';

                if ( $cs_vat_switch == 'on' ) {
                    $vat = number_format( (float) ( $price['total_price'] / 100 ) * $cs_payment_vat, 2 );
                } else {
                    $vat = 0;
                }

                $gross_total = $grand_total;
                $grand_total = $grand_total;

                // Advance and Remainings if Transactions exist
                $transaction_amount = 0;

                $advance = number_format( (float) $transaction_amount, 2 );
                $remaining = number_format( (float) $grand_total - $transaction_amount, 2 );

                $advance_total = $grand_total;
                $due_total = $grand_total;

                if ( $payment_type == 'deposit' && $cs_advance_deposit != '' ) {
                    $advance_total = ( $grand_total / 100 ) * $cs_advance_deposit;
                    $due_total = $grand_total - $advance_total;
                }

                $advance_total = $grand_total;
                $due_total = $grand_total;

                if ( $payment_type == 'deposit' && $cs_advance_deposit != '' ) {
                    $advance_total = ( $grand_total / 100 ) * $cs_advance_deposit;
                    $due_total = $grand_total - $advance_total;
                }

                $session_data = isset( $_SESSION['cs_reservation'] ) ? $_SESSION['cs_reservation'] : array();
                $cs_vehicle_type = $session_data['vehicle_type'];

                //Update Booking
                update_post_meta( $post_id, 'cs_vehicle_type', $cs_vehicle_type );
                update_post_meta( $post_id, 'cs_booking_id', $booking_id );
                update_post_meta( $post_id, 'cs_booking_num_days', $total_days );
                update_post_meta( $post_id, 'cs_bkng_grand_total', $grand_total );
                update_post_meta( $post_id, 'cs_bkng_advance', $advance_total );
                update_post_meta( $post_id, 'cs_bkng_remaining', $due_total );
                update_post_meta( $post_id, 'cs_bkng_gross_total', $price['total_price'] );
                update_post_meta( $post_id, 'cs_check_in_date', strtotime( $start_date_time ) );
                update_post_meta( $post_id, 'cs_check_out_date', strtotime( $end_date_time ) );

                update_post_meta( $post_id, 'start_date_time', strtotime( $start_date_time ) );
                update_post_meta( $post_id, 'end_date_time', strtotime( $end_date_time ) );
                update_post_meta( $post_id, 'cs_pickup_time', $start_time );
                update_post_meta( $post_id, 'cs_dropup_time', $end_time );
                update_post_meta( $post_id, 'cs_station', $station );
                update_post_meta( $post_id, 'cs_pickup_location', $pickup_location );
                update_post_meta( $post_id, 'cs_dropup_location', $dropup_location );

                update_post_meta( $post_id, 'cs_booking_status', 'pending' );
                update_post_meta( $post_id, 'cs_select_guest', $customer_id );
                update_post_meta( $post_id, 'cs_invoice', $post_id );
                update_post_meta( $post_id, 'cs_bkng_tax', $vat_price );
                update_post_meta( $post_id, 'cs_bkng_vat_percentage', $cs_payment_vat );
                update_post_meta( $post_id, 'cs_payment_type', $payment_type );

                update_post_meta( $post_id, 'cs_booked_vehicle_data', $reserved_vehicles );
                update_post_meta( $post_id, 'cs_booked_vehicle', $cs_booked_vehicles );
                update_post_meta( $post_id, 'cs_booked_vehicle_id', $booked_vehicle_id );
                update_post_meta( $post_id, 'cs_gateway', $gateway_name );


                foreach ( $_POST as $key => $value ) {
                    if ( strstr( $key, 'cs_' ) ) {
                        update_post_meta( $post_id, $key, $value );
                    }
                }

                //Gateway
                $gateway = isset( $_REQUEST['cs_payment_gateway'] ) ? $_REQUEST['cs_payment_gateway'] : '';

                $cs_transactions_data = array();
                $cs_transactions_data['order_id'] = $post_id;
                $cs_transactions_data['price'] = $advance_total;
                $cs_transactions_data['item_name'] = $post_title;
                $cs_transactions_data['cs_invoice'] = $post_id;
                $cs_transactions_data['cs_booking_id'] = $booking_id;
                $cs_transactions_data['cs_grand_total'] = $grand_total;
                $cs_transactions_data['remaining'] = $due_total;

                $_SESSION['cs_session_booked_id'] = $post_id;

                if ( class_exists( 'CS_PAYMENTS' ) ) {
                    if ( isset( $gateway ) && $gateway == 'cs_paypal_gateway' ) {
                        $paypal_gateway = new CS_PAYPAL_GATEWAY();
                        $json['form'] = $paypal_gateway->cs_proress_request( $cs_transactions_data );
                    } else if ( isset( $gateway ) && $gateway == 'cs_authorizedotnet_gateway' ) {
                        $authorizedotnet = new CS_AUTHORIZEDOTNET_GATEWAY();
                        $json['form'] = $authorizedotnet->cs_proress_request( $cs_transactions_data );
                    } else if ( isset( $gateway ) && $gateway == 'cs_skrill_gateway' ) {
                        $skrill = new CS_SKRILL_GATEWAY();
                        $json['form'] = $skrill->cs_proress_request( $cs_transactions_data );
                    } else if ( isset( $gateway ) && $gateway == 'cs_pre_bank_transfer' ) {
                        $banktransfer = new CS_PRE_BANK_TRANSFER();
                        $json['gateway'] = 'transfer';
                        $json['form'] = $banktransfer->cs_proress_request( $cs_transactions_data );
                    }
                }
            }
        }

        echo json_encode( $json );
        die;
    }

    add_action( 'wp_ajax_cs_add_booking', 'cs_add_booking' );
    add_action( 'wp_ajax_nopriv_cs_add_booking', 'cs_add_booking' );
}

/**
 *
 * @Get Booking HTML
 *
 */
if ( ! function_exists( 'cs_booking_detail' ) ) {

    function cs_booking_detail() {
        global $post, $cs_notification, $cs_plugin_options;

        $json = array();
        $json['reservation_detail'] = '';

        $session_data = isset( $_SESSION['cs_reservation'] ) ? $_SESSION['cs_reservation'] : array();
        $date_from = $session_data['start_date'];
        $date_to = $session_data['end_date'];
        $reserved_vehicles = $_SESSION['reserved_vehicles'];

        $price['total_price'] = 0.00;

        foreach ( $reserved_vehicles as $key => $value ) {
            $ses_key = $value['key'];
            $ses_vehicle_type = $value['vehicle_type'];
            $ses_vehicle_id = $value['vehicle_id'];
            $price['total_price'] = number_format( (float) $value['price'], 2 );
        }

        $cs_page_id = isset( $cs_plugin_options['cs_reservation'] ) && $cs_plugin_options['cs_reservation'] != '' && absint( $cs_plugin_options['cs_reservation'] ) ? $cs_plugin_options['cs_reservation'] : '';

        $search_link = add_query_arg( array( 'action' => 'booking' ), esc_url( get_permalink( $cs_page_id ) ) );

        $currency_sign = isset( $cs_plugin_options['currency_sign'] ) && $cs_plugin_options['currency_sign'] != '' ? $cs_plugin_options['currency_sign'] : '$';
        $cs_payment_vat = isset( $cs_plugin_options['cs_payment_vat'] ) && $cs_plugin_options['cs_payment_vat'] != '' ? $cs_plugin_options['cs_payment_vat'] : '0';
        $cs_vat_switch = isset( $cs_plugin_options['cs_vat_switch'] ) && $cs_plugin_options['cs_vat_switch'] == 'on' ? $cs_plugin_options['cs_vat_switch'] : 'off';

        $full_pay = isset( $cs_plugin_options['cs_allow_full_pay'] ) && $cs_plugin_options['cs_allow_full_pay'] == 'on' ? $cs_plugin_options['cs_allow_full_pay'] : 'off';
        $cs_advance_deposit = isset( $cs_plugin_options['cs_advnce_deposit'] ) && $cs_plugin_options['cs_advnce_deposit'] != '' ? $cs_plugin_options['cs_advnce_deposit'] : '';

        $datetime1 = date_create( $date_from );
        $datetime2 = date_create( $date_to );

        $interval = date_diff( $datetime1, $datetime2 );
        $total_days = $interval->days;

        ob_start();


        $json['reservation_detail'] .= '<div class="cs-booking">';
        $json['reservation_detail'] .= '<div class="col-md-12 tab-content">';
        $json['reservation_detail'] .= '<div class="tabs" id="tab1" style="display: block;">';
        $json['reservation_detail'] .= '<script>jQuery(document).ready(function() { cs_gross_calculation(); });</script>';

        $json['reservation_detail'] .= cs_booking_extras(
                array( 'name' => __( 'Extras', 'rental' ),
                    'id' => 'booking_extras',
                    'classes' => '',
                    'guests' => '',
                    'days' => $total_days,
                    'post_id' => '',
                )
        );

        $json['reservation_detail'] .= '<div class="button_style cs-process-wrap"> <a href="javascript:;" class="continue-btn btn-step btnNext">' . __( 'Continue', 'rental' ) . '</a> </div>';
        $json['reservation_detail'] .= '</div>';

        if ( $cs_vat_switch == 'on' ) {
            $vat = number_format( (float) ( $price['total_price'] / 100 ) * $cs_payment_vat, 2 );
        } else {
            $vat = 0;
        }

        $gross_total = $price['total_price'];
        $grand_total = number_format( (float) $price['total_price'] + $vat, 2 );

        // Advance and Remainings if Transactions exist
        $transaction_amount = 0;

        $advance = number_format( (float) $transaction_amount, 2 );
        $remaining = number_format( (float) $grand_total - $transaction_amount, 2 );

        $advance_total = $grand_total;
        $due_total = 0;

        if ( $cs_advance_deposit != '' ) {
            $advance_total = ( $grand_total / 100 ) * $cs_advance_deposit;
            $due_total = $grand_total - $advance_total;
        }


        // User Information
        $json['reservation_detail'] .= '<div class="tabs" id="tab2" style="display: none;">';
        $json['reservation_detail'] .= '<div class="booking-step"><div class="booking-holder"><div class="top-step">';
        $json['reservation_detail'] .= '<h4>';
        $json['reservation_detail'] .= __( 'Your Detail', 'rental' );
        $json['reservation_detail'] .= '</h4>';
        $json['reservation_detail'] .= '<ul class="cs-element-list">';
        $json['reservation_detail'] .= '<li>';
        $json['reservation_detail'] .= '<div class="fields-area col-md-6">';
        $json['reservation_detail'] .= '<div class="field-col">';
        $json['reservation_detail'] .= '<label>' . __( 'First Name *', 'rental' ) . '</label>';
        $json['reservation_detail'] .= '<input type="text" id="cs_f_name" name="cs_f_name" value="" placeholder="' . __( 'First Name *', 'rental' ) . '">';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';

        $json['reservation_detail'] .= '<div class="fields-area col-md-6">';
        $json['reservation_detail'] .= '<div class="field-col">';
        $json['reservation_detail'] .= '<label>' . __( 'Last Name *', 'rental' ) . '</label>';
        $json['reservation_detail'] .= '<input type="text" id="cs_l_name" name="cs_l_name" value="" placeholder="' . __( 'Last Name *', 'rental' ) . '">';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';

        $json['reservation_detail'] .= '<div class="fields-area col-md-6">';
        $json['reservation_detail'] .= '<div class="field-col">';
        $json['reservation_detail'] .= '<label>' . __( 'Email *', 'rental' ) . '</label>';
        $json['reservation_detail'] .= '<input type="text"  id="cs_email" name="cs_email" placeholder="' . __( 'Email *', 'rental' ) . '" />';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';

        $json['reservation_detail'] .= '<div class="fields-area col-md-6">';
        $json['reservation_detail'] .= '<div class="field-col">';
        $json['reservation_detail'] .= '<label>' . __( 'Telephone Number', 'rental' ) . '</label>';
        $json['reservation_detail'] .= '<input type="text"  id="cs_phone_no" name="cs_phone_no" value="" placeholder="' . __( 'Telephone Number', 'rental' ) . '" />';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';


        $json['reservation_detail'] .= '<div class="fields-area col-md-6">';
        $json['reservation_detail'] .= '<div class="field-col">';
        $json['reservation_detail'] .= '<label>' . __( 'City', 'rental' ) . '</label>';
        $json['reservation_detail'] .= '<input type="text"  id="cs_city" name="cs_city" placeholder="' . __( 'City', 'rental' ) . '" />';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';

        $json['reservation_detail'] .= '<div class="fields-area col-md-6">';
        $json['reservation_detail'] .= '<div class="field-col">';
        $json['reservation_detail'] .= '<label>' . __( 'Country', 'rental' ) . '</label>';
        $json['reservation_detail'] .= '<div class="search-content">';
        $json['reservation_detail'] .= '<div class="select-booking">';
        $json['reservation_detail'] .= '<select name="cs_country" id="cs_country">';

        $countries = cs_get_countries();
        foreach ( $countries as $value ) {
            $json['reservation_detail'] .= '<option value="' . $value . '">' . $value . '</option>';
        }

        $json['reservation_detail'] .= '</select>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';

        $json['reservation_detail'] .= '<div class="fields-area col-md-12">';
        $json['reservation_detail'] .= '<div class="field-col">';
        $json['reservation_detail'] .= '<label>' . __( 'Address', 'rental' ) . '</label>';
        $json['reservation_detail'] .= '<input type="text"  id="cs_address" name="cs_address" placeholder="' . __( 'Address', 'rental' ) . '" />';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';

        $json['reservation_detail'] .= '</div>';

        //Payments

        if ( $full_pay == 'off' && $cs_advance_deposit != '' ) {
            $advance_total = ( $grand_total / 100 ) * $cs_advance_deposit;
        }

        if ( isset( $full_pay ) && $full_pay == 'on' ) {
            $selected_depost = '';
            $display_deposit = 'style="display:none"';
        } else {
            $selected_depost = '';
        }

        $json['reservation_detail'] .= '<div class="btm-step">';
        $json['reservation_detail'] .= '<ul class="cs-element-list">';
        $json['reservation_detail'] .= '<li>';
        $json['reservation_detail'] .= '<div class="fields-area col-md-12">';
        $json['reservation_detail'] .= '<div class="booking-way">';

        if ( isset( $cs_advance_deposit ) && trim( $cs_advance_deposit ) != '' ) {
            $json['reservation_detail'] .= '<div class="booking-left">';
            $json['reservation_detail'] .= '<div class="booking-radio-box">';
            $json['reservation_detail'] .= '<input type="radio" ' . $selected_depost . ' class="cs-set-pay-type" name="payment_type" id="payment_type_deposit" value="deposit">';
            $json['reservation_detail'] .= '<label for="payment_type_deposit"></label>';
            $json['reservation_detail'] .= '</div>';
            $json['reservation_detail'] .= '<div class="booking-heading">';
            $json['reservation_detail'] .= '<h4>' . __( 'Deposit Amount', 'rental' ) . '</h4>';
            $json['reservation_detail'] .= '<p>' . __( 'Pay the rest on arrival', 'rental' ) . '</p>';
            $json['reservation_detail'] .= '</div>';
            $json['reservation_detail'] .= '<div class="booking-price"><span><span class="cs-deposit-amount">' . $currency_sign . $advance_total . '</span><em>(' . $cs_advance_deposit . '%)</em></span></div>';
            $json['reservation_detail'] .= '</div>';
        }




        $json['reservation_detail'] .= '<div class="booking-left">';
        $json['reservation_detail'] .= '<div class="booking-radio-box">';

        if ( isset( $full_pay ) && $full_pay == 'on' ) {
            $json['reservation_detail'] .= '<input checked="checked" class="cs-set-pay-type" type="radio" name="payment_type" id="payment_type_full" value="full">';
            $json['reservation_detail'] .= '<label for="payment_type_full"></label>';
        }

        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '<div class="booking-heading">';
        $json['reservation_detail'] .= '<h4>' . __( 'Total Amount', 'rental' ) . '</h4>';
        $json['reservation_detail'] .= '<p>' . __( 'Including VAT', 'rental' ) . ' (' . $cs_payment_vat . '%)</p>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '<div class="booking-price"><span class="cs-booking-grand-total">' . $currency_sign . $grand_total . '</span></div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</li>';
        $json['reservation_detail'] .= '<li>';
        $json['reservation_detail'] .= '<div class="fields-area col-md-12">';
        $json['reservation_detail'] .= '<div class="make-payment">';
        $json['reservation_detail'] .= '<h3>' . __( 'Select Payment Method', 'rental' ) . '</h3>';
        $json['reservation_detail'] .= '<div class="partner-box">';
        $json['reservation_detail'] .= '<ul class="partner-list">';

        global $gateways;
        $object = new CS_PAYMENTS();
        $cs_gw_counter = 1;
        foreach ( $gateways as $key => $value ) {

            $selected = '';
            if ( $key == 'CS_PAYPAL_GATEWAY' ) {
                $selected = 'checked="checked"';
            }

            $status = $cs_plugin_options[strtolower( $key ) . '_status'];

            if ( isset( $status ) && $status == 'on' ) {
                $logo = '';

                if ( isset( $cs_plugin_options[strtolower( $key ) . '_logo'] ) ) {
                    $logo = $cs_plugin_options[strtolower( $key ) . '_logo'];
                }



                $json['reservation_detail'] .= '<li>';
                $json['reservation_detail'] .= '<div class="partner-select">';
                $json['reservation_detail'] .= '<div class="booking-radio-box">';
                $json['reservation_detail'] .= '<input class="cs-gateway-calculation" type="radio" ' . $selected . '  name="cs_payment_gateway" value="' . strtolower( $key ) . '" id="' . strtolower( $key ) . '" />';
                $json['reservation_detail'] .= '<label for="' . strtolower( $key ) . '">' . $value . '</label>';
                $json['reservation_detail'] .= '</div>';

                if ( isset( $logo ) && $logo != '' ) {
                    $json['reservation_detail'] .= '<img src="' . esc_url( $logo ) . '" alt="" /> ';
                }

                $json['reservation_detail'] .= '</div>';
                $json['reservation_detail'] .= '</li>';
            }
        }

        $json['reservation_detail'] .= '</ul>';
        $json['reservation_detail'] .= '</div';
        $json['reservation_detail'] .= '</li>';

        $json['reservation_detail'] .= '<div class="button_style cs-process-wrap"> <a href="javascript:;" class="continue-btn btnNext btn-step">' . __( 'Pay Now', 'rental' ) . '</a> </div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';

        //Confirmation
        $json['reservation_detail'] .= '<div class="tabs" id="tab3" style="display: none;">';
        $json['reservation_detail'] .= '<div class="booking-step">';
        $json['reservation_detail'] .= '<div class="cs-confirmed">';
        $json['reservation_detail'] .= '<ul class="cs-element-list">';
        $json['reservation_detail'] .= '<li>';
        $json['reservation_detail'] .= '<div class="fields-area col-md-12">';
        $json['reservation_detail'] .= '<div class="confirmd-msg"><i><img src="' . esc_url( wp_car_rental::plugin_url() . '/assets/images/confirmed.png' ) . '" alt=""></i>';

        if ( $cs_plugin_options['cs_thank_title'] && $cs_plugin_options['cs_thank_title'] != '' ) {
            $json['reservation_detail'] .= '<h5>' . __( $cs_plugin_options['cs_thank_title'], 'rental' ) . '</h5>';
        }

        if ( $cs_plugin_options['cs_thank_msg'] && $cs_plugin_options['cs_thank_msg'] != '' ) {
            $json['reservation_detail'] .= '<span class="col-md-10">' . __( $cs_plugin_options['cs_thank_msg'], 'rental' ) . '</span>';
        }
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</li>';
        $json['reservation_detail'] .= '<li>';
        $json['reservation_detail'] .= '<div class="fields-area col-md-10">';
        $json['reservation_detail'] .= '<div class="contact-area">';
        $json['reservation_detail'] .= '<p>' . __( 'For Cancellation or more information Please Contact us', 'rental' ) . '</p>';
        $json['reservation_detail'] .= '<ul class="contact-list">';

        if ( $cs_plugin_options['cs_confir_phone'] && $cs_plugin_options['cs_confir_phone'] != '' ) {
            $json['reservation_detail'] .= '<li><i class="icon-mobile-phone"></i>' . __( 'Phone: ', 'rental' ) . __( $cs_plugin_options['cs_confir_phone'], 'rental' ) . '</li>';
        }

        if ( $cs_plugin_options['cs_confir_fax'] && $cs_plugin_options['cs_confir_fax'] != '' ) {
            $json['reservation_detail'] .= '<li><i class="icon-printer4"></i>' . __( 'Fax :', 'rental' ) . __( $cs_plugin_options['cs_confir_fax'], 'rental' ) . '</li>';
        }

        if ( $cs_plugin_options['cs_confir_email'] && $cs_plugin_options['cs_confir_email'] != '' ) {
            $json['reservation_detail'] .= '<li><i class="icon-mail6"></i>' . __( 'Email: ', 'rental' ) . ' <a href="mailto:' . $cs_plugin_options['cs_confir_email'] . '&subject=hello">' . $cs_plugin_options['cs_confir_email'] . '</a></li>';
        }

        $json['reservation_detail'] .= '</ul>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</li>';
        $json['reservation_detail'] .= '</ul>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';

        $json['reservation_detail'] .= '</div>';

        return $json['reservation_detail'];
        die;
    }

    add_action( 'wp_ajax_cs_booking_detail', 'cs_booking_detail' );
    add_action( 'wp_ajax_nopriv_cs_booking_detail', 'cs_booking_detail' );
}

/**
 *
 * @Countries Array
 *
 */
if ( ! function_exists( 'cs_get_countries' ) ) {

    function cs_get_countries() {
        $get_countries = array( "Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan",
            "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "British Virgin Islands",
            "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China",
            "Colombia", "Comoros", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Democratic People's Republic of Korea", "Democratic Republic of the Congo", "Denmark", "Djibouti",
            "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "England", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Fiji", "Finland", "France", "French Polynesia",
            "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea Bissau", "Guyana", "Haiti", "Honduras", "Hong Kong",
            "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kosovo", "Kuwait", "Kyrgyzstan",
            "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia",
            "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique",
            "Myanmar(Burma)", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Northern Ireland",
            "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Puerto Rico",
            "Qatar", "Republic of the Congo", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa",
            "San Marino", "Saudi Arabia", "Scotland", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa",
            "South Korea", "Spain", "Sri Lanka", "Sudan", "Suriname", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga",
            "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "US Virgin Islands", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay",
            "Uzbekistan", "Vanuatu", "Vatican", "Venezuela", "Vietnam", "Wales", "Yemen", "Zambia", "Zimbabwe" );
        return $get_countries;
    }

}

/**
 *
 * @Email Subject Wordpress To Custom
 *
 */
add_filter( 'wp_mail_from_name', 'cs_wp_mail_from_name' );

function cs_wp_mail_from_name( $original_email_from ) {
    return get_bloginfo( 'name' );
}

/**
 *
 * @Pricing Breakdown
 *
 */
function cs_get_pricing_breakdown( $vehicle_id = '', $return_type = 'price' ) {
    global $post, $cs_plugin_options;

    $cs_charge_base = isset( $cs_plugin_options['cs_charge_base'] ) ? $cs_plugin_options['cs_charge_base'] : '';

    $session_data = isset( $_SESSION['cs_reservation'] ) ? $_SESSION['cs_reservation'] : '';
    $currency_sign = isset( $cs_plugin_options['currency_sign'] ) && $cs_plugin_options['currency_sign'] != '' ? $cs_plugin_options['currency_sign'] : '$';
    $date_from = $session_data['start_date'];
    $date_to = $session_data['end_date'];
    $start_time = $session_data['start_time'];
    $end_time = $session_data['end_time'];

    if ( date( "i", strtotime( '+0 minutes', strtotime( $start_time ) ) ) > 0 ) {
        $minus_time = 60 - date( "i", strtotime( '+0 minutes', strtotime( $start_time ) ) );
        $start_time = date( "H:i", strtotime( '-' . $minus_time . ' minutes', strtotime( $start_time ) ) );
    }

    if ( date( "i", strtotime( '+0 minutes', strtotime( $end_time ) ) ) > 0 ) {
        $plus_time = 60 - date( "i", strtotime( '+0 minutes', strtotime( $end_time ) ) );
        $end_time = date( "H:i", strtotime( '+' . $plus_time . ' minutes', strtotime( $end_time ) ) );
    }
    $_SESSION['cs_reservation']['start_time'] = $start_time;
    $_SESSION['cs_reservation']['end_time'] = $end_time;

    $start_date = strtotime( $date_from );
    $end_date = strtotime( $date_to );

    // start to end dates with time
    if ( $cs_charge_base == 'hourly' ) {
        $cs_start_date_time = strtotime( $date_from . ' ' . $start_time );
        $cs_end_date_time = strtotime( $date_to . ' ' . $end_time );
    } else {
        $cs_start_date_time = strtotime( $date_from . ' ' . $start_time );
        $cs_end_date_time = strtotime( $date_to . ' ' . $start_time );
    }

    // Loop between timestamps, 24 hours at a time
    $total_price = '';
    $adult_price = 0;

    $pricings = get_option( 'cs_price_options' );
    $cs_offers_options = get_option( "cs_offers_options" );

    $pricings_array = isset( $pricings[$vehicle_id] ) ? $pricings[$vehicle_id] : '';

    if ( isset( $pricings[$vehicle_id]['cs_plan_days'] ) ) {
        $cs_sp_days = $pricings[$vehicle_id]['cs_plan_days'];
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

    $cs_counter_plus_plus = 86400;
    if ( $cs_charge_base == 'hourly' ) {
        $cs_counter_plus_plus = 3600;

        if ( $cs_end_date_time > 3600 ) {
            $cs_end_date_time = (int) $cs_end_date_time - 3600;
        }
    }

    $cs_this_new_date = 0;
    $cs_hourly_pr_count = 1;
    $cs_g_total = 0;
    $total_g_temp = 0;
    for ( $i = $cs_start_date_time; $i <= $cs_end_date_time; $i = $i + $cs_counter_plus_plus ) {

        $total_days ++;
        $brk_counter ++;
        $thisDate = date( 'Y-m-d', $i ); // 2016-05-01, 2016-05-02, etc
        $cs_this_date = strtotime( date( 'Y-m-d', $i ) );
        $day = strtolower( date( 'D', strtotime( $thisDate ) ) );

        $adult_price = '';
        if ( isset( $pricings_array['cs_pricing_branches']['adult_' . $day . '_price'][0] ) and is_array( $pricings_array ) and $pricings_array <> '' ) {
            $adult_price = $pricings_array['cs_pricing_branches']['adult_' . $day . '_price'][0];
        }

        $adult_temp_price = $adult_price != '' ? $adult_price : 0;
        $adult_price = $adult_temp_price;
        $to_check_date = strtotime( date( 'Y-m-d', $i ) );

        // Special Prices Calculations
        if ( isset( $cs_sp_days['start_date'][0] ) && $cs_sp_days['start_date'][0] != '' ) {
            foreach ( $cs_sp_days['start_date'] as $key => $sp_price_date ) {
                $sp_start_date = $cs_sp_days['start_date'][$key];
                $sp_end_date = $cs_sp_days['end_date'][$key];

                $sp_start_date = date( 'Y-m-d', strtotime( $sp_start_date ) );
                $sp_end_date = date( 'Y-m-d', strtotime( $sp_end_date ) );

                if ( isset( $sp_start_date ) && isset( $sp_end_date ) ) {
                    $sp_start_date = strtotime( $sp_start_date );
                    $sp_end_date = strtotime( $sp_end_date );

                    if ( $to_check_date >= $sp_start_date && $to_check_date <= $sp_end_date ) {

                        if ( isset( $pricings[$vehicle_id]['cs_plan_prices'] ) ) {
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
        if ( isset( $cs_offers_options ) && ! empty( $cs_offers_options ) ) {
            foreach ( $cs_offers_options as $key => $offer_data ) {

                $offer_start_date = date( 'Y-m-d', strtotime( $offer_data['start_date'] ) );
                $offer_end_date = date( 'Y-m-d', strtotime( $offer_data['end_date'] ) );

                if ( isset( $offer_start_date ) && isset( $offer_end_date ) ) {
                    $offer_start_date = strtotime( $offer_start_date );
                    $offer_end_date = strtotime( $offer_end_date );

                    if ( $to_check_date >= $offer_start_date && $to_check_date <= $offer_end_date ) {
                        $offer_discount = $offer_data['discount'];
                        if ( isset( $cs_booking_days ) && $cs_booking_days <= $offer_data['min_days'] ) {
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
        if ( $cs_charge_base == 'hourly' ) {

            if ( $cs_this_date == $cs_this_new_date || $cs_this_new_date == 0 ) {
                $cs_g_total += $cs_total;
                $total_g_temp += $total_temp;
            } else {
                if ( $flag == true && $total_temp != $cs_total ) {
                    $price_breakdown .= '<li><span>' . date_i18n( get_option( 'date_format' ), ( $cs_this_new_date ) ) . '</span><em>(' . $cs_total . ' x ' . $cs_hourly_pr_count . ') ' . $currency_sign . number_format( (float) $cs_g_total, 2 ) . '</em><em class="old">(' . $total_temp . ' x ' . $cs_hourly_pr_count . ') ' . $currency_sign . number_format( (float) $total_g_temp, 2 ) . '</em></li>';
                } else {
                    $price_breakdown .= '<li><span>' . date_i18n( get_option( 'date_format' ), ( $cs_this_new_date ) ) . '</span><em>(' . $cs_total . ' x ' . $cs_hourly_pr_count . ') ' . $currency_sign . number_format( (float) $cs_g_total, 2 ) . '</em></li>';
                }
                $cs_g_total = 0;
                $total_g_temp = 0;
                $cs_hourly_pr_count = 0;
            }
            if ( strtotime( date( 'Y-m-d H:i', $cs_end_date_time ) ) == strtotime( date( 'Y-m-d H:i', $i ) ) ) {
                if ( $flag == true && $total_temp != $cs_total ) {
                    $price_breakdown .= '<li><span>' . date_i18n( get_option( 'date_format' ), strtotime( $thisDate ) ) . '</span><em>(' . $cs_total . ' x ' . $cs_hourly_pr_count . ') ' . $currency_sign . number_format( (float) $cs_g_total, 2 ) . '</em><em class="old">(' . $total_temp . ' x ' . $cs_hourly_pr_count . ') ' . $currency_sign . number_format( (float) $total_g_temp, 2 ) . '</em></li>';
                } else {
                    $price_breakdown .= '<li><span>' . date_i18n( get_option( 'date_format' ), strtotime( $thisDate ) ) . '</span><em>(' . $cs_total . ' x ' . $cs_hourly_pr_count . ') ' . $currency_sign . number_format( (float) $cs_g_total, 2 ) . '</em></li>';
                }
            }

            $cs_this_new_date = strtotime( date( 'Y-m-d', $i ) );
            $cs_hourly_pr_count ++;
        } else {
            if ( $flag == true && $total_temp != $cs_total ) {
                $price_breakdown .= '<li><span>' . date_i18n( get_option( 'date_format' ), strtotime( $thisDate ) ) . '</span><em>' . $currency_sign . number_format( (float) $cs_total, 2 ) . '</em><em class="old">' . $currency_sign . number_format( (float) $total_temp, 2 ) . '</em></li>';
            } else {
                $price_breakdown .= '<li><span>' . date_i18n( get_option( 'date_format' ), strtotime( $thisDate ) ) . '</span><em>' . $currency_sign . number_format( (float) $cs_total, 2 ) . '</em></li>';
            }
        }
    }

    if ( $return_type == 'breakdown' ) {
        $output = '<a href="javascript:;" class="information"><i class="icon-info7"></i>';
        $output .= '<ul>';
        $output .= $price_breakdown;
        $output .= '</ul>';
        $output .= '</a>';
    } else {
        if ( $flag == true ) {
            $output .= '<em class="new-price">' . $currency_sign . number_format( (float) $price['total_price'], 2 ) . '</em><em class="old-price">' . $currency_sign . number_format( (float) $total_orignal, 2 ) . '</em>';
        } else {
            $output .= '<em class="new-price">' . $currency_sign . number_format( (float) $price['total_price'], 2 ) . '</em>';
            ;
        }
    }

    echo cs_allow_special_char( $output );
}

/**
 *
 * @Vehicle Detail
 *
 */
function cs_get_detail( $post_id = '', $price_flag = 'breakdown' ) {
    global $cs_plugin_options;
    $cs_gallery = get_post_meta( $post_id, "cs_vehicle_image_gallery", true );
    $cs_gallery = explode( ',', $cs_gallery );
    $width = '300';
    $height = '300';

    $nav_width = '100';
    $nav_height = '100';
    ?>

    <div aria-hidden="false" role="dialog" tabindex="-1" class="modal fade login_modal" id="car-rental-popup-<?php echo esc_attr( $post_id ); ?>">
        <div class="user-dialog">
            <div class="user-content">
                <div class="modal-header">
                    <h4><?php echo get_the_title( $post_id ); ?></h4>
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"> <span aria-hidden="true"><i class="icon-cross3"></i></span></button>
                </div>
                <div class="modal-body"> 
                    <script>
                        jQuery(document).ready(function () {
                            setTimeout(function () {
                                jQuery('.slider_for_<?php echo esc_attr( $post_id ); ?>').slick({
                                    autoplay: false,
                                    slidesToShow: 1,
                                    slidesToScroll: 1,
                                    arrows: false,
                                    fade: true,
                                    asNavFor: '.slider_nav_<?php echo esc_attr( $post_id ); ?>'
                                });

                                jQuery('.slider_nav_<?php echo esc_attr( $post_id ); ?>').slick({
                                    autoplay: false,
                                    slidesToShow: 4,
                                    slidesToScroll: 1,
                                    arrows: true,
                                    slide: true,
                                    asNavFor: '.slider_for_<?php echo esc_attr( $post_id ); ?>',
                                    dots: false,
                                    centerMode: true,
                                    focusOnSelect: true
                                });
                            }, 8000);
                        });
                    </script> 
                    <div class="features-box">
                        <div class="slider-holder">
                            <ul class="features-slider slider_for_<?php echo esc_attr( $post_id ); ?>">
                                <?php
                                foreach ( $cs_gallery as $key => $value ) {

                                    $cs_img_s = wp_get_attachment_image_src( $cs_gallery[0], array( 150, 150 ) );

                                    if ( isset( $cs_img_s['1'] ) && $cs_img_s['1'] == 150 ) {
                                        $thumbnail = CS_FUNCTIONS()->cs_get_post_img( $value, $width, $height );
                                    } else {
                                        $thumbnail = wp_car_rental::plugin_url() . '/assets/images/no-img-vechile.jpg';
                                    }
                                    if ( isset( $thumbnail ) && $thumbnail != '' ) {
                                        ?>
                                        <li class=""><img src="<?php echo esc_url( $thumbnail ); ?>" alt="" /></li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                            <ul class="features-thumbs slider_nav_<?php echo esc_attr( $post_id ); ?>">
                                <?php
                                foreach ( $cs_gallery as $key => $value ) {
                                    $cs_img_s = wp_get_attachment_image_src( $cs_gallery[0], array( 150, 150 ) );


                                    if ( isset( $cs_img_s['1'] ) && $cs_img_s['1'] == 150 ) {

                                        $thumbnail = CS_FUNCTIONS()->cs_get_post_img( $value, $nav_width, $nav_height );
                                    } else {
                                        $thumbnail = wp_car_rental::plugin_url() . '/assets/images/no-img-vechile.jpg';
                                    }

                                    if ( isset( $thumbnail ) && $thumbnail != '' ) {
                                        ?>
                                        <li class="slick-slide"><img src="<?php echo esc_url( $thumbnail ); ?>" alt="" /></li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>

                        </div>
                        <div class="features-list-bar">
                            <h3><a href="<?php get_the_permalink( $post_id ); ?>"><?php _e( 'Rented Car Features', 'rental' ); ?></a></h3>
                            <?php
                            $featureList = get_post_meta( $post_id, 'cs_vehicle_features', true );

                            $cs_feature_options = isset( $cs_plugin_options['cs_feats_options'] ) ? $cs_plugin_options['cs_feats_options'] : '';
                            $cs_output = '';
                            if ( is_array( $cs_feature_options ) && sizeof( $cs_feature_options ) > 0 ) {
                                $counter = 0;
                                echo '<ul class="cs-user-info">';
                                foreach ( $cs_feature_options as $feature ) {
                                    $feature_title = $feature['cs_feats_title'];
                                    $feature_image = $feature['cs_feats_image'];
                                    $feature_slug = isset( $feature['feats_id'] ) ? $feature['feats_id'] : '';
                                    $checked = '';
                                    if ( function_exists( 'icl_t' ) ) {
                                        $feature_title = icl_t( 'Vehicle Features', 'Feature "' . $feature_title . '" - Title field' );
                                    }
                                    $cs_image = '';
                                    if ( isset( $feature_image ) && $feature_image != '' ) {
                                        $cs_image = '<img src="' . esc_url( $feature_image ) . '" alt="" />';
                                    } else {
                                        $cs_image = '<i>&nbsp;</i>';
                                    }
                                    if ( is_array( $featureList ) && in_array( $feature_slug, $featureList ) ) {
                                        $counter ++;
                                        if ( $counter < 9 ) {
                                            echo '<li><a href="javascript:;">' . $cs_image . wp_trim_words( $feature_title, 3 ) . '</a></li>';
                                        }
                                    }
                                }
                                echo '</ul>';
                            }
                            ?>
                            <div class="enquiry"><a href="javascript:;" data-toggle="modal" data-target="#report-vehicle-<?php echo absint( $post_id ); ?>"><i class="icon-mail6"></i><?php _e( 'Quick Enquiry', 'rental' ); ?></a><a href="javascript:;" data-toggle="modal" data-target="#invite-vehicle-<?php echo absint( $post_id ); ?>" ><i class="icon-export"></i><?php _e( 'Email to a Friend', 'rental' ); ?></a></div>
                        </div>
                    </div>

                    <div class="features-detail">
                        <section id="scroll-box">
                            <div class="scroll-content mCustomScrollbar">
                                <div class="current-price">
                                    <?php
                                    if ( $price_flag == 'starting' ) {
                                        $price_val = get_post_meta( $post_id, 'cs_vehicle_price', true );
                                        if ( $price_val != '' ) {
                                            $currency_sign = isset( $cs_plugin_options['currency_sign'] ) && $cs_plugin_options['currency_sign'] != '' ? $cs_plugin_options['currency_sign'] : '$';
                                            ?>
                                            <span><?php _e( 'Price', 'rental' ); ?><em class="new-price"><?php echo esc_html( $currency_sign . $price_val ) ?></em><i class="icon-info7"></i></span>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <span><?php _e( 'Price', 'rental' ); ?><em class="new-price"><?php cs_get_pricing_breakdown( $post_id, 'price' ); ?></em><i class="icon-info7"></i></span>
                                    <?php } ?>
                                </div>
                                <?php echo do_shortcode( get_post_field( 'post_content', $post_id ) ); ?>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        jQuery('#car-rental-popup-<?php echo absint( $post_id ) ?>').on('show.bs.modal', function (e) {
            setTimeout(function () {
                jQuery('#car-rental-popup-<?php echo absint( $post_id ) ?>').resize();
            }, 500);
        });
    </script>
    <?php
}

/*
 *
 * @Vehicle Flex Slider
 * @retrun
 *
 */
if ( ! function_exists( 'cs_vehicles_flex_slider' ) ) {

    function cs_vehicles_flex_slider( $sliderData, $thumbArray, $is_thumb ) {
        global $cs_node, $post, $cs_theme_option;
        $cs_post_counter = rand( 40, 9999999 );
        ?>
        <!-- Flex Slider -->
        <div id="slider-<?php echo esc_attr( $cs_post_counter ); ?>" class="flexslider">
            <ul class="slides">
                <?php
                $cs_counter = 1;
                $cs_title_counter = 0;
                foreach ( $sliderData as $as_node ) {

                    echo '<li>
						<figure>
							<a href="' . esc_url( $as_node ) . '" title="" data-rel="prettyPhoto[gallery]"><img src="' . esc_url( $as_node ) . '" alt="" title=""></a>
						</figure>
						
				</li>';
                    $cs_title_counter ++;
                    $cs_counter ++;
                }
                ?>
                <!-- items mirrored twice, total of 12 -->
            </ul>
        </div>
        <?php if ( isset( $is_thumb ) && $is_thumb == 'true' ) { ?>
            <div id="carousel-<?php echo esc_attr( $cs_post_counter ); ?>" class="carousel">
                <ul class="slides">
                    <?php
                    $cs_counter = 1;
                    $cs_title_counter = 0;
                    foreach ( $thumbArray as $as_node ) {
                        echo '<li>
						<figure>
							<img src="' . esc_url( $as_node ) . '" alt="" title="">';
                        ?>
                        </figure>
                        </li>
                        <?php
                        $cs_title_counter ++;
                        $cs_counter ++;
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
        wp_car_rental::cs_flexslider_scripts();
        ?>
        <!-- Flex Slider Javascript Files -->
        <script type="text/javascript">

            jQuery(window).load(function () {
                // The slider being synced must be initialized first
                var target_flexslider = jQuery('.flexslider');
        <?php if ( isset( $is_thumb ) && $is_thumb == 'true' ) { ?>
                    jQuery('.carousel').flexslider({
                        animation: "slide",
                        controlNav: false,
                        smoothHeight: true,
                        animationLoop: false,
                        slideshow: false,
                        itemWidth: 113,
                        itemMargin: 5,
                        asNavFor: '.flexslider'

                    });
        <?php } ?>

                jQuery('.flexslider').flexslider({
                    animation: "slide",
                    controlNav: false,
                    smoothHeight: true,
                    animationLoop: false,
                    slideshow: false,
                    sync: ".carousel",
                    start: function (slider) {
                        target_flexslider.parent('.cs-gallery').removeClass('cs-loading');
                    }

                });

            });
        </script>

        <?php
    }

}

/**
 *
 * @Check Day OFF
 *
 */
function cs_check_day_off( $post_id = '' ) {
    global $post;

    $session_data = isset( $_SESSION['cs_reservation'] ) ? $_SESSION['cs_reservation'] : '';
    $date_from = $session_data['start_date'];
    $date_to = $session_data['end_date'];

    $start_date = strtotime( $date_from );
    $end_date = strtotime( $date_to );

    $brk_counter = 0;
    $get_posts = array( $post_id );

    for ( $i = $start_date; $i <= $end_date; $i = $i + 86400 ) {
        $brk_counter ++;
        $thisDate = date( 'Y-m-d', $i ); // 2010-05-01, 2010-05-02, etc
        $cs_args = array( 'posts_per_page' => '-1', 'post_type' => 'locations', 'post__in' => $get_posts, 'orderby' => 'ID', 'post_status' => 'publish' );

        $meta_fields_array = array( 'relation' => 'AND', );
        $meta_fields_array[] = array(
            'key' => 'cs_off_days',
            'value' => serialize( strval( $date_from ) ),
            'compare' => 'LIKE',
        );

        if ( is_array( $meta_fields_array ) && count( $meta_fields_array ) > 1 ) {
            $cs_args['meta_query'] = $meta_fields_array;
        }

        $day_query = new WP_Query( $cs_args );
        $day_count = $day_query->post_count;
        wp_reset_postdata();

        if ( $day_count > 0 ) {
            return 'exist';
            break;
        } else {
            return 'null';
            break;
        }
    }
}

/* ----------------------------------------------------------------------
 * @ Email Purchase Package
 * --------------------------------------------------------------------- */

function cs_send_inquiry_form() {
    global $cs_plugin_options;
    foreach ( $_REQUEST as $keys => $values ) {
        $$keys = $values;
    }

    $cs_contact_email = get_option( 'admin_email' );
    //$cs_plugin_options	= get_option( 'cs_plugin_options');
    $bloginfo = get_bloginfo();
    $subjecteEmail = "(" . $bloginfo . ") Inquiry Received";

    $cs_vehicle_title = get_the_title( $post_id );
    $cs_vehicle_price = get_post_meta( $post_id, 'cs_vehicle_price', true );

    $cs_currency = isset( $cs_plugin_options['currency_sign'] ) ? $cs_plugin_options['currency_sign'] : '';
    $featureList = get_post_meta( $post_id, 'cs_vehicle_features', true );
    $cs_feature_options = isset( $cs_plugin_options['cs_feats_options'] ) ? $cs_plugin_options['cs_feats_options'] : '';
    $cs_output = '';
    $features = '';

    if ( is_array( $cs_feature_options ) && sizeof( $cs_feature_options ) > 0 ) {
        $counter = 0;
        $features .= '<ul class="cs-user-info" style="float:left; width:100%; height:100%;">';
        foreach ( $cs_feature_options as $feature ) {
            $feature_title = $feature['cs_feats_title'];
            $feature_image = $feature['cs_feats_image'];
            $feature_slug = isset( $feature['feats_id'] ) ? $feature['feats_id'] : '';
            $checked = '';

            if ( function_exists( 'icl_t' ) ) {
                $feature_title = icl_t( 'Vehicle Features', 'Feature "' . $feature_title . '" - Title field' );
            }

            $cs_image = '';
            if ( isset( $feature_image ) && $feature_image != '' ) {
                $cs_image = '<img src="' . esc_url( $feature_image ) . '" alt="" />';
            } else {
                $cs_image = '<i>&nbsp;</i>';
            }
            if ( is_array( $featureList ) && in_array( $feature_slug, $featureList ) ) {
                $counter ++;
                if ( $counter < 4 ) {
                    $features .= '<li style="float:left; width:100%; height:100%;"><a href="javascript:;">' . $cs_image . $feature_title . '</a></li>';
                }
            }
        }
        $features .= '</ul>';
    }

    $message = '
		<table width="100%" border="1">
		  <tr>
			<td width="100"><strong>' . __( 'Vehicle:', 'rental' ) . '</strong></td>
			<td>' . $cs_vehicle_title . '</td>
		  </tr>
		  <tr>
			<td width="100"><strong>' . __( 'Price Start From', 'rental' ) . '</strong></td>
			<td>' . $cs_currency . $cs_vehicle_price . '</td>
		  </tr>
		   <tr>
			<td width="100"><strong>' . __( 'Features:', 'rental' ) . '</strong></td>
			<td>' . $features . '</td>
		  </tr>
		  <tr>
			<td width="100"><strong>' . __( 'Name:', 'rental' ) . '</strong></td>
			<td>' . $cs_name . '</td>
		  </tr>
		  <tr>
			<td><strong>' . __( 'Email:', 'rental' ) . '</strong></td>
			<td>' . $cs_email . '</td>
		  </tr>
		  <tr>
			<td><strong>Subject:</strong></td>
			<td>' . $cs_subject . '</td>
		  </tr>
		  <tr>
			<td><strong>' . __( 'Message:', 'rental' ) . '</strong></td>
			<td>' . $cs_description . '</td>
		  </tr>
		</table>';

    $headers = "From: " . $cs_name . "\r\n";
    $headers .= "Reply-To: " . $cs_email . "\r\n";
    $headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $attachments = '';

    if ( @mail( $cs_contact_email, $subjecteEmail, $message, $headers, $attachments ) ) {
        $json = array();
        $json['type'] = "success";
        $json['message'] = __( 'Email Sent Successfully', 'rental' );
    } else {
        $json['type'] = "error";
        $json['message'] = __( 'Some error occur,please try again later', 'rental' );
    }

    echo json_encode( $json );
    die;
}

add_action( 'wp_ajax_cs_send_inquiry_form', 'cs_send_inquiry_form' );
add_action( 'wp_ajax_nopriv_cs_send_inquiry_form', 'cs_send_inquiry_form' );


/* ----------------------------------------------------------------------
 * @ Email Purchase Package
 * --------------------------------------------------------------------- */

function cs_invite_friends() {
    global $cs_plugin_options;

    foreach ( $_REQUEST as $keys => $values ) {
        $$keys = $values;
    }

    $data_email = explode( ',', $cs_emails );

    $cs_contact_email = get_option( 'admin_email' );
    //$cs_plugin_options	= get_option( 'cs_plugin_options');
    $bloginfo = get_bloginfo();
    $subjecteEmail = "(" . $bloginfo . ") Invitation";

    if ( isset( $data_email ) && is_array( $data_email ) && ! empty( $data_email ) ) {
        foreach ( $data_email as $emailto ) {
            $message = '
				<table width="100%" border="0">
				  <tr>
					<td>' . $cs_description . '</td>
				  </tr>
				</table>';

            $data_names = explode( '@', $emailto );
            if ( $data_names ) {
                $cs_name = $data_names[0];
            }

            $headers = "From: " . $cs_name . "\r\n";
            $headers .= "Reply-To: " . $cs_contact_email . "\r\n";
            $headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $attachments = '';

            @mail( $emailto, $subjecteEmail, $message, $headers, $attachments );
            $json = array();
            $json['type'] = "success";
            $json['message'] = __( 'Email Sent Successfully', 'rental' );
        }
    }

    echo json_encode( $json );
    die;
}

add_action( 'wp_ajax_cs_invite_friends', 'cs_invite_friends' );
add_action( 'wp_ajax_nopriv_cs_invite_friends', 'cs_invite_friends' );

/**
 *
 * @Check Time Availabilty
 *
 */
function cs_check_time_availabilty( $post_id = '' ) {
    global $post;

    $cs_days_data = get_post_meta( $post_id, "cs_days_data", false );
    $cs_days_data = $cs_days_data[0];

    $cs_location_start_time = get_post_meta( $post_id, "cs_location_start_time", false );
    $cs_location_start_time = $cs_location_start_time[0];

    $cs_location_end_time = get_post_meta( $post_id, "cs_location_end_time", false );
    $cs_location_end_time = $cs_location_end_time[0];

    $session_data = isset( $_SESSION['cs_reservation'] ) ? $_SESSION['cs_reservation'] : '';

    //Time
    $start_time = $session_data['start_time'];
    $end_time = $session_data['end_time'];
    $start_time = strtotime( $start_time );
    $end_time = strtotime( $end_time );

    //Date
    $date_from = $session_data['start_date'];
    $date_to = $session_data['end_date'];
    $start_date = strtotime( $date_from );
    $end_date = strtotime( $date_to );
    $brk_counter = 0;
    $check_time = false;
    $week_day = array( 1 => 'monday', 2 => 'tuesday', 3 => 'wednesday', 4 => 'thursday', 5 => 'friday', 6 => 'saturday', 7 => 'sunday' );
    for ( $i = $start_date; $i <= $end_date; $i = $i + 86400 ) {

        $brk_counter ++;
        $thisDate = date( 'Y-m-d', $i ); // 2010-05-01, 2010-05-02, etc
        $day = strtolower( date( 'l', strtotime( $thisDate ) ) );
        $day_status = $cs_days_data[$day];

        $key = array_search( $day, $week_day );

        if ( $day_status == 'off' ) {
            $check_time = true;
            return 'exist';
            break;
        }

        if ( $check_time == false ) {

            // End Time
            $location_start_time = $cs_location_start_time[$key];
            //$temp_start_time			= date( "h:i A" , strtotime( $location_start_time ) );
            $db_start_time = strtotime( $location_start_time );
            // End Time
            $location_end_time = $cs_location_end_time[$key];
            //$temp_end_time				= date( "h:i A" , strtotime( $location_end_time ) );
            $db_end_time = strtotime( $location_end_time );

            if ( $db_start_time <= $start_time && $db_end_time >= $end_time ) {
                $check_time = false;
            }

            if ( $check_time == true ) {
                return 'exist';
            }
        }
    }

    return 'null';
}

// Create Form
function cs_quick_inquiry( $post_id = '' ) {
    ob_start();
    ?>
    <div class="modal fade" id="report-vehicle-<?php echo absint( $post_id ); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true"><i class="icon-times"></i></span></button>
                    <h5 class="modal-title" id="myModalLabel"><?php _e( 'Quick Quote', 'rental' ); ?></h5>
                </div>
                <div class="modal-body">
                    <div class="cs-messages"></div>
                    <ul class="reviews-modal" data-post="<?php echo absint( $post_id ); ?>">
                        <li>
                            <label><?php _e( 'Name', 'rental' ); ?></label>
                            <input type="text" name="cs_name" id="cs-name" class="cs-name" />
                        </li>
                        <li>
                            <label><?php _e( 'Email', 'rental' ); ?></label>
                            <input type="text"  name="cs_email" id="cs-email" class="cs-email" />
                        </li>
                        <li>
                            <label><?php _e( 'Subject', 'rental' ); ?></label>
                            <input type="text"   name="cs_subject" id="cs-subject" class="cs-subject" />
                        </li>
                        <li>
                            <label><?php _e( 'Write Description', 'rental' ); ?></label>
                            <textarea type="text" name="cs_description"  id="cs-description" class="cs-description"></textarea>
                        </li>
                        <li>
                            <input type="submit"  class="cs-bgcolor cs-send-inquiry-form" value="<?php _e( 'Send', 'rental' ); ?>" />
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php
    echo ob_get_clean();
}

// Booking Form
function cs_get_booking_form( $rand = '' ) {
    global $cs_plugin_options;

    ob_start();
    $cs_page_id = isset( $cs_plugin_options['cs_reservation'] ) && $cs_plugin_options['cs_reservation'] != '' && absint( $cs_plugin_options['cs_reservation'] ) ? $cs_plugin_options['cs_reservation'] : '';

    $search_link = add_query_arg( array( 'action' => 'booking' ), esc_url( get_permalink( $cs_page_id ) ) );
    $cs_vehicle_types = get_option( 'cs_type_options' );

    wp_car_rental::cs_enqueue_datepicker_script();
    ?>
    <div class="modal fade" id="car-booking-popup-<?php echo esc_attr( $rand ); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content cs-popup-booking-form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true"><i class="icon-times"></i></span></button>
                    <h5 class="modal-title" id="myModalLabel"><?php _e( 'Booking', 'rental' ); ?></h5>
                </div>
                <div class="modal-body">
                    <div class="vehicle-search widget-searchform">
                        <script>
                            jQuery(document).ready(function ($) {
                                cs_widget_script();
                            });
                        </script>
                        <?php 
                        $rand_id = rand( 3242343, 324324990 );
                        ?>
                        <div class="profile-setting tab-content">
                            <form class="form-reviews_<?php echo absint($rand_id); ?>" method="post" action="<?php echo esc_url( $search_link ); ?>" id="vehicle-seach">
                                <div class="tab-area tab-pane fade active in" id="cs-tab-education3520">
                                    <div class="vehicle-type-wrap">

                                        <!--                                        <ul class="tab-list">-->
                                        <ul class="cs-vehicle-radio">
                                            <?php
                                            $cs_type_data = get_option( "cs_type_options" );
                                            if ( isset( $cs_type_data ) && is_array( $cs_type_data ) && ! empty( $cs_type_data ) ) {
                                                $counter = 0;
                                                foreach ( $cs_type_data as $key => $type ) {
                                                    $cs_counter = rand( 3242343, 324324990 );
                                                    $counter ++;
                                                    $checked = $counter == 1 ? 'checked="checked"' : '';
                                                    $active = $counter == 1 ? 'active' : '';

                                                    $vehicle_name = isset( $type['cs_type_name'] ) ? $type['cs_type_name'] : '';
                                                    if ( function_exists( 'icl_t' ) ) {
                                                        $vehicle_name = icl_t( 'Vehicle Types', 'Type "' . $vehicle_name . '" - Name field' );
                                                    }

                                                    if ( isset( $type['cs_type_image'] ) && ! empty( $type['cs_type_image'] ) ) {
                                                        $image = '<img src="' . esc_url( $type['cs_type_image'] ) . '" alt="" />';
                                                    } else {
                                                        $image = '';
                                                    }

                                                    echo '<li class="' . $active . '">' . $image . '<input name="vehicle-type" ' . $checked . ' type="radio" id="type_' . $cs_counter . '" value="' . $key . '" /><label for="type_' . $cs_counter . '">' . $vehicle_name . '</label></li>';
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <div class="select-holder dp-margin">
                                        <select name="pickup_location" class="pickup_location">
                                            <option value=""><?php _e( 'Select PickUp Location', 'rental' ) ?></option>
                                            <?php
                                            $cs_args = array( 'posts_per_page' => '-1', 'post_type' => 'locations', 'orderby' => 'ID', 'post_status' => 'publish', 'suppress_filters' => 0 );
                                            $cust_query = get_posts( $cs_args );

                                            $cs_locations[''] = __( 'Select Location', 'rental' );

                                            if ( isset( $cust_query ) && is_array( $cust_query ) && ! empty( $cust_query ) ) {
                                                foreach ( $cust_query as $key => $location ) {
                                                    echo '<option value="' . $location->ID . '">' . get_the_title( $location->ID ) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="select-holder dropup-wrap" style="display:none">
                                        <select class="dropup_location" name="dropup_location">
                                            <option value=""><?php _e( 'Please Drop Up Location', 'rental' ) ?></option>
                                            <?php
                                            $cs_args = array( 'posts_per_page' => '-1', 'post_type' => 'locations', 'orderby' => 'ID', 'post_status' => 'publish' );
                                            $cust_query = get_posts( $cs_args );

                                            $cs_locations[''] = __( 'Select Location', 'rental' );

                                            if ( isset( $cust_query ) && is_array( $cust_query ) && ! empty( $cust_query ) ) {
                                                foreach ( $cust_query as $key => $location ) {
                                                    echo '<option value="' . $location->ID . '">' . get_the_title( $location->ID ) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>

                                    </div>
                                    <div class=" check-box">
                                        <input type="hidden" value="off" name="station">
                                        <input type="checkbox" checked="checked" value="on" class="station" name="station" id="station_boooking">
                                        <label for="station_boooking"><?php _e( 'Return car to the same station', 'rental' ) ?></label>
                                    </div>
                                    <div class="pick-date">
                                        <h6><?php _e( 'Pick up date & time', 'rental' ) ?></h6>
                                        <div class="date-holder">
                                            <div class="date cs-calendar-combo">
                                                <input type="text" class="pickup_date" name="pickup_date" value="<?php echo date( 'd.m.Y' ); ?>" placeholder="<?php echo date( 'd.m.Y' ); ?>">
                                            </div>
                                            <div class="time">
                                                <input type="text" class="pickup_time" name="pickup_time" value="<?php echo date( 'H:i A' ); ?>" placeholder="<?php echo date( 'H:i A' ); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pick-date">
                                        <h6><?php _e( 'Drop date & time', 'rental' ) ?></h6>
                                        <div class="date-holder">
                                            <div class="date cs-calendar-combo">
                                                <input type="text" class="dropup_date" name="dropup_date"  value="<?php echo date( 'd.m.Y' ); ?>" placeholder="<?php echo date( 'd.m.Y' ); ?>">
                                            </div>
                                            <div class="time">
                                                <input type="text" class="dropup_time" name="dropup_time" value="<?php echo date( 'H:i A' ); ?>" placeholder="<?php echo date( 'H:i A' ); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="check-box">
                                        <div  class="pull-left" style="padding-top:5px;">
                                            <input type="hidden" value="off" name="aged">
                                            <input type="checkbox" value="on" name="aged" class="aged" id="aged_boooking">
                                            <label for="aged_boooking"><?php _e( 'Driver aged between 25 – 70?', 'rental' ) ?></label>
                                        </div>
                                        <?php /* ?>                <a href="javascript:;" class="btn-search seach_vehicle_btn"><i class="icon-arrow-right9"></i><?php _e('Search Car','rental')?></a><?php */ ?>
                                        <input type="submit" class="btn-search seach_vehicle_btn" value="<?php _e( 'Search Car', 'rental' ) ?>" /> 
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    echo ob_get_clean();
}

function cs_server_protocol() {

    if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) {
        return 'https://';
    }
    return 'http://';
}

// Create Form
function cs_invite_form( $post_id = '' ) {
    ob_start();
    ?>
    <div class="modal fade email-message" id="invite-vehicle-<?php echo absint( $post_id ); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true"><i class="icon-times"></i></span></button>
                    <h5 class="modal-title" id="myModalLabel"><?php _e( 'Quick Quote', 'rental' ); ?></h5>
                </div>
                <div class="modal-body">
                    <div class="cs-messages"></div>
                    <ul class="reviews-modal" data-post="<?php echo absint( $post_id ); ?>">
                        <li>
                            <label><?php _e( 'Emails', 'rental' ); ?></label>
                            <input type="text" name="cs_name" data-role="tagsinput" id="cs-emails" class="cs-emails emails-tags" />
                            <p><?php _e( 'Write Email address,and hit Enter to add email in list.', 'rental' ); ?>
                        </li>
                        <li>
                            <label><?php _e( 'Write Description', 'rental' ); ?></label>
                            <textarea type="text" name="cs_description"  id="cs-description" class="cs-description"><?php echo esc_attr( 'Hi,', 'rental' ); ?><?php echo 'I want to share this amazing Vehicle ' . get_the_title( $post_id ) . ': ' . get_the_permalink( $post_id ); ?> </textarea>
                        </li>
                        <li>
                            <input type="submit"  class="cs-bgcolor cs-send-invite-form" value="<?php _e( 'Send', 'rental' ); ?>" />
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php
    echo ob_get_clean();
}
?>