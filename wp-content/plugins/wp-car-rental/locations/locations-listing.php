<?php
/*
* location listing
*/
	global $excerpt, $category, $cs_plugin_notify,$cs_plugin_options,$cs_vehicle_location_style;
	$cs_plugin_notify = new CS_Plugin_Notification_Helper();
	$width = '202';
	$height = '146';
	$title_limit = 46;

 ?>

<div class="cs-location-list simple-list">
  <div class="col-md-12">
    <ul class="location-listing">
    
      <?php
	   $query = new WP_Query($args);
            if ($query->have_posts()) {
                $postCounter = 1;
                while ($query->have_posts()) : $query->the_post();
				 
				  $cs_location_address = get_post_meta($post->ID, 'cs_location_address', true);
				  $cs_email = get_post_meta($post->ID, 'cs_email', true);
				  $cs_phone_no = get_post_meta($post->ID, 'cs_phone_no', true);  
			   ?>
              <li>
                <div class="loaction-address">
                  <h5><a href="<?php the_permalink();?>">
                    <?php the_title();?>
                    </a></h5>
                  <?php if($cs_location_address <> '') { ?>
                  <address>
                  <i class="icon-location6"></i><?php echo esc_html($cs_location_address) ?>
                  </address>
                  <?php } ?>
                </div>
                <div class="location-number">
                  <?php if($cs_phone_no <> '') { ?>
                  <div class="phone-number"><?php echo esc_html($cs_phone_no) ?> <i class=" icon-caret-right"></i></div>
                  <?php } ?>
                </div>
              </li>
              <?php
                endwhile;
				 
            } else {
                $cs_plugin_notify->error(__('No Vehicle found', 'rental'));
            }
            ?>
    </ul>
  </div>
</div>
