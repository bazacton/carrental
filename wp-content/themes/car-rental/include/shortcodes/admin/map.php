<?php
/** 
 * @Google map html form for page builder start
 */
if ( ! function_exists( 'cs_pb_map' ) ) {
    function cs_pb_map($die = 0){
        global $cs_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $cs_counter = $_POST['counter'];
         if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $PREFIX = CS_SC_MAP;
            $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        $defaults = array(
		'cs_map_section_title'=>'',
		'map_title'=>'',
		'map_height'=>'',
		'map_lat'=>'-0.127758',
		'map_lon'=>'51.507351',
		'map_zoom'=>'',
		'map_type'=>'',
		'map_info'=>'',
		'map_info_width'=>'',
		'map_info_height'=>'',
		'map_marker_icon'=>'',
		'map_show_marker'=>'true',
		'map_controls'=>'',
		'map_draggable' => '',
		'map_scrollwheel' => '',
		'map_conactus_content' => '',
		'map_border' => '',
		'cs_map_style' => '',
		'map_border_color' => '',
		'cs_map_class' => ''
		);
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $atts_content = $output['0']['content'];
            else 
                $atts_content = array();
             $map_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_map';
            $coloumn_class = 'column_'.$map_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
    $rand_string = $cs_counter.''.cs_generate_random_string(3);    
    ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="blog" data="<?php echo cs_element_size_data_array_index($map_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$map_element_size,'','globe');?>
  <div class="cs-wrapp-class-<?php echo esc_attr($cs_counter);?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[<?php echo esc_attr( CS_SC_MAP );?> {{attributes}}]" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php esc_html_e('Edit Map Options','car-rental');?></h5>
      <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
    <div class="cs-pbwp-content">
      <div class="cs-wrapp-clone cs-shortcode-wrapp">
        <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Section Title','car-rental');?></label>
          </li>
          <li class="to-field">
            <input  name="cs_map_section_title[]" type="text"  value="<?php echo cs_allow_special_char($cs_map_section_title)?>"   />
            <p> <?php esc_html_e('This is used for the one page navigation, to identify the section below. Give a title','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Title','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="map_title[]" class="txtfield" value="<?php echo cs_allow_special_char($map_title)?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Map Height','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="map_height[]" class="txtfield" value="<?php echo esc_attr($map_height)?>" />
            <p><?php esc_html_e('Map height set here','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Latitude','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="map_lat[]" class="txtfield" value="<?php echo esc_attr($map_lat)?>" />
            <p><?php esc_html_e('The map will appear only if this field is filled correctly','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Longitude','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="map_lon[]" class="txtfield" value="<?php echo esc_attr($map_lon)?>" />
            <p><?php esc_html_e('The map will appear only if this field is filled correctly','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Zoom','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="map_zoom[]" class="txtfield" value="<?php echo esc_attr($map_zoom)?>" />
            <p></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Map Types','car-rental');?></label>
          </li>
          <li class="to-field select-style">
            <select name="map_type[]" class="dropdown" >
              <option value="ROADMAP" <?php if($map_type=="ROADMAP")echo "selected";?> ><?php esc_html_e('ROADMAP','car-rental');?></option>
              <option value="HYBRID" <?php if($map_type=="HYBRID")echo "selected";?> ><?php esc_html_e('HYBRID','car-rental');?></option>
              <option value="SATELLITE" <?php if($map_type=="SATELLITE")echo "selected";?> ><?php esc_html_e('SATELLITE','car-rental');?></option>
              <option value="TERRAIN" <?php if($map_type=="TERRAIN")echo "selected";?> ><?php esc_html_e('TERRAIN','car-rental');?></option>
            </select>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Info Text','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="map_info[]" class="txtfield" value="<?php echo esc_attr($map_info)?>" />
            <p><?php esc_html_e('Enter the marker content','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Info Max Width','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="map_info_width[]" class="txtfield" value="<?php echo esc_attr($map_info_width)?>" />
            <p><?php esc_html_e('set max width for the google map','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Info Max Height','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="map_info_height[]" class="txtfield" value="<?php echo esc_attr($map_info_height)?>" />
            <p><?php esc_html_e('set max height for the google map','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Marker Icon Path','car-rental');?></label>
          </li>
          <li class="to-field">
            <input id="map_marker_icon<?php echo esc_attr($rand_string)?>" name="map_marker_icon[]" type="hidden" class="" value="<?php echo esc_attr($map_marker_icon);?>"/>
            <label class="browse-icon"><input name="map_marker_icon<?php echo esc_attr($rand_string)?>"  type="button" class="uploadMedia left" value="<?php esc_html_e('Browse','car-rental');?>"/></label>
            <div class="left-info"><p><?php esc_html_e('Give a link for your marker icon','car-rental');?></p></div>
          </li>
        </ul>
        <div class="page-wrap" style="overflow:hidden; display:<?php echo esc_attr($map_marker_icon) && trim($map_marker_icon) !='' ? 'inline' : 'none';?>" id="map_marker_icon<?php echo esc_attr($rand_string);?>_box" >
          <div class="gal-active">
            <div class="dragareamain" style="padding-bottom:0px;">
              <ul id="gal-sortable">
                <li class="ui-state-default" id="">
                  <div class="thumb-secs"> <img src="<?php echo esc_url($map_marker_icon);?>"  id="map_marker_icon<?php echo esc_attr($rand_string);?>_img" width="100" height="150"  />
                    <div class="gal-edit-opts"> <a   href="javascript:del_media('map_marker_icon<?php echo esc_js($rand_string)?>')" class="delete"></a> </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Show Marker','car-rental');?></label>
          </li>
          <li class="to-field select-style">
            <select name="map_show_marker[]" class="dropdown" >
              <option value="true" <?php if($map_show_marker=="true")echo "selected";?> ><?php esc_html_e('On','car-rental');?></option>
              <option value="false" <?php if($map_show_marker=="false")echo "selected";?> ><?php esc_html_e('Off','car-rental');?></option>
            </select>
            <p><?php esc_html_e('Set marker on/off for the map','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Disable Map Controls','car-rental');?></label>
          </li>
          <li class="to-field select-style">
            <select name="map_controls[]" class="dropdown" >
              <option value="false" <?php if($map_controls=="false")echo "selected";?> ><?php esc_html_e('Off','car-rental');?></option>
              <option value="true" <?php if($map_controls=="true")echo "selected";?> ><?php esc_html_e('On','car-rental');?></option>
            </select>
            <p><?php esc_html_e('You can set map control disable/enable','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Drag able','car-rental');?></label>
          </li>
          <li class="to-field select-style">
            <select name="map_draggable[]" class="dropdown" >
              <option value="true" <?php if($map_draggable=="true")echo "selected";?> ><?php esc_html_e('On','car-rental');?></option>
              <option value="false" <?php if($map_draggable=="false")echo "selected";?> ><?php esc_html_e('Off','car-rental');?></option>
            </select>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Scroll Wheel','car-rental');?></label>
          </li>
          <li class="to-field select-style">
            <select name="map_scrollwheel[]" class="dropdown" >
              <option value="true" <?php if($map_scrollwheel=="true")echo "selected";?> ><?php esc_html_e('On','car-rental');?></option>
              <option value="false" <?php if($map_scrollwheel=="false")echo "selected";?> ><?php esc_html_e('Off','car-rental');?></option>
            </select>
            <p><?php esc_html_e('Set scroll wheel','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Border','car-rental');?></label>
          </li>
          <li class="to-field select-style">
            <select class="dropdown" name="map_border[]">
              <option <?php if($map_border == 'yes'){echo 'selected="selected"';}?> value="yes"><?php esc_html_e('Yes','car-rental');?></option>
              <option <?php if($map_border == 'no'){echo 'selected="selected"';}?> value="no"><?php esc_html_e('No','car-rental');?></option>
            </select>
            <p><?php esc_html_e('Set border for map','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Border Color','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="map_border_color[]" class="bg_color" value="<?php echo esc_attr($map_border_color);?>" />
            <div class="left-info">
            <p><?php esc_html_e('If you will select a border than select the border color','car-rental');?></p>
            </div>
          </li>
        </ul>
         
       
      </div>
      <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
	   ?>
      <ul class="form-elements insert-bg">
        <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo esc_js(str_replace('cs_pb_','',$name));?>','<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" ><?php esc_html_e('Insert','car-rental');?></a> </li>
      </ul>
      <div id="results-shortocde"></div>
      <?php } else {?>
      <ul class="form-elements noborder">
        <li class="to-label"></li>
        <li class="to-field">
          <input type="hidden" name="cs_orderby[]" value="map" />
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
add_action('wp_ajax_cs_pb_map', 'cs_pb_map');
}
