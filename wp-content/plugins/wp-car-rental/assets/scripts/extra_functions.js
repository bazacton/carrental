jQuery(document).ready(function($) {	/*$("table.cs-price-plans tr > td").on('click', function() {		var all_fields = $("table.cs-price-plans tr > td");		all_fields.find("input[type='text']").hide();		all_fields.find("small.price_val").show();				$(this).find("input[type='text']").show();		$(this).find("small.price_val").hide();	});*/		/*jQuery("table.cs-price-plans tr > td input[type='text']").on('change', function() {		jQuery(this).next('small.price_val').html(jQuery(this).val());		var admin_url = jQuery("#cs-booking-pricing").data('url');		setTimeout(function() {			jQuery("#cs-booking-pricing input[type='text']").hide();			jQuery("#cs-booking-pricing input[type='text']").next('small.price_val').show();			jQuery("#cs_pricing_popup input[type='text']").show();		}, 1000);	});		$('.vehicle-price-days input[type="text"]').on('change', function() {		var field_val = $(this).val();		if(field_val !== '') {			$(this).next('small.price_val').html(field_val);		}		else {			$(this).next('small.price_val').html(' - ');		}	});*/		jQuery(document).ready(function() {    jQuery(".pickup_date, .pickup_time, .dropup_date, .dropup_time").keydown(function (e) {        // Allow: backspace, delete, tab, escape, enter and .        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 188, 52]) !== -1 ||             // Allow: Ctrl+A, Command+A            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) ||              // Allow: home, end, left, right, down, up            (e.keyCode >= 35 && e.keyCode <= 40)) {                 // let it happen, don't do anything                 return;        }        // Ensure that it is a number and stop the keypress        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {            e.preventDefault();        }    });});		jQuery(".cs_select_vehicle select").on('change', function() {		var admin_url = jQuery("#cs-booking-pricing").data('url');		cs_get_vehicle_cont(jQuery(this).val(), admin_url, jQuery(this).data('type'));	});		jQuery(".cs-remove").on('click', function() {		var admin_url = jQuery("#cs-booking-pricing").data('url');		var confirm_msg = confirm("Are you sure, you want to delete this?");		if( confirm_msg == true ){			var tr_parent = $(this).parent().parent('.plan-header');			tr_parent.hide('slow', function(){				tr_parent.remove();				price_option_save(admin_url,'');			});		}	});		// Offers	jQuery(".offer-delete").on('click', function() {		var confirm_msg = confirm("Are you sure, you want to delete this?");		if( confirm_msg == true ){			var tr_parent = $(this).parent().parent('tr');			tr_parent.hide('slow', function(){				tr_parent.remove();			});		}	});		// Customers	jQuery(document).ready(function() {			jQuery("#add_customer_to_btn").on('click','#cs_edit_customer', function(){			var _this	= jQuery(this);			var id		= _this.data('id');			var admin_url = jQuery("#cs-booking-customers").data('url');			var $container	= _this.parents('tr');			var cs_customer_name			= $container.find("#cs_customer_name option:selected").text();			var cs_customer_first_name		= $container.find("#cs_customer_first_name").val();			var cs_customer_last_name		= $container.find("#cs_customer_last_name").val();			var cs_customer_phone			= $container.find('#cs_customer_phone').val();			var cs_customer_email			= $container.find("#cs_customer_email").val();			var cs_customer_address			= $container.find("#cs_customer_address").val();			var cs_customer_city			= $container.find("#cs_customer_city").val();			var cs_customer_country			= $container.find("#cs_customer_country").val();						$container.find('td.cs_customer_name').html(cs_customer_name);			$container.find('td.cs_customer_first_name').html(cs_customer_first_name);			$container.find('td.cs_customer_last_name').html(cs_customer_last_name);			$container.find('td.cs_customer_phone').html(cs_customer_phone);			$container.find('td.cs_customer_email').html(cs_customer_email);			$container.find('td.cs_customer_city').html(cs_customer_city);			$container.find('td.cs_customer_country').html(cs_customer_country);			$container.find('td.cs_customer_address').html(cs_customer_address);						customer_fields_save(admin_url);			cs_remove_overlay(id,'append');		});				return false;	});		});//Remove Customerfunction cs_remove_customer(){	jQuery(".remove-custmr").on('click', function() {		var confirm_msg = confirm("Are you sure, you want to delete this?");		if( confirm_msg == true ){			var tr_parent = jQuery(this).parent().parent('tr');			tr_parent.hide('slow', function(){				tr_parent.remove();				setTimeout(function() {					var admin_url = jQuery("#cs-booking-customers").data('url');					customer_fields_save(admin_url);				}, 2000);			});		}	});}function cs_edit_prices(){	jQuery('#cs_pricing_add').on('click','a#edit-prices', function(e){		var all_fields = jQuery("table.cs_prices_capacity tr > td");		all_fields.find("input[type='text']").show();		all_fields.find("small.price_val").hide();	});}	function cs_get_vehicle_cont(vehicle_id, admin_url, type) {	if(vehicle_id == ''){		jQuery("#cs_pricing_cont").html('');		jQuery("#cs_pricing_add").html('');		return false;	}	var dataString = 'vehicle_id=' + vehicle_id +		'&type=' + type +		'&action=cs_get_vehicle_cont';	jQuery("#cs_"+type+"_cont").html('<i class="icon-spinner8 icon-spin"></i>');	jQuery.ajax({		type: "POST",		url: admin_url,		dataType: 'json',		data: dataString,		success: function(response) {			jQuery("#cs_"+type+"_cont").html(response.html);			jQuery("#cs_"+type+"_add").html(response.btn);		}	});	return false;}function add_pricing_to_vehicle(admin_url){	var $ = jQuery;	var dataString = 'vehicle_id=' + jQuery("#cs_vehicle_id").val() +		'&cs_spec_start_day=' + jQuery("#cs_spec_start_day").val() +		'&cs_spec_end_day=' + jQuery("#cs_spec_end_day").val() +		'&cs_plan_spec_pr_title=' + jQuery("#cs_plan_spec_pr_title").val() +		'&action=add_price_plan';			var cap_counter = 0;	           	dataString += '&cs_adult_mon_price[]=' + jQuery("#cs_adult_mon_price"+cap_counter).val();	dataString += '&cs_adult_tue_price[]=' + jQuery("#cs_adult_tue_price"+cap_counter).val();;	dataString += '&cs_adult_wed_price[]=' + jQuery("#cs_adult_wed_price"+cap_counter).val();	dataString += '&cs_adult_thu_price[]=' + jQuery("#cs_adult_thu_price"+cap_counter).val();	dataString += '&cs_adult_fri_price[]=' + jQuery("#cs_adult_fri_price"+cap_counter).val();	dataString += '&cs_adult_sat_price[]=' + jQuery("#cs_adult_sat_price"+cap_counter).val();	dataString += '&cs_adult_sun_price[]=' + jQuery("#cs_adult_sun_price"+cap_counter).val();					if(jQuery("#cs_spec_start_day").val() != '' && jQuery("#cs_spec_end_day").val() != ''){		jQuery("#add_pricing_to_btn").html('<i class="icon-spinner8 icon-spin"></i>');		jQuery.ajax({			type: "POST",			url: admin_url,			data: dataString,			success: function(response) {				jQuery("#cs_pricing_result").append(response);				jQuery("#add_pricing_to_btn").html('');				jQuery("#cs_spec_start_day, #cs_spec_end_day, #cs_plan_spec_pr_title").val('');				cs_remove_overlay('cs_pricing_popup','append');				setTimeout(function() {					price_option_save(admin_url);				}, 1000);			}		});	}	else{		alert('Please Select Dates First.');	}	return false;}function add_offers_to_vehicle(admin_url){	var $ = jQuery;	var dataString = 'cs_spec_start_day=' + jQuery("#cs_spec_start_day").val() +		'&cs_offer_name=' + jQuery("#cs_offer_name").val() +		'&cs_offer_vehicle=' + jQuery("#cs_offer_vehicle").val() +		'&cs_spec_end_day=' + jQuery("#cs_spec_end_day").val() +		'&cs_offer_require=' + jQuery("#cs_offer_require").val() +		'&ofer_discount=' + jQuery("#ofer_discount").val() +		'&action=add_price_offer';			if(jQuery("#cs_spec_start_day").val() != '' && jQuery("#cs_spec_end_day").val() != ''){		jQuery("#add_offers_to_btn").html('<i class="icon-spinner8 icon-spin"></i>');		jQuery.ajax({			type: "POST",			url: admin_url,			data: dataString,			dataType: 'json',			success: function(response) {				if( response.type == 'error' ) {					jQuery('.cs-offer-message p').html(response.message);					jQuery('.cs-offer-message').show();				} else{					jQuery("#cs_offers_tr_result").append(response.message);					jQuery("#add_offers_to_btn").html('');					jQuery("#cs_spec_start_day, #cs_spec_end_day").val('');					cs_remove_overlay('cs_offers_popup','append');					jQuery('.cs-offer-message p').html('');					jQuery('.cs-offer-message').hide();				}							}		});	}	else{		alert('Please Select Dates First.');	}	return false;}function customer_fields_save(admin_url){	jQuery(".outerwrapp-layer,.loading_div").fadeIn(100);	function newValues() {	  var serializedValues = jQuery("#cs-booking-customers input,#cs-booking-customers select,#cs-booking-customers textarea").serialize()+'&action=customer_fields_save';	  return serializedValues;	}	var serializedReturn = newValues();	 jQuery.ajax({		type:"POST",		url: admin_url,		data:serializedReturn, 		success:function(response){						jQuery(".loading_div").hide();			jQuery(".form-msg .innermsg").html(response);			jQuery(".form-msg").show();			jQuery(".outerwrapp-layer").delay(100).fadeOut(400)			slideout();		}	});	//return false;}