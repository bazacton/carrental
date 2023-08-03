<?php
/**
 * Vehicle Listing fancy
 *
 */
global $vehicle_excerpt, $category, $vehicle_type, $vehicle_price, $vehicle_type, $cs_plugin_options;
$cs_plugin_notify = new CS_Plugin_Notification_Helper();
$width = '350';
$height = '263';
$title_limit = 46;
?>

<div class="cs-listing fancy-view simple-view" data-admin_url="<?php echo admin_url('admin-ajax.php'); ?>">
    <div class="col-md-12">
        <?php
        $query = new WP_Query($args);

        $post_count = $query->post_count;
        if ($query->have_posts()) {
            $postCounter = 0;
            while ($query->have_posts()) : $query->the_post();
                $thumbnail = '';
                $cs_post_id = $post->ID;
                $cs_postObject = get_post_meta(get_the_id(), "cs_array_data", true);

                $cs_gallery = get_post_meta($post->ID, "cs_vehicle_image_gallery", true);
                $cs_vehicle_price = get_post_meta($post->ID, "cs_vehicle_price", true);


                $cs_vehicle_type = get_post_meta($post->ID, 'cs_vehicle_type', true);
                $cs_gallery = explode(',', $cs_gallery);

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

                $cs_type_data = get_option("cs_type_options");
                $cs_vehicle_type = isset($cs_type_data[$cs_vehicle_type]['cs_type_name']) ? $cs_type_data[$cs_vehicle_type]['cs_type_name'] : '';
				if ( function_exists('icl_t') ) {
					$cs_vehicle_type = icl_t('Vehicle Types', 'Type "' . $cs_vehicle_type . '" - Name field');
				}
                $excerpt_data = CS_FUNCTIONS()->cs_get_the_excerpt($vehicle_excerpt, 'false', 'Read More');
                $currency_sign = isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] != '' ? $cs_plugin_options['currency_sign'] : '$';
                ?>
                <article>
                    <div class="cs-media">
                        <figure> <a data-toggle="modal" data-target="#car-rental-popup-<?php echo absint($cs_post_id); ?>" href="javascript:;"><img src="<?php echo esc_url($thumbnail); ?>" alt=""  /></a> <?php echo cs_get_detail($cs_post_id, 'starting'); ?></figure>
                    </div>
                    <div class="listing-text">
                        <div class="fancy-left">
                            <?php if (trim($cs_vehicle_type) != '') { ?>
                                <span class="cs-categroies"><?php echo esc_attr($cs_vehicle_type); ?></span>
                            <?php } ?>
                            <h3><a href="javascript:;"  data-toggle="modal" data-target="#car-rental-popup-<?php echo absint($cs_post_id); ?>">
                                    <?php the_title(); ?>
                                </a></h3>
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
                                        if ($counter <= 6) {
                                            echo '<li><a href="javascript:;">' . $cs_image . wp_trim_words($feature_title, 3) . '</a></li>';
                                        }
                                    }
                                }
                                echo '</ul>';
                            }
                            ?>
                        </div>
                        <div class="fancy-right">
                            <div class="current-price"> <span>
                                    <?php _e('Price Starts From', 'rental'); ?>
                                    <em class="new-price"> <?php echo esc_html($currency_sign . $cs_vehicle_price); ?></em></span> </div>
                            <div class="info-btn"> <a href="javascript:;" data-toggle="modal" data-target="#car-booking-popup-div" class="book-btn">
                                    <?php _e('Book Now', 'rental'); ?>
                                </a> </div>
                        </div>
                    </div>
                    <div class="fancy-btm">
                        <?php
                        $propertyList = get_post_meta($cs_post_id, 'cs_vehicle_properties', true);
                        $cs_property_options = isset($cs_plugin_options['cs_properties_options']) ? $cs_plugin_options['cs_properties_options'] : '';
                        $cs_output = '';
                        if (is_array($cs_property_options) && sizeof($cs_property_options) > 0) {
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
                        <a href="javascript:;" data-toggle="modal" data-target="#report-vehicle-<?php echo absint($cs_post_id); ?>" class="email"><i class="icon-mail"></i>
                            <?php _e('Email me a quote', 'rental'); ?>
                        </a>
                        <?php cs_quick_inquiry($cs_post_id); ?>
                        <!--Invite-->
                        <?php cs_invite_form($cs_post_id); ?>
                        <!--Invite--> 
                    </div>
                </article>
                <?php
            endwhile;
            ?>

            <!--Bookign Form-->
            <?php cs_get_booking_form('div'); ?>
            <!--Bookign Form-->

            <?php
        } else {
            $cs_plugin_notify->error(__('No Vehicles found', 'rental'));
        }
        ?>

    </div>
</div>
