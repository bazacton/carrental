jQuery(document).ready(function () {
    "use strict";

    jQuery(this).find('.skills-sec').each(function() {
	    	 jQuery(this).find('.skillbar-bar').animate({
                width: jQuery(this).attr('data-percent')
            }, 2000);
	    });


});


jQuery(document).ready(function () {

    if (jQuery('.car-rental').length != '') {
        jQuery('.car-rental').slick({
            slidesToShow: 3,
            dots: false,
            slidesToScroll: 1,
            autoplay: false,
            autoplaySpeed: 2000,
            arrows: true,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: false
                    }
                }, {
                    breakpoint: 700,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                }, {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
    }
});



// Responsive Menu

jQuery(document).ready(function () {
    jQuery('.navigation>ul').slicknav();
});

// Menu Items Limit

var maxItems = 10; // Change Number of Items here
var totalItems = jQuery('.navigation>ul').find('>li').length;
if (totalItems > maxItems) {
    jQuery('.navigation>ul>li:nth-child(' + maxItems + ') ~ li').wrapAll('<li></li>').wrapAll('<ul class="sub-dropdown"></ul>');
    jQuery('.navigation>ul>li:last-child').prepend('<a href="#">More</a>');
}

// Calender

jQuery(document).ready(function ($) {
    if (jQuery('.cs-calendar-combo input').length != '') {
        jQuery('.cs-calendar-combo input').datepicker();
    }
});

// Slider


jQuery(document).ready(function () {
    if (jQuery('.blog-silder').length != '') {
        jQuery('.blog-silder').slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            adaptiveHeight: true
        });
    }
});



// Tooltip

jQuery(document).ready(function () {
    jQuery('[data-toggle="tooltip"]').tooltip();
});

// Counter

jQuery(document).ready(function ($) {
    if (jQuery('.custom-counter').length != '') {
        jQuery('.custom-counter').counterUp({
            delay: 10,
            time: 1000
        });
    }
});

cs_nicescroll();

function cs_nicescroll() {
    'use strict';
    var nice = jQuery(".scroll-content").niceScroll({
        mousescrollstep: "10",
        scrollspeed: "160"
    });
}
function cs_nicescroll2() {
    'use strict';
    var nice = jQuery("body").niceScroll({
        mousescrollstep: "10",
        scrollspeed: "160"
    });
}



function cs_mailchimp_submit(theme_url, counter, admin_url) {
    'use strict';
    $ = jQuery;
    //$('#btn_newsletter_' + counter).hide();
    $('#process_' + counter).html('<div id="process_newsletter_' + counter + '"><i class="icon-refresh icon-spin"></i></div>');
    $.ajax({
        type: 'POST',
        url: admin_url,
        data: $('#mcform_' + counter).serialize() + '&action=cs_mailchimp',
        success: function (response) {
            $('#mcform_' + counter).get(0).reset();
            $('#newsletter_mess_' + counter).fadeIn(600);
            $('#newsletter_mess_' + counter).html(response);
            $('#btn_newsletter_' + counter).fadeIn(600);
            $('#process_' + counter).html('');
        }
    });
}



/* ---------------------------------------------------------------------------
 *  Form validation
 * --------------------------------------------------------------------------- */



