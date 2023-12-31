<?php
if (!class_exists('facebook_module')) {

    class facebook_module extends WP_Widget {
        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */

        /**
         * @Facebook Module
         *
         *
         */
        public function __construct() {

            parent::__construct(
                    'facebook_module', // Base ID
                    __('CS : Facebook', 'car-rental'), // Name
                    array('classname' => 'facebok_widget', 'description' => esc_html__('Facebook widget like box total customized with theme.', 'car-rental'),) // Args
            );
        }

        /**
         * @Facebook html Form
         *
         *
         */
        function form($instance) {
            $instance = wp_parse_args((array) $instance, array('title' => ''));
            $title = $instance['title'];
            $pageurl = isset($instance['pageurl']) ? esc_attr($instance['pageurl']) : '';
            $showfaces = isset($instance['showfaces']) ? esc_attr($instance['showfaces']) : '';
            $showstream = isset($instance['showstream']) ? esc_attr($instance['showstream']) : '';
            $showheader = isset($instance['showheader']) ? esc_attr($instance['showheader']) : '';
            $fb_bg_color = isset($instance['fb_bg_color']) ? esc_attr($instance['fb_bg_color']) : '';
            $likebox_height = isset($instance['likebox_height']) ? esc_attr($instance['likebox_height']) : '';


            $width = isset($instance['width']) ? esc_attr($instance['width']) : '';
            $hide_cover = isset($instance['hide_cover']) ? esc_attr($instance['hide_cover']) : '';
            $show_posts = isset($instance['show_posts']) ? esc_attr($instance['show_posts']) : '';
            $hide_cta = isset($instance['hide_cta']) ? esc_attr($instance['hide_cta']) : '';
            $small_header = isset($instance['small_header']) ? esc_attr($instance['small_header']) : '';
            $adapt_container_width = isset($instance['adapt_container_width']) ? esc_attr($instance['adapt_container_width']) : '';
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title', 'car-rental'); ?>
                    <input class="upcoming" id="<?php echo esc_attr($this->get_field_id('title')); ?>" size='40' name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('pageurl')); ?>"><?php esc_html_e('Page Url', 'car-rental'); ?> 
                    <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('pageurl')); ?>" size='40' name="<?php echo esc_attr($this->get_field_name('pageurl')); ?>" type="text" value="<?php echo esc_attr($pageurl); ?>" />
                    <br />
                    <small><?php esc_html_e('Please enter your page or User profile url example:L', 'car-rental'); ?> http://www.facebook.com/profilename OR <br />
                        https://www.facebook.com/pages/wxyz/123456789101112 </small><br />
                </label>
            </p>


            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('fb_bg_color')); ?>"><?php esc_html_e('Background Color', 'car-rental'); ?> 
                    <input type="text" name="<?php echo cs_allow_special_char($this->get_field_name('fb_bg_color')); ?>" size='4' id="<?php echo cs_allow_special_char($this->get_field_id('fb_bg_color')); ?>"  value="<?php if (!empty($fb_bg_color)) {
                   echo cs_allow_special_char($fb_bg_color);
               }
               ?>" class="fb_bg_color upcoming"  />
                </label>
            </p>   

            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('width')); ?>"><?php esc_html_e('width', 'car-rental'); ?> 
                    <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('width')); ?>" size='2' name="<?php echo cs_allow_special_char($this->get_field_name('width')); ?>" type="text" value="<?php echo esc_attr($width); ?>" />
                </label>
            </p>

            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('likebox_height')); ?>"><?php esc_html_e('Like Box Height', 'car-rental'); ?> 
                    <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('likebox_height')); ?>" size='2' name="<?php echo cs_allow_special_char($this->get_field_name('likebox_height')); ?>" type="text" value="<?php echo esc_attr($likebox_height); ?>" />
                </label>
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('hide_cover')); ?>"><?php esc_html_e('Hide Cover', 'car-rental'); ?> 
                    <input class="upcoming" id="<?php echo esc_attr($this->get_field_id('hide_cover')); ?>" name="<?php echo esc_attr($this->get_field_name('hide_cover')); ?>" type="checkbox" <?php if (esc_attr($hide_cover) != '') {
                   echo 'checked';
               } ?> />
                </label>
            </p>


            <p>
                <label for="<?php echo esc_attr($this->get_field_id('showfaces')); ?>"><?php esc_html_e('Show Faces', 'car-rental'); ?> 
                    <input class="upcoming" id="<?php echo esc_attr($this->get_field_id('showfaces')); ?>" name="<?php echo esc_attr($this->get_field_name('showfaces')); ?>" type="checkbox" <?php if (esc_attr($showfaces) != '') {
                   echo 'checked';
               } ?> />
                </label>
            </p>


            <p>
                <label for="<?php echo esc_attr($this->get_field_id('show_posts')); ?>"><?php esc_html_e('Show Posts', 'car-rental'); ?> 
                    <input class="upcoming" id="<?php echo esc_attr($this->get_field_id('show_posts')); ?>" name="<?php echo esc_attr($this->get_field_name('show_posts')); ?>" type="checkbox" <?php if (esc_attr($show_posts) != '') {
                   echo 'checked';
               } ?> />
                </label>
            </p>


            <p>
                <label for="<?php echo esc_attr($this->get_field_id('hide_cta')); ?>"><?php esc_html_e('Hide Cta', 'car-rental'); ?> 
                    <input class="upcoming" id="<?php echo esc_attr($this->get_field_id('hide_cta')); ?>" name="<?php echo esc_attr($this->get_field_name('hide_cta')); ?>" type="checkbox" <?php if (esc_attr($hide_cta) != '') {
                   echo 'checked';
               } ?> />
                </label>
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('small_header')); ?>"><?php esc_html_e('Small Header', 'car-rental'); ?> 
                    <input class="upcoming" id="<?php echo esc_attr($this->get_field_id('small_header')); ?>" name="<?php echo esc_attr($this->get_field_name('small_header')); ?>" type="checkbox" <?php if (esc_attr($small_header) != '') {
                echo 'checked';
            } ?> />
                </label>
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('adapt_container_width')); ?>"><?php esc_html_e('Adapt width', 'car-rental'); ?> 
                    <input class="upcoming" id="<?php echo esc_attr($this->get_field_id('adapt_container_width')); ?>" name="<?php echo esc_attr($this->get_field_name('adapt_container_width')); ?>" type="checkbox" <?php if (esc_attr($adapt_container_width) != '') {
                echo 'checked';
            } ?> />
                </label>
            </p>

            <?php
        }

        /**
         * @Facebook Update Form Data
         *
         *
         */
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['pageurl'] = $new_instance['pageurl'];
            $instance['showfaces'] = $new_instance['showfaces'];
            $instance['showstream'] = $new_instance['showstream'];
            $instance['showheader'] = $new_instance['showheader'];
            $instance['fb_bg_color'] = $new_instance['fb_bg_color'];
            $instance['likebox_height'] = $new_instance['likebox_height'];

            $instance['width'] = $new_instance['width'];
            $instance['hide_cover'] = $new_instance['hide_cover'];
            $instance['show_posts'] = $new_instance['show_posts'];
            $instance['hide_cta'] = $new_instance['hide_cta'];
            $instance['small_header'] = $new_instance['small_header'];
            $instance['adapt_container_width'] = $new_instance['adapt_container_width'];


            return $instance;
        }

        /**
         * @Facebook Widget Display
         *
         *
         */
        function widget($args, $instance) {
            extract($args, EXTR_SKIP);
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
            $title = wp_specialchars_decode(stripslashes($title));
            $pageurl = empty($instance['pageurl']) ? ' ' : apply_filters('widget_title', $instance['pageurl']);
            $showfaces = empty($instance['showfaces']) ? ' ' : apply_filters('widget_title', $instance['showfaces']);
            $showstream = empty($instance['showstream']) ? ' ' : apply_filters('widget_title', $instance['showstream']);
            $showheader = empty($instance['showheader']) ? ' ' : apply_filters('widget_title', $instance['showheader']);
            $fb_bg_color = empty($instance['fb_bg_color']) ? ' ' : apply_filters('widget_title', $instance['fb_bg_color']);
            $likebox_height = empty($instance['likebox_height']) ? ' ' : apply_filters('widget_title', $instance['likebox_height']);

            $width = empty($instance['width']) ? ' ' : apply_filters('widget_title', $instance['width']);
            $hide_cover = empty($instance['hide_cover']) ? ' ' : apply_filters('widget_title', $instance['hide_cover']);
            $show_posts = empty($instance['show_posts']) ? ' ' : apply_filters('widget_title', $instance['show_posts']);
            $hide_cta = empty($instance['hide_cta']) ? ' ' : apply_filters('widget_title', $instance['hide_cta']);
            $small_header = empty($instance['small_header']) ? ' ' : apply_filters('widget_title', $instance['small_header']);
            $adapt_container_width = empty($instance['adapt_container_width']) ? ' ' : apply_filters('widget_title', $instance['adapt_container_width']);


            if (isset($showfaces) AND $showfaces == 'on') {
                $showfaces = 'true';
            } else {
                $showfaces = 'false';
            }
            if (isset($showstream) AND $showstream == 'on') {
                $showstream = 'true';
            } else {
                $showstream = 'false';
            }

            if (isset($hide_cover) AND $hide_cover == 'on') {
                $hide_cover = 'true';
            } else {
                $hide_cover = 'false';
            }
            if (isset($show_posts) AND $show_posts == 'on') {
                $show_posts = 'true';
            } else {
                $show_posts = 'false';
            }
            if (isset($hide_cta) AND $hide_cta == 'on') {
                $hide_cta = 'true';
            } else {
                $hide_cta = 'false';
            }
            if (isset($small_header) AND $small_header == 'on') {
                $small_header = 'true';
            } else {
                $small_header = 'false';
            }
            if (isset($adapt_container_width) AND $adapt_container_width == 'on') {
                $adapt_container_width = 'true';
            } else {
                $adapt_container_width = 'false';
            }


            echo cs_allow_special_char($before_widget);
            ?>
            <style scoped>
                .facebookOuter {background-color:<?php echo cs_allow_special_char($fb_bg_color); ?>; width:100%;padding:0;float:left;}
                .facebookInner {float: left; width: 100%;}
                .facebook_module, .fb_iframe_widget > span, .fb_iframe_widget > span > iframe { width: 100% !important;}
                .fb_iframe_widget, .fb-like-box div span iframe { width: 100% !important; float: left;}
            </style>
            <?php
            if (!empty($title) && $title <> ' ') {
                echo cs_allow_special_char($before_title);
                echo cs_allow_special_char($title);
                echo cs_allow_special_char($after_title);
            }
            global $wpdb, $post;
            ?>		

            <div id="fb-root"></div>
            <script>(function (d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id))
                        return;
                    js = d.createElement(s);
                    js.id = id;
                    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>

            <?php
            $output = '';
            $output .= '<div style="background:' . esc_attr($instance['fb_bg_color']) . ';" class="fb-like-box" ';
            $output .= ' data-href="' . esc_url($pageurl) . '"';
            $output .= ' data-width="' . $width . '" ';
            $output .= ' data-height="' . $likebox_height . '" ';
            $output .= ' data-hide-cover="' . $hide_cover . '" ';
            $output .= ' data-show-facepile="' . $showfaces . '" ';
            $output .= ' data-show-posts="' . $show_posts . '">';
            $output .= ' </div>';
            echo cs_allow_special_char($output);

            echo cs_allow_special_char($after_widget);
        }

    }

}
if (function_exists('cs_widget_register')) {
    cs_widget_register('facebook_module');
}
?>