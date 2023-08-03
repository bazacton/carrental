<?php
	global $excerpt, $category, $cs_plugin_notify,$cs_plugin_options,$cs_vehicle_location_style;
	$cs_plugin_notify = new CS_Plugin_Notification_Helper();
	$width = '202';
	$height = '146';
	$title_limit = 46;
?>
<div class="cs-location-list">
    <div class="col-md-12">
        <ul class="location-listing">
            <?php
            $query = new WP_Query($args);
            $post_count = $query->post_count;

            if ($query->have_posts()) {
                $postCounter = 0;
                while ($query->have_posts()) : $query->the_post();
				  $postCounter++;
				  $cs_location_address = get_post_meta($post->ID, 'cs_location_address', true);
				  $cs_email = get_post_meta($post->ID, 'cs_email', true);
				  $cs_phone_no = get_post_meta($post->ID, 'cs_phone_no', true);
                   
			   ?>
                    <li>
                        <div class="location-list"> <?php echo esc_attr(sprintf("%02d", $postCounter)); ?></div>
                        <div class="loaction-address">
                            <h5><i class="icon-location6"></i><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
                            <p><?php echo esc_html($cs_location_address) ?></p>
                        </div>
                        <div class="location-number"> <i class="icon-phone6"></i>
                        <?php if($cs_phone_no <>'' || $cs_email <> '') { ?>
                            <div class="phone-number"><?php echo esc_html($cs_phone_no)?><span><?php echo sanitize_email($cs_email);?></span></div>
                            <?php } ?>
                        </div>
                    </li>
                    <?php
                endwhile;
				 
            } else {
                $cs_plugin_notify->error(__(' No Vehicle found', 'rental'));
            }
            ?>
        </ul>
    </div>
</div>