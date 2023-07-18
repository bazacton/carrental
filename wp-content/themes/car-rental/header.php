<!DOCTYPE html>
<?php
/**
 * The template for displaying header
 */
global  $cs_options, $cs_theme_options, $cs_node, $cs_xmlObject, $cs_page_option, $post;
 	$cs_site_layout 	= '';
	$global_var_set = 0;
    //$cs_theme_options = get_option('cs_theme_options');
    if( ! get_option('cs_theme_options') ){
            $cs_activation_data = theme_default_options();
            $cs_theme_options = $cs_activation_data;
            $cs_theme_options['cs_default_layout_sidebar'] = 'sidebar-1';
			$cs_theme_options['cs_single_layout_sidebar'] = 'sidebar-1';
            $cs_theme_options['cs_footer_widget'] = 'off';
			$global_var_set = 1;
    }
	
    $cs_builtin_seo_fields =$cs_theme_options['cs_builtin_seo_fields'];
    if(isset($cs_theme_options['cs_layout'])){ $cs_site_layout =$cs_theme_options['cs_layout'];} else { $cs_site_layout == '';}
		$cs_post_id =isset($post->ID) ? $post->ID : '';
		if(isset($cs_post_id) and $cs_post_id <> ''){
			$cs_postObject		= get_post_meta($post->ID,'cs_full_data',true);
		}else{
			$cs_post_id = '';
		}
include get_template_directory() . '/include/theme-components/cs-global-variables.php';
?>
<html <?php language_attributes(); ?>><head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
		<?php
 		$cs_builtin_seo_fields = $cs_theme_options['cs_builtin_seo_fields'];
 		if(isset($cs_builtin_seo_fields) && $cs_builtin_seo_fields == 'on'){
			$cs_seo_title 		= isset($cs_postObject['cs_seo_title']) ? $cs_postObject['cs_seo_title'] : '';
			$cs_seo_description = isset($cs_postObject['cs_seo_description']) ? $cs_postObject['cs_seo_description'] : $cs_theme_options['cs_meta_description'];
			$cs_seo_keywords	= isset($cs_postObject['cs_seo_keywords']) ? $cs_postObject['cs_seo_keywords'] : $cs_theme_options['cs_meta_keywords'];
			?>
            <meta name="title" content="<?php echo esc_attr($cs_seo_title);?>">
            <meta name="keywords" content="<?php echo esc_attr($cs_seo_keywords);?>">
          	<meta name="description" content="<?php echo esc_textarea($cs_seo_description);?>">
            <?php } ?>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php esc_url(bloginfo( 'pingback_url' )); ?>">    
    <?php 
        if(isset($cs_theme_options['cs_custom_css']) and $cs_theme_options['cs_custom_css']<>''){
		$cs_content = $cs_theme_options['cs_custom_css'];
		$content = str_replace(array('&gt;'), '>', $cs_content);
            echo '<style type="text/css">
                '.$content. '
            </style> ';
        }
        if(isset($cs_theme_options['cs_custom_js']) and $cs_theme_options['cs_custom_js']<>''){
       		 echo '<script type="text/javascript">
					 ' . $cs_theme_options['cs_custom_js'] . '
				  </script> ';
        }
        if ( function_exists( 'cs_header_settings' ) ) { cs_header_settings(); }           
		
		$cs_res_cls =(isset($cs_theme_options['cs_responsive']) && $cs_theme_options['cs_responsive'] == "on") ? 'cbp-spmenu-push' :'non-responsive';
        //=====================================================================
        // Header Colors
        //=====================================================================
        if ( function_exists( 'cs_header_color' ) ) { cs_header_color(); }        
        //=====================================================================
        // Theme Colors
        //=====================================================================
        if ( function_exists( 'cs_footer_color' ) ) { cs_footer_color(); }
        if ( function_exists( 'cs_theme_colors' ) ) { cs_theme_colors(); }
        if ( is_singular() && get_option( 'thread_comments' )  && get_comments_number()) {
            wp_enqueue_script( 'comment-reply' );
		}
		
		if (isset($cs_theme_options['cs_sitcky_header_switch']) and $cs_theme_options['cs_sitcky_header_switch'] == "on"){
			$has_sticky = 'has_sticky';
		}else {
			$has_sticky = '';
		}
	wp_head();
    ?>
    </head>
	
    <body <?php body_class($cs_res_cls.' '.$has_sticky); if($cs_site_layout !='full_width'){ echo cs_bg_image(); }?>>
	 <?php if ( function_exists( 'cs_under_construction' ) ) { cs_under_construction(); } ?>
        <!-- Wrapper -->
    <div class="wrapper wrapper_<?php cs_wrapper_class(); ?>">
    	<?php 
			cs_resslide_nav(); 
            if ( function_exists( 'cs_get_headers' ) ) { cs_get_headers(); }
			if ( function_exists( 'cs_below_header_style' ) ) { cs_below_header_style(); }
            if(isset($cs_theme_options['cs_smooth_scroll']) and $cs_theme_options['cs_smooth_scroll'] == 'on'){
           		 cs_scrolltofix();
            ?>            
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    cs_nicescroll2();    
                });
            </script>
            <?php            
            }
            if (isset($cs_theme_options['cs_sitcky_header_switch']) and $cs_theme_options['cs_sitcky_header_switch'] == "on"){
                 cs_scrolltofix();
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function(){
                    jQuery('#header').scrollToFixed();    
                });
            </script>
            <?php }?>
            <div class="clear"></div>
            <!-- Breadcrumb SecTion -->
            <?php //if ( function_exists( 'cs_subheader_style' ) ) { cs_subheader_style(); } ?>
        <!-- Main Content Section -->
        <main id="main-content">
            <!-- Main Section -->
            <div class="main-section">