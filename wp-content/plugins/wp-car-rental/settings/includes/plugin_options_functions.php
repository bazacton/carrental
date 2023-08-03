<?php

/* ------------------------------------------------------
 * Save Option
 * ----------------------------------------------------- */
if (!function_exists('plugin_option_save')) {

    function plugin_option_save() {
        global $reset_plugin_data, $cs_setting_options;
        if (isset($_POST)) {
            $_POST = cs_stripslashes_htmlspecialchars($_POST);
            update_option("cs_plugin_options", $_POST);
            cs_update_extras_options();
            cs_update_feats();
            cs_update_properties();
            echo __("All Settings Saved", "rental");
            die();
        }
    }

    add_action('wp_ajax_plugin_option_save', 'plugin_option_save');
}

/**
 * @Generate Options Backup
 * @return
 *
 */
if (!function_exists('cs_pl_opt_backup_generate')) {

    function cs_pl_opt_backup_generate() {

        global $wp_filesystem, $cs_plugin_options;

        $cs_export_options = get_option('cs_plugin_options');

        $cs_typ_options = get_option('cs_type_options');

        if (is_array($cs_export_options)) {
            $cs_export_options['cs_typ_options'] = $cs_typ_options;
        }

        $cs_option_fields = json_encode($cs_export_options, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

        $backup_url = wp_nonce_url('edit.php?post_type=vehicles&page=cs_settings');
        if (false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) )) {

            return true;
        }

        if (!WP_Filesystem($creds)) {
            request_filesystem_credentials($backup_url, '', true, false, array());
            return true;
        }

        $cs_upload_dir = wp_car_rental::plugin_dir() . 'settings/backups/';
        $cs_filename = trailingslashit($cs_upload_dir) . (current_time('d-M-Y_H.i.s')) . '.json';


        if (!$wp_filesystem->put_contents($cs_filename, $cs_option_fields, FS_CHMOD_FILE)) {
            echo __("Error saving file!", "rental");
        } else {
            echo __("Backup Generated.", "rental");
        }

        die();
    }

    add_action('wp_ajax_cs_pl_opt_backup_generate', 'cs_pl_opt_backup_generate');
}

/**
 * @Delete Backup File
 * @return
 *
 */
if (!function_exists('cs_pl_backup_file_delete')) {

    function cs_pl_backup_file_delete() {

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

        $file_name = isset($_POST['file_name']) ? $_POST['file_name'] : '';

        $cs_filename = trailingslashit($cs_upload_dir) . $file_name;

        if (is_file($cs_filename)) {
            unlink($cs_filename);
            printf(__("File '%s' Deleted Successfully", "rental"), $file_name);
        } else {
            echo __("Error Deleting file!", "rental");
        }

        die();
    }

    add_action('wp_ajax_cs_pl_backup_file_delete', 'cs_pl_backup_file_delete');
}

/**
 * @Restore Backup File
 * @return
 *
 */
if (!function_exists('cs_pl_backup_file_restore')) {

    function cs_pl_backup_file_restore() {

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

                update_option("cs_plugin_options", $get_options_file);

                _e("File Import Successfully", "rental");
            } else {
                _e("Error Restoring file!", "rental");
            }

            die;
        }

        $cs_filename = trailingslashit($cs_upload_dir) . $file_name;

        if (is_file($cs_filename)) {

            $get_options_file = $wp_filesystem->get_contents($cs_filename);

            $get_options_file = json_decode($get_options_file, true);

            update_option("cs_plugin_options", $get_options_file);

            printf(__("File '%s' Restore Successfully", "rental"), $file_name);
        } else {
            _e("Error Restoring file!", "rental");
        }

        die();
    }

    add_action('wp_ajax_cs_pl_backup_file_restore', 'cs_pl_backup_file_restore');
}
/**
 * @saving all the theme options end
 * @return
 *
 */
if (!function_exists('plugin_option_rest_all')) {

    function plugin_option_rest_all() {
        global $wp_filesystem;
        $backup_url = esc_url(home_url('/'));
        if (false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) )) {
            return true;
        }
        if (!WP_Filesystem($creds)) {
            request_filesystem_credentials($backup_url, '', true, false, array());
            return true;
        }
        $cs_upload_dir = wp_car_rental::plugin_dir() . 'settings/default-settings/';
        $cs_filename = trailingslashit($cs_upload_dir) . 'default-settings.json';
        if (is_file($cs_filename)) {
            $get_options_file = $wp_filesystem->get_contents($cs_filename);
            $get_options_file = json_decode($get_options_file, true);
            update_option("cs_plugin_options", $get_options_file);
        }
        die;
    }

    add_action('wp_ajax_plugin_option_rest_all', 'plugin_option_rest_all');
}

function cs_get_settings_demo($cs_demo_file = '') {
    global $wp_filesystem;
    $backup_url = '';
    if (false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) )) {
        return true;
    }
    if (!WP_Filesystem($creds)) {
        request_filesystem_credentials($backup_url, '', true, false, array());
        return true;
    }
    $cs_upload_dir = wp_car_rental::plugin_dir() . 'settings/demo/';
    $cs_filename = trailingslashit($cs_upload_dir) . $cs_demo_file;
    $cs_demo_data = array();
    if (is_file($cs_filename)) {
        $get_options_file = $wp_filesystem->get_contents($cs_filename);
        $cs_demo_data = $get_options_file;
    }
    return $cs_demo_data;
}

