<?php
/*
 *
 *@Shortcode Name : Button
 *@retrun
 *
 */
if ( ! function_exists( 'cs_pb_button' ) ) {
    function cs_pb_button($die = 0){
        global $cs_node, $cs_count_node, $post;
        
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $PREFIX = CS_SC_BUTTON;
        $counter = $_POST['counter'];
        $parseObject     = new ShortcodeParse();
        $cs_counter = $_POST['counter'];
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
		'button_size'=>'btn-lg','button_border' => '','border_button_color' => '','button_title' => '','button_link' => '#','button_color' => '','button_bg_color' => '','button_icon_position' => 'left','button_icon'=>'', 'button_type' => 'rounded',	'button_target' => '_self',	'cs_button_class' => ''	);
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $atts_content = $output['0']['content'];
            else 
                $atts_content = array();
            $button_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_button';
            $cs_count_node++;
            $coloumn_class = 'column_'.$button_element_size;
        
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
		
		$rand_id = rand(45,897009);
    ?>

<div id="<?php echo esc_attr($name.$cs_counter);?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="blog" data="<?php echo cs_element_size_data_array_index($button_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$button_element_size,'','heart');?>
  <div class="cs-wrapp-class-<?php echo esc_attr($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[<?php echo esc_attr( CS_SC_BUTTON );?> {{attributes}}]" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php esc_html_e('Edit Button Options','car-rental');?></h5>
      <a href="javascript:removeoverlay('<?php echo esc_attr($name.$cs_counter)?>','<?php echo esc_attr($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
      <div class="cs-pbwp-content">
      <div class="cs-wrapp-clone cs-shortcode-wrapp cs-pbwp-content">
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Size','car-rental');?></label>
          </li>
          <li class="to-field select-style">
            <select class="button_size" id="button_size" name="button_size[]">
                <option value="btn-lg" <?php if($button_size == 'btn-lg'){echo 'selected="selected"';}?>><?php esc_html_e('Large','car-rental');?> </option>
                <option  value="medium-btn" <?php if($button_size == 'medium-btn'){echo 'selected="selected"';}?>><?php esc_html_e('Medium','car-rental');?></option>
                <option value="btn-sml" <?php if($button_size == 'btn-sml'){echo 'selected="selected"';}?>><?php esc_html_e('Small','car-rental');?></option>
            </select>
            <div class='left-info'><p><?php esc_html_e('Select column width. This width will be calculated depend page width','car-rental');?></p></div>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Button Title','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="button_title[]" class="txtfield" value="<?php echo cs_allow_special_char($button_title)?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Button Link','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="button_link[]" class="txtfield" value="<?php echo esc_attr($button_link);?>" />
            <div class='left-info'><p><?php esc_html_e('Button external/internal url','car-rental');?></p></div>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Border','car-rental');?></label>
          </li>
          <li class="to-field select-style">
            <select class="button_border" id="button_border" name="button_border[]">
              <option value="yes" <?php if($button_border == 'yes'){echo 'selected="selected"';}?>><?php esc_html_e('Yes','car-rental');?> </option>
              <option  value="no" <?php if($button_border == 'no'){echo 'selected="selected"';}?>><?php esc_html_e('No','car-rental');?></option>
            </select>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Border Color','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="border_button_color[]" class="bg_color" value="<?php echo esc_attr($border_button_color)?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Button Background Color','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="button_bg_color[]" class="bg_color" value="<?php echo esc_attr($button_bg_color)?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Button Color','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="button_color[]" class="bg_color" value="<?php echo esc_attr($button_color)?>" />
            <div class='left-info'><p><?php esc_html_e('select a color which you want on the buttons','car-rental');?></p></div>
          </li>
        </ul>
        <ul class='form-elements' id="cs_infobox_<?php echo esc_attr($name.$cs_counter);?>">
          <li class='to-label'>
            <label><?php esc_html_e('Fontawsome Icon','car-rental');?></label>
          </li>
          <li class="to-field">
            <?php cs_fontawsome_icons_box($button_icon,$rand_id,'button_icon');?>
            <div class='left-info'><p><?php esc_html_e('select the fontawsome Icons you would like to add to your menu items','car-rental');?> </p></div>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Button Icon Position','car-rental');?></label>
          </li>
          <li class="to-field select-style">
            <select class="button_icon_position" id="button_icon_position" name="button_icon_position[]">
              <option value="left" <?php if($button_icon_position == 'left'){echo 'selected="selected"';}?>><?php esc_html_e('Left','car-rental');?></option>
              <option value="right" <?php if($button_icon_position == 'right'){echo 'selected="selected"';}?>><?php esc_html_e('Right','car-rental');?></option>
            </select>
            <div class='left-info'><p><?php esc_html_e('set a position for the button','car-rental');?></p></div>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Button Type','car-rental');?></label>
          </li>
          <li class="to-field select-style">
            <select class="button_type" id="button_type" name="button_type[]">
              <option value="rectangle" <?php if($button_type == 'rectangle'){echo 'selected="selected"';}?>><?php esc_html_e('Square','car-rental');?></option>
              <option value="rounded" <?php if($button_type == 'rounded'){echo 'selected="selected"';}?>><?php esc_html_e('Rounded','car-rental');?></option>
 
            </select>
           <div class='left-info'> <p><?php esc_html_e('Select the display type for the button','car-rental');?></p></div>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Target','car-rental');?></label>
          </li>
          <li class="to-field select-style">
            <select class="button_target" id="button_target" name="button_target[]">
              <option value="_blank" <?php if($button_target == '_blank'){echo 'selected="selected"';}?>><?php esc_html_e('Blank','car-rental');?></option>
              <option value="_self" <?php if($button_target == '_self'){echo 'selected="selected"';}?>><?php esc_html_e('Self','car-rental');?></option>
            </select>
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
          <input type="hidden" name="cs_orderby[]" value="button" />
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
    add_action('wp_ajax_cs_pb_button', 'cs_pb_button');
}
?>