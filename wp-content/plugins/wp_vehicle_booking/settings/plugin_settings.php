<?php

/**
 *  File Type: Settings Class
 */
if (!class_exists('cs_plugin_options')) {

    class cs_plugin_options {

        public function __construct() {
            add_action('wp_ajax_cs_add_extra_feature_to_list', array(&$this, 'cs_add_extra_feature_to_list'));
            add_action('wp_ajax_cs_add_feats_to_list', array(&$this, 'cs_add_feats_to_list'));
            add_action('wp_ajax_cs_add_properties_to_list', array(&$this, 'cs_add_properties_to_list'));
            add_action('wp_ajax_cs_add_dyn_reviews_to_list', array(&$this, 'cs_add_dyn_reviews_to_list'));
        }

        //======================================================================
        // Settings Menu Function
        //======================================================================
        public function cs_register_booking_settings() {
            //add submenu page
            add_submenu_page('edit.php?post_type=vehicles', __('Settings', 'rental'), __('Settings', 'rental'), 'manage_options', 'cs_settings', array(&$this, 'cs_settings'));
        }

        //======================================================================
        // Directory Menu Function
        //======================================================================
        public function cs_settings() {
            cs_settings_options_page();
        }

        /* ------------------------------------------------------------
         * Package Extra Features
         * ----------------------------------------------------------- */

        public function cs_extra_feature_section() {
            global $post, $extra_feature_id, $counter_extra_feature, $extra_feature_title, $extra_feature_price, $cs_plugin_options, $extra_feature_type, $extra_feature_guests, $extra_feature_fchange, $extra_feature_desc, $cs_form_fields;
            //$cs_plugin_options	= get_option( "cs_plugin_options" );
            $cs_extra_features_options = $cs_plugin_options['cs_extra_features_options'];
            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';

            $html = '
            <form name="dir-extra_feature" method="post" action="">
			<input type="hidden" name="dynamic_booking_extra_feature" value="1" />
			<script>
                jQuery(document).ready(function($) {
                    $("#total_extra_features").sortable({
						cancel : \'td div.table-form-elem\'
                    });
                });
            </script>
              <ul class="form-elements">
                    <li class="to-label">' . __("Add Extra Feature", "rental") . '</li>
                    <li class="to-button"><a href="javascript:cs_createpop(\'add_extra_feature_title\',\'filter\')" class="button">' . __("Add Extra Feature", "rental") . '</a> </li>
               </ul>
              <div class="cs-list-table">
              <table class="to-table" border="0" cellspacing="0">
                <thead>
                  <tr>
                    <th style="width:80%;">' . __("Title", "rental") . '</th>
                    <th style="width:80%;" class="centr">' . __("Actions", "rental") . '</th>
                    <th style="width:0%;" class="centr"></th>
                  </tr>
                </thead>
                <tbody id="total_extra_features">';
            if (isset($cs_extra_features_options) && is_array($cs_extra_features_options) && count($cs_extra_features_options) > 0) {
                foreach ($cs_extra_features_options as $extra_feature_key => $extra_feature) {
                    if (isset($extra_feature_key) && $extra_feature_key <> '') {
                        $counter_extra_feature = $extra_feature_id = isset($extra_feature['extra_feature_id']) ? $extra_feature['extra_feature_id'] : '';
                        $extra_feature_title = isset($extra_feature['cs_extra_feature_title']) ? $extra_feature['cs_extra_feature_title'] : '';
                        $extra_feature_price = isset($extra_feature['cs_extra_feature_price']) ? $extra_feature['cs_extra_feature_price'] : '';
                        $extra_feature_fchange = isset($extra_feature['cs_extra_feature_fchange']) ? $extra_feature['cs_extra_feature_fchange'] : '';
                        $extra_feature_type = isset($extra_feature['cs_extra_feature_type']) ? $extra_feature['cs_extra_feature_type'] : '';
                        $extra_feature_desc = isset($extra_feature['cs_extra_feature_desc']) ? $extra_feature['cs_extra_feature_desc'] : '';
						
						if (function_exists('icl_register_string')) {
							do_action('wpml_register_single_string', 'Vehicle Extras', 'Extra "' . $extra_feature_title . '" - Title field', $extra_feature_title);
							do_action('wpml_register_single_string', 'Vehicle Extras', 'Extra "' . $extra_feature_desc . '" - Description field', $extra_feature_desc);
						}
						
                        $html .= $this->cs_add_extra_feature_to_list();
                    }
                }
            }
            $html .= '
				</tbody>
              </table>

              </div>
              </form>
              <div id="add_extra_feature_title" style="display: none;">
                <div class="cs-heading-area">
                  <h5><i class="icon-plus-circle"></i> ' . __('Extra Feature Settings', 'rental') . '</h5>
                  <span class="cs-btnclose" onClick="javascript:cs_remove_overlay(\'add_extra_feature_title\',\'append\')"> <i class="icon-times"></i></span> 	
				</div>';
            $html .= $cs_form_fields->cs_form_text_render(
                    array('name' => __('Title', 'rental'),
                        'id' => 'extra_feature_title',
                        'classes' => '',
                        'std' => __('Title', 'rental'),
                        'description' => '',
                        'return' => true,
                        'hint' => ''
                    )
            );

            $html .= $cs_form_fields->cs_form_text_render(
                    array('name' => __('Price', 'rental'),
                        'id' => 'extra_feature_price',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'return' => true,
                        'hint' => ''
                    )
            );

            $html .= $cs_form_fields->cs_form_select_render(
                    array('name' => __('Type', 'rental'),
                        'id' => 'extra_feature_type',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'return' => true,
                        'hint' => '',
                        'options' => array('none' => __('None', 'rental'), 'one-time' => __('One Time', 'rental'), 'daily' => __('Daily', 'rental')),
                    )
            );


            $html .= $cs_form_fields->cs_form_checkbox_render(
                    array('name' => __('Frontend Changeable', 'rental'),
                        'id' => 'extra_feature_fchange',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'return' => true,
                        'hint' => '',
                    )
            );

            $html .= $cs_form_fields->cs_form_textarea_render(
                    array('name' => __('Description', 'rental'),
                        'id' => 'extra_feature_desc',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'return' => true,
                        'hint' => '',
                    )
            );

            $html .= '
                <ul class="form-elements noborder">
                  <li class="to-label"></li>
                  <li class="to-field">
                    <input type="button" value="Add Extra Feature to List" onClick="add_extra_feature_to_list(\'' . esc_js(admin_url('admin-ajax.php')) . '\', \'' . esc_js(wp_car_rental::plugin_url()) . '\')" />
                    <div class="feature-loader"></div>
                  </li>
                </ul>
              </div>';

            echo force_balance_tags($html, true);
        }

        /* ------------------------------------------------------------
         * Package Extra Features List
         * ----------------------------------------------------------- */

        public function cs_add_extra_feature_to_list() {
            global $counter_extra_feature, $cs_plugin_options, $extra_feature_id, $extra_feature_title, $extra_feature_price, $extra_feature_type, $extra_feature_guests, $extra_feature_fchange, $extra_feature_desc, $cs_form_fields;
            foreach ($_POST as $keys => $values) {
                $$keys = $values;
            }
            //$cs_plugin_options	= get_option( "cs_plugin_options" );

            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';

            $cs_extra_features_options = $cs_plugin_options['cs_extra_features_options'];

            if (isset($_POST['cs_extra_feature_title']) && $_POST['cs_extra_feature_title'] <> '') {
                $extra_feature_id = time();
                $extra_feature_title = $_POST['cs_extra_feature_title'];
            }
            if (isset($_POST['cs_extra_feature_price']) && $_POST['cs_extra_feature_price'] <> '') {
                $extra_feature_price = $_POST['cs_extra_feature_price'];
            }
            if (isset($_POST['cs_extra_feature_type']) && $_POST['cs_extra_feature_type'] <> '') {
                $extra_feature_type = $_POST['cs_extra_feature_type'];
            }
            if (isset($_POST['cs_extra_feature_fchange']) && $_POST['cs_extra_feature_fchange'] <> '') {
                $extra_feature_fchange = $_POST['cs_extra_feature_fchange'];
            }
            if (isset($_POST['cs_extra_feature_desc']) && $_POST['cs_extra_feature_desc'] <> '') {
                $extra_feature_desc = $_POST['cs_extra_feature_desc'];
            }
            if (empty($extra_feature_id)) {
                $extra_feature_id = $counter_extra_feature;
            }
            if (isset($_POST['cs_extra_feature_title']) && is_array($cs_extra_features_options) && ($this->cs_in_array_field($extra_feature_title, 'cs_extra_feature_title', $cs_extra_features_options))) {
                $cs_error_message = sprintf(__('This feature "%s" is already exist. Please create with another Title.', 'rental'), $extra_feature_title);
                $html = '
                <tr class="parentdelete" id="edit_track' . esc_attr($counter_extra_feature) . '">
					<td style="width:100%;">' . $cs_error_message . '</td>
                </tr>';
                echo force_balance_tags($html);
                die();
            } else {

                $extra_feature_price = isset($extra_feature_price) ? esc_attr($extra_feature_price) : '';
                $html = '
                <tr class="parentdelete" id="edit_track' . esc_attr($counter_extra_feature) . '">
                  <td id="subject-title' . esc_attr($counter_extra_feature) . '" style="width:80%;">' . esc_attr($extra_feature_title) . '</td>
                  <td class="centr" style="width:90%;"><a href="javascript:cs_createpop(\'edit_track_form' . esc_js($counter_extra_feature) . '\',\'filter\')" class="actions edit">&nbsp;</a> <a href="#" class="delete-it btndeleteit actions delete">&nbsp;</a></td>
                  <td style="width:0"><div id="edit_track_form' . esc_attr($counter_extra_feature) . '" style="display: none;" class="table-form-elem">
                      <input type="hidden" name="extra_feature_id_array[]" value="' . absint($extra_feature_id) . '" />
                      <div class="cs-heading-area">
                        <h5 style="text-align: left;">' . __('Extra Feature Settings', 'rental') . '</h5>
                        <span onclick="javascript:cs_remove_overlay(\'edit_track_form' . esc_js($counter_extra_feature) . '\',\'append\')" class="cs-btnclose"> <i class="icon-times"></i></span>
                        <div class="clear"></div>
                      </div>';
                $html .= $cs_form_fields->cs_form_text_render(
                        array('name' => __('Extra Feature Title', 'rental'),
                            'id' => 'extra_feature_title',
                            'classes' => '',
                            'std' => $extra_feature_title,
                            'description' => '',
                            'return' => true,
                            'array' => true,
                            'hint' => ''
                        )
                );

                $html .= $cs_form_fields->cs_form_text_render(
                        array('name' => __('Price', 'rental'),
                            'id' => 'extra_feature_price',
                            'classes' => '',
                            'std' => $extra_feature_price,
                            'description' => '',
                            'return' => true,
                            'array' => true,
                            'hint' => ''
                        )
                );

                $html .= $cs_form_fields->cs_form_select_render(
                        array('name' => __('Type', 'rental'),
                            'id' => 'extra_feature_type',
                            'classes' => '',
                            'std' => $extra_feature_type,
                            'description' => '',
                            'return' => true,
                            'array' => true,
                            'hint' => '',
                            'options' => array('none' => __('None', 'rental'), 'one-time' => __('One Time', 'rental'), 'daily' => __('Daily', 'rental')),
                        )
                );


                $html .= $cs_form_fields->cs_form_checkbox_render(
                        array('name' => __('Frontend Changeable', 'rental'),
                            'id' => 'extra_feature_fchange',
                            'classes' => '',
                            'std' => $extra_feature_fchange,
                            'description' => '',
                            'return' => true,
                            'array' => true,
                            'hint' => '',
                        )
                );

                $html .= $cs_form_fields->cs_form_textarea_render(
                        array('name' => __('Description', 'rental'),
                            'id' => 'extra_feature_desc',
                            'classes' => '',
                            'std' => $extra_feature_desc,
                            'description' => '',
                            'return' => true,
                            'array' => true,
                            'hint' => '',
                        )
                );

                $html .= '
                      <ul class="form-elements noborder">
                        <li class="to-label">
                          <label></label>
                        </li>
                        <li class="to-field">
                          <input type="button" value="' . __('Update Extra Feature', 'rental') . '" onclick="cs_remove_overlay(\'edit_track_form' . esc_js($counter_extra_feature) . '\',\'append\')" />
                        </li>
                      </ul>
                    </div></td>
                </tr>';

                if (isset($_POST['cs_extra_feature_title']) && isset($_POST['cs_extra_feature_price'])) {
                    echo force_balance_tags($html);
                } else {
                    return $html;
                }
            }
            if (isset($_POST['cs_extra_feature_title']) && isset($_POST['cs_extra_feature_price']))
                die();
        }

        // Features List
        public function cs_feats_section() {
            global $post, $feats_id, $counter_feats, $feats_title, $feats_image, $feats_desc, $cs_plugin_options, $cs_form_fields;
            //$cs_plugin_options	= get_option( "cs_plugin_options" );
            $cs_feats_options = $cs_plugin_options['cs_feats_options'];
            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';

            $html = '
            <form name="dir-feats" method="post" action="">
			<input type="hidden" name="dynamic_booking_feats" value="1" />
			<script>
                jQuery(document).ready(function($) {
                    $("#total_feats").sortable({
						cancel : \'td div.table-form-elem\'
                    });
                });
            </script>
              <ul class="form-elements">
                    <li class="to-label">' . __("Add Feature", "rental") . '</li>
                    <li class="to-button"><a href="javascript:cs_createpop(\'add_feats_title\',\'filter\')" class="button">' . __("Add Feature", "rental") . '</a> </li>
               </ul>
              <div class="cs-list-table">
              <table class="to-table" border="0" cellspacing="0">
                <thead>
                  <tr>
                    <th style="width:80%;">' . __("Title", "rental") . '</th>
                    <th style="width:80%;" class="centr">' . __("Actions", "rental") . '</th>
                    <th style="width:0%;" class="centr"></th>
                  </tr>
                </thead>
                <tbody id="total_feats">';
            if (isset($cs_feats_options) && is_array($cs_feats_options) && count($cs_feats_options) > 0) {
                foreach ($cs_feats_options as $feats_key => $feats) {
                    if (isset($feats_key) && $feats_key <> '') {
                        $counter_feats = $feats_id = isset($feats['feats_id']) ? $feats['feats_id'] : '';
                        $feats_title = isset($feats['cs_feats_title']) ? $feats['cs_feats_title'] : '';
                        $feats_image = isset($feats['cs_feats_image']) ? $feats['cs_feats_image'] : '';
                        $feats_desc = isset($feats['cs_feats_desc']) ? $feats['cs_feats_desc'] : '';
						
						if (function_exists('icl_register_string')) {
							do_action('wpml_register_single_string', 'Vehicle Features', 'Feature "' . $feats_title . '" - Title field', $feats_title);
							do_action('wpml_register_single_string', 'Vehicle Features', 'Feature "' . $feats_desc . '" - Description field', $feats_desc);
						}
						
                        $html .= $this->cs_add_feats_to_list();
                    }
                }
            }
            $html .= '
				</tbody>
              </table>

              </div>
              </form>
              <div id="add_feats_title" style="display: none;">
                <div class="cs-heading-area">
                  <h5><i class="icon-plus-circle"></i> ' . __('Feature Settings', 'rental') . '</h5>
                  <span class="cs-btnclose" onClick="javascript:cs_remove_overlay(\'add_feats_title\',\'append\')"> <i class="icon-times"></i></span> 	
				</div>';
            $html .= $cs_form_fields->cs_form_text_render(
                    array('name' => __('Title', 'rental'),
                        'id' => 'feats_title',
                        'classes' => '',
                        'std' => __('Title', 'rental'),
                        'description' => '',
                        'return' => true,
                        'hint' => ''
                    )
            );
            $html .= $cs_form_fields->cs_form_fileupload_render(
                    array('name' => __('Image', 'rental'),
                        'id' => 'feats_image',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'return' => true,
                        'hint' => ''
                    )
            );
            $html .= $cs_form_fields->cs_form_textarea_render(
                    array('name' => __('Description', 'rental'),
                        'id' => 'feats_desc',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'return' => true,
                        'hint' => ''
                    )
            );
            $html .= '
                <ul class="form-elements noborder">
                  <li class="to-label"></li>
                  <li class="to-field">
                    <input type="button" value="Add Feature to List" onClick="add_feats_to_list(\'' . esc_js(admin_url('admin-ajax.php')) . '\', \'' . esc_js(wp_car_rental::plugin_url()) . '\')" />
                    <div class="feature-loader"></div>
                  </li>
                </ul>
              </div>';

            echo force_balance_tags($html, true);
        }

        public function cs_add_feats_to_list() {
            global $counter_feats, $cs_plugin_options, $feats_id, $feats_title, $feats_image, $feats_desc, $cs_form_fields;
            foreach ($_POST as $keys => $values) {
                $$keys = $values;
            }

            //$cs_plugin_options	= get_option( "cs_plugin_options" );

            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';

            if (isset($_POST['cs_feats_title']) && $_POST['cs_feats_title'] <> '') {
                $feats_id = time();
                $feats_title = $_POST['cs_feats_title'];
            }
            if (isset($_POST['cs_feats_image']) && $_POST['cs_feats_image'] <> '') {
                $feats_image = $_POST['cs_feats_image'];
            }

            if (isset($_POST['cs_feats_desc']) && $_POST['cs_feats_desc'] <> '') {
                $feats_desc = $_POST['cs_feats_desc'];
            }
            if (empty($feats_id)) {
                $feats_id = $counter_feats;
            }

            $feats_desc = isset($feats_desc) ? esc_attr($feats_desc) : '';
            $html = '
                <tr class="parentdelete" id="edit_track' . esc_attr($counter_feats) . '">
                  <td id="subject-title' . esc_attr($counter_feats) . '" style="width:80%;">' . esc_attr($feats_title) . '</td>
                  <td class="centr" style="width:20%;"><a href="javascript:cs_createpop(\'edit_track_form' . esc_js($counter_feats) . '\',\'filter\')" class="actions edit">&nbsp;</a> <a href="#" class="delete-it btndeleteit actions delete">&nbsp;</a></td>
                  <td style="width:0"><div id="edit_track_form' . esc_attr($counter_feats) . '" style="display: none;" class="table-form-elem">
                      <input type="hidden" name="feats_id_array[]" value="' . absint($feats_id) . '" />
                      <div class="cs-heading-area">
                        <h5 style="text-align: left;">' . __('Feature Settings', 'rental') . '</h5>
                        <span onclick="javascript:cs_remove_overlay(\'edit_track_form' . esc_js($counter_feats) . '\',\'append\')" class="cs-btnclose"> <i class="icon-times"></i></span>
                        <div class="clear"></div>
                      </div>';
            $html .= $cs_form_fields->cs_form_text_render(
                    array('name' => __('Feature Title', 'rental'),
                        'id' => 'feats_title',
                        'classes' => '',
                        'std' => $feats_title,
                        'description' => '',
                        'return' => true,
                        'array' => true,
                        'hint' => ''
                    )
            );
            $html .= $cs_form_fields->cs_form_fileupload_render(
                    array('name' => __('Image', 'rental'),
                        'id' => 'feats_image',
                        'classes' => '',
                        'std' => $feats_image,
                        'description' => '',
                        'return' => true,
                        'array' => true,
                        'hint' => ''
                    )
            );

            $html .= $cs_form_fields->cs_form_textarea_render(
                    array('name' => __('Description', 'rental'),
                        'id' => 'feats_desc',
                        'classes' => '',
                        'std' => $feats_desc,
                        'description' => '',
                        'return' => true,
                        'array' => true,
                        'hint' => ''
                    )
            );


            $html .= '
                      <ul class="form-elements noborder">
                        <li class="to-label">
                          <label></label>
                        </li>
                        <li class="to-field">
                          <input type="button" value="' . __('Update Feature', 'rental') . '" onclick="cs_remove_overlay(\'edit_track_form' . esc_js($counter_feats) . '\',\'append\')" />
                        </li>
                      </ul>
                    </div></td>
                </tr>';

            if (isset($_POST['cs_feats_title']) && isset($_POST['cs_feats_desc'])) {
                echo force_balance_tags($html);
            } else {
                return $html;
            }

            if (isset($_POST['cs_feats_title']) && isset($_POST['cs_feats_desc']))
                die();
        }

        // Properties List
        public function cs_properties_section() {
            global $post, $properties_id, $counter_properties, $properties_title, $properties_image, $properties_desc, $cs_plugin_options, $cs_form_fields;
            //$cs_plugin_options	= get_option( "cs_plugin_options" );
            $cs_properties_options = isset($cs_plugin_options['cs_properties_options']) ? $cs_plugin_options['cs_properties_options'] : array();
            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';

            $html = '
            <form name="dir-properties" method="post" action="">
			<input type="hidden" name="dynamic_booking_properties" value="1" />
			<script>
                jQuery(document).ready(function($) {
                    $("#total_properties").sortable({
						cancel : \'td div.table-form-elem\'
                    });
                });
            </script>
              <ul class="form-elements">
                    <li class="to-label">' . __("Add Facility", "rental") . '</li>
                    <li class="to-button"><a href="javascript:cs_createpop(\'add_properties_title\',\'filter\')" class="button">' . __("Add Facility", "rental") . '</a> </li>
               </ul>
              <div class="cs-list-table">
              <table class="to-table" border="0" cellspacing="0">
                <thead>
                  <tr>
                    <th style="width:80%;">' . __("Title", "rental") . '</th>
                    <th style="width:80%;" class="centr">' . __("Actions", "rental") . '</th>
                    <th style="width:0%;" class="centr"></th>
                  </tr>
                </thead>
                <tbody id="total_properties">';
            if (isset($cs_properties_options) && is_array($cs_properties_options) && count($cs_properties_options) > 0) {
                foreach ($cs_properties_options as $properties_key => $properties) {
                    if (isset($properties_key) && $properties_key <> '') {
                        $counter_properties = $properties_id = isset($properties['properties_id']) ? $properties['properties_id'] : '';
                        $properties_title = isset($properties['cs_properties_title']) ? $properties['cs_properties_title'] : '';
                        $properties_desc = isset($properties['cs_properties_desc']) ? $properties['cs_properties_desc'] : '';
						
						if (function_exists('icl_register_string')) {
							do_action('wpml_register_single_string', 'Vehicle Facilities', 'Facility "' . $properties_title . '" - Title field', $properties_title);
							do_action('wpml_register_single_string', 'Vehicle Facilities', 'Facility "' . $properties_desc . '" - Description field', $properties_desc);
						}
						
                        $html .= $this->cs_add_properties_to_list();
                    }
                }
            }
            $html .= '
				</tbody>
              </table>
              </div>
              </form>
              <div id="add_properties_title" style="display: none;">
                <div class="cs-heading-area">
                  <h5><i class="icon-plus-circle"></i> ' . __('Facility Settings', 'rental') . '</h5>
                  <span class="cs-btnclose" onClick="javascript:cs_remove_overlay(\'add_properties_title\',\'append\')"> <i class="icon-times"></i></span> 	
				</div>';
            $html .= $cs_form_fields->cs_form_text_render(
                    array('name' => __('Title', 'rental'),
                        'id' => 'properties_title',
                        'classes' => '',
                        'std' => __('Title', 'rental'),
                        'description' => '',
                        'return' => true,
                        'hint' => ''
                    )
            );
            $html .= $cs_form_fields->cs_form_textarea_render(
                    array('name' => __('Description', 'rental'),
                        'id' => 'properties_desc',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'return' => true,
                        'hint' => ''
                    )
            );
            $html .= '
                <ul class="form-elements noborder">
                  <li class="to-label"></li>
                  <li class="to-field">
                    <input type="button" value="Add Facility to List" onClick="add_properties_to_list(\'' . esc_js(admin_url('admin-ajax.php')) . '\', \'' . esc_js(wp_car_rental::plugin_url()) . '\')" />
                    <div class="property-loader"></div>
                  </li>
                </ul>
              </div>';

            echo force_balance_tags($html, true);
        }

        //Property Add To list
        public function cs_add_properties_to_list() {
            global $counter_properties, $cs_plugin_options, $properties_id, $properties_title, $properties_image, $properties_desc, $cs_form_fields;
            foreach ($_POST as $keys => $values) {
                $$keys = $values;
            }

            //$cs_plugin_options	= get_option( "cs_plugin_options" );

            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';

            if (isset($_POST['cs_properties_title']) && $_POST['cs_properties_title'] <> '') {
                $properties_id = time();
                $properties_title = $_POST['cs_properties_title'];
            }
            if (isset($_POST['cs_properties_desc']) && $_POST['cs_properties_desc'] <> '') {
                $properties_desc = $_POST['cs_properties_desc'];
            }
            if (empty($properties_id)) {
                $properties_id = $counter_properties;
            }

            $properties_desc = isset($properties_desc) ? esc_attr($properties_desc) : '';
            $html = '
                <tr class="parentdelete" id="edit_track' . esc_attr($counter_properties) . '">
                  <td id="subject-title' . esc_attr($counter_properties) . '" style="width:80%;">' . esc_attr($properties_title) . '</td>
                  <td class="centr" style="width:20%;"><a href="javascript:cs_createpop(\'edit_track_form' . esc_js($counter_properties) . '\',\'filter\')" class="actions edit">&nbsp;</a> <a href="#" class="delete-it btndeleteit actions delete">&nbsp;</a></td>
                  <td style="width:0"><div id="edit_track_form' . esc_attr($counter_properties) . '" style="display: none;" class="table-form-elem">
                      <input type="hidden" name="properties_id_array[]" value="' . absint($properties_id) . '" />
                      <div class="cs-heading-area">
                        <h5 style="text-align: left;">' . __('Facility Settings', 'rental') . '</h5>
                        <span onclick="javascript:cs_remove_overlay(\'edit_track_form' . esc_js($counter_properties) . '\',\'append\')" class="cs-btnclose"> <i class="icon-times"></i></span>
                        <div class="clear"></div>
                      </div>';
            $html .= $cs_form_fields->cs_form_text_render(
                    array('name' => __('Facility Title', 'rental'),
                        'id' => 'properties_title',
                        'classes' => '',
                        'std' => $properties_title,
                        'description' => '',
                        'return' => true,
                        'array' => true,
                        'hint' => ''
                    )
            );

            $html .= $cs_form_fields->cs_form_textarea_render(
                    array('name' => __('Description', 'rental'),
                        'id' => 'properties_desc',
                        'classes' => '',
                        'std' => $properties_desc,
                        'description' => '',
                        'return' => true,
                        'array' => true,
                        'hint' => ''
                    )
            );


            $html .= '
                      <ul class="form-elements noborder">
                        <li class="to-label">
                          <label></label>
                        </li>
                        <li class="to-field">
                          <input type="button" value="' . __('Update Facility', 'rental') . '" onclick="cs_remove_overlay(\'edit_track_form' . esc_js($counter_properties) . '\',\'append\')" />
                        </li>
                      </ul>
                    </div></td>
                </tr>';

            if (isset($_POST['cs_properties_title']) && isset($_POST['cs_properties_desc'])) {
                echo force_balance_tags($html);
            } else {
                return $html;
            }

            if (isset($_POST['cs_properties_title']) && isset($_POST['cs_properties_desc']))
                die();
        }

        // Reviews List
        public function cs_dyn_reviews_section() {
            global $post, $dyn_reviews_id, $counter_dyn_reviews, $dyn_reviews_title, $cs_plugin_options, $cs_form_fields;
            //$cs_plugin_options	= get_option( "cs_plugin_options" );
            $cs_dyn_reviews_options = $cs_plugin_options['cs_dyn_reviews_options'];
            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';

            $html = '
            <form name="dir-dyn_reviews" method="post" action="">
			<input type="hidden" name="dynamic_booking_dyn_reviews" value="1" />
			<script>
                jQuery(document).ready(function($) {
                    $("#total_dyn_reviews").sortable({
						cancel : \'td div.table-form-elem\'
                    });
                });
            </script>
              <ul class="form-elements">
                    <li class="to-label">' . __("Add Review", "rental") . '</li>
                    <li class="to-button"><a href="javascript:cs_createpop(\'add_dyn_reviews_title\',\'filter\')" class="button">' . __("Add Review", "rental") . '</a> </li>
               </ul>
              <div class="cs-list-table">
              <table class="to-table" border="0" cellspacing="0">
                <thead>
                  <tr>
                    <th style="width:80%;">' . __("Title", "rental") . '</th>
                    <th style="width:80%;" class="centr">' . __("Actions", "rental") . '</th>
                    <th style="width:0%;" class="centr"></th>
                  </tr>
                </thead>
                <tbody id="total_dyn_reviews">';
            if (isset($cs_dyn_reviews_options) && is_array($cs_dyn_reviews_options) && count($cs_dyn_reviews_options) > 0) {
                foreach ($cs_dyn_reviews_options as $dyn_reviews_key => $dyn_reviews) {
                    if (isset($dyn_reviews_key) && $dyn_reviews_key <> '') {
                        $counter_dyn_reviews = $dyn_reviews_id = isset($dyn_reviews['dyn_reviews_id']) ? $dyn_reviews['dyn_reviews_id'] : '';
                        $dyn_reviews_title = isset($dyn_reviews['cs_dyn_reviews_title']) ? $dyn_reviews['cs_dyn_reviews_title'] : '';

                        $html .= $this->cs_add_dyn_reviews_to_list();
                    }
                }
            }
            $html .= '
				</tbody>
              </table>

              </div>
              </form>
              <div id="add_dyn_reviews_title" style="display: none;">
                <div class="cs-heading-area">
                  <h5><i class="icon-plus-circle"></i> ' . __('Review Settings', 'rental') . '</h5>
                  <span class="cs-btnclose" onClick="javascript:cs_remove_overlay(\'add_dyn_reviews_title\',\'append\')"> <i class="icon-times"></i></span> 	
				</div>';
            $html .= $cs_form_fields->cs_form_text_render(
                    array('name' => __('Title', 'rental'),
                        'id' => 'dyn_reviews_title',
                        'classes' => '',
                        'std' => __('Title', 'rental'),
                        'description' => '',
                        'return' => true,
                        'hint' => ''
                    )
            );

            $html .= '
                <ul class="form-elements noborder">
                  <li class="to-label"></li>
                  <li class="to-field">
                    <input type="button" value="Add Review to List" onClick="add_dyn_reviews_to_list(\'' . esc_js(admin_url('admin-ajax.php')) . '\', \'' . esc_js(wp_car_rental::plugin_url()) . '\')" />
                    <div class="feature-loader"></div>
                  </li>
                </ul>
              </div>';

            echo force_balance_tags($html, true);
        }

        // Reviews List

        public function cs_add_dyn_reviews_to_list() {
            global $counter_dyn_reviews, $cs_plugin_options, $dyn_reviews_id, $dyn_reviews_title, $cs_form_fields;

            foreach ($_POST as $keys => $values) {
                $$keys = $values;
            }

            //$cs_plugin_options	= get_option( "cs_plugin_options" );

            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';

            $cs_dyn_reviews_options = $cs_plugin_options['cs_dyn_reviews_options'];


            if (isset($_POST['cs_dyn_reviews_title']) && $_POST['cs_dyn_reviews_title'] <> '') {
                $dyn_reviews_id = time();
                $dyn_reviews_title = $_POST['cs_dyn_reviews_title'];
            }

            if (empty($dyn_reviews_id)) {
                $dyn_reviews_id = $counter_dyn_reviews;
            }

            $html = '
                <tr class="parentdelete" id="edit_track' . esc_attr($counter_dyn_reviews) . '">
                  <td id="subject-title' . esc_attr($counter_dyn_reviews) . '" style="width:80%;">' . esc_attr($dyn_reviews_title) . '</td>
                  <td class="centr" style="width:20%;"><a href="javascript:cs_createpop(\'edit_track_form' . esc_js($counter_dyn_reviews) . '\',\'filter\')" class="actions edit">&nbsp;</a> <a href="#" class="delete-it btndeleteit actions delete">&nbsp;</a></td>
                  <td style="width:0"><div id="edit_track_form' . esc_attr($counter_dyn_reviews) . '" style="display: none;" class="table-form-elem">
                      <input type="hidden" name="dyn_reviews_id_array[]" value="' . absint($dyn_reviews_id) . '" />
                      <div class="cs-heading-area">
                        <h5 style="text-align: left;">' . __('Review Settings', 'rental') . '</h5>
                        <span onclick="javascript:cs_remove_overlay(\'edit_track_form' . esc_js($counter_dyn_reviews) . '\',\'append\')" class="cs-btnclose"> <i class="icon-times"></i></span>
                        <div class="clear"></div>
                      </div>';
            $html .= $cs_form_fields->cs_form_text_render(
                    array('name' => __('Review Title', 'rental'),
                        'id' => 'dyn_reviews_title',
                        'classes' => '',
                        'std' => $dyn_reviews_title,
                        'description' => '',
                        'return' => true,
                        'array' => true,
                        'hint' => ''
                    )
            );

            $html .= '
                      <ul class="form-elements noborder">
                        <li class="to-label">
                          <label></label>
                        </li>
                        <li class="to-field">
                          <input type="button" value="' . __('Update Review', 'rental') . '" onclick="cs_remove_overlay(\'edit_track_form' . esc_js($counter_dyn_reviews) . '\',\'append\')" />
                        </li>
                      </ul>
                    </div></td>
                </tr>';

            if (isset($_POST['cs_dyn_reviews_title'])) {
                echo force_balance_tags($html);
            } else {
                return $html;
            }

            if (isset($_POST['cs_dyn_reviews_title']))
                die();
        }

        /* ------------------------------------------------------------
         * Array Fields
         * ----------------------------------------------------------- */

        function cs_in_array_field($array_val, $array_field, $array, $strict = false) {
            if ($strict) {
                foreach ($array as $item)
                    if (isset($item[$array_field]) && $item[$array_field] === $array_val)
                        return true;
            }
            else {
                foreach ($array as $item)
                    if (isset($item[$array_field]) && $item[$array_field] == $array_val)
                        return true;
            }
            return false;
        }

        /* ------------------------------------------------------------
         * Check Duplicate Values
         * ----------------------------------------------------------- */

        function cs_check_duplicate_value($array_val, $array_field, $array) {
            $cs_val_counter = 0;
            foreach ($array as $item) {
                if (isset($item[$array_field]) && $item[$array_field] == $array_val) {
                    $cs_val_counter++;
                }
            }
            if ($cs_val_counter > 1)
                return true;
            return false;
        }

        /* ------------------------------------------------------------
         * Remove Extra Features
         * ----------------------------------------------------------- */

        function cs_remove_duplicate_extra_value() {
            global $cs_plugin_options;
            //$cs_plugin_options 			= get_option( 'cs_plugin_options' );
            $cs_extra_features_options = $cs_plugin_options['cs_extra_features_options'];
            $extrasdata = array();
            $extra_feature_array = $extra_features = '';
            if (isset($cs_extra_features_options) && is_array($cs_extra_features_options) && count($cs_extra_features_options) > 0) {
                $extra_feature_array = $extra_features = $extrasdata = array();
                foreach ($cs_extra_features_options as $extra_feature_key => $extra_feature) {
                    if (isset($extra_feature_key) && $extra_feature_key <> '') {

                        $extra_feature_id = isset($extra_feature['extra_feature_id']) ? $extra_feature['extra_feature_id'] : '';
                        $extra_feature_title = isset($extra_feature['cs_extra_feature_title']) ? $extra_feature['cs_extra_feature_title'] : '';
                        $extra_feature_price = isset($extra_feature['cs_extra_feature_price']) ? $extra_feature['cs_extra_feature_price'] : '';
                        $extra_feature_type = isset($extra_feature['cs_extra_feature_type']) ? $extra_feature['cs_extra_feature_type'] : '';
                        $extra_feature_fchange = isset($extra_feature['cs_extra_feature_fchange']) ? $extra_feature['cs_extra_feature_fchange'] : '';
                        $extra_feature_desc = isset($extra_feature['cs_extra_feature_desc']) ? $extra_feature['cs_extra_feature_desc'] : '';

                        if (!$this->cs_check_duplicate_value($extra_feature_title, 'cs_extra_feature_title', $cs_extra_features_options)) {
                            $extra_feature_array['extra_feature_id'] = $extra_feature_id;
                            $extra_feature_array['cs_extra_feature_title'] = $extra_feature_title;
                            $extra_feature_array['cs_extra_feature_price'] = $extra_feature_price;
                            $extra_feature_array['cs_extra_feature_type'] = $extra_feature_type;
                            $extra_feature_array['cs_extra_feature_fchange'] = $extra_feature_fchange;
                            $extra_feature_array['cs_extra_feature_desc'] = $extra_feature_desc;
                            $extra_features[$extra_feature_id] = $extra_feature_array;
                        }
                    }
                }

                $extrasdata['cs_extra_features_options'] = $extra_features;
                $cs_options = array_merge($cs_plugin_options, $extrasdata);
                update_option("cs_plugin_options", $cs_options);
            }
            //End if
        }

    }

    //End Class
}

