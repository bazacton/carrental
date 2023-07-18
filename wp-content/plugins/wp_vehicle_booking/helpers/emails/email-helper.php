<?php

if (!class_exists('cs_email_helper')) {

    class cs_email_helper {

        public function __construct() {
            // Do Something
        }

        /* ----------------------------------------------------------------------
         * @ Email Header
         * --------------------------------------------------------------------- */

        public function cs_get_email_header($logo = '') {
            global $current_user;
            $header = '';
            $header .= '<img src="' . esc_url($logo) . '" alt="" title="logo" />';

            return $header;
        }

        /* ----------------------------------------------------------------------
         * @ Email Footer
         * --------------------------------------------------------------------- */

        public function cs_get_email_footer($params = '') {
            global $current_user;
            $footer = '';
            $footer .= 'Footer';
            return $footer;
        }

        /* ----------------------------------------------------------------------
         * @ Email Add New Directory
         * --------------------------------------------------------------------- */

        public function cs_order_confirmation($params = '') {
            global $current_user, $cs_plugin_options;
            extract($params);
            //$cs_plugin_options 		= get_option('cs_plugin_options');
            $currency_sign = isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] != '' ? $cs_plugin_options['currency_sign'] : '$';
            $cs_booking = get_post_meta($order_id, 'cs_booked_vehicle_data', false);
            $cs_booking_extras = get_post_meta($order_id, 'cs_booking_extras', false);
            $cs_booked_vehicle_id = get_post_meta($order_id, 'cs_booked_vehicle_id', false);

            $cs_booking = $cs_booking[0];
            $cs_booking_extras = $cs_booking_extras[0];
            $subject = __('Booking Order', 'rental');
            $discount_price = 0;
            $vehicles_price = 0;
            $attachments = '';
            $body = '';

            $body .= '<div class="cs-invoice-wrape" style="width:70%; margin: 0 auto; font-family:Arial, Helvetica, sans-serif;">
            <div style="width:50%; float:left; margin:0 0 20px; font-family:Arial, Helvetica, sans-serif;">
                     <a href="' . esc_url(home_url('/')) . '"><img src="' . $logo . '" alt="#"></a>
            </div>
            <div style="width:50%; float:right; margin:0 0 20px 0; font-weight:600; font-family:Arial, Helvetica, sans-serif;">
                    <span style="float:right; margin:0; font-family:Arial, Helvetica, sans-serif;">' . __('Invoice', 'rental') . ':: ' . $order_id . '</span><br>
                    <span style="float:right; margin:0; font-family:Arial, Helvetica, sans-serif;">' . __('Payment Date', 'rental') . ':: ' . date_i18n(get_option('date_format'), strtotime(date('Y-m-d'))) . '</span><br>
                    <span style="float:right; margin:0; font-family:Arial, Helvetica, sans-serif;">' . __('Payment Method', 'rental') . ':: ' . $cs_gateway . '</span>
            </div>
            <div style="width:100%; float:left; border:1px solid #000; border-bottom:none;">
            <table style="margin:0; border-bottom:1px solid #000; width:100%;  border-collapse: collapse;border-spacing: 0; line-height:2; font-family:Arial, Helvetica, sans-serif;">
                <thead>
                    <tr>
                        <th rowspan="2" style="border-width: 0 0 1px 0; border-color:#000; font-size:12px; font-weight:400; padding: 6px; line-height:20px; border-style:solid; vertical-align:middle; text-align:left; line-height:2; font-family:Arial, Helvetica, sans-serif;">
                         <strong style="display:block; line-height:22px; color:#222; font-weight:600; font-family:Arial, Helvetica, sans-serif;">' . __('Dear', 'rental') . $first_name . ' ' . $last_name . '</strong>
                         ' . $email_to . '<br />
                         ' . $cs_address . '
                        </th>
                        <th style="border-width: 0 1px 1px 1px; border-color:#000; border-style:solid; vertical-align:middle; text-align:center; padding:0; font-weight:400; line-height:2; font-weight:700; font-size:12px; text-transform:uppercase; font-family:Arial, Helvetica, sans-serif;">' . __('Vehicle Type', 'rental') . '</th>
                        <th style="border-width: 0 1px 1px 0; border-color:#000; border-style:solid; vertical-align:middle; text-align:center; padding:0; font-weight:400; line-height:2; font-weight:700; font-size:12px; text-transform:uppercase; font-family:Arial, Helvetica, sans-serif;">' . __('Booking Id', 'rental') . '</th>
                        <th style="border-width: 0 1px 1px 0; border-color:#000; border-style:solid; vertical-align:middle; text-align:center; padding:0; font-weight:400; line-height:2; font-weight:700; font-size:12px; text-transform:uppercase; font-family:Arial, Helvetica, sans-serif;">' . __('Check In Date', 'rental') . '</th>
                        <th style="border-width: 0 0 1px 0; border-color:#000; border-style:solid; vertical-align:middle; text-align:center; padding:0; font-weight:400; line-height:2; font-weight:700; font-size:12px; text-transform:uppercase; font-family:Arial, Helvetica, sans-serif;">' . __('Check Out Date', 'rental') . '</th>
                        </tr>
                        <tr>
                        <th style="border-width: 0 1px 1px 0; border-color:#000; border-style:solid; vertical-align:middle; text-align:center; padding:0; font-weight:400; line-height:2; font-size:12px; font-family:Arial, Helvetica, sans-serif;">' . strtoupper($cs_type_name) . '</th>
                        <th style="border-width: 0 1px 1px 1px; border-color:#000; border-style:solid; vertical-align:middle; text-align:center; padding:0; font-weight:400; line-height:2; font-size:12px; font-family:Arial, Helvetica, sans-serif;"> #' . $booking_id . '</th>

                        <th style="border-width: 0 1px 1px 0; border-color:#000; border-style:solid; vertical-align:middle; text-align:center; padding:0; font-weight:400; line-height:2; font-size:12px; font-family:Arial, Helvetica, sans-serif;">' . date_i18n(get_option('date_format'), strtotime($check_in_date)) . ' ' . date('H:i A', strtotime($cs_pickup_time)) . '</th>
                        <th style="border-width: 0 0 1px 0; border-color:#000; border-style:solid; vertical-align:middle; text-align:center; padding:0; font-weight:400; line-height:2; font-size:12px; font-family:Arial, Helvetica, sans-serif;">' . date_i18n(get_option('date_format'), strtotime($check_out_date)) . ' ' . date('H:i A', strtotime($cs_dropup_time)) . '</th>
                    </tr>
                    <tr>
                        <th style="border-width: 0 1px 1px 0; border-color:#000; border-style:solid; vertical-align:middle; text-align:left; padding:0 0 0 6px; font-weight:600; line-height:2; font-family:Arial, Helvetica, sans-serif;">Billing information</th>
                        <th colspan="4" style="border-bottom:1px solid #000; border-right:none; line-height:2; font-family:Arial, Helvetica, sans-serif;"></th>
                    </tr>
                    <tr>
                        <th colspan="4" style="border-width: 0 0 1px 0; border-color:#000; font-size:14px; font-weight:400; padding: 6px; line-height:20px; border-style:solid; vertical-align:middle; text-align:left; line-height:2; font-family:Arial, Helvetica, sans-serif;">
                         ' . __('Description', 'rental') . '
                        </th>
                        <th style="border-width: 0 0 1px 0; border-color:#000; border-style:solid; vertical-align:middle; text-align:right; padding:0 6px 0 6px; font-weight:400; font-size:14px; line-height:2; font-family:Arial, Helvetica, sans-serif;">Balance</th>
                    </tr>
                </thead>
                <tbody style="border-bottom:1px solid #000;">';

            if (isset($cs_booking) && is_array($cs_booking) && !empty($cs_booking)) {
                foreach ($cs_booking as $key => $data) {
                    $body .= '<tr>
                            <td colspan="4" style="border:none; text-align:left; padding:0 6px 0 6px; line-height:2; font-family:Arial, Helvetica, sans-serif;">';
								//$vehicle_id = get_post_meta($order_id, 'cs_booked_vehicle_id', true);
								//$body .= get_the_title($order_id) . ' #' . get_the_title($vehicle_id);
                                $body .= get_the_title($order_id) . ' #' . $data['vehicle_id'];
                            $body .= '</td>';

                    if ($data['discount'] > 0) {
                        $discount = $data['discount'];
                        $vehicles_price += $data['orignal_price'];
                        $discount_price += $data['price'];
                        $body .= '<td style="border:none; text-align:right; padding:0 6px 0 0;  font-family:Arial, Helvetica, sans-serif;"><span style="text-decoration:line-through;"> ' . $currency_sign . number_format($data['orignal_price'], 2) . ', </span>' . $currency_sign . number_format($data['price'], 2) . '</td>';
                    } else {
                        $body .= '<td style="border:none; text-align:right; padding:0 6px 0 0;  font-family:Arial, Helvetica, sans-serif;"><span>' . $currency_sign . number_format($data['price'], 2) . '</td>';
                    }

                    $body .= '</tr>';
                }
            }

            $discount_price = $vehicles_price - $discount_price;

            $cs_extras_options = isset($cs_plugin_options['cs_extra_features_options']) ? $cs_plugin_options['cs_extra_features_options'] : '';

            if (isset($cs_booking_extras) && !empty($cs_booking_extras)) {
                $extrasList = $cs_booking_extras;
            } else {
                $extrasList = array();
            }

            if (isset($cs_extras_options) && !empty($cs_extras_options)) {
                if (isset($extrasList) && !empty($extrasList)) {
                    foreach ($extrasList as $key => $extras) {
                        $cs_price_options = $cs_extras_price[0];
                        $extra_name = $cs_extras_options[$key]['cs_extra_feature_title'];
                        $extra_price = $cs_price_options[$key][0];
                        $body .= '<tr>
                                    <td colspan="4" style="border:none; text-align:left; padding:0 6px 0 6px;line-height:2; font-family:Arial, Helvetica, sans-serif;">' . $extra_name . '</td>
                                    <td style="border:none; text-align:right; padding:0 6px 0 0; font-family:Arial, Helvetica, sans-serif;">' . $currency_sign . number_format($extra_price, 2) . '</td>
                            </tr>';
                    }
                }
            }

            if ($payment_type == 'deposit') {
                $row = '5';
            } else {
                $row = '5';
            }

            $body .= '</tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" rowspan="' . $row . '" style="border-width: 0 0 1px 0; border-color:#000; font-size:12px; font-weight:400; padding: 6px; line-height:20px; border-style:solid; vertical-align:middle; font-family:Arial, Helvetica, sans-serif;">';
            if ($payment_type == 'deposit') {
                $body .= '<strong style="display:block; line-height:22px; color:#222; font-weight:600; line-height:2; font-family:Arial, Helvetica, sans-serif;">' . __('Deposit Amount', 'rental') . $currency_sign . number_format($cs_bkng_advance, 2) . '<br/> ' . __('Remaining Amount', 'rental') . $currency_sign . number_format($cs_bkng_remaining, 2) . ',  ' . __('*Pay the rest on arrival', 'rental') . '</strong>';
            } else {
                $body .= '<strong style="display:block; line-height:22px; color:#222; font-weight:600; line-height:2; font-family:Arial, Helvetica, sans-serif;">' . __('All Payment done.', 'rental') . '</strong>';
            }

            $body .= '</td>
                            <td style="border-width: 0 1px 1px 1px; border-color:#000; border-style:solid; vertical-align:middle; padding:0 0 0 6px; font-weight:400; text-align:left; line-height:2; font-family:Arial, Helvetica, sans-serif;">' . __('Total', 'rental') . '</td>
                            <td style="border-width: 0 0 1px 0; border-color:#000; border-style:solid; vertical-align:middle; text-align:right; padding:0 6px 0 0; font-weight:400; line-height:2; font-family:Arial, Helvetica, sans-serif;">' . $currency_sign . $gross_total . '</td>
                    </tr>
                    <tr>
                            <td style="border-width: 0 1px 1px 1px; border-color:#000; border-style:solid; vertical-align:middle; text-align:left; padding:0 0 0 6px; font-weight:400; line-height:2; font-family:Arial, Helvetica, sans-serif;">' . __('VAT', 'rental') . '(' . $vat_percentage . '%)</td>
                            <td style="border-width: 0 0 1px 0; border-color:#000; border-style:solid; vertical-align:middle; text-align:right; padding:0 6px 0 0; font-weight:400; line-height:2; font-family:Arial, Helvetica, sans-serif;">' . $currency_sign . $bkng_tax . '</td>
                    </tr>
                    <tr>
                            <td style="border-width: 0 1px 1px 1px; border-color:#000; border-style:solid; vertical-align:middle; text-align:left; padding:0 0 0 6px; font-weight:400; line-height:2; font-family:Arial, Helvetica, sans-serif;">' . __('Discount', 'rental') . '</td>
                            <td style="border-width: 0 0 1px 0; border-color:#000; border-style:solid; vertical-align:middle; text-align:right; padding:0 6px 0 0; font-weight:400; line-height:2; font-family:Arial, Helvetica, sans-serif;">' . $currency_sign . number_format($discount_price, 2) . '</td>
                    </tr>
                    <tr>
                            <td style="border-width: 0 1px 1px 1px; border-color:#000; border-style:solid; vertical-align:middle; text-align:left; padding:0 0 0 6px; font-weight:400; line-height:2; font-family:Arial, Helvetica, sans-serif;">' . __('Net Paid', 'rental') . '</td>
                            <td style="border-width: 0 0 1px 0; border-color:#000; border-style:solid; vertical-align:middle; text-align:right; padding:0 6px 0 0; font-weight:400; line-height:2; font-family:Arial, Helvetica, sans-serif;">' . $currency_sign . $cs_bkng_advance . '</td>
                    </tr>';
            $body .= '<tr>
                            <td style="border-width: 0 1px 1px 1px; border-color:#000; border-style:solid; vertical-align:middle; text-align:left; padding:0 0 0 6px; font-weight:400; line-height:2; font-family:Arial, Helvetica, sans-serif;">' . __('Balance', 'rental') . '</td>
                            <td style="border-width: 0 0 1px 0; border-color:#000; border-style:solid; vertical-align:middle; text-align:right; padding:0 6px 0 0; font-weight:400; line-height:2; font-family:Arial, Helvetica, sans-serif;">' . $currency_sign . ( number_format(($grand_total - $cs_bkng_advance), 2) ) . '</td>
                    </tr>';
            $body .= '</tfoot>
                            </table>
                    </div>
            </div>';

            $headers = "From: " . $booking_email . "\r\n";
            $headers .= "Reply-To: " . $booking_email . "\r\n";
            $headers .= "CC: " . $admin_email . "\r\n";
            $headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
            $headers .= "MIME-Version: 1.0" . "\r\n";
            wp_mail($email_to, $subject, $body, $headers, $attachments);
        }

        /* ----------------------------------------------------------------------
         * @ Email Purchase Package
         * --------------------------------------------------------------------- */

        public function cs_booking_cancellation($params = '') {
            global $current_user;
            extract($params);

            $subject = "{$cs_package_name} purchased on (" . get_bloginfo() . ")";

            $headers = "From: " . esc_attr($name) . "\r\n";
            $headers .= "Reply-To: " . sanitize_email($email) . "\r\n";
            $headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
            $headers .= "MIME-Version: 1.0" . "\r\n";

            $attachments = '';

            $body = cs_get_email_header();
            $body .= 'Message here..';
            $body .= cs_get_email_footer();

            wp_mail(sanitize_email($current_user->email), $subjecteEmail, $body, $headers, $attachments);
        }

        /* ----------------------------------------------------------------------
         * @ Email User Registration
         * --------------------------------------------------------------------- */

        public function cs_order($params = '') {
            global $current_user;
            extract($params);

            $subject = "Registration on (" . get_bloginfo() . ")";

            $headers = "From: " . esc_attr($name) . "\r\n";
            $headers .= "Reply-To: " . sanitize_email($email) . "\r\n";
            $headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
            $headers .= "MIME-Version: 1.0" . "\r\n";

            $attachments = '';

            $body = cs_get_email_header();
            $body .= 'Message here..';
            $body .= cs_get_email_footer();

            wp_mail(sanitize_email($current_user->email), $subjecteEmail, $body, $headers, $attachments);
        }

    }

}