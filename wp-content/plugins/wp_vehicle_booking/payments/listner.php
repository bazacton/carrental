<?php
include_once('../../../../wp-load.php');
include_once('../helpers/emails/email-helper.php');

/**
 * params (String)
 * Update Transaction
 *
 */
function cs_update_transaction( $params = array() ){
	extract( $params );
	$cs_new_transaction	= array();
	$cs_transactions	= get_option('cs_transactions');
	
	if( isset( $cs_transactions ) && ! is_array( $cs_transactions ) ) {
		$cs_transactions	= array();
	}
	
	$cs_new_transaction[$cs_trans_id]['cs_trans_id']			= $cs_trans_id;	
	$cs_new_transaction[$cs_trans_id]['cs_booking_id']			= $cs_booking_id;	
	$cs_new_transaction[$cs_trans_id]['cs_trans_email']			= $cs_trans_email;	
	$cs_new_transaction[$cs_trans_id]['cs_trans_first_name']	= $cs_trans_first_name;	
	$cs_new_transaction[$cs_trans_id]['cs_trans_last_name']		= $cs_trans_last_name;	
	$cs_new_transaction[$cs_trans_id]['cs_trans_address']		= $cs_trans_address;	
	$cs_new_transaction[$cs_trans_id]['cs_trans_amount']		= $cs_trans_amount;	
	$cs_new_transaction[$cs_trans_id]['cs_trans_gateway']		= $cs_trans_gateway;	
	$cs_new_transaction[$cs_trans_id]['cs_trans_status']		= $cs_trans_status;
	$cs_new_transaction[$cs_trans_id]['cs_trans_date']			= date('Y-m-d H:i:s');
	$cs_new_transaction[$cs_trans_id]['cs_trans_currency']		= $cs_trans_currency;

	$cs_all_transactions	= array_merge($cs_transactions,$cs_new_transaction);
	update_option( 'cs_transactions', $cs_all_transactions );
}
/**
 * params (Array)
 * Order Confirmation
 *
 */
function cs_order_confirmation( $booking_id = '' ){
	global $post, $cs_plugin_options , $cs_theme_option;
	$emails	= new cs_email_helper();
	$params	= array();
	//$cs_theme_options 			= get_option('cs_theme_options');
	//$cs_plugin_options 			= get_option('cs_plugin_options');	
	$cs_vehicle_type			= get_option('cs_type_options');
	$cs_vehicle_type_id			= get_post_meta((int)$booking_id,'cs_vehicle_type',true);
	$params['admin_email']		= get_option( 'admin_email' );
	
	if( isset( $cs_vehicle_type_id ) && $cs_vehicle_type_id !='' ){
		$params['cs_type_name']			= isset($cs_vehicle_type[$cs_vehicle_type_id]['cs_type_name']) ? $cs_vehicle_type[$cs_vehicle_type_id]['cs_type_name'] : '';
		$params['booking_email']		= isset($cs_plugin_options['cs_confir_email']) ? $cs_plugin_options['cs_confir_email'] : 'example@example.com';
	}
	
	$params['logo']				= isset($cs_theme_option['cs_custom_logo']) ? $cs_theme_option['cs_custom_logo'] : '';
	$params['booking_id']		= get_post_meta((int)$booking_id,'cs_booking_id',true);
	$params['order_id']			= get_post_meta((int)$booking_id,'cs_invoice',true);
	
	$params['total_days']		= get_post_meta((int)$booking_id,'cs_booking_num_days',true);
	$params['grand_total']		= get_post_meta((int)$booking_id,'cs_bkng_grand_total',true);
	$params['advance']			= get_post_meta((int)$booking_id,'cs_bkng_advance',true);
	$params['gross_total']		= get_post_meta((int)$booking_id,'cs_bkng_gross_total',true);
	$params['check_in_date']	= get_post_meta((int)$booking_id,'cs_check_in_date',true);
	$params['check_out_date']	= get_post_meta((int)$booking_id,'cs_check_out_date',true);
	$params['cs_pickup_time']	= get_post_meta((int)$booking_id,'cs_pickup_time',true);
	$params['cs_dropup_time']	= get_post_meta((int)$booking_id,'cs_dropup_time',true);
	$params['guest']			= get_post_meta((int)$booking_id,'cs_select_guest',true);
	$params['bkng_tax']			= get_post_meta((int)$booking_id,'cs_bkng_tax',true);
	$params['cs_bkng_advance']			= get_post_meta((int)$booking_id,'cs_bkng_advance',true);
	$params['cs_bkng_remaining']		= get_post_meta((int)$booking_id,'cs_bkng_remaining',true);
	$params['vat_percentage']		= get_post_meta((int)$booking_id,'cs_bkng_vat_percentage',true);
	$params['cs_booked_vehicle_data']	= get_post_meta((int)$booking_id,'cs_booked_vehicle_data',false);
	$params['cs_booked_vehicle']		= get_post_meta((int)$booking_id,'cs_booked_vehicle',false);
	$params['cs_booking_extras']		= get_post_meta((int)$booking_id,'cs_booking_extras',false);
	$params['cs_extras_price']			= get_post_meta((int)$booking_id,'cs_extras_price',false);
	
	$params['payment_type']		= get_post_meta((int)$booking_id,'cs_payment_type',true);
	$params['email_to']			= get_post_meta((int)$booking_id,'cs_email',true);
	$params['first_name']		= get_post_meta((int)$booking_id,'cs_f_name',true);
	$params['last_name']		= get_post_meta((int)$booking_id,'cs_l_name',true);
	$params['cs_gateway']		= get_post_meta((int)$booking_id,'cs_gateway',true);
	$params['cs_address']		= get_post_meta((int)$booking_id,'cs_address',true);
	
	$emails	= new cs_email_helper();
		
	$emails->cs_order_confirmation( $params ); // Order Confirmation Email
}

