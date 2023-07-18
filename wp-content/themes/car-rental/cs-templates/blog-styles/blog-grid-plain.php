<?php
	global $post,$cs_blog_cat,$cs_blog_description,$cs_blog_excerpt,$cs_notification,$wp_query;
	extract( $wp_query->query_vars );
	$width 		  = '360';
	$height	      = '270';
	$title_limit  = 20;      
?>
<div class="cs-blog blog-grid plain">
  <?php 
 	$query = new WP_Query( $args );
	$post_count = $query->post_count;
	if ( $query->have_posts() ) {  
	  $postCounter    = 0;
	  while ( $query->have_posts() )  : $query->the_post();             
	  $thumbnail 	  = cs_get_post_img_src( $post->ID, $width, $height );                
	  $cs_postObject  = get_post_meta(get_the_id(), "cs_full_data", true);
	  $cs_gallery	  = get_post_meta($post->ID,'cs_post_list_gallery', true);
	  $cs_gallery	= explode(',',$cs_gallery);
	  $cs_thumb_view	  = get_post_meta($post->ID,'cs_thumb_view', true);
	  $cs_post_view = isset($cs_thumb_view) ?  $cs_thumb_view : '';

	 ?>
       <div class="col-md-4">
        <article>
       
            <?php if ( $cs_post_view == 'single' ){
              if ( isset( $thumbnail ) && $thumbnail !='' ) {?>
                <div class="cs-media">  <figure> 
                    <a href="<?php esc_url(the_permalink());?>"><img src="<?php echo esc_url( $thumbnail );?>" alt="thumbnail"></a>
                    <figcaption><a href="<?php esc_url(the_permalink());?>"><img src="<?php echo get_template_directory_uri();?>/assets/images/plus-img.png" alt="plus-img"></a></figcaption>
                </figure>  </div>
              <?php }
                   } else if ( $cs_post_view == 'slider' && is_array( $cs_gallery ) and count( $cs_gallery ) > 0 ) {
                      echo ' <div class="cs-media">'; cs_post_flex_slider($width,$height,get_the_id(),'post-list');
					  echo '</div>';
                   } 
                ?>
        
          <section>
              <div class="title">
                <ul class="post-option">
                  <li>
                    <time datetime="<?php echo date('Y-m-d',strtotime(get_the_date()));?>"><?php echo date_i18n(get_option( 'date_format' ),strtotime(get_the_date()));?></time>
                  </li>
                </ul>
                <h5><a href="<?php esc_url(the_permalink());?>"><?php the_title(); ?></a></h5>
              </div>
            <div class="blog-text">
              <?php if ($cs_blog_description == 'yes') {?><p> <?php echo cs_get_the_excerpt($cs_blog_excerpt,'false','Read More');?></p><?php } ?> 
              <ul class="post-options">
                <li><i class="icon-user9"></i><?php echo esc_html__('by','car-rental');?><span style="color:blogdetail.htmla4957e"> <?php echo get_the_author();?> </span>
                <li><i class="icon-chat6"></i><a href="<?php esc_url(comments_link()); ?>"><?php esc_html_e('Comments','car-rental');?>
                    <span> 
                    <?php 
                        $coments	= get_comments_number(esc_html__('0', 'car-rental'),esc_html__('1', 'car-rental'),esc_html__('%', 'car-rental') );
                        printf('(%s)',$coments);?>
                    </span>
                </a></li>
                <li>
              </ul>
            </div>
          </section>
        </article>
      </div>
     <?php 
	    endwhile;
	  } else {
	  	 $cs_notification->error('No blog post found.');
	  }
	 ?>
</div>