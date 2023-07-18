<?php
/*
 *
 *@Shortcode Name : Price Table
 *@retrun
 *
 */

if ( ! function_exists( 'cs_pb_deals' ) ) {
    function cs_pb_deals($die = 0){
        global $cs_node, $cs_count_node, $post;
        
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $cs_counter = $_POST['counter'];
        $PREFIX = CS_SC_DEALS.'|'.CS_SC_DEALSITEM;
        $parseObject     = new ShortcodeParse();
        $deals_num = 0;
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
		'column_size'=>'1/1',
		'deals_style'=>'',
		'deals_title'=>'', 
		'deals_title_bgcolor'=>'',
		'deals_price'=>'',
		'currency_symbols'=>'$',
		'cs_price_icon'=>'',
		'deals_img'=>'',
		'deals_period'=>'',
		'deals_bgcolor'=>'',
		'cs_deals_text'=>'',
		'btn_link'=>'',
		'cs_btn_text'=>'',
		'feature_style'=>'',
		'deals_style'=>'',
		'btn_bg_color'=>'',
		'deals_featured'=>'',
		'deals_class'=>''
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
                $deals_num = count($atts_content);
            $deals_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_deals';
            $coloumn_class = 'column_'.$deals_element_size;
        
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
        
        $cs_counter = $cs_counter.rand(11,555);
		$rand_id = rand(0,999999999999);
        
    ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="deals" data="<?php echo cs_element_size_data_array_index($deals_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$deals_element_size,'','th');?>
  <div class="cs-wrapp-class-<?php echo esc_attr($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php esc_html_e('Edit Price Table Options','car-rental');?></h5>
      <a href="javascript:removeoverlay('<?php echo esc_attr($name.$cs_counter)?>','<?php echo esc_attr($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
       <div class="cs-clone-append cs-pbwp-content">
        <div class="cs-wrapp-tab-box">
         <div  id="cs-shortcode-wrapp_<?php echo esc_attr($name.$cs_counter)?>">
          <div id="shortcode-item-<?php echo esc_attr($cs_counter);?>" data-shortcode-template="{{child_shortcode}} [/<?php echo esc_attr( CS_SC_DEALS );?>]" data-shortcode-child-template="[<?php echo esc_attr( CS_SC_DEALSITEM );?> {{attributes}}] {{content}} [/<?php echo esc_attr( CS_SC_DEALSITEM );?>]">
            <div class="cs-wrapp-clone cs-shortcode-wrapp cs-disable-true" data-template="[<?php echo esc_attr( CS_SC_DEALS );?> {{attributes}}]">
                <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
           
                 <ul class="form-elements">
                  <li class="to-label">
                    <label><?php esc_html_e('Title','car-rental');?></label>
                  </li>
                  <li class="to-field">
                    <input type="text" name="deals_title[]" class="txtfield" value="<?php echo cs_allow_special_char($deals_title);?>" />
                    <div class='left-info'>
                      <div class='left-info'><p> <?php esc_html_e('set title for the item','car-rental');?></p></div>
                    </div>
                  </li>
                </ul>
                
                    <ul class="form-elements">
                  <li class="to-label">
                    <label><?php esc_html_e('Text','car-rental');?></label>
                  </li>
                  <li class="to-field">
                    <input type="text" name="cs_deals_text[]" class="txtfield" value="<?php echo cs_allow_special_char($cs_deals_text);?>" />
                    <div class='left-info'>
                      <div class='left-info'><p> <?php esc_html_e('set title for the item','car-rental');?></p></div>
                    </div>
                  </li>
                </ul>
          
                 <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Background Image','car-rental');?></label>
          </li>
          <li class="to-field">
            <input id="deals_title_bgcolor<?php echo esc_attr($cs_counter)?>" name="deals_title_bgcolor[]" type="hidden" class="" value="<?php echo esc_attr($deals_title_bgcolor);?>"/>
            <input name="deals_title_bgcolor<?php echo esc_attr($cs_counter)?>"  type="button" class="uploadMedia left" value="<?php esc_html_e('Browse','car-rental'); ?>"/>
            <div class='left-info'><p><?php esc_html_e('Select the background image for action element','car-rental');?></p></div>
          </li>
        </ul>
                 <div class="page-wrap" style="overflow:hidden; display:<?php echo esc_attr($deals_title_bgcolor) && trim($deals_title_bgcolor) !='' ? 'inline' : 'none';?>" id="deals_title_bgcolor<?php echo esc_attr($cs_counter)?>_box" >
          <div class="gal-active">
            <div class="dragareamain" style="padding-bottom:0px;">
              <ul id="gal-sortable">
                <li class="ui-state-default" id="">
                  <div class="thumb-secs"> <img src="<?php echo esc_url($deals_title_bgcolor);?>"  id="deals_title_bgcolor<?php echo esc_attr($cs_counter)?>_img" width="100" height="150"  />
                    <div class="gal-edit-opts"> <a href="javascript:del_media('deals_title_bgcolor<?php echo esc_js($cs_counter)?>')" class="delete"></a> </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <ul class="form-elements">
                  <li class="to-label">
                    <label><?php esc_html_e('From Text','car-rental');?></label>
                  </li>
                  <li class="to-field">
                    <input type="text" name="deals_class[]" class="" placeholder="From"value="<?php echo esc_attr($deals_class);?>" />
                    <div class='left-info'>
                    </div>
                  </li>
                </ul>
          
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php esc_html_e('Price','car-rental');?></label>
                  </li>
                  <li class="to-field">
                    <input type="text" name="deals_price[]" class="" value="<?php echo esc_attr($deals_price);?>" />
                    <div class='left-info'>
                      <div class='left-info'><p><?php esc_html_e('item Price','car-rental');?></p></div>
                    </div>
                  </li>
                </ul>
          
   
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php esc_html_e('Currency Symbols','car-rental');?></label>
                  </li>
                  <li class="to-field">
                    <input type="text" name="currency_symbols[]" class="" value="<?php echo esc_attr($currency_symbols);?>" />
                    <div class='left-info'>
                      <div class='left-info'><p><?php esc_html_e('item currency symbols','car-rental');?></p></div>
                    </div>
                  </li>
                </ul>
          
                </div>
             </div>
       
            <div class="wrapptabbox">
          <div class="opt-conts">
            <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
            <ul class="form-elements insert-bg">
              <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo esc_js(str_replace('cs_pb_','',$name));?>','shortcode-item-<?php echo esc_js($cs_counter);?>','<?php echo esc_js($filter_element);?>')" ><?php esc_html_e('Insert','car-rental');?></a> </li>
            </ul>
            <div id="results-shortocde"></div>
            <?php } else {?>
            <ul class="form-elements noborder">
              <li class="to-label"></li>
              <li class="to-field">
                <input type="hidden" name="cs_orderby[]" value="deals" />
                <input type="button" value="<?php esc_html_e('Save','car-rental');?>" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
              </li>
            </ul>
            <?php }?>
          </div>
        </div>
         </div>
       </div>
    </div>
  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_deals', 'cs_pb_deals');
}
?>