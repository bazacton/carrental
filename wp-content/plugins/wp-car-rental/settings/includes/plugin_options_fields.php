<?php

/**
 * File Type: Booking Option Feilds
 */
if (!class_exists('booking_options_fields')) {

    class booking_options_fields {

        public function __construct() {
            
        }

        /* get sub-menus */

        public function sub_menu($sub_menu = '') {
            $menu_items = '';
            $active = '';
            $menu_items.='<ul class="sub-menu">';
            foreach ($sub_menu as $key => $value) {
                $active = ($key == "tab-global-setting") ? 'active' : '';
                $menu_items.='<li class="' . sanitize_html_class($key) . ' ' . $active . ' "><a href="#' . $key . '" onClick="toggleDiv(this.hash);return false;">' . esc_attr($value) . '</a></li>';
            }
            $menu_items.='</ul>';
            return $menu_items;
        }

        public function cs_fields($cs_setting_options) {
            global $cs_plugin_options;

            //$cs_plugin_options = get_option('cs_plugin_options');
            $counter = 0;
            $cs_counter = 0;
            $menu = '';
            $output = '';
            $parent_heading = '';
            $style = '';
            $cs_countries_list = '';


            //print_r($cs_setting_options);

            foreach ($cs_setting_options as $value) {
                $counter++;
                $val = '';
                if ($value['type'] != "heading") {
                    //$output .= '<h3 class="heading">'. $value['name'] .'</h3>'."\n";
                }
                $select_value = '';
                switch ($value['type']) {
                    case "heading":
                        $parent_heading = $value['name'];
                        $menu .= '<li><a title="' . $value['name'] . '" href="#"><i class="' . sanitize_html_class($value["fontawesome"]) . '"></i><span class="cs-title-menu">' . esc_attr($value['name']) . '</span></a>';
                        if (is_array($value['options']) and $value['options'] <> '') {
                            $menu .= $this->sub_menu($value['options']);
                        }
                        $menu .= '</li>';
                        break;

                    case "main-heading":
                        $parent_heading = $value['name'];
                        $menu .= '<li><a title="' . $value['name'] . '" href="#' . $value['id'] . '" onClick="toggleDiv(this.hash);return false;">
						<i class="' . sanitize_html_class($value["fontawesome"]) . '"></i><span class="cs-title-menu">' . esc_attr($value['name']) . '</span></a>';
                        $menu .= '</li>';
                        break;

                    case "sub-heading":
                        $cs_counter++;
                        if ($cs_counter > 1) {
                            $output .='</div>';
                        }
                        if ($value['id'] != 'tab-general') {
                            $style = 'style="display:none;"';
                        }

                        $output .='<div id="' . $value['id'] . '" ' . $style . ' >';
                        $output .='<div class="theme-header">
										<h1>' . $value['name'] . '</h1>
								   </div>';
                        break;
                    case "announcement":
                        $cs_counter++;
                        $output.='<div id="' . $value['id'] . '" class="alert alert-info fade in nomargin theme_box">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&#215;</button>
											<h4>' . force_balance_tags($value['name']) . '</h4>
											<p>' . force_balance_tags($value['std']) . '</p>
								 </div>';
                        break;
                    case "acc_start":
                        $output .='<div id="accordion-' . esc_attr($value['rand']) . '">';
                        break;

                    case "acc_panel_start":
                        $output .='<div class="panel panel-default">';
                        break;

                    case "acc_cont_start":

                        if (isset($value['active']) && $value['active'] == true) {
                            $active = ' in';
                        } else {
                            $active = '';
                        }
                        $output .='<div id="accordion-' . esc_attr($value['rand']) . '" class="panel-collapse collapse' . $active . '"><div class="panel-body">';
                        break;

                    case "elem_end":
                        $output .='</div>';
                        break;

                    case "section":
                        if (isset($value['accordion']) && $value['accordion'] == true) {

                            if (isset($value['active']) && $value['active'] == true) {
                                $active = '';
                            } else {
                                $active = ' class="collapsed"';
                            }
                            $output .='<div class="panel-heading">
										<a' . $active . ' href="#accordion-' . esc_attr($value['id']) . '" data-parent="#accordion-' . esc_attr($value['parrent_id']) . '" data-toggle="collapse"><h4>' . esc_attr($value['name']) . '</h4>';
                        } else {
                            $output .='<div class="theme-help">
										<h4>' . esc_attr($value['name']) . '</h4>
										<div class="clear"></div>
									  </div>';
                        }
                        if (isset($value['accordion']) && $value['accordion'] == true) {
                            $output .='</a>
									  </div>';
                        }
                        break;
                    case 'text':
                        if (isset($cs_plugin_options)) {
                            if (isset($cs_plugin_options[$value['id']])) {
                                $val = $cs_plugin_options[$value['id']];
                            } else {
                                $val = $value['std'];
                            }
                        } else {
                            $val = $value['std'];
                        }

                        $output .= '<ul class="form-elements" id="' . $value['id'] . '_textfield">';
                        $output .= '<li class="to-label">
										<label>' . esc_attr($value["name"]) . '<span>' . esc_attr($value['hint_text']) . '</span></label>
									</li>
									<li class="to-field"><input   name="' . $value['id'] . '" id="' . $value['id'] . '" type="' . $value['type'] . '" value="' . $val . '" class="vsmall" />';
                        $output .= '<p>' . esc_attr($value['desc']) . '</p></li>';
                        $output .= '</ul>';
                        break;


                    case 'range':
                        if (isset($cs_plugin_options)) {
                            if (isset($cs_plugin_options[$value['id']])) {
                                $val = $cs_plugin_options[$value['id']];
                            } else {
                                $val = $value['std'];
                            }
                        } else {
                            $val = $value['std'];
                        }
                        //if (isset($std)) { $val = $std; }
                        $output .= '<ul class="form-elements" id="' . $value['id'] . '_range">';
                        $output .= '<li class="to-label">
										<label>' . esc_attr($value["name"]) . '<span>' . esc_attr($value['hint_text']) . '</span></label>
									</li>
									<li class="to-field">
									<div class="cs-drag-slider" data-slider-min="' . $value['min'] . '" data-slider-max="' . $value['max'] . '" data-slider-step="1" data-slider-value="' . $val . '">
									</div>
									<input class="cs-range-input" name="' . $value['id'] . '" id="' . $value['id'] . '" type="text" value="' . $val . '" class="vsmall" />';
                        $output .= '<p>' . esc_attr($value['desc']) . '</p></li>';
                        $output .= '</ul>';

                        break;

                    case 'textarea':
                        $val = $value['std'];
                        $std = get_option($value['id']);
                        if (isset($cs_plugin_options)) {
                            if (isset($cs_plugin_options[$value['id']])) {
                                $val = $cs_plugin_options[$value['id']];
                            } else {
                                $val = $value['std'];
                            }
                        } else {
                            $val = $value['std'];
                        }
                        $output .= '<ul class="form-elements" id="' . $value['id'] . '_textarea"> 
										<li class="to-label">
											<label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label>
										</li>
										<li class="to-field">
											<div class="input-sec">
												<textarea rows="10" cols="60" name="' . $value['id'] . '" id="' . $value['id'] . '" type="' . $value['type'] . '">' . htmlspecialchars_decode($val) . '</textarea>
											</div>
											<div class="left-info"><p>' . esc_attr($value['desc']) . '</p></div>
										</li>
								  </ul>';
                        break;

                    case "radio":
                        if (isset($cs_plugin_options)) {
                            $select_value = $cs_plugin_options[$value['id']];
                        } else {
                            
                        }
                        foreach ($value['options'] as $key => $option) {
                            $checked = '';
                            if ($select_value != '') {
                                if ($select_value == $option) {
                                    $checked = ' checked';
                                }
                            } else {
                                if ($value['std'] == $option) {
                                    $checked = ' checked';
                                }
                            }
                            $output .= '<input type="radio" name="' . $value['id'] . '" value="' . $option . '" ' . $checked . ' />' . $key . '<br />';
                        }
                        break;


                    case 'select':
                        if (isset($cs_plugin_options) and $cs_plugin_options <> '') {
                            if (isset($cs_plugin_options[$value['id']]) and $cs_plugin_options[$value['id']] <> '') {
                                $select_value = $cs_plugin_options[$value['id']];
                            } else {
                                $select_value = $value['std'];
                            }
                        } else {
                            $select_value = $value['std'];
                        }
                        if ($select_value == 'absolute') {
                            if ($cs_plugin_options['cs_headerbg_options'] == 'cs_rev_slider') {
                                $output .='<style>
																	#cs_headerbg_image_upload,#cs_headerbg_color_color,#cs_headerbg_image_box{ display:none;}
																	#tab-header-options ul#cs_headerbg_slider_1,#tab-header-options ul#cs_headerbg_options_header{ display:block;}
																</style>';
                            } else if ($cs_plugin_options['cs_headerbg_options'] == 'cs_bg_image_color') {
                                $output .='<style>
																#cs_headerbg_image_upload,#cs_headerbg_color_color,#cs_headerbg_image_box{ display:block;}
																#tab-header-options ul#cs_headerbg_slider_1{ display:none; }
															</style>';
                            } else {
                                $output .='<style>
																#cs_headerbg_options_header{display:block;}
																#cs_headerbg_image_upload,#cs_headerbg_color_color,#cs_headerbg_image_box{ display:none;}
																#tab-header-options ul#cs_headerbg_slider_1{ display:none; }
															</style>';
                            }
                        } elseif ($select_value == 'relative') {
                            $output .='<style>
													 #tab-header-options ul#cs_headerbg_slider_1,#tab-header-options ul#cs_headerbg_options_header,#tab-header-options ul#cs_headerbg_image_upload,#tab-header-options ul#cs_headerbg_color_color,#tab-header-options #cs_headerbg_image_box{ display:none;}
											   </style>';
                        }



                        $output .= ($value['id'] == 'cs_bgimage_position') ? '<div class="main_tab">' : '';
                        $select_header_bg = ($value['id'] == 'cs_header_position') ? 'onchange=javascript:cs_set_headerbg(this.value)' : '';

                        $output .='<ul class="form-elements" id="' . $value['id'] . '_select">
									<li class="to-label"><label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label></li>
										<li class="to-field">
										<div class="input-sec">
											<div class="select-style">
											<select ' . $select_header_bg . ' name="' . $value['id'] . '" id="' . $value['id'] . '">';
                        foreach ($value['options'] as $key => $option) {
                            $selected = '';
                            if ($select_value != '') {
                                if ($select_value == $key) {
                                    $selected = ' selected="selected"';
                                }
                            } else {
                                if (isset($value['std']))
                                    if ($value['std'] == $key) {
                                        $selected = ' selected="selected"';
                                    }
                            }
                            $output .= '<option' . $selected . ' value="' . $key . '">';
                            $output .= $option;
                            $output .= '</option>';
                        }
                        $output .= '</select></div>
														</div><div class="left-info">
														<p>' . esc_attr($value['desc']) . '</p>
												</div>
											</li>
									</ul>';
                        $output .=($value['id'] == 'cs_bgimage_position') ? '</div>' : '';
                        break;

                    case 'select_values':
                        if (isset($cs_plugin_options) and $cs_plugin_options <> '') {
                            if (isset($cs_plugin_options[$value['id']]) and $cs_plugin_options[$value['id']] <> '') {
                                $select_value = $cs_plugin_options[$value['id']];
                            } else {
                                $select_value = $value['std'];
                            }
                        } else {
                            $select_value = $value['std'];
                        }
                        $output .= ($value['id'] == 'cs_bgimage_position') ? '<div class="main_tab">' : '';
                        $select_header_bg = ($value['id'] == 'cs_header_position') ? 'onchange=javascript:cs_set_headerbg(this.value)' : '';

                        $output .='<ul class="form-elements" id="' . $value['id'] . '_select">
									<li class="to-label"><label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label></li>
										<li class="to-field">
										<div class="input-sec">
											<div class="select-style">
											<select ' . $select_header_bg . ' name="' . $value['id'] . '" id="' . $value['id'] . '">';
                        foreach ($value['options'] as $key => $option) {
                            $selected = '';
                            if ($select_value != '') {
                                if ($select_value == $key) {
                                    $selected = ' selected="selected"';
                                }
                            } else {
                                if (isset($value['std']))
                                    if ($value['std'] == $key) {
                                        $selected = ' selected="selected"';
                                    }
                            }
                            $output .= '<option' . $selected . ' value="' . $key . '">';
                            $output .= $option;
                            $output .= '</option>';
                        }
                        $output .= '</select></div>
														</div><div class="left-info">
														<p>' . esc_attr($value['desc']) . '</p>
												</div>
											</li>
									</ul>';
                        $output .=($value['id'] == 'cs_bgimage_position') ? '</div>' : '';
                        break;

                    case 'ad_select':
                        if (isset($cs_plugin_options) and $cs_plugin_options <> '') {
                            if (isset($cs_plugin_options[$value['id']]) and $cs_plugin_options[$value['id']] <> '') {
                                $select_value = $cs_plugin_options[$value['id']];
                            } else {
                                $select_value = $value['std'];
                            }
                        } else {
                            $select_value = $value['std'];
                        }
                        if ($select_value == 'absolute') {
                            if ($cs_plugin_options['cs_headerbg_options'] == 'cs_rev_slider') {
                                $output .='<style>
																	#cs_headerbg_image_upload,#cs_headerbg_color_color,#cs_headerbg_image_box{ display:none;}
																	#tab-header-options ul#cs_headerbg_slider_1,#tab-header-options ul#cs_headerbg_options_header{ display:block;}
																</style>';
                            } else if ($cs_plugin_options['cs_headerbg_options'] == 'cs_bg_image_color') {
                                $output .='<style>
																#cs_headerbg_image_upload,#cs_headerbg_color_color,#cs_headerbg_image_box{ display:block;}
																#tab-header-options ul#cs_headerbg_slider_1{ display:none; }
															</style>';
                            } else {
                                $output .='<style>
																#cs_headerbg_options_header{display:block;}
																#cs_headerbg_image_upload,#cs_headerbg_color_color,#cs_headerbg_image_box{ display:none;}
																#tab-header-options ul#cs_headerbg_slider_1{ display:none; }
															</style>';
                            }
                        } elseif ($select_value == 'relative') {
                            $output .='<style>
													 #tab-header-options ul#cs_headerbg_slider_1,#tab-header-options ul#cs_headerbg_options_header,#tab-header-options ul#cs_headerbg_image_upload,#tab-header-options ul#cs_headerbg_color_color,#tab-header-options #cs_headerbg_image_box{ display:none;}
												  </style>';
                        }



                        $output .= ($value['id'] == 'cs_bgimage_position') ? '<div class="main_tab">' : '';
                        $select_header_bg = ($value['id'] == 'cs_header_position') ? 'onchange=javascript:cs_set_headerbg(this.value)' : '';

                        $output .='<ul class="form-elements" id="' . $value['id'] . '_select">
									<li class="to-label"><label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label></li>
										<li class="to-field">
										<div class="input-sec">
											<div class="select-style">
											<select ' . $select_header_bg . ' name="' . $value['id'] . '" id="' . $value['id'] . '">';
                        foreach ($value['options'] as $option => $option_vlaue) {
                            $selected = '';
                            if ($select_value != '') {
                                if ($select_value == $option) {
                                    $selected = ' selected="selected"';
                                }
                            } else {
                                if (isset($value['std']))
                                    if ($value['std'] == $option) {
                                        $selected = ' selected="selected"';
                                    }
                            }
                            $output .= '<option' . $selected . ' value="' . $option . '">';
                            $output .= $option_vlaue;
                            $output .= '</option>';
                        }
                        $output .= '</select></div>
														</div><div class="left-info">
														<p>' . esc_attr($value['desc']) . '</p>
												</div>
											</li>
									</ul>';
                        $output .=($value['id'] == 'cs_bgimage_position') ? '</div>' : '';
                        break;

                    case "checkbox":
                        $saved_std = '';
                        $std = '';
                        if (isset($cs_plugin_options)) {
                            if (isset($cs_plugin_options[$value['id']])) {
                                $saved_std = $cs_plugin_options[$value['id']];
                            }
                        } else {
                            $std = $value['std'];
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
                        $output .= '<ul class="form-elements" id="' . $value['id'] . '_checkbox">
									  <li class="to-label">
									  <label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label>
									  </li>
									  <li class="to-field"><div class="input-sec"><label class="pbwp-checkbox">
									  <input type="hidden" name="' . $value['id'] . '" value="off" />
									  <input type="checkbox" class="myClass"  name="' . $value['id'] . '" id="' . $value['id'] . '" ' . $checked . ' />
									  <span class="pbwp-box"></span>
									  </label></div><div class="left-info">
										  <p>' . esc_attr($value['desc']) . '</p>
									  </div></li>
									</ul>';
                        break;

                    case "color":
                        $val = $value['std'];
                        if (isset($cs_plugin_options)) {
                            if (isset($cs_plugin_options[$value['id']])) {
                                $val = $cs_plugin_options[$value['id']];
                            }
                        } else {
                            $std = $value['std'];
                            if ($std != '') {
                                $val = $std;
                            }
                        }
                        $output .= '<ul class="form-elements" id="' . $value['id'] . '_color">
										<li class="to-label">
										  <label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label>
										</li>
										<li class="to-field">
										  <div class="input-sec">
										  <input type="text" name="' . $value['id'] . '" id="' . $value['id'] . '" value="' . $val . '" class="bg_color" data-default-color="' . $val . '" /></div>
										  <div class="left-info">
											  <p>' . esc_attr($value['desc']) . '</p>
										  </div>
										</li>
									</ul>';
                        break;
                    case "extras":
                        ob_start();
                        $obj = new cs_plugin_options();
                        $obj->cs_remove_duplicate_extra_value();
                        $obj->cs_extra_feature_section();
                        $post_data = ob_get_clean();
                        $output .= $post_data;
                        break;
                    case "features":
                        ob_start();
                        $obj = new cs_plugin_options();
                        $obj->cs_feats_section();
                        $post_data = ob_get_clean();
                        $output .= $post_data;
                        break;
                    case "properties":
                        ob_start();
                        $obj = new cs_plugin_options();
                        $obj->cs_properties_section();
                        $post_data = ob_get_clean();
                        $output .= $post_data;
                        break;
                    case "reviews":
                        ob_start();
                        $obj = new cs_plugin_options();
                        $obj->cs_dyn_reviews_section();
                        $post_data = ob_get_clean();
                        $output .= $post_data;
                        break;
                    case "gateways":
                        global $gateways;
                        $general_settings = new CS_PAYMENTS();
                        $cs_counter = '';
                        foreach ($gateways as $key => $value) {
                            $output .='<div class="theme-help">';
                            $output .='<h4>' . $value . '</h4>';
                            $output .='<div class="clear"></div>';
                            $output .='</div>';
                            if (class_exists($key)) {
                                $settings = new $key();
                                $cs_settings = $settings->settings();
                                $html = '';
                                foreach ($cs_settings as $key => $params) {
                                    ob_start();
                                    cs_settings_fields($key, $params);
                                    $post_data = ob_get_clean();
                                    $output .= $post_data;
                                }
                            }
                        }
                        break;

                    case "check_color":
                        $val = $value['std'];
                        if (isset($cs_plugin_options)) {
                            if (isset($cs_plugin_options[$value['id']])) {
                                $val = $cs_plugin_options[$value['id']];
                            }
                        } else {
                            $std = $value['std'];
                            if ($std != '') {
                                $val = $std;
                            }
                        }
                        $check_val = '';
                        if (isset($cs_plugin_options)) {
                            if (isset($cs_plugin_options[$value['id'] . '_switch'])) {
                                $check_val = $cs_plugin_options[$value['id'] . '_switch'];
                            }
                        } else {
                            $check_val = 'off';
                        }
                        $checked = '';
                        if ($check_val == 'on') {
                            $checked = 'checked="checked"';
                        } else {
                            $checked = '';
                        }
                        $output .= '<ul class="form-elements" id="' . $value['id'] . '_check_color">
										<li class="to-label">
										  <label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label>
										</li>
										<li class="to-field">
										  <div class="input-sec">
										  <input type="text" name="' . $value['id'] . '" id="' . $value['id'] . '" value="' . $val . '" class="bg_color" data-default-color="' . $val . '" />
										  <label class="pbwp-checkbox" style="float:right;margin-top:3px !important;right:60px;">
											<input type="hidden" name="' . $value['id'] . '_switch" value="off" />
											<input type="checkbox" class="myClass"  name="' . $value['id'] . '_switch" id="' . $value['id'] . '_switch" ' . $checked . ' />
											<span class="pbwp-box"></span>
										 </label> 
										  </div>
										  <div class="left-info">
											  <p>' . esc_attr($value['desc']) . '</p>
										  </div>
										</li>
										
									</ul>';
                        break;
                    case "upload":
                        $cs_counter++;

                        if (isset($cs_plugin_options) and $cs_plugin_options <> '' && isset($cs_plugin_options[$value['id']])) {
                            $val = $cs_plugin_options[$value['id']];
                        } else {
                            $val = $value['std'];
                        }
                        $display = ($val <> '' ? 'display' : 'none');
                        if (isset($value['tab'])) {
                            $output .= '<div class="main_tab"><div class="horizontal_tab" style="display:' . $value['display'] . '" id="' . $value['tab'] . '">';
                        }
                        $output .= '<ul class="form-elements" id="' . $value['id'] . '_upload">
									  <li class="to-label">
										 <label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label>
									  </li>
									  <li class="to-field">
									  <div class="page-wrap cs_custom_upload_box" style="overflow:hidden;display:' . $display . '" id="' . $value['id'] . '_box" >
										<div class="gal-active">
										  <div class="dragareamain" style="padding-bottom:0px;">
											<ul id="gal-sortable">
											  <li class="ui-state-default" id="">
												<div class="thumb-secs"> <img src="' . esc_url($val) . '"  id="' . $value['id'] . '_img"  />
												  <div class="gal-edit-opts"> <a href=javascript:del_media("' . $value['id'] . '") class="delete"></a> </div>
												</div>
											  </li>
											</ul>
										  </div>
										</div>
									  </div>
									  <input id="' . $value['id'] . '" name="' . $value['id'] . '" type="hidden" class="" value="' . $val . '"/>
									  <label class="browse-icon"><input name="' . $value['id'] . '"  type="button" class="uploadMedia left" value="' . __('Browse', 'rental') . '" /></label>
									  </li>
									</ul>';
                        if (isset($value['tab'])) {
                            $output.='</div></div>';
                        }
                        break;
                    case "upload logo":
                        $cs_counter++;

                        if (isset($cs_plugin_options) and $cs_plugin_options <> '' && isset($cs_plugin_options[$value['id']])) {
                            $val = $cs_plugin_options[$value['id']];
                        } else {
                            $val = $value['std'];
                        }

                        $display = ($val <> '' ? 'display' : 'none');
                        if (isset($value['tab'])) {
                            $output .='<div class="main_tab"><div class="horizontal_tab" style="display:' . $value['display'] . '" id="' . $value['tab'] . '">';
                        }
                        $output .= '<ul class="form-elements" id="' . $value['id'] . '_upload">
									  <li class="to-label">
										 <label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label>
									  </li>
									  <li class="to-field">
										<div class="page-wrap cs_custom_upload_box" style="overflow:hidden;display:' . $display . '" id="' . $value['id'] . '_box" >
										  <div class="gal-active">
											<div class="dragareamain" style="padding-bottom:0px;">
											  <ul id="gal-sortable">
												<li class="ui-state-default" id="">
												  <div class="thumb-secs cs-custom-image"> <img src="' . esc_url($val) . '"  id="' . $value['id'] . '_img"  />
													<div class="gal-edit-opts"> <a href=javascript:del_media("' . $value['id'] . '") class="delete"></a> </div>
												  </div>
												</li>
											  </ul>
											</div>
										  </div>
										</div>
										<input id="' . $value['id'] . '" name="' . $value['id'] . '" type="hidden" class="" value="' . $val . '"/>
										<label class="browse-icon"><input name="' . $value['id'] . '"  type="button" class="uploadMedia left" value="' . __('Browse', 'rental') . '" /></label>
									  </li>
									</ul>';
                        break;

                    case 'select_dashboard':
                        if (isset($cs_plugin_options) and $cs_plugin_options <> '') {
                            if (isset($cs_plugin_options[$value['id']])) {
                                $select_value = $cs_plugin_options[$value['id']];
                            }
                        } else {
                            $select_value = $value['std'];
                        }
                        $args = array(
                            'depth' => 0,
                            'child_of' => 0,
                            'sort_order' => 'ASC',
                            'sort_column' => 'post_title',
                            'show_option_none' => __('Please select a page', "rental"),
                            'hierarchical' => '1',
                            'exclude' => '',
                            'include' => '',
                            'meta_key' => '',
                            'meta_value' => '',
                            'authors' => '',
                            'exclude_tree' => '',
                            'selected' => $select_value,
                            'echo' => 0,
                            'name' => $value['id'],
                            'post_type' => 'page'
                        );
                        $output .= '<ul class="form-elements"><li class="to-label"><label>' . $value['name'] . '<span>' . $value['hint_text'] . '</span></label></li>
										<li class="to-field">
										<div class="select-style">' .
                                wp_dropdown_pages($args)
                                . '</div></li></ul>';
                        break;


                    case 'information':
                        $val = $value['std'];
                        $output .= '<ul class="form-elements">
										<li class="to-label">
											<label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label>
										</li>
										<li class="to-field">
											<div class="input-sec">
											   <p>' . esc_attr($val) . '</p>
											</div>
											<div class="left-info"><p>' . esc_attr($value['desc']) . '</p></div>
										</li>
								  </ul>';
                        break;
                    case 'generate_backup':

                        global $wp_filesystem;

                        $backup_url = wp_nonce_url('edit.php?post_type=vehicles&page=cs_settings');

                        if (false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) )) {

                            return true;
                        }

                        if (!WP_Filesystem($creds)) {
                            request_filesystem_credentials($backup_url, '', true, false, array());
                            return true;
                        }

                        $cs_upload_dir = wp_car_rental::plugin_dir() . 'settings/backups/';

                        $cs_upload_dir_path = wp_car_rental::plugin_url() . 'settings/backups/';

                        $cs_all_list = $wp_filesystem->dirlist($cs_upload_dir);

                        $output .= '<div class="backup_generates_area" data-ajaxurl="' . esc_url(admin_url('admin-ajax.php')) . '">';

                        $output .= '
						<div class="theme-help">
							<h4>' . __('Import Options', "rental") . '</h4>
						</div>';

                        $output .= '<div class="external_backup_areas">';

                        $output .= '<p>' . __('Input the Url from another location and hit Import Button to apply settings.', "rental") . '</p>';

                        $output .= '<input type="text" id="bkup_import_url" />';
                        $output .= '<input id="cs-p-backup-url-restore" type="button" value="' . __('Import', "rental") . '" />';

                        $output .= '</div>';

                        $output .= '
						<div class="theme-help">
							<h4>' . __('Export Options', "rental") . '</h4>
						</div>';

                        if (is_array($cs_all_list) && sizeof($cs_all_list) > 0) {

                            $output .= '<p>' . __('Here you can Generate/Download Backups. Also you can use these Backups to Restore settings.', "rental") . '</p>';

                            $output .= '<select onchange="cs_set_p_filename(this.value, \'' . esc_url(admin_url('admin-ajax.php')) . '\', \'' . esc_url($cs_upload_dir_path) . '\')">';

                            $cs_list_count = 1;
                            foreach ($cs_all_list as $file_key => $file_val) {

                                if (isset($file_val['name'])) {

                                    $cs_slected = sizeof($cs_all_list) == $cs_list_count ? ' selected="selected"' : '';
                                    $output .= '<option' . $cs_slected . '>' . $file_val['name'] . '</option>';
                                }
                                $cs_list_count++;
                            }
                            $output .= '</select>';
                            $output .= '<div class="backup_action_btns">';

                            if (isset($file_val['name'])) {

                                $output .= '<input id="cs-p-backup-restore" data-file="' . $file_val['name'] . '" type="button" value="' . __('Restore', "rental") . '" />';

                                $output .= '<a download="' . $file_val['name'] . '" href="' . esc_url($cs_upload_dir_path . $file_val['name']) . '">' . __('Download', "rental") . '</a>';

                                $output .= '<input id="cs-p-backup-delte" data-file="' . $file_val['name'] . '" type="button" value="' . __('Delete', "rental") . '" />';
                            }

                            $output .= '</div>';
                        }

                        $output .= '<input type="button" value="' . __('Generate Backup', "rental") . '" onclick="javascript:cs_pl_backup_generate(\'' . esc_js(admin_url('admin-ajax.php')) . '\');" />';

                        $output .= '</div>';

                        break;

                        $output .= '</div>';
                        $output .= '</tbody></table></div></div>';
                }
            }
            $output .= '</div>';

            return array($output, $menu);
        }

    }

}