<?php
/*
 *
 * @Shortcode Name : Contact us
 * @retrun
 *
 */
if (!function_exists('cs_contactus_shortcode')) {

    function cs_contactus_shortcode($atts, $content = "") {
        $defaults = array(
            'cs_contactus_section_title' => '',
            'cs_contactus_label' => '',
            'cs_contactus_view' => '',
            'cs_contactus_custom_view' => '',
            'cs_contactus_send' => '',
            'cs_success' => '',
            'cs_error' => '',
            'cs_contact_class' => '',
            'column_size' => '1/1',
        );
        extract(shortcode_atts($defaults, $atts));
        $column_class = cs_custom_column_class($column_size);
        $cs_email_counter = rand(3242343, 324324990);
        $html = '';
        $class = '';
        $section_title = '';
        $cs_contactus_section_content = isset($cs_contactus_section_content) ? $cs_contactus_section_content : '';
        if ($cs_contactus_section_title && trim($cs_contactus_section_title) != '') {
            $section_title = '' . $cs_contactus_section_title . '';
        }

        if (trim($cs_success) && trim($cs_success) != '') {
            $success = $cs_success;
        } else {
            $success = 'Email has been sent Successfully.';
        }

        if (trim($cs_error) && trim($cs_error) != '') {
            $error = $cs_error;
        } else {
            $error = 'An error Occured, please try again later.';
        }

        if (trim($cs_contactus_view) == 'plain') {
            $view_class = 'cs-plan';
        } else {
            $view_class = '';
        }
        ?>
        <script type="text/javascript">
            function cs_contact_frm_submit(form_id) {
                var cs_mail_id = '<?php echo esc_js($cs_email_counter); ?>';
                if (form_id == cs_mail_id) {
                    var $ = jQuery;
                    $("#loading_div<?php echo esc_js($cs_email_counter); ?>").html('<img src="<?php echo esc_js(esc_url(get_template_directory_uri())); ?>/assets/images/ajax-loader.gif" alt="ajax-loader" />');
                    $("#loading_div<?php echo esc_js($cs_email_counter); ?>").show();
                    $("#message<?php echo esc_js($cs_email_counter); ?>").html('');
                    var datastring = $('#frm<?php echo esc_js($cs_email_counter); ?>').serialize() + "&cs_contact_email=<?php echo esc_js($cs_contactus_send); ?>&cs_contact_succ_msg=<?php echo esc_js($success); ?>&cs_contact_error_msg=<?php echo esc_js($error); ?>&action=cs_contact_form_submit";
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
                            }
                            else if (response.type == 'success') {
                                $("#frm<?php echo esc_js($cs_email_counter); ?>").slideUp();
                                $("#loading_div<?php echo esc_js($cs_email_counter); ?>").html('');
                                $("#loading_div<?php echo esc_js($cs_email_counter); ?>").hide();
                                $("#message<?php echo esc_js($cs_email_counter); ?>").addClass('succ_mess');
                                $("#message<?php echo esc_js($cs_email_counter) ?>").show();
                                $("#message<?php echo esc_js($cs_email_counter); ?>").html(response.message);
                            }

                        }
                    }
                    );
                }
            }
        </script>

        <?php
        $html .= '<div id="respond-comment" class="cs-classic-form">';
        $html .= '<div id="respond" class="comment-respond">';
        $html .= '<form  name="frm' . absint($cs_email_counter) . '" id="frm' . absint($cs_email_counter) . '" action="javascript:cs_contact_frm_submit(' . absint($cs_email_counter) . ')"  class="row comment-form">';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-12">';
        $html .= '<div class="cs-text">';
        $html .= '<h4 class="comment-reply-title">' . $section_title . '</h4>';
        $html .= '<span>' . $cs_contactus_section_content . '</span>  ';
        $html .= '</div>';
        $html .= ' </div>';
        $html .= '<p>';
        if (isset($cs_contactus_label) && $cs_contactus_label == 'on') {

            $html .= '<label class="icon-usr">' . esc_html__('Enter Your Name', 'car-rental') . '</label>';
        }
        $html .= '<input type="text" name="contact_name" placeholder="' . esc_html__('Enter Name', 'car-rental') . '"  class="' . sanitize_html_class($class) . ' ' . sanitize_html_class($view_class) . '">';
        $html .= '</p>';
        $html .= '<p>';
        if (isset($cs_contactus_label) && $cs_contactus_label == 'on') {

            $html .= '<label class="icon-envlp">' . esc_html__('Enter Your Email Address', 'car-rental') . '</label>';
        }
        $html .= '<input type="text" name="contact_email" placeholder="' . esc_html__('Email', 'car-rental') . '"  class="' . sanitize_html_class($class) . ' ' . sanitize_html_class($view_class) . '" required>';
        $html .= '</p>';
        $html .= '<p class="subject">';
        if (isset($cs_contactus_label) && $cs_contactus_label == 'on') {

            $html .= '<label class="icon-globe">' . esc_html__('Enter Subject', 'car-rental') . '</label>';
        }
        $html .= '<input type="text" name="subject" placeholder="' . esc_html__(' Subject', 'car-rental') . '"  class="' . sanitize_html_class($class) . ' ' . $view_class . '" required>';
        $html .= '</p>';
        $html .= '<p class="comment-form-comment">';

        if (isset($cs_contactus_label) && $cs_contactus_label == 'on') {

            $html .= '<label>' . esc_html__('Message', 'car-rental') . '</label>';
        }
        $html .= '<label class="icon-qute"><textarea placeholder="' . esc_html__('Message', 'car-rental') . '"  id="comment_mes" name="contact_msg" class="commenttextarea ' . sanitize_html_class($class) . ' ' . $view_class . '" rows="4" cols="39"></textarea></label>';

        $html .= '</p>';
        $html .= '<p class="form-submit"> <input type="submit" name="submit" id="submit_btn' . absint($cs_email_counter) . '"  class="submit-btn form-style-right" value="' . esc_html__('Submit Now', 'car-rental') . '"></p>';
        $html .= '<div id="loading_div' . $cs_email_counter . '"></div>';


        $html .= '</div>';
        $html .= '</form>';
        $html .= '<div id="message' . $cs_email_counter . '"  style="display:none;"></div>';
        $html .= '</div>';
        $html .= '</div>';

        $cs_contact_class_id = '';

        if ($cs_contact_class <> '') {
            $cs_contact_class_id = ' id="' . $cs_contact_class . '"';
        }

        return '<div class="' . $column_class . '"' . $cs_contact_class_id . '>' . $html . '</div>';
    }

    if (function_exists('cs_short_code'))
        cs_short_code(CS_SC_CONTACTUS, 'cs_contactus_shortcode');
}