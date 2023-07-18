<?php
/**
 * File Type: Vehicles Shortcode
 */
if (!function_exists('cs_vehicles_listing')) {

    function cs_vehicles_listing($atts, $content = "") {
        global $post, $wpdb, $cs_node, $cs_theme_option, $events_time, $vehicle_excerpt, $vehicle_type, $view, $category, $cs_plugin_notify;
        $defaults = array('column_size' => '1/1', 'section_title' => '', 'vehicle_type' => '', 'view' => 'style-1', 'orderby' => 'ID', 'vehicle_excerpt' => '255', 'vehicles_pagination' => 'pagination', 'vehicle_num_post' => '10', 'filterable' => 'show');
        extract(shortcode_atts($defaults, $atts));
        $coloumn_class = cs_custom_column_class($column_size);
        $cs_dataObject = get_post_meta($post->ID, 'cs_full_data');
        $cs_vehicle_price = get_post_meta($post->ID, "cs_vehicle_price", true);
        $filterable = isset($filterable) ? $filterable : '';
        ob_start();
        $meta_compare = "";
        $meta_key = 'cs_vehicle_type';
        $orderby = 'meta_value';
        $order = 'DESC';

        if (empty($_REQUEST['page_id_all']))
            $_REQUEST['page_id_all'] = 1;
     

        if (isset($_REQUEST['sort']) and $_REQUEST['sort'] == 'asc') {
            $orderby = 'ASC';
        } else if (isset($_REQUEST['sort']) and $_REQUEST['sort'] == 'alphabetical') {
            $orderby = 'title';
            $order = 'ASC';
        } else {
            $order = 'DESC';
        }

        if (isset($_REQUEST['price']) and $_REQUEST['price'] == 'low') {
            $order = 'ASC';
            $meta_key = 'cs_vehicle_price';
        } else if (isset($_REQUEST['price']) and $_REQUEST['price'] == 'high') {
            $order = 'DESC';
            $meta_key = 'cs_vehicle_price';
        }

        if (isset($_REQUEST['vehicle_type']) and $_REQUEST['vehicle_type']) {
            $vehicle_type = $_REQUEST['vehicle_type'];
        } else {
            $vehicle_type = $vehicle_type;
        }

        if (isset($vehicle_type) && $vehicle_type != '') {
            $args = array(
                'posts_per_page' => "-1",
                'post_type' => 'vehicles',
                'post_status' => 'publish',
                'meta_key' => 'cs_vehicle_type',
                'meta_value' => $vehicle_type,
            );
        } else {
            $args = array(
                'posts_per_page' => "-1",
                'post_type' => 'vehicles',
                'post_status' => 'publish',
            );
        }
        
        
        $custom_query = new WP_Query($args);
        $count_post = 0;
        $counter = 1;
        $count_post = $custom_query->post_count;

        if ($vehicle_num_post == '')
            $vehicle_num_post = '-1';

        if (isset($vehicle_type) && $vehicle_type != '') {

            $args = array(
                'posts_per_page' => "$vehicle_num_post",
                'paged' => $_REQUEST['page_id_all'],
                'post_type' => 'vehicles',
                'post_status' => 'publish',
                'meta_key' => $meta_key,
                'orderby' => $orderby,
                'order' => $order,
            );

            $args['meta_query'] = array('relation' => 'AND',);
            $args['meta_query'] = array('key' => 'cs_vehicle_type', 'value' => $vehicle_type, 'compare' => '=');
        } else {
            $args = array(
                'posts_per_page' => "$vehicle_num_post",
                'paged' => $_REQUEST['page_id_all'],
                'post_type' => 'vehicles',
                'meta_key' => $meta_key,
                'post_status' => 'publish',
                'orderby' => $orderby,
                'order' => $order,
            );
        }
        //echo "<pre>"; print_r($args);echo "</pre>";exit;
        if (isset($filterable) && $filterable == 'show') {
            $vehicle_type_array = array(
                'vehicle_type' => $vehicle_type,
                'vehicle_excerpt' => $vehicle_excerpt,
                'vehicle_num_post' => $vehicle_num_post,
            );
        }

        if ($vehicle_type <> '') {
            $cs_type_data = get_option("cs_type_options");
            $cs_vechile = $cs_type_data[$vehicle_type]['cs_type_name'];
			if ( function_exists('icl_t') ) {
				$cs_vechile = icl_t('Vehicle Types', 'Type "' . $cs_vechile . '" - Name field');
			}
        } else {
            $cs_vechile = 'Vechile';
        }

        wp_car_rental::cs_tagsinput_scripts();

        if (isset($section_title) && $section_title != '') {
            echo '<div class="cs-section-title  col-md-12">
						  <h2>' . $section_title . '</h2>
					  </div>';
        }
        if (isset($filterable) and $filterable == "show") {
            echo '<div class="col-md-12">';
            ?>

            <form class="form-reviews" method="GET" action="" id="vehicle-seach" onchange="this.form.submit()">
                <div class="sec-fliter">
                    <ul class="fliter-list">
                        <li>
                            <label><?php _e('Sort by', 'rental'); ?></label>
                            <div class="select-fliter">
                                <select name="sort" onchange="this.form.submit();">
                                    <option value=""><?php _e('Select Type', 'rental') ?></option>
                                    <option value="asc" <?php echo isset($_REQUEST['vehicle_sort']) && $_REQUEST['vehicle_sort'] == 'date' ? 'selected' : ''; ?>><?php _e('Date', 'rental'); ?></option>
                                    <option value="alphabetical" <?php echo isset($_REQUEST['vehicle_sort']) && $_REQUEST['vehicle_sort'] == 'date' ? 'selected' : ''; ?>><?php _e('Alphabetical', 'rental'); ?></option>

                                </select>
                            </div>
                        </li>
                        <li>
                            <label><?php _e('Price', 'rental'); ?></label>
                            <div class="select-fliter">
                                <select name="price" onchange="this.form.submit();">
                                    <option value=""><?php _e('Select Type', 'rental') ?></option>
                                    <option value="low" <?php echo isset($_REQUEST['price']) && $_REQUEST['price'] == 'low' ? 'selected' : ''; ?>><?php _e('Low', 'rental'); ?></option>
                                    <option value="high" <?php echo isset($_REQUEST['price']) && $_REQUEST['price'] == 'high' ? 'selected' : ''; ?>><?php _e('High', 'rental'); ?></option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <label><?php _e('Type:', 'rental'); ?></label>
                            <div class="select-fliter">
                                <select class="vehicle_type" name="vehicle_type" onchange="this.form.submit();">
                                    <option value=""><?php _e('Select Type', 'rental') ?></option>
                                    <?php
                                    $cs_type_data = get_option("cs_type_options");
                                    if (isset($cs_type_data) && is_array($cs_type_data) && !empty($cs_type_data)) {
                                        foreach ($cs_type_data as $key => $type) {
                                            $selected = '';
                                            if (isset($_REQUEST['vehicle_type']) && $_REQUEST['vehicle_type'] == $key) {
                                                $selected = 'selected';
                                            }
											
											$cs_vehicle_type = isset($type['cs_type_name']) ? $type['cs_type_name'] : '';
											if ( function_exists('icl_t') ) {
												$cs_vehicle_type = icl_t('Vehicle Types', 'Type "' . $cs_vehicle_type . '" - Name field');
											}
                                            echo '<option value="' . $key . '" ' . $selected . '>' . $cs_vehicle_type . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </li>
                    </ul>
                </div>

            </form>

            <?php
            echo '</div>';
        }

        if ($view == 'detailed') {
            include( 'vehicles-detailed.php' );
        } elseif ($view == 'default') {
            include( 'vehicles-listing.php' );
        } elseif ($view == 'slider') {
            
        }

        //==Pagination Start
        if ($view != 'slider') {
            if ($count_post > $vehicle_num_post && $vehicle_num_post > 0 && $vehicles_pagination == 'pagination') {
                $qrystr = '';
                if (isset($_REQUEST['page_id']))
                    $qrystr .= "&amp;page_id=" . $_REQUEST['page_id'];
                if (isset($_REQUEST['filter_category']))
                    $qrystr .= "&amp;filter_category=" . $_REQUEST['filter_category'];
                echo cs_pagination($count_post, $vehicle_num_post, $qrystr, 'Show Pagination');
            }
        }
        //==Pagination End
        $eventpost_data = ob_get_clean();
        return $eventpost_data;
    }

    add_shortcode('cs_vehicles', 'cs_vehicles_listing');
}