function cs_form_validation(form_id) {
    'use strict';
    var name_err_msg = '';
    var email_err_msg = '';
    var subject_err_msg = '';
    var msg_err_msg = '';

    var name_field = jQuery('#frm' + form_id + ' input[name="contact_name"]');
    var email_field = jQuery('#frm' + form_id + ' input[name="contact_email"]');
    var subject_field = jQuery('#frm' + form_id + ' input[name="subject"]');
    var message_field = jQuery('#frm' + form_id + ' textarea[name="contact_msg"]');

    var name = name_field.val();
    var email = email_field.val();
    var subject = subject_field.val();
    var message = message_field.val();
    var email_pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

    var cs_error_form = true;
    if (name == '') {
        name_err_msg = '<p>Please Fill in Name</p>';
        name_field.addClass('cs-error');
        cs_error_form = false;
    } else {
        name_err_msg = '';
        name_field.removeClass('cs-error');
    }
    if (email == '') {
        email_err_msg = "<p>Please Enter Email.</p>";
        email_field.addClass('cs-error');
        cs_error_form = false;
    } else {
        email_err_msg = '';
        email_field.removeClass('cs-error');
    }
    if (email != '') {
        if (!email_pattern.test(email)) {
            email_err_msg = "<p>Please Enter Valid Email.</p>";
            email_field.addClass('cs-error');
            cs_error_form = false;
        } else {
            email_err_msg = '';
            email_field.removeClass('cs-error');
        }
    }
    if (subject == '') {
        subject_err_msg = '<p>Please Fill in Subject</p>';
        subject_field.addClass('cs-error');
        cs_error_form = false;
    } else {
        subject_err_msg = '';
        subject_field.removeClass('cs-error');
    }
    if (message == '') {
        msg_err_msg = '<p>Please Fill in Message</p>';
        message_field.addClass('cs-error');
        cs_error_form = false;
    } else {
        msg_err_msg = '';
        message_field.removeClass('cs-error');
    }
    if (cs_error_form == true) {
        cs_contact_frm_submit(form_id);
    } else {
        // do nothing 
    }
}

/* ---------------------------------------------------------------------------
 * Responsive Video Function
 * --------------------------------------------------------------------------- */

jQuery(document).ready(function ($) {
    "use strict";
    jQuery(".main-section").fitVids();
});

(function (e) {
    "use strict";
    e.fn.fitVids = function (t) {
        var n = {customSelector: null, ignore: null};
        if (!document.getElementById("fit-vids-style")) {
            var r = document.head || document.getElementsByTagName("head")[0];
            var i = ".fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}";
            var s = document.createElement("div");
            s.innerHTML = '<p>x</p><style id="fit-vids-style">' + i + "</style>";
            r.appendChild(s.childNodes[1])
        }
        if (t) {
            e.extend(n, t)
        }
        return this.each(function () {
            var t = ['iframe[src*="player.vimeo.com"]', 'iframe[src*="youtube.com"]', 'iframe[src*="youtube-nocookie.com"]', 'iframe[src*="kickstarter.com"][src*="video.html"]', "object", "embed"];
            if (n.customSelector) {
                t.push(n.customSelector)
            }
            var r = ".fitvidsignore";
            if (n.ignore) {
                r = r + ", " + n.ignore
            }
            var i = e(this).find(t.join(","));
            i = i.not("object object");
            i = i.not(r);
            i.each(function () {
                var t = e(this);
                if (t.parents(r).length > 0) {
                    return
                }
                if (this.tagName.toLowerCase() === "embed" && t.parent("object").length || t.parent(".fluid-width-video-wrapper").length) {
                    return
                }
                if (!t.css("height") && !t.css("width") && (isNaN(t.attr("height")) || isNaN(t.attr("width")))) {
                    t.attr("height", 9);
                    t.attr("width", 16)
                }
                var n = this.tagName.toLowerCase() === "object" || t.attr("height") && !isNaN(parseInt(t.attr("height"), 10)) ? parseInt(t.attr("height"), 10) : t.height(), i = !isNaN(parseInt(t.attr("width"), 10)) ? parseInt(t.attr("width"), 10) : t.width(), s = n / i;
                if (!t.attr("id")) {
                    var o = "fitvid" + Math.floor(Math.random() * 999999);
                    t.attr("id", o)
                }
                t.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top", s * 100 + "%");
                t.removeAttr("height").removeAttr("width")
            })
        })
    }
})(window.jQuery || window.Zepto)

// Custom sidebar plugin function
jQuery('.vehicle-type-wrap').on('click', '.tab-list li', function () {
    var active = jQuery(this).hasClass('active');

    if (!active) {
        jQuery('.vehicle-type-wrap .tab-list li').removeClass('active');
        jQuery(this).addClass('active');
    }

});

/* ---------------------------------------------------------------------------
 * Textarea Focus Function's
 * --------------------------------------------------------------------------- */
jQuery(document).ready(function () {
    "use strict";
    jQuery('input,textarea').focus(function () {
        jQuery(this).data('placeholder', jQuery(this).attr('placeholder'));
        jQuery(this).attr('placeholder', '');
    });
    jQuery('input,textarea').blur(function () {
        jQuery(this).attr('placeholder', jQuery(this).data('placeholder'));
    });
});