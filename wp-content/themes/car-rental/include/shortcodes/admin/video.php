<?php

/*
 *
 * @Shortcode Name : Video
 * @retrun
 *
 */
 function cs_pb_video($die = 0){
    global $cs_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $counter = $_POST['counter'];
        $cs_counter = $_POST['counter'];
        $album_num = 0;
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $PREFIX = CS_SC_VIDEO;
            $parseObject = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
            $defaults = array('cs_video_section_title' => '','cs_video_sub_title'=>'','video_url' => '','video_width' => '500', 'video_height' => '250','cs_video_custom_class'=>'','cs_video_custom_animation'=>'');
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $atts_content = $output['0']['content'];
            else 
                $atts_content = array();
            if(is_array($atts_content))
                $album_num = count($atts_content);
            $video_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_video';
            $coloumn_class = 'column_'.$video_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
 ?>

<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="column" data="<?php echo cs_element_size_data_array_index($video_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$video_element_size,'','play-circle');?>
  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[<?php echo esc_attr( CS_SC_VIDEO );?> {{attributes}}]{{content}}[/<?php echo esc_attr( CS_SC_VIDEO );?>]" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php esc_html_e('Edit Video Options','car-rental');?></h5>
      <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
    <div class="cs-pbwp-content">
      <div class="cs-wrapp-clone cs-shortcode-wrapp">
        <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
        <ul class="form-elements">
            <li class="to-label"><label><?php esc_html_e('Section Title','car-rental');?></label></li>
            <li class="to-field">
                <input  name="cs_video_section_title[]" type="text"  value="<?php echo cs_allow_special_char($cs_video_section_title);?>"   />
                <p> <?php esc_html_e('This is used for the one page navigation, to identify the section below. Give a title','car-rental');?>  </p>
            </li>                  
        </ul>
             <ul class="form-elements">
            <li class="to-label"><label><?php esc_html_e('Sub Title','car-rental');?></label></li>
            <li class="to-field">
                <input  name="cs_video_sub_title[]" type="text"  value="<?php echo cs_allow_special_char($cs_video_sub_title);?>"   />
               
            </li>                  
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Video Url','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="video_url[]" class="txtfield" value="<?php echo esc_url($video_url)?>" />
            <p><?php esc_html_e('give the video Url here','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Width','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="video_width[]" class="txtfield" value="<?php echo esc_attr($video_width);?>" />
            <p><?php esc_html_e('Add a width in pix, If you want to override the default','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Height','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="video_height[]" class="txtfield" value="<?php echo esc_attr($video_height)?>" />
            <p><?php esc_html_e('Provide height in cs, if you want to override the default','car-rental');?> </p>
          </li>
        </ul>
        <?php 
            if ( function_exists( 'cs_shortcode_custom_classes_test' ) ) {
                cs_shortcode_custom_dynamic_classes($cs_video_custom_class,$cs_video_custom_animation,'','video');
            }
        ?>
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
          <input type="hidden" name="cs_orderby[]" value="video" />
          <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
        </li>
      </ul>
      <?php }?>
    </div>
  </div>
</div>
<?php
    if ( $die <> 1 ) die();
}
add_action('wp_ajax_cs_pb_video', 'cs_pb_video');