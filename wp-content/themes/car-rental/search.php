<?php
/**
 * The template for displaying Search Result
 */
	get_header();
	global  $cs_theme_options, $wp_query; 
 	$default_excerpt_length = isset($cs_theme_options['cs_excerpt_length']) ? $cs_theme_options['cs_excerpt_length'] : '255';
	
	$cs_layout = isset($cs_theme_options['cs_default_page_layout']) ? $cs_theme_options['cs_default_page_layout']:'';
 	if ( isset( $cs_layout ) && ($cs_layout == "sidebar_left" || $cs_layout == "sidebar_right")) {
		$cs_page_layout = "page-content";
 	} else {
		$cs_page_layout = "page-content-fullwidth";
 	}
	$cs_sidebar	= isset($cs_theme_options['cs_default_layout_sidebar']) ? $cs_theme_options['cs_default_layout_sidebar']:'';			
	$cs_tags_name = 'post_tag';
	$cs_categories_name = 'category';
	if(!isset($GET['page_id'])) $GET['page_id_all']=1;
	?>
    <section class="page-section" style=" padding:0;">
        <!-- Container -->
        <div class="container">
            <!-- Row -->
            <div class="row">
 				<?php if ($cs_layout == 'sidebar_left'){ ?>
                     <div class="page-sidebar">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar) ) :  endif; ?>
                     </div>
                <?php } ?>
				<div class="<?php echo esc_attr($cs_page_layout); ?>">
                    <section class="page-section">
                              <div class="cs-result relevent">
                  <div class="search-heading col-md-12">
                  <?php
				if ( have_posts() ) : 
					?>
                   <!-- <h2>< ?php esc_html_e('Showing result for "'.get_search_query().'"','car-rental'); ?></h2>-->
                      <h2><?php printf(esc_html__('Showing result for %s', 'car-rental'), get_search_query()); ?></h2>
                    
                  </div>
                  <?php
				  $width = 255;
				  $height = 191;
                    while ( have_posts() ) : the_post();
					$thumbnail = cs_get_post_img_src($post->ID, $width, $height);
                     if ( is_sticky() ){  echo '<span>'.esc_html__('Featured:', 'car-rental').'</span>';} ?>
                    <article class="col-md-12">
                    <?php if($thumbnail <> '') { ?>
                      <div class="cs-media">
                        <figure> <img src="<?php echo esc_url($thumbnail); ?>" class="img-responsive" alt="blog">
                          <figcaption>
                            <div class="cs-pop"> <a href="<?php esc_url(the_permalink()); ?>"></a> </div>
                          </figcaption>
                        </figure>
                      </div>
                      <?php } ?>
                      <div class="cs-description">
                        <div class="post-option">
                          <h5><a href="<?php esc_url(the_permalink()); ?>"> <?php the_title();?></a></h5>
                        </div>
                        <div class="posted">
                          <ul>
                            <li><a href="<?php esc_url(the_permalink()); ?>"><?php esc_url(the_permalink()); ?></a></li>
                          </ul>
                        </div>
                        <div class="cs-time"><?php echo  date_i18n(get_option( 'date_format' ),strtotime(get_the_date())); ?></div>
                      </div>
                    </article>
                      <?php endwhile;	?>
                  </div>
                    <?php
					else:
					cs_fnc_no_result_found(); 
					endif;				
                	$qrystr = '';
					if ($wp_query->found_posts > get_option('posts_per_page')) {    
						if ( isset($_GET['s']) ) $qrystr = "&amp;s=".$_GET['s'];
						if ( isset($_GET['page_id']) ) $qrystr .= "&amp;page_id=".$_GET['page_id'];
						echo cs_pagination($wp_query->found_posts,get_option('posts_per_page'), $qrystr);
					}
							?>
			        </section>
				
       </div>
           <?php if ( $cs_layout  == 'sidebar_right'){ ?>
                   <div class="page-sidebar">
                       <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar) ) : endif; ?>
                  </div>
                <?php } ?>
       </div>
      </div>
   </section>
<?php 
get_footer();
?>
<!-- Columns End -->