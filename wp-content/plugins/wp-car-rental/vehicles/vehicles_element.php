<?php
/**
 * @Vehicles
 * @return html
 *
 */
if (!function_exists('cs_pb_vehicles')) {

    function cs_pb_vehicles($die = 0) {
        global $cs_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $counter = $_POST['counter'];
        $cs_counter = $_POST['counter'];
        if (isset($_POST['action']) && !isset($_POST['shortcode_element_id'])) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes($shortcode_element_id);
            $PREFIX = 'cs_vehicles';
            $parseObject = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes($output, $shortcode_str, true, $PREFIX);
        }

        $defaults = array('column_size' => '1/1', 'section_title' => '', 'vehicle_type' => '', 'view' => 'style-1', 'orderby' => 'ID', 'vehicle_excerpt' => '255', 'vehicles_pagination' => 'pagination', 'vehicle_num_post' => '10', 'filterable' => 'show');
        if (isset($output['0']['atts']))
            $atts = $output['0']['atts'];
        else
            $atts = array();
        $vehicles_element_size = '50';
        foreach ($defaults as $key => $values) {
            if (isset($atts[$key]))
                $$key = $atts[$key];
            else
                $$key = $values;
        }
        $name = 'cs_pb_vehicles';
        $coloumn_class = 'column_' . $vehicles_element_size;
        if (isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode') {
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
        ?>
        <div id="<?php echo esc_attr($name . $cs_counter); ?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class); ?> <?php echo esc_attr($shortcode_view); ?>" item="blog" data="<?php echo element_size_data_array_index($vehicles_element_size) ?>">
            <?php cs_element_setting($name, $cs_counter, $vehicles_element_size); ?>
            <div class="cs-wrapp-class-<?php echo intval($cs_counter); ?> <?php echo esc_attr($shortcode_element); ?>" id="<?php echo esc_attr($name . $cs_counter); ?>" data-shortcode-template="[cs_vehicles {{attributes}}]"  style="display: none;">
                <div class="cs-heading-area">
                    <h5><?php _e('Edit Vehicles Options', 'rental') ?></h5>
                    <a href="javascript:cs_remove_overlay('<?php echo esc_attr($name . $cs_counter) ?>','<?php echo esc_attr($filter_element); ?>')" class="cs-btnclose"><i class="fa fa-times"></i></a> </div>
                <div class="cs-pbwp-content">
                    <div class="cs-wrapp-clone cs-shortcode-wrapp">
                        <?php if (isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode') {
                            cs_shortcode_element_size();
                        } ?>
                        <ul class="form-elements">
                            <li class="to-label"><label><?php _e('Section Title', 'rental') ?></label></li>
                            <li class="to-field">
                                <input  name="section_title[]" type="text"  value="<?php echo esc_attr($section_title); ?>"   />
                            </li>                  
                        </ul>
                        <ul class="form-elements">
                            <li class="to-label">
                                <label><?php _e('View', 'rental') ?></label>
                            </li>
                            <li class="to-field select-style">
                                <select name="view[]" class="dropdown">
                                    <option <?php if ($view == "default") echo "selected"; ?> value="default"><?php _e('Default', 'rental') ?></option>
                                    <option <?php if ($view == "detailed") echo "selected"; ?> value="detailed" ><?php _e('Detailed', 'rental') ?></option>
                                </select>
                            </li>
                        </ul>
                        <ul class="form-elements">
                            <li class="to-label">
                                <label><?php _e('Vehicle Type', 'rental') ?></label>
                            </li>
                            <li class="to-field select-style">
                                <select name="vehicle_type[]" class="dropdown">
                                    <option value=""><?php _e('Select Vehicle Type', 'rental') ?></option>
                                    <?php
                                    $cs_type_data = get_option("cs_type_options");

                                    if (isset($cs_type_data) && is_array($cs_type_data) && !empty($cs_type_data)) {
                                        foreach ($cs_type_data as $key => $type) {
                                            $selected = '';
                                            if ($vehicle_type == $key) {
                                                $selected = 'selected="selected"';
                                            }

                                            echo '<option value="' . $key . '" ' . $selected . ' >' . $type['cs_type_name'] . '</option> ';
                                        }
                                    }
                                    ?>
                                </select>
                            </li>
                        </ul>
                        <ul class="form-elements">
                            <li class="to-label">
                                <label><?php _e('Filterable', 'rental') ?></label>
                            </li>
                            <li class="to-field select-style">
                                <select name="filterable[]" class="dropdown">
                                    <option <?php if ($filterable == "show") echo "selected"; ?> value="show"><?php _e('Show', 'rental') ?></option>
                                    <option <?php if ($filterable == "hide") echo "selected"; ?> value="hide" ><?php _e('Hide', 'rental') ?></option>
                                </select>
                            </li>
                        </ul>
                        <ul class="form-elements">
                            <li class="to-label">
                                <label><?php _e('Post Order', 'rental') ?></label>
                            </li>
                            <li class="to-field">
                                <div class="input-sec">
                                    <div class="select-style">
                                        <select name="order[]" class="dropdown" >
                                            <option <?php if ($order == "ASC") echo "selected"; ?> value="ASC"><?php _e('Asc', 'rental') ?></option>
                                            <option <?php if ($order == "DESC") echo "selected"; ?> value="DESC"><?php _e('DESC', 'rental') ?></option>
                                        </select>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="form-elements">
                            <li class="to-label">
                                <label><?php _e('Length of Excerpt', 'rental') ?></label>
                            </li>
                            <li class="to-field">
                                <div class="input-sec">
                                    <input type="text" name="vehicle_excerpt[]" class="txtfield" value="<?php echo esc_attr($vehicle_excerpt); ?>" />
                                </div>
                                <div class="left-info">
                                    <p><?php _e('Enter number of character for short description text', 'rental') ?></p>
                                </div>
                            </li>
                        </ul>
                        <ul class="form-elements">
                            <li class="to-label">
                                <label><?php _e('Pagination', 'rental') ?></label>
                            </li>
                            <li class="to-field select-style">
                                <select name="vehicles_pagination[]" class="dropdown">
                                    <option <?php if ($vehicles_pagination == "pagination") echo "selected"; ?> value="pagination"><?php _e('Pagination', 'rental') ?></option>
                                    <option <?php if ($vehicles_pagination == "single_page") echo "selected"; ?> value="single_page" ><?php _e('Single Page', 'rental') ?></option>
                                </select>
                                <div class="left-info">
                                    <p><?php _e('Pagination will not work in slider view.', 'rental') ?></p>
                                </div>
                            </li>
                        </ul>
                        <ul class="form-elements">
                            <li class="to-label">
                                <label><?php _e('No. of Post Per Page', 'rental') ?></label>
                            </li>
                            <li class="to-field">
                                <div class="input-sec">
                                    <input type="text" name="vehicle_num_post[]" class="txtfield" value="<?php echo esc_attr($vehicle_num_post); ?>" />
                                </div>
                                <div class="left-info">
                                    <p><?php _e('To display all the records, leave this field blank', 'rental') ?></p>
                                </div>
                            </li>
                        </ul>
        <?php if (isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode') { ?>
                            <ul class="form-elements insert-bg">
                                <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo esc_js(str_replace('cs_pb_', '', $name)); ?>', '<?php echo esc_js($name . $cs_counter); ?>', '<?php echo esc_js($filter_element); ?>')" ><?php _e('Insert', 'rental') ?></a> </li>
                            </ul>
                            <div id="results-shortocde"></div>
        <?php } else { ?>
                            <ul class="form-elements">
                                <li class="to-label"></li>
                                <li class="to-field">
                                    <input type="hidden" name="cs_orderby[]" value="vehicles" />
                                    <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
                                </li>
                            </ul>
        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if ($die <> 1)
            die();
    }

    add_action('wp_ajax_cs_pb_vehicles', 'cs_pb_vehicles');
}