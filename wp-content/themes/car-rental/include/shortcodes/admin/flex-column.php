<?php
/*
 *
 *@File : Flex column
 *@retrun
 *
 */	
 
 if ( ! function_exists( 'cs_pb_flex_column' ) ) {
    function cs_pb_flex_column($die = 0){
        global $cs_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $PREFIX = CS_SC_COLUMN;
        $counter = $_POST['counter'];
        $cs_counter = $_POST['counter'];
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
             $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );            
        }
        $defaults = array(
		'flex_column_section_title'=>'',
		'cs_image_url'=>'',
		'column_bg_color'=> '',
		'content_title_color'=> '',
		'cs_column_class'=>''
		);
        if(isset($output['0']['atts']))
            $atts = $output['0']['atts'];
        else 
            $atts = array();
        if(isset($output['0']['content']))
            $flex_column_text = $output['0']['content'];
        else 
            $flex_column_text = '';
        $flex_column_element_size = '25';
        foreach($defaults as $key=>$values){
            if(isset($atts[$key]))
                $$key = $atts[$key];
            else 
                $$key =$values;
         }
        $name = 'cs_pb_flex_column';
        $coloumn_class = 'column_'.$flex_column_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
        ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?>
		<?php echo esc_attr($shortcode_view);?>" item="flex_column" data="<?php echo cs_element_size_data_array_index($flex_column_element_size)?>" >
  		<?php cs_element_setting($name,$cs_counter,$flex_column_element_size, '', 'columns',$type='');?>
	  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?>
	  	<?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[<?php echo esc_attr( CS_SC_COLUMN );?> {{attributes}}]{{content}}[/<?php echo esc_attr( CS_SC_COLUMN );?>]" style="display: none;">
		<div class="cs-heading-area">
			  <h5><?php esc_html_e('Edit Flex Column Options','car-rental');?></h5>
			  <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')"
			  class="cs-btnclose"><i class="icon-times"></i>
			  </a>
		</div>
    <div class="cs-pbwp-content">
		  <div class="cs-wrapp-clone cs-shortcode-wrapp">
			<?php
			if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
				
                        <ul class="form-elements">
          <li class="to-label">
            <label><?php esc_html_e('Background Image','car-rental');
			$rand_id = rand(34, 443534);
			?></label>
          </li>
          <li class="to-field">
            <input id="cs_image_url<?php echo esc_attr($rand_id)?>" name="cs_image_url[]" type="hidden" class="" value="<?php echo esc_url($cs_image_url);?>"/>
            <input name="cs_image_url<?php echo esc_attr($rand_id)?>"  type="button" class="uploadMedia left" value="<?php esc_html_e('Browse','car-rental');?>"/>
            <div class="left-info">
            <p><?php esc_html_e('Browse the image','car-rental');?> </p>
            </div>
          </li>
        </ul>
        <ul class="form-elements">
            <li class="image-frame">
                <div class="page-wrap" style="overflow:hidden; display:<?php echo cs_allow_special_char($cs_image_url) && trim($cs_image_url) !='' ? 'inline' : 'none';?>" id="cs_image_url<?php echo cs_allow_special_char($rand_id)?>_box" >
                  <div class="gal-active">
                    <div class="dragareamain" style="padding-bottom:0px;">
                      <ul id="gal-sortable">
                        <li class="ui-state-default" id="">
                          <div class="thumb-secs"> <img src="<?php echo esc_url($cs_image_url);?>"  id="cs_image_url<?php echo cs_allow_special_char($rand_id);?>_img" width="100" height="150"  />
                            <div class="gal-edit-opts"> <a   href="javascript:del_media('cs_image_url<?php echo cs_allow_special_char($rand_id);?>')" class="delete"></a> </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
            </li>
        </ul>
        <ul class="form-elements">
            <li class="to-label">
              <label><?php esc_html_e('Content Color','car-rental');?></label>
            </li>
            <li class="to-field">
              <div class='input-sec'>
                <input type="text" name="content_title_color[]" class="bg_color"  value="<?php echo esc_attr($content_title_color)?>" />
                <div class='left-info'><p><?php esc_html_e('set a color for the counter icon','car-rental');?></p></div>
              </div>
            </li>
          </ul>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php esc_html_e('Background Color','car-rental');?></label>
            </li>
            <li class="to-field">
              <div class='input-sec'>
                <input type="text" name="column_bg_color[]" class="bg_color"  value="<?php echo esc_attr($column_bg_color)?>" />
                <div class='left-info'><p><?php esc_html_e('set a color for the counter icon','car-rental');?></p></div>
              </div>
            </li>
          </ul>
                <ul class="form-elements">
					<li class="to-label">
						<label><?php esc_html_e('Section Title','car-rental');?></label>
					</li>
					<li class="to-field">
						<input name="flex_column_section_title[]" type="text" value="<?php echo cs_allow_special_char($flex_column_section_title);?>"   />
						<p><?php esc_html_e('This is used for the one page navigation, to identify the section below. Give a title','car-rental');?></p>
					</li>
				</ul>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php esc_html_e('Column Text','car-rental');?></label>
					  </li>
					  <li class="to-field">
                        <textarea name="flex_column_text[]" data-content-text="cs-shortcode-textarea"><?php echo esc_textarea($flex_column_text)?></textarea>
                        <p><?php esc_html_e('Enter your content','car-rental');?></p>
					  </li>
				</ul>
			 
		  </div>
      <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
		  <ul class="form-elements insert-bg">
			<li class="to-field">
				<a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>','<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" ><?php esc_html_e('Insert','car-rental');?></a>
			</li>
		  </ul>
      <div id="results-shortocde"></div>
      <?php } else {?>
		  <ul class="form-elements noborder">
			<li class="to-label"></li>
			<li class="to-field">
			  <input type="hidden" name="cs_orderby[]" value="flex_column" />
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
    add_action('wp_ajax_cs_pb_flex_column', 'cs_pb_flex_column');
}
 