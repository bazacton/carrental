<?php
/**
 * @get google font list
 */
/**** google font from Api ****/
/* get google font list */
global $fonts;
 function cs_googlefont_list(){
	global $fonts;
	$font_array = '';
 	if(get_option('cs_font_list') <> '' and get_option('cs_font_attribute') <> ''){
		$font_array =get_option('cs_font_list');
		$font_attribute = get_option('cs_font_attribute');
	}else{
  		$font_array = cs_get_google_fontlist($fonts);
		$font_attribute = cs_font_attribute($fonts);
		if(is_array($font_array) and count($font_array) > 0 and is_array($font_attribute) and count($font_attribute) > 0){
			update_option('cs_font_list',$font_array);
			update_option('cs_font_attribute',$font_attribute);
		}
	}
	return $font_array;

}


/* get google font array from jason */
function cs_get_google_fontlist($response = ''){
	$json_fonts = json_decode($response,  true);
	$items = $json_fonts['items'];
	//$response = file_get_contents($cachefile);
	$font_list= array();
	$i=0;
	if(is_array($items)){
		foreach($items as $item){
		
			//$key=str_replace(' ','-',$item['family']);
			$key=$item['family'];
			$font_list[$key] = $item['family'];
			$i++;
 		}
	}else{
			$font_list = '';
	}
	return $font_list;
}

/* get google font array from jason */

function cs_get_google_font_attribute($response = '', $id= 'ABeeZee'){
	global $fonts;
 	$items	 = '';
	if(get_option('cs_font_attribute')){
		$font_attribute = get_option('cs_font_attribute');
		if(isset($font_attribute) and $font_attribute <> '' && isset($font_attribute[$id]) && $font_attribute[$id] !=''){
			$items = $font_attribute[$id];
		}
	}else{
		$arrtibue_array	= cs_font_attribute($fonts);
		$items = isset($arrtibue_array[$id]) ? $arrtibue_array[$id] : '';
	}
	return $items;
	
}
/** end google font from api ***/

/** Get Google font attributes ***/
add_action('wp_ajax_cs_get_google_font_attributes','cs_get_google_font_attributes');

function cs_get_google_font_attributes(){
	global $fonts;
	if(isset($_POST['index']) and $_POST['index'] <> ''){ 
		$index = $_POST['index'];
	}else{ 
		$index = '';
	}
 	if($index != 'default'){
		if(get_option('cs_font_attribute')){
			$font_attribute = get_option('cs_font_attribute');
			$items = isset($font_attribute[$index]) ? $font_attribute[$index] : '';
		}
		else{
			$json_fonts = json_decode($fonts, true);
			$items = isset($json_fonts['items'][$index]['variants']) ? $json_fonts['items'][$index]['variants'] : '';
		}
		$html='<select id="'.$_POST['id'].'" name="'.$_POST['id'].'"><option value="">Select Attribute</option>';
		if(is_array($items)){
			foreach($items as $key=>$value){
				$html .= '<option value="'.$value.'">'.$value.'</option>';		
			}
		}else{
			$html = '';
		}
		$html .='</select>';
	}
	else{
		$html = '<select id="'.$_POST['id'].'" name="'.$_POST['id'].'"><option value="">Select Attribute</option></select>';
	}
	echo cs_remove_force_tag_blnc_theme($html, false);
	die();
}

function cs_font_attribute($fontarray = ''){
		global $fonts;
		//return $response;	
		 if (isset($fontarray) && $fontarray != '') {
		$json_fonts = json_decode($fontarray, true);
 		$items = isset($json_fonts['items']) ? $json_fonts['items'] : '';
		 }
		$font_list= array();
		$i=0;
		if(is_array($items)){
			foreach($items as $item){
				if (isset($item['family'])) {
				//$key=str_replace(' ','-',$item['family']);
				$key=$item['family'];
				$font_list[$key] = $item['variants'];
				}
				$i++;
			}
		}else{
			echo cs_allow_special_char($font_list);
		}
		return $font_list;
}

/**
 * @Set Font for Frontend
 */
if ( ! function_exists( 'cs_get_font_family' ) ) {
	function cs_get_font_family($font_index = 'default', $att = 'regular') {
		global $fonts;
		$html = '';
		if($font_index != 'default'){
			$fonts = cs_googlefont_list();
 			$all_att = '';
			if(isset($fonts) and is_array($fonts) && isset( $fonts[$font_index] ) ){
				$name = isset($fonts[$font_index]) ? $fonts[$font_index] : '';
				$name = str_replace(' ', '+', $name);
				if($att <> '') $all_att = ':'.$att;
				$url = add_query_arg( 'family', $name.$all_att, "//fonts.googleapis.com/css" );
				wp_enqueue_style( 'font_'.$name, $url, array(), '' );
			}
		}
		
	}
}

/**
 * @Get font family Frontend.
 */
if ( ! function_exists( 'cs_get_font_name') ) {
	function cs_get_font_name($font_index ='default') {
		global $fonts;
		if($font_index != 'default' ){
			$fonts = cs_googlefont_list();
			if(isset($fonts) and is_array($fonts) && isset( $fonts[$font_index] ) ){
				$name = isset($fonts[$font_index]) ? $fonts[$font_index] : '';
				return $name;
			}
		}
		else{
			return 'default';
		}
		
	}
}

function recursive_array_replace($array){
	global $fonts;
	if(is_array($array)){
		$new_array = array();
		for ($i = 0; $i < sizeof($array); $i++) {
			$new_array[] = $array[$i] == 'regular' ? 'Normal' : $array[$i];
		}
	}
	
	return $new_array;
}

/**
 * @Get font family Frontend.
 */
if ( ! function_exists( 'cs_get_font_att_array') ) {
	function cs_get_font_att_array($atts = array()) {
		global $fonts;
		$atts = recursive_array_replace($atts);
		if(sizeof($atts) == 1 and is_numeric($atts[0])) $atts = array_merge($atts, array('Normal'));
		$r_atts = '';
		foreach($atts as $att){
			$r_atts .= $att.' ';
		}
		return $r_atts;
	}
}

/**
 * @Frontend Font Printing.
 */
if ( ! function_exists( 'cs_font_font_print') ) {
	function cs_font_font_print($atts = array(), $size, $f_name, $imp = false) {
		global $fonts;
		$important = '';
		$f_name = cs_get_font_name($f_name);
		if($f_name == 'default' || $f_name == ''){
			if($imp == true) $important = ' !important';
			$html = 'font-size:'.$size.'px'.$important.';';
		}
		else{
			if($imp == true) $important = ' !important';
			$html = 'font:'.$atts.' '.$size.'px \''.$f_name.'\', sans-serif'.$important.';';
		}
		return $html;
	}
}