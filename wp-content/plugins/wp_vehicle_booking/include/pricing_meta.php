<?php

/**
 *  File Type: Pricing Class
 */
if (!class_exists('cs_pricing_options')) {

    class cs_pricing_options {

        public function __construct() {

            add_action('wp_ajax_cs_add_pricing_to_list', array(&$this, 'cs_add_pricing_to_list'));
            add_action('wp_ajax_price_option_save', array(&$this, 'price_option_save'));
            add_action('wp_ajax_cs_get_vehicle_cont', array(&$this, 'cs_get_vehicle_cont'));
            add_action('wp_ajax_add_price_plan', array(&$this, 'add_price_plan'));
            add_action('wp_ajax_add_price_offer', array(&$this, 'add_price_offer'));
        }

        //add submenu page
        public function cs_pricing_settings() {

            add_submenu_page('edit.php?post_type=vehicles', __('Price Plans & Offers', 'rental'), __('Price Plans & Offers', 'rental'), 'manage_options', 'cs_pricing', array(&$this, 'cs_pricing_area'));
        }

        //add price fields
        public function cs_pricing_area() {
            global $cs_plugin_options;

            $cs_charge_base = isset($cs_plugin_options['cs_charge_base']) ? $cs_plugin_options['cs_charge_base'] : '';
            $cs_require_days_txt = '';
            if ($cs_charge_base == 'hourly') {
                $cs_require_days_txt = __('Require Hours', 'rental');
            } else {
                $cs_require_days_txt = __('Require Days', 'rental');
            }

            $cs_price_options = get_option("cs_price_options");
            $cs_offers_options = get_option("cs_offers_options");

            wp_car_rental::cs_date_range_style_script();

            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';

            $url = admin_url('edit.php?post_type=vehicles&page=cs_pricing');
            $html = '
			<div class="theme-wrap fullwidth">
				<div class="outerwrapp-layer">
					<div class="loading_div"> <i class="icon-circle-o-notch icon-spin"></i> <br>
						' . __('Saving prices...', 'rental') . '
					</div>
					<div class="form-msg"> <i class="icon-check-circle-o"></i>
						<div class="innermsg"></div>
					</div>
				</div>
				<div class="row">
					<form name="vehicle-pricing" id="cs-booking-pricing" method="post" data-url="' . esc_js(admin_url('admin-ajax.php')) . '">
						<input type="hidden" name="cs_pricing_fields" value="1" />
						<div class="cs-vehicles-prices cs-customers-area">
							<div class="cs-title"><h2>' . __('Vehicle Pricing', 'rental') . '</h2></div>';
            $cs_args = array('posts_per_page' => '-1', 'post_type' => 'vehicles', 'orderby' => 'ID', 'post_status' => 'publish');
            $cust_query = get_posts($cs_args);

            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'offers') {
                $cs_ofer_active = 'active';
                $cs_plan_active = '';
            } else if (isset($_REQUEST['action']) && $_REQUEST['action'] <> 'offers') {
                $cs_ofer_active = '';
                $cs_plan_active = 'active';
            } else {
                $cs_ofer_active = '';
                $cs_plan_active = 'active';
            }
            $html .= '
							<div class="cs-price-tabs">
								<ul>
									<li class="' . sanitize_html_class($cs_plan_active) . '"><a href="' . esc_url($url . '&amp;action=plans') . '">' . __('Price Plans', 'rental') . '</a></li>
									<li class="' . sanitize_html_class($cs_ofer_active) . '"><a href="' . esc_url($url . '&amp;action=offers') . '">' . __('Special Offers', 'rental') . '</a></li>
								</ul>
								<div class="tabs-content">';
            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'offers') {
                $html .= '
										<div id="rum-special-ofrs" class="tab-pane">
											<div class="cs_select_vehicle">
												<div id="cs_offers_add">
													<a class="cs-add-btn" data-type="offers" href="javascript:cs_createpop(\'cs_offers_popup\',\'filter\')">' . __('Add New Offer', 'rental') . '</a>
												</div>
											</div>';

                $html .= '
											<div id="cs_offers_cont">
												<div class="cs-price-offers">
													<table class="cs-offers-list">
														<thead>
															<tr>
																<th>' . __('Offer Duration', 'rental') . '</th>
																<th>' . __('Name', 'rental') . '</th>
																<th>' . __('Discount (%)', 'rental') . '</th>
																<th>' . $cs_require_days_txt . '</th>
																<th>&nbsp;</th>
															</tr>
														</thead>
														<tbody id="cs_offers_tr_result">';
                $html .= $this->added_price_offer($cs_offers_options);
                $html .= '
														</tbody>
													</table>
													<div class="footer footerbg">
														<a href="javascript:;" onclick="javascript:price_option_save(\'' . esc_js(admin_url('admin-ajax.php')) . '\');" class="cs-save-btn">' . __('Save Offers', 'rental') . '</a>
													</div>
												</div>
											</div>
										</div>
										<div id="cs_offers_popup" style="display: none;">
											<div class="cs-popup-header">
												<h5>' . __('New Offer', 'rental') . '</h5>
												<span class="cs-pop-close" onclick="javascript:cs_remove_overlay(\'cs_offers_popup\',\'append\')"> <i class="icon-times"></i></span>
											</div>
											<div class="cs-popup-content">
											<div id="message" class="wp-custom-messages cs-offer-message notice notice-error" style="display:none;">
												<p></p>
											</div>';
                $html .= '
												<script type="text/javascript">
													jQuery(function(){
														jQuery("#date_range_new").dateRangePicker({
															separator : " to ",
															getValue: function()
															{
																if (jQuery("cs_spec_start_day").val() && jQuery("#cs_spec_end_day").val() )
																	return jQuery("#cs_spec_start_day").val() + " to " + jQuery("#cs_spec_end_day").val();
																else
																	return "";
															},
															setValue: function(s,s1,s2)
															{
																jQuery("#cs_spec_start_day").val(s1);
																jQuery("#cs_spec_end_day").val(s2);
															}
														});
													});
									
												</script>
												<label>
													<span>' . __('Offer Name', 'rental') . '</span>
													<input id="cs_offer_name" type="text" />
												</label>
												<label class="cs_select">
													<span>' . __('Select Vehicle', 'rental') . '</span>
													<select id="cs_offer_vehicle">';
                $html .= '<option value="">-- ' . __('All Vehicle', 'rental') . ' --</option>';
                foreach ($cust_query as $vehicle) {
                    $html .= '<option value="' . $vehicle->ID . '">' . get_the_title($vehicle->ID) . '</option>';
                }
                $html .= '
													</select>
												</label>
												<div class="strt-day" id="date_range_new">
													<label>
														<span>' . __('Starts From', 'rental') . '</span>
														<input id="cs_spec_start_day" type="text" />
													</label>
													<label>
														<span>' . __('Valid Then', 'rental') . '</span>
														<input id="cs_spec_end_day" type="text" />
													</label>
												</div>
												<label>
													<span>' . $cs_require_days_txt . '</span>
													<input id="cs_offer_require" type="text" />
												</label>
												<div class="cs_get_discount"><input type="text" id="ofer_discount" /><small>%</small></div>
												<a class="price-btn" type="button" onclick="add_offers_to_vehicle(\'' . esc_js(admin_url('admin-ajax.php')) . '\', \'0\')">' . __('Add Offer', 'rental') . '</a>
												<div id="add_offers_to_btn"></div>
											</div>
										</div>';
            } else {
                $html .= '
										<div id="rum-price-plans" class="tab-pane">';
                if (sizeof($cust_query) > 0) {

                    $html .= '
											<div class="cs_select_vehicle">
												<div class="cs_selct_area">
													<select data-type="pricing">';
                    $html .= '<option value="">-- ' . __('Select Vehicle', 'rental') . ' --</option>';
                    foreach ($cust_query as $vehicle) {
                        $html .= '<option value="' . $vehicle->ID . '">' . get_the_title($vehicle->ID) . '</option>';
                    }
                    $html .= '
													</select>
												</div>
												<div id="cs_pricing_add"></div>
											</div>';
                    wp_reset_postdata();

                    $html .= '<div id="cs_pricing_cont"></div>';
                } else {
                    $html .= __('No Vehicle Found.', 'rental');
                }
                $html .= '
										</div>';
            }
            $html .= '
								</div>
							</div>
						</div>
					</form>';
            $html .= '
				</div>
			</div>';
            echo force_balance_tags($html, true);
        }

        // Update Pricing
        public function cs_update_pricing() {

            $exs_data = get_option("cs_price_options");

            $pricing_counter = 0;
            $pricing_array = $pricing = $pricedata = array();
            if (isset($_POST['cs_pricing_vehicle_array']) && !empty($_POST['cs_pricing_vehicle_array'])) {

                foreach ($_POST['cs_pricing_vehicle_array'] as $keys => $values) {
                    if ($values) {
                        $pricing_array['cs_pricing_vehicle'] = $_POST['cs_pricing_vehicle_array'][$pricing_counter];
                        if (isset($_POST['cs_brnch_name_array'][$values]) && sizeof($_POST['cs_brnch_name_array'][$values]) > 0) {
                            $branch_counter = 0;
                            foreach ($_POST['cs_brnch_name_array'][$values] as $b_keys => $branch) {

                                $plans_array = array();

                                $pricing_array['cs_pricing_branches']['adult_mon_price'][$branch_counter] = $_POST['cs_adult_mon_price'][$values][$branch_counter];

                                $pricing_array['cs_pricing_branches']['adult_tue_price'][$branch_counter] = $_POST['cs_adult_tue_price'][$values][$branch_counter];

                                $pricing_array['cs_pricing_branches']['adult_wed_price'][$branch_counter] = $_POST['cs_adult_wed_price'][$values][$branch_counter];

                                $pricing_array['cs_pricing_branches']['adult_thu_price'][$branch_counter] = $_POST['cs_adult_thu_price'][$values][$branch_counter];

                                $pricing_array['cs_pricing_branches']['adult_fri_price'][$branch_counter] = $_POST['cs_adult_fri_price'][$values][$branch_counter];

                                $pricing_array['cs_pricing_branches']['adult_sat_price'][$branch_counter] = $_POST['cs_adult_sat_price'][$values][$branch_counter];

                                $pricing_array['cs_pricing_branches']['adult_sun_price'][$branch_counter] = $_POST['cs_adult_sun_price'][$values][$branch_counter];
                                $branch_counter++;
                            }

                            if (isset($_POST['cs_spec_pr_start_day'][$values]) && sizeof($_POST['cs_spec_pr_start_day'][$values]) > 0) {
                                $spec_day_counter = 0;
                                foreach ($_POST['cs_spec_pr_start_day'][$values] as $b_keys => $branch) {

                                    $pricing_array['cs_plan_days']['start_date'][$spec_day_counter] = $_POST['cs_spec_pr_start_day'][$values][$spec_day_counter];
                                    $pricing_array['cs_plan_days']['end_date'][$spec_day_counter] = $_POST['cs_spec_pr_end_day'][$values][$spec_day_counter];
                                    $spec_day_counter++;
                                }
                            }

                            // Save Titles
                            if (isset($_POST['cs_spec_pr_range_title'][$values]) && sizeof($_POST['cs_spec_pr_range_title'][$values]) > 0) {
                                $spec_title_counter = 0;
                                foreach ($_POST['cs_spec_pr_range_title'][$values] as $b_keys => $branch) {
                                    $pricing_array['cs_plan_titles']['cs_titles'][$spec_title_counter] = $_POST['cs_spec_pr_range_title'][$values][$spec_title_counter];
                                    $spec_title_counter++;
                                }
                            }

                            if (isset($_POST['cs_plan_rand']) && sizeof($_POST['cs_plan_rand']) > 0) {
                                $spec_counter = 0;
                                $pricing_array['cs_plan_prices'] = array();

                                foreach ($_POST['cs_plan_rand'] as $b_keys => $branch) {


                                    $cs_plans = array();
                                    $pricing_id = CS_FUNCTIONS()->cs_generate_random_string(5);

                                    if (isset($_POST['cs_adult_mon_sp_price'][$branch]) && sizeof($_POST['cs_adult_mon_sp_price'][$branch]) > 0) {
                                        $cs_plan_counter = 0;
                                        foreach ($_POST['cs_adult_mon_sp_price'][$branch] as $p_keys => $plns) {
                                            $cs_plans['adult_mon_price'][$cs_plan_counter] = $_POST['cs_adult_mon_sp_price'][$branch][$cs_plan_counter];
                                            $cs_plans['adult_tue_price'][$cs_plan_counter] = $_POST['cs_adult_tue_sp_price'][$branch][$cs_plan_counter];
                                            $cs_plans['adult_wed_price'][$cs_plan_counter] = $_POST['cs_adult_wed_sp_price'][$branch][$cs_plan_counter];
                                            $cs_plans['adult_thu_price'][$cs_plan_counter] = $_POST['cs_adult_thu_sp_price'][$branch][$cs_plan_counter];
                                            $cs_plans['adult_fri_price'][$cs_plan_counter] = $_POST['cs_adult_fri_sp_price'][$branch][$cs_plan_counter];
                                            $cs_plans['adult_sat_price'][$cs_plan_counter] = $_POST['cs_adult_sat_sp_price'][$branch][$cs_plan_counter];
                                            $cs_plans['adult_sun_price'][$cs_plan_counter] = $_POST['cs_adult_sun_sp_price'][$branch][$cs_plan_counter];
                                            $cs_plan_counter++;
                                        }
                                    }

                                    $pricing_array['cs_plan_prices'][$spec_counter] = $cs_plans;
                                    $spec_counter++;
                                }
                            }
                        }

                        $pricing[$values] = $pricing_array;
                        $pricing_counter++;
                    }
                }

                if (is_array($exs_data) && sizeof($exs_data) > 0) {

                    foreach ($exs_data as $exs_key => $exs_val) {
                        $pricedata[$exs_key] = $exs_val;
                    }
                }

                if (is_array($pricing) && sizeof($pricing) > 0) {

                    foreach ($pricing as $p_key => $p_val) {
                        $pricedata[$p_key] = $p_val;
                    }
                }

                update_option("cs_price_options", $pricedata);
            }
        }

        // Update Offers
        public function cs_update_offers() {

            $pricing_counter = 0;
            $pricing_array = $pricing = $pricedata = array();
            if (isset($_POST['cs_offers_id']) && !empty($_POST['cs_offers_id'])) {

                foreach ($_POST['cs_offers_id'] as $keys => $values) {
                    if ($values) {
                        $pricing_array['cs_offer_id'] = $_POST['cs_offers_id'][$pricing_counter];

                        $pricing_array['name'] = $_POST['cs_spec_ofr_name'][$pricing_counter];
                        $pricing_array['vehicle'] = $_POST['cs_spec_ofr_vehicle'][$pricing_counter];
                        $pricing_array['start_date'] = $_POST['cs_spec_ofr_start_day'][$pricing_counter];
                        $pricing_array['end_date'] = $_POST['cs_spec_ofr_end_day'][$pricing_counter];
                        $pricing_array['min_days'] = $_POST['cs_spec_ofr_days'][$pricing_counter];
                        $pricing_array['discount'] = $_POST['cs_spec_discount'][$pricing_counter];

                        $pricing[$values] = $pricing_array;
                        $pricing_counter++;
                    }
                }

                update_option("cs_offers_options", $pricing);
            }
        }

        public function price_option_save() {

            $this->cs_update_pricing();
            $this->cs_update_offers();
            echo __("All Prices Saved", "rental");

            die();
        }

        public function price_val_show($price = '') {

            global $cs_plugin_options;
            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';
            if ($price <> '') {
                $html = '<small class="price_val">' . esc_attr($currency_sign . $price) . '</small>';
            } else {
                $html = '<small class="price_val"> - </small>';
            }
            return $html;
        }

        public function cs_get_vehicle_cont() {

            global $cs_plugin_options;
            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';

            $cs_price_options = get_option("cs_price_options");
            $cs_offers_options = get_option("cs_offers_options");
            $html = $cs_add_btn = '';

            $cs_result = array();

            $vehicle_id = isset($_POST['vehicle_id']) ? $_POST['vehicle_id'] : '';
            $cs_type = isset($_POST['type']) ? $_POST['type'] : '';
            if ($vehicle_id <> '') {


                $html .= '
					<div class="cs-vehicle-con" id="cs-vehicle-price-' . absint($vehicle_id) . '">
						<div class="cs-plan-border">
							<span class="plan-range">' . get_the_title($vehicle_id) . '</span>
							<a class="cs-edit-btn" style="margin-right:0px;" data-type="pricing" href="javascript:cs_createpop(\'cs_' . $vehicle_id . '_popup\',\'filter\')">Edit Prices</a>
						</div>
						<div class="cs-detail-con">';
                if ($cs_type == 'pricing') {
                    $html .= '
							<table class="cs-price-plans cs_prices_capacity" border="1" cellpadding="10" cellspacing="0">';
                    $html .=
                            '<thead>
									<tr>
										<th></th>
										<th>&nbsp;</th>
										<th>' . __('Monday', 'rental') . '</th>
										<th>' . __('Tuesday', 'rental') . '</th>
										<th>' . __('Wednesday', 'rental') . '</th>
										<th>' . __('Thursday', 'rental') . '</th>
										<th>' . __('Friday', 'rental') . '</th>
										<th>' . __('Saturday', 'rental') . '</th>
										<th>' . __('Sunday', 'rental') . '</th>
									</tr>
								</thead>';
                    $html .= $this->cs_week_days_prices($cs_price_options, $vehicle_id, 'cs_pricing_branches');
                    $html .= '
							</table>';
                }

                //Edit Default Prices
                $html .= '<div id="cs_' . esc_attr($vehicle_id) . '_popup" style="display: none;">
							<div class="cs-popup-header">
								<h5>' . get_the_title($vehicle_id) . '</h5>
								<span class="cs-pop-close" onclick="javascript:cs_remove_overlay(\'cs_' . esc_attr($vehicle_id) . '_popup\',\'append\')"> <i class="icon-times"></i></span>
							</div>
							<div class="cs-popup-content">';
                $html .= '<div class="cs_get_prices" id="cs_get_prices">
											<table class="cs-get-plans" border="1" cellpadding="5" cellspacing="0">
											<thead>
												<tr>
													<th><input name="cs_pricing_vehicle_array[]" type="hidden" value="' . absint($vehicle_id) . '" /></th>
													<th>&nbsp;</th>
													<th>' . __('Monday', 'rental') . '</th>
													<th>' . __('Tuesday', 'rental') . '</th>
													<th>' . __('Wednesday', 'rental') . '</th>
													<th>' . __('Thursday', 'rental') . '</th>
													<th>' . __('Friday', 'rental') . '</th>
													<th>' . __('Saturday', 'rental') . '</th>
													<th>' . __('Sunday', 'rental') . '</th>
												</tr>
											</thead>
											<tbody>';
                $html .= $this->cs_edit_week_days_prices($cs_price_options, $vehicle_id, 'cs_pricing_branches');
                $html .= '
											</tbody>
										  </table>';
                $html .= '<a class="price-btn" type="button"  onclick="javascript:price_option_save(\'' . esc_js(admin_url('admin-ajax.php')) . '\',\'' . esc_attr($vehicle_id) . '\');" >' . __('Update', 'rental') . '</a>
										</div>
									  </div>
									</div>';

                $html .= '
							<div id="cs_' . esc_attr($cs_type) . '_result">';
                if ($cs_type == 'pricing') {
                    $html .= $this->added_price_plan($cs_type, $vehicle_id, $cs_price_options);
                }

                $html .= '	
							</div>
						</div>
					</div>
					<div id="cs_' . esc_attr($cs_type) . '_popup" style="display: none;">
						<div class="cs-popup-header">
							<h5>' . get_the_title($vehicle_id) . '</h5>
							<span class="cs-pop-close" onclick="javascript:cs_remove_overlay(\'cs_' . esc_attr($cs_type) . '_popup\',\'append\')"> <i class="icon-times"></i></span>
						</div>
						<div class="cs-popup-content">';
                $html .= '
							<script type="text/javascript">
								jQuery(function(){
									cs_edit_prices();
									jQuery("#date_range_' . absint($vehicle_id) . '").dateRangePicker({
										separator : " to ",
										getValue: function()
										{
											if (jQuery("cs_spec_start_day").val() && jQuery("#cs_spec_end_day").val() )
												return jQuery("#cs_spec_start_day").val() + " to " + jQuery("#cs_spec_end_day").val();
											else
												return "";
										},
										setValue: function(s,s1,s2)
										{
											jQuery("#cs_spec_start_day").val(s1);
											jQuery("#cs_spec_end_day").val(s2);
										}
									});
								});
								
							</script>
							<div class="title-wrap">
								<label>
									<span>' . __('Title', 'rental') . '</span>
									<input name="cs_plan_spec_pr_title" id="cs_plan_spec_pr_title" type="text" value="" />
								</label>
							</div>
							<div class="strt-day" id="date_range_' . absint($vehicle_id) . '">
								<label>
									<span>' . __('Starts From', 'rental') . '</span>
									<input id="cs_spec_start_day" type="text" />
								</label>
								<label>
									<span>' . __('Valid Then', 'rental') . '</span>
									<input id="cs_spec_end_day" type="text" />
								</label>
							</div>';
                if ($cs_type == 'pricing') {
                    $html .= '
							<div class="cs_get_prices">
								<table class="cs-get-plans" border="1" cellpadding="5" cellspacing="0">
									<thead>
										<tr>
											<th><input id="cs_vehicle_id" type="hidden" value="' . absint($vehicle_id) . '" /></th>
											<th>&nbsp;</th>
											<th>' . __('Monday', 'rental') . '</th>
											<th>' . __('Tuesday', 'rental') . '</th>
											<th>' . __('Wednesday', 'rental') . '</th>
											<th>' . __('Thursday', 'rental') . '</th>
											<th>' . __('Friday', 'rental') . '</th>
											<th>' . __('Saturday', 'rental') . '</th>
											<th>' . __('Sunday', 'rental') . '</th>
										</tr>
									</thead>
									<tbody>';
                    $capi_counter = 0;
                    $html .= '
											<tr>
												<td rowspan="2" class="capacty_title">
													<span>' . get_the_title($vehicle_id) . '</span>
													<input id="cs_vehicle_name' . absint($capi_counter) . '" type="hidden" value="' . absint($vehicle_id) . '" />
												</td>
												<td class="cs-italic-title">' . sprintf(__('Prices(%s)', 'rental'), $currency_sign) . '</td>
												<td><input id="cs_adult_mon_price' . absint($capi_counter) . '" type="text" /></td>
												<td><input id="cs_adult_tue_price' . absint($capi_counter) . '" type="text" /></td>
												<td><input id="cs_adult_wed_price' . absint($capi_counter) . '" type="text" /></td>
												<td><input id="cs_adult_thu_price' . absint($capi_counter) . '" type="text" /></td>
												<td><input id="cs_adult_fri_price' . absint($capi_counter) . '" type="text" /></td>
												<td><input id="cs_adult_sat_price' . absint($capi_counter) . '" type="text" /></td>
												<td><input id="cs_adult_sun_price' . absint($capi_counter) . '" type="text" /></td>
											</tr>';

                    $html .= '
									</tbody>
								</table>
							</div>';
                } else {
                    $html .= '<input id="cs_vehicle_id" type="hidden" value="' . absint($vehicle_id) . '" /><div class="cs_get_discount"><input type="text" id="ofer_discount" /><small>%</small></div>';
                }
                $html .= '
                                            <a class="price-btn" type="button" onclick="add_' . esc_attr($cs_type) . '_to_vehicle(\'' . esc_js(admin_url('admin-ajax.php')) . '\', \'' . absint($vehicle_id) . '\')">' . sprintf(__('Add %s', 'rental'), ucfirst($cs_type)) . '</a>
                                            <div id="add_' . esc_attr($cs_type) . '_to_btn"></div>
                                    </div>
                            </div>';


                $cs_add_btn .= '<a class="cs-add-btn" data-type="' . esc_attr($cs_type) . '" href="javascript:cs_createpop(\'cs_' . esc_attr($cs_type) . '_popup\',\'filter\')">' . __('Add new Price Plan', 'rental') . '</a>';


                $cs_result['html'] = $html;
                $cs_result['btn'] = $cs_add_btn;

                echo json_encode($cs_result);
            }
            die();
        }

        public function cs_week_days_prices($cs_price_options, $vehicle_id, $cs_index) {

            global $cs_plugin_options;
            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';

            $cs_html = '<tbody>';

            $cs_brnch_counter = 0;


            $cs_branch_data = '';

            if (isset($cs_price_options[$vehicle_id])) {
                $cs_branch_data = $cs_price_options[$vehicle_id];
            }
            $cs_html .=
                '<tr>
                            <td rowspan="2" class="capacty_title">
                                    <span>' . get_the_title($vehicle_id) . '</span>
                            </td>
                            <td class="cs-italic-title">' . sprintf(__('Prices(%s)', 'rental'), $currency_sign) . '</td>';
            $cs_html .= $this->single_days_price($cs_branch_data, $vehicle_id, $cs_index, $cs_brnch_counter, 'adult', 'mon', '', 'html');
            $cs_html .= $this->single_days_price($cs_branch_data, $vehicle_id, $cs_index, $cs_brnch_counter, 'adult', 'tue', '', 'html');
            $cs_html .= $this->single_days_price($cs_branch_data, $vehicle_id, $cs_index, $cs_brnch_counter, 'adult', 'wed', '', 'html');
            $cs_html .= $this->single_days_price($cs_branch_data, $vehicle_id, $cs_index, $cs_brnch_counter, 'adult', 'thu', '', 'html');
            $cs_html .= $this->single_days_price($cs_branch_data, $vehicle_id, $cs_index, $cs_brnch_counter, 'adult', 'fri', '', 'html');
            $cs_html .= $this->single_days_price($cs_branch_data, $vehicle_id, $cs_index, $cs_brnch_counter, 'adult', 'sat', '', 'html');
            $cs_html .= $this->single_days_price($cs_branch_data, $vehicle_id, $cs_index, $cs_brnch_counter, 'adult', 'sun', '', 'html');
            $cs_html .= '
			</tr>';

            $cs_brnch_counter++;


            $cs_html .= '</tbody>';

            return force_balance_tags($cs_html);
        }

        public function cs_edit_week_days_prices($cs_price_options, $vehicle_id, $cs_index) {

            global $cs_plugin_options;
            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';

            $cs_html = '<tbody>';

            $cs_brnch_counter = 0;


            $cs_branch_data = '';

            if (isset($cs_price_options[$vehicle_id])) {

                $cs_branch_data = $cs_price_options[$vehicle_id];
            }

            $cs_html .=
                    '<tr>
                            <td rowspan="2" class="capacty_title">
                                    <input name="cs_brnch_name_array[' . absint($vehicle_id) . '][]" type="hidden" value="" />
                                    <span>' . get_the_title($vehicle_id) . '</span>
                            </td>
                            <td class="cs-italic-title">' . sprintf(__('Prices(%s)', 'rental'), $currency_sign) . '</td>';
            $cs_html .= $this->single_days_price($cs_branch_data, $vehicle_id, $cs_index, $cs_brnch_counter, 'adult', 'mon', '', 'input');
            $cs_html .= $this->single_days_price($cs_branch_data, $vehicle_id, $cs_index, $cs_brnch_counter, 'adult', 'tue', '', 'input');
            $cs_html .= $this->single_days_price($cs_branch_data, $vehicle_id, $cs_index, $cs_brnch_counter, 'adult', 'wed', '', 'input');
            $cs_html .= $this->single_days_price($cs_branch_data, $vehicle_id, $cs_index, $cs_brnch_counter, 'adult', 'thu', '', 'input');
            $cs_html .= $this->single_days_price($cs_branch_data, $vehicle_id, $cs_index, $cs_brnch_counter, 'adult', 'fri', '', 'input');
            $cs_html .= $this->single_days_price($cs_branch_data, $vehicle_id, $cs_index, $cs_brnch_counter, 'adult', 'sat', '', 'input');
            $cs_html .= $this->single_days_price($cs_branch_data, $vehicle_id, $cs_index, $cs_brnch_counter, 'adult', 'sun', '', 'input');
            $cs_html .= '
                                </tr>';

            $cs_brnch_counter++;


            $cs_html .= '</tbody>';

            return force_balance_tags($cs_html);
        }

        public function single_days_price($cs_data, $vehicle_id, $cs_index, $cs_counter = 0, $type = 'adult', $day = 'mon', $sp = '', $case = '') {
            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';
            $cs_price = '';
            if (isset($cs_data) && is_array($cs_data)) {
                $cs_price = isset($cs_data[$cs_index]["{$type}_{$day}_price"][$cs_counter]) ? $cs_data[$cs_index]["{$type}_{$day}_price"][$cs_counter] : '';
            }

            $cs_name = "cs_{$type}_{$day}{$sp}_price[{$vehicle_id}][]";
            if ($case == 'input') {
                $cs_html = '
                            <td class="price-action" data-currency="' . $currency_sign . '">
                                    <input name="' . $cs_name . '" type="text" value="' . esc_attr($cs_price) . '" />
                            </td>';
            } else {
                $cs_html = '
                            <td class="price-action" data-currency="' . $currency_sign . '">
                                    ' . $this->price_val_show($cs_price) . '
                            </td>';
            }
            return force_balance_tags($cs_html);
        }

        public function add_price_plan() {

            global $cs_plugin_options;
            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';

            $cs_html = '';

            $vehicle_id = isset($_POST['vehicle_id']) ? $_POST['vehicle_id'] : '';
            $cs_start_date = isset($_POST['cs_spec_start_day']) ? $_POST['cs_spec_start_day'] : '';
            $cs_end_date = isset($_POST['cs_spec_end_day']) ? $_POST['cs_spec_end_day'] : '';
            $cs_plan_spec_pr_title = isset($_POST['cs_plan_spec_pr_title']) ? $_POST['cs_plan_spec_pr_title'] : '';


            $rand_id = CS_FUNCTIONS()->cs_generate_random_string(5);

            $cs_html .= '
                            <div class="cs-price-plan">
                                    <div class="plan-header">
                                            <div class="cs-plan-border">
                                                    <span class="plan-range">' . strtoupper($cs_plan_spec_pr_title) . ': ' . esc_attr($cs_start_date) . ' ' . __('To', 'rental') . ' ' . esc_attr($cs_end_date) . '</span>
                                                    <input name="cs_plan_rand[]" type="hidden" value="' . $rand_id . '" />
                                                    <a class="cs-remove">' . __('Delete', 'rental') . '</a>
                                                    <a class="cs-edit-btn" data-type="price" href="javascript:cs_createpop(\'cs_' . esc_attr($rand_id) . '_popup\',\'filter\')">' . __('Edit Prices', 'rental') . '</a>
                                            </div>
                                            <table class="cs-price-plans" border="1" cellpadding="10" cellspacing="0">
                                                    <thead>
                                                            <tr>
                                                                    <th>&nbsp;</th>
                                                                    <th>&nbsp;</th>
                                                                    <th>' . __('Monday', 'rental') . '</th>
                                                                    <th>' . __('Tuesday', 'rental') . '</th>
                                                                    <th>' . __('Wednesday', 'rental') . '</th>
                                                                    <th>' . __('Thursday', 'rental') . '</th>
                                                                    <th>' . __('Friday', 'rental') . '</th>
                                                                    <th>' . __('Saturday', 'rental') . '</th>
                                                                    <th>' . __('Sunday', 'rental') . '</th>
                                                            </tr>
                                                    </thead>
                                                    <tbody>';
            $cap_counter = 0;

            $adult_mon = isset($_POST['cs_adult_mon_price'][$cap_counter]) ? $_POST['cs_adult_mon_price'][$cap_counter] : '';
            $adult_tue = isset($_POST['cs_adult_tue_price'][$cap_counter]) ? $_POST['cs_adult_tue_price'][$cap_counter] : '';
            $adult_wed = isset($_POST['cs_adult_wed_price'][$cap_counter]) ? $_POST['cs_adult_wed_price'][$cap_counter] : '';
            $adult_thu = isset($_POST['cs_adult_thu_price'][$cap_counter]) ? $_POST['cs_adult_thu_price'][$cap_counter] : '';
            $adult_fri = isset($_POST['cs_adult_fri_price'][$cap_counter]) ? $_POST['cs_adult_fri_price'][$cap_counter] : '';
            $adult_sat = isset($_POST['cs_adult_sat_price'][$cap_counter]) ? $_POST['cs_adult_sat_price'][$cap_counter] : '';
            $adult_sun = isset($_POST['cs_adult_sun_price'][$cap_counter]) ? $_POST['cs_adult_sun_price'][$cap_counter] : '';

            $cs_html .= '
                                <tr>
                                        <td rowspan="2" class="capacty_title">
                                                <span>' . get_the_title($vehicle_id) . '</span>
                                        </td>
                                        <td class="cs-italic-title">' . sprintf(__('Prices(%s)', 'rental'), $currency_sign) . '</td>
                                        <td>' . $this->price_val_show($adult_mon) . '</td>
                                        <td>' . $this->price_val_show($adult_tue) . '</td>
                                        <td>' . $this->price_val_show($adult_wed) . '</td>
                                        <td>' . $this->price_val_show($adult_thu) . '</td>
                                        <td>' . $this->price_val_show($adult_fri) . '</td>
                                        <td>' . $this->price_val_show($adult_sat) . '</td>
                                        <td>' . $this->price_val_show($adult_sun) . '</td>
                                </tr>';

            $cs_html .= '
                                            </tbody>
                                    </table>';

            //Edit Mode
            $cs_html .= '<div id="cs_' . esc_attr($rand_id) . '_popup" style="display: none;">
                                    <div class="cs-popup-header">
                                            <h5>' . get_the_title($vehicle_id) . '</h5>
                                            <span class="cs-pop-close" onclick="javascript:cs_remove_overlay(\'cs_' . esc_attr($rand_id) . '_popup\',\'append\')"> <i class="icon-times"></i></span>
                                    </div>
                                    <div class="cs-popup-content">';

            $cs_html .= '<script type="text/javascript">
                                                    jQuery(function(){
                                                            jQuery("#date_range_' . esc_attr($rand_id) . '").dateRangePicker({
                                                                    separator : " to ",
                                                                    getValue: function()
                                                                    {
                                                                            if (jQuery("cs_spec_start_day_' . esc_attr($rand_id) . '").val() && jQuery("#cs_spec_end_day_' . esc_attr($rand_id) . '").val() )
                                                                                    return jQuery("#cs_spec_start_day_' . esc_attr($rand_id) . '").val() + " to " + jQuery("#cs_spec_end_day_' . esc_attr($rand_id) . '").val();
                                                                            else
                                                                                    return "";
                                                                    },
                                                                    setValue: function(s,s1,s2)
                                                                    {
                                                                            jQuery("#cs_spec_start_day_' . esc_attr($rand_id) . '").val(s1);
                                                                            jQuery("#cs_spec_end_day_' . esc_attr($rand_id) . '").val(s2);
                                                                    }
                                                            });
                                                    });

                                            </script>

                                                        <div class="title-wrap">
                                                                <label>
                                                                        <span>' . __('Title', 'rental') . '</span>
                                                                        <input name="cs_spec_pr_range_title[' . absint($vehicle_id) . '][]" id="cs_spec_pr_range_title" type="text" value="' . esc_attr($cs_plan_spec_pr_title) . '" />
                                                                </label>
                                                        </div>
                                                        <div class="strt-day" id="date_range_' . esc_attr($rand_id) . '">
                                                                <label>
                                                                        <span>' . __('Starts From', 'rental') . '</span>
                                                                        <input name="cs_spec_pr_start_day[' . absint($vehicle_id) . '][]" id="cs_spec_start_day_' . esc_attr($rand_id) . '"  type="text" value="' . esc_attr($cs_start_date) . '" />
                                                                </label>
                                                                <label>
                                                                        <span>' . __('Valid Then', 'rental') . '</span>
                                                                        <input name="cs_spec_pr_end_day[' . absint($vehicle_id) . '][]"  type="text" id="cs_spec_end_day_' . esc_attr($rand_id) . '" value="' . esc_attr($cs_end_date) . '" />
                                                                </label>
                                                        </div>';

            $cs_html .= '<div class="cs_get_prices">
                                                    <table class="cs-get-plans" border="1" cellpadding="5" cellspacing="0">
                                                    <thead>
                                                            <tr>
                                                                    <th><input id="cs_vehicle_id" type="hidden" value="' . absint($vehicle_id) . '" /></th>
                                                                    <th>&nbsp;</th>
                                                                    <th>' . __('Monday', 'rental') . '</th>
                                                                    <th>' . __('Tuesday', 'rental') . '</th>
                                                                    <th>' . __('Wednesday', 'rental') . '</th>
                                                                    <th>' . __('Thursday', 'rental') . '</th>
                                                                    <th>' . __('Friday', 'rental') . '</th>
                                                                    <th>' . __('Saturday', 'rental') . '</th>
                                                                    <th>' . __('Sunday', 'rental') . '</th>
                                                            </tr>
                                                    </thead>
                                                    <tbody>';

            $cap_counter = 0;


            $adult_mon = isset($_POST['cs_adult_mon_price'][$cap_counter]) ? $_POST['cs_adult_mon_price'][$cap_counter] : '';
            $adult_tue = isset($_POST['cs_adult_tue_price'][$cap_counter]) ? $_POST['cs_adult_tue_price'][$cap_counter] : '';
            $adult_wed = isset($_POST['cs_adult_wed_price'][$cap_counter]) ? $_POST['cs_adult_wed_price'][$cap_counter] : '';
            $adult_thu = isset($_POST['cs_adult_thu_price'][$cap_counter]) ? $_POST['cs_adult_thu_price'][$cap_counter] : '';
            $adult_fri = isset($_POST['cs_adult_fri_price'][$cap_counter]) ? $_POST['cs_adult_fri_price'][$cap_counter] : '';
            $adult_sat = isset($_POST['cs_adult_sat_price'][$cap_counter]) ? $_POST['cs_adult_sat_price'][$cap_counter] : '';
            $adult_sun = isset($_POST['cs_adult_sun_price'][$cap_counter]) ? $_POST['cs_adult_sun_price'][$cap_counter] : '';
            $tr_class = isset($tr_class) ? $tr_class : '';

            $cs_html .= '
                                <tr class="' . sanitize_html_class($tr_class) . '">
                                        <td rowspan="2" class="capacty_title">
                                                <span>' . get_the_title($vehicle_id) . '</span>
                                        </td>
                                        <td class="cs-italic-title">' . sprintf(__('Adult %s', 'rental'), $currency_sign) . '</td>
                                        <td><input name="cs_adult_mon_sp_price[' . $rand_id . '][]" type="text" value="' . $adult_mon . '" /></td>
                                        <td><input name="cs_adult_tue_sp_price[' . $rand_id . '][]" type="text" value="' . $adult_tue . '" /></td>
                                        <td><input name="cs_adult_wed_sp_price[' . $rand_id . '][]" type="text" value="' . $adult_wed . '" /></td>
                                        <td><input name="cs_adult_thu_sp_price[' . $rand_id . '][]" type="text" value="' . $adult_thu . '" /></td>
                                        <td><input name="cs_adult_fri_sp_price[' . $rand_id . '][]" type="text" value="' . $adult_fri . '" /></td>
                                        <td><input name="cs_adult_sat_sp_price[' . $rand_id . '][]" type="text" value="' . $adult_sat . '" /></td>
                                        <td><input name="cs_adult_sun_sp_price[' . $rand_id . '][]" type="text" value="' . $adult_sun . '" /></td>
                                </tr>';


            $cs_html .= '
                                                </tbody>
                                        </table>
                                </div>';
            $cs_html .= '<a class="price-btn" type="button" onclick="javascript:cs_remove_overlay(\'cs_' . esc_attr($rand_id) . '_popup\',\'append\')">' . __('Update', 'rental') . '</a>
								<div id="add_price_to_btn"></div>
							</div>
						  </div>
				</div>
			</div>';

            echo force_balance_tags($cs_html);
            die();
        }

        public function added_price_plan($cs_type, $vehicle_id, $cs_price_options) {

            global $cs_plugin_options;
            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';

            $cs_plans_data = '';
            if (isset($cs_price_options[$vehicle_id]['cs_plan_prices'])) {
                $cs_plans_data = $cs_price_options[$vehicle_id]['cs_plan_prices'];
            }



            $cs_days_data = '';

            if (isset($cs_price_options[$vehicle_id]['cs_plan_days'])) {
                $cs_days_data = $cs_price_options[$vehicle_id]['cs_plan_days'];
            }

            $cs_titles_data = '';

            if (isset($cs_price_options[$vehicle_id]['cs_plan_titles'])) {
                $cs_titles_data = $cs_price_options[$vehicle_id]['cs_plan_titles'];
            }

            $cs_html = '';

            if (is_array($cs_days_data) && sizeof($cs_days_data) > 0 && isset($cs_days_data['start_date'][0])) {

                $plan_days_contr = 0;
                foreach ($cs_days_data['start_date'] as $date_key => $plan_days) {

                    $cs_start_date = isset($cs_days_data['start_date'][$plan_days_contr]) ? $cs_days_data['start_date'][$plan_days_contr] : '';
                    $cs_end_date = isset($cs_days_data['end_date'][$plan_days_contr]) ? $cs_days_data['end_date'][$plan_days_contr] : '';
                    $cs_title = isset($cs_titles_data['cs_titles'][$plan_days_contr]) ? $cs_titles_data['cs_titles'][$plan_days_contr] : '';
                    $rand_id = CS_FUNCTIONS()->cs_generate_random_string(5);

                    $cs_html .= '
				<div class="cs-price-plan">
					<div class="plan-header">
						<div class="cs-plan-border">
							<span class="plan-range">' . strtoupper($cs_title) . ': ' . esc_attr($cs_start_date) . ' ' . __('To', 'rental') . ' ' . esc_attr($cs_end_date) . '</span>
							<input name="cs_plan_rand[]" type="hidden" value="' . $rand_id . '" />
							<a class="cs-remove">' . __('Delete', 'rental') . '</a>
							<a class="cs-edit-btn" data-type="' . esc_attr($cs_type) . '" href="javascript:cs_createpop(\'cs_' . esc_attr($rand_id) . '_popup\',\'filter\')">' . __('Edit Prices', 'rental') . '</a>
						</div>
						<table class="cs-price-plans" border="1" cellpadding="10" cellspacing="0">
							<thead>
								<tr>
									<th>&nbsp;</th>
									<th>&nbsp;</th>
									<th>' . __('Monday', 'rental') . '</th>
									<th>' . __('Tuesday', 'rental') . '</th>
									<th>' . __('Wednesday', 'rental') . '</th>
									<th>' . __('Thursday', 'rental') . '</th>
									<th>' . __('Friday', 'rental') . '</th>
									<th>' . __('Saturday', 'rental') . '</th>
									<th>' . __('Sunday', 'rental') . '</th>
								</tr>
							</thead>
							<tbody>';

                    if (is_array($cs_plans_data) && sizeof($cs_plans_data) > 0) {
                        $cap_counter = 0;
                        $r_titles = isset($cs_plans_data[$date_key]['cs_titles_data'][$cap_counter]) ? $cs_plans_data[$date_key]['cs_titles_data'][$cap_counter] : '';
                        $adult_mon = isset($cs_plans_data[$date_key]['adult_mon_price'][$cap_counter]) ? $cs_plans_data[$date_key]['adult_mon_price'][$cap_counter] : '';
                        $adult_tue = isset($cs_plans_data[$date_key]['adult_tue_price'][$cap_counter]) ? $cs_plans_data[$date_key]['adult_tue_price'][$cap_counter] : '';
                        $adult_wed = isset($cs_plans_data[$date_key]['adult_wed_price'][$cap_counter]) ? $cs_plans_data[$date_key]['adult_wed_price'][$cap_counter] : '';
                        $adult_thu = isset($cs_plans_data[$date_key]['adult_thu_price'][$cap_counter]) ? $cs_plans_data[$date_key]['adult_thu_price'][$cap_counter] : '';
                        $adult_fri = isset($cs_plans_data[$date_key]['adult_fri_price'][$cap_counter]) ? $cs_plans_data[$date_key]['adult_fri_price'][$cap_counter] : '';
                        $adult_sat = isset($cs_plans_data[$date_key]['adult_sat_price'][$cap_counter]) ? $cs_plans_data[$date_key]['adult_sat_price'][$cap_counter] : '';
                        $adult_sun = isset($cs_plans_data[$date_key]['adult_sun_price'][$cap_counter]) ? $cs_plans_data[$date_key]['adult_sun_price'][$cap_counter] : '';
                        $cs_html .= '
								<tr>
									<td rowspan="2" class="capacty_title">
										<span>' . get_the_title($vehicle_id) . '</span>
									</td>
									<td class="cs-italic-title">' . sprintf(__('Prices(%s)', 'rental'), $currency_sign) . '</td>
									<td>' . $this->price_val_show($adult_mon) . '</td>
									<td>' . $this->price_val_show($adult_tue) . '</td>
									<td>' . $this->price_val_show($adult_wed) . '</td>
									<td>' . $this->price_val_show($adult_thu) . '</td>
									<td>' . $this->price_val_show($adult_fri) . '</td>
									<td>' . $this->price_val_show($adult_sat) . '</td>
									<td>' . $this->price_val_show($adult_sun) . '</td>
								</tr>';
                    }
                    $cs_html .= '
							</tbody>
						</table>';

                    $cs_html .= '<div id="cs_' . esc_attr($rand_id) . '_popup" style="display: none;">
										<div class="cs-popup-header">
											<h5>' . get_the_title($vehicle_id) . '</h5>
											<span class="cs-pop-close" onclick="javascript:cs_remove_overlay(\'cs_' . esc_attr($rand_id) . '_popup\',\'append\')"> <i class="icon-times"></i></span>
										</div>
										<div class="cs-popup-content">';
                    $cs_html .= '<script type="text/javascript">
														jQuery(function(){
															jQuery("#date_range_' . esc_attr($rand_id) . '").dateRangePicker({
																separator : " to ",
																getValue: function()
																{
																	if (jQuery("cs_spec_start_day_' . esc_attr($rand_id) . '").val() && jQuery("#cs_spec_end_day_' . esc_attr($rand_id) . '").val() )
																		return jQuery("#cs_spec_start_day_' . esc_attr($rand_id) . '").val() + " to " + jQuery("#cs_spec_end_day_' . esc_attr($rand_id) . '").val();
																	else
																		return "";
																},
																setValue: function(s,s1,s2)
																{
																	jQuery("#cs_spec_start_day_' . esc_attr($rand_id) . '").val(s1);
																	jQuery("#cs_spec_end_day_' . esc_attr($rand_id) . '").val(s2);
																}
															});
														});
										
													</script>
													<div class="title-wrap">
														<label>
															<span>' . __('Title', 'rental') . '</span>
															<input name="cs_spec_pr_range_title[' . absint($vehicle_id) . '][]" id="cs_spec_pr_range_title" type="text" value="' . esc_attr($cs_title) . '" />
														</label>
													</div>
													<div class="strt-day" id="date_range_' . esc_attr($rand_id) . '">
														<label>
															<span>' . __('Starts From', 'rental') . '</span>
															<input name="cs_spec_pr_start_day[' . absint($vehicle_id) . '][]" id="cs_spec_start_day_' . esc_attr($rand_id) . '"  type="text" value="' . esc_attr($cs_start_date) . '" />
														</label>
														<label>
															<span>' . __('Valid Then', 'rental') . '</span>
															<input name="cs_spec_pr_end_day[' . absint($vehicle_id) . '][]" type="text" id="cs_spec_end_day_' . esc_attr($rand_id) . '" value="' . esc_attr($cs_end_date) . '" />
														</label>
													</div>
													<div class="cs_get_prices">
														<table class="cs-get-plans" border="1" cellpadding="5" cellspacing="0">
														<thead>
															<tr>
																<th><input id="cs_vehicle_id" type="hidden" value="' . absint($vehicle_id) . '" /></th>
																<th>&nbsp;</th>
																<th>' . __('Monday', 'rental') . '</th>
																<th>' . __('Tuesday', 'rental') . '</th>
																<th>' . __('Wednesday', 'rental') . '</th>
																<th>' . __('Thursday', 'rental') . '</th>
																<th>' . __('Friday', 'rental') . '</th>
																<th>' . __('Saturday', 'rental') . '</th>
																<th>' . __('Sunday', 'rental') . '</th>
															</tr>
														</thead>
														<tbody>';
                    if (is_array($cs_plans_data) && sizeof($cs_plans_data) > 0) {

                        $cap_counter = 0;
                        $adult_mon = isset($cs_plans_data[$date_key]['adult_mon_price'][$cap_counter]) ? $cs_plans_data[$date_key]['adult_mon_price'][$cap_counter] : '';
                        $adult_tue = isset($cs_plans_data[$date_key]['adult_tue_price'][$cap_counter]) ? $cs_plans_data[$date_key]['adult_tue_price'][$cap_counter] : '';
                        $adult_wed = isset($cs_plans_data[$date_key]['adult_wed_price'][$cap_counter]) ? $cs_plans_data[$date_key]['adult_wed_price'][$cap_counter] : '';
                        $adult_thu = isset($cs_plans_data[$date_key]['adult_thu_price'][$cap_counter]) ? $cs_plans_data[$date_key]['adult_thu_price'][$cap_counter] : '';
                        $adult_fri = isset($cs_plans_data[$date_key]['adult_fri_price'][$cap_counter]) ? $cs_plans_data[$date_key]['adult_fri_price'][$cap_counter] : '';
                        $adult_sat = isset($cs_plans_data[$date_key]['adult_sat_price'][$cap_counter]) ? $cs_plans_data[$date_key]['adult_sat_price'][$cap_counter] : '';
                        $adult_sun = isset($cs_plans_data[$date_key]['adult_sun_price'][$cap_counter]) ? $cs_plans_data[$date_key]['adult_sun_price'][$cap_counter] : '';

                        $cs_html .= '
															<tr>
																<td rowspan="2" class="capacty_title">
																	<span>' . get_the_title($vehicle_id) . '</span>
																</td>
																<td class="cs-italic-title">' . sprintf(__('Adult %s', 'rental'), $currency_sign) . '</td>
																<td><input name="cs_adult_mon_sp_price[' . $rand_id . '][]" type="text" value="' . $adult_mon . '" /></td>
																<td><input name="cs_adult_tue_sp_price[' . $rand_id . '][]" type="text" value="' . $adult_tue . '" /></td>
																<td><input name="cs_adult_wed_sp_price[' . $rand_id . '][]" type="text" value="' . $adult_wed . '" /></td>
																<td><input name="cs_adult_thu_sp_price[' . $rand_id . '][]" type="text" value="' . $adult_thu . '" /></td>
																<td><input name="cs_adult_fri_sp_price[' . $rand_id . '][]" type="text" value="' . $adult_fri . '" /></td>
																<td><input name="cs_adult_sat_sp_price[' . $rand_id . '][]" type="text" value="' . $adult_sat . '" /></td>
																<td><input name="cs_adult_sun_sp_price[' . $rand_id . '][]" type="text" value="' . $adult_sun . '" /></td>
															</tr>';
                    }

                    $cs_html .= '
												</tbody>
											</table>
										</div>';
                    $cs_html .= '<a class="price-btn" type="button"  onclick="javascript:price_option_save(\'' . esc_js(admin_url('admin-ajax.php')) . '\',\'' . esc_attr($rand_id) . '\');" >' . __('Update', 'rental') . '</a>
									<div id="add_' . esc_attr($cs_type) . '_to_btn"></div>
								</div>
							  </div>
						  </div>
					</div>';
                    $plan_days_contr++;
                }
            }

            return force_balance_tags($cs_html);
        }

        public function add_price_offer() {
            global $cs_plugin_options;
            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';
            $json = array();
            $cs_ofer_counter = time();

            $cs_html = '';
            $cs_offer_name = isset($_POST['cs_offer_name']) ? $_POST['cs_offer_name'] : '';
            $cs_start_date = isset($_POST['cs_spec_start_day']) ? $_POST['cs_spec_start_day'] : '';
            $cs_end_date = isset($_POST['cs_spec_end_day']) ? $_POST['cs_spec_end_day'] : '';
            $cs_offer_require = isset($_POST['cs_offer_require']) ? $_POST['cs_offer_require'] : '';
            $cs_discount = isset($_POST['ofer_discount']) ? $_POST['ofer_discount'] : '';
            $cs_offer_vehicle = isset($_POST['cs_offer_vehicle']) ? $_POST['cs_offer_vehicle'] : '';

            // Check if Date Already Exist
            $offers_data = get_option("cs_offers_options");

            if (isset($offers_data) && !empty($offers_data)) {

                $new_date = date('Y-m-d', strtotime($cs_start_date));
                $to_check_date = strtotime($new_date);

                foreach ($offers_data as $keys => $values) {
                    if ($values) {
                        $start_date = $values['start_date'];
                        $end_date = $values['end_date'];

                        $offer_start_date = date('Y-m-d', strtotime($values['start_date']));
                        $offer_end_date = date('Y-m-d', strtotime($values['end_date']));

                        $offer_start_date = strtotime($offer_start_date);
                        $offer_end_date = strtotime($offer_end_date);

                        /* if( $to_check_date  >= $offer_start_date &&  $to_check_date  <= $offer_end_date  ) {
                          $json['type']		= 'error';
                          $json['message']	= 'Date already exist.';
                          echo json_encode($json);
                          die();
                          break;
                          } */
                    }
                }
            }

            $cs_html .= '
			<tr>
				<td>
					<span class="plan-range">' . esc_attr($cs_start_date) . ' ' . __('To', 'rental') . ' ' . esc_attr($cs_end_date) . '</span>
				</td>
				<td>
					<span class="plan-range">' . esc_attr($cs_offer_name) . '</span>
				</td>
				<td>
					<span class="plan-discount">' . absint($cs_discount) . '</span>
				</td>
				<td>
					<span class="plan-range">' . absint($cs_offer_require) . ' ' . __('Days', 'rental') . '</span>
				</td>
				<td>
					<a class="offer-delete"><i class="icon-trash4"></i></a>
					<a class="offer-edit" onclick="javascript:cs_createpop(\'cs_offer_popup' . absint($cs_ofer_counter) . '\',\'filter\')"><i class="icon-pencil3"></i></a>
					<div id="cs_offer_popup' . absint($cs_ofer_counter) . '" style="display: none;">
						<div class="cs-popup-header">
							<h5>' . __('Edit Offer', 'rental') . '</h5>
							<span class="cs-pop-close" onclick="javascript:cs_remove_overlay(\'cs_offer_popup' . absint($cs_ofer_counter) . '\',\'append\')"> <i class="icon-times"></i></span>
						</div>
						<div class="cs-popup-content">';
            $cs_html .= '
							<input name="cs_offers_id[]" value="' . absint($cs_ofer_counter) . '" type="hidden" />
							<script type="text/javascript">
								jQuery(function(){
									jQuery("#date_range_' . absint($cs_ofer_counter) . '").dateRangePicker({
										separator : " to ",
										getValue: function()
										{
											if (jQuery("cs_spec_start_day' . absint($cs_ofer_counter) . '").val() && jQuery("#cs_spec_end_day' . absint($cs_ofer_counter) . '").val() )
												return jQuery("#cs_spec_start_day' . absint($cs_ofer_counter) . '").val() + " to " + jQuery("#cs_spec_end_day' . absint($cs_ofer_counter) . '").val();
											else
												return "";
										},
										setValue: function(s,s1,s2)
										{
											jQuery("#cs_spec_start_day' . absint($cs_ofer_counter) . '").val(s1);
											jQuery("#cs_spec_end_day' . absint($cs_ofer_counter) . '").val(s2);
										}
									});
								});
				
							</script>
							<label>
								<span>' . __('Offer Name', 'rental') . '</span>
								<input name="cs_spec_ofr_name[]" value="' . esc_attr($cs_offer_name) . '" type="text" />
							</label>
							<label class="cs_select">
								<span>' . __('Select Vehicle', 'rental') . '</span>
								<select name="cs_spec_ofr_vehicle[]">';
            $cs_html .= '<option value="">-- ' . __('All Vehicle', 'rental') . ' --</option>';
            $cs_args = array('posts_per_page' => '-1', 'post_type' => 'vehicles', 'orderby' => 'ID', 'post_status' => 'publish');
            $cust_query = get_posts($cs_args);
            foreach ($cust_query as $vehicle) {
                $cs_selected = $vehicle->ID == $cs_offer_vehicle ? ' selected="selected"' : '';
                $cs_html .= '<option value="' . $vehicle->ID . '"' . $cs_selected . '>' . get_the_title($vehicle->ID) . '</option>';
            }
            wp_reset_postdata();
            $cs_html .= '
								</select>
							</label>
							<div class="strt-day" id="date_range_' . absint($cs_ofer_counter) . '">
								<label>
									<span>' . __('Starts From', 'rental') . '</span>
									<input id="cs_spec_start_day' . absint($cs_ofer_counter) . '" name="cs_spec_ofr_start_day[]" value="' . esc_attr($cs_start_date) . '" type="text" />
								</label>
								<label>
									<span>' . __('Valid Then', 'rental') . '</span>
									<input id="cs_spec_end_day' . absint($cs_ofer_counter) . '" name="cs_spec_ofr_end_day[]" value="' . esc_attr($cs_end_date) . '" type="text" />
									
								</label>
							</div>
							<label>
								<span>' . $cs_require_days_txt . '</span>
								<input name="cs_spec_ofr_days[]" value="' . absint($cs_offer_require) . '" type="text" />
							</label>
							<div class="cs_get_discount">
							<span>' . __('Discount', 'rental') . '</span>
							<input type="text" name="cs_spec_discount[]" value="' . absint($cs_discount) . '" /><small>%</small></div>
							<a class="price-btn" type="button" onclick="javascript:cs_remove_overlay(\'cs_offer_popup' . absint($cs_ofer_counter) . '\',\'append\')">' . __('Edit Offer', 'rental') . '</a>
						</div>
					</div>
				</td>
			</tr>';

            //$data	= force_balance_tags($cs_html);
            $json['type'] = 'success';
            $json['message'] = $cs_html;
            echo json_encode($json);
            die();
        }

        public function added_price_offer($cs_price_options) {
            global $cs_plugin_options;

            $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';

            $cs_charge_base = isset($cs_plugin_options['cs_charge_base']) ? $cs_plugin_options['cs_charge_base'] : '';

            if ($cs_charge_base == 'hourly') {
                $cs_require_days_txt = __('Require Hours', 'rental');
            } else {
                $cs_require_days_txt = __('Require Days', 'rental');
            }

            $cs_html = '';

            if (is_array($cs_price_options) && sizeof($cs_price_options) > 0) {

                foreach ($cs_price_options as $ofer_days) {
                    $cs_ofer_counter = isset($ofer_days['cs_offer_id']) ? $ofer_days['cs_offer_id'] : '';
                    $cs_offer_name = isset($ofer_days['name']) ? $ofer_days['name'] : '';
                    $cs_offer_vehicle = isset($ofer_days['vehicle']) ? $ofer_days['vehicle'] : '';
                    $cs_start_date = isset($ofer_days['start_date']) ? $ofer_days['start_date'] : '';
                    $cs_end_date = isset($ofer_days['end_date']) ? $ofer_days['end_date'] : '';
                    $cs_offer_require = isset($ofer_days['min_days']) ? $ofer_days['min_days'] : '';
                    $cs_discount = isset($ofer_days['discount']) ? $ofer_days['discount'] : '';
                    $cs_html .= '
					<tr>
						<td>
							<span class="plan-range">' . esc_attr($cs_start_date) . ' ' . __('To', 'rental') . ' ' . esc_attr($cs_end_date) . '</span>
						</td>
						<td>
							<span class="plan-range">' . esc_attr($cs_offer_name) . '</span>
						</td>
						<td>
							<span class="plan-discount">' . absint($cs_discount) . '</span>
						</td>
						<td>
							<span class="plan-range">' . absint($cs_offer_require) . ' ' . __('Days', 'rental') . '</span>
						</td>
						<td>
							<a class="offer-delete"><i class="icon-trash4"></i></a>
							<a class="offer-edit" onclick="javascript:cs_createpop(\'cs_offer_popup' . absint($cs_ofer_counter) . '\',\'filter\')"><i class="icon-pencil3"></i></a>
							<div id="cs_offer_popup' . absint($cs_ofer_counter) . '" style="display: none;">
								<div class="cs-popup-header">
									<h5>' . __('Edit Offer', 'rental') . '</h5>
									<span class="cs-pop-close" onclick="javascript:cs_remove_overlay(\'cs_offer_popup' . absint($cs_ofer_counter) . '\',\'append\')"> <i class="icon-times"></i></span>
								</div>
								<div class="cs-popup-content">';
                    			$cs_html .= '
									<input name="cs_offers_id[]" value="' . absint($cs_ofer_counter) . '" type="hidden" />
									<script type="text/javascript">
										jQuery(function(){
											jQuery("#date_range_' . absint($cs_ofer_counter) . '").dateRangePicker({
												separator : " to ",
												getValue: function() {
													if (jQuery("cs_spec_start_day' . absint($cs_ofer_counter) . '").val() && jQuery("#cs_spec_end_day' . absint($cs_ofer_counter) . '").val() )
														return jQuery("#cs_spec_start_day' . absint($cs_ofer_counter) . '").val() + " to " + jQuery("#cs_spec_end_day' . absint($cs_ofer_counter) . '").val();
													else
														return "";
												},
												setValue: function(s,s1,s2)
												{
													jQuery("#cs_spec_start_day' . absint($cs_ofer_counter) . '").val(s1);
													jQuery("#cs_spec_end_day' . absint($cs_ofer_counter) . '").val(s2);
												}
											});
										});
						
									</script>
									<label>
										<span>' . __('Offer Name', 'rental') . '</span>
										<input name="cs_spec_ofr_name[]" value="' . esc_attr($cs_offer_name) . '" type="text" />
									</label>
									<label class="cs_select">
										<span>' . __('Select Vehicle', 'rental') . '</span>
										<select name="cs_spec_ofr_vehicle[]">';
										$cs_html .= '<option value="">-- ' . __('All Vehicle', 'rental') . ' --</option>';
										$cs_args = array('posts_per_page' => '-1', 'post_type' => 'vehicles', 'orderby' => 'ID', 'post_status' => 'publish');
										$cust_query = get_posts($cs_args);
										foreach ($cust_query as $vehicle) {
											$cs_selected = $vehicle->ID == $cs_offer_vehicle ? ' selected="selected"' : '';
											$cs_html .= '<option value="' . $vehicle->ID . '"' . $cs_selected . '>' . get_the_title($vehicle->ID) . '</option>';
										}
										wp_reset_postdata();
										$cs_html .= '
										</select>
									</label>
									<div class="strt-day" id="date_range_' . absint($cs_ofer_counter) . '">
										<label>
											<span>' . __('Starts From', 'rental') . '</span>
											<input id="cs_spec_start_day' . absint($cs_ofer_counter) . '" name="cs_spec_ofr_start_day[]" value="' . esc_attr($cs_start_date) . '" type="text" />
										</label>
										<label>
											<span>' . __('Valid Then', 'rental') . '</span>
											<input id="cs_spec_end_day' . absint($cs_ofer_counter) . '" name="cs_spec_ofr_end_day[]" value="' . esc_attr($cs_end_date) . '" type="text" />
										</label>
									</div>
									<label>
										<span>' . $cs_require_days_txt . '</span>
										<input name="cs_spec_ofr_days[]" value="' . absint($cs_offer_require) . '" type="text" />
									</label>
									<div class="cs_get_discount"><input type="text" name="cs_spec_discount[]" value="' . absint($cs_discount) . '" /><small>%</small></div>
									<a class="price-btn" onclick="javascript:cs_remove_overlay(\'cs_offer_popup' . absint($cs_ofer_counter) . '\',\'append\')">' . __('Edit Offer', 'rental') . '</a>
								</div>
							</div>
						</td>
					</tr>';
                }
            }

            return force_balance_tags($cs_html);
        }

    }

}

if (class_exists('cs_pricing_options')) {
    $cs_pricing_obj = new cs_pricing_options();
    add_action('admin_menu', array(&$cs_pricing_obj, 'cs_pricing_settings'));
}