$to      = 'sadad@abc.com';
$subject = 'the subject';
$message = 'sadsadadas';
$headerss = 'From: webmaster@example.com' . "\r\n" .
	'Reply-To: webmaster@example.com' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headerss);

/**
 * params (String)
 * Update Booking
 *
 */
function cs_update_post( $id = '' ){
	if( isset ($id) ) {
		$order_id	= update_post_meta((int)$id,'cs_booking_status','confirmed');
	}
}

//Build the data to post back to Paypal
$postback = 'cmd=_notify-validate'; 
// go through each of the posted vars and add them to the postback variable
foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$postback .= "&$key=$value";
}
	
$ourFileName = "debug1_postdata.txt";
$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
fwrite($ourFileHandle, $postback);
fclose($ourFileHandle);
	
	
/*
 * Paypal Gateway Listner
 */
 
if ( isset( $_POST['payment_status'] ) && $_POST['payment_status'] == 'Completed' ) {
	
	$booking_id = $_POST['item_number'];
	
	if(isset($booking_id) && $booking_id  != '' ){
		if(isset($_POST['txn_id']) && $_POST['txn_id'] <> ''){
			
			$transaction_array = array();
		
			$transaction_array['cs_trans_id']				= esc_attr($_POST['txn_id']);
			$transaction_array['cs_booking_id']				= get_post_meta((int)$booking_id,'cs_booking_id',true);
			$transaction_array['cs_trans_date']				= date('Y-m-d H:i:s');
			$transaction_array['cs_trans_status']			= 'approved';
			$transaction_array['cs_trans_address']			= esc_attr($_POST['address_street']).' '.esc_attr($_POST['address_city']).' '.esc_attr($_POST['address_country']);
			$transaction_array['cs_trans_amount']			= esc_attr($_POST['payment_gross']);
			$transaction_array['cs_trans_gateway']			= 'cs_paypal_gateway';
			$transaction_array['cs_trans_currency']			= esc_attr($_POST['mc_currency']);			
			
			
			if( esc_attr($_POST['payer_email'] == '' ) ) {
				$transaction_array['cs_trans_email']	= get_post_meta((int)$booking_id,'cs_email',true);
			} else {
				$transaction_array['cs_trans_email']	= esc_attr($_POST['payer_email']);
			}
			
			if( esc_attr($_POST['first_name'] == '' ) ) {
				$transaction_array['cs_trans_first_name']	= get_post_meta((int)$booking_id,'cs_f_name',true);
			} else {
				$transaction_array['cs_trans_first_name']	= esc_attr($_POST['first_name']);
			}
			
			if( esc_attr($_POST['last_name'] == '' ) ) {
				$transaction_array['cs_trans_last_name']	= get_post_meta((int)$booking_id,'cs_l_name',true);
			} else{
				$transaction_array['cs_trans_last_name']	= esc_attr($_POST['last_name']);
			}

			cs_update_transaction( $transaction_array ); // Update Transaction
			cs_update_post( $booking_id ); //Update Booking
				
			//Email Params
			cs_order_confirmation( $booking_id );
			
		}
	}
}

