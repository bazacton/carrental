<?php

/**
 * @Theme Options Fields Class
 * @return
 *
 */
class theme_options_fields {

    public function __construct() {
        
    }

    # Sub Menu

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

    public function cs_fields($cs_options) {
        global $cs_theme_options;
        $counter = 0;
        $cs_counter = 0;
        $menu = '';
        $output = '';
        $parent_heading = '';
        $style = '';

        foreach ($cs_options as $value) {
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
                    if ($value['id'] != 'tab-global-setting') {
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
                                        <h4>' . cs_remove_force_tag_theme($value['name']) . '</h4>
                                        <p>' . cs_remove_force_tag_theme($value['std']) . '</p>
                             </div>';
                    break;

                case "section":
                    $output .='<div class="theme-help">
                                <h4>' . esc_attr($value['std']) . '</h4>
                                <div class="clear"></div>
                              </div>';
                    break;

                case 'text':
                    if (isset($cs_theme_options)) {
                        if (isset($cs_theme_options[$value['id']])) {
                            $val = $cs_theme_options[$value['id']];
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

                case 'headerbg slider':
                    if (isset($cs_theme_options) and $cs_theme_options <> '') {
                        if (isset($cs_theme_options[$value['id']]) and $cs_theme_options[$value['id']] <> '') {
                            $select_value = $cs_theme_options[$value['id']];
                        } else {
                            $select_value = $value['std'];
                        }
                    } else {
                        $select_value = $value['std'];
                    }

                    $show = '';
                    if (isset($cs_theme_options['cs_headerbg_options']) && $cs_theme_options['cs_headerbg_options'] == 'Revolution Slider') {
                        $show = 'block';
                    } else if (isset($cs_theme_options['cs_headerbg_options']) && ($cs_theme_options['cs_headerbg_options'] == 'None' || $cs_theme_options['cs_headerbg_options'] == 'Bg Image / bg Color')) {
                        $show = 'none';
                    }
                    $output .= '<ul class="form-elements" id="' . $value['id'] . '_1" style="display:' . $show . ';">';
                    $output .= '<li class="to-label">
                                    <label>' . esc_attr($value["name"]) . '<span>' . esc_attr($value['hint_text']) . '</span></label>
                                </li>
                                <li class="to-field">
                                <select  name="' . $value['id'] . '" id="' . $value['id'] . '">';
                    if (class_exists('RevSlider') && class_exists('cs_RevSlider')) {
                        $slider = new cs_RevSlider();
                        $arrSliders = $slider->getAllSliderAliases();
                        foreach ($arrSliders as $key => $entry) {
                            $selected = '';
                            if ($select_value != '') {
                                if ($select_value == $key['alias']) {
                                    $selected = ' selected="selected"';
                                }
                            } else {
                                if (isset($value['std']))
                                    if ($value['std'] == $key['alias']) {
                                        $selected = ' selected="selected"';
                                    }
                            }
                            $output.= '<option ' . $selected . ' value="' . $key['alias'] . '">' . $entry['title'] . '</option>';
                        }
                    }

                    $output .= '</select>';
                    $output .= '<p>' . esc_attr($value['desc']) . '</p></li>';
                    $output .= '</ul>';

                    break;

                case 'slider code':
                    if (isset($cs_theme_options) and $cs_theme_options <> '') {
                        if (isset($cs_theme_options[$value['id']]) and $cs_theme_options[$value['id']] <> '') {
                            $select_value = $cs_theme_options[$value['id']];
                        } else {
                            $select_value = $value['std'];
                        }
                    } else {
                        $select_value = $value['std'];
                    }
                    $show = '';
                    if ($cs_theme_options['cs_default_header'] == 'Slider') {
                        $show = 'block';
                    } else if ($cs_theme_options['cs_default_header'] == 'Breadcrumbs Sub Header' || $cs_theme_options['cs_default_header'] == 'No sub Header') {
                        $show = 'none';
                    }
                    $output .= '<ul class="form-elements" id="' . $value['id'] . '_1" style="display:' . $show . ';">';
                    $output .= '<li class="to-label">
                                    <label>' . esc_attr($value["name"]) . '<span>' . esc_attr($value['hint_text']) . '</span></label>
                                </li>
                                <li class="to-field">
                                <select name="' . $value['id'] . '" id="' . $value['id'] . '" >';
                    if (class_exists('RevSlider') && class_exists('cs_RevSlider')) {
                        $slider = new cs_RevSlider();
                        $arrSliders = $slider->getAllSliderAliases();
                        foreach ($arrSliders as $key => $entry) {
                            $selected = '';
                            if ($select_value != '') {
                                if ($select_value == $key['alias']) {
                                    $selected = ' selected="selected"';
                                }
                            } else {
                                if (isset($value['std']))
                                    if ($value['std'] == $key['alias']) {
                                        $selected = ' selected="selected"';
                                    }
                            }
                            $output.= '<option ' . $selected . ' value="' . $key['alias'] . '">' . $entry['title'] . '</option>';
                        }
                    }
                    $output .= '</select>';
                    $output .= '<p>' . esc_attr($value['desc']) . '</p></li>';
                    $output .= '</ul>';

                    break;

                case 'range':
                    if (isset($cs_theme_options)) {
                        if (isset($cs_theme_options[$value['id']])) {
                            $val = $cs_theme_options[$value['id']];
                        } else {
                            $val = $value['std'];
                        }
                    } else {
                        $val = $value['std'];
                    }

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
                    if (isset($cs_theme_options)) {
                        if (isset($cs_theme_options[$value['id']])) {
                            $val = $cs_theme_options[$value['id']];
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
                                            <textarea rows="10" cols="60" name="' . $value['id'] . '" id="' . $value['id'] . '" type="' . $value['type'] . '">' . esc_html($val) . '</textarea>
                                        </div>
                                        <div class="left-info"><p>' . esc_attr($value['desc']) . '</p></div>
                                    </li>
                              </ul>';
                    break;

                case 'import':
                    $val = $value['std'];
                    $std = get_option($value['id']);
                    if (isset($std)) {
                        $val = $std;
                    }
                    $output .= '<ul class="form-elements">
                                    <li class="to-label">
                                        <label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label>
                                    </li>
                                    <li class="to-field">
                                        <div class="input-sec">
                                            <textarea rows="10" cols="60" name="' . $value['id'] . '" id="' . $value['id'] . '" type="' . $value['type'] . '" ></textarea>
                                        </div>
                                        <div class="left-info"><p>' . esc_attr($value['desc']) . '</p></div>
                                    </li>
                              </ul>';
                    break;

                case 'export':
                    $cs_export_options = get_option('cs_theme_options');
                    $val = $value['std'];
                    $std = get_option($value['id']);
                    if (isset($std)) {
                        $val = $std;
                    }
                    $output .= '<ul class="form-elements">
                                    <li class="to-label">
                                        <label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label>
                                    </li>
                                    <li class="to-field">
                                        <div class="input-sec">
                                            <textarea rows="30" cols="60" name="' . $value['id'] . '" id="' . $value['id'] . '" type="' . $value['type'] . '" readonly="readonly">' . json_encode($cs_export_options, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) . '</textarea>
                                        </div>
                                        <div class="left-info"><p>' . esc_attr($value['desc']) . '</p></div>
                                    </li>
                              </ul>';
                    break;

                case 'generate_backup':

                    global $wp_filesystem;

                    $backup_url = wp_nonce_url('themes.php?page=cs_options_page');

                    if (false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) )) {

                        return true;
                    }

                    if (!WP_Filesystem($creds)) {
                        request_filesystem_credentials($backup_url, '', true, false, array());
                        return true;
                    }

                    $cs_upload_dir = get_template_directory() . '/include/theme-options/backups/';

                    $cs_upload_dir_path = get_template_directory_uri() . '/include/theme-options/backups/';

                    $cs_all_list = $wp_filesystem->dirlist($cs_upload_dir);

                    $output .= '<div class="backup_generates_area" data-ajaxurl="' . esc_url(admin_url('admin-ajax.php')) . '">';

                    $output .= '
					<div class="theme-help">
						<h4>' . esc_html__('Import Options', "car-rental") . '</h4>
					</div>';

                    $output .= '<div class="external_backup_areas">';

                    $output .= '<p>' . esc_html__('Input the URL from another location and hit Import Button to apply settings.', "car-rental") . '</p>';

                    $output .= '<input type="text" id="bkup_import_url" />';
                    $output .= '<input id="cs-backup-url-restore" type="button" value="' . esc_html__('Import', "car-rental") . '" />';

                    $output .= '</div>';

                    $output .= '
					<div class="theme-help">
						<h4>' . esc_html__('Export Options', "car-rental") . '</h4>
					</div>';

                    if (is_array($cs_all_list) && sizeof($cs_all_list) > 0) {

                        $output .= '<p>' . esc_html__('Here you can Generate/Download Backups. Also you can use these Backups to Restore settings.', "car-rental") . '</p>';

                        $output .= '<select onchange="cs_set_filename(this.value, \'' . esc_url(admin_url('admin-ajax.php')) . '\', \'' . esc_url($cs_upload_dir_path) . '\')">';

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

                            $output .= '<input id="cs-backup-restore" data-file="' . $file_val['name'] . '" type="button" value="' . esc_html__('Restore', "car-rental") . '" />';

                            $output .= '<a download="' . $file_val['name'] . '" href="' . $cs_upload_dir_path . $file_val['name'] . '">' . esc_html__('Download', "car-rental") . '</a>';

                            $output .= '<input id="cs-backup-delte" data-file="' . $file_val['name'] . '" type="button" value="' . esc_html__('Delete', "car-rental") . '" />';
                        }

                        $output .= '</div>';
                    }

                    $output .= '<input type="button" value="' . esc_html__('Generate Backup', "car-rental") . '" onclick="javascript:cs_backup_generate(\'' . esc_js(admin_url('admin-ajax.php')) . '\');" />';

                    $output .= '</div>';

                    break;

                case 'widgets_backup':

                    $output .= '<div class="backup_generates_area" data-ajaxurl="' . esc_url(admin_url('admin-ajax.php')) . '">';
                    if (class_exists('cs_widget_data')) {

                        global $wp_filesystem;

                        $backup_url = wp_nonce_url('themes.php?page=cs_options_page');

                        if (false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) )) {

                            return true;
                        }

                        if (!WP_Filesystem($creds)) {
                            request_filesystem_credentials($backup_url, '', true, false, array());
                            return true;
                        }

                        $cs_upload_dir = wp_car_rental::plugin_dir() . 'include/cs-importer/widgets-backup/';

                        $cs_upload_dir_path = wp_car_rental::plugin_url() . 'include/cs-importer/widgets-backup/';

                        $cs_all_list = $wp_filesystem->dirlist($cs_upload_dir);

                        $output .= '
						<div class="cs-import-help">
							<h4>' . esc_html__('Import Widgets', "car-rental") . '</h4>
						</div>';

                        $output .= '
						<div class="external_backup_areas">
							<div id="cs-import-widgets-con">
								<div id="cs-import-widget-loader"></div>
								' . cs_widget_data::import_settings_page() . '
							</div>
						</div>';

                        if (is_array($cs_all_list) && sizeof($cs_all_list) > 0) {

                            $output .= '<p>' . esc_html__('Here you can Generate/Download Backups. Also you can use these Backups to Restore settings.', "car-rental") . '</p>';

                            $output .= '<select id="cs-wid-backup-change" onchange="cs_set_filename(this.value, \'' . esc_url($cs_upload_dir_path) . '\')">';

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

                                $output .= '<input id="cs-wid-backup-restore" data-path="' . $cs_upload_dir_path . '" data-file="' . $file_val['name'] . '" type="button" value="' . esc_html__('Show Widget Settings', "car-rental") . '" />';

                                $output .= '<a download="' . $file_val['name'] . '" href="' . $cs_upload_dir_path . $file_val['name'] . '">' . esc_html__('Download', "car-rental") . '</a>';

                                $output .= '<input id="cs-wid-backup-delte" data-file="' . $file_val['name'] . '" type="button" value="' . esc_html__('Delete', "car-rental") . '" />';
                            }

                            $output .= '</div>';
                        }

                        $output .= '
						<div class="cs-import-help">
							<h4>' . esc_html__('Export Widgets', "car-rental") . '</h4>
						</div>';

                        $output .= '
						<div id="cs-export-widgets-con">
							<div id="cs-export-widget-loader"></div>
							' . cs_widget_data::export_settings_page() . '
						</div>';
                    }

                    $output .= '</div>';

                    break;

                case "radio":
                    if (isset($cs_theme_options)) {
                        $select_value = $cs_theme_options[$value['id']];
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

                case "layout":
                    global $cs_header_colors;


                    if (isset($cs_theme_options) and $cs_theme_options <> '') {
                        if (isset($cs_theme_options[$value['id']])) {
                            $select_value = $cs_theme_options[$value['id']];
                        }
                    } else {
                        $select_value = $value['std'];
                    }
                    $output .= '<ul class="form-elements" id="' . $value['id'] . '_layout">
                                    <li class="to-label">
                                      <label>' . $value['name'] . '<span>' . $value['hint_text'] . '</span></label>
                                    </li>
                                    <li class="to-field">
                                        <div class="input-sec">
                                            <div class="meta-input pattern">';
                    foreach ($value['options'] as $key => $option) {
                        $checked = '';
                        $custom_class = '';
                        if ($select_value != '') {

                            if ($select_value == $key) {
                                $checked = ' checked';
                                $custom_class = 'check-list';
                            }
                        } else {
                            if ($value['std'] == $key) {
                                $checked = ' checked';
                                $custom_class = 'check-list';
                            }
                        }
                        $name = $value['id'];
                        $output .= '<div class="radio-image-wrapper">
                                                    <input name="' . $value['id'] . '" class="radio" type="radio" 
                                                    onclick=select_bg("' . $name . '","' . $key . '","' . get_template_directory_uri() . '","") value="' . $key . '" 
                                                    ' . $checked . ' />
                                                    <label for="radio_' . $key . '"> 
                                                        <span class="ss"><img src="' . esc_url(get_template_directory_uri()) . '/include/assets/images/' . $key . '.png" /></span> 
                                                        <span class="' . sanitize_html_class($custom_class) . '" id="check-list">&nbsp;</span>
                                                    </label>
                                                    <span class="title-theme">' . esc_attr($option) . '</span>            
                                            </div>';
                    }
                    $output.=' </div></div></li></ul>';
                    break;
                case "layout1":
                    global $cs_header_colors;
                    $header_counter = 1;
                    if (isset($cs_theme_options)) {
                        if (isset($cs_theme_options[$value['id']])) {
                            $select_value = $cs_theme_options[$value['id']];
                        }
                    } else {
                        $select_value = $value['std'];
                    }
                    $output .= '<ul class="form-elements" id="' . $value['id'] . '_layout1">
                                    <li class="to-label ' . $value['class'] . 'label_left">
                                      <label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label>
                                    </li>
                                    <li class="to-field ' . $value['class'] . 'input_right">
                                        <div class="input-sec">
                                            <div class="meta-input pattern">';
                    foreach ($value['options'] as $key => $option) {
                        $checked = '';
                        $custom_class = '';
                        if ($select_value != '') {
                            if ($select_value == $option) {
                                $checked = ' checked';
                                $custom_class = 'check-list';
                            }
                        } else {
                            if ($value['std'] == $option) {
                                $checked = ' checked';
                                $custom_class = 'check-list';
                            }
                        }
                        $name = $value['id'];
                        $output .= '<div class="radio-image-wrapper"><span class="header-counter">' . $header_counter . '</span>
                                                    <input name="' . $value['id'] . '" class="radio" type="radio" 
                                                    onclick=select_bg("' . $name . '","' . $option . '","' . esc_url(get_template_directory_uri()) . '","' . admin_url('admin-ajax.php') . '") value="' . $option . '" 
                                                    ' . $checked . ' />
                                                    <label for="radio_' . $key . '"> 
                                                        <span class="ss"><img src="' . esc_url(get_template_directory_uri()) . '/include/assets/images/' . $option . '.png" /></span> 
                                                        <span class="' . sanitize_html_class($custom_class) . '" id="check-list">&nbsp;</span>
                                                    </label>
                                                    
                                                </div>';
                        $header_counter++;
                    }
                    $output.=' </div></div></li></ul>';
                    break;

                case "horizontal_tab":
                    if (isset($cs_theme_options['cs_layout']) and $cs_theme_options['cs_layout'] <> 'boxed') {
                        echo '<style type="text/css" scoped>
                        .horizontal_tabs,.main_tab{display:none;
                        
                        }
                    
                    </style>';
                    }
                    $output .= '<div class="horizontal_tabs"><ul>';
                    $i = 0;
                    foreach ($value['options'] as $key => $val) {
                        $active = ($i == 0) ? 'active' : '';
                        $output .= '<li class="' . sanitize_html_class($val) . ' ' . $active . '"><a href="#' . $val . '" onclick="show_hide(this.hash);return false;">' . $key . '</a></li>';
                        $i++;
                    }
                    $output.='</ul></div>';

                    break;

                case "layout_body":
                    global $cs_header_colors;
                    $bg_counter = 0;
                    if (isset($cs_theme_options) and $cs_theme_options <> '') {
                        if (isset($cs_theme_options[$value['id']])) {
                            $select_value = $cs_theme_options[$value['id']];
                        } else {
                            $select_value = $value['std'];
                        }
                    } else {
                        $select_value = $value['std'];
                    }
                    if ($value['path'] == "background") {
                        $image_name = "background";
                    } else {
                        $image_name = "pattern";
                    }
                    $output .= '<div class="main_tab"><div class="horizontal_tab" style="display:' . $value['display'] . '" id="' . $value['tab'] . '"><ul class="form-elements" id="' . $value['id'] . '_layout_body">
                                    <li class="to-label ' . sanitize_html_class($value['class']) . 'label_left">
                                      <label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label>
                                    </li>
                                    <li class="to-field ' . sanitize_html_class($value['class']) . 'input_right">
                                        <div class="input-sec">
                                            <div class="meta-input pattern">';
                    foreach ($value['options'] as $key => $option) {
                        $checked = '';
                        $custom_class = '';
                        if ($select_value != '') {
                            if ($select_value == $option) {
                                $checked = ' checked';
                                $custom_class = 'check-list';
                            }
                        } else {
                            if ($value['std'] == $option) {
                                $checked = ' checked';
                                $custom_class = 'check-list';
                            }
                        }
                        $name = $value['id'];
                        $output .= '<div class="radio-image-wrapper">
                                                    <input name="' . $value['id'] . '" class="radio" type="radio" 
                                                    onClick=javascript:select_bg("' . $name . '","' . $option . '","' . get_template_directory_uri() . '","") value="' . $option . '" 
                                                    ' . $checked . ' />
                                                    <label for="radio_' . $key . '"> 
                                                        <span class="ss">
                                                        <img src="' . esc_url(get_template_directory_uri()) . '/include/assets/images/' . $value['path'] . '/' . $image_name . $bg_counter . '.png" /></span> 
                                                        <span id="check-list" class="' . sanitize_html_class($custom_class) . '">&nbsp;</span>
                                                    </label>
                                                </div>';
                        $bg_counter++;
                    }
                    $output.=' </div></div></li></ul></div></div>';
                    break;
                case 'select':
                    if (isset($cs_theme_options) and $cs_theme_options <> '') {
                        if (isset($cs_theme_options[$value['id']]) and $cs_theme_options[$value['id']] <> '') {
                            $select_value = $cs_theme_options[$value['id']];
                        } else {
                            $select_value = $value['std'];
                        }
                    } else {
                        $select_value = $value['std'];
                    }
                    if ($select_value == 'absolute') {
                        if ($cs_theme_options['cs_headerbg_options'] == 'cs_rev_slider') {
                            $output .='<style>
                                                                #cs_headerbg_image_upload,#cs_headerbg_color_color,#cs_headerbg_image_box{ display:none;}
                                                                #tab-header-options ul#cs_headerbg_slider_1,#tab-header-options ul#cs_headerbg_options_header{ display:block;}
                                                            </style>';
                        } else if ($cs_theme_options['cs_headerbg_options'] == 'cs_bg_image_color') {
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
                                        <div class="select-style">';
                    $cs_change_function = (isset($value['change']) && $value['change'] == 'yes') ? ' onchange="' . $value['id'] . '_change(this.value)"' : '';
                    $output .='<select ' . $select_header_bg . ' name="' . $value['id'] . '"' . $cs_change_function . ' id="' . $value['id'] . '">';

                    foreach ($value['options'] as $option) {
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
                    if (isset($cs_theme_options) and $cs_theme_options <> '') {
                        if (isset($cs_theme_options[$value['id']]) and $cs_theme_options[$value['id']] <> '') {
                            $select_value = $cs_theme_options[$value['id']];
                        } else {
                            $select_value = $value['std'];
                        }
                    } else {
                        $select_value = $value['std'];
                    }

                    $output .= ($value['id'] == 'cs_bgimage_position') ? '<div class="main_tab">' : '';
                    $select_header_bg = ($value['id'] == 'cs_header_position') ? 'onchange=javascript:cs_set_headerbg(this.value)' : '';

                    $cs_search_display = '';

                    if ($value['id'] == 'cs_search_by_location') {
                        $cs_directory_location_suggestions = isset($cs_theme_options['cs_directory_location_suggestions']) ? $cs_theme_options['cs_directory_location_suggestions'] : '';
                        $cs_search_display = $cs_directory_location_suggestions == 'Website' ? 'block' : 'none';
                    }

                    if ($value['id'] == 'cs_search_by_location_city') {
                        $cs_search_by_location = isset($cs_theme_options['cs_search_by_location']) ? $cs_theme_options['cs_search_by_location'] : '';
                        $cs_search_display = $cs_search_by_location == 'single_city' ? 'block' : 'none';
                    }

                    $output .='<ul class="form-elements" id="' . $value['id'] . '_select" style="display:' . $cs_search_display . ';">
                                <li class="to-label"><label>' . esc_attr($value['name']) . '<span>' . $value['hint_text'] . '</span></label></li>
                                    <li class="to-field">
                                    <div class="input-sec">
                                        <div class="select-style">';
                    $cs_change_function = (isset($value['change']) && $value['change'] == 'yes') ? ' onchange="' . $value['id'] . '_change(this.value)"' : '';
                    $output .='<select ' . $select_header_bg . ' name="' . $value['id'] . '"' . $cs_change_function . ' id="' . $value['id'] . '">';
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
                    if (isset($cs_theme_options) and $cs_theme_options <> '') {
                        if (isset($cs_theme_options[$value['id']]) and $cs_theme_options[$value['id']] <> '') {
                            $select_value = $cs_theme_options[$value['id']];
                        } else {
                            $select_value = $value['std'];
                        }
                    } else {
                        $select_value = $value['std'];
                    }
                    if ($select_value == 'absolute') {
                        if ($cs_theme_options['cs_headerbg_options'] == 'cs_rev_slider') {
                            $output .='<style>
                                                                #cs_headerbg_image_upload,#cs_headerbg_color_color,#cs_headerbg_image_box{ display:none;}
                                                                #tab-header-options ul#cs_headerbg_slider_1,#tab-header-options ul#cs_headerbg_options_header{ display:block;}
                                                            </style>';
                        } else if ($cs_theme_options['cs_headerbg_options'] == 'cs_bg_image_color') {
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

                case 'gfont_select':

                    if (isset($cs_theme_options) and $cs_theme_options <> '') {
                        if (isset($cs_theme_options[$value['id']]) and $cs_theme_options[$value['id']] <> '') {
                            $select_value = $cs_theme_options[$value['id']];
                        } else {
                            $select_value = $value['std'];
                        }
                    } else {
                        $select_value = $value['std'];
                    }

                    $output .= ($value['id'] == 'cs_bgimage_position') ? '<div class="main_tab">' : '';
                    $output .='<ul class="form-elements no_border" id="' . $value['id'] . '_select">
                                <li class="to-label"><label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label></li>
                                    <li class="to-field">
                                    <div class="input-sec">
                                        <div class="select-style">
                                        <select onchange="cs_google_font_att(\'' . admin_url("admin-ajax.php") . '\',this.value, \'' . $value['id'] . '_att\')" name="' . $value['id'] . '" id="' . $value['id'] . '">';
                    $output .='<option value="default">' . esc_html__('Default Font', 'car-rental') . '</option>';
                    $i = 0;
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
                        $output .= '<option' . $selected . ' value="' . $option . '">';
                        $output .= $option;
                        $output .= '</option>';
                        $i++;
                    }
                    $output .= '</select></div>
                                                    </div><div class="left-info">
                                                    <p>' . esc_attr($value['desc']) . '</p>
                                            </div>
                                        </li>
                                </ul>';

                    $output .=($value['id'] == 'cs_bgimage_position') ? '</div>' : '';

                    break;

                case 'gfont_att_select':
                    if (isset($cs_theme_options) and $cs_theme_options <> '') {
                        if (isset($cs_theme_options[$value['id']]) and $cs_theme_options[$value['id']] <> '') {
                            $select_value = $cs_theme_options[$value['id']];
                            $value['options'] = cs_get_google_font_attribute('', $cs_theme_options[str_replace('_att', '', $value['id'])]);
                        } else {
                            $select_value = $value['std'];
                        }
                    } else {
                        $select_value = $value['std'];
                    }
                    $output .= ($value['id'] == 'cs_bgimage_position') ? '<div class="main_tab">' : '';
                    $output .='<ul class="form-elements" id="' . $value['id'] . '_select">
                                <li class="to-label"><label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label></li>
                                    <li class="to-field">
                                    <div class="input-sec">
                                        <div class="select-style">
                                        <select name="' . $value['id'] . '" id="' . $value['id'] . '">
                                        <option value="">' . esc_html__('Select Attribute', 'car-rental') . '</option>';
                    if (is_array($value['options'])) {
                        foreach ($value['options'] as $option) {
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
                            $output .= $option;
                            $output .= '</option>';
                        }
                    }
                    $output .= '</select></div>
                                                    </div><div class="left-info">
                                                    <p>' . esc_attr($value['desc']) . '</p>
                                            </div>
                                        </li>
                                </ul>';

                    $output .=($value['id'] == 'cs_bgimage_position') ? '</div>' : '';

                    break;

                case 'default header':
                    if (isset($cs_theme_options) and $cs_theme_options <> '') {
                        if (isset($cs_theme_options[$value['id']])) {
                            $select_value = $cs_theme_options[$value['id']];
                        }
                    } else {
                        $select_value = $value['std'];
                    }
                    if ($select_value == 'Revolution Slider') {
                        $output.='<style>
                                    #tab-sub-header-options ul,#tab-sub-header-options #cs_background_img_box{ display:none; }
                                    #tab-sub-header-options #cs_default_header_header,#tab-sub-header-options ul#cs_custom_slider_1{ display:block; }
                                    </style>';
                    } elseif ($select_value == 'Breadcrumbs Sub Header') {
                        $output.='<style>
                                    #tab-sub-header-options ul,#tab-sub-header-options #cs_background_img_box{ display:block; }
                                    #cs_custom_slider_1,#tab-sub-header-options ul#cs_header_border_color_color{ display:none; }
                                    </style>';
                    } else {
                        $output.='<style>
                              #tab-sub-header-options ul,#tab-sub-header-options #cs_background_img_box{ display:none; }
                              #tab-sub-header-options ul#cs_default_header_header,#tab-sub-header-options ul#cs_header_border_color_color{ display:block; }
                              </style>';
                    }
                    $output .= '<ul class="form-elements" id="' . $value['id'] . '_header">
                                    <li class="to-label"><label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label></li>
                                    <li class="to-field">
                                    <div class="input-sec">
                                        <div class="select-style">
                                        <select onchange=javascript:cs_show_slider(this.value) name="' . $value['id'] . '" id="' . $value['id'] . '">';
                    foreach ($value['options'] as $option) {
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
                        $output .= $option;
                        $output .= '</option>';
                    }
                    $output .= '</select></div>
                                                    </div><div class="left-info">
                                                    <p>' . esc_attr($value['desc']) . '</p>
                                            </div>
                                        </li>
                                </ul>';

                    break;
                case 'default header background':
                    if (isset($cs_theme_options) and $cs_theme_options <> '') {
                        if (isset($cs_theme_options[$value['id']])) {
                            $select_value = $cs_theme_options[$value['id']];
                        }
                    } else {
                        $select_value = $value['std'];
                    }


                    $output .= '<ul class="form-elements" id="' . $value['id'] . '_header">
                                    <li class="to-label"><label>' . $value['name'] . '<span>' . $value['hint_text'] . '</span></label></li>
                                    <li class="to-field">
                                    <div class="input-sec">
                                        <div class="select-style">
                                        <select onchange=javascript:cs_set_headerbg(this.value) name="' . $value['id'] . '" id="' . $value['id'] . '">';
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

                    break;

                case 'default padding':

                    if (isset($cs_theme_options) and $cs_theme_options <> '') {
                        if (isset($cs_theme_options[$value['id']])) {
                            $select_value = $cs_theme_options[$value['id']];
                        }
                    } else {
                        $select_value = $value['std'];
                    }
                    ?>                        
                    <?php if ($select_value == 'default') { ?>
                        <style type="text/css" scoped>
                            #cs_sh_paddingtop_range {
                                display:none;
                            }
                            #cs_sh_paddingbottom_range {
                                display:none;
                            }
                        </style>
                    <?php } ?>
                    <?php

                    $output .= '<ul class="form-elements" id="' . $value['id'] . '_header">
                                    <li class="to-label"><label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label></li>
                                    <li class="to-field">
                                    <div class="input-sec">
                                        <div class="select-style">
                                        <select onchange=javascript:cs_hide_show_toggle(this.value,"theme_options","theme_options") name="' . $value['id'] . '" id="' . $value['id'] . '">';
                    foreach ($value['options'] as $option) {
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
                        $output .= $option;
                        $output .= '</option>';
                    }
                    $output .= '</select></div>
                                                    </div><div class="left-info">
                                                    <p>' . esc_attr($value['desc']) . '</p>
                                            </div>
                                        </li>
                                </ul>';

                    break;

                case 'select_sidebar':
                    if (isset($cs_theme_options) and $cs_theme_options <> '') {
                        if (isset($cs_theme_options[$value['id']])) {
                            $select_value = $cs_theme_options[$value['id']];
                        }
                    } else {
                        $select_value = $value['std'];
                    }
                    $output .= '<ul class="form-elements"><li class="to-label"><label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label></li>
                                    <li class="to-field">
                                    <div class="input-sec">
                                        <div class="select-style">
                                        <select name="' . $value['id'] . '" id="' . $value['id'] . '">';
                    $output .= '<option>select sidebar</option>';
                    
                    if (!isset($value['options']) && !empty($value['options']) && is_array($value['options']['sidebar'])) {
                        foreach ($value['options']['sidebar'] as $option) {
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
                            $output .= '<option ' . $selected . '>';
                            $output .= $option;
                            $output .= '</option>';
                        }
                    }
                    $output .= '</select></div>
                                            </div><div class="left-info">
                                                <p>' . esc_attr($value['desc']) . '</p>
                                            </div>
                                    </li>
                                </ul>';

                    break;

                case 'mailchimp':
                    if (isset($cs_theme_options) and $cs_theme_options <> '') {
                        if (isset($cs_theme_options[$value['id']])) {
                            $select_value = $cs_theme_options[$value['id']];
                        }
                    } else {
                        $select_value = $value['std'];
                    }

                    $output .= '<ul class="form-elements"><li class="to-label"><label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label></li>
                                    <li class="to-field">
                                    <div class="input-sec">
                                        <div class="select-style">
                                        <select name="' . $value['id'] . '" id="' . $value['id'] . '">';
                    foreach ($value['options'] as $option_key => $option) {
                        $selected = '';
                        if ($select_value != '') {
                            if ($select_value == $option_key) {
                                $selected = ' selected="selected"';
                            }
                        } else {
                            if (isset($value['std']))
                                if ($value['std'] == $option_key) {
                                    $selected = ' selected="selected"';
                                }
                        }
                        $output .= '<option' . $selected . ' value="' . $option_key . '">';
                        $output .= $option;
                        $output .= '</option>';
                    }
                    $output .= '</select></div>
                                                    </div><div class="left-info">
                                                    <p>' . esc_attr($value['desc']) . '</p>
                                            </div>
                                        </li>
                                </ul>';

                    break;

                case "wpml":
                    $saved_std = '';
                    $std = '';
                    if (function_exists('icl_object_id')) {
                        if (isset($cs_theme_options)) {
                            if (isset($cs_theme_options[$value['id']])) {
                                $saved_std = $cs_theme_options[$value['id']];
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
                        $output .= '<ul class="form-elements">
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
                    }
                    break;
                case "checkbox":
                    $saved_std = '';
                    $std = '';
                    if (isset($cs_theme_options)) {
                        if (isset($cs_theme_options[$value['id']])) {
                            $saved_std = $cs_theme_options[$value['id']];
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
                case "multicheck":
                    $std = $value['std'];
                    foreach ($value['options'] as $key => $option) {
                        $of_key = $value['id'] . '_' . $key;
                        $saved_std = get_option($of_key);
                        if (!empty($saved_std)) {
                            if ($saved_std == 'true') {
                                $checked = 'checked="checked"';
                            } else {
                                $checked = '';
                            }
                        } elseif ($std == $key) {
                            $checked = 'checked="checked"';
                        } else {
                            $checked = '';
                        }
                        $output .= '<input type="checkbox" name="' . $of_key . '" id="' . $of_key . '" value="true" ' . $checked . ' /><label for="' . $of_key . '">' . $option . '</label><br />';
                    }
                    break;

                case 'hidden':
                    if (isset($cs_theme_options)) {
                        if (isset($cs_theme_options[$value['id']])) {
                            $val = $cs_theme_options[$value['id']];
                        } else {
                            $val = $value['std'];
                        }
                    } else {
                        $val = $value['std'];
                    }

                    $output .= '<input   name="' . $value['id'] . '" id="' . $value['id'] . '" type="' . $value['type'] . '" value="' . $val . '" class="vsmall" />';
                    break;

                case "color":
                    $val = $value['std'];
                    if (isset($cs_theme_options)) {
                        if (isset($cs_theme_options[$value['id']])) {
                            $val = $cs_theme_options[$value['id']];
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
                case "check_color":
                    $val = $value['std'];
                    if (isset($cs_theme_options)) {
                        if (isset($cs_theme_options[$value['id']])) {
                            $val = $cs_theme_options[$value['id']];
                        }
                    } else {
                        $std = $value['std'];
                        if ($std != '') {
                            $val = $std;
                        }
                    }
                    $check_val = '';
                    if (isset($cs_theme_options)) {
                        if (isset($cs_theme_options[$value['id'] . '_switch'])) {
                            $check_val = $cs_theme_options[$value['id'] . '_switch'];
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

                    if (isset($cs_theme_options) and $cs_theme_options <> '' && isset($cs_theme_options[$value['id']])) {
                        $val = $cs_theme_options[$value['id']];
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
                                    <input id="' . $value['id'] . '" name="' . $value['id'] . '" type="hidden" class="" value="' . $val . '"/>
                                    <label class="browse-icon">
                                    <input name="' . $value['id'] . '"  type="button" class="uploadMedia left" value="' . esc_html__('Browse', 'car-rental') . '"/></label>
                                  </li>
                                </ul>';
                    $output .= '<div class="page-wrap" style="overflow:hidden;display:' . $display . '" id="' . $value['id'] . '_box" >
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
                                </div>';
                    if (isset($value['tab'])) {
                        $output.='</div></div>';
                    }
                    break;
                case "upload logo":
                    $cs_counter++;

                    if (isset($cs_theme_options) and $cs_theme_options <> '' && isset($cs_theme_options[$value['id']])) {
                        $val = $cs_theme_options[$value['id']];
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
								  	<div class="page-wrap" style="overflow:hidden;display:' . $display . '" id="' . $value['id'] . '_box" >
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
                                    <label class="browse-icon"><input name="' . $value['id'] . '"  type="button" class="uploadMedia left" value="' . esc_html__('Browse', 'car-rental') . '"/></label>
                                  </li>
                                </ul>';

                    if (isset($value['tab'])) {
                        $output.='</div></div>';
                    }
                    break;
                case "upload font":
                    $cs_counter++;
                    if (isset($cs_theme_options) and $cs_theme_options <> '') {
                        $val = $cs_theme_options[$value['id']];
                    } else {
                        $val = $value['std'];
                    }
                    $display = ($val <> '' ? 'display' : 'none');
                    $output .= '<ul class="form-elements" id="' . $value['id'] . '_upload">
                                  <li class="to-label">
                                     <label>' . esc_attr($value['name']) . '<span>' . esc_attr($value['hint_text']) . '</span></label>
                                  </li>
                                  <li class="to-field">
                                    <input id="' . $value['id'] . '" name="' . $value['id'] . '" type="text" class="" value="' . $val . '"/>
                                    <label class="browse-icon">
                                        <input name="' . $value['id'] . '" type="button" class="uploadMedia left" value="' . esc_html__('Browse', 'car-rental') . '"/>
                                    </label>
                                  </li>
                                </ul>';
                    break;
                case 'select_dashboard':
                    if (isset($cs_theme_options) and $cs_theme_options <> '') {
                        if (isset($cs_theme_options[$value['id']])) {
                            $select_value = $cs_theme_options[$value['id']];
                        }
                    } else {
                        $select_value = $value['std'];
                    }
                    $args = array(
                        'depth' => 0,
                        'child_of' => 0,
                        'sort_order' => 'ASC',
                        'sort_column' => 'post_title',
                        'show_option_none' => 'Please select a page',
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
                case "upload favicon":
                    $val = $value['std'];
                    $std = get_option($value['id']);
                    if (isset($std)) {
                        $val = $std;
                    }
                    $output .= '<ul class="form-elements"><li class="to-label"><label>' . esc_attr($value["name"]) . '<span>' . esc_attr($value['hint_text']) . '</span></label></li><li class="to-field"><div class="input-sec"><input id="' . $value['id'] . '" name="' . $value['id'] . '" value="' . $val . '" type="hidden" />';
                    $output .= '<label class="cs-browse"><input id="log" name="' . $value['id'] . '" type="button" class="uploadfile left" value="' . esc_html__('Browse', 'car-rental') . '"/><i class="icon-upload"></i></label></div></li></ul>';

                    break;
                case "sidebar":
                    $val = $value['std'];
                    if (isset($cs_theme_options['sidebar']) && is_array($cs_theme_options['sidebar']) && count($cs_theme_options['sidebar']) > 0) {
                        $val['sidebar'] = $cs_theme_options['sidebar'];
                    }
                    if (isset($val['sidebar']) && is_array($val['sidebar']) && count($val['sidebar']) > 0 && $val['sidebar'] <> '') {
                        $display = 'block';
                    } else {
                        $display = 'none';
                    }
                    $output .= '<ul class="form-elements">
                                    <li class="to-label">
                                        <label>' . esc_attr($value['name']) . ' <span>' . esc_html__("Please enter the desired title of sidebar", 'car-rental') . '</span></label>
                                    </li>
                                    <li class="to-field">
                                        <input class="small" type="text" name="sidebar_input" id="sidebar_input"/>
                                        <input type="button" value=' . esc_html__("Add Sidebar", 'car-rental') . ' onclick="javascript:add_sidebar()" />
                                    </li>
                                </ul>
                                <div class="clear"></div>
                                <div class="sidebar-area" style="display:' . $display . '">
                                    <div class="theme-help">
                                      <h4 style="padding-bottom:0px;">' . esc_html__('Already Added Sidebars', 'car-rental') . '</h4>
                                      <div class="clear"></div>
                                    </div>
                                    <div class="boxes">
                                        <table class="to-table" border="0" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>' . esc_html__('Sider Bar Name', 'car-rental') . '</th>
                                                <th class="centr">' . esc_html__("Actions", 'car-rental') . '</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sidebar_area">';
                    if ($display == 'block') {
                        $i = 1;
                        foreach ($val['sidebar'] as $sidebar) {
                            $output.='<tr id="sidebar_' . $i . '">
                                                        <td><input type="hidden" name="sidebar[]" value="' . $sidebar . '" />' . $sidebar . '</td>
                                                        <td class="centr"> <a class="remove-btn" onclick="javascript:return confirm(\'Are you sure! You want to delete this\')" href="javascript:cs_div_remove(\'sidebar_' . $i . '\')" data-toggle="tooltip" data-placement="top" title="Remove"><i class="icon-times"></i></a>
                                                    </td>
                                                </tr>';
                            $i++;
                        }
                    };
                    $output.='</tbody>
                                        </table>
                                    </div>
                                </div>';
                    break;

                case "networks":
                    if (isset($cs_theme_options) and $cs_theme_options <> '') {

                        if (!isset($cs_theme_options['social_net_awesome']) and ! isset($cs_theme_options['social_net_awesome'])) {
                            $network_list = '';
                            $display = 'none';
                        } else {
                            $network_list = $cs_theme_options['social_net_awesome'];
                            $social_net_tooltip = $cs_theme_options['social_net_tooltip'];
                            $social_net_icon_path = $cs_theme_options['social_net_icon_path'];
                            $social_net_url = $cs_theme_options['social_net_url'];
                            $social_font_awesome_color = $cs_theme_options['social_font_awesome_color'];
                            $display = 'block';
                        }
                    } else {
                        $val = $value['options'];
                        $std = $value['id'];
                        $display = 'block';
                        $network_list = $val['social_net_awesome'];
                        $social_net_tooltip = $val['social_net_tooltip'];
                        $social_net_icon_path = $val['social_net_icon_path'];
                        $social_net_url = $val['social_net_url'];
                        $social_font_awesome_color = $val['social_font_awesome_color'];
                    }
                    $output.='<ul class="form-elements">
                                <li class="to-label">
                                  <label>' . esc_html__("Title", 'car-rental') . ' <span>' . esc_html__("Please enter text for icon", "car-rental") . '</span></label>
                                </li>
                                <li class="to-field">
                                  <input class="small" type="text" id="social_net_tooltip_input" />
                                </li>
                                <li class="full">&nbsp;</li>
                                <li class="to-label">
                                  <label>' . esc_html__("Url", 'car-rental') . ' <span>' . esc_html__("Please enter Full Url", "car-rental") . '</span></label>
                                </li>
                                <li class="to-field">
                                  <input class="small" type="text" id="social_net_url_input"  />
                                </li>
                                <li class="full">&nbsp;</li>
                                <li class="to-label">
                                  <label>Icon Path</label>
                                </li>
                                <li class="to-field">
                                <div class="input-sec">
                                  <input id="social_net_icon_path_input" type="hidden" class="small" onblur="javascript:update_image("social_net_icon_path_input_img_div")" />
                                </div>
                                  <label class="browse-icon"><input id="social_net_icon_path_input" name="social_net_icon_path_input" type="button" class="uploadMedia left" value="' . esc_html__('Browse', 'car-rental') . '"/></label>
                               </li>
                                <li style="padding: 10px 0px 20px;" class="full">
                                 <ul id="cs_infobox_networks' . $counter . '">
                                    <li class="to-label">
                                      <label>' . esc_html__("Fontawsome Icon", 'car-rental') . '</label>
                                    </li>
                                    <li class="to-field">' . cs_fontawsome_theme_options("", "networks" . $counter, 'social_net_awesome_input') . '</li>
                                 </ul>
                                </li>
                                
                              <li class="to-label">
                                <label>' . esc_html__('FontAwesome Color', 'car-rental') . '<span></span></label>
                              </li>
                              <li class="to-field">
                                <div class="input-sec">
                                <input type="text" name="social_font_awesome_color" id="social_font_awesome_color" value="#eee" class="bg_color" data-default-color="#eee" /></div>
                                <div class="left-info">
                                    <p></p>
                                </div>
                              </li>
                                
                                <li class="full">&nbsp;</li>
                                
                                
                                <li class="to-label"></li>
                                <li class="to-field" style="width:100%;">
                                  <input type="button" value="' . esc_html__("Add", 'car-rental') . '" onclick=javascript:cs_add_social_icon("' . admin_url("admin-ajax.php") . '") style="float:right;" />
                                </li>
                              </ul>
                              <div class="clear"></div>
                              <div class="social-area" style="display:' . $display . '">
                              <div class="theme-help">
                                <h4 style="padding-bottom:0px;">' . esc_html__("Already Added Social Icons", 'car-rental') . '</h4>
                                <div class="clear"></div>
                              </div>
                              <div class="boxes">
                              <table class="to-table" border="0" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>' . esc_html__("Icon Path", 'car-rental') . '</th>
                                      <th>' . esc_html__("Network Name", 'car-rental') . '</th>
                                      <th>' . esc_html__("Url", 'car-rental') . '</th>
                                      <th class="centr">' . esc_html__("Actions Icon", 'car-rental') . '</th>
                                    </tr>
                                  </thead>
                                  <tbody id="social_network_area">';
                    $i = 0;
                    if ($network_list <> '') {
                        foreach ($network_list as $network) {
                            if (isset($network_list[$i]) || isset($network_list[$i])) {

                                $output.='<tr id="del_' . str_replace(' ', '-', $social_net_tooltip[$i]) . '"><td>';
                                if (isset($network_list[$i]) and $network_list[$i] <> '') {
                                    $output .= '<i style="color:' . $social_font_awesome_color[$i] . ';" class="fa ' . $network_list[$i] . ' icon-2x"></i>';
                                } else {
                                    $output.='<img width="50" src="' . esc_url($social_net_icon_path[$i]) . '">';
                                }
                                $output .= '</td><td>' . $social_net_tooltip[$i] . '</td>';
                                $output .= '<td><a href="#">' . $social_net_url[$i] . '</a></td>';
                                $output .= '<td class="centr"> 
                                                              <a class="remove-btn" onclick="javascript:return confirm(\'Are you sure! You want to delete this\')" href="javascript:social_icon_del(\'' . str_replace(' ', '-', $social_net_tooltip[$i]) . '\')" data-toggle="tooltip" data-placement="top" title="Remove">
                                                              <i class="icon-times"></i></a>
                                                              <a href="javascript:cs_toggle(\'' . str_replace(' ', '-', $social_net_tooltip[$i]) . '\')" data-toggle="tooltip" data-placement="top" title="Edit">
                                                                <i class="icon-edit"></i>
                                                              </a>
                                                          </td></tr>';

                                $output.='<tr id="' . str_replace(' ', '-', $social_net_tooltip[$i]) . '" style="display:none">
                                                      <td colspan="3"><ul class="form-elements">
                                                      <li><a onclick="cs_toggle(\'' . str_replace(' ', '-', $social_net_tooltip[$i]) . '\')"><img src="' . get_template_directory_uri() . '/include/assets/images/close-red.png" /></a></li>
                                                            <li class="to-label">
                                                            <label>' . esc_html__("Title", 'car-rental') . '</label>
                                                          </li>
                                                          <li class="to-field">
                                                            <input class="small" type="text" id="social_net_tooltip" name="social_net_tooltip[]" value="' . $social_net_tooltip[$i] . '"  />
                                                            <p>' . esc_html__("Please enter text for icon tooltip", 'car-rental') . '</p>
                                                          </li>
                                                          <li class="full">&nbsp;</li>
                                                          <li class="to-label">
                                                            <label>' . esc_html__("Url", 'car-rental') . '</label>
                                                          </li>
                                                          <li class="to-field">
                                                            <input class="small" type="text" id="social_net_url" name="social_net_url[]" value="' . $social_net_url[$i] . '"/>
                                                            <p>' . esc_html__("Please enter full Url", 'car-rental') . '</p>
                                                          </li>
                                                          <li class="full">&nbsp;</li>
                                                          <li class="to-label">
                                                            <label>' . esc_html__("Icon Path", 'car-rental') . '</label>
                                                          </li>
                                                          <li class="to-field">
                                                            <input id="social_net_icon_path' . $i . '" name="social_net_icon_path[]" value="' . $social_net_icon_path[$i] . '" type="text" class="small" />
                                                            <label class="browse-icon"><input id="social_net_icon_path' . $i . '" name="social_net_icon_path' . $i . '" type="button" class="uploadMedia left" value="' . esc_html__('Browse', 'car-rental') . '"/></label>
                                                          </li>
                                                          
                                                          <li class="full">&nbsp;</li>
                                                          <li style="padding: 0px 0px 20px;" class="full">
                                                             <ul id="cs_infobox_theme_options' . $i . '">
                                                                <li class="to-label">
                                                                  <label>' . esc_html__("Fontawsome Icon", 'car-rental') . '</label>
                                                                </li>
                                                                <li class="to-field">' . cs_fontawsome_theme_options($network_list[$i], "theme_options" . $i, 'social_net_awesome') . '
                                                                
                                                                </li>
                                                             </ul>
                                                            </li>
                                                          <li class="to-label">
                                                            <label>' . esc_html__("Font Awesome Color", 'car-rental') . '</label>
                                                          </li>
                                                          <li class="to-field">
                                                            <div class="input-sec">
                                                            <input type="text" name="social_font_awesome_color[]" id="social_font_awesome_color" value="' . $social_font_awesome_color[$i] . '" class="bg_color" data-default-color="' . $social_font_awesome_color[$i] . '" /></div>
                                                            <div class="left-info">
                                                                <p></p>
                                                            </div>
                                                          </li>
                                                        </ul></td>
                                                    </tr>';
                            }
                            $i++;
                        }
                    }

                    $output .= '</tbody></table></div></div>';

                    break;
                    $output .= '</div>';

                case "banner_fields":
                    $cs_rand_id = rand(23789, 534578930);
                    if (isset($cs_theme_options) and $cs_theme_options <> '') {

                        if (!isset($cs_theme_options['banner_field_title'])) {
                            $banner_fields = '';
                            $banner_fields_style = '';
                            $banner_fields_type = '';
                            $banner_fields_image = '';
                            $banner_fields_url = '';
                            $banner_fields_url_target = '';
                            $banner_fields_code = '';
                            $banner_fields_code_no = '';
                            $display = 'none';
                        } else {
                            $banner_fields = $cs_theme_options['banner_field_title'];
                            $banner_fields_style = $cs_theme_options['banner_field_style'];
                            $banner_fields_type = $cs_theme_options['banner_field_type'];
                            $banner_fields_image = $cs_theme_options['banner_field_image'];
                            $banner_fields_url = $cs_theme_options['banner_field_url'];
                            $banner_fields_url_target = $cs_theme_options['banner_field_url_target'];
                            $banner_fields_code = $cs_theme_options['banner_adsense_code'];
                            $banner_fields_code_no = $cs_theme_options['banner_field_code_no'];
                            $display = 'block';
                        }
                    } else {
                        $val = $value['options'];
                        $std = $value['id'];
                        $display = 'block';
                        $banner_fields = $cs_theme_options['banner_field_title'];
                        $banner_fields_style = $cs_theme_options['banner_field_style'];
                        $banner_fields_type = $cs_theme_options['banner_field_type'];
                        $banner_fields_image = $cs_theme_options['banner_field_image'];
                        $banner_fields_url = $cs_theme_options['banner_field_url'];
                        $banner_fields_url_target = $cs_theme_options['banner_field_url_target'];
                        $banner_fields_code = $cs_theme_options['banner_adsense_code'];
                        $banner_fields_code_no = $cs_theme_options['banner_field_code_no'];
                    }
                    $output.='<ul class="form-elements">
                                <li class="to-label">
                                  <label>' . esc_html__('Title', 'car-rental') . ' <span>' . esc_html__('Please enter Banner Title', 'car-rental') . '</span></label>
                                </li>
                                <li class="to-field">
                                  <input class="small" type="text" id="banner_title_input" />
                                </li>
                                <li class="full">&nbsp;</li>
                                <li class="to-label">
                                  <label>' . esc_html__('Banner Style', 'car-rental') . '  <span>' . esc_html__('Please enter Banner Banner Style', 'car-rental') . '</span></label>
                                </li>
                                <li class="to-field select-style">
                                  <select id="banner_style_input">
                                    <option value="top_banner">' . esc_html__('Top Banner', 'car-rental') . '</option>
                                    <option value="bottom_banner">' . esc_html__('Bottom Banner', 'car-rental') . '</option>
                                    <option value="sidebar_banner">' . esc_html__('Sidebar Banner', 'car-rental') . '</option>
                                    <option value="vertical_banner">' . esc_html__('Vertical Banner', 'car-rental') . '</option>
                                  </select>
                                </li>
                                <li class="full">&nbsp;</li>
                                <li class="to-label">
                                  <label>' . esc_html__('Banner Type', 'car-rental') . ' <span>' . esc_html__('Please enter Banner Banner Type', 'car-rental') . '</span></label>
                                </li>
                                <li class="to-field select-style">
                                  <select id="banner_type_input" onchange="cs_banner_type_toggle(this.value, \'' . $cs_rand_id . '\')">
                                    <option value="image">' . esc_html__('Image', 'car-rental') . '</option>
                                    <option value="code">' . esc_html__('Code', 'car-rental') . '</option>
                                  </select>
                                </li>
                                <li class="full">&nbsp;</li>
                                <li class="to-label" id="cs_banner_image_field_' . $cs_rand_id . '">
                                  <label>' . esc_html__('Image', 'car-rental') . ' <span>' . esc_html__('Please enter Banner Image', 'car-rental') . '</span></label>
                                </li>
                                <li class="to-field" id="cs_banner_image_value_' . $cs_rand_id . '">
                                  <ul class="form-elements" id="' . $cs_rand_id . '_upload">
                                    <li class="to-field">
                                      <input id="banner_field_image' . $cs_rand_id . '" type="hidden" class="" value=""/>
                                      <label class="browse-icon">
                                      <input name="banner_field_image' . $cs_rand_id . '" type="button" class="uploadMedia left" value="' . esc_html__('Browse', 'car-rental') . '"/></label>
                                    </li>
                                  </ul>
                                  <div class="page-wrap" style="overflow:hidden;display:none" id="banner_field_image' . $cs_rand_id . '_box" >
                                    <div class="gal-active" style="padding-left:0 !important;">
                                      <div class="dragareamain" style="padding-bottom:0px;">
                                        <ul id="gal-sortable">
                                          <li class="ui-state-default" id="">
                                            <div class="thumb-secs"> <img src="" id="banner_field_image' . $cs_rand_id . '_img" alt="banner_field_image"  />
                                              <div class="gal-edit-opts"> <a href=javascript:del_media("banner_field_image' . $cs_rand_id . '") class="delete"></a> </div>
                                            </div>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                  </div>
                                </li>
                                <li class="full">&nbsp;</li>
                                <li class="to-label">
                                  <label>' . esc_html__('Url', 'car-rental') . ' <span>' . esc_html__('Please enter Banner Url', 'car-rental') . '</span></label>
                                </li>
                                <li class="to-field">
                                  <input class="small" type="text" id="banner_field_url_input"  />
                                </li>
                                <li class="full">&nbsp;</li>
                                <li class="to-label">
                                  <label>' . esc_html__('Target', 'car-rental') . ' <span>' . esc_html__('Please select Banner Link Target', 'car-rental') . '</span></label>
                                </li>
                                <li class="to-field select-style">
                                  <select id="banner_field_url_target_input">
                                    <option value="_self">' . esc_html__('Self', 'car-rental') . '</option>
                                    <option value="_blank">' . esc_html__('Blank', 'car-rental') . '</option>
                                  </select>
                                </li>
                                <li class="full">&nbsp;</li>
                                <li class="to-label" id="cs_banner_code_field_' . $cs_rand_id . '" style="display:none;">
                                  <label>' . esc_html__('Ad sense Code', 'car-rental') . '<span>' . esc_html__('Please enter Banner Ad sense Code', 'car-rental') . '</span></label>
                                </li>
                                <li class="to-field" id="cs_banner_code_value_' . $cs_rand_id . '" style="display:none;">
                                  <textarea id="banner_adsense_code_input"></textarea>
                                </li>
                                <li class="full">&nbsp;</li>
                                
                                <li class="to-label"></li>
                                <li class="to-field" style="width:100%;">
                                  <span id="banner-loader"></span>
                                  <input type="button" value="' . esc_html__('Add', 'car-rental') . '" onclick=javascript:cs_add_banner_fields("' . admin_url("admin-ajax.php") . '","' . esc_js(get_template_directory_uri()) . '","' . $cs_rand_id . '") style="float:right;" />
                                </li>
                              </ul>
                              <div class="clear"></div>
                              <div class="banner-fields-area" style="display:' . $display . '">
                              <div class="theme-help">
                                <h4 style="padding-bottom:0px;">' . esc_html__('Already Added Banners', 'car-rental') . '</h4>
                                <div class="clear"></div>
                              </div>
                              <div class="boxes">
                              <table class="to-table" border="0" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>' . esc_html__('Title', 'car-rental') . '</th>
                                      <th>' . esc_html__('Style', 'car-rental') . '</th>
                                      <th>' . esc_html__('Image', 'car-rental') . '</th>
                                      <th>' . esc_html__('Clicks', 'car-rental') . '</th>
                                      <th>' . esc_html__('Shortcode', 'car-rental') . '</th>
                                    </tr>
                                  </thead>
                                  <tbody id="cs_banner_fields">';
                    $i = 0;
                    if ($banner_fields <> '') {
                        foreach ($banner_fields as $field) {
                            $cs_rand_id = rand(23789, 934578930) . $i;
                            if (isset($banner_fields[$i]) || isset($banner_fields[$i])) {
                                // Image Display Block Check
                                $cs_image_display = $banner_fields_image[$i] <> '' ? 'block' : 'none';
                                // Banner Style Check
                                $cs_top_banner_selected = $banner_fields_style[$i] == 'top_banner' ? 'selected' : '';
                                $cs_bottom_banner_selected = $banner_fields_style[$i] == 'bottom_banner' ? 'selected' : '';
                                $cs_sidebar_banner_selected = $banner_fields_style[$i] == 'sidebar_banner' ? 'selected' : '';
                                $cs_vertical_banner_selected = $banner_fields_style[$i] == 'vertical_banner' ? 'selected' : '';
                                // Banner Type Check
                                $cs_image_banner_selected = $banner_fields_type[$i] == 'image' ? 'selected' : '';
                                $cs_code_banner_selected = $banner_fields_type[$i] == 'code' ? 'selected' : '';
                                // Banner Type Display Block Check
                                $cs_baner_image_display = $banner_fields_type[$i] == 'image' ? 'block' : 'none';
                                $cs_baner_code_display = $banner_fields_type[$i] == 'code' ? 'block' : 'none';
                                // Target Check
                                $cs_self_target_selected = $banner_fields_url_target[$i] == '_self' ? 'selected' : '';
                                $cs_blank_target_selected = $banner_fields_url_target[$i] == '_blank' ? 'selected' : '';

                                if ($banner_fields_style[$i] == 'top_banner') {
                                    $cs_banner_group = 'Top';
                                } else if ($banner_fields_style[$i] == 'bottom_banner') {
                                    $cs_banner_group = 'Bottom';
                                } else if ($banner_fields_style[$i] == 'sidebar_banner') {
                                    $cs_banner_group = 'Sidebar';
                                } else {
                                    $cs_banner_group = 'Vertical';
                                }
                                $output.='<tr id="del_' . cs_slugify_text($banner_fields[$i]) . '">';
                                $output .= '<td>' . $banner_fields[$i] . '</td>';
                                $output .= '<td>' . $cs_banner_group . '</td>';
                                if ($banner_fields_type[$i] == 'image') {
                                    if ($banner_fields_image[$i] <> '') {
                                        $output .= '<td><img src="' . esc_url($banner_fields_image[$i]) . '" alt="banner_fields_image" width="100" /></td>';
                                    } else {
                                        $output .= '<td>&nbsp;</td>';
                                    }
                                } else {
                                    $output .= '<td>' . esc_html__('Custom Code', 'car-rental') . '</td>';
                                }
                                if ($banner_fields_type[$i] == 'image') {
                                    $cs_banner_click_count = get_option("cs_banner_clicks_" . $banner_fields_code_no[$i]);
                                    $cs_banner_click_count = $cs_banner_click_count <> '' ? $cs_banner_click_count : '0';
                                    $output .= '<td>' . $cs_banner_click_count . '</td>';
                                } else {
                                    $output .= '<td>&nbsp;</td>';
                                }
                                $output .= '<td>[cs_ads id="' . $banner_fields_code_no[$i] . '"]</td>';
                                $output .= '<td class="centr"> 
                                                  <a class="remove-btn" onclick="javascript:return confirm(\'Are you sure! You want to delete this\')" href="javascript:social_icon_del(\'' . cs_slugify_text($banner_fields[$i]) . '\')" data-toggle="tooltip" data-placement="top" title="Remove">
                                                  <i class="icon-cross3"></i></a>
                                                  <a href="javascript:cs_toggle(\'' . cs_slugify_text($banner_fields[$i]) . '\')" data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i class="icon-pencil6"></i>
                                                  </a>
                                              </td>
                                            </tr>';
                                $output.='<tr id="' . cs_slugify_text($banner_fields[$i]) . '" style="display:none">
                                          <td colspan="3"><ul class="form-elements">
                                          <li><a onclick="cs_toggle(\'' . cs_slugify_text($banner_fields[$i]) . '\')"><img src="' . get_template_directory_uri() . '/include/assets/images/close-red.png" /></a></li>
                                              <li class="to-label">
                                                <label>' . esc_html__('Title', 'car-rental') . '</label>
                                              </li>
                                              <li class="to-field">
                                                <input class="small" type="text" name="banner_field_title[]" value="' . $banner_fields[$i] . '"  />
                                                <p>' . esc_html__('Please enter Banner Title', 'car-rental') . '</p>
                                              </li>
                                              <li class="full">&nbsp;</li>
                                              <li class="to-label">
                                                <label>' . esc_html__('Banner Style', 'car-rental') . '<span>' . esc_html__('Please enter Banner Style', 'car-rental') . '</span></label>
                                              </li>
                                              <li class="to-field select-style">
                                                <select name="banner_field_style[]">
                                                  <option ' . $cs_top_banner_selected . ' value="top_banner">' . esc_html__('Top Banner', 'car-rental') . '</option>
                                                  <option ' . $cs_bottom_banner_selected . ' value="bottom_banner">' . esc_html__('Bottom Banner', 'car-rental') . '</option>
                                                  <option ' . $cs_sidebar_banner_selected . ' value="sidebar_banner">' . esc_html__('Sidebar Banner', 'car-rental') . '</option>
                                                  <option ' . $cs_vertical_banner_selected . ' value="vertical_banner">' . esc_html__('Vertical Banner', 'car-rental') . '</option>
                                                </select>
                                              </li>
                                              <li class="full">&nbsp;</li>
                                              <li class="to-label">
                                                <label>' . esc_html__('Banner Type', 'car-rental') . '</label>
                                              </li>
                                              <li class="to-field select-style">
                                                <select name="banner_field_type[]" onchange="cs_banner_type_toggle(this.value, \'' . $cs_rand_id . '\')">
                                                  <option ' . $cs_image_banner_selected . ' value="image">' . esc_html__('Image', 'car-rental') . '</option>
                                                  <option ' . $cs_code_banner_selected . ' value="code">' . esc_html__('Code', 'car-rental') . '</option>
                                                </select>
                                                <p>' . esc_html__('Please enter Banner Banner Type', 'car-rental') . '</p>
                                              </li>
                                              <li class="full">&nbsp;</li>
                                              <li class="to-label" id="cs_banner_image_field_' . $cs_rand_id . '" style="display:' . $cs_baner_image_display . ';">
                                                <label>' . esc_html__('Image', 'car-rental') . '</label>
                                              </li>
                                              <li class="to-field" id="cs_banner_image_value_' . $cs_rand_id . '" style="display:' . $cs_baner_image_display . ';">
                                                <ul class="form-elements" id="' . $i . '_upload">
                                                  <li class="to-field">
                                                    <input id="banner_field_image' . $i . '" name="banner_field_image[]" type="hidden" class="" value="' . $banner_fields_image[$i] . '"/>
                                                    <label class="browse-icon">
                                                    <input name="banner_field_image' . $i . '" type="button" class="uploadMedia left" value="' . esc_html__('Browse', 'car-rental') . '"/></label>
                                                  </li>
                                                </ul>
                                                <div class="page-wrap" style="overflow:hidden;display:' . $cs_image_display . ';" id="banner_field_image' . $i . '_box" >
                                                  <div class="gal-active" style="padding-left:0 !important;">
                                                    <div class="dragareamain" style="padding-bottom:0px;">
                                                      <ul id="gal-sortable">
                                                        <li class="ui-state-default">
                                                          <div class="thumb-secs"> <img src="' . esc_url($banner_fields_image[$i]) . '" id="banner_field_image' . $i . '_img" />
                                                            <div class="gal-edit-opts"> <a href=javascript:del_media("banner_field_image' . $i . '") class="delete"></a> </div>
                                                          </div>
                                                        </li>
                                                      </ul>
                                                    </div>
                                                  </div>
                                                </div>
                                                <p>' . esc_html__('Please enter Banner Image', 'car-rental') . '</p>
                                              </li>
                                              <li class="full">&nbsp;</li>
                                              <li class="to-label">
                                                <label>' . esc_html__('Url', 'car-rental') . '</label>
                                              </li>
                                              <li class="to-field">
                                                <input class="small" type="text" name="banner_field_url[]" value="' . $banner_fields_url[$i] . '" />
                                                <p>' . esc_html__('Please enter Banner Url', 'car-rental') . '</p>
                                              </li>
                                              <li class="full">&nbsp;</li>
                                              <li class="to-label">
                                                <label>' . esc_html__('Target', 'car-rental') . '</label>
                                              </li>
                                              <li class="to-field select-style">
                                                <select name="banner_field_url_target[]">
                                                  <option ' . $cs_self_target_selected . ' value="_self">' . esc_html__('Self', 'car-rental') . '</option>
                                                  <option ' . $cs_blank_target_selected . ' value="_blank">' . esc_html__('Blank', 'car-rental') . '</option>
                                                </select>
                                                <p>' . esc_html__('Please select Banner Link Target', 'car-rental') . '</p>
                                              </li>
                                              <li class="full">&nbsp;</li>
                                              <li class="to-label" id="cs_banner_code_field_' . $cs_rand_id . '" style="display:' . $cs_baner_code_display . ';">
                                                <label>' . esc_html__('Ad sense Code', 'car-rental') . '</label>
                                              </li>
                                              <li class="to-field" id="cs_banner_code_value_' . $cs_rand_id . '" style="display:' . $cs_baner_code_display . ';">
                                                <textarea name="banner_adsense_code[]">' . esc_html($banner_fields_code[$i]) . '</textarea>
                                                <p>' . esc_html__('Please enter Banner Ad sense Code', 'car-rental') . '</p>
                                              </li>
                                              <li class="full">&nbsp;</li>
                                              <input type="hidden" name="banner_field_code_no[]" value="' . $banner_fields_code_no[$i] . '" />
                                            </ul></td>
                                        </tr>';
                            }
                            $i++;
                        }
                    }

                    $output .= '</tbody></table></div></div>';
            }
        }
        $output .= '</div>';
        return array($output, $menu);
    }

}
