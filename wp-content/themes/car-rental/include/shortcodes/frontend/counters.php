<?php
/*
 *
 *@Shortcode Name : Counters
 *@retrun
 *
 */

if (!function_exists('cs_counter_item_shortcode')) {
    function cs_counter_item_shortcode($atts, $content = null) {
        global $counter_style;
        extract(shortcode_atts(array(  
            'column_size' => '1/1',
            'counter_style' => '',
            'counter_icon_type' => '',
            'cs_counter_logo' => '',
            'counter_icon'=>'',
            'counter_icon_align'=>'',
            'counter_icon_size'=>'',
            'counter_icon_color' => '',
            'counter_numbers' => '',
            'counter_number_color' => '',
            'counter_title' => '',
            'counter_text_color' => '',
			'counter_border_color' => '',
            'counter_border' => '',
            'counter_class' => '',
           
         ), $atts));
         
         $column_class  = cs_custom_column_class($column_size);
         
         $CustomId    = '';
         if ( isset( $counter_class ) && $counter_class ) {
            $CustomId    = 'id="'.$counter_class.'"';
         }
         
         
            $rand_id = rand(98,56666);
            $output = '';
            $counter_style_class = '';
            $pattren_bg          = '';
            $has_border     = '';
            $output = '';
            $border_class =  '';
            
            cs_count_numbers_script();
          	$output  = '';  
            $output .= '
                <script>
                    jQuery(document).ready(function($){
                        jQuery(".custom-counter-'.esc_js($rand_id).'").counterUp({
                            delay: 10,
                            time: 1000
                        });
                    });    
                </script>';
          $counter_border = '';
          if ($counter_border_color <> ''){
              $counter_border = 'style="border:4px solid '.$counter_border_color.' " ';
          }
                $combine_counter_icon = '';    
		 	 
				$output .= '<div class="cs-counter">';
				$output .= '<div class="col-md-3">';
				$output .= '<article '.$counter_border.'>';
				$output .= '<figure>';
				$output .= '<i class="'.$counter_icon.' '.$counter_icon_size.'" style=" color: '.$counter_icon_color.'; "></i>';
				$output .= '</figure>';
				$output .= '<div class="text">';
				$output .= '<a class="cs-numcount custom-counter-'.$rand_id.'" style=" color: '.$counter_number_color.';">'.$counter_numbers.'</a>';
				$output .= '<p style="color:'.$counter_text_color.';">'.$counter_title.'</p>';
				$output .= '</div>';
				$output .= '</article>';
				$output .= '</div>';
				$output .= '</div>';
		
 
        	return $output;
    }
    if (function_exists('cs_short_code')) cs_short_code( CS_SC_COUNTERS, 'cs_counter_item_shortcode' );
}