/*
 * Authorize Gateway Listner
 */
if ( isset( $_POST['x_response_code'] ) && $_POST['x_response_code'] == '1' ) {
	
	$booking_id = $_POST['x_cust_id'];
	
	if(isset($booking_id) && $booking_id != ''){
			$transaction_array = array();
			$transaction_array['cs_trans_id']				= esc_attr($_POST['x_trans_id']);
			$transaction_array['cs_booking_id']				= get_post_meta((int)$booking_id,'cs_booking_id',true);
			$transaction_array['cs_trans_date']				= date('Y-m-d H:i:s');
			$transaction_array['cs_trans_status']			= 'approved';
			$transaction_array['cs_trans_address']			= get_post_meta((int)$booking_id,'cs_address',true);
			$transaction_array['cs_trans_amount']			= esc_attr($_POST['x_amount']);
			$transaction_array['cs_trans_gateway']			= 'cs_authorizedotnet_gateway';
			$transaction_array['cs_trans_currency']			= 'USD';			
			
			
			if( esc_attr($_POST['x_email'] == '' ) ) {
				$transaction_array['cs_trans_email']	= get_post_meta((int)$booking_id,'cs_email',true);
			} else {
				$transaction_array['cs_trans_email']	= esc_attr($_POST['x_email']);
			}
			
			if( esc_attr($_POST['x_first_name'] == '' ) ) {
				$transaction_array['cs_trans_first_name']	= get_post_meta((int)$booking_id,'cs_f_name',true);
			} else {
				$transaction_array['cs_trans_first_name']	= esc_attr($_POST['x_first_name']);
			}
			
			if( esc_attr($_POST['x_last_name'] == '' ) ) {
				$transaction_array['cs_trans_last_name']	= get_post_meta((int)$booking_id,'cs_l_name',true);
			} else{
				$transaction_array['cs_trans_last_name']	= esc_attr($_POST['x_last_name']);
			}

			cs_update_transaction( $transaction_array ); // Update Transaction
			cs_update_post( $booking_id ); //Update Booking
	}
}

/*
 * Skrill Gateway Listner
 */

if( isset( $_POST['merchant_id'] ) ) {
	// Validate the Moneybookers signature
	$concatFields = $_POST['merchant_id']
		.$_POST['order_id']
		.strtoupper(md5('Paste your secret word here'))
		.$_POST['mb_amount']
		.$_POST['mb_currency']
		.$_POST['status'];
	
	//$cs_plugin_options	= get_option('cs_plugin_options');
	
	$MBEmail = $cs_plugin_options['skrill_email'];
	
	// Ensure the signature is valid, the status code == 2,
	// and that the money is going to you
	if ( isset( $_POST['status'] ) && $_POST['status'] == '2' && trim( $_POST['pay_to_email'] ) == trim( $MBEmail ) )
	{
		$data 		= explode('||',$_POST['transaction_id']);
		$order_id	= $data[0];
		$booking_id	= $data[1];
		
		if(isset($booking_id) && $booking_id != ''){
			
			$transaction_array = array();
			$transaction_array['cs_trans_id']				= esc_attr($_POST['mb_transaction_id']);
			$transaction_array['cs_booking_id']				= get_post_meta((int)$booking_id,'cs_booking_id',true);
			$transaction_array['cs_trans_date']				= date('Y-m-d H:i:s');
			$transaction_array['cs_trans_status']			= 'approved';
			$transaction_array['cs_trans_amount']			= esc_attr($_POST['amount']);
			$transaction_array['cs_trans_gateway']			= 'cs_skrill_gateway';
			$transaction_array['cs_trans_currency']			= $_POST['currency'];			
			
			
			if( esc_attr($_POST['pay_from_email'] == '' ) ) {
				$transaction_array['cs_trans_email']	= get_post_meta((int)$booking_id,'cs_email',true);
			} else {
				$transaction_array['cs_trans_email']	= esc_attr($_POST['pay_from_email']);
			}
			
			$transaction_array['cs_trans_first_name']		= get_post_meta((int)$booking_id,'cs_f_name',true);
			$transaction_array['cs_trans_last_name']		= get_post_meta((int)$booking_id,'cs_l_name',true);
			$transaction_array['cs_trans_address']			= get_post_meta((int)$booking_id,'cs_address',true);

			cs_update_transaction( $transaction_array ); // Update Transaction
			cs_update_post( $booking_id ); //Update Booking

		}
	
	}else{
		// -2 == Order Pending
	}
}