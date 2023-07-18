<?php
/**
 * @Contact Form Widget Class
 *
 *
 */
if (!class_exists('cs_contact_msg')) {

    class cs_contact_msg extends WP_Widget {

        /**
         * @init Contact Module
         *
         *
         */
        public function __construct() {

            parent::__construct(
                    'cs_contact_msg', // Base ID
                    esc_html__('CS : Contact Form', 'car-rental'), // Name
                    array('classname' => 'widget_form', 'description' => 'Select contact form to show in widget.',) // Args
            );
        }

        /**
         * @Contact html form
         *
         *
         */
        function form($instance) {
            $instance = wp_parse_args((array) $instance, array('title' => ''));
            $title = $instance['title'];
            $contact_email = isset($instance['contact_email']) ? esc_attr($instance['contact_email']) : '';
            $contact_succ_msg = isset($instance['contact_succ_msg']) ? esc_attr($instance['contact_succ_msg']) : '';
            ?>
            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> <?php esc_html_e('Title:', 'car-rental') ?>
                    <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>

            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('contact_email')); ?>"> <?php esc_html_e('Contact Email:', 'car-rental') ?>
                    <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('contact_email')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('contact_email')); ?>" type="text" value="<?php echo sanitize_email($contact_email); ?>" />
                </label>
            </p>

            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('contact_succ_msg')); ?>"> <?php esc_html_e('Success Message:', 'car-rental') ?>
                    <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('contact_succ_msg')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('contact_succ_msg')); ?>" type="text" value="<?php echo esc_attr($contact_succ_msg); ?>" />
                </label>
            </p>


            <?php
        }

        /**
         * @Contact Update form data
         *
         *
         */
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['contact_email'] = $new_instance['contact_email'];
            $instance['contact_succ_msg'] = $new_instance['contact_succ_msg'];

            return $instance;
        }

        /**
         * @Display Contact widget
         *
         *
         */
        function widget($args, $instance) {
            extract($args, EXTR_SKIP);
            global $wpdb, $post;
            $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
            $contact_email = isset($instance['contact_email']) ? esc_attr($instance['contact_email']) : '';
            $contact_succ_msg = isset($instance['contact_succ_msg']) ? esc_attr($instance['contact_succ_msg']) : '';

            // WIDGET display CODE Start
            echo cs_allow_special_char($before_widget);
            if (strlen($title) <> 1 || strlen($title) <> 0) {
                echo cs_allow_special_char($before_title . $title . $after_title);
            }


            $cs_email_counter = rand(1343, 9999);
            $error = esc_html__('An error Occured, please try again later.', 'car-rental');
            ?>
            <script type="text/javascript">
                function cs_contact_frm_submit(form_id) {
                    var cs_mail_id = '<?php echo esc_js($cs_email_counter); ?>';
                    if (form_id == cs_mail_id) {
                        var $ = jQuery;
                        $("#loading_div<?php echo esc_js($cs_email_counter); ?>").html('<img src="<?php echo esc_js(esc_url(get_template_directory_uri())); ?>/assets/images/ajax-loader.gif" alt="ajax-loader" />');
                        $("#loading_div<?php echo esc_js($cs_email_counter); ?>").show();
                        $("#message<?php echo esc_js($cs_email_counter); ?>").html('');
                        var datastring = $('#frm<?php echo esc_js($cs_email_counter); ?>').serialize() + "&cs_contact_succ_msg=<?php echo esc_js($contact_succ_msg); ?>&cs_contact_error_msg=<?php echo esc_js($error); ?>&action=cs_contact_form_submit";
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo esc_js(esc_url(admin_url('admin-ajax.php'))); ?>',
                            data: datastring,
                            dataType: "json",
                            success: function (response) {

                                if (response.type == 'error') {
                                    $("#loading_div<?php echo esc_js($cs_email_counter); ?>").html('');
                                    $("#loading_div<?php echo esc_js($cs_email_counter); ?>").hide();
                                    $("#message<?php echo esc_js($cs_email_counter); ?>").addClass('error_mess');
                                    $("#message<?php echo esc_js($cs_email_counter); ?>").show();
                                    $("#message<?php echo esc_js($cs_email_counter) ?>").html(response.message);
                                } else if (response.type == 'success') {
                                    $("#frm<?php echo esc_js($cs_email_counter); ?>").slideUp();
                                    $("#loading_div<?php echo esc_js($cs_email_counter); ?>").html('');
                                    $("#loading_div<?php echo esc_js($cs_email_counter); ?>").hide();
                                    $("#message<?php echo esc_js($cs_email_counter); ?>").addClass('succ_mess');
                                    $("#message<?php echo esc_js($cs_email_counter) ?>").show();
                                    $("#message<?php echo esc_js($cs_email_counter); ?>").html(response.message);
                                }
                            }
                        });
                    }
                }
            </script>


            <form id="frm<?php echo absint($cs_email_counter); ?>" name="frm<?php echo absint($cs_email_counter) ?>" method="post" action="javascript:cs_form_validation(<?php echo absint($cs_email_counter) ?>)">
                <ul>
                    <li>
                        <input type="text" placeholder="<?php esc_html_e('Name', 'car-rental') ?>" name="contact_name" value="" >
                    </li>
                    <li>
                        <input type="text" placeholder="<?php esc_html_e('Email', 'car-rental') ?>" name="contact_email" value="">
                    </li>
                    <li>
                        <input type="text" placeholder="<?php esc_html_e('Subject', 'car-rental') ?>" name="subject" value="">
                    </li>
                    <li>
                        <textarea placeholder="<?php esc_html_e('Message', 'car-rental') ?>" name="contact_msg"></textarea>
                    </li>
                    <li>
                        <input type="submit" value="<?php esc_html_e('Send', 'car-rental') ?>" name="submit" id="submit_btn<?php echo absint($cs_email_counter) ?>">
                    </li>
                </ul>
            </form>
            <span id="loading_div<?php echo absint($cs_email_counter) ?>"></span>
            <div id="message<?php echo absint($cs_email_counter); ?>" style="display:none;"></div>
            <?php
            echo cs_allow_special_char($after_widget); // WIDGET display CODE End
        }

    }

}
if (function_exists('cs_widget_register')) {
    cs_widget_register('cs_contact_msg');
}
?>