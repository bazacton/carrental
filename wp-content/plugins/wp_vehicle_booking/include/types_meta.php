<?php
/**
 *  File Type: Types Class
 */

if( ! class_exists('cs_types_options') ) {
	
    class cs_types_options {
		
		public function __construct() {
			add_action('wp_ajax_cs_add_types', array(&$this, 'cs_add_types'));
			add_action('wp_ajax_cs_remove_types', array(&$this, 'cs_remove_types'));
			add_action( 'admin_menu', array(&$this, 'cs_type_settings') );
		}
		
		//add submenu page
		public function cs_type_settings() {
			
			add_submenu_page('edit.php?post_type=vehicles', __('Vehicle Types', 'rental'), __('Vehicle Types', 'rental'), 'manage_options', 'cs_types', array(&$this, 'list_types'));
		}
		
		
		public function list_types( ) {
			global $cs_plugin_options;
			
			$currency_sign = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '$';
			
			wp_car_rental::cs_data_table_style_script();
			
			$cs_type_data = get_option( "cs_type_options" );
			
			$cs_html = '
			<div class="theme-wrap fullwidth">
				<div class="row">
				 <div id="message" class="cs-update-message updated notice notice-success" style="display:none;">
                        <p></p>
                    </div>
					<form name="cs-booking-customers" id="cs-booking-customers" data-url="'.esc_js(admin_url('admin-ajax.php')).'" method="post">
						<div class="cs-customers-area">
							<script type="text/javascript">
							jQuery(document).ready(function() {
								jQuery("#cs_types_data").DataTable({
									fnDrawCallback : function() {
										cs_delete_type();
								  	}
								});
								cs_delete_type();
							});
							</script>
							<div class="cs-title"><h2>' . __('Manage Types', 'rental') . '</h2></div>
	                        <a href="javascript:cs_createpop(\'cs_type_pop\',\'filter\')" style="margin-top:20px;" class="button">'.__("+ Add New Type","rental").'</a>
							<div class="cs_table_data cs_loading">
								<table id="cs_types_data" class="display" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>'.__('Vehicle Type','rental').'</th>
											<th>'.__('Action','rental').'</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>'.__('Vehicle Type','rental').'</th>
											<th>'.__('Action','rental').'</th>
										</tr>
									</tfoot>
									<tbody>';
										$cs_type_counter = 0;
										if( isset( $cs_type_data ) && is_array( $cs_type_data) && sizeof( $cs_type_data ) > 0 ) {
											foreach( $cs_type_data as $key => $type ) {
																							
												if( isset($cs_type_data[$key]) ) {
													$cs_type_fields = $cs_type_data[$key];
												}
												
												if( isset($cs_type_fields) ) {
													
													$cs_type_name = isset($cs_type_fields['cs_type_name']) ? $cs_type_fields['cs_type_name'] : '';
													if (function_exists('icl_register_string')) {
														do_action('wpml_register_single_string', 'Vehicle Types', 'Type "' . $cs_type_name . '" - Name field', $cs_type_name);
													}
													
													$cs_html .= '<tr class="type-detail">
																	<td>'.$cs_type_name.'</td>
																	<td class="type-action" data-key='.$key.'>
																		<a class="type-delete"><i class="icon-trash4"></i></a>
																		
																		<a href="javascript:cs_createpop(\'cs_type_pop_'.$cs_type_counter.'\',\'filter\')"><i class="icon-pencil3"></i></a>
																		'.$this->cs_type_edit($key,$cs_type_counter).'
																	</td>
																</tr>';
																
												}else {
													$cs_html .= '<tr><td class="dataTables_empty" valign="top" colspan="7">' . __('No records found.', 'rental') . '</td></tr>';
												}
												$cs_type_counter++;
											}
										}
									$cs_html .= '	
									</tbody>
								</table>
							</div>
						</div>
					</form>
				</div>
			</div>';
			echo force_balance_tags( $cs_html, true );
			echo cs_allow_special_char($this->cs_type_form());
		}
		
		/**
		 *
		 *@Edit Type
		 *
		 */
		public function cs_type_edit( $key='',$counter='' ){
			global $post;
			$cs_type_data = get_option( "cs_type_options" );
			$type_data	= $cs_type_data[$key];
			$cs_html  = '';
			$cs_html .= '<div class="cs-vehicles-prices cs-types-area cs-types-edit"><div id="cs_type_pop_'.$counter.'" style="display: none;">
							<div class="cs-popup-header">
								<h5 style="margin:0px">' . __('Edit Type', 'rental') . '</h5>
								<span class="cs-pop-close" onclick="javascript:cs_remove_overlay(\'cs_type_pop_'.$counter.'\',\'append\')"> <i class="icon-times"></i></span>
							</div>
							<div class="cs-popup-content">
							 <div class="message-wrap" style="display:none">
								<div class="cs-message updated"></div>
							</div>';
								$cs_html .= '
								<div class="type-input">
									<label>
										<span>'.__('Vehicle Type','rental').'</span>
										<input id="cs_type_name" value="'.$type_data['cs_type_name'].'" name="cs_type_name" type="text" />
									</label>
								</div>
								<div class="type-input vehicle-icon-wrap">
									 <div class="page-wrap cs-option-image"  id="cs_type_image'.$counter.'_box">
									  <div class="gal-active">
										<div class="dragareamain" style="padding-bottom:0px;">
										  <ul id="gal-sortable">
											<li class="ui-state-default" id="" style="height:120px; width:120px;">
											  <div class="thumb-secs" style="padding:5px;"> <img src="'.esc_url($type_data['cs_type_image']).'" id="cs_type_image'.$counter.'_img" width="100" alt="">
												<div class="gal-edit-opts"><a href="javascript:del_media(\'cs_type_image'.$counter.'\')" class="delete"></a> </div>
											  </div>
											</li>
										  </ul>
										</div>
									  </div>
									</div>
										<input id="cs_type_image'.$counter.'" name="cs_type_image'.$counter.'" type="hidden" class="" value="'.$type_data['cs_type_image'].'">
										<label class="browse-icon">
										  <input name="cs_type_image'.$counter.'" type="button" class="uploadMedia left" value="Browse"  style="width:120px">
										</label>
								</div>
								<div id="add_type_to_btn">
									<a class="type-action-btn price-btn" id="cs_edit_type" data-image="'.$counter.'" data-key="update" data-id="'.$key.'" type="button">'.__('Update Type', 'rental').'</a>
								</div>
							</div>
							</div>
						</div>';
			return force_balance_tags( $cs_html, true );
		}
		
		/**
		 *
		 *@Add Form
		 *
		 */
		public function cs_type_form(){
			global $post,$cs_form_fields;
			$rand_id	= rand(55,999999);
			$cs_html  = '';
			$cs_html .= '<div class="cs-vehicles-prices cs-types-area"><div id="cs_type_pop" style="display: none;">
							<div class="cs-popup-header">
								<h5 style="margin:0px">' . __('Add New Vehicle Type', 'rental') . '</h5>
								<span class="cs-pop-close" onclick="javascript:cs_remove_overlay(\'cs_type_pop\',\'append\')"> <i class="icon-times"></i></span>
							</div>
							<div class="cs-popup-content">
							 <div class="message-wrap" style="display:none">
								<div class="cs-message updated"></div>
							</div>';
								$cs_html .= '
								<div class="type-input">
									<label>
										<span>'.__('Vehicle Type Name','rental').'  *(required)</span>
										<input id="cs_type_name" name="cs_type_name" type="text" />
									</label>
								</div>
								<div class="type-input vehicle-icon-wrap">
									 <div class="page-wrap cs-option-image" style="display:none" id="cs_type_image'.$rand_id.'_box">
									  <div class="gal-active">
										<div class="dragareamain" style="padding-bottom:0px;">
										  <ul id="gal-sortable">
											<li class="ui-state-default" id="" style="height:120px; width:120px;">
											  <div class="thumb-secs" style="padding:5px;"> <img src="" id="cs_type_image'.$rand_id.'_img" width="100" alt="">
												<div class="gal-edit-opts"><a href="javascript:del_media(\'cs_type_image'.$rand_id.'\')" class="delete"></a> </div>
											  </div>
											</li>
										  </ul>
										</div>
									  </div>
									</div>
										<input id="cs_type_image'.$rand_id.'" name="cs_type_image'.$rand_id.'" type="hidden" class="" value="">
										<label class="browse-icon">
										  <input name="cs_type_image'.$rand_id.'" type="button" class="uploadMedia left" value="Browse" style="width:120px">
										</label>
								</div>
								
								<div id="add_type_to_btn">
									<a class="type-action-btn price-btn" id="cs_add_type" data-image="'.$rand_id.'"  data-key="add" data-id="" type="button">'.__('Add Type', 'rental').'</a>
								</div>
							</div>
							</div>
						</div>';
			return force_balance_tags( $cs_html, true );
		}
		
		/**
		 *
		 *@add New Type
		 *
		 */
		public function cs_add_types(){
			global $post,$gateways,$cs_plugin_options;
			
			$json		= array();
			$key		= $_REQUEST['key'];

			if( $key == 'update' ) {
				$type_id		= $_REQUEST['id'];
			} else{
				$type_id  = CS_FUNCTIONS()->cs_generate_random_string(10);
			}
			
			$cs_type_name		= $_REQUEST['cs_type_name'];
			$cs_type_image		= $_REQUEST['cs_type_image'];
			
			if( $type_id !='' && $cs_type_name  && $cs_type_image ){
					
					$cs_new_type	= array();

					$cs_type	= get_option('cs_type_options');
					
					if( isset( $cs_type ) && ! is_array( $cs_type ) ) {
						$cs_type	= array();
					}
					
					$cs_new_type[$type_id]['type_id']				= $type_id;	
					$cs_new_type[$type_id]['cs_type_name']			= $cs_type_name;	
					$cs_new_type[$type_id]['cs_type_image']			= $cs_type_image;	

					if( isset( $cs_type[$type_id]) && $key == 'add' ) {
						$json['type']		= 'error';
						$json['message']	= __('Vehicle Type already exist.','rental');
						echo json_encode( $json );
						die();
					
					} else if( isset( $cs_type[$type_id]) && $key == 'update' ) {
						
						$cs_all_types	= 	array_merge($cs_type,$cs_new_type);
						update_option( 'cs_type_options',$cs_all_types );
						
						$json['type']		= 'success';
						$json['message']	= __('Vehicle Type Updated.','rental');
						echo json_encode( $json );
						die();
					
					} else {
						
						$cs_all_types	= 	array_merge($cs_type,$cs_new_type);
						update_option( 'cs_type_options',$cs_all_types );
						
							$json['data']	= '<tr class="type-detail">';
							$json['data']	.= '<td>'.$cs_type_name.'</td>';
							$json['data']	.= '<td class="type-action" data-key="'.$type_id.'">
													<script type="text/javascript">
													jQuery(document).ready(function() {
														cs_delete_type();
													});
													</script>
													<a class="type-delete"><i class="icon-trash4"></i></a>
													<a class="type-edit" onclick="javascript:cs_createpop(\'cs_type_pop_'.$type_id.'\',\'filter\')"><i class="icon-pencil3"></i></a>
													'.$this->cs_type_edit($type_id,$type_id).'
												</td>';
						$json['data']	.= '</tr>';
						
						
						$json['type']		= 'success';
						$json['message']	= __('Vehicle Type Added.','rental');
					}
	
			} else{
				$json['type']		= 'error';
				$json['message']	= __('Please fill all the fields.','rental');
			}
			
			echo json_encode( $json );
			die();
		}
		
		/**
		 *
		 *@Remove Type
		 *
		 */
		public function cs_remove_types(){
			global $post,$cs_plugin_options;
			
			$type_id			= CS_FUNCTIONS()->cs_generate_random_string(10);
			$json				= array();
			$cs_types	= get_option('cs_type_options');
			$key		= $_REQUEST['key'];
			
		
			if( $key !='' ){
				unset( $cs_types[$key] );
				update_option( 'cs_type_options',$cs_types );
				
				$json['type']		= 'success';
				$json['message']	= __('Vehicle Type Deleted.','rental');
	
			} else{
				$json['type']		= 'error';
				$json['message']	= __('Please fill all the fields.','rental');
			}
			
			echo json_encode( $json );
			die();
		}
	}
	
	new cs_types_options();
}