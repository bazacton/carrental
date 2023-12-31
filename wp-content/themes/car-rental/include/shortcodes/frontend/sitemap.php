<?php
/**
 * @Spacer html form for page builder
 */
if (!function_exists('cs_sitemap_shortcode')) {

    function cs_sitemap_shortcode($atts, $content = "") {
        global $cs_border;

        $defaults = array('cs_sitemap_section_title' => '');
        extract(shortcode_atts($defaults, $atts));
        
        $cs_sitemap_section_title = $cs_sitemap_section_title ? $cs_sitemap_section_title : '';
     ob_start();
    ?>
        <div class="sitemap-links">	
					  <div class="cs-section-title col-md-12">
					  <h2><?php echo esc_html($cs_sitemap_section_title) ?></h2>
					  </div> 
        <div class="col-md-3">
            <div class="site-maps-links">
                <h6><?php esc_html_e('Pages', 'car-rental'); ?></h6>
                <ul>
                    <?php
                    $args = array(
                        'posts_per_page' => "-1",
                        'post_type' => 'page',
						'order' => 'ASC',
                        'post_status' => 'publish',
                    );
                    $query = new WP_Query($args);
                    $post_count = $query->post_count;
                    if ($query->have_posts()) {
                        while ($query->have_posts()) : $query->the_post();
                            ?>
                            <li><a href="<?php esc_url(the_permalink()); ?>"><?php the_title(); ?></a></li>
                        <?php
                        endwhile;
                    }
                    ?>
                </ul>
            </div>
       
        </div>
        <div class="col-md-3">
            <div class="site-maps-links">
                <h6><?php esc_html_e('Posts', 'car-rental'); ?></h6>
                <ul>
                    <?php
                    $args = array(
                        'posts_per_page' => "-1",
                        'post_type' => 'post',
						'order' => 'ASC',
                        'post_status' => 'publish',
                    );
                    $query = new WP_Query($args);
                    $post_count = $query->post_count;
                    if ($query->have_posts()) {
                        while ($query->have_posts()) : $query->the_post();
                            ?>
                            <li><a href="<?php esc_url(the_permalink()); ?>"><?php echo wp_trim_words(get_the_title(), 3, '...'); ?></a></li>
                        <?php
                        endwhile;
                    }
                    ?>

                </ul>
            </div>	
        </div>
        <div class="col-md-3">
             <div class="site-maps-links">
                <h6><?php esc_html_e('Tags', 'car-rental'); ?></h6>
                <ul>
                    <?php
                    $tags = get_tags(array('order' => 'ASC', 'post_type' => 'post', 'order' => 'DESC'));
                    foreach ((array) $tags as $tag) {
                        ?>
                        <li> <?php echo '<a href="' . esc_url($tag->term_id) . '" rel="tag">' . $tag->name . ' (' . $tag->count . ') </a>'; ?></li>
                        <?php
                    }
                    ?>

                </ul>
            </div>
          </div>
        <div class="col-md-3">
          <div class="site-maps-links">
                <h6><?php esc_html_e('Categories', 'car-rental'); ?></h6>
                <ul>
                    <?php
                    $args = array(
                        'show_option_all' => '',
                        'order' => 'ASC',
						'post_type' => 'post',
                        'order' => 'ASC',
                        'style' => 'list',
                        'title_li' => '',
                         'taxonomy' => 'category'
                    );

                    wp_list_categories($args);
                    ?>

                </ul>
            </div>	
         </div>
         
         
         
             <div class="col-md-3">
            <div class="site-maps-links">
                <h6><?php esc_html_e('Location', 'car-rental'); ?></h6>
                <ul>
                    <?php
                    $args = array(
                        'posts_per_page' => "-1",
                        'post_type' => 'locations',
						'order' => 'ASC',
                        'post_status' => 'publish',
                    );
                    $query = new WP_Query($args);
                    $post_count = $query->post_count;
                    if ($query->have_posts()) {
                        while ($query->have_posts()) : $query->the_post();
                            ?>
                            <li><a href="<?php esc_url(the_permalink()); ?>"><?php the_title(); ?></a></li>
                        <?php
                        endwhile;
                    }
                    ?>
                </ul>
            </div>
       
        </div>
   
        
   
         
        </div> 
        
    <?php
	$cs_sitemap = ob_get_clean();
	return do_shortcode($cs_sitemap);
    }

    if (function_exists('cs_short_code')) cs_short_code(CS_SC_SITEMAP, 'cs_sitemap_shortcode');
}