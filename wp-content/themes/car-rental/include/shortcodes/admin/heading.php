<?php
/*
 *
 *@Shortcode Name : Heading
 *@retrun
 *
 */
 if ( ! function_exists( 'cs_pb_heading' ) ) {
    function cs_pb_heading($die = 0){
        global $cs_node, $post;
        $shortcode_element = '';
        $g_fonts = cs_get_google_fonts();
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
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $PREFIX = CS_SC_HEADING;
            $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        $defaults = array( 
		'heading_title' => '',
		'color_title'=>'',
		'heading_color' => '#000', 
		'class'=>'cs-heading-shortcode', 
		'heading_style'=>'1',
		'heading_style_type'=>'1', 
		'heading_size'=>'', 
		'font_weight'=>'', 
		'heading_font_style'=>'', 
		'heading_align'=>'center', 
		'heading_divider'=>'', 
		'heading_color' => '', 
		'heading_content_color' => ''
		);
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $heading_content = $output['0']['content'];
            else 
                $heading_content = '';
            $heading_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_heading';
            $coloumn_class = 'column_'.$heading_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
    ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="heading" data="<?php echo cs_element_size_data_array_index($heading_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$heading_element_size, '', 'h-square',$type='');?>
	  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>"  data-shortcode-template="[<?php echo esc_attr( CS_SC_HEADING );?> {{attributes}}]{{content}}[/<?php echo esc_attr( CS_SC_HEADING );?>]" style="display: none;">
		<div class="cs-heading-area">
			<h5><?php esc_html_e('Edit Heading Options','car-rental');?></h5>
			<a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')"
			class="cs-btnclose"><i class="icon-times"></i>
			</a>
		  </div>
		<div class="cs-pbwp-content">
		  <div class="cs-wrapp-clone cs-shortcode-wrapp">
			<?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php esc_html_e('Title','car-rental');?></label>
					  </li>
					  <li class="to-field">
						<input type="text" name="heading_title[]" class="txtfield" value="<?php echo cs_allow_special_char($heading_title);?>" />
					  </li>
				</ul>
				 	<!--<ul class="form-elements">
					  <li class="to-label">
						<label><?php //esc_html_e('Color Title','car-rental');?></label>
					  </li>
					  <li class="to-field">
						<input type="text" name="color_title[]" class="bg_color"  value="<?php echo esc_attr($color_title);?>" />
					  </li>
				</ul>-->
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php esc_html_e('Content','car-rental');?></label>
					  </li>
					  <li class="to-field">
						<textarea name="heading_content[]" rows="8" cols="40" data-content-text="cs-shortcode-textarea">
							<?php echo esc_textarea($heading_content);?>
						</textarea>
						<p><?php esc_html_e('Enter content here','car-rental');?></p>
					  </li>
				</ul>
				<ul class="form-elements">
				  <li class="to-label">
					<label><?php esc_html_e('Style','car-rental');?></label>
				  </li>
				  <li class="to-field select-style">
					<select name="heading_style[]">
					  <option <?php if($heading_style=="1")echo "selected";?> value="1" >h1</option>
					  <option <?php if($heading_style=="2")echo "selected";?> value="2" >h2</option>
					  <option <?php if($heading_style=="3")echo "selected";?> value="3" >h3</option>
					  <option <?php if($heading_style=="4")echo "selected";?> value="4" >h4</option>
					  <option <?php if($heading_style=="5")echo "selected";?> value="5" >h5</option>
					  <option <?php if($heading_style=="6")echo "selected";?> value="6" >h6</option>
					  <option <?php if($heading_style=="fancy")echo "selected";?> value="fancy" ><?php esc_html_e('Fancy','car-rental');?></option>

    				</select>
				  </li>
				</ul>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php esc_html_e('Font Size','car-rental');?></label>
					  </li>
					  <li class="to-field">
						<div class="cs-drag-slider" data-slider-min="0" data-slider-max="50" data-slider-step="1"
						 data-slider-value="<?php echo intval($heading_size)?>">
						</div>
						<input  class="cs-range-input"  name="heading_size[]" type="text" value="<?php echo esc_attr($heading_size)?>"   />
						<p><?php esc_html_e('add font size number for the heading','car-rental');?></p>
					  </li>
				</ul>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php esc_html_e('Align','car-rental');?></label>
					  </li>
					  <li class="to-field select-style">
						<select class="dropdown" name="heading_align[]">
						  <option value="left" <?php if($heading_align=='left'){echo 'selected="selected"';}?>><?php esc_html_e('Left','car-rental');?></option>
						  <option  value="center" <?php if($heading_align=='center'){echo 'selected="selected"';}?>><?php esc_html_e('Center','car-rental');?></option>
						  <option value="right" <?php if($heading_align=='right'){echo 'selected="selected"';}?>><?php esc_html_e('Right','car-rental');?></option>
						</select>
						<p><?php esc_html_e('Align the content position','car-rental');?></p>
					  </li>
				</ul>
				
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php esc_html_e('Font Style','car-rental');?></label>
					  </li>
					  <li class="to-field select-style">
						<select class="dropdown" name="heading_font_style[]">
						  <option value="normal" <?php if($heading_font_style=='normal'){echo 'selected="selected"';}?>><?php esc_html_e('Normal','car-rental');?></option>
						  <option value="italic" <?php if($heading_font_style=='italic'){echo 'selected="selected"';}?>><?php esc_html_e('Italic','car-rental');?></option>
						  <option value="oblique" <?php if($heading_font_style=='oblique'){echo 'selected="selected"';}?>><?php esc_html_e('Oblique','car-rental');?></option>
						</select>
						<p><?php esc_html_e('select a font style from the drop down','car-rental');?></p>
					  </li>
				</ul>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php esc_html_e('Heading Color','car-rental');?></label>
					  </li>
					  <li class="to-field">
						<input type="text" name="heading_color[]" class="bg_color"  value="<?php echo esc_attr($heading_color);?>" />
						<div class="left-info">
						  <p><?php esc_html_e('heading color for the heading element','car-rental');?></p>
						</div>
					  </li>
				</ul>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php esc_html_e('Content Color','car-rental');?></label>
					  </li>
					  <li class="to-field">
						<input type="text" name="heading_content_color[]" class="bg_color"  value="<?php echo esc_attr($heading_content_color);?>" />
						<div class="left-info">
						  <p><?php esc_html_e('set a content color for the heading element','car-rental');?></p>
						</div>
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
				  <input type="hidden" name="cs_orderby[]" value="heading" />
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
    add_action('wp_ajax_cs_pb_heading', 'cs_pb_heading');
}