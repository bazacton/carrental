<?php
/**
 *
 * @Search page Template
 *
 */
global $post, $cs_notification, $cs_plugin_options;
$cs_plugin_notify = new CS_Plugin_Notification_Helper();
get_header();

$params['return'] = 'false';

$cs_prev_link = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : esc_url(home_url('/'));
if (isset($_GET['invoice']) && $_GET['invoice'] != '') {
    include('confirmation.php');
} else {
    ?>
    <div class="container">
        <div class="row">
            <div class="section-fullwidth reservation-editor">
                <form action="#" method="post" id="booking_form">
                    <aside class="page-sidebar">
                        <div class="booking-widget  cs-gross-calculation" data-full_pay="<?php echo esc_attr($full_pay); ?>" data-advance="<?php echo esc_attr($cs_advance_deposit); ?>" data-vat_switch="<?php echo esc_attr($cs_vat_switch); ?>" data-vat="<?php echo esc_attr($cs_payment_vat); ?>" data-currency="<?php echo esc_attr($currency_sign); ?>">
                            <h3><?php _e('Your Booking', 'rental'); ?> <a href="<?php echo esc_url($cs_prev_link) ?>"><i class="icon-refresh2"></i></a></h3>
                            <div class="widget-holder cs-reserved-vehicle" style="display:none"></div>
                            <div class="widget-holder pick-drop">
                                <ul class="pick-retrun">
                                    <li> <strong><?php _e('PICKUP', 'rental'); ?></strong> <span><?php echo get_the_title($pickup_location); ?><a class="information" href="#"><i class="icon-info7"></i>
                                                <ul>
                                                    <li>
                                                        <div>
                                                            <h5><?php echo get_the_title($pickup_location); ?></h5>
                                                            <p><?php echo wp_trim_words(get_post_field('post_content', $pickup_location), 8, '...') ?></p>
                                                            <span><?php _e('Call', 'rental'); ?> <?php echo get_post_meta($pickup_location, 'cs_phone_no', true); ?></span> </div>
                                                    </li>
                                                </ul>
                                            </a></span> <span><?php echo date('D j M Y', strtotime($date_from)); ?></span> <em><?php echo date('g:i A', strtotime($start_time)); ?></em> </li>
                                    <?php
                                    if ($station == 'on') {
                                        $dropup_location = $pickup_location;
                                    }
                                    ?>
                                    <li> <strong><?php _e('Return', 'rental'); ?></strong> <span><?php echo get_the_title($dropup_location); ?><a class="information" href="#"><i class="icon-info7"></i>
                                                <ul>
                                                    <li>
                                                        <div>
                                                            <h5><?php echo get_the_title($dropup_location); ?></h5>
                                                            <p><?php echo wp_trim_words(get_post_field('post_content', $dropup_location), 8, '...') ?></p>
                                                            <span><?php _e('Call', 'rental'); ?> <?php echo get_post_meta($dropup_location, 'cs_phone_no', true); ?></span></div>
                                                    </li>
                                                </ul>
                                            </a></span> <span><?php echo date('D j M Y', strtotime($date_to)); ?></span><em><?php echo date('g:i A', strtotime($end_time)); ?></em> </li>
                                </ul>
                                <ul class="price-list booking-extras"  style="display:none"></ul>
                                <ul class="amount-list" style="display:none">
                                    <li><?php _e('Total Amount', 'rental'); ?><span class="total-price"></span></li>
                                    <li class="cs-vat-percent"><?php _e('Vat ', 'rental'); ?>(<?php echo esc_attr($cs_payment_vat); ?>%)<span class="vat-final-amount"></span></li>
                                </ul>
                            </div>
                            <div class="widget-holder grand-total-wrap" style="display:none">
                                <div class="grand-total"> <span><?php _e('Grand Total', 'rental'); ?></span> <em></em> </div>
                            </div>
                        </div>
                    </aside>
                    <?php
                    $output = '';
                    $cs_vehicles = array();
                    $cs_vehicle_capacity_data = array();
                    $temp_data = array();
                    $cs_args = array('posts_per_page' => '-1', 'post_type' => 'vehicles', 'orderby' => 'ID', 'post_status' => 'publish');
                    $meta_fields_array = array();//array('relation' => 'AND',);
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
                    $width = '350';
                    $height = '263';
                    $title_limit = 46;
                    $excerpt = 255;
                    $flag = false;
                    $data_vehicle = '';
                    wp_car_rental::cs_tagsinput_scripts();
                    $cs_type_data = get_option('cs_type_options');
                    $vehicles_type = isset($cs_type_data[$vehicle_type]['cs_type_name']) ? $cs_type_data[$vehicle_type]['cs_type_name'] : '';
                    ?>
                    <div class="page-content">
                        <section class="page-section">
                            <div class="container">
                                <div class="row">
                                    <div class="element-size-100 cs-reservation-tabs" style="display:none">
                                        <div class="col-md-12">
                                            <ul class="booking-tabs">
                                                <li class="active">
                                                    <a data-id="tab1"><span><i class="icon-plus3"></i></span>
                                                        <h4><em><?php _e('STEP 1', 'rental'); ?></em><?php _e('Add Essential', 'rental'); ?></h4>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a data-id="tab2"><span><i class="icon-database"></i></span>
                                                        <h4><em><?php _e('STEP 2', 'rental'); ?></em><?php _e('Reservation and Payments', 'rental'); ?></h4>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="active in" data-id="tab3"><span><i class="icon-checkmark6"></i></span>
                                                        <h4><em><?php _e('Step 3', 'rental'); ?></em><?php _e('Confirmation', 'rental'); ?></h4>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="element-size-100 cs-search-breadcrumbs" >
                                        <div class="col-md-12">
                                            <div class="cs-main-title">
                                                <h2><?php _e("We've found", 'rental'); ?> <?php echo absint($post_count); ?> <?php echo esc_attr($vehicles_type); ?></h2>
                                                <div class="breadcrumbs">
                                                    <ul>
                                                        <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('Home', 'rental'); ?></a></li>
                                                        <li><a href="javascript:;"><?php _e('Booking', 'rental'); ?></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="element-size-100">
                                        <div class="cs-listing simple-view cs-ajax-listing" data-admin_url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                                            <script>
                                                jQuery(document).ready(function ($) {
                                                    cs_check_vehicle_availabilty();

                                                    jQuery('.emails-tags').tagsinput({
                                                        allowDuplicates: false,
                                                        onTagExists: function (item, $tag) {
                                                            //$tag.hide.fadeIn();
                                                        },
                                                    });

                                                    jQuery('.emails-tags').on('beforeItemAdd', function (event) {
                                                        console.log(event.cancel);
                                                        var _email = event.item;
                                                        if (!validateEmail(_email)) {
                                                            alert('Please enter a valid email address.');
                                                            event.cancel = true;
                                                        }

                                                        // event.item: contains the item
                                                        // event.cancel: set to true to prevent the item getting added
                                                    });
                                                });
                                            </script>
                                            <div class="col-md-12">
                                                <?php
                                                //echo $query->request;
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
                                                            $cs_img_s = wp_get_attachment_image_src($cs_gallery[0], array(150, 150));

                                                            if (isset($cs_img_s['1']) && $cs_img_s['1'] == 150) {
                                                                $thumbnail = CS_FUNCTIONS()->cs_get_post_img($cs_gallery[0], $width, $height);
                                                            } else {
                                                                $thumbnail = wp_car_rental::plugin_url() . '/assets/images/no-img-vechile.jpg';
                                                            }
                                                        }

                                                        if ($thumbnail == '') {
                                                            $thumbnail = wp_car_rental::plugin_url() . '/assets/images/no-image.png';
                                                        }

                                                        $vehicles_type = isset($cs_type_data[$cs_vehicle_type]['cs_type_name']) ? $cs_type_data[$cs_vehicle_type]['cs_type_name'] : '';
														if ( function_exists('icl_t') ) {
															$vehicles_type = icl_t('Vehicle Types', 'Type "' . $vehicles_type . '" - Name field');
														}
                                                        // Day Off Checking
                                                        $is_day_off = cs_check_day_off($pickup_location);

                                                        if ($is_day_off == 'exist') {
                                                            $hide = 'true';
                                                        } else {
                                                            if (isset($station) && $station = 'off' && $dropup_location != '') {
                                                                $is_day_off = cs_check_day_off($dropup_location);

                                                                if ($is_day_off == 'exist') {
                                                                    $hide = 'true';
                                                                }
                                                            }
                                                        }
                                                        //Time Availabilty
                                                        if ($hide == 'false') {
                                                            $is_time_available = cs_check_time_availabilty($pickup_location);
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
                                                        ?>
                                                        <article class="<?php echo absint($cs_post_id); ?>">
                                                            <div class="cs-media">
                                                                <figure><a data-toggle="modal" data-target="#car-rental-popup-<?php echo absint($cs_post_id); ?>" href="<?php echo get_the_permalink($cs_post_id); ?>"><img src="<?php echo esc_url($thumbnail); ?>" alt=""  /></a>

                                                                </figure>
                                                            </div>
                                                            <div class="listing-text">
                                                                <?php if ($vehicles_type <> '') { ?>
                                                                    <span class="cs-categroies"><?php echo esc_attr($vehicles_type); ?></span>
                                                                <?php } ?>
                                                                <h3><?php echo get_the_title($cs_post_id); ?></h3>

                                                                <!-- Features-->
                                                                <?php
                                                                $featureList = get_post_meta($cs_post_id, 'cs_vehicle_features', true);

                                                                $cs_feature_options = isset($cs_plugin_options['cs_feats_options']) ? $cs_plugin_options['cs_feats_options'] : '';
                                                                $cs_output = '';
                                                                if (is_array($cs_feature_options) && sizeof($cs_feature_options) > 0) {
                                                                    $counter = 0;
                                                                    echo '<ul class="cs-user-info">';
                                                                    foreach ($cs_feature_options as $feature) {
                                                                        $feature_title = $feature['cs_feats_title'];
                                                                        $feature_image = $feature['cs_feats_image'];
                                                                        $feature_slug = isset($feature['feats_id']) ? $feature['feats_id'] : '';
                                                                        $checked = '';
                                                                        $cs_image = '';
																		
																		if ( function_exists('icl_t') ) {
																			$feature_title = icl_t('Vehicle Features', 'Feature "' . $feature_title . '" - Title field');
																		}
																		
                                                                        if (isset($feature_image) && $feature_image != '') {
                                                                            $cs_image = '<img src="' . esc_url($feature_image) . '" alt="" />';
                                                                        } else {
                                                                            $cs_image = '<i>&nbsp;</i>';
                                                                        }
                                                                        if (is_array($featureList) && in_array($feature_slug, $featureList)) {
                                                                            $counter++;
                                                                            if ($counter < 4) {
                                                                                echo '<li><a href="javascript:;">' . $cs_image . wp_trim_words($feature_title, 3) . '</a></li>';
                                                                            }
                                                                        }
                                                                    }
                                                                    echo '</ul>';
                                                                }

                                                                // Properties
                                                                $propertyList = get_post_meta($cs_post_id, 'cs_vehicle_properties', true);

                                                                $cs_property_options = isset($cs_plugin_options['cs_properties_options']) ? $cs_plugin_options['cs_properties_options'] : '';
                                                                $cs_output = '';
                                                                if (is_array($cs_property_options) && sizeof($cs_property_options) > 0 && is_array($propertyList) && !empty($propertyList)) {
                                                                    $counter = 0;
                                                                    echo ' <ul class="facility-list">';
                                                                    foreach ($cs_property_options as $property) {
                                                                        $property_title = $property['cs_properties_title'];
                                                                        $property_slug = isset($property['properties_id']) ? $property['properties_id'] : '';
                                                                        $checked = '';
																		if ( function_exists('icl_t') ) {
																			$property_title = icl_t('Vehicle Facilities', 'Facility "' . $property_title . '" - Title field');
																			//$property_desc = icl_t('Vehicle Facilities', 'Facility "' . $property_desc . '" - Description field');
																		}
                                                                        if (is_array($propertyList) && in_array($property_slug, $propertyList)) {
                                                                            $counter++;
                                                                            echo '<li>' . wp_trim_words($property_title, 3) . '</li>';
                                                                        }
                                                                    }
                                                                    echo '</ul>';
                                                                }
                                                                ?>
                                                                <div class="price-box pull-left">
                                                                    <div class="current-price">
                                                                        <span><?php _e('Price', 'rental'); ?><em class="new-price"><?php cs_get_pricing_breakdown($cs_post_id, 'price'); ?></em></span>
                                                                        <?php cs_get_pricing_breakdown($cs_post_id, 'breakdown'); ?>

                                                                    </div>
                                                                </div>

                                                                <div class="info-btn pull-right cs-book-vehicle"> 
                                                                    <a href="javascript:;" data-toggle="modal" data-target="#report-vehicle-<?php echo absint($cs_post_id); ?>" class="mail-btn"><i class="icon-mail"></i></a>
                                                                    <?php cs_quick_inquiry($cs_post_id); ?>
                                                                    <?php if (isset($hide) && $hide == 'false') { ?>
                                                                        <a href="javascript:;" data-vehicle="<?php echo esc_attr($data_vehicle); ?>" data-type="<?php echo esc_attr($cs_vehicle_type); ?>" data-post="<?php echo absint($cs_post_id); ?>" class="book-btn reserve-vehicle-btn"><?php _e('Book Now', 'rental'); ?></a> </div>
                                                                    <?php } ?>
                                                            </div>
                                                            <?php echo cs_get_detail($cs_post_id, 'breakdown'); ?>
                                                            <!--Invite-->
                                                            <?php cs_invite_form($cs_post_id); ?>
                                                            <!--Invite-->
                                                        </article>
                                                        <?php
                                                    endwhile;

                                                    wp_reset_postdata();
                                                } else {
                                                    $cs_plugin_notify->error(__('No Vehicle found', 'rental'));
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </form>
            </div>  
        </div>
    </div>
    <?php
} // Booking
get_footer();