function cs_settings_fields($key, $param) {
    global $post;

    $cs_gateway_options = get_option('cs_gateway_options');
    $cs_value = $param['std'];


    $html = '';
    switch ($param['type']) {
        case 'text':

            if (isset($cs_gateway_options)) {
                if (isset($cs_gateway_options[$param['id']])) {
                    $val = $cs_gateway_options[$param['id']];
                } else {
                    $val = $param['std'];
                }
            } else {
                $val = $param['std'];
            }

            $output = '<ul class="form-elements" id="' . $param['id'] . '_textfield">';
            $output .= '<li class="to-label">
							<label>' . esc_attr($param["name"]) . '<span>' . esc_attr($param['hint_text']) . '</span></label>
						</li>
						<li class="to-field"><input   name="' . $param['id'] . '" id="' . $param['id'] . '" type="' . $param['type'] . '" value="' . $val . '" class="vsmall" />';
            $output .= '<p>' . esc_attr($param['desc']) . '</p></li>';
            $output .= '</ul>';
            $html .= $output;
            break;
        case 'textarea':

            $val = $param['std'];

            $std = get_option($param['id']);

            if (isset($cs_gateway_options)) {
                if (isset($cs_gateway_options[$param['id']])) {
                    $val = $cs_gateway_options[$param['id']];
                } else {
                    $val = $param['std'];
                }
            } else {
                $val = $param['std'];
            }

            $output = '<ul class="form-elements" id="' . $param['id'] . '_textarea"> 
						<li class="to-label">
							<label>' . esc_attr($param['name']) . '<span>' . esc_attr($param['hint_text']) . '</span></label>
						</li>
						<li class="to-field">
							<div class="input-sec">
								<textarea rows="10" cols="60" name="' . $param['id'] . '" id="' . $param['id'] . '" type="' . $param['type'] . '">' . htmlspecialchars_decode($val) . '</textarea>
							</div>
							<div class="left-info"><p>' . esc_attr($param['desc']) . '</p></div>
						</li>
				  </ul>';
            $html .= $output;
            break;

        case "checkbox":
            $saved_std = '';
            $std = '';

            if (isset($cs_gateway_options)) {
                if (isset($cs_gateway_options[$param['id']])) {
                    $saved_std = $cs_gateway_options[$param['id']];
                }
            } else {
                $std = $param['std'];
            }
            $checked = '';
            if (!empty($saved_std)) {
                if ($saved_std == 'on') {
                    $checked = 'checked="checked"';
                } else {
                    $checked = '';
                }
            } elseif ($std == 'on') {
                $checked = 'checked="checked"';
            } else {
                $checked = '';
            }

            $output = '<ul class="form-elements" id="' . $param['id'] . '_checkbox">
						  <li class="to-label">
						  <label>' . esc_attr($param['name']) . '<span>' . esc_attr($param['hint_text']) . '</span></label>
						  </li>
						  <li class="to-field"><div class="input-sec"><label class="pbwp-checkbox">
						  <input type="hidden" name="' . $param['id'] . '" value="off" />
						  <input type="checkbox" class="myClass"  name="' . $param['id'] . '" id="' . $param['id'] . '" ' . $checked . ' />
						  <span class="pbwp-box"></span>
						  </label></div><div class="left-info">
							  <p>' . esc_attr($param['desc']) . '</p>
						  </div></li>
						</ul>';
            $html .= $output;
            break;

        case "logo":

            if (isset($cs_gateway_options) and $cs_gateway_options <> '' && isset($cs_gateway_options[$param['id']])) {
                $val = $cs_gateway_options[$param['id']];
            } else {
                $val = $param['std'];
            }

            $output = '';
            $display = ($val <> '' ? 'display' : 'none');
            if (isset($value['tab'])) {
                $output .='<div class="main_tab"><div class="horizontal_tab" style="display:' . $param['display'] . '" id="' . $param['tab'] . '">';
            }

            $output .= '<ul class="form-elements" id="' . $param['id'] . '_upload">
					  <li class="to-label">
						 <label>' . esc_attr($param['name']) . '<span>' . esc_attr($param['hint_text']) . '</span></label>
					  </li>
					  <li class="to-field">
						<div class="page-wrap" style="overflow:hidden;display:' . $display . '" id="' . $param['id'] . '_box" >
						  <div class="gal-active">
							<div class="dragareamain" style="padding-bottom:0px;">
							  <ul id="gal-sortable">
								<li class="ui-state-default" id="">
								  <div class="thumb-secs cs-custom-image"> <img src="' . esc_url($val) . '"  id="' . $param['id'] . '_img"  />
									<div class="gal-edit-opts"> <a href=javascript:del_media("' . $param['id'] . '") class="delete"></a> </div>
								  </div>
								</li>
							  </ul>
							</div>
						  </div>
						</div>
						<input id="' . $param['id'] . '" name="' . $param['id'] . '" type="hidden" class="" value="' . $val . '"/>
						<label class="browse-icon"><input name="' . $param['id'] . '"  type="button" class="uploadMedia left" value=' . __('Browse', 'rental') . '/></label>
					  </li>
					</ul>';
            $html .= $output;
            break;

        case 'select' :

            $output = '<ul class="form-elements">';
            $output .= '<li class="to-label"><label>' . $param['title'] . '</label></li>';
            $output .= '<li class="to-field">';
            $output .= '<div class="input-select">';
            $output .= '<select name="cs_transaction_meta[' . $key . ']" id="' . $key . '" class="cs-form-select cs-input">' . "\n";

            foreach ($param['options'] as $value => $option) {
                $selected = '';
                if ($cs_value == $value) {
                    $selected = 'selected="selected"';
                }

                $output .= '<option value="' . $value . '" ' . $selected . '>' . $option . '</option>' . "\n";
            }
            $output .= '</select>' . "\n";
            $output .= '<span class="cs-form-desc">' . $param['description'] . '</span>' . "\n</div>";
            $output .= '</li>';
            $output .= '</ul>';
            // append
            $html .= $output;
            break;

        default :
            break;
    }
    return $html;
}

if (class_exists('cs_plugin_options')) {
    $settings_object = new cs_plugin_options();
    add_action('admin_menu', array(&$settings_object, 'cs_register_booking_settings'));
}
