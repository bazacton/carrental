<?php
//=====================================================================
// Promobox Shortcode Builder start
//=====================================================================
if ( ! function_exists( 'cs_pb_promobox' ) ) {
    function cs_pb_promobox($die = 0){
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
            $PREFIX = CS_SC_PROMOBOX;
            $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
            $defaults = array( 
			'cs_promobox_section_title'=>'', 
			'cs_promo_image_url'=>'', 
			'cs_promobox_title'=>'', 
			'cs_promobox_contents'=>'', 
			'cs_promobox_btn_bg_color'=>'',
			'cs_promobox_title_color'=>'',
			'cs_promobox_background_color'=>'', 
			'cs_promobox_content_color'=>'' ,
			'cs_link_title'=>'',
			'text_align'=>'', 
			'cs_link'=>'#', 
			'cs_promobox_class' => '', 			
			'bg_repeat'=>'',
			'target'=>'_self'
			);
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
            $promobox_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_promobox';
            $coloumn_class = 'column_'.$promobox_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
    ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="promobox" data="<?php echo cs_element_size_data_array_index($promobox_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$promobox_element_size,'','life-ring');?>
  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[<?php echo esc_attr( CS_SC_PROMOBOX ) ;?> {{attributes}}]{{content}}[/<?php echo esc_attr( CS_SC_PROMOBOX ) ;?>]" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php esc_html_e('Edit Promo Box Options','car-rental');?></h5>
      <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
    <div class="cs-pbwp-content">
      <div class="cs-wrapp-clone cs-shortcode-wrapp">
        <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
        <ul class="form-elements">
            <li class="to-label"><label><?php esc_html_e('Section Title','car-rental');?></label></li>
            <li class="to-field">
                <input name="cs_promobox_section_title[]" type="text"  value="<?php echo cs_allow_special_char($cs_promobox_section_title)?>" />
                <p><?php esc_html_e('This is used for the one page navigation, to identify the section below. Give a title','car-rental');?></p>
            </li>                  
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Background Image','car-rental');?></label>
          </li>
          <li class="to-field">
            <input id="cs_promo_image_url<?php echo esc_attr($cs_counter)?>" name="cs_promo_image_url[]" type="hidden" class="" value="<?php echo esc_url($cs_promo_image_url);?>" />
            <input name="cs_promo_image_url<?php echo esc_attr($cs_counter)?>" type="button" class="uploadMedia left" value="<?php esc_html_e('Browse','car-rental');?>"/>
            <div class="left-info">
            
            <p><?php esc_html_e('Promo Box Background image here','car-rental');?></p>
            </div>
          </li>
        </ul>
        <div class="page-wrap" style="overflow:hidden; display:<?php echo esc_url($cs_promo_image_url) !='' ? 'inline' : 'none';?>" id="cs_promo_image_url<?php echo intval($cs_counter)?>_box" >
          <div class="gal-active">
            <div class="dragareamain" style="padding-bottom:0px;">
              <ul id="gal-sortable">
                <li class="ui-state-default" id="">
                  <div class="thumb-secs"> <img src="<?php echo esc_url($cs_promo_image_url);?>"  id="cs_promo_image_url<?php echo intval($cs_counter)?>_img" width="100" height="150"  />
                    <div class="gal-edit-opts"> <a   href="javascript:del_media('cs_promo_image_url<?php echo intval($cs_counter)?>')" class="delete"></a> </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Background Repeat','car-rental');?></label>
          </li>
          <li class="to-field select-style">
            <select class="bg_repeat" name="bg_repeat[]">
              <option <?php if($bg_repeat == 'yes'){echo 'selected="selected"';}?> value="yes"><?php esc_html_e('Yes','car-rental');?></option>
              <option <?php if($bg_repeat == 'no'){echo 'selected="selected"';}?> value="no"><?php esc_html_e('No','car-rental');?></option>
            </select>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Title','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_promobox_title[]" class="txtfield" value="<?php echo cs_allow_special_char($cs_promobox_title)?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Title Color','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_promobox_title_color[]" class="bg_color" value="<?php echo esc_attr($cs_promobox_title_color)?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Background Color','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_promobox_background_color[]" class="bg_color" value="<?php echo esc_attr($cs_promobox_background_color)?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Contents','car-rental');?></label>
          </li>
          <li class="to-field">
            <textarea  name="cs_promobox_contents[]" rows="10" class="textarea" data-content-text="cs-shortcode-textarea"><?php echo esc_textarea($cs_promobox_contents);?></textarea>
            <p><?php esc_html_e('Enter content here','car-rental');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Content Color','car-rental');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_promobox_content_color[]" class="bg_color" value="<?php echo esc_attr($cs_promobox_content_color)?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Text Align','car-rental');?></label>
          </li>
          <li class="to-field select-style">
            <select class="text_align" name="text_align[]">
              <option <?php if($text_align == 'left'){echo 'selected="selected"';}?> value="left"><?php esc_html_e('Left','car-rental');?></option>
              <option <?php if($text_align == 'right'){echo 'selected="selected"';}?> value="right"><?php esc_html_e('Right','car-rental');?></option>
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
          <input type="hidden" name="cs_orderby[]" value="promobox" />
          <input type="button" value="<?php esc_html_e('Save','car-rental');?>" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))"/>
        </li>
      </ul>
      <?php }?>
    </div>
  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_promobox', 'cs_pb_promobox');
}