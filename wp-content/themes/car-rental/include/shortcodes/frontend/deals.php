<?php
/*
 *
 *@Shortcode Name : Price Table
 *@retrun
 *
 */

if (!function_exists('cs_deals_shortcode')) {
    function cs_deals_shortcode($atts, $content = "") {
        global $pricetable_style;
		
        $defaults = array(
		
	    'column_size'=>'1/1',
		'deals_style'=>'',
		'deals_title'=>'', 
		'deals_title_bgcolor'=>'',
		'deals_price'=>'',
		'currency_symbols'=>'$',
		'cs_price_icon'=>'',
		'deals_img'=>'',
		'deals_period'=>'',
		'deals_bgcolor'=>'',
		'cs_deals_text'=>'',
		'btn_link'=>'',
		'cs_btn_text'=>'',
		'feature_style'=>'',
		'deals_style'=>'',
		'btn_bg_color'=>'',
		'deals_featured'=>'',
		'deals_class'=>''
		
		);
		
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
       $deals_title = isset($deals_title) ? $deals_title : '';
	   $cs_deals_text = isset($cs_deals_text) ? $cs_deals_text : '';
	   $deals_price = isset($deals_price) ? $deals_price : '';
	   $deals_title_bgcolor = isset($deals_title_bgcolor) ? $deals_title_bgcolor : '';
	   $currency_symbols = isset($currency_symbols) ? $currency_symbols : '';
	   $deals_class = isset($deals_class) ? $deals_class : '';
 
      
 
		$html  = '';
		$html  .= '<div class="our-deals">';
		$html .= '<div class="element-size-33">';
		$html .= '<div class="col-md-12">';
		$html .= '<article>';
		$html .= '<figure>';
		$html .= '<img src="'.esc_url($deals_title_bgcolor).'" alt="#">';
		$html .= '<figcaption>';
		$html .= '<div class="caption-inner">';
		$html .= '<p>'.$cs_deals_text.'</p>';
		$html .= '</div>';
		$html .= '</figcaption>';
		$html .= '</figure>';
		$html .= '<div class="text">';
		$html .= '<h6>'.$deals_title .'</h6>';
		$html .= '<div class="price-sec">';
		$html .= '<span>'.$deals_class.'</span>';
		$html .= '<p>';
		$html .= '<span>'.$currency_symbols.'</span>';
		$html .= ''. $deals_price.'';
		$html .= '</p>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</article>';
		$html .= '</div>';
		$html .= '</div>';
 
		
 
	    return '<div '.$CustomId.' class="'.$column_class.'">'.$html.'</div>';
    }
    if (function_exists('cs_short_code')) cs_short_code(CS_SC_DEALS, 'cs_deals_shortcode');
}

/*
 *
 *@Price Table Item
 *@retrun
 *
 */
 