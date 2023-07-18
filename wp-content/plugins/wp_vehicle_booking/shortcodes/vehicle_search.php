<?php
/**
 * @Spacer html form for page builder
 */
if ( ! function_exists( 'cs_vehicle_search_shortcode' ) ) {

    function cs_vehicle_search_shortcode( $atts, $content = "" ) {
        global $cs_border, $cs_plugin_options;

        $defaults = array( 'cs_vehicle_search_section_title' => '' );
        extract( shortcode_atts( $defaults, $atts ) );

        $cs_vehicle_search_section_title = $cs_vehicle_search_section_title ? $cs_vehicle_search_section_title : '';
        $cs_args = array( 'posts_per_page' => '-1', 'post_type' => 'locations', 'orderby' => 'ID', 'post_status' => 'publish' );
        $cust_query = get_posts( $cs_args );
        ?>
        <div class="col-md-12"  style="padding-top:340px; padding-bottom:190px">
            <div class="col-md-12 banner-search vehicle-search widget-searchform ">
                <?php
                $cs_page_id = isset( $cs_plugin_options['cs_reservation'] ) && $cs_plugin_options['cs_reservation'] != '' && absint( $cs_plugin_options['cs_reservation'] ) ? $cs_plugin_options['cs_reservation'] : '';
                wp_car_rental::cs_enqueue_datepicker_script();
                $search_link = add_query_arg( array( 'action' => 'booking' ), esc_url( get_permalink( $cs_page_id ) ) );
                $cs_vehicle_types = get_option( 'cs_type_options' );
                wp_car_rental::cs_enqueue_datepicker_script();
                ?>

                <h2><?php echo esc_html( $cs_vehicle_search_section_title ) ?></h2>
                <form class="form-reviews" method="post" action="<?php echo esc_url( $search_link ); ?>" id="vehicle-seach">
                    <div class="vehicle-type-wrap">
                        <!--                        <ul class="tab-list">-->
                        <ul class="cs-vehicle-radio">
                            <?php
                            $cs_type_data = get_option( "cs_type_options" );
                            if ( isset( $cs_type_data ) && is_array( $cs_type_data ) && ! empty( $cs_type_data ) ) {
                                $counter = 0;
                                foreach ( $cs_type_data as $key => $type ) {
                                    $counter ++;
                                    $checked = $counter == 1 ? 'checked="checked"' : '';
                                    $active = $counter == 1 ? 'active' : '';
                                    if ( isset( $type['cs_type_image'] ) && ! empty( $type['cs_type_image'] ) ) {
                                        $image = '<img src="' . esc_url( $type['cs_type_image'] ) . '" alt="" />';
                                    } else {
                                        $image = '';
                                    }

                                    $vehicle_name = isset( $type['cs_type_name'] ) ? $type['cs_type_name'] : '';
                                    if ( function_exists( 'icl_t' ) ) {
                                        $vehicle_name = icl_t( 'Vehicle Types', 'Type "' . $vehicle_name . '" - Name field' );
                                    }

                                    echo '<li class="' . $active . '">' . $image . '<input name="vehicle-type" ' . $checked . ' type="radio" id="type_' . $key . '" value="' . $key . '" /><label for="type_' . $key . '">' . $vehicle_name . '</label></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="profile-setting tab-content">
                        <div class="tab-area tab-pane fade active in" id="cs-tab-education3520">
                            <div class="select-holder dp-margin">
                                <select name="pickup_location" class="pickup_location">
                                    <option value="">
                                        <?php _e( 'Select PickUp Location', 'rental' ) ?>
                                    </option>
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
                            <div class="select-holder dropup-wrap dp-margin" style="display:none">
                                <select class="dropup_location" name="dropup_location">
                                    <option value="">
                                        <?php _e( 'Please Drop Up Location', 'rental' ) ?>
                                    </option>
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
                            <div class="col-md-12 check-box">
                                <input type="hidden" value="off" name="station">
                                <input type="checkbox" checked="checked" value="on" name="station" id="station" class="station">
                                <label for="station">
                                    <?php _e( 'Return car to the same station', 'rental' ) ?>
                                </label>
                            </div>
                            <div class="col-md-6 pick-date">
                                <h6>
                                    <?php _e( 'Pick up date & time', 'rental' ) ?>
                                </h6>
                                <div class="date-holder">
                                    <div class="date cs-calendar-combo">
                                        <input type="text" class="pickup_date" name="pickup_date" value="<?php echo date( 'd.m.Y' ); ?>" placeholder="<?php echo date( 'd.m.Y' ); ?>">
                                    </div>
                                    <div class="time">
                                        <input type="text" class="pickup_time" name="pickup_time" value="<?php echo date( 'H:i A' ); ?>" placeholder="<?php echo date( 'H:i A' ); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 pick-date">
                                <h6>
                                    <?php _e( 'Drop date & time', 'rental' ) ?>
                                </h6>
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
                                    <input type="checkbox" value="on" name="aged" id="aged" class="aged">
                                    <label for="aged">
                                        <?php _e( 'Driver aged between 25 – 70?', 'rental' ) ?>
                                    </label>
                                </div>
                                <input type="submit" class="btn-search seach_vehicle_btn" value="<?php _e( 'Search Car', 'rental' ) ?>" />
                            </div>
                        </div>

                        <script type="text/javascript">
                            jQuery(document).ready(function ($) {
                                cs_widget_script();
                            });
                        </script>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    add_shortcode( 'cs_vehicle_search', 'cs_vehicle_search_shortcode' );
}