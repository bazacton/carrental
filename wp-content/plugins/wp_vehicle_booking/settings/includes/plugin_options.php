<?php

// Theme option function
if (!function_exists('cs_settings_options_page')) {

    function cs_settings_options_page() {
        global $cs_setting_options, $cs_plugin_options;
        $obj = new booking_options_fields();
        $return = $obj->cs_fields($cs_setting_options);

        $html = ' 
        <div class="theme-wrap fullwidth">
            <div class="inner">
                <div class="outerwrapp-layer">
                    <div class="loading_div"> <i class="icon-circle-o-notch icon-spin"></i> <br>
                        ' . __('Saving changes...', 'rental') . '
                    </div>
                    <div class="form-msg"> <i class="icon-check-circle-o"></i>
                        <div class="innermsg"></div>
                    </div>
                </div>
                <div class="row">
                    <form id="plugin-options" method="post">
						<div class="col1">
                            <nav class="admin-navigtion">
                                <div class="logo"> <a href="javascript;;" class="logo1"><img src="' . esc_url(wp_car_rental::plugin_url()) . 'assets/images/logo.png" /></a> <a href="#" class="nav-button"><i class="icon-align-justify"></i></a> </div>
                                <ul>
                                    ' . force_balance_tags($return[1], true) . '
                                </ul>
                            </nav>
                        </div>
                        <div class="col2">
                            ' . force_balance_tags($return[0], true) . '
							
                        </div>
                        <div class="clear"></div>
						<div class="footer">
							<input type="button" id="submit_btn" name="submit_btn" class="bottom_btn_save" value="' . __('Save All Settings', 'rental') . '" onclick="javascript:plugin_option_save(\'' . esc_js(admin_url('admin-ajax.php')) . '\');" />
							<input type="hidden" name="action" value="plugin_option_save"  />
							<input type="hidden" id="cs_plugin_url" name="cs_plugin_url" value="' . wp_car_rental::plugin_url() . '"  />
							<input class="bottom_btn_reset" name="reset" type="button" value="' . __('Reset All Options', 'rental') . '" onclick="javascript:cs_rest_plugin_options(\'' . esc_js(admin_url('admin-ajax.php')) . '\');" />
						</div>
                    </form>
                </div>
            </div>
        </div>
        <div class="clear"></div>';

        $html .= '<script type="text/javascript">
			// Sub Menus Show/hide
			jQuery(document).ready(function($) {
                jQuery(".sub-menu").parent("li").addClass("parentIcon");
                $("a.nav-button").click(function() {
                    $(".admin-navigtion").toggleClass("navigation-small");
                });
                
                $("a.nav-button").click(function() {
                    $(".inner").toggleClass("shortnav");
                });
                
                $(".admin-navigtion > ul > li > a").click(function() {
                    var a = $(this).next(\'ul\')
                    $(".admin-navigtion > ul > li > a").not($(this)).removeClass("changeicon")
                    $(".admin-navigtion > ul > li ul").not(a) .slideUp();
                    $(this).next(\'.sub-menu\').slideToggle();
                    $(this).toggleClass(\'changeicon\');
                });
            });
            
            function show_hide(id){
                var link = id.replace("#", "");
                jQuery(\'.horizontal_tab\').fadeOut(0);
                jQuery("#"+link).fadeIn(400);
            }
            
            function toggleDiv(id) { 
                jQuery(\'.col2\').children().hide();
                jQuery(id).show();
                location.hash = id+"-show";
                var link = id.replace("#", "");
                jQuery(\'.categoryitems li\').removeClass(\'active\');
                jQuery(".menuheader.expandable") .removeClass(\'openheader\');
                jQuery(".categoryitems").hide();
                jQuery("."+link).addClass(\'active\');
                jQuery("."+link) .parent("ul").show().prev().addClass("openheader");
            }
            jQuery(document).ready(function() {
                jQuery(".categoryitems").hide();
                jQuery(".categoryitems:first").show();
                jQuery(".menuheader:first").addClass("openheader");
                jQuery(".menuheader").live(\'click\', function(event) {
                    if (jQuery(this).hasClass(\'openheader\')){
                        jQuery(".menuheader").removeClass("openheader");
                        jQuery(this).next().slideUp(200);
                        return false;
                    }
                    jQuery(".menuheader").removeClass("openheader");
                    jQuery(this).addClass("openheader");
                    jQuery(".categoryitems").slideUp(200);
                    jQuery(this).next().slideDown(200); 
                    return false;
                });
                
                var hash = window.location.hash.substring(1);
                var id = hash.split("-show")[0];
                if (id){
                    jQuery(\'.col2\').children().hide();
                    jQuery("#"+id).show();
                    jQuery(\'.categoryitems li\').removeClass(\'active\');
                    jQuery(".menuheader.expandable") .removeClass(\'openheader\');
                    jQuery(".categoryitems").hide();
                    jQuery("."+id).addClass(\'active\');
                    jQuery("."+id) .parent("ul").slideDown(300).prev().addClass("openheader");
                } 
            });
            jQuery(function($) {
                $( "#cs_launch_date" ).datepicker({
                    defaultDate: "+1w",
                    dateFormat: "dd/mm/yy",
                    changeMonth: true,
                    numberOfMonths: 1,
                    onSelect: function( selectedDate ) {
                        $( "#cs_launch_date" ).datepicker();
                    }
                });
            });
        </script>';

        echo force_balance_tags($html, true);
    }

}

add_action('admin_init', 'cs_settings_option');



if (!function_exists('cs_settings_option')) {

    function cs_settings_option() {
        global $cs_setting_options, $booking_menu_name, $cs_plugin_options;

        if (!isset($_SESSION)) {
            session_start();
        }

        $_SESSION['booking_menu_name'] = __('Bookings', 'rental');
        $_SESSION['location_menu_name'] = __('Locations', 'rental');
        $_SESSION['vehicle_menu_name'] = __('Vehicles', 'rental');

        //$cs_plugin_options 		= get_option('cs_plugin_options');
        $on_off_option = array("show" => "on", "hide" => "off");

        $cs_min_days = array();
        for ($days = 1; $days < 11; $days++) {
            $cs_min_days[$days] = "$days day";
        }

        $cs_setting_options[] = array("name" => __("General Options", "rental"),
            "fontawesome" => 'icon-tools3',
            "id" => "tab-general",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );

        $cs_setting_options[] = array(
            "name" => __("Payment Settings", "rental"),
            "fontawesome" => 'icon-credit-card',
            "id" => "tab-payment-settings",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );

        $cs_setting_options[] = array(
            "name" => __("Payment Gateways", "rental"),
            "fontawesome" => 'icon-credit-card',
            "id" => "tab-gateways-settings",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );

        $cs_setting_options[] = array(
            "name" => __("Extras", "rental"),
            "fontawesome" => 'icon-feather2',
            "id" => "tab-extras-settings",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );

        $cs_setting_options[] = array(
            "name" => __("Features", "rental"),
            "fontawesome" => 'icon-feather2',
            "id" => "cs-dynamic-features",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );

        $cs_setting_options[] = array(
            "name" => __("Facilities", "rental"),
            "fontawesome" => 'icon-feather2',
            "id" => "cs-dynamic-properties",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );


        // General Settings
        $cs_setting_options[] = array("name" => __("General Options", "rental"),
            "id" => "tab-general",
            "type" => "sub-heading"
        );

        $cs_setting_options[] = array("name" => __('Booking Settings', 'rental'),
            "id" => "tab-booking-settings",
            "std" => __("Booking Settings", "rental"),
            "type" => "section",
            "options" => ""
        );
        $cs_setting_options[] = array(
            "name" => __('Price Charge', 'rental'),
            "desc" => "",
            "hint_text" => __('Set Price Charging Scenario.', 'rental'),
            "id" => "cs_charge_base",
            "std" => "",
            "type" => "select_values",
            "options" => array('daily' => __('Daily', 'rental'), 'hourly' => __('Hourly', 'rental'))
        );
        $cs_setting_options[] = array(
            "name" => __('Reservation Page', 'rental'),
            "desc" => "",
            "hint_text" => __('Go to Pages and create New Page, Assign User Reservation Template and then choose that New Page here', 'rental'),
            "id" => "cs_reservation",
            "std" => "",
            "type" => "select_dashboard",
            "options" => ''
        );
        $cs_setting_options[] = array("name" => __("Allow Booking", "rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_allow_user_booking",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );


        $cs_setting_options[] = array("name" => __("Advance Deposit", "rental"),
            "desc" => "",
            "hint_text" => __("Allow advance deposit %", "rental"),
            "id" => "cs_advnce_deposit",
            "std" => "50",
            "type" => "text",
        );

        $cs_setting_options[] = array("name" => __("Full Payment", "rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_allow_full_pay",
            "std" => "off",
            "type" => "checkbox",
            "options" => $on_off_option
        );

        $cs_setting_options[] = array("name" => __("Booking Thank you Message", "rental"),
            "id" => "tab-thankyou-options",
            "std" => __("Booking Thank you Message", "rental"),
            "type" => "section",
            "options" => ""
        );

        $cs_setting_options[] = array("name" => __("Title", "rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_thank_title",
            "std" => __("Thank you for booking with us.", "rental"),
            "type" => "text",
        );

        $cs_setting_options[] = array("name" => __("Phone", "rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_confir_phone",
            "std" => "+44 1234 5678",
            "type" => "text",
        );

        $cs_setting_options[] = array("name" => __("Fax", "rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_confir_fax",
            "std" => "+44 1234 5678",
            "type" => "text",
        );

        $cs_setting_options[] = array("name" => __("Email", "rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_confir_email",
            "std" => "example@example.com",
            "type" => "text",
        );

        $cs_setting_options[] = array("name" => __("Message", "rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_thank_msg",
            "std" => '',
            "type" => "textarea",
        );

        $cs_setting_options[] = array("name" => __("Vehicles Settings", "rental"),
            "id" => "tab-vehicle-options",
            "std" => __("Vehicles Settings", "rental"),
            "type" => "section",
            "options" => ""
        );


        $cs_setting_options[] = array("name" => __("Total No of Vehicles", "rental"),
            "desc" => "",
            "hint_text" => __("Add total no of vehicles eg: 100", "rental"),
            "id" => "cs_total_vehicles",
            "std" => "100",
            "type" => "text",
        );


        $cs_setting_options[] = array("name" => __("Language Settings", "rental"),
            "id" => "tab-lang-options",
            "std" => "sssLanguage Settings",
            "type" => "section",
            "options" => ""
        );

        $dir = wp_car_rental::plugin_dir() . '/languages/';
        $cs_plugin_language[''] = __('Select Language File', "rental");
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                    if ($ext == 'mo') {
                        $cs_plugin_language[$file] = $file;
                    }
                }
                closedir($dh);
            }
        }

        $cs_setting_options[] = array("name" => __("Select Plugin Language", "rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_language_file",
            "std" => "30",
            "type" => "select",
            "options" => $cs_plugin_language,
        );

        // Payments Settings
        $cs_setting_options[] = array("name" => __("Payment Settings", "rental"),
            "id" => "tab-payment-settings",
            "type" => "sub-heading"
        );

        $cs_setting_options[] = array("name" => __("General Settings", "rental"),
            "id" => "tab-general-payments",
            "std" => __("General Settings", "rental"),
            "type" => "section",
            "options" => ""
        );

        $cs_setting_options[] = array("name" => __("VAT On/Off", "rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_vat_switch",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );

        $cs_setting_options[] = array("name" => __("VAT(Value Added Tax) in %", "rental"),
            "desc" => "",
            "hint_text" => __("Add Numeric value eg:14", "rental"),
            "id" => "cs_payment_vat",
            "std" => "",
            "type" => "text",
        );

        // Currency Settings
        $cs_setting_options[] = array(
            "name" => __("Currency Settings", "rental"),
            "id" => "tab-currency-settings",
            "std" => __("Currency Settings", "rental"),
            "type" => "section"
        );

        global $gateways;
        $general_settings = new CS_PAYMENTS();
        $cs_settings = $general_settings->cs_general_settings();

        foreach ($cs_settings as $key => $params) {
            $cs_setting_options[] = $params;
        }

        // Payments Gateways
        $cs_setting_options[] = array(
            "name" => __("Gateways Settings", "rental"),
            "id" => "tab-gateways-settings",
            "type" => "sub-heading"
        );

        $cs_gateways_id = CS_FUNCTIONS()->cs_rand_id();

        $cs_setting_options[] = array(
            "type" => "acc_start",
            "rand" => "$cs_gateways_id"
        );

        foreach ($gateways as $key => $value) {
            if (class_exists($key)) {
                $settings = new $key();
                $cs_settings = $settings->settings($cs_gateways_id);
                foreach ($cs_settings as $key => $params) {
                    $cs_setting_options[] = $params;
                }
            }
        }

        $cs_setting_options[] = array(
            "type" => "elem_end",
        );

        // Extras
        $cs_setting_options[] = array(
            "name" => __("Extras Settings", "rental"),
            "id" => "tab-extras-settings",
            "type" => "sub-heading"
        );
        $cs_setting_options[] = array("name" => __("Extras On/Off", "rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_extras_switch",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );

        $cs_setting_options[] = array("name" => __("Extras Settings", "rental"),
            "desc" => "",
            "hint_text" => __("Add/Edit Extras", "rental"),
            "id" => "extras",
            "std" => '',
            "type" => "extras"
        );

        $cs_setting_options[] = array("name" => __("Features", "rental"),
            "id" => "cs-dynamic-features",
            "type" => "sub-heading"
        );
        $cs_setting_options[] = array("name" => __("Features On/Off", "rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_features_switch",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );
        $cs_setting_options[] = array("name" => __("Add Features", "rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_booking_features",
            "std" => "",
            "type" => "features",
            "options" => ""
        );

        // Properties
        $cs_setting_options[] = array("name" => __("Facilities", "rental"),
            "id" => "cs-dynamic-properties",
            "type" => "sub-heading"
        );
        $cs_setting_options[] = array("name" => __("Facilities On/Off", "rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_properties_switch",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );
        $cs_setting_options[] = array("name" => __("Add Facilities", "rental"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_booking_properties",
            "std" => "",
            "type" => "properties",
            "options" => ""
        );

        $cs_setting_options[] = array("name" => __("import & export", 'rental'),
            "fontawesome" => 'icon-database',
            "id" => "tab-import-export-options",
            "std" => "",
            "type" => "main-heading",
            "options" => ""
        );

        $cs_setting_options[] = array("name" => __("import & export", 'rental'),
            "id" => "tab-import-export-options",
            "type" => "sub-heading"
        );

        $cs_setting_options[] = array("name" => __("Backup", "rental"),
            "desc" => "",
            "hint_text" => '',
            "id" => "cs_backup_options",
            "std" => "",
            "type" => "generate_backup"
        );
    }
}

