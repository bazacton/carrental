<?php
/**
 *  File Type: Skrill- Monery Booker Gateway
 *
 */
 
if( ! class_exists( 'CS_SKRILL_GATEWAY' ) ) {
	class CS_SKRILL_GATEWAY extends CS_PAYMENTS {
		
		public function __construct()
		{
			global $cs_plugin_options;
			//$cs_plugin_options	= get_option('cs_plugin_options');
			
			$this->gateway_url  = "https://www.moneybookers.com/app/payment.pl";
			$this->listner_url	= isset( $cs_plugin_options['cs_skrill_ipn_url'] ) ? $cs_plugin_options['cs_skrill_ipn_url'] : '';

		}
		
		public function settings($cs_gateways_id = ''){
			global $post;
			
			$cs_rand_id = CS_FUNCTIONS()->cs_rand_id();
			
			$on_off_option =  array("show" => "on","hide"=>"off"); 
			

			$cs_settings[] = array("name" => __("Skrill-Money Booker Settings", "rental"),
											"id" => "tab-heading-options",
											"std" => __("Skrill-Money Booker Settings", "rental"),
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
									"id" 		=> "cs_skrill_gateway_logo",
									"std" 		=> wp_car_rental::plugin_url().'payments/images/skrill.jpg',
									"display"	=> "none",
									"type" 		=> "upload logo"
								);
								
			$cs_settings[] = array( "name" 		=> __("Default Status", "rental"),
                            "desc" 				=> "",
                            "hint_text" 		=> __("Show/Hide Gateway On Front End.", "rental"),
                            "id" 				=> "cs_skrill_gateway_status",
                            "std" 				=> "on",
                            "type" 				=> "checkbox",
                            "options" 			=> $on_off_option
                        ); 
             
			$cs_settings[] = array( "name" 	=> __("Skrill-Money Booker Business Email", "rental"),
								"desc" 		=> "",
								"hint_text" => "",
								"id" 		=>   "skrill_email",
								"std" 		=> "",
								"type" 		=> "text"
							);
							
			$ipn_url = wp_car_rental::plugin_url().'payments/listner.php';
			$cs_settings[] = array( "name" 	=> __("Skrill-Money Booker Ipn Url", "rental"),
								"desc" 		=> $ipn_url,
								"hint_text" => __("Do not edit this Url", "rental"),
								"id"		=> "cs_skrill_ipn_url",
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
			$business_email 		= $cs_plugin_options['skrill_email'];

			$currency				= isset( $cs_plugin_options['cs_currency_type'] ) && $cs_plugin_options['cs_currency_type'] !='' ? $cs_plugin_options['cs_currency_type'] : 'USD';
			
			$cs_page_id				= isset( $cs_plugin_options['cs_reservation'] ) && $cs_plugin_options['cs_reservation'] !='' && absint($cs_plugin_options['cs_reservation']) ? $cs_plugin_options['cs_reservation'] : '';
			$cancel_url   			= add_query_arg( array('action'=>'search' ),  esc_url( get_permalink( $cs_page_id ) ) );
			$return_url   			= add_query_arg( array('action'=>'booking&invoice='.$order_id ),  esc_url( get_permalink( $cs_page_id ) ) );
			
			$output .= '<form name="SkrillForm" id="direcotry-skrill-form" action="'.$this->gateway_url.'" method="post">  
							<input type="hidden" name="pay_to_email" value="'.sanitize_email($business_email).'">
							<input type="hidden" name="amount" value="'.number_format( $price,2 ).'">
							<input type="hidden" value="EN" name="language">
							<input type="hidden" value="'.$currency.'" name="currency">
							<input type="hidden" name="detail1_description" value="Vehicle : "> 
							<input type="hidden" name="detail1_text" value="'.get_the_title($order_id).'">
							<input type="hidden" name="detail2_description" value="Vehicle Title "> 
							<input type="hidden" name="detail2_text" value="'.sanitize_text_field(get_the_title($order_id)).'">
							<input type="hidden" name="detail3_description" value="Vehicle ID : ">
							<input type="hidden" name="detail3_text" value="'.$item_name.'">
							<input name="cancel_url" value="'.esc_url( $cancel_url ).'" type="hidden">  
							<input type="hidden" name="status_url" value="'.sanitize_text_field( $this->listner_url ).'">
							<input type="hidden" name="transaction_id" value="'.sanitize_text_field($order_id).'||'.sanitize_text_field($order_id).'">
							<input type="hidden" name="customer_number" value="'.$order_id.'">  
							<input type="hidden" name="return_url" value="'.esc_url( $return_url ).'"> 
							<input type="hidden" name="merchant_fields" value="'.$order_id.','.$order_id.'"> 
						</form>';
							
			$data	 = CS_FUNCTIONS()->cs_special_chars( $output );
			$data	.= '<script>
							jQuery("#direcotry-skrill-form").submit();
					    </script>';
			return 	$data;							
		}
	}
}