<?php
/**
 * The template for location Detail
 */
global $post, $cs_plugin_options, $location_map;
$cs_uniq = rand(11111111, 99999999);
get_header();
$width = 818;
$height = 460;
$cs_sidebar_left = get_post_meta($post->ID, 'cs_single_location_layout', true);
$cs_content_class = 'page-content-fullwidth';

wp_car_rental::cs_prettyphoto_script();
$cs_postid = get_the_id();
?>

<div class="container">
    <div class="row">
        <div class="section-fullwidth blog-editor">
            <div class="<?php echo sanitize_html_class($cs_content_class) ?>">
                <div class="page-content">
                    <div class="page-section">
                        <div class="container">
                            <div class="row">
                                <?php
                                while (have_posts()) : the_post();
                                    $cs_cus_post_id = $post->ID;
                                    $cs_location_address = get_post_meta($post->ID, 'cs_location_address', true);
                                    $cs_email = get_post_meta($post->ID, 'cs_email', true);
                                    $cs_phone_no = get_post_meta($post->ID, 'cs_phone_no', true);
                                    $image_url = cs_get_post_img_src($post->ID, $width, $height);
                                    $week_day = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

                                    $start_time = get_post_meta($post->ID, 'cs_location_start_time', false);
                                    $cs_contact_form = get_post_meta($post->ID, 'cs_contact_form', true);
                                    $end_time = get_post_meta($post->ID, 'cs_location_end_time', false);
                                    $cs_days = get_post_meta($post->ID, 'cs_days_data', false);
                                    $start_time = $start_time[0];
                                    $end_time = $end_time[0];
                                    $cs_days = $cs_days[0];
                                    ?>

                                    <div class="section-fullwidth">
                                        <div class="element-size-100">
                                            <div class="col-md-12">
                                                <div class="cs-main-title">
                                                    <h2><?php the_title(); ?></h2>
                                                    <?php
                                                    if (function_exists('cs_breadcrumbs')) {
                                                        cs_breadcrumbs();
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="element-size-100">
                                            <div class="cs-location-list">
                                                <div class="col-md-12">
                                                    <ul class="location-detail">
                                                        <li>
                                                            <?php if ($image_url <> '') { ?>
                                                                <div class="company-logo">
                                                                    <figure> <img src="<?php echo esc_url($image_url) ?>" alt=""> </figure>
                                                                </div>
                                                            <?php } if ($cs_location_address <> '') { ?>
                                                                <div class="loaction-address-inner"> <i class="icon-location6"></i> <?php echo esc_html($cs_location_address) ?> </div>
                                                            <?php } if ($cs_phone_no <> '') { ?>
                                                                <div class="location-number-inner"> <i class="icon-phone6"></i>
                                                                    <div class="phone-number-inner"><?php echo esc_html($cs_phone_no) ?><span><?php echo sanitize_email($cs_email) ?></span></div>
                                                                </div>
                                                            <?php } ?>
                                                        </li>
                                                    </ul>
                                                    <div class="cs-station-featuers">
                                                        <?php the_content(); ?>
                                                    </div>
                                                </div>
                                                <?php
                                                $args = array(
                                                    'post_type' => 'vehicles',
                                                    'post_status' => 'publish',
                                                );

                                                include(wp_car_rental::plugin_dir() . 'vehicles/vehicles-slider-view.php');
                                                ?>
                                                <div class="element-size-33">
                                                    <div class="col-md-12">
                                                        <div class="monthly-permotion">
                                                            <div class="cs-section-title">
                                                                <h5>
                                                                    <?php _e('Monthly Promotions', 'rental'); ?>
                                                                </h5>
                                                            </div>
                                                            <ul class="monthly-permotion-list">
                                                                <?php
                                                                $counter = 0;
                                                                foreach ($week_day as $key => $day) {
                                                                    $counter++;

                                                                    $cs_day_status = isset($cs_days[strtolower($day)]) ? $cs_days[strtolower($day)] : 'off';
                                                                    $checked = '';

                                                                    $disabled = 'range-day-disabled';
                                                                    if ($cs_day_status == 'on') {
                                                                        $checked = 'checked="checked"';
                                                                        $disabled = '';
                                                                    }
                                                                    $cs_start_minuts = CS_FUNCTIONS()->cs_hoursToMinutes($start_time[$counter]);
                                                                    $cs_end_minuts = CS_FUNCTIONS()->cs_hoursToMinutes($end_time[$counter]);
                                                                    ?>
                                                                    <li><?php echo esc_attr($day); ?> <span><i class="icon-clock7"></i>
                                                                            <?php
                                                                            if ($cs_day_status == 'on') {
                                                                                echo esc_attr($start_time[$counter]);
                                                                                ?>
                                                                                - <?php
                                                                                echo esc_attr($end_time[$counter]);
                                                                            } else {
                                                                                _e('Closed', 'rental');
                                                                            }
                                                                            ?></span></li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="element-size-67">
                                                    <div class="col-md-12">
                                                        <div class="cs-section-title">
                                                            <h5> <?php _e('Find Us on Map', 'rental'); ?></h5>
                                                        </div>
                                                        <div class="map">
                                                            <?php CS_FUNCTIONS()->cs_location_fields($cs_postid); ?>
                                                            <div class="col-md-12"> </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if ($cs_contact_form <> '') { ?>
                                                    <div class="element-size-100">
                                                        <div class="col-md-12">
                                                            <?php
                                                            echo do_shortcode($cs_contact_form);
                                                            ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        ?>
                    </div>
                </div>
                <aside class="page-sidebar">
                    <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('location_sidebar')) : endif; ?>
                </aside>
            </div>
        </div>
    </div>
</div>
</div>
<?php
get_footer();



