<?php
/**
 *  File Type: Pre Bank Transfer
 *
 */
 
if( ! class_exists( 'CS_PRE_BANK_TRANSFER' ) ) {
	class CS_PRE_BANK_TRANSFER{
		
		public function __construct()
		{
			global $cs_plugin_options;
			//$cs_plugin_options	= get_option('cs_plugin_options');
		}
		
		public function settings($cs_gateways_id = ''){
			global $post;
			
			$cs_rand_id = CS_FUNCTIONS()->cs_rand_id();
			
			$on_off_option =  array("show" => "on","hide"=>"off"); 
			
			$cs_settings[] = array(
								"type" => "acc_panel_start",
							);
			
			$cs_settings[] = array("name" => __("Bank Transfer Settings", "rental"),
											"id" => "tab-heading-options",
											"std" => __("Bank Transfer Settings", "rental"),
											"type" => "section",
											"accordion" => false,
											"id" => "$cs_rand_id",
											"parrent_id" => "$cs_gateways_id",
											"active" => false,
										);
										
			$cs_settings[] = array(
								"type" => "acc_cont_start",
								"rand" => "$cs_rand_id",
								"active" => false,
							);
										
			$cs_settings[] = array( "name" 		=> __("Custom Logo", "rental"),
									"desc" 		=> "",
									"hint_text" => "",
									"id" 		=> "cs_pre_bank_transfer_logo",
									"std" 		=>  wp_car_rental::plugin_url().'payments/images/bank.jpg',
									"display"	=>"none",
									"type" 		=> "upload logo"
								);
								
			$cs_settings[] = array( "name" 		=> __("Default Status", "rental"),
                            "desc" 				=> "",
                            "hint_text" 		=> __("Show/Hide Gateway On Front End.","rental"),
                            "id" 				=> "cs_pre_bank_transfer_status",
                            "std" 				=> "on",
                            "type" 				=> "checkbox",
                            "options" 			=> $on_off_option
                        );
			$cs_settings[] = array( "name" 		=> __("Bank Information", "rental"),
                            "desc" 				=> "",
                            "hint_text" 		=> __("Enter the bank name to which you want to transfer payment", "rental"),
                            "id" 				=> "cs_bank_information",
                            "std" 				=> "",
                            "type" 				=> "text"
                        );
			$cs_settings[] = array( "name" 		=> __("Account Number", "rental"),
                            "desc" 				=> "",
                            "hint_text" 		=> __("Enter your bank Account Id", "rental"),
                            "id" 				=> "cs_bank_account_id",
                            "std" 				=> "",
                            "type" 				=> "text"
                        ); 
			$cs_settings[] = array( "name" 		=> __("Other Information", "rental"),
                            "desc" 				=> "",
                            "hint_text" 		=> __("Enter your bank Other Information.", "rental"),
                            "id" 				=> "cs_other_information",
                            "std" 				=> "",
                            "type" 				=> "textarea"
                        );
						 
			$cs_settings[] = array(
								"type" => "elem_end",
							);
			$cs_settings[] = array(
								"type" => "elem_end",
							);
			$cs_settings[] = array(
								"type" => "elem_end",
							);
						
			return $cs_settings;
		}
		
		public function cs_proress_request( $params = '' ){
			global $post, $cs_plugin_options, $cs_plugin_options;
			
			extract( $params );

			$currency_sign	= isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] !='' ? $cs_plugin_options['currency_sign'] : '$';
			
			$cs_booking  			= get_post_meta($order_id, 'cs_booked_vehicle_data', false);
			$cs_bkng_gross_total	= get_post_meta((int)$order_id,'cs_bkng_gross_total',true);
			$vat_percentage			= get_post_meta((int)$order_id,'cs_bkng_vat_percentage',true);
			$cs_bkng_tax			= get_post_meta((int)$order_id,'cs_bkng_tax',true);
			$grand_total			= get_post_meta( $order_id , 'cs_bkng_grand_total', true );
			$cs_bkng_advance		= get_post_meta( $order_id , 'cs_bkng_advance', true );
			$cs_booked_vehicle_id	= get_post_meta( $order_id , 'cs_booked_vehicle_id', true );
			$cs_booking				= $cs_booking[0];
			
			$cs_bking_type = isset( $_POST['payment_type'] ) ? $_POST['payment_type'] : '';
									
			$cs_bank_transfer	= '<div class="cs-bank-transfer">';
				$cs_bank_transfer	.= '<h3>'.__('Order detail','rental').'</h3>';
				
				if( isset( $cs_plugin_options['cs_bank_information'] ) && $cs_plugin_options['cs_bank_information'] !='' ) {
					 $cs_bank_transfer	.= '<div class="widget-holder">';
					 $cs_bank_transfer	.= '<h4>'.__('Bank Information','rental').'</h4>';
					 $cs_bank_transfer	.= '<div class="price-bar"> <span>'.$cs_plugin_options['cs_bank_information'].'</span></div>';
					 $cs_bank_transfer	.= '</div>';
				 }
				 
				 if( isset( $cs_plugin_options['cs_bank_account_id'] ) && $cs_plugin_options['cs_bank_account_id'] !='' ) {
					 $cs_bank_transfer	.= '<div class="widget-holder">';
					 $cs_bank_transfer	.= '<h4>'.__('Account No','rental').'</h4>';
					 $cs_bank_transfer	.= '<div class="price-bar"> <span>'.$cs_plugin_options['cs_bank_account_id'].'</span></div>';
					 $cs_bank_transfer	.= '</div>';
				 }
				 
				 if( isset( $cs_plugin_options['cs_other_information'] ) && $cs_plugin_options['cs_other_information'] !='' ) {
					 $cs_bank_transfer	.= '<div class="widget-holder">';
					 $cs_bank_transfer	.= '<h4>'.__('Other Information','rental').'</h4>';
					 $cs_bank_transfer	.= '<div class="price-bar"> <span>'.$cs_plugin_options['cs_other_information'].'</span></div>';
					 $cs_bank_transfer	.= '</div>';
				 }
				
				$cs_bank_transfer	.= '<div class="widget-holder">';
				$cs_bank_transfer	.= '<ul class="price-list">';
					
					$cs_bank_transfer	.= '<li style="border:0px"><i class="icon-check-circle"></i>'.__('Booking Id','rental').'<span>#'.$cs_booking_id.'</span></li>';

					if( isset( $cs_booking ) && is_array( $cs_booking ) && !empty( $cs_booking ) ) {
							$counter	= 0;
							foreach( $cs_booking as $key => $data ){
								$counter++;
								$cs_bank_transfer	.= '<li><i class="icon-check-circle"></i>'.__('Booked Vehicle','rental').'<span>'. get_the_title( $cs_booked_vehicle_id ).' #'.$data['key'].'</span></li>';
                     	}
					}
					
					$cs_bank_transfer	.= '<li><i class="icon-check-circle"></i>'. __('Gross Total','rental').'<span>'.$currency_sign.number_format((float) $cs_bkng_gross_total,2 ).'</span>';
					
					$cs_bank_transfer	.= '<li><i class="icon-check-circle"></i>'. __('VAT','rental').'<span>'. $currency_sign.number_format((float) $cs_bkng_tax,2 ).'</span>('.$vat_percentage.'%)</li>';
					
					if( $cs_bking_type == 'deposit' && $cs_bkng_advance > 0 ) {
						
						$cs_blnc_amount = (float)$grand_total - (float)$cs_bkng_advance;
						$cs_bank_transfer	.= '<li><i class="icon-check-circle"></i>'. __('Total','rental').'<span>'.$currency_sign.number_format((float) $grand_total,2 ).'</span></li>';
						$cs_bank_transfer	.= '<li><i class="icon-check-circle"></i>'. __('Payable','rental').'<span>'.$currency_sign.number_format((float) $cs_bkng_advance,2 ).'</span></li>';
						$cs_bank_transfer	.= '<li style="border:0px"><i class="icon-check-circle"></i>'. __('Balance','rental').'<span>'.$currency_sign.number_format((float) $cs_blnc_amount,2 ).'</span></li>';
					} else {
						$cs_bank_transfer	.= '<li><i class="icon-check-circle"></i>'. __('Grand Total','rental').'<span>'.$currency_sign.number_format((float) $grand_total,2 ).'</span></li>';
					}
					
					
				$cs_bank_transfer	.= '</ul>';

			return force_balance_tags($cs_bank_transfer);

		}
	}
}