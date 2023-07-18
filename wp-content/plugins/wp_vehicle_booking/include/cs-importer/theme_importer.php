<?php
add_action('admin_menu', 'cs_theme');
if (!function_exists('cs_theme')) {

    function cs_theme() {
        add_theme_page("Import Demo Data", __("Import Demo Data", 'rental'), 'read', 'cs_demo_importer', 'cs_demo_importer');
    }

}

/**
 *
 * @Import XML File For Theme Demo
 */
if (!function_exists('cs_demo_importer')) {

    function cs_demo_importer() {
        global $cs_page_option, $page;
        $import = get_option('cs_import_demo');

        if (isset($_REQUEST['demo']) && $_REQUEST['demo'] == 'demo-data') {
            if (isset($_POST['demo-theme-data']) and $cs_page_option['theme_options'][$_POST['demo-theme-data']]['page_slug'] <> '') {
                $page = $cs_page_option['theme_options'][$_POST['demo-theme-data']]['page_slug'];
                $theme_option = $cs_page_option['theme_options'][$_POST['demo-theme-data']]['theme_option'];
            } else {
                $page = 'home';
                $theme_option = $cs_page_option['theme_options']['home-v1']['theme_option'];
            }

            require_once ABSPATH . 'wp-admin/includes/import.php';
            if (!defined('WP_LOAD_IMPORTERS'))
                define('WP_LOAD_IMPORTERS', true);
            $cs_demoimport_error = false;
            if (!class_exists('WP_Importer')) {

                $cs_import_class = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
                if (file_exists($cs_import_class)) {
                    require_once $cs_import_class;
                } else {
                    $cs_demoimport_error = true;
                }
            }

            if (!class_exists('WP_Import')) {

                $cs_import_class = wp_car_rental::plugin_dir() . 'include/cs-importer/wordpress-importer.php';
                if (file_exists($cs_import_class))
                    require_once $cs_import_class;
                else
                    $cs_demoimport_error = true;
            }

            if ($cs_demoimport_error) {
                echo __('Error.', 'rental') . '</p>';
                die();
            } else {

                if (!is_file(wp_car_rental::plugin_dir() . 'include/cs-importer/demo.xml')) {
                    echo '<p><strong>' . __('Sorry, there has been an error.', 'rental') . '</strong><br />';
                    echo __('The file does not exist, please try again.', 'rental') . '</p>';
                } else {

                    if (!get_option('cs_import_demo')) {
                        update_option('cs_import_demo', 'success');

                        global $wpdb, $page;

                        // code remove

                        $theme_mod_val = array();
                        $term_exists = term_exists('main-menu', 'nav_menu');
                        if (!$term_exists) {
                            $wpdb->insert(
                                    $wpdb->terms, array(
                                'name' => 'Main Menu',
                                'slug' => 'main-menu',
                                'term_group' => 0
                                    ), array(
                                '%s',
                                '%s',
                                '%d'
                                    )
                            );
                            $insert_id = $wpdb->insert_id;
                            $theme_mod_val['main-menu'] = $insert_id;
                            $wpdb->insert(
                                    $wpdb->term_taxonomy, array(
                                'term_id' => $insert_id,
                                'taxonomy' => 'nav_menu',
                                'description' => '',
                                'parent' => 0,
                                'count' => 0
                                    ), array(
                                '%d',
                                '%s',
                                '%s',
                                '%d',
                                '%d'
                                    )
                            );
                        } else {
                            $theme_mod_val['main-menu'] = $term_exists['term_id'];
                        }
                        $term_exists = term_exists('top-menu', 'nav_menu');
                        if (!$term_exists) {
                            $wpdb->insert(
                                    $wpdb->terms, array(
                                'name' => 'Top Menu',
                                'slug' => 'top-menu',
                                'term_group' => 0
                                    ), array(
                                '%s',
                                '%s',
                                '%d'
                                    )
                            );
                            $insert_id = $wpdb->insert_id;
                            $theme_mod_val['top-menu'] = $insert_id;
                            $wpdb->insert(
                                    $wpdb->term_taxonomy, array(
                                'term_id' => $insert_id,
                                'taxonomy' => 'nav_menu',
                                'description' => '',
                                'parent' => 0,
                                'count' => 0
                                    ), array(
                                '%d',
                                '%s',
                                '%s',
                                '%d',
                                '%d'
                                    )
                            );
                        } else {
                            $theme_mod_val['top-menu'] = $term_exists['term_id'];
                        }
                        set_theme_mod('nav_menu_locations', $theme_mod_val);

                        $cs_demo_import = new WP_Import();
                        $cs_demo_import->fetch_attachments = true;
                        $cs_demo_import->import(wp_car_rental::plugin_dir() . 'include/cs-importer/demo.xml');
                        $page = get_page_by_path($page);

                        cs_update_themeoptions($page, $_POST, $theme_option);

                        if (class_exists('cs_widget_data')) {
                            cs_widget_data::cs_import_widget_data();
                        }
                    } else {
                        $page = get_page_by_path($page);
                        cs_update_themeoptions($page, $_POST, $theme_option);
                        if (class_exists('cs_widget_data')) {
                            cs_widget_data::cs_import_widget_data();
                        }
                        echo '<p><strong>' . __('You have already install this demo.', 'rental') . '</strong><br />';
                    }
                }
            }
        }
        ?>
        <div class="cs-demo-data">
            <h2><?php _e('Import Demo Data', 'rental'); ?></h2>

            <div class="inn-text">
                <p><?php _e('Importing demo data helps to build site like the demo site by all means. It is the quickest way to setup theme. Following things happen when dummy data is imported;', 'rental'); ?></p>
                <ul class="import-data">
                    <li>&#8226;<?php _e('All wordpress settings will remain same and intact', 'rental'); ?></li>
                    <li>&#8226;<?php _e('Posts, pages and dummy images shown in demo will be imported', 'rental'); ?></li>
                    <li>&#8226;<?php _e('Only dummy images will be imported as all demo images have copy right restriction', 'rental'); ?> </li>
                    <li>&#8226;<?php _e('No existing posts, pages, categories, custom post types or any other data will be deleted or modified', 'rental'); ?></li>
                    <li>&#8226;<?php _e('To proceed, please click "Import Demo Data" and wait for a while', 'rental'); ?></li>
                    <li style="color:red;">&#8226; <b><?php _e('Note: Before Import demo data please make sure your server has following Setting', 'rental'); ?></b></li>
                    <li>
                        <ul style="margin-left:15px;">
                            <li>&#8226;<?php _e('post_max_size = 10M or Greater', 'rental'); ?> </li>
                            <li>&#8226;<?php _e('upload_max_filesize = 10M or Greater', 'rental'); ?> </li>
                            <li>&#8226;<?php _e('memory_limit = 128M or Greater', 'rental'); ?></li>
                        </ul>
                    </li>
                    <li>&#8226;<?php _e('Your Current Setting', 'rental'); ?> </b></li>
                    <li>
                        <ul style="margin-left:15px;">
        <?php
        $post_max_size = ini_get('post_max_size');
        $upload_max_filesize = ini_get('upload_max_filesize');
        $memory_limit = ini_get('memory_limit');
        ?>
                            <li style="color:<?php echo ( str_replace('M', '', $post_max_size) > 49 ) ? 'green' : 'red'; ?>"> &#8226; post_max_size = <?php echo esc_attr($post_max_size); ?>.</li>
                            <li style="color:<?php echo ( str_replace('M', '', $upload_max_filesize) > 49 ) ? 'green' : 'red'; ?>">&#8226; upload_max_filesize = <?php echo esc_attr($upload_max_filesize); ?>.</li>
                            <li style="color:<?php echo ( str_replace('M', '', $memory_limit) > 127 ) ? 'green' : 'red'; ?>">&#8226; memory_limit = <?php echo esc_attr($memory_limit); ?>.</li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="cs-import-data" style="float:left; width:100%;">
                <form method="post">
                    <ul class="form-elements noborder" id="other_sliders" style=" padding: 20px 0px 0px 0px; text-align:left ">
                        <li class="to-field importdeta">
                            <div class="meta-input pattern">
        <?php
        $demo_data = get_option('demo-theme-data');
        $cs_counter = 0;
        $cs_uncheck = false;
        if (isset($demo_data) && $demo_data == '') {
            $cs_uncheck = true;
        }

        foreach ($cs_page_option['theme_options']['select'] as $key => $value) {
            $cs_counter++;

            $custom_class = '';
            if ($cs_uncheck && $cs_counter == 1) {
                $checked = 'checked';
                $custom_class = 'check-list';
            } else {
                $checked = ($demo_data == $key) ? 'checked' : '';
                $custom_class = ($demo_data == $key) ? 'check-list' : '';
            }

            echo '<div class="radio-image-wrapper">
								  <input name="demo-theme-data" class="radio" type="radio" 
								  onclick=select_bg("demo-theme-data","","","") value="' . $key . '" ' . $checked . '/>
								  <label for="radio_option_1"> 
									  <span class="ss"><img src="' . esc_url(get_template_directory_uri() . '/include/assets/images/import/' . $cs_page_option['theme_options'][$key]['thumb'] . '.png') . '" /></span> 
									  <span class="' . sanitize_html_class($custom_class) . '" id="check-list">&nbsp;</span>
								  </label>
								  <span class="title-theme">' . $value . '</span>			
							</div>';
        }
        ?>
                            </div>
                        </li>
                    </ul>
                    <input name="reset"  type="submit" value="Import Demo Data" id="submit_btn" class="import-btn" />
                    <input type="hidden" name="demo" value="demo-data" />
                </form>
            </div>
        </div>
        <?php
    }

}
/**
 * @set home page with theme options
 */
if (!function_exists('cs_update_themeoptions')) {

    function cs_update_themeoptions($page = '', $formdata = '', $theme_option = '') {
        if (isset($page->ID)) {

            if (isset($formdata['demo-theme-data'])) {
                update_option('demo-theme-data', $formdata['demo-theme-data']);
            }
            update_option('page_on_front', $page->ID);
            update_option('show_on_front', 'page');
            update_option('front_page_settings', '1');
            $cs_theme_skin = json_decode($theme_option, true);
            update_option("cs_theme_option", $cs_theme_skin);

            echo '<p><strong>' . __('Theme Settings Saved.', 'rental') . '</strong><br />';

            cs_demo_plugin_data();

            echo '<p><strong>' . __('Plugin Settings Saved.', 'rental') . '</strong><br />';
        } else {
            //echo '<div class="updated"> <h4>Page not exist</h4></div>';	
        }
    }

}