if (!function_exists('cs_demo_plugin_data')) {

    function cs_demo_plugin_data() {
        global $cs_settings_init;
        $demo_plugin_data = '';
        if (isset($cs_settings_init) && $cs_settings_init <> '') {
            $cs_settings = $cs_settings_init['plugin_options'];
            $plugin_settings = json_decode($cs_settings, true);
            $demo_plugin_data = $plugin_settings;
        }

        delete_option('cs_plugin_options');
        update_option("cs_plugin_options", $demo_plugin_data);

        if (isset($demo_plugin_data['cs_typ_options'])) {
            delete_option('cs_type_options');
            update_option("cs_type_options", $demo_plugin_data['cs_typ_options']);
        }
    }

}
/* ------------------------------------------------------
 * Update Packages
 * ----------------------------------------------------- */
if (!function_exists('stripslashes_htmlspecialchars')) {

    function stripslashes_htmlspecialchars($value) {
        $value = is_array($value) ? array_map('stripslashes_htmlspecialchars', $value) : stripslashes(htmlspecialchars($value));
        return $value;
    }

}
/* ------------------------------------------------------
 * Update Extras
 * ----------------------------------------------------- */
if (!function_exists('cs_update_extras_options')) {

    function cs_update_extras_options() {
        $data = get_option("cs_plugin_options");
        $extra_feature_counter = 0;
        $extra_feature_array = $extra_features = $extrasdata = array();
        if (isset($_POST['extra_feature_id_array']) && !empty($_POST['extra_feature_id_array'])) {
            foreach ($_POST['extra_feature_id_array'] as $keys => $values) {
                if ($values) {
                    $extra_feature_array['extra_feature_id'] = $_POST['extra_feature_id_array'][$extra_feature_counter];
                    $extra_feature_array['cs_extra_feature_title'] = $_POST['cs_extra_feature_title_array'][$extra_feature_counter];
                    $extra_feature_array['cs_extra_feature_price'] = $_POST['cs_extra_feature_price_array'][$extra_feature_counter];
                    $extra_feature_array['cs_extra_feature_type'] = $_POST['cs_extra_feature_type_array'][$extra_feature_counter];
                    $extra_feature_array['cs_extra_feature_fchange'] = $_POST['cs_extra_feature_fchange_array'][$extra_feature_counter];
                    $extra_feature_array['cs_extra_feature_desc'] = $_POST['cs_extra_feature_desc_array'][$extra_feature_counter];
                    $extra_features[$values] = $extra_feature_array;
                    $extra_feature_counter++;
                }
            }
        }
        $extrasdata['cs_extra_features_options'] = $extra_features;
        $cs_options = array_merge($data, $extrasdata);
        update_option("cs_plugin_options", $cs_options);
//        $obj = new cs_plugin_options();
//        $obj->cs_remove_duplicate_extra_value();
    }

}
// Update Features
if (!function_exists('cs_update_feats')) {

    function cs_update_feats() {
        $data = get_option("cs_plugin_options");
        $feats_counter = 0;
        $feats_array = $feats = $extrasdata = array();
        if (isset($_POST['feats_id_array']) && !empty($_POST['feats_id_array'])) {
            foreach ($_POST['feats_id_array'] as $keys => $values) {
                if ($values) {
                    $feats_array['feats_id'] = $_POST['feats_id_array'][$feats_counter];
                    $feats_array['cs_feats_title'] = $_POST['cs_feats_title_array'][$feats_counter];
                    $feats_array['cs_feats_image'] = $_POST['cs_feats_image_array'][$feats_counter];
                    $feats_array['cs_feats_desc'] = $_POST['cs_feats_desc_array'][$feats_counter];
                    $feats[$values] = $feats_array;
                    $feats_counter++;
                }
            }
        }
        $extrasdata['cs_feats_options'] = $feats;
        $cs_options = array_merge($data, $extrasdata);
        update_option("cs_plugin_options", $cs_options);
    }

}
// Update Properties
if (!function_exists('cs_update_properties')) {

    function cs_update_properties() {
        $data = get_option("cs_plugin_options");
        $properties_counter = 0;
        $properties_array = $properties = $extrasdata = array();
        if (isset($_POST['properties_id_array']) && !empty($_POST['properties_id_array'])) {
            foreach ($_POST['properties_id_array'] as $keys => $values) {
                if ($values) {
                    $properties_array['properties_id'] = $_POST['properties_id_array'][$properties_counter];
                    $properties_array['cs_properties_title'] = $_POST['cs_properties_title_array'][$properties_counter];
                    //$properties_array['cs_properties_image']	= $_POST['cs_properties_image_array'][$properties_counter];
                    $properties_array['cs_properties_desc'] = $_POST['cs_properties_desc_array'][$properties_counter];
                    $properties[$values] = $properties_array;
                    $properties_counter++;
                }
            }
        }
        $extrasdata['cs_properties_options'] = $properties;
        $cs_options = array_merge($data, $extrasdata);
        update_option("cs_plugin_options", $cs_options);
    }

}
/* ------------------------------------------------------
 * Activation Data
 * ----------------------------------------------------- */
if (!function_exists('cs_activation-plugin_data')) {

    function plugin_data() {
        update_option('cs_plugin_options', cs_reset_plugin_data());
    }

}
/* ------------------------------------------------------
 * Get Currency Symbol
 * ----------------------------------------------------- */
