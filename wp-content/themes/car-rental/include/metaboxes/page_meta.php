<?php
/**
 * @Add Page Meta Boxe
 * @return
 *
 */
$cs_add_me_bo = 'add' . '_meta_' . 'boxes';

add_action($cs_add_me_bo, 'cs_page_options_add');

function cs_page_options_add() {
    if (function_exists('cs_meta_box')) {
        cs_meta_box('id_page_options', esc_html__('Page Options', 'car-rental'), 'cs_page_options', 'page', 'normal', 'high');
    }
}

function cs_page_options($post) {
    global $post, $cs_theme_options;
    $cs_builtin_seo_fields = isset($cs_theme_options['cs_builtin_seo_fields']) ? $cs_theme_options['cs_builtin_seo_fields'] : '';
    $cs_header_position = isset($cs_theme_options['cs_header_position']) ? $cs_theme_options['cs_header_position'] : '';
    ?>
    <div class="elementhidden">
        <nav class="admin-navigtion">
            <ul id="cs-options-tab">
                <li><a name="#tab-general-settings" href="javascript:;"><i class="icon-gear"></i><?php esc_html_e('General Settings', 'car-rental'); ?> </a></li>
                <li><a name="#tab-slideshow" href="javascript:;"><i class="icon-forward2"></i> <?php esc_html_e('Subheader', 'car-rental'); ?></a></li>
                <?php if ($cs_builtin_seo_fields == 'on') { ?>
                    <li><a name="#tab-seo-advance-settings" href="javascript:;"><i class="icon-globe4"></i><?php esc_html_e('Seo Options', 'car-rental'); ?> </a></li>
                <?php } ?>
            </ul> 
        </nav>
        <div id="tabbed-content">
            <div id="tab-general-settings">
                <?php cs_sidebar_layout_options(); ?>
            </div>
            <div id="tab-slideshow">
                <?php cs_subheader_element(); ?>
            </div>
            <?php if ($cs_builtin_seo_fields == 'on') { ?>
                <div id="tab-seo-advance-settings">
                    <?php cs_seo_settitngs_element(); ?>
                </div>
            <?php } ?>
            <?php if ($cs_header_position == 'absolute') { ?>
                <div id="tab-header-position-settings">                        
                    <?php cs_header_postition_element(); ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php
}

/**
 * @Header Setting for in case of position absolute
 * @return
 *
 */
if (!function_exists('cs_header_postition_element')) {

    function cs_header_postition_element() {
        global $post, $cs_theme_options, $page_option, $cs_metaboxes;

        $header_bg_options = get_post_meta($post->ID, 'cs_header_bg_options', true);

        $cs_rev_slider = $cs_headerbg = 'hide';

        if (isset($header_bg_options) && $header_bg_options == 'cs_rev_slider') {
            $cs_rev_slider = 'show';
        } else if (isset($header_bg_options) && $header_bg_options == 'cs_bg_image_color') {
            $cs_headerbg = 'show';
        }


        $cs_metaboxes->cs_form_select_render(
                array('name' => esc_html__('Header Background', 'car-rental'),
                    'id' => 'header_bg_options',
                    'classes' => '',
                    'std' => 'default_header',
                    'onclick' => 'cs_header_option',
                    'status' => '',
                    'description' => '',
                    'options' => array('none' => 'None', 'cs_rev_slider' => 'Revolution Slider', 'cs_bg_image_color' => 'Bg Image / bg Color'),
                )
        );

        $cs_metaboxes->cs_wrapper_start_render(
                array('name' => esc_html__('Wrapper', 'car-rental'),
                    'id' => 'rev_slider',
                    'status' => $cs_rev_slider,
                )
        );

        $cs_slider_array = array('' => 'Select Slider');

        if (class_exists('RevSlider') && class_exists('cs_RevSlider')) {
            $slider = new cs_RevSlider();
            $arrSliders = $slider->getAllSliderAliases();
            foreach ($arrSliders as $key => $entry) {
                $cs_slider_array[$entry['alias']] = $entry['title'];
            }
        }

        $cs_metaboxes->cs_form_select_render(
                array('name' => esc_html__('Select Slider', 'car-rental'),
                    'id' => 'custom_slider_id',
                    'classes' => '',
                    'std' => 'left',
                    'onclick' => '',
                    'status' => '',
                    'description' => esc_html__("Please select Revolution Slider if already included in package. Otherwise buy Sliders from Code canyon But its optional", "car-rental"),
                    'options' => $cs_slider_array,
                )
        );

        $cs_metaboxes->cs_wrapper_end_render(
                array('name' => esc_html__('Wrapper', 'car-rental'),
                    'id' => 'rev_slider',
                )
        );

        $cs_metaboxes->cs_wrapper_start_render(
                array('name' => esc_html__('Wrapper', 'car-rental'),
                    'id' => 'headerbg_image',
                    'status' => $cs_headerbg,
                )
        );

        $cs_metaboxes->cs_form_fileupload_render(
                array('name' => esc_html__('Background Image', 'car-rental'),
                    'id' => 'headerbg_image',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_metaboxes->cs_form_color_render(
                array('name' => esc_html__('Background Color', 'car-rental'),
                    'id' => 'headerbg_color',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => ''
                )
        );
        $cs_metaboxes->cs_wrapper_end_render(
                array('name' => esc_html__('Wrapper', 'car-rental'),
                    'id' => 'headerbg_image',
                )
        );
    }

}