<?php
/**
 *  File Type: Customers Class
 */

if( ! class_exists('cs_transactions_options') ) {
	
    class cs_transactions_options {
		
		public function __construct() {
			add_action( 'admin_menu', array(&$this, 'cs_transactions_settings') );
			add_action('wp_ajax_cs_add_transaction', array(&$this, 'cs_add_transaction'));
			add_action('wp_ajax_cs_transaction_filteration', array(&$this, 'cs_transaction_filteration'));
			add_action('wp_ajax_cs_update_transaction_status', array(&$this, 'cs_update_transaction_status'));
		}
		
		/**
		 *
		 *@Transaction Settings Menu
		 *
		 */
		public function cs_transactions_settings() {
			add_submenu_page('edit.php?post_type=vehicles', __('Transactions', 'rental'), __('Transactions', 'rental'), 'manage_options', 'cs_transactions', array(&$this, 'cs_transactions_area'));
		}
		
		/**
		 *
		 *@Transaction Filters
		 *
		 */
		public function cs_transaction_filteration(){
			global $post,$gateways,$cs_plugin_options;
			
			$currency_sign	= isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] !='' ? $cs_plugin_options['currency_sign'] : '$';
			
			$json	= array();
			$search_type			= $_REQUEST['search_type'];
			
			if( $transaction_id !=''){
					
					$cs_new_transaction	= array();
					$cs_transactions	= get_option('cs_transactions');

					
					if( isset( $cs_transactions[$transaction_id] ) ) {
						$json['type']		= 'error';
						$json['message']	= __('Transaction Id already exist.','rental');
						echo json_encode( $json );
						die();
					} else {
						$json['data']	= '';
						
						foreach( $cs_transactions_data as $key => $trans ) {
							if( $trans['cs_trans_gateway'] == $search_type ){
								$json['data']	.= '<tr>';
									$json['data']	.= '<td width="15%">'.$transaction_id.'</td>';
									$json['data']	.= '<td width="15%">'.$cs_booking_id.'</td>';
									$json['data']	.= '<td width="15%">'.$cs_trans_first_name.' '.$cs_trans_last_name.'</td>';
									$json['data']	.= '<td width="10%">'.$gateways[strtoupper($_REQUEST['cs_trans_gateway'])].'</td>';
									$json['data']	.= '<td width="5%">'.$currency_sign.$cs_trans_amount.'</td>';
									$json['data']	.= '<td width="5%">'.ucwords($cs_trans_status).'</td>';
									$json['data']	.= '<td width="10%">'.date_i18n( get_option( 'date_format' ), strtotime( date('Y-m-d H:i:s') ) ).'</td>';
									$json['data']	.= '<td width="20%">'.$cs_trans_address.'</td>';
								$json['data']	.= '</tr>';
							}
						}	
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
		 *@Update Status
		 *
		 */
		public function cs_update_transaction_status(){
			$json	= array();
			$status				= $_REQUEST['status'];
			$transaction_id		= $_REQUEST['transaction_id'];
			
			if( $status !='' ){
					
					$cs_new_transaction	= array();
					$cs_transactions	= get_option('cs_transactions');

					
					if( $transaction_id =='' ) {
						
						$json['type']		= 'error';
						$json['message']	= __('Some error occur, please try again later','rental');
						echo json_encode( $json );
						die();
						
					} else {
						
						$cs_new_transaction	= array();
						$cs_transactions	= get_option('cs_transactions');
						
						$cs_new_transaction[$transaction_id]['cs_trans_id']			= $cs_transactions[$transaction_id]['cs_trans_id'];	
						$cs_new_transaction[$transaction_id]['cs_booking_id']		= $cs_transactions[$transaction_id]['cs_booking_id'];	
						$cs_new_transaction[$transaction_id]['cs_trans_email']		= $cs_transactions[$transaction_id]['cs_trans_email'];	
						$cs_new_transaction[$transaction_id]['cs_trans_first_name']	= $cs_transactions[$transaction_id]['cs_trans_first_name'];	
						$cs_new_transaction[$transaction_id]['cs_trans_last_name']	= $cs_transactions[$transaction_id]['cs_trans_last_name'];	
						$cs_new_transaction[$transaction_id]['cs_trans_address']	= $cs_transactions[$transaction_id]['cs_trans_address'];	
						$cs_new_transaction[$transaction_id]['cs_trans_amount']		= $cs_transactions[$transaction_id]['cs_trans_amount'];	
						$cs_new_transaction[$transaction_id]['cs_trans_gateway']	= $cs_transactions[$transaction_id]['cs_trans_gateway'];	
						$cs_new_transaction[$transaction_id]['cs_trans_status']		= $status;
						$cs_new_transaction[$transaction_id]['cs_trans_date']		= $cs_transactions[$transaction_id]['cs_trans_date'];
						$cs_new_transaction[$transaction_id]['cs_trans_currency']	= $cs_transactions[$transaction_id]['cs_trans_currency'];
	
						$cs_all_transactions	= array_merge($cs_transactions,$cs_new_transaction);
						
						update_option( 'cs_transactions',$cs_all_transactions );
						$json['type']		= 'success';
						$json['message']	= __('Status Updated.','rental');
					}
	
			} else{
				$json['type']		= 'error';
				$json['message']	= __('Please status','rental');
			}
			
			echo json_encode( $json );
			die();
		}
		
		/**
		 *
		 *@Transections Listing
		 *
		 */
		public function cs_transactions_area() {
			global $post, $cs_form_fields, $gateways, $cs_plugin_options;
			
			$payment_geteways = array();
			$currency_sign	= isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] !='' ? $cs_plugin_options['currency_sign'] : '$';
			wp_car_rental::cs_data_table_style_script();
			$cs_transactions_data = get_option( "cs_transactions" );
			
			?>
            <div class="theme-wrap fullwidth">
				<div class="row">
						<div class="cs-customers-area">
							<script type="text/javascript">
								jQuery(document).ready(function() {
									jQuery('#cs_transactions_data').dataTable({
										"paging":   true,
										"pagingType": "simple_numbers",
										"ordering": true,
										"order": [[ 6, "desc" ]],
										"info":     false,
										"pageLength": 15,	
										"fnDrawCallback": function(oSettings) {
											if(jQuery("#cs_transactions_data").find("tr:not(.ui-widget-header)").length <= 4){
												//jQuery('#cs_transactions_data .dataTables_paginate').hide();
											} else {
												//jQuery('#cs_transactions_data .dataTables_paginate').show();
											}
										}
									});
								});
							</script>
							<div class="cs-title"><h2><?php _e('Transactions', 'rental');?></h2></div>
                            <a href="javascript:cs_createpop('cs_transactions_pop','filter')" style="margin-top:20px;" class="button"><?php _e("+ Add New Transaction","rental");?></a>
							<div class="cs_table_data cs_loading">
								<table id="cs_transactions_data" class="display" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th width="15%"><?php	_e('Transaction Id', 'rental');?></th>
											<th width="15%"><?php  	_e('Booking Id', 'rental');?></th>
											<th width="15%"><?php  	_e('Customer', 'rental');?></th>
											<th width="15%"><?php  	_e('Payment Type', 'rental');?></th>
											<th width="5%"><?php  	_e('Amount', 'rental');?></th>
											<th width="5%"><?php  	_e('Status', 'rental');?></th>
											<th width="10%"><?php  	_e('Payment Date', 'rental');?></th>
                                            <th width="20%"><?php  	_e('Address', 'rental');?></th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th width="15%"><?php	_e('Transaction Id', 'rental');?></th>
											<th width="15%"><?php  	_e('Booking Id', 'rental');?></th>
											<th width="15%"><?php  	_e('Customer', 'rental');?></th>
											<th width="15%"><?php  	_e('Payment Type', 'rental');?></th>
											<th width="5%"><?php  	_e('Amount', 'rental');?></th>
											<th width="5%"><?php  	_e('Status', 'rental');?></th>
											<th width="10%"><?php  	_e('Payment Date', 'rental');?></th>
                                            <th width="2%"><?php  	_e('Address', 'rental');?></th>
										</tr>
									</tfoot>
									<tbody>
                                    <?php 
										if( is_array($cs_transactions_data) && sizeof($cs_transactions_data) > 0 ) {
											foreach( $cs_transactions_data as $key => $trans ) {
												$gateway_type	= isset( $trans['cs_trans_gateway'] ) && isset( $gateways[strtoupper($trans['cs_trans_gateway'])] ) ? $gateways[strtoupper($trans['cs_trans_gateway'])] :'';
												$cs_trans_id	= isset( $trans['cs_trans_id'] ) ? $trans['cs_trans_id'] :'';
												$cs_booking_id	= isset( $trans['cs_booking_id'] ) ? $trans['cs_booking_id'] :'';
												$cs_trans_first_name	= isset( $trans['cs_trans_first_name'] ) ? $trans['cs_trans_first_name'] :'';
												$cs_trans_last_name	= isset( $trans['cs_trans_last_name'] ) ? $trans['cs_trans_last_name'] :'';
												$cs_trans_amount	= isset( $trans['cs_trans_amount'] ) ? $trans['cs_trans_amount'] :'';
												$cs_trans_status	= isset( $trans['cs_trans_status'] ) ? $trans['cs_trans_status'] :'';
												$cs_trans_date		= isset( $trans['cs_trans_date'] ) ? $trans['cs_trans_date'] :'';
												$cs_trans_address	= isset( $trans['cs_trans_address'] ) ? $trans['cs_trans_address'] :'';
												
                                            	echo '<tr>
													<td width="15%">'.$cs_trans_id.'</td>
                                                    <td width="15%">'.$cs_booking_id.'</td>
                                                    <td width="15%">'.$cs_trans_first_name.' '.$cs_trans_last_name.'</td>
                                                    <td width="10%">'. $gateway_type .'</td>
                                                    <td width="5%">'.$currency_sign.$cs_trans_amount.'</td>
                                                   	<td width="5%">
														<select name="cs_filter_gateways" class="cs_filter_gateways" data-key="'.$key.'">';
														?>
                                        					<option value="approved" <?php echo isset( $cs_trans_status ) && $cs_trans_status == 'approved' ? 'selected' :'';?>>
																<?php _e('Approved','rental');?>
                                                        	</option>
                                                        	<option value="pending" <?php echo isset( $cs_trans_status ) && $cs_trans_status == 'pending' ? 'selected' :'';?>>
															<?php _e('Pending','rental');?>
                                                        	</option>
                                                       	</select>
                                                    </td>
                                                    <td width="10%"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $cs_trans_date ) );?></td>
                                                    <td width="20%"><?php echo force_balance_tags($cs_trans_address);?></td>
                                                </tr>
											<?php }
										}
									?>	
									</tbody>
								</table>
							</div>
						</div>
				</div>
			</div>
            <?php 
			$this->cs_add_transaction_form();
		}

		/**
		 *
		 *@add New Transaction
		 *
		 */
		public function cs_add_transaction(){
			global $post,$gateways,$cs_plugin_options;
			
			$currency_sign	= isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] !='' ? $cs_plugin_options['currency_sign'] : '$';
			
			$json	= array();
			$transaction_id			= $_REQUEST['cs_trans_id'];
			$cs_booking_id			= $_REQUEST['cs_booking_id'];
			$cs_trans_email			= $_REQUEST['cs_trans_email'];
			$cs_trans_first_name	= $_REQUEST['cs_trans_first_name'];
			$cs_trans_last_name		= $_REQUEST['cs_trans_last_name'];
			$cs_trans_address		= $_REQUEST['cs_trans_address'];
			$cs_trans_amount		= $_REQUEST['cs_trans_amount'];
			$cs_trans_gateway		= $_REQUEST['cs_trans_gateway'];
			$cs_trans_status		= $_REQUEST['cs_trans_status'];
			
			if( $transaction_id !='' 
				&& $cs_booking_id !='' 
				&& $cs_trans_email !='' 
				&& $cs_trans_first_name !='' 
				&& $cs_trans_last_name !='' 
				&& $cs_trans_address !='' 
				&& $cs_trans_amount !='' 
				&& $cs_trans_gateway !='' 
				&& $cs_trans_status !='' ){
					
					$cs_new_transaction	= array();
					$cs_transactions	= get_option('cs_transactions');
					
					if( isset( $cs_transactions ) && ! is_array( $cs_transactions ) ) {
						$cs_transactions	= array();
					}
					
					if( isset( $cs_transactions[$transaction_id] ) ) {
						$json['type']		= 'error';
						$json['message']	= __('Transaction Id already exist.','rental');
						echo json_encode( $json );
						die();
					} else {
						
						$approved_status	= '';
						$pending_status		= '';
						
						if( $cs_trans_status == 'approved' ) {
							$approved_status	= 'selected';
						} elseif(  $cs_trans_status == 'pending' ) {
							$pending_status		= 'selected';
						}
							
						$cs_new_transaction[$transaction_id]['cs_trans_id']			= $transaction_id;	
						$cs_new_transaction[$transaction_id]['cs_booking_id']		= $cs_booking_id;	
						$cs_new_transaction[$transaction_id]['cs_trans_email']		= $cs_trans_email;	
						$cs_new_transaction[$transaction_id]['cs_trans_first_name']	= $cs_trans_first_name;	
						$cs_new_transaction[$transaction_id]['cs_trans_last_name']	= $cs_trans_last_name;	
						$cs_new_transaction[$transaction_id]['cs_trans_address']	= $cs_trans_address;	
						$cs_new_transaction[$transaction_id]['cs_trans_amount']		= $cs_trans_amount;	
						$cs_new_transaction[$transaction_id]['cs_trans_gateway']	= $cs_trans_gateway;	
						$cs_new_transaction[$transaction_id]['cs_trans_status']		= $cs_trans_status;
						$cs_new_transaction[$transaction_id]['cs_trans_date']		= date('Y-m-d H:i:s');
						$cs_new_transaction[$transaction_id]['cs_trans_currency']	= $currency_sign;
	
						$cs_all_transactions	= array_merge($cs_transactions,$cs_new_transaction);
						
						$json['data']	= '<tr>';
							$json['data']	.= '<td width="15%">'.$transaction_id.'</td>';
							$json['data']	.= '<td width="15%">'.$cs_booking_id.'</td>';
							$json['data']	.= '<td width="15%">'.$cs_trans_first_name.' '.$cs_trans_last_name.'</td>';
							$json['data']	.= '<td width="10%">'.$gateways[strtoupper($_REQUEST['cs_trans_gateway'])].'</td>';
							$json['data']	.= '<td width="5%">'.$currency_sign.$cs_trans_amount.'</td>';
							$json['data']	.= '<td width="5%">';
							$json['data']	.= '<select name="cs_filter_gateways" class="cs_filter_gateways" data-key="'.$transaction_id.'">';
							$json['data']	.= '<option value="approved" '.$approved_status.'>'.__('Approved','rental').'</option>';
							$json['data']	.= '<option value="pending" '.$pending_status.'>'.__('Pending','rental').'</option>';
						    $json['data']	.= '</select>';
							$json['data']	.= '</td>';
							$json['data']	.= '<td width="10%">'.date_i18n( get_option( 'date_format' ), strtotime( date('Y-m-d H:i:s') ) ).'</td>';
							$json['data']	.= '<td width="20%">'.$cs_trans_address.'</td>';
							$json['data']	.= '</tr>';
						
						update_option( 'cs_transactions',$cs_all_transactions );
						$json['type']		= 'success';
						$json['message']	= __('Transaction Added.','rental');
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
		 *@add New Transaction
		 *
		 */
		public function cs_add_transaction_form(){
			global $post, $cs_form_fields, $gateways, $cs_plugin_options;
			
			$payment_geteways = array();
			$payment_geteways[''] = __('Select Payment Gateway', 'rental');
			
			foreach( $gateways as $key => $value ) {
				$status	= $cs_plugin_options[strtolower($key).'_status'];
				if( isset( $status ) && $status	== 'on' ) {		
					$payment_geteways[strtolower($key)]	= $value;
				}
			}
			
			?>

            <div id="cs_transactions_pop"  style="display:none">
                <div class="cs-heading-area">
                    <h5><i class="icon-plus-circle"></i><?php _e('Add Transaction','rental');?></h5>
                    <span class="cs-btnclose" onClick="javascript:cs_remove_overlay('cs_transactions_pop','append')"> <i class="icon-times"></i></span>
                </div>
                <div class="transaction-form">
                <div class="message-wrap" style="display:none">
                    <div class="cs-message updated"></div>
                </div>
					<?php
                        $cs_form_fields->cs_form_text_render(
                                array(  
                                    'name'	=> __('Transaction Id', 'rental'),
                                    'id'	=> 'trans_id',
                                    'classes' => '',
                                    'std'	=> '',
                                    'description'  => '',
                                    'hint'  => ''
                                )
                        );
                        
                        $cs_form_fields->cs_form_text_render(
                                array(  
                                    'name'	=> __('Booking Id', 'rental'),
                                    'id'	=> 'booking_id',
                                    'classes' => '',
                                    'std'	=> '',
                                    'description'  => '',
                                    'hint'  => ''
                                )
                        );
                        
                        $cs_form_fields->cs_form_text_render(
                              array(  'name'	=> __('Email', 'rental'),
                                      'id'	=> 'trans_email',
                                      'classes' => '',
                                      'std'	=> '',
                                      'description'  => '',
                                      'hint'  => '',
                                      'return'  => false
                                  )
                          );
						
                        $cs_form_fields->cs_form_text_render(
                              array(  'name'	=> __('First Name', 'rental'),
                                      'id'	=> 'trans_first_name',
                                      'classes' => '',
                                      'std'	=> '',
                                      'description'  => '',
                                      'hint'  => '',
                                      'return'  => false
                                  )
                          );
                        $cs_form_fields->cs_form_text_render(
                              array(  'name'	=> __('Last Name', 'rental'),
                                      'id'	=> 'trans_last_name',
                                      'classes' => '',
                                      'std'	=> '',
                                      'description'  => '',
                                      'hint'  => '',
                                      'return'  => false
                                  )
                          );
                        $cs_form_fields->cs_form_textarea_render(
                              array(  'name'	=> __('Address', 'rental'),
                                      'id'	=> 'trans_address',
                                      'classes' => '',
                                      'std'	=> '',
                                      'description'  => '',
                                      'hint'  => '',
                                      'return'  => false
                                  )
                          );
                        
                        $cs_form_fields->cs_form_text_render(
                            array(  'name'	=> __('Gross Amount', 'rental'),
                                    'id'	=> 'trans_amount',
                                    'classes' => '',
                                    'std'	=> '',
                                    'description'  => '',
                                    'hint'  => ''
                                )
                        );
                        
                        $cs_form_fields->cs_form_select_render(
                            array(  'name'	=> __('Payment Gateway', 'rental'),
                                    'id'	=> 'trans_gateway',
                                    'classes' => '',
                                    'std'	=> '',
                                    'description'  => '',
                                    'options'  => $payment_geteways,
                                    'hint'  => ''
                                )
                        );
                        
                        $cs_form_fields->cs_form_select_render(
                            array(  'name'	=> __('Status', 'rental'),
                                    'id'	=> 'trans_status',
                                    'classes' => '',
                                    'std'	=> '',
                                    'description'  => '',
                                    'options'  => array('pending'=>__('Pending', 'rental'), 'approved'=>__('Approved', 'rental')),
                                    'hint'  => ''
                                )
                        );
						$cs_form_fields->cs_form_button_render(
							array(  'name'	=> __('Add Transaction','rental'),
									'id'	=> 'add_transaction',
									'classes' => '',
									'std'	=>__('Add Transaction', 'rental'),
									'description'  => '',
									'hint'  => ''
								)
						);
                    ?>
                </div>
			</div>
            <?php
		}
		
		public function customer_fields_save() {
			
			$this->cs_update_customers();
			echo __("All Customer Saved", "rental");
			
			die();
		}
		
	}
	new cs_transactions_options();
}

