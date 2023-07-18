<?php
/**
 * @Slider options
 * @return html
 *
 */
if ( ! function_exists( 'cs_subheader_element' ) ) {
	function cs_subheader_element(){
		global $cs_xmlObject, $post,$cs_metaboxes;
		$page_subheader_no_image = '';
		$cs_default_map	= '[cs_map column_size="1/1" map_height="250" map_lat="51.5072" map_lon="0.1275" map_zoom="11" map_type="ROADMAP" map_info="Info Window Text here." map_info_width="250" map_info_height="100" map_marker_icon1xis="Browse" map_show_marker="true" map_controls="false" map_draggable="true" map_scrollwheel="true" map_border="yes" cs_map_style="style-1"]';
		
			$cs_banner_style = get_post_meta($post->ID, 'cs_header_banner_style', true);
			
			$cs_default_header = $cs_breadcrumb_header = $cs_custom_slider = $cs_map = $cs_no_header = 'hide';
			if( isset( $cs_banner_style ) && $cs_banner_style == 'default_header' ){
				$cs_default_header	= 'hide';
			} else if( isset( $cs_banner_style ) && $cs_banner_style == 'breadcrumb_header' ){
				$cs_breadcrumb_header	= 'show';
				$cs_default_header	= 'show';
			} else if( isset( $cs_banner_style ) && $cs_banner_style == 'custom_slider' ){
				$cs_custom_slider	= 'show';
			} else if( isset( $cs_banner_style ) && $cs_banner_style == 'map' ){
				$cs_map	= 'show';
			} else if( isset( $cs_banner_style ) && $cs_banner_style == 'no-header' ){
				$cs_no_header	= 'show';
			} else {
				$cs_default_header	= 'show';
			}
			
			$cs_metaboxes->cs_form_select_render(
				array(  'name'	=>esc_html__('Choose Sub-header','car-rental'),
						'id'	=> 'header_banner_style',
						'classes' => '',
						'std'	=> 'default_header',
						'onclick'	   => 'cs_slider_element_toggle',
						'status'	   => '',
						'description'  => '',
						'options' => array( 'default_header'=>esc_html__('Default Subheader','car-rental'),'breadcrumb_header'=>esc_html__('Custom Subheader','car-rental'),'custom_slider'=>esc_html__('Revolution Slider','car-rental'),'map'=>esc_html__('Map','car-rental'),'no-header'=>esc_html__('No Subheader','car-rental'))
					)
			);
			
			$cs_metaboxes->cs_wrapper_start_render(
				array(  'name'		=>esc_html__('Wrapper','car-rental'),
						'id'		=> 'breadcrumb_header',
						'status'	=> $cs_breadcrumb_header,
					)
			);
			
			$cs_metaboxes->cs_form_checkbox_render(
				array(  'name'	=>esc_html__('Title','car-rental'),
						'id'	=> 'page_title',
						'classes' => '',
						'std'	=> '',
						'description'  => '',
						'hint'  => '',
					)
			);
			
			$cs_metaboxes->cs_form_textarea_render(
				array(  'name'	=>esc_html__('Sub Heading','car-rental'),
						'id'	=> 'page_subheading_title',
						'classes' => '',
						'std'	=> '',
						'description'  => '',
						'hint'  => ''
					)
			);
			
			$cs_metaboxes->cs_form_checkbox_render(
				array(  'name'	=>esc_html__('Breadcrumbs','car-rental'),
						'id'	=> 'page_breadcrumbs',
						'classes' => '',
						'std'	=> '',
						'description'  => '',
						'hint'  => ''
					)
			);
			
			$cs_metaboxes->cs_form_range_render(
				array(  'name'	=>esc_html__('Padding Top','car-rental'),
						'id'	=> 'subheader_padding_top',
						'classes' => '',
						'min'	=> '0',
						'max'	=> '100',
						'step'	=> '1',
						'std'	=> '0',
						'description'  =>esc_html__('Set the top padding (In PX), It Will Only work if padding is selected as','car-rental'), "Custom",
					)
			);
			
			$cs_metaboxes->cs_form_range_render(
				array(  'name'		=>	esc_html__('Padding Bottom','car-rental'),
						'id'	  	=> 'subheader_padding_bottom',
						'classes' 	=> '',
						'min'		=> '0',
						'max'		=> '100',
						'step'		=> '1',
						'std'		   => '0',
						'description'  =>esc_html__('Set the top padding (In PX), It Will Only work if padding is selected as','car-rental'),"Custom",
					)
			);
			
			

			$cs_metaboxes->cs_form_color_render(
				array(  'name'	=>esc_html__('Text Color','car-rental'),
						'id'	=> 'page_subheader_text_color',
						'classes' => '',
						'std'	=> '',
						'description'  => '',
						'hint'  => ''
					)
			);
			
			$cs_metaboxes->cs_form_color_render(
				array(  'name'	=>esc_html__('Border Color','car-rental'),
						'id'	=> 'page_subheader_border_color',
						'classes' => '',
						'std'	=> '',
						'description'  => '',
						'hint'  => ''
					)
			);
			
			$cs_metaboxes->cs_wrapper_end_render(
				array(  'name'	=>esc_html__('Wrapper','car-rental'),
						'id'	=> 'breadcrumb_header',
					)
			);
			
				
			$cs_metaboxes->cs_wrapper_start_render(
				array(  'name'	=>esc_html__('Wrapper','car-rental'),
						'id'	=> 'custom_slider',
						'status'	=> $cs_custom_slider,
					)
			);
			
			$cs_slider_array	= array( '' => 'Select Slider' );
			
			if( class_exists( 'RevSlider' ) && class_exists( 'cs_RevSlider' ) ) {
				$slider = new cs_RevSlider();
				$arrSliders = $slider->getAllSliderAliases();
				foreach ( $arrSliders as $key => $entry ) {
					$cs_slider_array[$entry['alias']]	= $entry['title'];
				}
         	}  
			
			$cs_metaboxes->cs_form_select_render(
				array(  'name'	=>esc_html__('Select Slider','car-rental'),
						'id'	=> 'custom_slider_id',
						'classes' => '',
						'std'	=> 'left',
						'onclick'	   => '',
						'status'	   => '',
						'description'  =>esc_html__("Please select Revolution Slider if already included in package. Otherwise buy Sliders from Code canyon But its optional","car-rental"),	
						
						
						
						
						'options' 	   => $cs_slider_array,
					)
			);
			
			 
			
			$cs_metaboxes->cs_wrapper_end_render(
				array(  'name'	=>esc_html__('Wrapper','car-rental'),
						'id'	=> 'custom_slider',
					)
			);
			
			$cs_metaboxes->cs_wrapper_start_render(
				array(  'name'	=>esc_html__('Wrapper','car-rental'),
						'id'	=> 'map',
						'status'	=> $cs_map,
					)
			);
			
			$cs_metaboxes->cs_form_textarea_render(
				array(  'name'	=>esc_html__('Custom Map Short Code','car-rental'),
						'id'	=> 'custom_map',
						'classes' => '',
						'std'	=> $cs_default_map,
						'description'  =>esc_html__('Please Add/Edit the short code for Map','car-rental'),
						'hint'  => ''
					)
			);
			
			$cs_metaboxes->cs_wrapper_end_render(
				array(  'name'	=>esc_html__('Wrapper','car-rental'),
						'id'	=> 'map',
					)
			);
			
			$cs_metaboxes->cs_wrapper_start_render(
				array(  'name'	=>esc_html__('Wrapper','car-rental'),
						'id'	=> 'no-header',
						'status'	=> $cs_no_header,
					)
			);
			
			$cs_metaboxes->cs_form_color_render(
				array(  'name'	=>esc_html__('Header Border Color','car-rental'),
						'id'	=> 'page_main_header_border_color',
						'classes' => '',
						'std'	=> '',
						'description'  => '',
						'hint'  => ''
					)
			);
			
			$cs_metaboxes->cs_wrapper_end_render(
				array(  'name'	=>esc_html__('Wrapper','car-rental'),
						'id'	=> 'no-header',
					)
			);

	}
}

