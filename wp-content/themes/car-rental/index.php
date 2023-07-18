<?php
/**
 * The template for Home page 
 */ 
    get_header();
	global $cs_node,$cs_blog_description,$cs_blog_cat,$cs_theme_options,$cs_counter_node;
    if(isset($cs_theme_options['cs_excerpt_length']) && $cs_theme_options['cs_excerpt_length'] <> ''){ 
        $default_excerpt_length = $cs_theme_options['cs_excerpt_length']; }else{ $default_excerpt_length = '255';
    } 
    $cs_layout     = isset($cs_theme_options['cs_default_page_layout']) ? $cs_theme_options['cs_default_page_layout'] : '';
    if ( isset( $cs_layout ) && ($cs_layout == "sidebar_left" || $cs_layout == "sidebar_right")) {
        $cs_page_layout = "page-content";
     } else {
        $cs_page_layout = "page-content-fullwidth";
     }
    $cs_sidebar    = $cs_theme_options['cs_default_layout_sidebar'];
    $cs_tags_name = 'post_tag';
    $cs_categories_name = 'category';
    ?>   
    
        <section class="page-section" style="padding:0;">
            <!-- Container -->
            <div class="container">
                <!-- Row -->
              <div class="row">     
                <!--Left Sidebar Starts-->
                <?php if ($cs_layout == 'sidebar_left'){ ?>
                    <div class="page-sidebar">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar) ) : ?>
						<?php endif; ?>
                    </div>
                <?php } ?>
                <!--Left Sidebar End-->
                <!-- Page Detail Start -->
                <div class="<?php echo esc_attr($cs_page_layout); ?>">
                 	<div class="cs-blog blog-medium">
                        <div class="col-md-12">
                        <?php 
                            if ( have_posts() ) : 
                                   if (empty($_GET['page_id_all']))
                                     $_GET['page_id_all'] = 1;
                                  if (!isset($_GET["s"])) {
                                     $_GET["s"] = '';
                                  }
                                while ( have_posts() ) : the_post(); 
                                    $width = '300';
                                    $height = '169';
                                    $title_limit = 1000;
                                    $thumbnail = cs_get_post_img_src( $post->ID, $width, $height );
                              ?>                         
                             <article>
                    
                        <?php  
                            if (isset($thumbnail) && $thumbnail != '') {
                                ?>
                                <div class="cs-media">
                                <figure><a href="<?php esc_url(the_permalink()); ?>"><img src="<?php echo esc_url($thumbnail); ?>" alt="thumbnail"></a></figure>
                             </div> <?php
                            }
                        
                        ?>
                  
                    <div class="blog-text">
                        <span class="cs-categroies"><?php cs_get_categories($cs_blog_cat); ?></span>
                        <h3><a href="<?php esc_url(the_permalink()); ?>"><?php the_title();
                        ?></a></h3>
        <?php if ($cs_blog_description == 'yes') { ?>  <p><?php echo cs_get_the_excerpt($default_excerpt_length, 'true', 'Read More'); ?>
                            </p><?php } ?> 
                        <ul class="post-options">
                            <li><i class=" icon-clock-o"></i><time datetime="2011-01-12"><?php echo date_i18n(get_option('date_format'), strtotime(get_the_date())); ?></time></li>
                        </ul>
                    </div>
                </article>
                             
							<?php 
                            endwhile; 
                            wp_reset_postdata();
                        else:
                             if ( function_exists( 'cs_fnc_no_result_found' ) ) { cs_fnc_no_result_found(); }
                        endif; 
                        $qrystr = '';
                            if ( isset($_GET['page_id']) ) $qrystr .= "&page_id=".$_GET['page_id'];
                            if ($wp_query->found_posts > get_option('posts_per_page')) {
                               if ( function_exists( 'cs_pagination' ) ) { echo cs_pagination(wp_count_posts()->publish,get_option('posts_per_page'), $qrystr); } 
                            }
                        ?>
                        </div>
                    </div>
                </div>  
               <?php if ( $cs_layout  == 'sidebar_right'){ ?>
                   <div class="page-sidebar"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar) ) : ?><?php endif; ?></div>
               <?php } ?>    
            </div>
        </div>
      </section>
    <?php get_footer(); ?>