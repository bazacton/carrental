<?php
/**
 * @Add Meta Box For Post
 * @return
 *
 */
$cs_add_me_bo = 'add' . '_meta_' . 'boxes';

add_action($cs_add_me_bo, 'cs_meta_post_add');

function cs_meta_post_add() {
    if (  function_exists( 'cs_meta_box' ) ) {
    cs_meta_box('cs_meta_post', esc_html__('Post Options', 'car-rental'), 'cs_meta_post', 'post', 'normal', 'high');
    
    }
}

function cs_meta_post($post) {
    global $cs_theme_options;
    $cs_builtin_seo_fields = isset($cs_theme_options['cs_builtin_seo_fields']) ? $cs_theme_options['cs_builtin_seo_fields'] : '';
    ?>
    <div class="page-wrap page-opts left" style="overflow:hidden; position:relative; height: 1432px;">
        <div class="option-sec" style="margin-bottom:0;">
            <div class="opt-conts">
                <div class="elementhidden">
                    <nav class="admin-navigtion">
                        <ul id="cs-options-tab">
                            <li><a name="#tab-general-settings" href="javascript:;"><i class="icon-toggle-right"></i><?php esc_html_e('General Settings', 'car-rental'); ?></a></li>
                            <li><a name="#tab-subheader-options" href="javascript:;"><i class="icon-list-alt"></i><?php esc_html_e('Sub Header Options', 'car-rental'); ?>  </a></li>
                            <li><a name="#tab-seo-advance-settings" href="javascript:;"><i class="icon-dribbble"></i><?php esc_html_e('Seo Options', 'car-rental'); ?> </a></li>
                            <li><a name="#tab-post-options" href="javascript:;"><i class="icon-list-alt"></i><?php esc_html_e('Post Settings', 'car-rental'); ?>  </a></li>
                        </ul>
                    </nav>
                    <div id="tabbed-content">
                        <div id="tab-general-settings">
                            <?php cs_general_settings_element(); ?>
                            <?php //cs_sidebar_layout_options(); ?>
                        </div>
                        <div id="tab-subheader-options">
                            <?php cs_subheader_element(); ?>
                        </div>
                        <div id="tab-seo-advance-settings">
                            <?php cs_seo_settitngs_element(); ?>
                        </div>
                        <div id="tab-post-options">
                            <?php
                            if (function_exists('cs_post_options')) {
                                cs_post_options();
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <?php
}

/**
 * @Slider options
 * @return html
 *
 */
if (!function_exists('cs_post_options')) {

    function cs_post_options() {
        global $post, $cs_metaboxes;

        # Show hide post thumnail
        $thumb_view = get_post_meta($post->ID, 'cs_thumb_view', true);
        $post_thumb_image = $post_thumb_slider = 'hide';

        if (isset($thumb_view) && $thumb_view == 'single') {
            $post_thumb_image = 'show';
        } else if (isset($thumb_view) && $thumb_view == 'slider') {
            $post_thumb_slider = 'show';
        }


        # Show hide post detail views
        $detail_view = get_post_meta($post->ID, 'cs_detail_view', true);
        $detail_image = $detail_slider = $detail_audio = $detail_video = 'hide';

        if (isset($detail_view) && $detail_view == 'single') {
            $detail_image = 'show';
        } else if (isset($detail_view) && $detail_view == 'slider') {
            $detail_slider = 'show';
        } else if (isset($detail_view) && $detail_view == 'audio') {
            $detail_audio = 'show';
        } else if (isset($detail_view) && $detail_view == 'video') {
            $detail_video = 'show';
        }

        $cs_metaboxes->cs_form_select_render(
                array('name' => esc_html__('Thumbnail View', 'car-rental'),
                    'id' => 'thumb_view',
                    'classes' => '',
                    'std' => 'single',
                    'onclick' => 'cs_thumbnail_view',
                    'status' => '',
                    'description' => '',
                    'options' => array('none' => esc_html__('None', 'car-rental'), 'single' => esc_html__('Single Image', 'car-rental'), 'slider' => esc_html__('Slider', 'car-rental')),
                )
        );

        $cs_metaboxes->cs_wrapper_start_render(
                array('name' => esc_html__('Wrapper', 'car-rental'),
                    'id' => 'post_thumb_image',
                    'status' => $post_thumb_image,
                )
        );

        $cs_metaboxes->cs_information_box(
                array('name' => esc_html__('Information Box', 'car-rental'),
                    'id' => 'information_box',
                    'classes' => '',
                    'description' => esc_html__('Use Featured Image as Thumbnail', 'car-rental'),
                )
        );

        $cs_metaboxes->cs_wrapper_end_render(
                array('name' => esc_html__('Wrapper', 'car-rental'),
                    'id' => 'post_thumb_image',
                )
        );

        $cs_metaboxes->cs_wrapper_start_render(
                array('name' => esc_html__('Wrapper', 'car-rental'),
                    'id' => 'thumb_slider',
                    'status' => $post_thumb_slider,
                )
        );

        $cs_metaboxes->cs_gallery_render(
                array('name' => esc_html__('Add Gallery Images', 'car-rental'),
                    'id' => 'post_list_gallery',
                    'classes' => '',
                    'std' => 'gallery_meta_form',
                )
        );

        $cs_metaboxes->cs_wrapper_end_render(
                array('name' => esc_html__('Wrapper', 'car-rental'),
                    'id' => 'thumb_slider',
                )
        );

        $cs_metaboxes->cs_form_select_render(
                array('name' => esc_html__('Inside Post Thumbnail View', 'car-rental'),
                    'id' => 'detail_view',
                    'classes' => '',
                    'std' => 'single',
                    'onclick' => 'cs_post_view',
                    'status' => '',
                    'description' => '',
                    'options' => array('none' => esc_html__('None', 'car-rental'), 'single' => esc_html__('Single Image', 'car-rental'), 'slider' => esc_html__('Slider', 'car-rental'), 'audio' => esc_html__('Audio', 'car-rental'), 'video' => esc_html__('Video', 'car-rental')),
                )
        );

        # Image View
        $cs_metaboxes->cs_wrapper_start_render(
                array('name' => esc_html__('Wrapper', 'car-rental'),
                    'id' => 'post_detail',
                    'status' => $detail_image,
                )
        );

        $cs_metaboxes->cs_information_box(
                array('name' => esc_html__('Information Box', 'car-rental'),
                    'id' => 'information_box',
                    'classes' => '',
                    'description' => esc_html__('Use Featured Image as Thumbnail', 'car-rental'),
                )
        );

        $cs_metaboxes->cs_wrapper_end_render(
                array('name' => esc_html__('Wrapper', 'car-rental'),
                    'id' => 'post_detail',
                )
        );

        #Slider View
        $cs_metaboxes->cs_wrapper_start_render(
                array('name' => esc_html__('Wrapper', 'car-rental'),
                    'id' => 'post_detail_slider',
                    'status' => $detail_slider,
                )
        );

        $cs_metaboxes->cs_gallery_render(
                array('name' => esc_html__('Add Gallery Images', 'car-rental'),
                    'id' => 'post_detail_gallery',
                    'classes' => '',
                    'std' => 'gallery_slider_meta_form',
                )
        );

        $cs_metaboxes->cs_wrapper_end_render(
                array('name' => esc_html__('Wrapper', 'car-rental'),
                    'id' => 'post_detail_slider',
                )
        );

        #Audio View
        $cs_metaboxes->cs_wrapper_start_render(
                array('name' => esc_html__('Wrapper', 'car-rental'),
                    'id' => 'audio_view',
                    'status' => $detail_audio,
                )
        );

        $cs_metaboxes->cs_media_url(
                array('name' => esc_html__('Audio Url', 'car-rental'),
                    'id' => 'post_detail_audio',
                    'classes' => '',
                    'std' => '',
                    'description' => esc_html__('Enter Specific Audio Url', 'car-rental'),
                    'hint' => ''
                )
        );

        $cs_metaboxes->cs_wrapper_end_render(
                array('name' => esc_html__('Wrapper', 'car-rental'),
                    'id' => 'audio_view',
                )
        );

        #Video View
        $cs_metaboxes->cs_wrapper_start_render(
                array('name' => esc_html__('Wrapper', 'car-rental'),
                    'id' => 'video_view',
                    'status' => $detail_video,
                )
        );

        $cs_metaboxes->cs_media_url(
                array('name' => esc_html__('Thumbnail Video Url', 'car-rental'),
                    'id' => 'post_detail_video',
                    'classes' => '',
                    'std' => '',
                    'description' => esc_html__('Enter Specific Video Url (Youtube, Vimeo and Dailymotion) OR you can select it from your media library', 'car-rental'),
                    'hint' => ''
                )
        );

        $cs_metaboxes->cs_wrapper_end_render(
                array('name' => esc_html__('Wrapper', 'car-rental'),
                    'id' => 'video_view',
                )
        );
    }

}

/**
 * @page/post General Settings Function
 * @return
 *
 */
if (!function_exists('cs_general_settings_element')) {

    function cs_general_settings_element() {
        global $cs_xmlObject, $post, $cs_metaboxes;
        $cs_metaboxes->cs_form_checkbox_render(
                array('name' => esc_html__('Social Sharing', 'car-rental'),
                    'id' => 'post_social_sharing',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => '',
                )
        );

        $cs_metaboxes->cs_form_checkbox_render(
                array('name' => esc_html__('Tags', 'car-rental'),
                    'id' => 'post_tags_show',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => '',
                )
        );

        $cs_metaboxes->cs_form_checkbox_with_field_render(
                array('name' => esc_html__('Related Posts', 'car-rental'),
                    'id' => 'related_post',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => '',
                    'field' => array('field_name' => esc_html__('related_post', 'car-rental'),
                        'field_id' => 'related_post_title',
                        'field_std' => esc_html__('Related Post', 'car-rental'),
                    )
                )
        );

        $cs_metaboxes->cs_form_checkbox_render(
                array('name' => esc_html__('Next Previous', 'car-rental'),
                    'id' => 'post_pagination_show',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => '',
                )
        );
    }

}