<?php
/*
 *
 *@Shortcode Name : Tabs
 *@retrun
 *
 */
if (!function_exists('cs_tabs_shortcode')) {
    function cs_tabs_shortcode( $atts, $content = null ) {
        global $tabs_content;
        $tabs_content = '';
        extract(shortcode_atts(array('cs_tab_style' => '','cs_tabs_class' => '','column_size'=>'1/1','cs_tabs_section_title' => ''), $atts));  
        $column_class  = cs_custom_column_class($column_size);
        
        $randid = rand(8,9999);
        $section_title = '';
        $tabs_output = '';
        
        if ( isset($cs_tabs_section_title) && trim($cs_tabs_section_title) !='' ) {
            $section_title    = '<div class="cs-section-title"><h2>'.$cs_tabs_section_title.'</h2></div>';
        }
        $tabs_vertical_classs = (isset($cs_tab_style) and $cs_tab_style == 'vertical') ? 'vertical' : 'nav-position-top';
        $tabs_output .= '<div class="accomodation-tabs '.sanitize_html_class($tabs_vertical_classs).' "  id="cstabs'.absint($randid).'">';
        $tabs_output .= $section_title;
        $tabs_output .= '<ul class="nav'.sanitize_html_class($cs_tabs_class).'" > ';
        $tabs_output .= do_shortcode($content);
        $tabs_output .= '</ul>';
        $tabs_output .= '<div class="tab-content">'.$tabs_content.'</div>';
        $tabs_output .= '</div>';
        return '<div class="'.$column_class.' '.sanitize_html_class($cs_tabs_class).'">'.$tabs_output.'</div>';  
    }  
    if (function_exists('cs_short_code')) cs_short_code(CS_SC_TABS, 'cs_tabs_shortcode');
}

/**
 *
 * @Tabs Item
 *
 */
if (!function_exists('cs_tab_item_shortcode')) {
    function cs_tab_item_shortcode($atts, $content = null) {  
        global $tabs_content;
        extract(shortcode_atts(array(  
            'cs_tab_icon' => '',
            'tab_title' => '',
            'cs_tab_icon' => '',
            'tab_active'=>'no' 
        ), $atts));  
        $activeClass = $tab_active == 'yes' ? 'active in' :'';
        $fa_icon='';
        if($cs_tab_icon){
            $fa_icon = '<i class="'.sanitize_html_class($cs_tab_icon).'"></i> ';
        }
        $randid = rand(877,9999);
        $output = ' <li class="'.$activeClass.'" role="presentation"> <a href="#cs-tab-'.sanitize_title($tab_title).$randid.'"  data-toggle="tab">'.$fa_icon.$tab_title.'</a></li>';
        $tabs_content.= '<div class="tab-pane fade '.$activeClass.'" id="cs-tab-'.sanitize_title($tab_title).$randid.'">'.do_shortcode($content).'</div>';
        return $output;
    }
    if (function_exists('cs_short_code')) cs_short_code( CS_SC_TABSITEM, 'cs_tab_item_shortcode' );
}
?>