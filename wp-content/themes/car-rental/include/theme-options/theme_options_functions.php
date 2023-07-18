<?php

/**
 * @Save Theme Options
 * @return
 *
 */
if (!function_exists('theme_option_save')) {

    function theme_option_save() {

        global $reset_date, $cs_options;

        $_POST = cs_stripslashes_htmlspecialchars($_POST);

        update_option("cs_theme_options", $_POST);

        echo "All Settings Saved";

        die();
    }

    add_action('wp_ajax_theme_option_save', 'theme_option_save');
}

/**
 * @Generate Options Backup
 * @return
 *
 */
if (!function_exists('cs_options_backup_generate')) {

    function cs_options_backup_generate() {

        global $wp_filesystem;

        $cs_export_options = get_option('cs_theme_options');

        $cs_option_fields = json_encode($cs_export_options, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

        $backup_url = wp_nonce_url('themes.php?page=cs_options_page');
        if (false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) )) {

            return true;
        }

        if (!WP_Filesystem($creds)) {
            request_filesystem_credentials($backup_url, '', true, false, array());
            return true;
        }

        $cs_upload_dir = get_template_directory() . '/include/theme-options/backups/';
        $cs_filename = trailingslashit($cs_upload_dir) . (current_time('d-M-Y_H.i.s')) . '.json';


        if (!$wp_filesystem->put_contents($cs_filename, $cs_option_fields, FS_CHMOD_FILE)) {
            echo esc_html__("Error saving file!", "car-rental");
        } else {
            echo esc_html__("Backup Generated.", "car-rental");
        }

        die();
    }

    add_action('wp_ajax_cs_options_backup_generate', 'cs_options_backup_generate');
}

/**
 * @Delete Backup File
 * @return
 *
 */
if (!function_exists('cs_backup_file_delete')) {

    function cs_backup_file_delete() {

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

        $file_name = isset($_POST['file_name']) ? $_POST['file_name'] : '';

        $cs_filename = trailingslashit($cs_upload_dir) . $file_name;

        if (is_file($cs_filename)) {
            unlink($cs_filename);
            printf(esc_html__("File '%s' Deleted Successfully", "car-rental"), $file_name);
        } else {
            echo esc_html__("Error Deleting file!", "car-rental");
        }

        die();
    }

    add_action('wp_ajax_cs_backup_file_delete', 'cs_backup_file_delete');
}

/**
 * @Restore Backup File
 * @return
 *
 */
if (!function_exists('cs_backup_file_restore')) {

    function cs_backup_file_restore() {

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

        $file_name = isset($_POST['file_name']) ? $_POST['file_name'] : '';

        $file_path = isset($_POST['file_path']) ? $_POST['file_path'] : '';

        if ($file_path == 'yes') {

            $cs_file_body = '';

            $cs_file_response = wp_remote_get($file_name, array('decompress' => false));

            if (is_array($cs_file_response)) {
                $cs_file_body = isset($cs_file_response['body']) ? $cs_file_response['body'] : '';
            }

            if ($cs_file_body != '') {

                $get_options_file = json_decode($cs_file_body, true);

                update_option("cs_theme_options", $get_options_file);

                esc_html_e("File Import Successfully", "car-rental");
            } else {
                esc_html_e("Error Restoring file!", "car-rental");
            }

            die;
        }

        $cs_filename = trailingslashit($cs_upload_dir) . $file_name;

        if (is_file($cs_filename)) {

            $get_options_file = $wp_filesystem->get_contents($cs_filename);

            $get_options_file = json_decode($get_options_file, true);

            update_option("cs_theme_options", $get_options_file);

            printf(esc_html__("File '%s' Restore Successfully", "car-rental"), $file_name);
        } else {
            esc_html_e("Error Restoring file!", "car-rental");
        }

        die();
    }

    add_action('wp_ajax_cs_backup_file_restore', 'cs_backup_file_restore');
}

/**
 * @saving all the theme options end
 * @return
 *
 */
