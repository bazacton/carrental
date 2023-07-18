<?php
global $cs_page_option;

cs_include_file( ABSPATH . '/wp-admin/includes/file.php' );

// Demo 1
$home_demo = cs_get_demo_content( 'home.json' );

$cs_page_option[] = array();
$cs_page_option['theme_options'] 	= 	array(
					'select'	=>	array(
						'home'				=> 'Home',
 					),
 					'home'	=>	array(
						'name' 			=> 'Home',
						'page_slug' 	=> 'home',
						'theme_option'	=> $home_demo,
						'thumb'			=> 'Import-Dummy-Data'
					),
   			);		
