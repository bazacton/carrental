<?php
/**
 * Vehicle Listing Classic
 *
 */
	global $excerpt,$category,$vehicle_type,$cs_plugin_options;
	$cs_plugin_notify = new CS_Plugin_Notification_Helper();
	$width = '350';
	$height = '263';
	$title_limit  = 46;
	
	
?>

<div class="element-size-100">
  <div class="col-md-12">
    <div class="cs-section-title">
      <h5><?php  _e('Related Vehicles','rental');?></h5>
    </div>
  </div>
</div>
<ul class="car-rental">
  <?php	$query = new WP_Query( $args );
		$post_count = $query->post_count;
		
		if ( $query->have_posts() ) {  
		  $postCounter    = 0;
		  while ( $query->have_posts() )  : $query->the_post();              
			  $thumbnail	  = '';
			  $cs_post_id 	  = $post->ID;
			  $cs_postObject  = get_post_meta(get_the_id(), "cs_array_data", true);
			  $cs_gallery  	  = get_post_meta($post->ID, "cs_vehicle_image_gallery", true);
			  $cs_vehicle_price = get_post_meta($post->ID, "cs_vehicle_price", true);
			  $cs_gallery	  = explode(',',$cs_gallery);
			  
			  if( is_array( $cs_gallery ) && count( $cs_gallery ) > 0 && $cs_gallery[0] !='' ){
				 $thumbnail 	  =  CS_FUNCTIONS()->cs_get_post_img( $cs_gallery[0], $width, $height );
			  }
			  
			  if( $thumbnail == '' ){
				 $thumbnail		  = wp_car_rental::plugin_url().'/assets/images/no-image.png';
			  }          
			  $excerpt_data	= CS_FUNCTIONS()->cs_get_the_excerpt($excerpt,'false','Read More');
              $vehicles_type = isset($cs_type_data[$vehicle_type]['cs_type_name']) ? $cs_type_data[$vehicle_type]['cs_type_name'] : '';
              ?>
              <li class="element-size-33">
                <div class="col-md-12">
                  <article class="rental-product-outer">
                    <figure><img src="<?php echo esc_url($thumbnail);?>" alt=""/></figure>
                    <div class="rental-prduct-detail">
                      <?php if( $vehicles_type <> '') { ?>
                      <span class="cs-categroies"><?php echo esc_attr( $vehicles_type ); ?></span>
                      <?php } ?>
                      <h6>
                        <?php the_title(); ?>
                      </h6>
                      <?php 
                            $featureList = get_post_meta($cs_post_id, 'cs_vehicle_features', true);
                            $cs_feature_options = isset($cs_plugin_options['cs_feats_options']) ? $cs_plugin_options['cs_feats_options'] : '';
                            $cs_output = '';
                            if( is_array($cs_feature_options) && sizeof($cs_feature_options) > 0 ) {
                                $counter	= 0;
                                echo '<ul>';
                                foreach($cs_feature_options as $feature){
                                    $feature_title 	 = $feature['cs_feats_title'];
                                    $feature_image 	 = $feature['cs_feats_image'];
                                    $feature_slug 	 = isset($feature['feats_id']) ? $feature['feats_id'] : '';
                                    $checked		 = '';
                                    $cs_image		 = '';
									
									if ( function_exists('icl_t') ) {
										$feature_title = icl_t('Vehicle Features', 'Feature "' . $feature_title . '" - Title field');
									}
									
                                    if( isset( $feature_image ) && $feature_image !='' ){
                                        $cs_image	= '<img src="'.esc_url( $feature_image ).'" alt="" />';
                                    }else{
                                        $cs_image	= '<i>&nbsp;</i>';
                                    }
                                    if ( is_array( $featureList ) && in_array( $feature_slug , $featureList )  ) {
                                        $counter++;
                                        if( $counter < 4  ) {
                                            echo '<li><a href="javascript:;">'.$cs_image.wp_trim_words( $feature_title, 3 ).'</a></li>';
                                            if( $counter  == 4  ) {
                                                echo ' <li><a href="'.esc_url(get_the_permalink($cs_post_id)).'"></a></li>';
                                            }
                                        }
                                    }
                                }
                                echo '</ul>';
                            }
                        ?>
                    </div>
                    <div class="rental-prduct-price"> <span>
                      <?php _e('Price','rental');
                                      $currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '';
                                  ?>
                      <em class="rental-sprice"> <?php echo esc_html($currency_sign . $cs_vehicle_price); ?> </em> </span> </div>
                  </article>
                </div>
              </li>
              <?php
				  endwhile;
			} else{
				$cs_plugin_notify->error(__('No Vehicles found','rental'));
			}
			?>
</ul>
