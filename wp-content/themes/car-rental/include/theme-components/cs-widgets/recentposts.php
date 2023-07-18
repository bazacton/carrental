<?php
/**
 * @Recent posts widget Class
 *
 *
 */
if (!class_exists('recentposts')) {

    class recentposts extends WP_Widget {

        /**
         * @init Recent posts Module
         *
         *
         */
        public function __construct() {

            parent::__construct(
                    'recentposts', // Base ID
                    esc_html__('CS: Mail Chimp', 'car-rental'), // Name
                    array('classname' => 'recentblog_post', 'description' => 'Recent Posts from category',) // Args
            );
        }

        /**
         * @Recent posts html form
         *
         *
         */
        function form($instance) {
            $instance = wp_parse_args((array) $instance, array('title' => ''));
            $title = $instance['title'];
            $select_category = isset($instance['select_category']) ? esc_attr($instance['select_category']) : '';
            $showcount = isset($instance['showcount']) ? esc_attr($instance['showcount']) : '';
            ?>
            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> <?php esc_html_e('Title:', 'car-rental') ?>
                    <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('select_category')); ?>"> <?php esc_html_e('Select Category:', 'car-rental') ?>
                    <select id="<?php echo cs_allow_special_char($this->get_field_id('select_category')); ?>" name="<?php echo cs_allow_special_char($this->get_field_name('select_category')); ?>" style="width:225px">
                        <option value="" ><?php esc_html_e('All', 'car-rental') ?></option>
                        <?php
                        $categories = get_categories();
                        if ($categories <> "") {
                            foreach ($categories as $category) {
                                ?>
                                <option <?php if ($select_category == $category->slug) {
                        echo 'selected';
                    } ?> value="<?php echo cs_allow_special_char($category->slug); ?>" ><?php echo cs_allow_special_char($category->name); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </label>
            </p>
            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('showcount')); ?>"> <?php esc_html_e('Number of Posts To Display:', 'car-rental') ?>
                    <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('showcount')); ?>" size='2' name="<?php echo cs_allow_special_char($this->get_field_name('showcount')); ?>" type="text" value="<?php echo esc_attr($showcount); ?>" />
                </label>
            </p>
            <?php
        }

        /**
         * @Recent posts update form data
         *
         *
         */
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['select_category'] = $new_instance['select_category'];
            $instance['showcount'] = $new_instance['showcount'];
            return $instance;
        }

        /**
         * @Display Recent posts widget
         *
         *
         */
        function widget($args, $instance) {
            global $cs_node;
            extract($args, EXTR_SKIP);
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
            $title = esc_html($title);
            $select_category = empty($instance['select_category']) ? ' ' : apply_filters('widget_title', $instance['select_category']);
            $showcount = empty($instance['showcount']) ? ' ' : apply_filters('widget_title', $instance['showcount']);
            if ($instance['showcount'] == "") {
                $instance['showcount'] = '-1';
            }
            echo cs_allow_special_char($before_widget);
            if (!empty($title) && $title <> ' ') {
                echo cs_allow_special_char($before_title);
                echo cs_allow_special_char($title);
                echo cs_allow_special_char($after_title);
            }
            global $wpdb, $post;
            ?>
            <?php
            /**
             * @Display Recent posts
             *
             */
            if (isset($select_category) and $select_category <> ' ' and $select_category <> '') {
                $args = array('posts_per_page' => "$showcount", 'post_type' => 'post', 'category_name' => "$select_category");
            } else {
                $args = array('posts_per_page' => "$showcount", 'post_type' => 'post');
            }
            $title_limit = 6;
            $custom_query = new WP_Query($args);
            if ($custom_query->have_posts() <> "") {
                while ($custom_query->have_posts()) : $custom_query->the_post();
                    $cs_post_id = get_the_ID();
                    $post_xml = get_post_meta($post->ID, "post", true);
                    $cs_xmlObject = new stdClass();
                    $cs_noimage = '';
                    if ($post_xml <> "") {
                        $cs_xmlObject = new SimpleXMLElement($post_xml);
                    }//43                  
                    $cs_noimage = '';
                    $width = 150;
                    $height = 150;
                    $image_id = get_post_thumbnail_id($post->ID);
                    $image_url = cs_get_post_img_src($post->ID, $width, $height);
                    if ($image_url == '') {
                        $cs_noimage = ' class="cs-noimage"';
                    }
                    ?>
                    <ul>
                        <li<?php echo cs_allow_special_char($cs_noimage) ?>>
                    <?php if ($image_url <> '') { ?>
                                <figure><a href="<?php esc_url(the_permalink()); ?>"><img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title(); ?>"></a></figure>
                    <?php } ?>
                            <div class="text">
                                <h6><a href="<?php esc_url(the_permalink()); ?>"><?php echo wp_trim_words(get_the_title($cs_post_id), $title_limit, '...'); ?></a></h6>
                                <time datetime="<?php echo date_i18n('Y-m-d', strtotime(get_the_date())); ?>"><?php echo date_i18n(get_option('date_format'), strtotime(get_the_date())); ?></time>
                            </div>
                        </li>                   
                    </ul>                    
                    <?php
                endwhile;
            } else {
                if (function_exists('cs_fnc_no_result_found')) {
                    cs_fnc_no_result_found(false);
                }
            }
            echo cs_allow_special_char($after_widget);
        }

    }

}
if (function_exists('cs_widget_register')) {
    cs_widget_register('recentposts');
}
?>