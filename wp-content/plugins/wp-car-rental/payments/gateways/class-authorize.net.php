<?php
/**
 *  File Type: Authorize.net Gateway
 
 */
 
if( ! class_exists( 'CS_AUTHORIZEDOTNET_GATEWAY' ) ) {
	class CS_AUTHORIZEDOTNET_GATEWAY  extends CS_PAYMENTS{
		
		public function __construct()
		{
			// Do Something
			global $cs_plugin_options;
			//$cs_plugin_options	= get_option('cs_plugin_options');
			
			if( isset( $cs_plugin_options['cs_authorizenet_sandbox'] ) && $cs_plugin_options['cs_authorizenet_sandbox'] == 'on'){
				$this->gateway_url = "https://test.authorize.net/gateway/transact.dll";
			} else {
				$this->gateway_url = "https://secure.authorize.net/gateway/transact.dll";
			}
			
			$this->listner_url	= isset( $cs_plugin_options['dir_authorizenet_ipn_url'] ) ? $cs_plugin_options['dir_authorizenet_ipn_url'] :'';
			
		}
		
		public function settings($cs_gateways_id = ''){
			global $post;
			
			$cs_rand_id = CS_FUNCTIONS()->cs_rand_id();
			
			$on_off_option =  array("show" => "on","hide"=>"off"); 
			
			$cs_settings[] = array(
								"type" => "acc_panel_start",
							);
			
			$cs_settings[] = array("name" => __("Authorize.net Settings", "rental"),
											"id" => "tab-heading-options",
											"std" => __("Authorize.net Settings", "rental"),
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
									"id" 		=> "cs_authorizedotnet_gateway_logo",
									"std" 		=>  wp_car_rental::plugin_url().'payments/images/athorizedotnet_.jpg',
									"display"	=>"none",
									"type" 		=> "upload logo"
								);
								
			$cs_settings[] = array( "name" 		=> __("Default Status", "rental"),
                            "desc" 				=> "",
                            "hint_text" 		=> __("Show/Hide Gateway On Front End.", "rental"),
                            "id" 				=> "cs_authorizedotnet_gateway_status",
                            "std" 				=> "on",
                            "type" 				=> "checkbox",
                            "options" 			=> $on_off_option
                        ); 
						
			$cs_settings[] = array( "name" 		=> __("Authorize.net Sandbox", "rental"),
                            "desc" 				=> "",
                            "hint_text" 		=> __("Only for Developer use.", "rental"),
                            "id" 				=> "cs_authorizenet_sandbox",
                            "std" 				=> "on",
                            "type" 				=> "checkbox",
                            "options" 			=> $on_off_option
                        );    
                      
			$cs_settings[] = array( "name" 	=> __("Login Id", "rental"),
								"desc" 		=> "",
								"hint_text" => __("This is API Login Id", "rental"),
								"id" 		=>   "authorizenet_login",
								"std" 		=> "",
								"type" 		=> "text"
							);
			
			$cs_settings[] = array( "name" 	=> __("Transaction Key", "rental"),
								"desc" 		=> "",
								"hint_text" => __("API Transaction Key", "rental"),
								"id" 		=> "authorizenet_transaction_key",
								"std" 		=> "",
								"type" 		=> "text"
							);
							
			$ipn_url = wp_car_rental::plugin_url().'payments/listner.php';
			$cs_settings[] = array( "name" 	=> __("Authorize.net Ipn Url", "rental"),
								"desc" 		=> $ipn_url,
								"hint_text" => __("Do not edit this url", "rental"),
								"id"		=>   "dir_authorizenet_ipn_url",
								"std" 		=> $ipn_url,
								"type" 		=> "text"
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
			global $post, $cs_plugin_options;
			extract( $params );
			
			$cs_current_date   		= date('Y-m-d H:i:s');
			$output					= '';
			$rand_id				= $this->cs_get_string(5);
			$cs_login 				= $cs_plugin_options['authorizenet_login'];
			$transaction_key 		= $cs_plugin_options['authorizenet_transaction_key'];
			$timeStamp	= time();
			$sequence	= rand(1, 1000);
			
			if( phpversion() >= '5.1.2' )
				{ $fingerprint = hash_hmac("md5", $cs_login . "^" . $sequence . "^" . $timeStamp . "^" . $price . "^", $transaction_key); }
			else 
				{ $fingerprint = bin2hex(mhash(MHASH_MD5, $cs_login . "^" . $sequence . "^" . $timeStamp . "^" . $price . "^", $transaction_key)); }
			
			$currency				= isset( $cs_plugin_options['cs_currency_type'] ) && $cs_plugin_options['cs_currency_type'] !='' ? $cs_plugin_options['cs_currency_type'] : 'USD';

			$cs_page_id				= isset( $cs_plugin_options['cs_reservation'] ) && $cs_plugin_options['cs_reservation'] !='' && absint($cs_plugin_options['cs_reservation']) ? $cs_plugin_options['cs_reservation'] : '';
			
			$cancel_url   			= add_query_arg( array('action'=>'search' ),  esc_url( get_permalink( $cs_page_id ) ) );
			$return_url   			= add_query_arg( array('action'=>'booking&invoice='.$order_id ),  esc_url( get_permalink( $cs_page_id ) ) );
			
			$output .= '<form name="AuthorizeForm" id="direcotry-authorize-form" action="'.$this->gateway_url.'" method="post">  
							<input type="hidden" name="x_login" value="'.$cs_login.'">
							<input type="hidden" name="x_type" value="AUTH_CAPTURE"/>
							<input type="hidden" name="x_amount" value="'.$price.'">
							<input type="hidden" name="x_fp_sequence" value="'.$sequence.'" />
							<input type="hidden" name="x_fp_timestamp" value="'.$timeStamp.'" />
							<input type="hidden" name="x_fp_hash" value="'.$fingerprint.'" />
							<input type="hidden" name="x_show_form" value="PAYMENT_FORM" />
							<input type="hidden" name="x_invoice_num" value="ORDER-'.sanitize_text_field($order_id).'">
							<input type="hidden" name="x_po_num" value="'.sanitize_text_field($order_id).'">
							<input type="hidden" name="x_cust_id" value="'.sanitize_text_field($order_id).'"/> 
							<input type="hidden" name="x_description" value="'.$item_name.'"> 
							<input type="hidden" name="x_cancel_url" value="'.esc_url( $cancel_url ).'" />
							<input type="hidden" name="x_cancel_url_text" value="Cancel Order" />
							<input type="hidden" name="x_relay_response" value="TRUE" />
							<input type="hidden" name="x_relay_url" value="'.sanitize_text_field( $this->listner_url ).'"/> 
							<input type="hidden" name="x_test_request" value="false"/>
						</form>';
				$data	 = CS_FUNCTIONS()->cs_special_chars( $output );
				$data	.= '<script>
								jQuery("#direcotry-authorize-form").submit();
						    </script>';
			    return 	$data;							
		}
	}
}