<?php
/**
 *  File Type: Vehicles Block
 */

if( ! class_exists('cs_vehicles_block') ) {
	
    class cs_vehicles_block {
		
		public function __construct() {
			add_action( 'admin_menu', array(&$this, 'cs_block_menu') );
			add_action('wp_ajax_cs_get_vehicles', array(&$this, 'cs_get_vehicles'));
			add_action('wp_ajax_cs_update_vehicles', array(&$this, 'cs_update_vehicles'));
		}
		
		//add submenu page
		public function cs_block_menu() {
			
			add_submenu_page('edit.php?post_type=vehicles', __('Block Vehicles', 'rental'), __('Block Vehicles', 'rental'), 'manage_options', 'cs_blocks', array(&$this, 'cs_block_meta'));
		}
		
		//add price fields
		public function cs_block_meta() {
			
			global $cs_form_fields;
			?>
            <div class="theme-wrap fullwidth">
                <div class="row">
                    <div id="message" class="cs-update-message updated notice notice-success" style="display:none;">
                        <p></p>
                    </div>
                    <div class="vehicle-block-wrap cs-customers-area">
						<div class="cs-title"><h2><?php _e('Block Vehicles', 'rental');?></h2></div>
                    	<div class="cs_table_data cs_loading">
                            <form action="">
                                <div class="cs-block-header">
                                   <select name="cs_vehicles" id="cs_get_vehicles" class="dropdown">
                                        <option value=""><?php _e('Select Vehicle', 'rental') ?></option>
                                        <?php
                                            $cs_vehicle_types = array();
                                            $cs_args = array( 'posts_per_page' => '-1', 'post_type' => 'vehicles', 'orderby'=>'ID', 'post_status' => 'publish' );
                                            $cust_query = get_posts($cs_args);
                                            
                                            if( sizeof($cust_query) > 0 ) {
                                                
                                                foreach( $cust_query as $type ) {
                                                    echo '<option value="'.$type->ID.'">'.get_the_title($type->ID).'</option>';
                                                }
                                                wp_reset_postdata();
                                            }
                                        ?>
                                    </select>
                                    <div id="cs-loader"></div>
                                </div>
									<?php wp_car_rental::cs_data_table_style_script(); ?>
									<script type="text/javascript">
										jQuery(document).ready(function() {
											jQuery("#cs_block_data").dataTable({
												"paging":   true,
												"pagingType": "simple_numbers",
												"ordering": true,
												"info":     false,
												"fnDrawCallback": function(oSettings) {
													if(jQuery("#cs_block_data").find("tr:not(.ui-widget-header)").length <= 4){
													} else {
													}
												}
											});
										});
									</script>                              
                                    <div class="vehicles-data-wrapper">
                                     	<table id="cs_block_data" class="display" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="15%"><?php _e('Id', 'rental'); ?></th>
                                                    <th width="15%"><?php _e('Vehicle Reference No', 'rental'); ?></th>
                                                    <th width="15%"><?php _e('Reason', 'rental'); ?></th>
                                                    <th width="15%"><?php _e('Action', 'rental'); ?></th>
                                                </tr>
                                            </thead>
                                    </table>
                            	</div>
                    		</form>
                        </div>
                    </div>
                </div>
           </div>
           <?php
		}
		
		// Get Vehicles Data
		public function cs_get_vehicles(){
			global $post;
			$json		= array();
			$vehicle_id	= (isset($_REQUEST['vehicle_id']) and $_REQUEST['vehicle_id'] <> '') ? $_REQUEST['vehicle_id'] : '';
			
			if ( $vehicle_id =='' ){
				$json['type']		= 'error';
				$json['message']	= '<i class="icon-times"></i> Some error occur, pleae try again later.';
			} else {
				
				$list_item	= '<script>jQuery(document).ready(function() {	cs_update_vehicles(); });</script>';
				$list_item	.= '<script type="text/javascript">
									jQuery(document).ready(function() {
										jQuery("#cs_block_data").dataTable({
											"paging":   true,
											"pagingType": "simple_numbers",
											"ordering": true,
											"info":     false,
											"pageLength": 15,	
											"fnDrawCallback": function(oSettings) {
												if(jQuery("#cs_block_data").find("tr:not(.ui-widget-header)").length <= 4){
												} else {
												}
											}
										});
									});
								</script>'; 	
				$data_attr	= get_post_meta($vehicle_id, 'cs_vehicle_meta_data', true);
				if( isset( $data_attr ) && $data_attr !='' ) {
					$cs_vehicle_data = '';
					$cs_vehicle_meta = get_post_meta($vehicle_id, 'cs_vehicle_meta_data', false);
					$data_counter	 = 0;
					$list_item	.='<table id="cs_block_data" class="display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th width="15%">'.__('Id', 'rental').'</th>
								<th width="15%">'.__('Vehicle Reference No', 'rental').'</th>
								<th width="15%">'.__('Reason', 'rental').'</th>
								<th width="15%">'.__('Action', 'rental').'</th>
							</tr>
						</thead>';
						foreach( $cs_vehicle_meta[0] as $key => $vehicle_reference  ){
							$data_counter++;
							$cs_status	= 'In-active';
							$class		= 'in-active';
							if( $vehicle_reference['status'] == 'active' ) {
								$cs_status	= 'Active';
								$class		= 'active';
							}
							$color	= 'cs-odd';
							if( $data_counter%2 == 0 ) {
								$color	= 'cs-even';
							}
							$list_item	.= '<tr class="vehicles-data">
								<td  width="15%">'.$data_counter.'</td>
								<td  width="15%">'.$vehicle_reference['reference_no'].'</td>
								<td  width="15%"  class="cs-block-reason">
								<a href="javascript:;" class="edit-reason"><i class="icon-pencil3"></i></a>
								<a href="javascript:;" data-key='.$key.' data-reference='.$vehicle_id.' data-status="no" class="edit-reason-update" style="display:none">
								<i class="icon-cycle"></i></a>
								<input  type="text" value="'.$vehicle_reference['reason'].'" style="display:none" />
								<p>'.$vehicle_reference['reason'].'</p>
								
								</td>
								<td  width="15%" class="cs-block-action"><a class="'.$class.'" href="javascript:;"  data-status="yes" data-key='.$key.' data-reference='.$vehicle_id.' >'.$cs_status.'</a>
								<span class="cs-spinner"></span>
								</td>
							</tr>';
					}

				} else {
					$list_item	.= '<div class="vehicles-data">'.__('No Vehicles Found.', 'rental').'</div>';	
				}
				$list_item	.= '</table>';
				
				$json['type']		= 'success';
				$json['data']		= $list_item;
			}
			echo json_encode( $json );
			die();
		}
		
		// Update Vehicles Data
		public function cs_update_vehicles(){
			global $post;

			$json				= array();
			$data_key	    	= $_REQUEST['data_key'];
			$vehicle_id	    	= $_REQUEST['vehicle_id'];
			$reason	    		= $_REQUEST['reason'];
			$status_update	    = $_REQUEST['status'];

			if ( $data_key =='' ){
				$json['type']		= 'error';
				$json['message']	= __('Some error occur, please try again later.','rental');
			} else {
				if( isset( $vehicle_id ) && $vehicle_id !='' ){
					$cs_vehicle_meta = get_post_meta($vehicle_id, 'cs_vehicle_meta_data', false);
					
					if( isset( $cs_vehicle_meta[0][$data_key] ) ) {
						$vehicle_data	= array();
						$vehicle_data['id']				= $cs_vehicle_meta[0][$data_key]['id'];
						$vehicle_data['reference_no']	= $cs_vehicle_meta[0][$data_key]['reference_no'];
						
						if( $cs_vehicle_meta[0][$data_key]['status'] == 'active' ) {
							$status	= 'in-active';
							$json['message']	= __('Vehicle is de-activated.','rental');
						} else{
							$status	= 'active';
							$json['message']	= __('Vehicle is activated.','rental');
						}
						
						if( $status_update == 'no' ) {
							$status	= $cs_vehicle_meta[0][$data_key]['status'];
						}
						
						$vehicle_data['status']			= $status;
						$vehicle_data['reason']			= $reason;

						$cs_vehicle_meta[0][$data_key]	= $vehicle_data;
						$new_data	= $cs_vehicle_meta[0];
						
						update_post_meta($vehicle_id,'cs_vehicle_meta_data',$new_data);
						$json['type']		=__('success','rental');
						$json['status']		= $status;
						
					
					} else{
						$json['type']		=__('error','rental');
						$json['message']	= __('Some error occur, pleae try again later.','rental');
					}

					
				} else{
					$json['type']		=__('error','rental');
					$json['message']	= __('Some error occur, pleae try again later.','rental');
				}
		  }
			  echo json_encode( $json );
			  die();
		}
	}
	
	new cs_vehicles_block();
}

