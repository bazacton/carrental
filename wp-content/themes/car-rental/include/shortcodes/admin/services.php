<?php
/*
 *
 *@Shortcode Name : Services
 *@retrun
 *
 */

if ( ! function_exists( 'cs_pb_services' ) ) {
    function cs_pb_services($die = 0){
        global $cs_node, $cs_count_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $PREFIX = CS_SC_SERVICES;
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
		'column_size'=>'1/2', 
		'cs_service_type_view' => '',
		'cs_service_border_right' => '',
		'cs_service_icon_type' => '',
		'cs_service_icon' => '',
		'cs_service_icon_color' => '',
		'cs_service_bg_image' => '',
		'cs_service_bg_color' => '',
		'service_icon_size' => '',
		'cs_service_postion_modern' => '',
		'cs_service_postion_classic' => '',
		'cs_service_title'=>'',
		'cs_service_url' => '',
		'cs_service_btn_link' => '',
		'cs_service_title_color'=>'',
		'cs_service_content_color'=>'',
		'cs_service_btn_text_color'=>'',
		'cs_service_content' => '',
		'cs_service_link_text' => '', 
		'cs_service_link_color'=>'',
		'cs_service_class'=>''
		);
            
        if(isset($output['0']['atts']))
            $atts = $output['0']['atts'];
        else 
            $atts = array();
        
        if(isset($output['0']['content']))
            $atts_content = $output['0']['content'];
        else 
            $atts_content = "";
		
        $services_element_size = '25';
        foreach($defaults as $key=>$values){
            if(isset($atts[$key]))
                $$key = $atts[$key];
            else 
                $$key =$values;
         }
        $name = 'cs_pb_services';
        $coloumn_class = 'column_'.$services_element_size;
    
    if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
        $shortcode_element = 'shortcode_element_class';
        $shortcode_view = 'cs-pbwp-shortcode';
        $filter_element = 'ajax-drag';
        $coloumn_class = '';
    }    
    $counter_count = $cs_counter;
    $rand_counter = cs_generate_random_string(10);
    ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="services" data="<?php echo cs_element_size_data_array_index($services_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$services_element_size,'','check-square-o');?>
  <div class="cs-wrapp-class-<?php echo esc_attr($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[<?php echo esc_attr( CS_SC_SERVICES ) ;?> {{attributes}}]{{content}}[/<?php echo esc_attr( CS_SC_SERVICES ) ;?>]" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php esc_html_e('Edit Services Options','car-rental');?></h5>
      <a href="javascript:removeoverlay('<?php echo esc_attr($name.$cs_counter);?>','<?php echo esc_attr($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
    <div class="cs-pbwp-content">
      <div class="cs-wrapp-clone cs-shortcode-wrapp cs-pbwp-content">
        <ul class='form-elements'>
              <li class='to-label'>
                <label><?php esc_html_e('Style','car-rental');?></label>
              </li>
              <li class='to-field'>
                <div class='input-sec select-style'>
                  <select name='cs_service_type_view[]' class='dropdown'>
                    <option value='default' <?php if($cs_service_type_view == 'default'){echo 'selected';}?>>
					<?php esc_html_e('default','car-rental');?></option>
                    <option value='box' <?php if($cs_service_type_view == 'box'){echo 'selected';}?>>
					<?php esc_html_e('box','car-rental');?>
                    </option>
                  </select>
                </div>
                <div class='left-info'>
                  <p><?php esc_html_e('choose a style type for accordion element','car-rental');?></p>
                </div>
              </li>
            </ul>
      
        
        <div class="selected_image_type" id="selected_image_type<?php echo esc_attr($rand_counter);?>">
          <ul class="form-elements">
            <li class="to-label">
              <label><?php esc_html_e('Image','car-rental');?></label>
            </li>
            <li class="to-field">
              <input id="service_bg_image<?php echo esc_attr($rand_counter);?>" name="cs_service_bg_image[]" type="hidden" class="" value="<?php echo esc_url($cs_service_bg_image);?>"/>
              <input name="service_bg_image<?php echo esc_attr($rand_counter);?>"  type="button" class="uploadMedia left" value="<?php esc_html_e('Browse','car-rental');?>"/>
            </li>
          </ul>
          <div class="page-wrap" style="overflow:hidden; display:<?php echo esc_url($cs_service_bg_image) && trim($cs_service_bg_image) !='' ? 'inline' : 'none';?>" id="service_bg_image<?php echo esc_attr($rand_counter);?>_box" >
            <div class="gal-active">
              <div class="dragareamain" style="padding-bottom:0px;">
                <ul id="gal-sortable">
                  <li class="ui-state-default" id="">
                    <div class="thumb-secs"> <img src="<?php echo esc_url($cs_service_bg_image);?>"  id="service_bg_image<?php echo esc_attr($rand_counter);?>_img" width="100" height="150"  />
                      <div class="gal-edit-opts"> <a   href="javascript:del_media('service_bg_image<?php echo esc_attr($rand_counter);?>')" class="delete"></a> </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <ul class='form-elements'>
          <li class='to-label'>
            <label><?php esc_html_e('Title','car-rental');?></label>
          </li>
          <li class='to-field'>
            <div class='input-sec'>
              <input class='txtfield' type='text' name='cs_service_title[]' value="<?php echo cs_allow_special_char($cs_service_title);?>" />
            </div>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Title Color','car-rental');?></label>
          </li>
          <li class="to-field">
            <div class="pic-color"><input type="text" name="cs_service_title_color[]" class="bg_color" value="<?php echo esc_attr($cs_service_title_color);?>" /></div>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Content','car-rental');?></label>
          </li>
          <li class="to-field">
            <textarea name="cs_service_content[]" data-content-text="cs-shortcode-textarea"><?php echo esc_textarea($atts_content)?></textarea>
            <p><?php esc_html_e('Enter the content','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Content Color','car-rental');?></label>
          </li>
          <li class="to-field">
            <div class="pic-color"><input type="text" name="cs_service_content_color[]" class="bg_color" value="<?php echo esc_attr($cs_service_content_color);?>" /></div>
          </li>
        </ul>
                <ul class="form-elements">
                          <li class="to-label">
                            <label><?php esc_html_e('Button Text','car-rental');?></label>
                          </li>
                          <li class="to-field">
                            <div class="input-sec">
                              <input type="text" id="cs_service_url" class="" name="cs_service_url[]" value="<?php echo esc_attr($cs_service_url) ?>" />
                            </div>
                           
                          </li>
                        </ul>
                         <ul class="form-elements">
                          <li class="to-label">
                            <label><?php esc_html_e('Button Link','car-rental');?></label>
                          </li>
                          <li class="to-field">
           <input type="text" id="cs_service_btn_link" class="" name="cs_service_btn_link[]" value="<?php echo esc_url($cs_service_btn_link);?>" />
                          </li>
                        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Custom ID','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_service_class[]" class="txtfield"  value="<?php echo esc_attr($cs_service_class)?>" />
            <div class='left-info'><p><?php esc_html_e('Use this option if you want to use specified id for this element','car-rental');?></p></div>
          </li>
        </ul>
        
      </div>
 
      <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
      <ul class="form-elements insert-bg">
        <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo esc_js(str_replace('cs_pb_','',$name));?>','<?php echo esc_js($name.$cs_counter);?>','<?php echo esc_js($filter_element);?>')" ><?php esc_html_e('Insert','car-rental');?></a> </li>
      </ul>
      <div id="results-shortocde"></div>
      <?php } else {?>
      <ul class="form-elements noborder">
        <li class="to-label"></li>
        <li class="to-field">
          <input type="hidden" name="cs_orderby[]" value="services" />
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
    add_action('wp_ajax_cs_pb_services', 'cs_pb_services');
}
?>