/**
 * @SEO Settings
 * @return
 *
 */
if ( ! function_exists( 'cs_seo_settitngs_element' ) ) {
	function cs_seo_settitngs_element(){
		global $cs_metaboxes;
		$cs_metaboxes->cs_form_text_render(
				array(  'name'	=>esc_html__('Seo Title','car-rental'),
						'id'	=> 'seo_title',
						'classes' => '',
						'std'	=> '',
						'description'  => '',
						'hint'  => ''
					)
			);

		$cs_metaboxes->cs_form_textarea_render(
				array(  'name'	=>esc_html__('Seo Description','car-rental'),
						'id'	=> 'seo_description',
						'classes' => '',
						'std'	=> '',
						'description'  => '',
						'hint'  => ''
					)
			);
		
		$cs_metaboxes->cs_form_text_render(
				array(  'name'	=>esc_html__('Seo Keywords','car-rental'),
						'id'	=> 'seo_keywords',
						'classes' => '',
						'std'	=> '',
						'description'  => '',
						'hint'  => ''
					)
			);
	}
}


/**
 * @Sidebar Layout
 * @return
 *
 */
if ( ! function_exists( 'cs_sidebar_layout_options' ) ) {
	function cs_sidebar_layout_options(){
		global $post , $cs_xmlObject,$cs_theme_options, $page_option,$cs_metaboxes;
		
		$cs_theme_sidebar   = get_option('cs_theme_options');
		$cs_sidebars_array	= array(''=>'Select Sidebar');
		if ( isset($cs_theme_sidebar['sidebar']) and count($cs_theme_sidebar['sidebar']) > 0 ) {
			foreach ( $cs_theme_sidebar['sidebar'] as $key => $sidebar ){
				$cs_sidebars_array[$sidebar]	= $sidebar;
			}

		}
		
		$cs_page_layout = get_post_meta($post->ID, 'cs_page_layout', true);
		
		$cs_left = $cs_right = 'hide';
		if( isset( $cs_page_layout ) && $cs_page_layout == 'left' ){
			$cs_left	= 'show';
		} else if( isset( $cs_page_layout ) && $cs_page_layout == 'right' ){
			$cs_right	= 'show';
		} 
		
		$cs_metaboxes->cs_form_layout_render(
			array(  'name'	=>esc_html__('Choose Sidebar','car-rental'),
					'id'	=> 'page_layout',
					'std'	=> 'none',
					'classes' => '',
					'description'  => '',
					'onclick'	   => '',
					'status'	   => '',
					'meta'  	   => '',
				)
		);

		
		$cs_metaboxes->cs_wrapper_start_render(
			array(  'name'		=>esc_html__('Wrapper','car-rental'),
					'id'		=> 'sidebar_left',
					'status'	=> $cs_left,
				)
		);
		
		$cs_metaboxes->cs_form_select_render(
			array(  'name'	=>esc_html__('Select Left Sidebar','car-rental'),
					'id'	=> 'page_sidebar_left',
					'classes' => '',
					'std'	=> '',
					'description'  =>esc_html__('Add New Sidebar','car-rental'), '<a href="'.esc_url(admin_url('themes.php?page=cs_options_page#tab-sidebar-show" target="_blank"')).'>'.esc_html__('Click Here','car-rental').'</a>',
					'onclick'	   => '',
					'status'	   => '', // Hide OR Show
					'options' 	   => $cs_sidebars_array,
				)
		);
		
		$cs_metaboxes->cs_wrapper_end_render(
			array(  'name'		=>esc_html__('Wrapper','car-rental'),
					'id'		=> 'sidebar_left',
				)
		);
		
		$cs_metaboxes->cs_wrapper_start_render(
			array(  'name'		=>esc_html__('Wrapper','car-rental'),
					'id'		=> 'sidebar_right',
					'status'	=> $cs_right,
				)
		);
		
		$cs_metaboxes->cs_form_select_render(
			array(  'name'	=>esc_html__('Select Right Sidebar','car-rental'),
					'id'	=> 'page_sidebar_right',
					'classes' => '',
					'std'	=> '',
					'description'  =>esc_html__('Add New Sidebar','car-rental'), '<a href="'.esc_url(admin_url().'themes.php?page=cs_options_page#tab-sidebar-show" target="_blank"').'>'.esc_html__('Click Here','car-rental').'</a>',
					'onclick'	   => '',
					'status'	   => '',
					'options' 	   => $cs_sidebars_array,
				)
		);
		
		$cs_metaboxes->cs_wrapper_end_render(
			array(  'name'		=>esc_html__('Wrapper','car-rental'),
					'id'		=> 'sidebar_right',
				)
		);
		
		$cs_metaboxes->cs_form_hidden_render(
			array(  'id'	=>esc_html__('orderby','car-rental'),
					'classes' => '',
					'std'	=> 'meta_layout',
					'type'    => 'array', // Type : array for arrays and for single leave it empty,
					'return'  => 'echo' // return type : echo OR return
				)
		);

	}
}