<?php
/**
 * @Twitter Tweets widget Class
 *
 *
 */
if (!class_exists('cs_twitter_widget')) {

    class cs_twitter_widget extends WP_Widget {
        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */

        /**
         * @init Twitter Module
         *
         *
         */
        public function __construct() {

            parent::__construct(
                    'cs_twitter_widget', // Base ID
                    esc_html__('CS: Twitter Widget', 'car-rental'), // Name
                    array('classname' => 'twitter-widget', 'description' => 'Twitter Widget.',) // Args
            );
        }

        /**
         * @Twitter html form
         *
         *
         */
        function form($instance) {
            $instance = wp_parse_args((array) $instance, array('title' => ''));
            $title = $instance['title'];
            $username = isset($instance['username']) ? esc_attr($instance['username']) : '';
            $numoftweets = isset($instance['numoftweets']) ? esc_attr($instance['numoftweets']) : '';
            ?>
            <label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> <span><?php esc_html_e('Title:', 'car-rental') ?> </span>
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
            <label for="screen_name"><?php esc_html_e('User Name', 'car-rental') ?><span class="required">(*)</span>: </label>
            <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('username')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('username')); ?>" type="text" value="<?php echo esc_attr($username); ?>" />
            <label for="tweet_count">
                <span><?php esc_html_e('Num of Tweets:', 'car-rental') ?> </span>
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('numoftweets')); ?>" size="2" name="<?php echo cs_allow_special_char($this->get_field_name('numoftweets')); ?>" type="text" value="<?php echo esc_attr($numoftweets); ?>" />
                <div class="clear"></div>
            </label>
            <?php
        }

        /**
         * @Twitter update form data 
         *
         *
         */
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['username'] = $new_instance['username'];
            $instance['numoftweets'] = $new_instance['numoftweets'];
            return $instance;
        }

        /**
         * @Display Twitter widget
         *
         *
         */
        //updated function
        function widget($args, $instance) {
            global $cs_theme_options, $cs_twitter_arg;

            $cs_twitter_api_switch = isset($cs_theme_options['cs_twitter_api_switch']) ? $cs_theme_options['cs_twitter_api_switch'] : '';



            $cs_twitter_arg['consumerkey'] = isset($cs_theme_options['cs_consumer_key']) ? $cs_theme_options['cs_consumer_key'] : '';
            $cs_twitter_arg['consumersecret'] = isset($cs_theme_options['cs_consumer_secret']) ? $cs_theme_options['cs_consumer_secret'] : '';
            $cs_twitter_arg['accesstoken'] = isset($cs_theme_options['cs_access_token']) ? $cs_theme_options['cs_access_token'] : '';
            $cs_twitter_arg['accesstokensecret'] = isset($cs_theme_options['cs_access_token_secret']) ? $cs_theme_options['cs_access_token_secret'] : '';
            $cs_cache_limit_time = isset($cs_theme_options['cs_cache_limit_time']) ? $cs_theme_options['cs_cache_limit_time'] : '';
            $cs_tweet_num_from_twitter = isset($cs_theme_options['cs_tweet_num_post']) ? $cs_theme_options['cs_tweet_num_post'] : '';
            $cs_twitter_datetime_formate = isset($cs_theme_options['cs_twitter_datetime_formate']) ? $cs_theme_options['cs_twitter_datetime_formate'] : '';

            if ($cs_cache_limit_time == '') {
                $cs_cache_limit_time = 60;
            }
            if ($cs_twitter_datetime_formate == '') {
                $cs_twitter_datetime_formate = 'time_since';
            }
            if ($cs_tweet_num_from_twitter == '') {
                $cs_tweet_num_from_twitter = 5;
            }
            if ($cs_twitter_api_switch == 'on') {

                extract($args, EXTR_SKIP);
                $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
                $title = wp_specialchars_decode(stripslashes($title));
                $username = $instance['username'];
                $numoftweets = $instance['numoftweets'];
                if ($numoftweets == '') {
                    $numoftweets = 2;
                }
                echo cs_allow_special_char($before_widget);
                // WIDGET display CODE Start
                if (!empty($title) && $title <> ' ') {
                    echo cs_allow_special_char($before_title . $title . $after_title);
                }
                if (strlen($username) > 1) {
                    if ($cs_twitter_arg['consumerkey'] <> '' && $cs_twitter_arg['consumersecret'] <> '' && $cs_twitter_arg['accesstoken'] <> '' && $cs_twitter_arg['accesstokensecret'] <> '') {
                        cs_include_file(wp_car_rental::plugin_dir() . 'include/cs-twitter/display-tweets.php');
                        //require_once get_template_directory() . '/include/theme-components/cs-twitter/display-tweets.php';
                        display_tweets($username, $cs_twitter_datetime_formate, $cs_tweet_num_from_twitter, $numoftweets, $cs_cache_limit_time);
                    } else {
                        echo '<p>' . __('Please Set Twitter API', 'car-rental') . '</p>';
                    }
                }
                echo cs_allow_special_char($after_widget);
            }
        }

    }

}
if (function_exists('cs_widget_register')) {
    cs_widget_register('cs_twitter_widget');
}
?>