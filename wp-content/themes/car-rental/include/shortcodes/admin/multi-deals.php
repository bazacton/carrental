<?php

if ( ! function_exists( 'cs_pb_multiple_deals' ) ) {
    function cs_pb_multiple_deals($die = 0){
        global $cs_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $cs_counter = $_POST['counter'];
        $multiple_deals_num = 0;
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $PREFIX = CS_SC_MULTPLEDEALS.'|'.CS_SC_MULTPLEDEALSITEM;
            $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        $defaults = array(
		'cs_multiple_deals_section_title' => ''
		
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
                $multiple_deals_num = count($atts_content);
        $multiple_deals_element_size = '33';
        foreach( $defaults as $key => $values ) {
            if(isset($atts[$key]))
                $$key = $atts[$key];
            else 
                $$key =$values;
         }

        $name = 'cs_pb_multiple_deals';
        $coloumn_class = 'column_'.$multiple_deals_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
      $rand_counter = rand(888, 9999999);
    ?>
<div id="<?php echo cs_allow_special_char($name.$cs_counter);?>_del" class="column  parentdelete <?php echo cs_allow_special_char($coloumn_class);?> <?php echo cs_allow_special_char($shortcode_view);?>" item="multiple_deals" data="<?php echo cs_element_size_data_array_index($multiple_deals_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$multiple_deals_element_size,'','weixin');?>
  <div class="cs-wrapp-class-<?php echo cs_allow_special_char($cs_counter)?> <?php echo cs_allow_special_char($shortcode_element);?>" id="<?php echo cs_allow_special_char($name.$cs_counter);?>" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php esc_html_e('Edit Multiple deals Options','car-rental');?></h5>
      <a href="javascript:removeoverlay('<?php echo cs_allow_special_char($name.$cs_counter)?>','<?php echo cs_allow_special_char($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a>
	  </div>
    <div class="cs-clone-append cs-pbwp-content" >
      <div class="cs-wrapp-tab-box">
        <div id="shortcode-item-<?php echo esc_attr($cs_counter);?>" data-shortcode-template="{{child_shortcode}} [/<?php echo esc_attr( CS_SC_MULTPLEDEALS ) ;?>]" data-shortcode-child-template="[<?php echo esc_attr( CS_SC_MULTPLEDEALSITEM ) ;?> {{attributes}}] {{content}} [/<?php echo esc_attr( CS_SC_MULTPLEDEALSITEM ) ;?>]">
          <div class="cs-wrapp-clone cs-shortcode-wrapp cs-disable-true" data-template="[<?php echo esc_attr( CS_SC_MULTPLEDEALS ) ;?> {{attributes}}]">
            <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
            <ul class="form-elements">
                <li class="to-label"><label><?php esc_html_e('Section Title','car-rental');?></label></li>
                <li class="to-field">
                    <input  name="cs_multiple_deals_section_title[]" type="text"  value="<?php echo cs_allow_special_char($cs_multiple_deals_section_title);?>"   />
                </li>                  
             </ul>
          
          </div>
			<?php
			  if ( isset($multiple_deals_num) && $multiple_deals_num <> '' && isset($atts_content) && is_array($atts_content)){
				$itemCounter  = 0 ;   
			   
				foreach ( $atts_content as $multiple_deals_items ) {
					$itemCounter++;
					
					$cs_multiple_deals_text = $multiple_deals_items['content'];
	 $defaults = array(
	   'cs_title_color'=>'',
	   'cs_text_color'=>'',
	   'cs_bg_color'=>'',
	   'cs_website_url'=>'',
	   'cs_multiple_deals_title'=>'',
	   'cs_multiple_deals_logo'=>'',
	   'cs_multiple_deals_btn'=>'',
	   'cs_multiple_deals_btn_link'=>'',
	   'cs_multiple_deals_btn_bg_color'=>'',
	   'cs_multiple_deals_btn_txt_color'=>'',
	   'cs_multi_deals_bg_image'=>'',
	   'cs_fontawsome_color'=>'',
	   'cs_multiple_from'=>'',
	   'cs_multi_deals_icon' => ''
	   );
					$cs_multi_deals_icon = 'cs_multi_deals_icon';
					$rand_id = rand(123344556, 984362621);
					foreach($defaults as $key=>$values){
						if(isset($multiple_deals_items['atts'][$key]))
							$$key = $multiple_deals_items['atts'][$key];
						else
							$$key = $values;
					}
			?>
                      <div class='cs-wrapp-clone cs-shortcode-wrapp' id="cs_infobox_<?php echo cs_allow_special_char($rand_id);?>">
                        <header>
                          <h4><i class='icon-arrows'></i><?php esc_html_e('Multiple deals','car-rental');?></h4>
                          <a href='#' class='deleteit_node'><i class='icon-minus-circle'></i><?php esc_html_e('Remove','car-rental');?></a>
                        </header>
                        <ul class="form-elements">
                          <li class="to-label">
                            <label><?php esc_html_e('Title','car-rental');?></label>
                          </li>
                          <li class="to-field">
                            <input type="text" id="cs_multiple_deals_title" class="" name="cs_multiple_deals_title[]" value="<?php echo cs_allow_special_char($cs_multiple_deals_title);?>" />
                          </li>
                        </ul>
                        <ul class='form-elements'>
                          <li class='to-label'>
                            <label><?php esc_html_e('Text:','car-rental');?></label>
                          </li>
                          <li class='to-field'>
                            <div class='input-sec'>
                              <textarea class='txtfield' data-content-text="cs-shortcode-textarea" name='cs_multiple_deals_text[]'><?php echo cs_allow_special_char($cs_multiple_deals_text);?></textarea>
                              <div class='left-info'>
                                <p><?php esc_html_e('Enter your content','car-rental');?></p>
                              </div>
                            </div>
                          </li>
                        </ul>
                        <ul class="form-elements">
                          <li class="to-label">
                            <label><?php esc_html_e('From','car-rental');?></label>
                          </li>
                          <li class="to-field">
                            <input type="text" id="cs_multiple_from" class="" name="cs_multiple_from[]" value="<?php echo esc_html($cs_multiple_from) ?>" />
                          </li>
                        </ul>
                        <ul class="form-elements">
                          <li class="to-label">
                            <label><?php esc_html_e('Currency Sign','car-rental');?></label>
                          </li>
                          <li class="to-field">
                            <div class="input-sec">
                              <input type="text" id="cs_website_url" class="" name="cs_website_url[]" value="<?php echo esc_html($cs_website_url) ?>" />
                            </div>
                           
                          </li>
                        </ul>
                        <ul class="form-elements">
                          <li class="to-label">
                            <label><?php esc_html_e('Price','car-rental');?></label>
                          </li>
                          <li class="to-field">
                            <input type="text" id="cs_multiple_deals_btn_link" class="" name="cs_multiple_deals_btn_link[]" value="<?php echo esc_html($cs_multiple_deals_btn_link) ?>" />
                          </li>
                        </ul>
                
                        
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php esc_html_e('Image','car-rental');?></label>
                  </li>
                  <li class="to-field">
                    <input id="cs_multi_deals_bg_image<?php echo esc_attr($rand_id)?>" name="cs_multi_deals_bg_image[]" type="hidden" class="" value="<?php echo esc_url($cs_multi_deals_bg_image);?>"/>
                    <input name="cs_multi_deals_bg_image<?php echo esc_attr($rand_id);?>"  type="button" class="uploadMedia left" value="<?php esc_html_e('Browse','car-rental');?>"/>
                  </li>
                </ul>
                <div class="page-wrap" style="overflow:hidden; display:<?php echo esc_url($cs_multi_deals_bg_image) && trim($cs_multi_deals_bg_image) !='' ? 'inline' : 'none';?>" id="cs_multi_deals_bg_image<?php echo esc_attr($rand_id)?>_box" >
                  <div class="gal-active">
                    <div class="dragareamain" style="padding-bottom:0px;">
                      <ul id="gal-sortable">
                        <li class="ui-state-default" id="">
                          <div class="thumb-secs"> <img src="<?php echo esc_url($cs_multi_deals_bg_image);?>"  id="cs_multi_deals_bg_image<?php echo esc_attr($rand_id)?>_img" width="100" height="150"  />
                            <div class="gal-edit-opts"> <a href="javascript:del_media('cs_multi_deals_bg_image<?php echo esc_attr($rand_id);?>')" class="delete"></a> </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                
               </div>
          <?php }
             }
            ?>
        </div>
        <div class="hidden-object">
          <input type="hidden" name="multiple_deals_num[]" value="<?php echo (int)$multiple_deals_num;?>" class="fieldCounter"  />
        </div>
        <div class="wrapptabbox no-padding-lr">
          <div class="opt-conts">
            <ul class="form-elements noborder">
              <li class="to-field"> <a href="#" class="add_dealsss cs-main-btn" onclick="cs_shortcode_element_ajax_call('multiple_deals', 'shortcode-item-<?php echo cs_allow_special_char($cs_counter);?>', '<?php echo cs_allow_special_char(admin_url('admin-ajax.php'));?>')"><i class="icon-plus-circle"></i><?php esc_html_e('Add Multiple deals','car-rental');?></a> </li>
               <div id="loading" class="shortcodeload"></div>
            </ul>
          </div>
          <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
          <ul class="form-elements insert-bg">
            <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo cs_allow_special_char(str_replace('cs_pb_','',$name));?>','shortcode-item-<?php echo cs_allow_special_char($cs_counter);?>','<?php echo cs_allow_special_char($filter_element);?>')" ><?php esc_html_e('Insert','car-rental');?></a> </li>
          </ul>
          <div id="results-shortocde"></div>
          <?php } else {?>
          <ul class="form-elements noborder no-padding-lr">
            <li class="to-label"></li>
            <li class="to-field">
              <input type="hidden" name="cs_orderby[]" value="multiple_deals" />
              <input type="button" value="<?php esc_html_e('Save','car-rental');?>" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
            </li>
          </ul>
          <?php }?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_multiple_deals', 'cs_pb_multiple_deals');
}
?>
