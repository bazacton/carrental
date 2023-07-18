<?php
/**
 *
 * @Get Event Address
 * @return
 */
if (!function_exists('cs_get_vehicle_address')) {

    function cs_get_vehicle_address($address = '', $limit = 35) {
        return substr($address, 0, $limit);
        if (strlen($address) > $limit) {
            echo '...';
        }
    }

}

/**
 *
 * @Get Vehicle Title
 * @return
 */
if (!function_exists('cs_get_vehicle_title')) {

    function cs_get_vehicle_title($title = '', $limit = 35) {
        return substr($title, 0, $limit);
        if (strlen($title) > $limit) {
            echo '...';
        }
    }

}

/**
 *
 * @Get Price Section
 * @return
 */
if (!function_exists('cs_get_price_section')) {

    function cs_get_price_section($post_id = '') {
        global $cs_plugin_options;
        $cs_vehicle_type = '';
        $cs_vehicle_type = get_post_meta($post_id, 'cs_vehicle_type', true);
        $cs_price = get_post_meta($post_id, 'cs_vehicle_starting_price', true);
        ?>
        <div class="short-info">
            <?php if (isset($cs_price) && $cs_price != '') { ?>
                <div class="cs-price"><small><?php _e('Starts from', 'rental'); ?></small> <span><?php echo esc_attr($cs_plugin_options['currency_sign'] . $cs_price); ?></span> </div>
            <?php } ?>
            <a href="<?php echo get_the_permalink($post_id); ?>" class="booking-btn cscolor csborder-color"><?php _e('Vehicle Detail', 'rental'); ?> <i class="icon-arrow-circle-right"></i></a>
        </div>
        <?php
    }

}

/**
 *
 * @Get Total Rating
 * @return
 */
if (!function_exists('cs_vehicle_rating')) {

    function cs_vehicle_rating($cs_post_id = '', $return = false) {
        $vehicle_rating = '';
        $vehicle_rating = cs_get_total_rating($cs_post_id);
        $cs_reviews_count = cs_total_reviews($cs_post_id);
        if (isset($vehicle_rating)) {
            $vehicle_rating = $vehicle_rating * 20;
        } else {
            $vehicle_rating = 0;
        }

        $rating = '<div class="rating-section">
					<div class="accomodation-rating">
						<span style="width:' . absint($vehicle_rating) . '%" class="rating-box"></span>
					</div>
					<small>(' . $cs_reviews_count . ' ' . __('reviews', 'rental') . ')</small>
				  </div>';
        if ($return == true) {
            return $rating;
        } else {
            echo esc_html($rating);
        }
    }

}

/**
 *
 * @Get Total Rating Detail
 * @return
 */
if (!function_exists('cs_vehicle_rating_detail')) {

    function cs_vehicle_rating_detail($cs_post_id = '') {
        $vehicle_rating = '';
        $vehicle_rating = cs_get_total_rating($cs_post_id);
        $cs_reviews_count = cs_total_reviews($cs_post_id);
        if (isset($vehicle_rating)) {
            $vehicle_rating = $vehicle_rating * 20;
        } else {
            $vehicle_rating = 0;
        }

        echo '<div class="cs-custom-rating">
				  <div class="cs-rating"><span style="width:' . absint($vehicle_rating) . '%" class="rating-box"></span></div>
				  <span class="review-rating">(' . $cs_reviews_count . ' ' . __('reviews', 'rental') . ')</span>
			  </div>';
    }

}


/**
 *
 * @Get User Avatar 
 * @return
 */
if (!function_exists('cs_get_user_avatar')) {

    function cs_get_user_avatar($size = 0, $cs_user_id = '') {

        if ($cs_user_id != '') {
            $cs_user_avatars = get_the_author_meta('user_avatar_display', $cs_user_id);

            if (is_array($cs_user_avatars) && isset($cs_user_avatars[$size])) {
                return $cs_user_avatars[$size];
            } else if (!is_array($cs_user_avatars) && $cs_user_avatars <> '') {
                return $cs_user_avatars;
            }
        }
    }

}

/**
 *
 * @Check Value exist
 * @return
 */
if (!function_exists('cs_check_name_availabilty')) {

    function cs_check_name_availabilty() {
        global $post;
        $json = array();
        $cs_field_name = $_REQUEST['name'];
        $cs_temp_names = array();

        $form_field_names = isset($_REQUEST['cs_vehicle_meta']) ? $_REQUEST['cs_vehicle_meta'] : array();

        $length = count(array_keys($form_field_names, $cs_field_name));

        if ($cs_field_name == '') {
            $json['type'] = 'error';
            $json['message'] = '<i class="icon-times"></i>';
        } else {
            if (preg_match('/\s/', $cs_field_name)) {
                $json['type'] = 'error';
                $json['message'] = '<i class="icon-times"></i>';
                echo json_encode($json);
                die();
            }

            if (in_array(trim($cs_field_name), $form_field_names) && $length > 1) {
                $json['type'] = 'error';
                $json['message'] = '<i class="icon-times"></i>';
            } else {
                $json['type'] = 'success';
                $json['message'] = '<i class="icon-checkmark6"></i>';
            }
        }
        echo json_encode($json);
        die();
    }

    add_action('wp_ajax_cs_check_name_availabilty', 'cs_check_name_availabilty');
}

/*
 *
 * @Vehicles Flex Slider
 * @retrun
 *
 */
if (!function_exists('cs_vehicles_flex_slider')) {

    function cs_vehicles_flex_slider($sliderData, $thumbArray, $is_thumb) {
        global $cs_node, $post, $cs_theme_option;
        $cs_post_counter = rand(40, 9999999);
        ?>
        <!-- Flex Slider -->
        <div id="slider-<?php echo esc_attr($cs_post_counter); ?>" class="flexslider">
            <ul class="slides">
                <?php
                $cs_counter = 1;
                $cs_title_counter = 0;
                foreach ($sliderData as $as_node) {

                    echo '<li>
						<figure>
							<a href="' . esc_url($as_node) . '" title="" data-rel="prettyPhoto[gallery]"><img src="' . esc_url($as_node) . '" alt="" title=""></a>
						</figure>
						
				</li>';
                    $cs_title_counter++;
                    $cs_counter++;
                }
                ?>
                <!-- items mirrored twice, total of 12 -->
            </ul>
        </div>
        <?php if (isset($is_thumb) && $is_thumb == 'true') { ?>
            <div id="carousel-<?php echo esc_attr($cs_post_counter); ?>" class="carousel">
                <ul class="slides">
                    <?php
                    $cs_counter = 1;
                    $cs_title_counter = 0;
                    foreach ($thumbArray as $as_node) {
                        echo '<li>
						<figure>
							<img src="' . esc_url($as_node) . '" alt="" title="">';
                        ?>
                        </figure>
                        </li>
                        <?php
                        $cs_title_counter++;
                        $cs_counter++;
                    }
                    ?>
                </ul>
            </div>
        <?php
        }
        cs_enqueue_flexslider_script();
        ?>
        <!-- Flex Slider Javascript Files -->
        <script type="text/javascript">
            jQuery(window).load(function () {
                // The slider being synced must be initialized first
                var target_flexslider = jQuery('.flexslider');
        <?php if (isset($is_thumb) && $is_thumb == 'true') { ?>
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

}?>