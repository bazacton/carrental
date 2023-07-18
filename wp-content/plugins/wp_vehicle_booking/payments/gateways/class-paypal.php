<?php
/**
 *  File Type: Paypal Gateway
 *
 */

if( ! class_exists( 'CS_PAYPAL_GATEWAY' ) ) {
	class CS_PAYPAL_GATEWAY extends CS_PAYMENTS {
		
		public function __construct(){
			global $cs_plugin_options;
			
			//$cs_plugin_options	= get_option('cs_plugin_options');
			
			if( isset( $cs_plugin_options['cs_paypal_sandbox'] ) && $cs_plugin_options['cs_paypal_sandbox'] == 'on'){
				$this->gateway_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
			} else {
				$this->gateway_url = "https://www.paypal.com/cgi-bin/webscr";
			}
			
			$this->listner_url	= isset( $cs_plugin_options['dir_paypal_ipn_url'] ) ? $cs_plugin_options['dir_paypal_ipn_url'] : '';
			
		}
		
		public function settings( $cs_gateways_id = '' ){
			global $post;
						
			$cs_rand_id = CS_FUNCTIONS()->cs_rand_id();
			
			$on_off_option =  array("show" => "on","hide"=>"off");
			
			$cs_settings[] = array(
								"type" => "acc_panel_start",
							);
			
			$cs_settings[] = array("name" => __("Paypal Settings", "rental"),
											"id" => "tab-heading-options",
											"std" => __("Paypal Settings", "rental"),
											"type" => "section",
											"accordion" => false,
											"id" => "$cs_rand_id",
											"parrent_id" => "$cs_gateways_id",
											"active" => true,
										);
										
			$cs_settings[] = array(
								"type" => "acc_cont_start",
								"rand" => "$cs_rand_id",
								"active" => true,
							);
										
			$cs_settings[] = array( "name" 		=> __("Custom Logo", "rental"),
									"desc" 		=> "",
									"hint_text" => "",
									"id" 		=> "cs_paypal_gateway_logo",
									"std" 		=>  wp_car_rental::plugin_url().'payments/images/paypal.jpg',
									"display"	=>"none",
									"type" 		=> "upload logo"
								);
							
			$cs_settings[] = array( "name" 		=> __("Default Status", "rental"),
									"desc" 				=> "",
									"hint_text" 		=> __("Show/Hide Gateway On Front End.", "rental"),
									"id" 				=> "cs_paypal_gateway_status",
									"std" 				=> "on",
									"type" 				=> "checkbox",
									"options" 			=> $on_off_option
								); 
			
			$cs_settings[] = array( "name" 		=> __("Paypal Sandbox", "rental"),
									"desc" 				=> "",
									"hint_text" 		=> __("Only for Developer use.", "rental"),
									"id" 				=> "cs_paypal_sandbox",
									"std" 				=> "on",
									"type" 				=> "checkbox",
									"options" 			=> $on_off_option
								);  
						
			$cs_settings[] = array( "name" 	=> __("Paypal Business Email", "rental"),
									"desc" 		=> "",
									"hint_text" => "",
									"id" 		=> "paypal_email",
									"std" 		=> "",
									"type" 		=> "text"
								);
								
			$ipn_url = wp_car_rental::plugin_url().'payments/listner.php';
			$cs_settings[] = array( "name" 	=> __("Paypal Ipn Url", "rental"),
									"desc" 		=> $ipn_url,
									"hint_text" =>__("Do not edit this Url","rental"),
									"id"		=> "dir_paypal_ipn_url",
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
			$business_email 		= $cs_plugin_options['paypal_email'];
			
			
			$currency				= isset( $cs_plugin_options['cs_currency_type'] ) && $cs_plugin_options['cs_currency_type'] !='' ? $cs_plugin_options['cs_currency_type'] : 'USD';
			$cs_page_id				= isset( $cs_plugin_options['cs_reservation'] ) && $cs_plugin_options['cs_reservation'] !='' && absint($cs_plugin_options['cs_reservation']) ? $cs_plugin_options['cs_reservation'] : '';
			$cancel_url   			= add_query_arg( array('action'=>'search' ),  esc_url( get_permalink( $cs_page_id ) ) );
			$return_url   			= add_query_arg( array('action'=>'booking&invoice='.$order_id ),  esc_url( get_permalink( $cs_page_id ) ) );
			
			$output .= '<form name="PayPalForm" id="direcotry-paypal-form" action="'.$this->gateway_url.'" method="post">  
							<input type="hidden" name="cmd" value="_xclick">  
							<input type="hidden" name="business" value="'.sanitize_email($business_email).'">
							<input type="hidden" name="amount" value="'.number_format( $price,2 ).'">
							<input type="hidden" name="currency_code" value="'.$currency.'">
							<input type="hidden" name="item_name" value="'.$item_name.'"> 
							<input type="hidden" name="item_number" value="'.sanitize_text_field($order_id).'">  
							<input name="cancel_return" value="'.$cancel_url.'" type="hidden">  
							<input type="hidden" name="no_note" value="1">  
							<input type="hidden" name="invoice" value="'.sanitize_text_field($order_id).'">  
							<input type="hidden" name="notify_url" value="'.esc_url( $this->listner_url ).'">
							<input type="hidden" name="lc">
							<input type="hidden" name="rm" value="2">
							<input type="hidden" name="custom" value="'.sanitize_text_field($order_id).'">  
							<input type="hidden" name="return" value="'.$return_url.'">  
						</form>';
							
			$data	 = CS_FUNCTIONS()->cs_special_chars( $output );
			$data	.= '<script>
							jQuery("#direcotry-paypal-form").submit();
						</script>';
			return 	$data;					
		}
	}
 
}


