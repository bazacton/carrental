<?php
/*
 *
 *@Shortcode Name : Counters
 *@retrun
 *
 */

if ( ! function_exists( 'cs_pb_counter' ) ) {
    function cs_pb_counter($die = 0){
        global $cs_node, $cs_count_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $PREFIX = CS_SC_COUNTERS;
        $cs_counter = $_POST['counter'];
        $parseObject     = new ShortcodeParse();
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        
        $defaults = array(  
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
                'counter_link_title' => '',
                'counter_link_url' => '',
                'counter_text_color' => '',
                'counter_border' => '',
				'counter_border_color' => '',
                'counter_class' => '',
               
             );
            
        if(isset($output['0']['atts']))
            $atts = $output['0']['atts'];
        else 
            $atts = array();
        
        if(isset($output['0']['content']))
            $atts_content = $output['0']['content'];
        else 
            $atts_content = "";
            
        $counter_element_size = '25';
        foreach($defaults as $key=>$values){
            if(isset($atts[$key]))
                $$key = $atts[$key];
            else 
                $$key =$values;
         }
        $name = 'cs_pb_counter';
        $coloumn_class = 'column_'.$counter_element_size;
    
    if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
        $shortcode_element = 'shortcode_element_class';
        $shortcode_view = 'cs-pbwp-shortcode';
        $filter_element = 'ajax-drag';
        $coloumn_class = '';
    }    
	 $random_id = rand(34, 3434233);
    $counter_count = $random_id;
   
    ?>
<div id="<?php echo esc_attr($name.$cs_counter);?>_del" class="column  parentdelete <?php echo cs_allow_special_char($coloumn_class);?> <?php echo cs_allow_special_char($shortcode_view);?>" item="counter" data="<?php echo cs_element_size_data_array_index($counter_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$counter_element_size,'','clock-o');?>
  <div class="cs-wrapp-class-<?php echo esc_attr($cs_counter);?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo cs_allow_special_char($name.$cs_counter);?>" data-shortcode-template="[<?php echo esc_attr( CS_SC_COUNTERS ) ;?> {{attributes}}]{{content}}[/<?php echo esc_attr( CS_SC_COUNTERS ) ;?>]" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php esc_html_e('Edit Counter Options','car-rental');?></h5>
      <a href="javascript:removeoverlay('<?php echo cs_allow_special_char($name.$cs_counter)?>','<?php echo cs_allow_special_char($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
    <div class="cs-pbwp-content">
      <div class="cs-wrapp-clone cs-shortcode-wrapp cs-pbwp-content">

        <div id="selected_view_icon_type<?php echo esc_attr($counter_count)?>" >

        <ul class="form-elements" style="display:none">
          <li class="to-field">
            <div class="select-style">
              <select name="counter_icon_align[]" class="dropdown" >
                <option value="left" ><?php esc_html_e('Left','car-rental');?></option>
              </select>
            </div>
          </li>
        </ul>
        </div>
        <div class="selected_icon_type<?php echo esc_attr($counter_count)?>" id="selected_view_icon_icon_type<?php echo esc_attr($counter_count)?>" >
          <ul class='form-elements' id="cs_infobox_<?php echo esc_attr($name.$cs_counter);?>">
            <li class='to-label'>
              <label><?php esc_html_e('Fontawsome Icon','car-rental');?></label>
            </li>
            <li class="to-field">
              <?php cs_fontawsome_icons_box($counter_icon,$name.$cs_counter,'counter_icon');?>
              <div class='left-info'><p><?php esc_html_e('select the fontawsome Icons you would like to add to your menu items','car-rental');?> </p></div>
            </li>
          </ul>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php esc_html_e('Icon Color','car-rental');?></label>
            </li>
            <li class="to-field">
              <div class='input-sec'>
                <input type="text" name="counter_icon_color[]" class="bg_color"  value="<?php echo esc_attr($counter_icon_color)?>" />
                <div class='left-info'><p><?php esc_html_e('set a color for the counter icon','car-rental');?></p></div>
              </div>
            </li>
          </ul>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php esc_html_e('Icon Size','car-rental');?></label>
            </li>
            <li class="to-field select-style">
              <select class="counter_icon_size" name="counter_icon_size[]">
                <option value="">None</option>
                <option value="icon-2x" <?php if($counter_icon_size == 'icon-2x'){echo 'selected="selected"';}?>><?php esc_html_e('Small','car-rental');?></option>
                <option value="icon-3x" <?php if($counter_icon_size == 'icon-3x'){echo 'selected="selected"';}?>><?php esc_html_e('Medium','car-rental');?></option>
                <option value="icon-4x" <?php if($counter_icon_size == 'icon-4x'){echo 'selected="selected"';}?>><?php esc_html_e('Large','car-rental');?></option>
                <option value="icon-5x" <?php if($counter_icon_size == 'icon-5x'){echo 'selected="selected"';}?>><?php esc_html_e('Extra Large','car-rental');?></option>
              </select>
              <div class='left-info'><p><?php esc_html_e('Select Icon Size','car-rental');?></p></div>
            </li>
          </ul>
        </div>

        <ul class="form-elements bcevent_title">
          <li class="to-label">
            <label><?php esc_html_e('set number','car-rental');?></label>
          </li>
          <li class="to-field">
            <div class="input-sec">
              <input type="text" name="counter_numbers[]" value="<?php if(isset($counter_numbers)){echo esc_attr($counter_numbers);}?>" />
              <div class="color-picker"><input type="text" name="counter_number_color[]" value="<?php if(isset($counter_number_color)){echo esc_attr($counter_number_color);}?>" class="bg_color" /></div>
              
            </div>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Sub Title','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text"  name="counter_title[]" value="<?php echo cs_allow_special_char($counter_title);?>" class="txtfield"  />
            <div class='left-info'><p><?php esc_html_e('enter a sub title for the counter','car-rental');?></p></div>
          </li>
        </MS
        ><ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Sub Title Color','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text"  name="counter_text_color[]"  value="<?php echo esc_attr($counter_text_color);?>" class="bg_color"  />
            <div class='left-info'><p><?php esc_html_e('Provide a hex colour code here (with #) if you want to override the default','car-rental');?> </p></div>
          </li>
        </ul>
        
          <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Border Color','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text"  name="counter_border_color[]"  value="<?php echo esc_attr($counter_border_color);?>" class="bg_color"  />
            <div class='left-info'><p><?php esc_html_e('Provide a hex colour code here (with #) if you want to override the default','car-rental');?> </p></div>
          </li>
        </ul>
          
        <div class="selected_image_type" id="selected_view_border_type<?php echo esc_attr($counter_count)?>" <?php if($counter_style == "icon-border"){ echo 'style="display:block"';} else { echo 'style="display:none"';}?>>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Border Frame','car-rental');?></label>
          </li>
          <li class="to-field">
            <div class="select-style">
              <select name="counter_border[]" class="dropdown">
                <option <?php if($counter_border=="on")echo "selected";?> value="on" ><?php esc_html_e('Yes','car-rental');?></option>
                <option <?php if($counter_border=="off")echo "selected";?> value="off" ><?php esc_html_e('No','car-rental');?></option>
              </select>
             <div class='left-info'> <p><?php esc_html_e('set yes/no border frame form the dropdown','car-rental');?> </p></div>
            </div>
          </li>
        </ul>
        </div>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Custom ID','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="counter_class[]" class="txtfield"   value="<?php echo esc_attr($counter_class);?>" />
            <div class='left-info'><p><?php esc_html_e('Use this option if you want to use specified id for this element','car-rental');?></p></div>
          </li>
        </ul>
        
      </div>
      <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
      <ul class="form-elements insert-bg">
        <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo esc_js(str_replace('cs_pb_','',$name));?>','<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" ><?php esc_html_e('Insert','car-rental');?></a> </li>
      </ul>
      <div id="results-shortocde"></div>
      <?php } else {?>
      <ul class="form-elements noborder">
        <li class="to-label"></li>
        <li class="to-field">
          <input type="hidden" name="cs_orderby[]" value="counter" />
          <input type="button" value="<?php esc_html_e('Save','car-rental');?>" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
        </li>
      </ul>
      <?php }?>
    </div>
  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_counter', 'cs_pb_counter');
}


?>