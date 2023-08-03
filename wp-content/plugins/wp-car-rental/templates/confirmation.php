<?php
/**
 *
 * @Search page Template
 *
 */
global $post, $cs_notification, $cs_plugin_options;
get_header();
$cs_plugin_notify = new CS_Plugin_Notification_Helper();
$post_id = $_GET['invoice'];
$currency_sign = isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] != '' ? $cs_plugin_options['currency_sign'] : '$';
$session_id = '';
if (isset($_SESSION['cs_session_booked_id']) && $_SESSION['cs_session_booked_id'] != '') {
    $session_id = $_SESSION['cs_session_booked_id'];
}
$search_link = add_query_arg(array('action' => 'booking'), esc_url(get_permalink($cs_page_id)));
?>

<div class="container">
    <div class="row">
        <div class="section-fullwidth reservation-editor">
            <?php if ($session_id == $post_id) { ?>
                <div class="page-sidebar">
                    <div class="booking-widget">
                        <h3><?php _e('Order detail', 'rental'); ?></h3>
                        <ul class="price-list">		
                            <li><i class="icon-check-circle"></i><?php _e('Booking Id', 'rental'); ?><span><?php echo get_post_meta($post_id, 'cs_booking_id', true); ?></span>
                            </li>
                            <?php
                            $cs_booking = get_post_meta($post_id, 'cs_booked_vehicle_data', false);
                            $cs_bkng_gross_total = get_post_meta((int) $post_id, 'cs_bkng_gross_total', true);
                            $vat_percentage = get_post_meta((int) $post_id, 'cs_bkng_vat_percentage', true);
                            $cs_bkng_tax = get_post_meta((int) $post_id, 'cs_bkng_tax', true);
                            $grand_total = get_post_meta($post_id, 'cs_bkng_grand_total', true);
                            $cs_bkng_advance = get_post_meta($post_id, 'cs_bkng_advance', true);
                            $cs_booked_vehicle_id = get_post_meta($post_id, 'cs_booked_vehicle_id', true);
                            $cs_booking = $cs_booking[0];

                            if (isset($cs_booking) && is_array($cs_booking) && !empty($cs_booking)) {
                                $counter = 0;
                                foreach ($cs_booking as $key => $data) {
                                    $counter++;
                                    ?>
                                    <li><i class="icon-check-circle"></i><?php _e('Booked Vehicle', 'rental'); ?><span><?php echo get_the_title($cs_booked_vehicle_id) . ' #' . $data['key']; ?></span></li>
                                    <?php
                                }
                            }
                            ?>
                            <li><i class="icon-check-circle"></i> <?php _e('Gross Total', 'rental'); ?><span><?php echo esc_attr($currency_sign) . number_format($cs_bkng_gross_total, 2); ?></span>

                            </li>
                            <li><i class="icon-check-circle"></i> <?php _e('VAT', 'rental'); ?><span><?php echo esc_attr($currency_sign) . number_format($cs_bkng_tax, 2); ?></span>(<?php echo esc_attr($vat_percentage); ?>%)</li>
                            <?php
                            if ($cs_bkng_advance > 0) {
                                ?>
                                <li><i class="icon-check-circle"></i><?php _e('Total', 'rental'); ?><span><?php echo esc_attr($currency_sign) . number_format($grand_total, 2); ?></span></li>

                                <li><i class="icon-check-circle"></i><?php _e('Paid', 'rental'); ?><span><?php echo esc_attr($currency_sign) . number_format($cs_bkng_advance, 2); ?></span></li>
                                <li><i class="icon-check-circle"></i><?php _e('Balance', 'rental'); ?><span><?php echo esc_attr($currency_sign) . ( number_format(( $grand_total - $cs_bkng_advance), 2) ); ?></span></li>
                                <?php
                            } else {
                                ?>
                                <li><i class="icon-check-circle"></i><?php _e('Grand Total', 'rental'); ?><span><?php echo esc_attr($currency_sign) . number_format($grand_total, 2); ?></span></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="page-content">
                    <section class="page-section">
                        <div class="container">
                            <div class="row">
                                <div class="element-size-100">
                                    <div class="col-md-12">
                                        <ul class="booking-tabs">
                                            <li><a><span><i class="icon-plus3"></i></span>
                                                    <h4><em><?php _e('Step 1', 'rental'); ?></em> <?php _e('Add Essential', 'rental'); ?></h4>
                                                </a></li>
                                            <li><a><span><i class="icon-database"></i></span>
                                                    <h4><em><?php _e('Step 2', 'rental'); ?></em><?php _e('Reservation and Payments', 'rental'); ?></h4>
                                                </a></li>
                                            <li class="active"><a><span><i class="icon-checkmark6"></i></span>
                                                    <h4><em> <?php _e('Step 3', 'rental'); ?></em><?php _e('Confirmation', 'rental'); ?></h4>
                                                </a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="tabs-content">
                                        <div class="tabs" id="tab4">
                                            <div class="booking-step">
                                                <div class="cs-confirmed">
                                                    <ul class="cs-element-list">
                                                        <li>
                                                            <div class="fields-area col-md-12">
                                                                <div class="confirmd-msg"> <i><img src="<?php echo wp_car_rental::plugin_url(); ?>/assets/images/confirmed.png" alt=""></i>
                                                                    <?php
                                                                    if ($cs_plugin_options['cs_thank_title'] && $cs_plugin_options['cs_thank_title'] != '') {
                                                                        echo '<h5>' . __($cs_plugin_options['cs_thank_title'], 'rental') . '</h5>';
                                                                    }

                                                                    if ($cs_plugin_options['cs_thank_msg'] && $cs_plugin_options['cs_thank_msg'] != '') {
                                                                        echo '<span class="col-md-10">' . __($cs_plugin_options['cs_thank_msg'], 'rental') . '</span>';
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="fields-area col-md-10">
                                                                <div class="contact-area">
                                                                    <p>
                                                                        <?php _e('For Cancellation or more information Please Contact us', 'rental'); ?>
                                                                    </p>
                                                                    <ul class="contact-list">
                                                                        <?php
                                                                        if ($cs_plugin_options['cs_confir_phone'] && $cs_plugin_options['cs_confir_phone'] != '') {
                                                                            echo '<li><i class="icon-mobile-phone"></i>' . __('Phone: ', 'rental') . __($cs_plugin_options['cs_confir_phone'], 'rental') . '</li>';
                                                                        }

                                                                        if ($cs_plugin_options['cs_confir_fax'] && $cs_plugin_options['cs_confir_fax'] != '') {
                                                                            echo '<li><i class="icon-printer4"></i>' . __('Fax: ', 'rental') . __($cs_plugin_options['cs_confir_fax'], 'rental') . '</li>';
                                                                        }

                                                                        if ($cs_plugin_options['cs_confir_email'] && $cs_plugin_options['cs_confir_email'] != '') {
                                                                            echo '<li><i class="icon-mail6"></i>' . __('Email: ', 'rental') . '<a href="mailto:' . $cs_plugin_options['cs_confir_email'] . '&subject=hello">' . __($cs_plugin_options['cs_confir_email'], 'rental') . '</a></li>';
                                                                        }
                                                                        ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <?php
            } else {
                $cs_plugin_notify->error(__('Oops! direct access is not allowed.', 'rental'));
            }
            ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