if (!function_exists('cs_get_currency_symbol')) {

    function cs_get_currency_symbol() {
        $code = $_POST['code'];
        $currency_list = cs_get_currency();
        echo CS_FUNCTIONS()->cs_special_chars($currency_list[$code]['symbol']);
        die();
    }

    add_action('wp_ajax_cs_get_currency_symbol', 'cs_get_currency_symbol');
}
/* ------------------------------------------------------
 * Get Currency List
 * ----------------------------------------------------- */
if (!function_exists('cs_get_currency')) {

    function cs_get_currency() {
        return array(
            'USD' => array('numeric_code' => 840, 'code' => 'USD', 'name' => 'United States dollar', 'symbol' => '$', 'fraction_name' => 'Cent[D]', 'decimals' => 2),
            'AED' => array('numeric_code' => 784, 'code' => 'AED', 'name' => 'United Arab Emirates dirham', 'symbol' => 'د.إ', 'fraction_name' => 'Fils', 'decimals' => 2),
            'AFN' => array('numeric_code' => 971, 'code' => 'AFN', 'name' => 'Afghan afghani', 'symbol' => '؋', 'fraction_name' => 'Pul', 'decimals' => 2),
            'ALL' => array('numeric_code' => 8, 'code' => 'ALL', 'name' => 'Albanian lek', 'symbol' => 'L', 'fraction_name' => 'Qintar', 'decimals' => 2),
            'AMD' => array('numeric_code' => 51, 'code' => 'AMD', 'name' => 'Armenian dram', 'symbol' => 'դր.', 'fraction_name' => 'Luma', 'decimals' => 2),
            'AMD' => array('numeric_code' => 51, 'code' => 'AMD', 'name' => 'Armenian dram', 'symbol' => 'դր.', 'fraction_name' => 'Luma', 'decimals' => 2),
            'ANG' => array('numeric_code' => 532, 'code' => 'ANG', 'name' => 'Netherlands Antillean guilder', 'symbol' => 'ƒ', 'fraction_name' => 'Cent', 'decimals' => 2),
            'AOA' => array('numeric_code' => 973, 'code' => 'AOA', 'name' => 'Angolan kwanza', 'symbol' => 'Kz', 'fraction_name' => 'Cêntimo', 'decimals' => 2),
            'ARS' => array('numeric_code' => 32, 'code' => 'ARS', 'name' => 'Argentine peso', 'symbol' => '$', 'fraction_name' => 'Centavo', 'decimals' => 2),
            'AUD' => array('numeric_code' => 36, 'code' => 'AUD', 'name' => 'Australian dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'AWG' => array('numeric_code' => 533, 'code' => 'AWG', 'name' => 'Aruban florin', 'symbol' => 'ƒ', 'fraction_name' => 'Cent', 'decimals' => 2),
            'AZN' => array('numeric_code' => 944, 'code' => 'AZN', 'name' => 'Azerbaijani manat', 'symbol' => 'AZN', 'fraction_name' => 'Qəpik', 'decimals' => 2),
            'BAM' => array('numeric_code' => 977, 'code' => 'BAM', 'name' => 'Bosnia and Herzegovina convertible mark', 'symbol' => 'КМ', 'fraction_name' => 'Fening', 'decimals' => 2),
            'BBD' => array('numeric_code' => 52, 'code' => 'BBD', 'name' => 'Barbadian dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'BDT' => array('numeric_code' => 50, 'code' => 'BDT', 'name' => 'Bangladeshi taka', 'symbol' => '৳', 'fraction_name' => 'Paisa', 'decimals' => 2),
            'BGN' => array('numeric_code' => 975, 'code' => 'BGN', 'name' => 'Bulgarian lev', 'symbol' => 'лв', 'fraction_name' => 'Stotinka', 'decimals' => 2),
            'BHD' => array('numeric_code' => 48, 'code' => 'BHD', 'name' => 'Bahraini dinar', 'symbol' => 'ب.د', 'fraction_name' => 'Fils', 'decimals' => 3),
            'BIF' => array('numeric_code' => 108, 'code' => 'BIF', 'name' => 'Burundian franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2),
            'BMD' => array('numeric_code' => 60, 'code' => 'BMD', 'name' => 'Bermudian dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'BND' => array('numeric_code' => 96, 'code' => 'BND', 'name' => 'Brunei dollar', 'symbol' => '$', 'fraction_name' => 'Sen', 'decimals' => 2),
            'BND' => array('numeric_code' => 96, 'code' => 'BND', 'name' => 'Brunei dollar', 'symbol' => '$', 'fraction_name' => 'Sen', 'decimals' => 2),
            'BOB' => array('numeric_code' => 68, 'code' => 'BOB', 'name' => 'Bolivian boliviano', 'symbol' => 'Bs.', 'fraction_name' => 'Centavo', 'decimals' => 2),
            'BRL' => array('numeric_code' => 986, 'code' => 'BRL', 'name' => 'Brazilian real', 'symbol' => 'R$', 'fraction_name' => 'Centavo', 'decimals' => 2),
            'BSD' => array('numeric_code' => 44, 'code' => 'BSD', 'name' => 'Bahamian dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'BTN' => array('numeric_code' => 64, 'code' => 'BTN', 'name' => 'Bhutanese ngultrum', 'symbol' => 'BTN', 'fraction_name' => 'Chertrum', 'decimals' => 2),
            'BWP' => array('numeric_code' => 72, 'code' => 'BWP', 'name' => 'Botswana pula', 'symbol' => 'P', 'fraction_name' => 'Thebe', 'decimals' => 2),
            'BWP' => array('numeric_code' => 72, 'code' => 'BWP', 'name' => 'Botswana pula', 'symbol' => 'P', 'fraction_name' => 'Thebe', 'decimals' => 2),
            'BYR' => array('numeric_code' => 974, 'code' => 'BYR', 'name' => 'Belarusian ruble', 'symbol' => 'Br', 'fraction_name' => 'Kapyeyka', 'decimals' => 2),
            'BZD' => array('numeric_code' => 84, 'code' => 'BZD', 'name' => 'Belize dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'CAD' => array('numeric_code' => 124, 'code' => 'CAD', 'name' => 'Canadian dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'CDF' => array('numeric_code' => 976, 'code' => 'CDF', 'name' => 'Congolese franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2),
            'CHF' => array('numeric_code' => 756, 'code' => 'CHF', 'name' => 'Swiss franc', 'symbol' => 'Fr', 'fraction_name' => 'Rappen[I]', 'decimals' => 2),
            'CLP' => array('numeric_code' => 152, 'code' => 'CLP', 'name' => 'Chilean peso', 'symbol' => '$', 'fraction_name' => 'Centavo', 'decimals' => 2),
            'CNY' => array('numeric_code' => 156, 'code' => 'CNY', 'name' => 'Chinese yuan', 'symbol' => '元', 'fraction_name' => 'Fen[E]', 'decimals' => 2),
            'COP' => array('numeric_code' => 170, 'code' => 'COP', 'name' => 'Colombian peso', 'symbol' => '$', 'fraction_name' => 'Centavo', 'decimals' => 2),
            'CRC' => array('numeric_code' => 188, 'code' => 'CRC', 'name' => 'Costa Rican colón', 'symbol' => '₡', 'fraction_name' => 'Céntimo', 'decimals' => 2),
            'CUC' => array('numeric_code' => 931, 'code' => 'CUC', 'name' => 'Cuban convertible peso', 'symbol' => '$', 'fraction_name' => 'Centavo', 'decimals' => 2),
            'CUP' => array('numeric_code' => 192, 'code' => 'CUP', 'name' => 'Cuban peso', 'symbol' => '$', 'fraction_name' => 'Centavo', 'decimals' => 2),
            'CVE' => array('numeric_code' => 132, 'code' => 'CVE', 'name' => 'Cape Verdean escudo', 'symbol' => 'Esc', 'fraction_name' => 'Centavo', 'decimals' => 2),
            'CZK' => array('numeric_code' => 203, 'code' => 'CZK', 'name' => 'Czech koruna', 'symbol' => 'K�?', 'fraction_name' => 'Haléř', 'decimals' => 2),
            'DJF' => array('numeric_code' => 262, 'code' => 'DJF', 'name' => 'Djiboutian franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2),
            'DKK' => array('numeric_code' => 208, 'code' => 'DKK', 'name' => 'Danish krone', 'symbol' => 'kr', 'fraction_name' => 'Øre', 'decimals' => 2),
            'DKK' => array('numeric_code' => 208, 'code' => 'DKK', 'name' => 'Danish krone', 'symbol' => 'kr', 'fraction_name' => 'Øre', 'decimals' => 2),
            'DOP' => array('numeric_code' => 214, 'code' => 'DOP', 'name' => 'Dominican peso', 'symbol' => '$', 'fraction_name' => 'Centavo', 'decimals' => 2),
            'DZD' => array('numeric_code' => 12, 'code' => 'DZD', 'name' => 'Algerian dinar', 'symbol' => 'د.ج', 'fraction_name' => 'Centime', 'decimals' => 2),
            'EEK' => array('numeric_code' => 233, 'code' => 'EEK', 'name' => 'Estonian kroon', 'symbol' => 'KR', 'fraction_name' => 'Sent', 'decimals' => 2),
            'EGP' => array('numeric_code' => 818, 'code' => 'EGP', 'name' => 'Egyptian pound', 'symbol' => '£', 'fraction_name' => 'Piastre[F]', 'decimals' => 2),
            'ERN' => array('numeric_code' => 232, 'code' => 'ERN', 'name' => 'Eritrean nakfa', 'symbol' => 'Nfk', 'fraction_name' => 'Cent', 'decimals' => 2),
            'ETB' => array('numeric_code' => 230, 'code' => 'ETB', 'name' => 'Ethiopian birr', 'symbol' => 'ETB', 'fraction_name' => 'Santim', 'decimals' => 2),
            'EUR' => array('numeric_code' => 978, 'code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'fraction_name' => 'Cent', 'decimals' => 2),
            'FJD' => array('numeric_code' => 242, 'code' => 'FJD', 'name' => 'Fijian dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'FKP' => array('numeric_code' => 238, 'code' => 'FKP', 'name' => 'Falkland Islands pound', 'symbol' => '£', 'fraction_name' => 'Penny', 'decimals' => 2),
            'GBP' => array('numeric_code' => 826, 'code' => 'GBP', 'name' => 'British pound[C]', 'symbol' => '£', 'fraction_name' => 'Penny', 'decimals' => 2),
            'GEL' => array('numeric_code' => 981, 'code' => 'GEL', 'name' => 'Georgian lari', 'symbol' => 'ლ', 'fraction_name' => 'Tetri', 'decimals' => 2),
            'GHS' => array('numeric_code' => 936, 'code' => 'GHS', 'name' => 'Ghanaian cedi', 'symbol' => '₵', 'fraction_name' => 'Pesewa', 'decimals' => 2),
            'GIP' => array('numeric_code' => 292, 'code' => 'GIP', 'name' => 'Gibraltar pound', 'symbol' => '£', 'fraction_name' => 'Penny', 'decimals' => 2),
            'GMD' => array('numeric_code' => 270, 'code' => 'GMD', 'name' => 'Gambian dalasi', 'symbol' => 'D', 'fraction_name' => 'Butut', 'decimals' => 2),
            'GNF' => array('numeric_code' => 324, 'code' => 'GNF', 'name' => 'Guinean franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2),
            'GTQ' => array('numeric_code' => 320, 'code' => 'GTQ', 'name' => 'Guatemalan quetzal', 'symbol' => 'Q', 'fraction_name' => 'Centavo', 'decimals' => 2),
            'GYD' => array('numeric_code' => 328, 'code' => 'GYD', 'name' => 'Guyanese dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'HKD' => array('numeric_code' => 344, 'code' => 'HKD', 'name' => 'Hong Kong dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'HNL' => array('numeric_code' => 340, 'code' => 'HNL', 'name' => 'Honduran lempira', 'symbol' => 'L', 'fraction_name' => 'Centavo', 'decimals' => 2),
            'HRK' => array('numeric_code' => 191, 'code' => 'HRK', 'name' => 'Croatian kuna', 'symbol' => 'kn', 'fraction_name' => 'Lipa', 'decimals' => 2),
            'HTG' => array('numeric_code' => 332, 'code' => 'HTG', 'name' => 'Haitian gourde', 'symbol' => 'G', 'fraction_name' => 'Centime', 'decimals' => 2),
            'HUF' => array('numeric_code' => 348, 'code' => 'HUF', 'name' => 'Hungarian forint', 'symbol' => 'Ft', 'fraction_name' => 'Fillér', 'decimals' => 2),
            'IDR' => array('numeric_code' => 360, 'code' => 'IDR', 'name' => 'Indonesian rupiah', 'symbol' => 'Rp', 'fraction_name' => 'Sen', 'decimals' => 2),
            'ILS' => array('numeric_code' => 376, 'code' => 'ILS', 'name' => 'Israeli new sheqel', 'symbol' => '₪', 'fraction_name' => 'Agora', 'decimals' => 2),
            'INR' => array('numeric_code' => 356, 'code' => 'INR', 'name' => 'Indian rupee', 'symbol' => '₨', 'fraction_name' => 'Paisa', 'decimals' => 2),
            'IQD' => array('numeric_code' => 368, 'code' => 'IQD', 'name' => 'Iraqi dinar', 'symbol' => 'ع.د', 'fraction_name' => 'Fils', 'decimals' => 3),
            'IRR' => array('numeric_code' => 364, 'code' => 'IRR', 'name' => 'Iranian rial', 'symbol' => '﷼', 'fraction_name' => 'Dinar', 'decimals' => 2),
            'ISK' => array('numeric_code' => 352, 'code' => 'ISK', 'name' => 'Icelandic króna', 'symbol' => 'kr', 'fraction_name' => 'Eyrir', 'decimals' => 2),
            'JMD' => array('numeric_code' => 388, 'code' => 'JMD', 'name' => 'Jamaican dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'JOD' => array('numeric_code' => 400, 'code' => 'JOD', 'name' => 'Jordanian dinar', 'symbol' => 'د.ا', 'fraction_name' => 'Piastre[H]', 'decimals' => 2),
            'JPY' => array('numeric_code' => 392, 'code' => 'JPY', 'name' => 'Japanese yen', 'symbol' => '¥', 'fraction_name' => 'Sen[G]', 'decimals' => 2),
            'KES' => array('numeric_code' => 404, 'code' => 'KES', 'name' => 'Kenyan shilling', 'symbol' => 'Sh', 'fraction_name' => 'Cent', 'decimals' => 2),
            'KGS' => array('numeric_code' => 417, 'code' => 'KGS', 'name' => 'Kyrgyzstani som', 'symbol' => 'KGS', 'fraction_name' => 'Tyiyn', 'decimals' => 2),
            'KHR' => array('numeric_code' => 116, 'code' => 'KHR', 'name' => 'Cambodian riel', 'symbol' => '៛', 'fraction_name' => 'Sen', 'decimals' => 2),
            'KMF' => array('numeric_code' => 174, 'code' => 'KMF', 'name' => 'Comorian franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2),
            'KPW' => array('numeric_code' => 408, 'code' => 'KPW', 'name' => 'North Korean won', 'symbol' => '₩', 'fraction_name' => 'Ch�?n', 'decimals' => 2),
            'KRW' => array('numeric_code' => 410, 'code' => 'KRW', 'name' => 'South Korean won', 'symbol' => '₩', 'fraction_name' => 'Jeon', 'decimals' => 2),
            'KWD' => array('numeric_code' => 414, 'code' => 'KWD', 'name' => 'Kuwaiti dinar', 'symbol' => 'د.ك', 'fraction_name' => 'Fils', 'decimals' => 3),
            'KYD' => array('numeric_code' => 136, 'code' => 'KYD', 'name' => 'Cayman Islands dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'KZT' => array('numeric_code' => 398, 'code' => 'KZT', 'name' => 'Kazakhstani tenge', 'symbol' => '〒', 'fraction_name' => 'Tiyn', 'decimals' => 2),
            'LAK' => array('numeric_code' => 418, 'code' => 'LAK', 'name' => 'Lao kip', 'symbol' => '₭', 'fraction_name' => 'Att', 'decimals' => 2),
            'LBP' => array('numeric_code' => 422, 'code' => 'LBP', 'name' => 'Lebanese pound', 'symbol' => 'ل.ل', 'fraction_name' => 'Piastre', 'decimals' => 2),
            'LKR' => array('numeric_code' => 144, 'code' => 'LKR', 'name' => 'Sri Lankan rupee', 'symbol' => 'Rs', 'fraction_name' => 'Cent', 'decimals' => 2),
            'LRD' => array('numeric_code' => 430, 'code' => 'LRD', 'name' => 'Liberian dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'LSL' => array('numeric_code' => 426, 'code' => 'LSL', 'name' => 'Lesotho loti', 'symbol' => 'L', 'fraction_name' => 'Sente', 'decimals' => 2),
            'LTL' => array('numeric_code' => 440, 'code' => 'LTL', 'name' => 'Lithuanian litas', 'symbol' => 'Lt', 'fraction_name' => 'Centas', 'decimals' => 2),
            'LVL' => array('numeric_code' => 428, 'code' => 'LVL', 'name' => 'Latvian lats', 'symbol' => 'Ls', 'fraction_name' => 'Santīms', 'decimals' => 2),
            'LYD' => array('numeric_code' => 434, 'code' => 'LYD', 'name' => 'Libyan dinar', 'symbol' => 'ل.د', 'fraction_name' => 'Dirham', 'decimals' => 3),
            'MAD' => array('numeric_code' => 504, 'code' => 'MAD', 'name' => 'Moroccan dirham', 'symbol' => 'Dh', 'fraction_name' => 'Centime', 'decimals' => 2),
            'MDL' => array('numeric_code' => 498, 'code' => 'MDL', 'name' => 'Moldovan leu', 'symbol' => 'L', 'fraction_name' => 'Ban', 'decimals' => 2),
            'MGA' => array('numeric_code' => 969, 'code' => 'MGA', 'name' => 'Malagasy ariary', 'symbol' => 'MGA', 'fraction_name' => 'Iraimbilanja', 'decimals' => 5),
            'MKD' => array('numeric_code' => 807, 'code' => 'MKD', 'name' => 'Macedonian denar', 'symbol' => 'ден', 'fraction_name' => 'Deni', 'decimals' => 2),
            'MMK' => array('numeric_code' => 104, 'code' => 'MMK', 'name' => 'Myanma kyat', 'symbol' => 'K', 'fraction_name' => 'Pya', 'decimals' => 2),
            'MNT' => array('numeric_code' => 496, 'code' => 'MNT', 'name' => 'Mongolian tögrög', 'symbol' => '₮', 'fraction_name' => 'Möngö', 'decimals' => 2),
            'MOP' => array('numeric_code' => 446, 'code' => 'MOP', 'name' => 'Macanese pataca', 'symbol' => 'P', 'fraction_name' => 'Avo', 'decimals' => 2),
            'MRO' => array('numeric_code' => 478, 'code' => 'MRO', 'name' => 'Mauritanian ouguiya', 'symbol' => 'UM', 'fraction_name' => 'Khoums', 'decimals' => 5),
            'MUR' => array('numeric_code' => 480, 'code' => 'MUR', 'name' => 'Mauritian rupee', 'symbol' => '₨', 'fraction_name' => 'Cent', 'decimals' => 2),
            'MVR' => array('numeric_code' => 462, 'code' => 'MVR', 'name' => 'Maldivian rufiyaa', 'symbol' => 'ރ.', 'fraction_name' => 'Laari', 'decimals' => 2),
            'MWK' => array('numeric_code' => 454, 'code' => 'MWK', 'name' => 'Malawian kwacha', 'symbol' => 'MK', 'fraction_name' => 'Tambala', 'decimals' => 2),
            'MXN' => array('numeric_code' => 484, 'code' => 'MXN', 'name' => 'Mexican peso', 'symbol' => '$', 'fraction_name' => 'Centavo', 'decimals' => 2),
            'MYR' => array('numeric_code' => 458, 'code' => 'MYR', 'name' => 'Malaysian ringgit', 'symbol' => 'RM', 'fraction_name' => 'Sen', 'decimals' => 2),
            'MZN' => array('numeric_code' => 943, 'code' => 'MZN', 'name' => 'Mozambican metical', 'symbol' => 'MTn', 'fraction_name' => 'Centavo', 'decimals' => 2),
            'NAD' => array('numeric_code' => 516, 'code' => 'NAD', 'name' => 'Namibian dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'NGN' => array('numeric_code' => 566, 'code' => 'NGN', 'name' => 'Nigerian naira', 'symbol' => '₦', 'fraction_name' => 'Kobo', 'decimals' => 2),
            'NIO' => array('numeric_code' => 558, 'code' => 'NIO', 'name' => 'Nicaraguan córdoba', 'symbol' => 'C$', 'fraction_name' => 'Centavo', 'decimals' => 2),
            'NOK' => array('numeric_code' => 578, 'code' => 'NOK', 'name' => 'Norwegian krone', 'symbol' => 'kr', 'fraction_name' => 'Øre', 'decimals' => 2),
            'NPR' => array('numeric_code' => 524, 'code' => 'NPR', 'name' => 'Nepalese rupee', 'symbol' => '₨', 'fraction_name' => 'Paisa', 'decimals' => 2),
            'NZD' => array('numeric_code' => 554, 'code' => 'NZD', 'name' => 'New Zealand dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'OMR' => array('numeric_code' => 512, 'code' => 'OMR', 'name' => 'Omani rial', 'symbol' => 'ر.ع.', 'fraction_name' => 'Baisa', 'decimals' => 3),
            'PAB' => array('numeric_code' => 590, 'code' => 'PAB', 'name' => 'Panamanian balboa', 'symbol' => 'B/.', 'fraction_name' => 'Centésimo', 'decimals' => 2),
            'PEN' => array('numeric_code' => 604, 'code' => 'PEN', 'name' => 'Peruvian nuevo sol', 'symbol' => 'S/.', 'fraction_name' => 'Céntimo', 'decimals' => 2),
            'PGK' => array('numeric_code' => 598, 'code' => 'PGK', 'name' => 'Papua New Guinean kina', 'symbol' => 'K', 'fraction_name' => 'Toea', 'decimals' => 2),
            'PHP' => array('numeric_code' => 608, 'code' => 'PHP', 'name' => 'Philippine peso', 'symbol' => '₱', 'fraction_name' => 'Centavo', 'decimals' => 2),
            'PKR' => array('numeric_code' => 586, 'code' => 'PKR', 'name' => 'Pakistani rupee', 'symbol' => '₨', 'fraction_name' => 'Paisa', 'decimals' => 2),
            'PLN' => array('numeric_code' => 985, 'code' => 'PLN', 'name' => 'Polish złoty', 'symbol' => 'zł', 'fraction_name' => 'Grosz', 'decimals' => 2),
            'PYG' => array('numeric_code' => 600, 'code' => 'PYG', 'name' => 'Paraguayan guaraní', 'symbol' => '₲', 'fraction_name' => 'Céntimo', 'decimals' => 2),
            'QAR' => array('numeric_code' => 634, 'code' => 'QAR', 'name' => 'Qatari riyal', 'symbol' => 'ر.ق', 'fraction_name' => 'Dirham', 'decimals' => 2),
            'RON' => array('numeric_code' => 946, 'code' => 'RON', 'name' => 'Romanian leu', 'symbol' => 'L', 'fraction_name' => 'Ban', 'decimals' => 2),
            'RSD' => array('numeric_code' => 941, 'code' => 'RSD', 'name' => 'Serbian dinar', 'symbol' => 'дин.', 'fraction_name' => 'Para', 'decimals' => 2),
            'RUB' => array('numeric_code' => 643, 'code' => 'RUB', 'name' => 'Russian ruble', 'symbol' => 'руб.', 'fraction_name' => 'Kopek', 'decimals' => 2),
            'RWF' => array('numeric_code' => 646, 'code' => 'RWF', 'name' => 'Rwandan franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2),
            'SAR' => array('numeric_code' => 682, 'code' => 'SAR', 'name' => 'Saudi riyal', 'symbol' => 'ر.س', 'fraction_name' => 'Hallallah', 'decimals' => 2),
            'SBD' => array('numeric_code' => 90, 'code' => 'SBD', 'name' => 'Solomon Islands dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'SCR' => array('numeric_code' => 690, 'code' => 'SCR', 'name' => 'Seychellois rupee', 'symbol' => '₨', 'fraction_name' => 'Cent', 'decimals' => 2),
            'SDG' => array('numeric_code' => 938, 'code' => 'SDG', 'name' => 'Sudanese pound', 'symbol' => '£', 'fraction_name' => 'Piastre', 'decimals' => 2),
            'SEK' => array('numeric_code' => 752, 'code' => 'SEK', 'name' => 'Swedish krona', 'symbol' => 'kr', 'fraction_name' => 'Öre', 'decimals' => 2),
            'SGD' => array('numeric_code' => 702, 'code' => 'SGD', 'name' => 'Singapore dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'SHP' => array('numeric_code' => 654, 'code' => 'SHP', 'name' => 'Saint Helena pound', 'symbol' => '£', 'fraction_name' => 'Penny', 'decimals' => 2),
            'SLL' => array('numeric_code' => 694, 'code' => 'SLL', 'name' => 'Sierra Leonean leone', 'symbol' => 'Le', 'fraction_name' => 'Cent', 'decimals' => 2),
            'SOS' => array('numeric_code' => 706, 'code' => 'SOS', 'name' => 'Somali shilling', 'symbol' => 'Sh', 'fraction_name' => 'Cent', 'decimals' => 2),
            'SRD' => array('numeric_code' => 968, 'code' => 'SRD', 'name' => 'Surinamese dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'STD' => array('numeric_code' => 678, 'code' => 'STD', 'name' => 'São Tomé and Príncipe dobra', 'symbol' => 'Db', 'fraction_name' => 'Cêntimo', 'decimals' => 2),
            'SVC' => array('numeric_code' => 222, 'code' => 'SVC', 'name' => 'Salvadoran colón', 'symbol' => '₡', 'fraction_name' => 'Centavo', 'decimals' => 2),
            'SYP' => array('numeric_code' => 760, 'code' => 'SYP', 'name' => 'Syrian pound', 'symbol' => '£', 'fraction_name' => 'Piastre', 'decimals' => 2),
            'SZL' => array('numeric_code' => 748, 'code' => 'SZL', 'name' => 'Swazi lilangeni', 'symbol' => 'L', 'fraction_name' => 'Cent', 'decimals' => 2),
            'THB' => array('numeric_code' => 764, 'code' => 'THB', 'name' => 'Thai baht', 'symbol' => '฿', 'fraction_name' => 'Satang', 'decimals' => 2),
            'TJS' => array('numeric_code' => 972, 'code' => 'TJS', 'name' => 'Tajikistani somoni', 'symbol' => 'ЅМ', 'fraction_name' => 'Diram', 'decimals' => 2),
            'TMM' => array('numeric_code' => 0, 'code' => 'TMM', 'name' => 'Turkmenistani manat', 'symbol' => 'm', 'fraction_name' => 'Tennesi', 'decimals' => 2),
            'TND' => array('numeric_code' => 788, 'code' => 'TND', 'name' => 'Tunisian dinar', 'symbol' => 'د.ت', 'fraction_name' => 'Millime', 'decimals' => 3),
            'TOP' => array('numeric_code' => 776, 'code' => 'TOP', 'name' => 'Tongan paʻanga', 'symbol' => 'T$', 'fraction_name' => 'Seniti[J]', 'decimals' => 2),
            'TRY' => array('numeric_code' => 949, 'code' => 'TRY', 'name' => 'Turkish lira', 'symbol' => 'TL', 'fraction_name' => 'Kuruş', 'decimals' => 2),
            'TTD' => array('numeric_code' => 780, 'code' => 'TTD', 'name' => 'Trinidad and Tobago dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'TWD' => array('numeric_code' => 901, 'code' => 'TWD', 'name' => 'New Taiwan dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'TZS' => array('numeric_code' => 834, 'code' => 'TZS', 'name' => 'Tanzanian shilling', 'symbol' => 'Sh', 'fraction_name' => 'Cent', 'decimals' => 2),
            'UAH' => array('numeric_code' => 980, 'code' => 'UAH', 'name' => 'Ukrainian hryvnia', 'symbol' => '₴', 'fraction_name' => 'Kopiyka', 'decimals' => 2),
            'UGX' => array('numeric_code' => 800, 'code' => 'UGX', 'name' => 'Ugandan shilling', 'symbol' => 'Sh', 'fraction_name' => 'Cent', 'decimals' => 2),
            'UYU' => array('numeric_code' => 858, 'code' => 'UYU', 'name' => 'Uruguayan peso', 'symbol' => '$', 'fraction_name' => 'Centésimo', 'decimals' => 2),
            'UZS' => array('numeric_code' => 860, 'code' => 'UZS', 'name' => 'Uzbekistani som', 'symbol' => 'UZS', 'fraction_name' => 'Tiyin', 'decimals' => 2),
            'VEF' => array('numeric_code' => 937, 'code' => 'VEF', 'name' => 'Venezuelan bolívar', 'symbol' => 'Bs F', 'fraction_name' => 'Céntimo', 'decimals' => 2),
            'VND' => array('numeric_code' => 704, 'code' => 'VND', 'name' => 'Vietnamese đồng', 'symbol' => '₫', 'fraction_name' => 'Hào[K]', 'decimals' => 10),
            'VUV' => array('numeric_code' => 548, 'code' => 'VUV', 'name' => 'Vanuatu vatu', 'symbol' => 'Vt', 'fraction_name' => 'None', 'decimals' => NULL),
            'WST' => array('numeric_code' => 882, 'code' => 'WST', 'name' => 'Samoan tala', 'symbol' => 'T', 'fraction_name' => 'Sene', 'decimals' => 2),
            'XAF' => array('numeric_code' => 950, 'code' => 'XAF', 'name' => 'Central African CFA franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2),
            'XCD' => array('numeric_code' => 951, 'code' => 'XCD', 'name' => 'East Caribbean dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
            'XOF' => array('numeric_code' => 952, 'code' => 'XOF', 'name' => 'West African CFA franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2),
            'XPF' => array('numeric_code' => 953, 'code' => 'XPF', 'name' => 'CFP franc', 'symbol' => 'Fr', 'fraction_name' => 'Centime', 'decimals' => 2),
            'YER' => array('numeric_code' => 886, 'code' => 'YER', 'name' => 'Yemeni rial', 'symbol' => '﷼', 'fraction_name' => 'Fils', 'decimals' => 2),
            'ZAR' => array('numeric_code' => 710, 'code' => 'ZAR', 'name' => 'South African rand', 'symbol' => 'R', 'fraction_name' => 'Cent', 'decimals' => 2),
            'ZMK' => array('numeric_code' => 894, 'code' => 'ZMK', 'name' => 'Zambian kwacha', 'symbol' => 'ZK', 'fraction_name' => 'Ngwee', 'decimals' => 2),
            'ZWR' => array('numeric_code' => 0, 'code' => 'ZWR', 'name' => 'Zimbabwean dollar', 'symbol' => '$', 'fraction_name' => 'Cent', 'decimals' => 2),
        );
    }

}
if (!function_exists('cs_get_countries')) {

    function cs_get_countries() {
        $get_countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan",
            "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "British Virgin Islands",
            "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China",
            "Colombia", "Comoros", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Democratic People's Republic of Korea", "Democratic Republic of the Congo", "Denmark", "Djibouti",
            "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "England", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Fiji", "Finland", "France", "French Polynesia",
            "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea Bissau", "Guyana", "Haiti", "Honduras", "Hong Kong",
            "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kosovo", "Kuwait", "Kyrgyzstan",
            "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia",
            "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique",
            "Myanmar(Burma)", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Northern Ireland",
            "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Puerto Rico",
            "Qatar", "Republic of the Congo", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa",
            "San Marino", "Saudi Arabia", "Scotland", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa",
            "South Korea", "Spain", "Sri Lanka", "Sudan", "Suriname", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga",
            "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "US Virgin Islands", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay",
            "Uzbekistan", "Vanuatu", "Vatican", "Venezuela", "Vietnam", "Wales", "Yemen", "Zambia", "Zimbabwe");
        return $get_countries;
    }

}
?>