<?php
global $post, $cs_blog_cat, $cs_theme_options,$cs_blog_description,$cs_blog_excerpt, $cs_blog_excerpt, $cs_notification, $wp_query;
extract($wp_query->query_vars);
$width = '300';
$height = '169';
?> 
<div class="cs-blog blog-medium">
    <div class="col-md-12">
        <?php
         $query = new WP_Query($args);
        $post_count = $query->post_count;
        if ($query->have_posts()) {
            $postCounter = 0;
            while ($query->have_posts()) : $query->the_post();
                $thumbnail = cs_get_post_img_src($post->ID, $width, $height);
                $cs_postObject = get_post_meta(get_the_id(), "cs_full_data", true);
                $cs_gallery = get_post_meta($post->ID, 'cs_post_list_gallery', true);
                $cs_gallery = explode(',', $cs_gallery);
                $post_audio = $cs_thumb_view = get_post_meta($post->ID, 'cs_post_detail_audio', true);
                $cs_thumb_view = get_post_meta($post->ID, 'cs_thumb_view', true);
                $cs_post_view = isset($cs_thumb_view) ? $cs_thumb_view : '';
                ?>
              <article>
             <?php if ($cs_post_view == 'single') {
                            if (isset($thumbnail) && $thumbnail != '') {
                                ?>
                                 <div class="cs-media">
                                <figure><a href="<?php esc_url(the_permalink()); ?>"><img src="<?php echo esc_url($thumbnail); ?>" alt="thumbnail"></a></figure>
                         </div>  
                          <?php
                            }
                        } else if ($cs_post_view == 'slider' && is_array($cs_gallery) and count($cs_gallery) > 0) {
							
                           echo '<div class="cs-media">';
						    cs_post_flex_slider($width, $height, get_the_id(), 'post-list');
                           echo '</div>';
						   
						   }
                        ?>
                  
                    <div class="blog-text">
                        <span class="cs-categroies"><?php $categories_list = get_the_term_list(get_the_id(), 'category', '', ', ', '');  
								if ($categories_list)
								printf('%1$s', $categories_list); ?></span>
                        <h3><a href="<?php esc_url(the_permalink()); ?>"><?php the_title();
                        ?></a></h3>
       <?php if ($cs_blog_description == 'yes') { ?>  <p><?php echo cs_get_the_excerpt($cs_blog_excerpt, 'true', 'Read More'); ?>
                            </p><?php } ?> 
                        <ul class="post-options">
                            <li><i class=" icon-clock-o"></i><time datetime="2011-01-12"><?php echo date_i18n(get_option('date_format'), strtotime(get_the_date())); ?></time></li>
                        </ul>
                    </div>
                </article>
               
           <?php
            endwhile;
        } else {
            $cs_notification->error('No blog post found.');
        }
        ?>
    </div>
</div>

