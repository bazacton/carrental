<?php
/*
 *
 *@Shortcode Name : Divider
 *@retrun
 *
 */
 if ( ! function_exists( 'cs_pb_divider' ) ) {
    function cs_pb_divider($die = 0){
        global $cs_node, $post;        
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();        
        $counter = $_POST['counter'];        
        $cs_counter = $_POST['counter'];
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $PREFIX = CS_SC_DIVIDER;
            $parseObject     = new ShortcodeParse();
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }        
        $defaults = array(
		     'column_size' => '1/1',
			 'divider_style' => 'divider1',
			 'divider_height' => '1',
			 'divider_backtotop' => '',
			 'divider_margin_top' => '',
			 'divider_margin_bottom' =>'',
			 'line' => 'Wide','color'=>'#000',
			 'cs_divider_class'=>''
			  );
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $atts_content = $output['0']['content'];
            else 
                $atts_content = '';
            $divider_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_divider';
            $coloumn_class = 'column_'.$divider_element_size;
        
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }        
    ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="blog" data="<?php echo cs_element_size_data_array_index($divider_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$divider_element_size, '', 'ellipsis-h',$type='');?>
  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[<?php echo esc_attr( CS_SC_DIVIDER );?> {{attributes}}]" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php esc_html_e('Edit Divider Option','car-rental');?></h5>
      <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
    <div class="cs-pbwp-content">
      <div class="cs-wrapp-clone cs-shortcode-wrapp">
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Style','car-rental');?></label>
          </li>
          <li class="to-field select-style">
            <select name="divider_style[]" class="dropdown" >
               <option <?php if($divider_style=="plain")echo "selected";?> value="plain" ><?php esc_html_e('Plain','car-rental');?></option>
               <option <?php if($divider_style=="fancy")echo "selected";?> value="fancy" ><?php esc_html_e('Fancy','car-rental');?></option>
               <option <?php if($divider_style=="fancy large")echo "selected";?> value="fancy large" ><?php esc_html_e('Fancy Large','car-rental');?></option>
 
            </select>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Back to Top','car-rental');?></label>
          </li>
          <li class="to-field select-style">
            <select name="divider_backtotop[]" class="dropdown" >
              <option value="yes" <?php if($divider_backtotop=="yes")echo "selected";?> ><?php esc_html_e('Yes','car-rental');?></option>
              <option value="no" <?php if($divider_backtotop=="no")echo "selected";?> ><?php esc_html_e('No','car-rental');?></option>
            </select>
            <p><?php esc_html_e('set back to top from the dropdown','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Margin Top','car-rental');?></label>
          </li>
          <li class="to-field">
            <div class="cs-drag-slider" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr($divider_margin_top);?>"></div>
            <input  class="cs-range-input"  name="divider_margin_top[]" type="text" value="<?php echo esc_attr($divider_margin_top);?>"   />
            <p><?php esc_html_e('set margin top for the divider in px','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Margin Bottom','car-rental');?></label>
          </li>
          <li class="to-field">
            <div class="cs-drag-slider" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr($divider_margin_bottom);?>"></div>
            <input  class="cs-range-input"  name="divider_margin_bottom[]" type="text" value="<?php echo esc_attr($divider_margin_bottom);?>"   />
            <p><?php esc_html_e('set a margin bottom for the divider in px','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Height','car-rental');?></label>
          </li>
          <li class="to-field">
            <div class="cs-drag-slider" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="<?php echo esc_attr($divider_height);?>"></div>
            <input  class="cs-range-input"  name="divider_height[]" type="text" value="<?php echo esc_attr($divider_height);?>"   />
            <p><?php esc_html_e('set the divider height','car-rental');?></p>
          </li>
        </ul>
       
      </div>
      <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
      <ul class="form-elements insert-bg">
        <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>','<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" ><?php esc_html_e('Insert','car-rental');?></a> </li>
      </ul>
      <div id="results-shortocde"></div>
      <?php } else {?>
      <ul class="form-elements noborder">
        <li class="to-label"></li>
        <li class="to-field">
          <input type="hidden" name="cs_orderby[]" value="divider" />
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
    add_action('wp_ajax_cs_pb_divider', 'cs_pb_divider');
}