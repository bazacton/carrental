<?php

/**
 * @ Contact info widget Class
 *
 *
 */
class contactinfo extends WP_Widget {
    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */

    /**
     * @init Contact Info Module
     *
     *
     */
    public function __construct() {

        parent::__construct(
                'contactinfo', // Base ID
                esc_html__('CS : Contact info', 'car-rental'), // Name
                array('classname' => 'widget_text', 'description' => 'Footer Contact Information.',) // Args
        );
    }

    /**
     * @Contact Info html form
     *
     *
     */
    function form($instance) {
        $instance = wp_parse_args((array) $instance);
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $image_url = isset($instance['image_url']) ? esc_attr($instance['image_url']) : '';
        $address = isset($instance['address']) ? esc_attr($instance['address']) : '';
        $phone = isset($instance['phone']) ? esc_attr($instance['phone']) : '';
        $fax = isset($instance['fax']) ? esc_attr($instance['fax']) : '';
        $email = isset($instance['email']) ? esc_attr($instance['email']) : '';
        $link = isset($instance['link']) ? esc_attr($instance['link']) : '';
        $randomID = rand(40, 9999999);
        ?>    
        <div style="margin-top:0px; float:left; width:100%;">
            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> <span>Title: </span>
                    <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
            <ul class="form-elements-widget">
                <li class="to-label" style="margin-top:20px;">
                    <label>Image</label>
                </li>
                <li class="to-field">
                    <input id="form-widget_cs_widget_logo<?php echo absint($randomID) ?>" name="<?php echo cs_allow_special_char($this->get_field_name('image_url')); ?>" type="hidden" class="" value="<?php echo esc_url($image_url); ?>"/>
                    <label class="browse-icon">
                        <input name="form-widget_cs_widget_logo<?php echo absint($randomID) ?>"  type="button" class="uploadMedia left" value="<?php esc_html_e('Browse', 'car-rental'); ?>"/>
                    </label>
                </li>
            </ul>
            <div class="page-wrap"  id="form-widget_cs_widget_logo<?php echo absint($randomID) ?>_box" style="margin-top:10px; margin-bottom:10px; float:left; overflow:hidden; display:<?php echo esc_url($image_url) && trim($image_url) != '' ? 'inline' : 'none'; ?>">
                <div class="gal-active">
                    <div class="dragareamain" style="padding-bottom:0px;">
                        <ul id="gal-sortable" style="margin-bottom:0px;">
                            <li class="ui-state-default" style="margin:6px">
                                <div class="thumb-secs"> <img src="<?php echo esc_url($image_url); ?>"  id="form-widget_cs_widget_logo<?php echo absint($randomID) ?>_img" style="max-height:80px; max-width:180px"  />
                                    <div class="gal-edit-opts"> <a   href="javascript:del_media('form-widget_cs_widget_logo<?php echo absint($randomID) ?>')" class="delete"></a> </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>



        <p style="margin-top:0px; float:left;">
            <label for="<?php echo cs_allow_special_char($this->get_field_id('address')); ?>"><?php esc_html_e('Address', 'car-rental'); ?><br />
                <textarea cols="20" rows="5" id="<?php echo cs_allow_special_char($this->get_field_id('address')); ?>" name="<?php echo cs_allow_special_char($this->get_field_name('address')); ?>" style="width:315px"><?php echo esc_attr($address); ?></textarea>
            </label>
        </p>
        <p style="margin-top:0px; float:left;">
            <label for="<?php echo cs_allow_special_char($this->get_field_id('phone')); ?>"> <?php esc_html_e('Phone', 'car-rental'); ?><br />
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('phone')); ?>" size="40"
                       name="<?php echo cs_allow_special_char($this->get_field_name('phone')); ?>" type="text" value="<?php echo esc_attr($phone); ?>" />
            </label>
        </p>

