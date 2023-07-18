<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 */
get_header();

global $cs_theme_options;
$cs_sub_footer_social_icons = isset($cs_theme_options['cs_sub_footer_social_icons'])? $cs_theme_options['cs_sub_footer_social_icons'] : ''; ?>
<div class="wrapper">
     <div class="clear"></div>
	<section class="page-section">
    	<div class="container">
        	<div class="row">
                <div class="element-size-100"> 
              <div class="col-md-12">
              <div class="page-not-found">
                <figure><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/404-page.png')?>"></figure>
                <div class="cs-content404">
                  <p><?php esc_html_e("We're sorry, but the page you were looking for doesn't exist.","car-rental");?></p>
                  <div class="cs-button"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Go to Homepage','car-rental');?></a></div>
                </div>
              </div>
            </div>
          </div>
		 </div>
        </div>
    </section>
</div>
<?php get_footer();?>