if (!function_exists('theme_option_rest_all')) {

    function theme_option_rest_all() {

        global $wp_filesystem;

        $backup_url = esc_url(home_url('/'));
        if (false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) )) {

            return true;
        }

        if (!WP_Filesystem($creds)) {
            request_filesystem_credentials($backup_url, '', true, false, array());
            return true;
        }

        $cs_upload_dir = get_template_directory() . '/include/theme-options/default-settings/';

        $cs_filename = trailingslashit($cs_upload_dir) . 'default-settings.json';

        if (is_file($cs_filename)) {

            $get_options_file = $wp_filesystem->get_contents($cs_filename);

            $get_options_file = json_decode($get_options_file, true);
            
            update_option("cs_theme_options", $get_options_file);
        } else {
            cs_reset_data();
        }
        die;
    }

    add_action('wp_ajax_theme_option_rest_all', 'theme_option_rest_all');
}

if (!function_exists('theme_default_options')) {

    function theme_default_options() {

        global $wp_filesystem;

        $backup_url = wp_nonce_url('themes.php?page=cs_options_page');
        if (false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) )) {

            return true;
        }

        if (!WP_Filesystem($creds)) {
            request_filesystem_credentials($backup_url, '', true, false, array());
            return true;
        }

        $cs_upload_dir = get_template_directory() . '/include/theme-options/default-settings/';

        $cs_filename = trailingslashit($cs_upload_dir) . 'default-settings.json';

        if (is_file($cs_filename)) {

            $get_options_file = $wp_filesystem->get_contents($cs_filename);

            $cs_default_data = $get_options_file = json_decode($get_options_file, true);
        } else {
            $cs_default_data = cs_reset_data();
        }

        return $cs_default_data;
    }

}

if (!function_exists('cs_get_demo_content')) {

    function cs_get_demo_content($cs_demo_file = '') {

        global $wp_filesystem;

        $backup_url = wp_nonce_url('themes.php?page=cs_options_page');
        if (false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) )) {

            return true;
        }

        if (!WP_Filesystem($creds)) {
            request_filesystem_credentials($backup_url, '', true, false, array());
            return true;
        }

        $cs_upload_dir = get_template_directory() . '/include/theme-options/demo-data/';

        $cs_filename = trailingslashit($cs_upload_dir) . $cs_demo_file;

        $cs_demo_data = array();

        if (is_file($cs_filename)) {

            $get_options_file = $wp_filesystem->get_contents($cs_filename);

            $cs_demo_data = $get_options_file;
        }

        return $cs_demo_data;
    }

}

/**
 * @theme activation
 * @return
 *
 */
if (!function_exists('cs_activation_data')) {

    function cs_activation_data() {
        update_option('cs_theme_options', theme_default_options());
    }

}

/**
 * @array for reset theme options
 * @return
 *
 */
if (!function_exists('cs_reset_data')) {

    function cs_reset_data() {
        global $reset_data, $cs_options;
        foreach ($cs_options as $value) {
            if ($value['type'] <> 'heading' and $value['type'] <> 'sub-heading' and $value['type'] <> 'main-heading') {
                if ($value['type'] == 'sidebar' || $value['type'] == 'networks' || $value['type'] == 'badges') {
                    $reset_data = (array_merge($reset_data, $value['options']));
                } if ($value['type'] == 'packages_data') {
                    update_option('cs_packages_options', $value['std']);
                } if ($value['type'] == 'free_package') {
                    update_option('cs_free_package_switch', $value['std']);
                } else if ($value['type'] == 'check_color') {
                    $reset_data[$value['id']] = $value['std'];
                    $reset_data[$value['id'] . '_switch'] = 'off';
                } else {
                    $reset_data[$value['id']] = $value['std'];
                }
            }
        }
        return $reset_data;
    }

}

/**
 * @Sub Header Slider
 * @return
 *
 */
if (!function_exists('cs_headerbg_slider')) {

    function cs_headerbg_slider() {
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
    }

}
?>