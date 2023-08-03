<?php
/**
 *  File Type: Payemnts Base Class
 *
 */
 
if( ! class_exists( 'CS_PAYMENTS' ) ) {
	class CS_PAYMENTS{
		
		public $gateways;
		
		public function __construct()
		{
			global $gateways;
			//$gateways['class name']							= 'Gate ways name'
			$gateways['CS_PAYPAL_GATEWAY']						= 'Paypal';
			$gateways['CS_AUTHORIZEDOTNET_GATEWAY']				= 'Authorize.net';
			$gateways['CS_PRE_BANK_TRANSFER']					= 'Bank Transfer';
			//$gateways['CS_2CHECKOUT_GATEWAY']					= '2Checkout';
			$gateways['CS_SKRILL_GATEWAY']						= 'Skrill';
		}
		
		public function cs_general_settings(){
			global $cs_settings;
			
			$cs_currencuies	= cs_get_currency();
			foreach($cs_currencuies as $key => $value ){
				$currencies[$key] = $value['name'].'-'.$value['code'];
			}
			
			$cs_settings[] = array( "name" 			=> __("Select Currency", "rental"),
									"desc" 			=> "",
									"hint_text" 	=> __("Select Currency", "rental"),
									"id" 			=> "cs_currency_type",
									"std" 			=> "USD",
									"type" 			=> "select_values",
									"options" 		=> $currencies
									);
									
			$cs_settings[] = array( "name" 			=> __("Currency Sign", "rental"),
									"desc" 			=> "",
									"hint_text" 	=> __("Use Currency Sign eg: &pound;,&yen;", "rental"),
									"id" 			=> "currency_sign",
									"std" 			=> "$",
									"type" 			=> "text");
			return $cs_settings;
		}
		
		public function cs_get_string($length = 3) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, strlen($characters) - 1)];
			}
			return $randomString;
		}
		
		public function cs_add_transaction( $fields = array() ){
			global $cs_plugin_options;
			
			define("DEBUG", 1);
			define("USE_SANDBOX", 1);
			define("LOG_FILE", "./ipn.log");
			include_once('../../../../wp-load.php');
			
			if( is_array($fields) ) {
				foreach($fields as $key => $value){
					update_post_meta((int)$fields['cs_transaction_id'], "$key", $value);
				}
			}
			
			return true;
		}
		
	}
}