        <p style="margin-top:0px; float:left;">
            <label for="<?php echo cs_allow_special_char($this->get_field_id('fax')); ?>"><?php esc_html_e('Fax', 'car-rental'); ?><br />
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('fax')); ?>" size="40" 
                       name="<?php echo cs_allow_special_char($this->get_field_name('fax')); ?>" type="text" value="<?php echo esc_attr($fax); ?>" />
            </label>
        </p>

        <p style="margin-top:0px; float:left;">
            <label for="<?php echo cs_allow_special_char($this->get_field_id('email')); ?>"><?php esc_html_e('Email', 'car-rental'); ?> <br />
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('email')); ?>" size="40" 
                       name="<?php echo cs_allow_special_char($this->get_field_name('email')); ?>" type="text" value="<?php echo esc_attr($email); ?>" />
            </label>
        </p>
        <p style="margin-top:0px; float:left;">
            <label for="<?php echo cs_allow_special_char($this->get_field_id('link')); ?>"><?php esc_html_e('Link', 'car-rental'); ?>  <br />
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('Link')); ?>" size="40" 
                       name="<?php echo cs_allow_special_char($this->get_field_name('link')); ?>" type="text" value="<?php echo esc_url($link); ?>" />
            </label>
        </p>
        <?php
    }

    /**
     * @Update Info html form
     *
     *
     */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['image_url'] = $new_instance['image_url'];
        $instance['image_url'] = $new_instance['image_url'];
        $instance['address'] = $new_instance['address'];
        $instance['phone'] = $new_instance['phone'];
        $instance['fax'] = $new_instance['fax'];
        $instance['email'] = $new_instance['email'];
        $instance['link'] = $new_instance['link'];
        return $instance;
    }

    /**
     * @Widget Info html form
     *
     *
     */
    function widget($args, $instance) {
        global $cs_node;
        extract($args, EXTR_SKIP);
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $title = esc_html($title);
        $image_url = empty($instance['image_url']) ? '' : apply_filters('widget_title', $instance['image_url']);
        $address = empty($instance['address']) ? '' : apply_filters('widget_title', $instance['address']);
        $phone = empty($instance['phone']) ? '' : apply_filters('widget_title', $instance['phone']);
        $fax = empty($instance['fax']) ? '' : apply_filters('widget_title', $instance['fax']);
        $email = empty($instance['email']) ? '' : apply_filters('widget_title', $instance['email']);
        $link = empty($instance['link']) ? '' : apply_filters('widget_title', $instance['link']);
        echo cs_allow_special_char($before_widget);
        if (!empty($title) && $title <> ' ') {
            echo cs_allow_special_char($before_title) . $title . $after_title;
        }
        if (isset($image_url) && $image_url != '') {
            echo '<div class="logo"><a href="' . esc_url(home_url('/')) . '"><img src="' . esc_url($image_url) . '" alt="image_url" /></a></div>';
        }
        echo '<ul>';
        if (isset($address) and $address <> '') {
            echo '<li><i class="icon-map5"></i><p>' . do_shortcode(esc_html($address)) . '</p></li>';
        }
        if (isset($phone) and $phone <> '') {
            echo '<li><i class="icon-phone6"></i><p>' . esc_html($phone) . '</p></li>';
        }
        if (isset($fax) and $fax <> '') {
            echo '<li><i class="icon-printer4"></i><p>' . esc_html($fax) . '</p></li>';
        }
        if (isset($email) and $email <> '') {
            echo '<li>
					<i class="icon-envelope4"></i>
					<p><a href="mailto:' . esc_html($email) . '">' . esc_html($email) . '</a></p>
				</li>';
        }

        if (isset($link) and $link <> '') {
            echo '<li>
					<i class="icon-link4"></i>
					<p><a href="' . esc_url($link) . '">' . esc_html($link) . '</a></p>
				</li>';
        }
        echo '</ul>';

        echo cs_allow_special_char($after_widget);
    }

}

if (function_exists('cs_widget_register')) {
    cs_widget_register('contactinfo');
}
?>