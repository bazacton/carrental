<?php
/**
 * Page Builder Functions 
 */
 
/**
 * @Generate Random String
 *
 *
 */
if ( ! function_exists( 'cs_generate_random_string' ) ) {
    function cs_generate_random_string($length = 3) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}
/**
 * @Page builder Element (shortcode(s))
 *
 *
 */
 if ( ! function_exists( 'cs_element_setting' ) ) {
    function cs_element_setting($name,$cs_counter,$element_size, $element_description='', $page_element_icon = 'icon-star',$type=''){
        $element_title = str_replace("cs_pb_","",$name);
		$elm_name	= ucwords(str_replace('_',' ',str_replace("cs_pb_","",$name)));
		if( $elm_name == 'Mailchimp' ) {
			$elm_name	= 'Mail chimp';
		} else if( $elm_name == 'Pricetable' ) {
			$elm_name	= 'Price Table';
		} else if( $elm_name == 'Promobox' ) {
			$elm_name	= 'Promo box';
		} else if( $elm_name == 'Progressbars' ) {
			$elm_name	= 'Progress Bars';
		}
        ?>
        <div class="column-in">
         	 <input type="hidden" name="<?php echo esc_attr($element_title); ?>_element_size[]" class="item" value="<?php echo esc_attr($element_size); ?>" >
          <!--<a href="javascript:;" onclick="javascript:_createclone(jQuery(this),'<?php echo esc_attr($cs_counter)?>', '', '')" class="options"><i class="icon-star"></i></a>-->
         	 <a href="javascript:;" onclick="javascript:_createpopshort(jQuery(this))" class="options"><i class="icon-gear"></i></a>
           	 <a href="#" class="delete-it btndeleteit"><i class="icon-trash-o"></i></a> &nbsp;
             <a class="decrement" onclick="javascript:decrement(this)"><i class="icon-minus4"></i></a> &nbsp; 
             <a class="increment" onclick="javascript:increment(this)"><i class="icon-plus3"></i></a> 
         	 <span> <i class="cs-icon <?php echo str_replace("cs_pb_","",$name);?>-icon"></i> 
          	 <strong><?php echo esc_attr( $elm_name );?></strong><br/>
          	 <?php echo esc_attr($element_description);?> </span>
        </div>
<?php
    }
}

/**
 * @Slugify The Text
 *
 */ 
function cs_slugify_text($str) {
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
    $clean = strtolower(trim($clean, '-'));
    $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);
    return $clean;
}
/**
 * @Page builder Element (shortcode(s))
 *
 */
if ( ! function_exists( 'cs_page_composer_elements' ) ) {
    function cs_page_composer_elements($element='',$icon='accordion-icon',$description='Dribble is community of designers'){
        echo '<i class="fa '.$icon.'"></i><span data-title="'.$element.'"> '.$element.'</span>';
    }
}
/**
 * @Page builder Sorting List
 *
 *
 */
if ( ! function_exists( 'cs_elements_categories' ) ) {
 	   function cs_elements_categories(){
        return array('typography'=>esc_html__('Typography','car-rental'),'commonelements'=>esc_html__('Common Elements','car-rental'),'mediaelement'=>esc_html__('Media Element','car-rental'),'contentblocks'=>esc_html__('Content Blocks','car-rental'),'loops'=>esc_html__('Loops','car-rental'));
    }
}
/**
 * @Page builder Ajax Element (shortcode(s))
 *
 *
 */
 if ( ! function_exists( 'cs_ajax_element_setting' ) ) {
    function cs_ajax_element_setting($name,$cs_counter,$element_size, $shortcode_element_id, $POSTID, $element_description='', $page_element_icon = '',$type=''){
        global $cs_node,$post;
        $element_title = str_replace("cs_pb_","",$name);
        ?>
        <div class="column-in">
        <input type="hidden" name="<?php echo esc_attr($element_title); ?>_element_size[]" class="item" value="<?php echo esc_attr($element_size); ?>" >
         <!--<a href="javascript:;" onclick="javascript:_createclone(jQuery(this),'<?php echo esc_attr($cs_counter)?>', '<?php echo esc_attr($shortcode_element_id);?>', '<?php echo absint($POSTID);?>')" class="options"><i class="icon-star"></i></a>--><a href="javascript:;" onclick="javascript:ajax_shortcode_widget_element(jQuery(this),'<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js($POSTID);?>','<?php echo esc_js($name);?>')" class="options"><i class="icon-gear"></i></a><a href="#" class="delete-it btndeleteit"><i class="icon-trash-o"></i></a> &nbsp; <a class="decrement" onclick="javascript:decrement(this)"><i class="icon-minus4"></i></a>
           &nbsp; <a class="increment" onclick="javascript:increment(this)"><i class="icon-plus3"></i></a> 
          <span> <i class="cs-icon <?php echo str_replace("cs_pb_","",$name);?>-icon"></i> 
          <strong>
          <?php 
           if($cs_node->getName() == 'page_element'){
                $element_name = $cs_node->element_name;
                $element_name = str_replace("cs-","",$element_name);
            } else {
                $element_name = $cs_node->getName();
            }
          	
			$elm_name	= ucwords(str_replace('_',' ',$element_name));
			
			if( $elm_name == 'Mailchimp' ) {
				$elm_name	= 'Mail chimp';
			} else if( $elm_name == 'Pricetable' ) {
				$elm_name	= 'Price Table';
			} else if( $elm_name == 'Promobox' ) {
				$elm_name	= 'Promo box';
			} else if( $elm_name == 'Progressbars' ) {
				$elm_name	= 'Progress Bars';
			}
			
            echo esc_attr( $elm_name );
			
			?>
            </strong><br/>
          <?php echo esc_attr($element_description);?> </span>
        </div>
<?php
    }
}
/**
 * @Page builder Section Settings
 *
 *
 */
if ( ! function_exists( 'cs_column_pb' ) ) {
    function cs_column_pb($die = 0, $shortcode=''){
         global $post, $cs_node, $cs_xmlObject, $cs_count_node, $column_container, $coloum_width;
		 $total_widget = 0;
         $i = 1;
         $cs_page_section_title = $cs_page_section_height = $cs_page_section_width = '';
         $cs_section_background_option = '';
         $cs_section_bg_image = '';
         $cs_section_bg_image_position = '';
	 $cs_section_bg_image_repeat = '';
         $cs_section_parallax = '';
         $cs_section_flex_slider = '';
         $cs_section_custom_slider = '';
         $cs_section_video_url = '';
         $cs_section_video_mute = '';
         $cs_section_video_autoplay = '';
         $cs_section_border_bottom = '0';
         $cs_section_border_top = '0';
         $cs_section_border_color = '#e0e0e0';
         $cs_section_padding_top = '0';
         $cs_section_padding_bottom = '0';
         $cs_section_margin_top = '0';
         $cs_section_margin_bottom = '0';
         $cs_section_css_id = '';
         $cs_section_view = 'container';
         $cs_layout = '';         
         $cs_sidebar_left = '';
         $cs_sidebar_right = ''; 
         $cs_section_bg_color = '';
        if ( isset( $column_container ) ){
            $column_attributes= $column_container->attributes();
             $column_class = $column_attributes->class;
             $cs_section_background_option = $column_attributes->cs_section_background_option;
             $cs_section_bg_image = $column_attributes->cs_section_bg_image;
             $cs_section_bg_image_position = $column_attributes->cs_section_bg_image_position;
	     $cs_section_bg_image_repeat = $column_attributes->cs_section_bg_image_repeat;
             $cs_section_flex_slider = $column_attributes->cs_section_flex_slider;
             $cs_section_custom_slider = $column_attributes->cs_section_custom_slider;
             $cs_section_video_url = $column_attributes->cs_section_video_url;     
             $cs_section_video_mute = $column_attributes->cs_section_video_mute;
             $cs_section_video_autoplay = $column_attributes->cs_section_video_autoplay;
             $cs_section_bg_color = $column_attributes->cs_section_bg_color; 
             $cs_section_parallax = $column_attributes->cs_section_parallax;
             $cs_section_padding_top = $column_attributes->cs_section_padding_top;
             $cs_section_padding_bottom = $column_attributes->cs_section_padding_bottom; 
             $cs_section_border_bottom = $column_attributes->cs_section_border_bottom;
             $cs_section_border_top = $column_attributes->cs_section_border_top;
             $cs_section_border_color = $column_attributes->cs_section_border_color;
             $cs_section_margin_top = $column_attributes->cs_section_margin_top;
             $cs_section_margin_bottom = $column_attributes->cs_section_margin_bottom;
             $cs_section_css_id = $column_attributes->cs_section_css_id;
             $cs_section_view = $column_attributes->cs_section_view;
             $cs_layout = $column_attributes->cs_layout;
             $cs_sidebar_left = $column_attributes->cs_sidebar_left;
             $cs_sidebar_right = $column_attributes->cs_sidebar_right; 
        }
        $style='';    
        if ( isset($_POST['action']) ) {
            $name = $_POST['action'];
            $cs_counter = $_POST['counter'];
            $total_column = $_POST['total_column'];
            $column_class = $_POST['column_class'];
            $postID = $_POST['postID'];
            $randomno = rand(34,3242432);
            $rand = rand(1,999);
            $style='';
        } else {
            $postID = $post->ID;
            $name = '';
            $cs_counter = '';
            $total_column = 0;
            $rand = rand(1,999);
            $randomno = rand(34,3242432);
            $name = $_REQUEST['action'];
            $style='style="display:none;"';
        }
        $cs_page_elements_name = cs_shortcode_names();
        $cs_page_categories_name =  cs_elements_categories();
        
    ?>
    <div class="cs-page-composer composer-<?php echo absint($rand)?>" id="composer-<?php echo absint($rand)?>" style="display:none">
          <div class="page-elements">
                <div class="cs-heading-area">
                      <h5> <i class="icon-plus-circle"></i><?php esc_html_e('Add Element','car-rental');?>  </h5>
                      <span class='cs-btnclose' onclick='javascript:removeoverlay("composer-<?php echo absint($rand)?>","append")'><i class="icon-times"></i></span> 
                  </div>
                <script>
                    jQuery(document).ready(function($){
                        cs_page_composer_filterable('<?php echo absint($rand)?>');
                    });
                </script>
            <div class="cs-filter-content">
              	<p><input type="text" id="quicksearch<?php echo absint($rand)?>" placeholder="<?php esc_html_e('Search','car-rental');?>" /></p>
                  <div class="cs-filtermenu-wrap">
                        <h6><?php esc_html_e('Filter by','car-rental');?></h6>
                        <ul class="cs-filter-menu" id="filters<?php echo absint($rand)?>">
                          <li data-filter="all" class="active"><?php esc_html_e('Show all','car-rental');?></li>
                          <?php foreach($cs_page_categories_name as $key=>$value){?>
                          <li data-filter="<?php echo esc_attr($key);?>"><?php echo esc_attr($value);?></li>
                          <?php }?>
                        </ul>
                  </div>
              <div class="cs-filter-inner" id="page_element_container<?php echo absint($rand)?>">
					<?php foreach($cs_page_elements_name as $key=>$value){?>
                    	<div class="element-item <?php echo esc_attr($value['categories']);?>"> 
                    		<a href='javascript:ajaxSubmitwidget("cs_pb_<?php echo esc_js($value['name']);?>","<?php echo esc_js($rand)?>")'>
                      			<?php cs_page_composer_elements($value['title'], $value['icon']); ?>
                      		</a> 
                      </div>
                    <?php }?>                    
              </div>
            </div>
          </div>
    </div>
	<?php 
    if(isset($shortcode) && $shortcode <> ''){
        ?>
        <a class="button" href="javascript:_createpop('composer-<?php echo esc_js($rand)?>', 'filter')"><i class="icon-plus-circle"></i><?php esc_html_e('CS: Insert shortcode','car-rental');?> </a>
    <?php
    } else {
    ?>
    <div id="<?php echo esc_attr($randomno);?>_del" class="column columnmain parentdeletesection column_100" >
      <div class="column-in"> <a class="button" href="javascript:_createpop('composer-<?php echo esc_js($rand)?>', 'filter')"><i class="icon-plus-circle"></i> <?php esc_html_e('Add Element','car-rental');?></a>
            <p> <a href="javascript:_createpop('<?php echo esc_js($column_class.$randomno);?>','filterdrag')" class="options">
                <i class="icon-gear"></i></a> &nbsp; <a href="#" class="delete-it btndeleteitsection"><i class="icon-trash-o"></i></a> &nbsp; 
             </p>
      </div>
      <div class="column column_container page_section <?php echo sanitize_html_class($column_class);?>" >
			<?php
                $parts = explode('_', $column_class);
                if ( $total_column > 0  ){
                    for ( $i = 1; $i <= $total_column; $i++ ) {
                    ?>
        <div class="dragarea" data-item-width="col_width_<?php echo esc_attr($parts[$i]);?>">
              <input name="total_widget[]" type="hidden" value="0" class="textfld" />
              <div class="draginner" id="counter_<?php echo absint($rand)?>"></div>
        </div>
        <?php 
            }
        }
        $i = 1;
        
        if ( isset( $column_container ) ) {
            global $wpdb;
            $total_column = count($column_container->children());
            $section = 0;
            $section_widget_element_num = 0;
            foreach ( $column_container->children() as $column ){
                $section++;
                $total_widget = count($column->children());
                ?>
                <div class="dragarea" data-item-width="col_width_<?php echo esc_attr($parts[$i])?>">
                      <div class="toparea">
                        <input name="total_widget[]" type="hidden" value="<?php echo esc_attr($total_widget)?>" class="textfld page-element-total-widget" />
                      </div>
                  <div class="draginner" id="counter_<?php echo absint($rand)?>">
                    <?php
                        $shortcode_element = '';
                        $abccc_golabal = 'Glo0ab testinggg';
                        $filter_element = 'filterdrag';
                        $shortcode_view = '';
                        $global_array = array();
                        $section_widget__element = 0;
                        foreach ( $column->children() as $cs_node ){
                            /*echo '<pre>';
                            print_r($cs_node);
                            echo '</pre>';*/
                            $section_widget__element++;
                            $shortcode_element_idd = $rand.'_'.$section_widget__element;
                            $global_array[] = $cs_node;
                            $cs_count_node++;
                            $cs_counter = $postID.$cs_count_node;
                            $a = $name = "cs_pb_".$cs_node->getName();
                            $coloumn_class = 'column_'.$cs_node->page_element_size;
                            $abbbbc = (string)$cs_node->cs_shortcode;
                            $type = '';
                            if($cs_node->getName() == 'page_element'){
                                $type = 'page_element';
                            }
                            ?>
                        <div id="<?php echo esc_attr($name.$cs_counter);?>_del" class="column  parentdelete  <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="<?php echo esc_attr($cs_node->getName());?>" data="<?php echo cs_element_size_data_array_index($cs_node->page_element_size)?>" >
                        <?php cs_ajax_element_setting($cs_node->getName(),$cs_counter,$cs_node->page_element_size,$shortcode_element_idd, $postID, $element_description='', $cs_node->getName().'-icon',$type);?>
                            <div class="cs-wrapp-class-<?php echo esc_attr($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" style="display: none;">
                                <div class="cs-heading-area">
                                    <h5>Edit  <?php echo esc_attr($cs_node->getName());?> Options</h5>
                                    <a href="javascript:;" onclick="javascript:_removerlay(jQuery(this))" class="cs-btnclose"><i class="icon-times"></i></a>
                                </div>
                                <?php 
                                echo '<input type="hidden"  class="cs-wiget-element-type"  id="shortcode_'.$name.$cs_counter.'" name="cs_widget_element_num[]" value="shortcode" />';
                                ?>
                                <div class="pagebuilder-data-load">
                                    <?php
                                        echo '<input type="hidden" name="cs_orderby[]" value="'.$cs_node->getName().'" />';
                                        echo '<textarea name="shortcode['.$cs_node->getName().'][]" style="display:none;" class="cs-textarea-val">'.esc_html($cs_node->cs_shortcode).'</textarea>';
                                     ?>
                                </div>
                             </div>
                        </div>
                        <?php
                        }
                        ?>
                  </div>
                </div>
        <?php
                $i++;
            }
        }
        ?>
      </div>
    <div id="<?php echo esc_attr($column_class.$randomno);?>" style="display:none">
        <div class="cs-heading-area">
          <h5><?php esc_html_e('Edit Page Section','car-rental');?></h5>
          <a href="javascript:removeoverlay('<?php echo esc_js($column_class.$randomno);?>','filterdrag')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
        <div class="cs-pbwp-content">
        	<div class="cs-wrapp-clone cs-shortcode-wrapp">
              <ul class="form-elements  ">
                <li class="to-label">
                  <label><?php esc_html_e('Background View','car-rental');?></label>
                </li>
                <li class="to-field">
                  <div class="input-sec">
                    <div class="select-style">
                      <select name="cs_section_background_option[]" class="dropdown" onchange="javascript:cs_section_background_settings_toggle(this.value,'<?php echo absint($randomno);?>')">
                        <option <?php if($cs_section_background_option=='no-image') echo "selected";?> value="no-image"><?php esc_html_e('None','car-rental');?></option>
                        <option <?php if($cs_section_background_option=='section-custom-background-image') echo "selected";?> value="section-custom-background-image"><?php esc_html_e('Background Image','car-rental');?></option>
                        <option <?php if($cs_section_background_option=='section-custom-slider') echo "selected";?> value="section-custom-slider"><?php esc_html_e('Custom Slider','car-rental');?></option>
                        <option  <?php if($cs_section_background_option=='section_background_video')echo "selected";?> value="section_background_video" > <?php esc_html_e('Video','car-rental');?></option>
                      </select>
                    </div>
                  </div>
                </li>
              </ul>
          <div class="meta-body noborder section-custom-background-image-<?php echo esc_attr($randomno);?>" style=" <?php if($cs_section_background_option == "section-custom-background-image"){echo "display:block";}else echo "display:none";?>" >
                <ul class="form-elements noborder">
                  <li class="to-label">
                    <label><?php esc_html_e('Background Image','car-rental');?></label>
                  </li>
                  <li class="to-field">
                    <input id="cs_section_bg_image<?php echo absint($rand);?>" name="cs_section_bg_image[]" type="hidden" class="" value="<?php echo esc_url($cs_section_bg_image);?>"/>
                    <input name="cs_section_bg_image<?php echo absint($rand);?>"  type="button" class="uploadMedia left" value="<?php esc_html_e('Browse','car-rental');?>"/>
                  </li>
                </ul>
                <div class="page-wrap" style="overflow:hidden; display:<?php echo esc_attr($cs_section_bg_image) && trim($cs_section_bg_image) !='' ? 'inline' : 'none';?>" id="cs_section_bg_image<?php echo absint($rand);?>_box" >
                  <div class="gal-active">
                    <div class="dragareamain" style="padding-bottom:0px;">
                      <ul id="gal-sortable">
                        <li class="ui-state-default" id="">
                          <div class="thumb-secs"> <img src="<?php echo esc_url($cs_section_bg_image);?>"  id="cs_section_bg_image<?php echo absint($rand);?>_img" width="100" height="150"  />
                            <div class="gal-edit-opts"> <a href="javascript:del_media('cs_section_bg_image<?php echo absint($rand);?>')" class="delete"></a> </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <ul class="form-elements noborder">
                  <li class="to-label">
                    <label><?php esc_html_e('Background Image Position','car-rental');?></label>
                  </li>
                  <li class="to-field">
                    <div class="input-sec">
                      <div class="select-style">
                        <select name="cs_section_bg_image_position[]" class="select_dropdown">
                          <option value=""><?php esc_html_e('Select position','car-rental');?></option>
                          <option value="left" <?php if ($cs_section_bg_image_position=='light')echo "selected";?>><?php esc_html_e('Left','car-rental');?></option>
                          <option value="right" <?php if ($cs_section_bg_image_position=='right')echo "selected";?>><?php esc_html_e('Right','car-rental');?></option>
                          <option value="center" <?php if ($cs_section_bg_image_position=='center')echo "selected";?>><?php esc_html_e('Center','car-rental');?></option>
                        </select>
                      </div>
                    </div>
                  </li>
                </ul>
                <ul class="form-elements noborder">
                  <li class="to-label">
                    <label><?php esc_html_e('Background Image Position','car-rental');?></label>
                  </li>
                  <li class="to-field">
                    <div class="input-sec">
                      <div class="select-style">
                        <select name="cs_section_bg_image_repeat[]" class="select_dropdown">
                          <option value="repeat" <?php if ($cs_section_bg_image_repeat=='repeat')echo "selected";?>><?php esc_html_e('Repeat','car-rental');?></option>
                          <option value="no-repeat" <?php if ($cs_section_bg_image_repeat=='no-repeat')echo "selected";?>><?php esc_html_e('No Repeat','car-rental');?></option>
                        </select>
                      </div>
                    </div>
                  </li>
                </ul>
          </div>
          <div class="meta-body noborder section-slider-<?php echo esc_attr($randomno);?>" style=" <?php if($cs_section_background_option == "section-slider"){echo "display:block";}else echo "display:none";?>" >
            <?php //cs_section_slider('section_field_name2');?>
          </div>
          <div class="meta-body noborder section-custom-slider-<?php echo esc_attr($randomno);?>" style=" <?php if($cs_section_background_option == "section-custom-slider"){echo "display:block";}else echo "display:none";?>" >
                <ul class="form-elements noborder">
                  <li class="to-label">
                    <label><?php esc_html_e('Custom Slider','car-rental');?></label>
                  </li>
                  <li class="to-field">
                    <div class="input-sec">
                        <input type="text" name="cs_section_custom_slider[]" class="txtfield" value="<?php echo esc_attr($cs_section_custom_slider);;?>" />
                    </div>
                  </li>
                </ul>
          </div>
          <div class="meta-body noborder section-background-video-<?php echo esc_attr($randomno);?>" style=" <?php if($cs_section_background_option == "section_background_video"){echo "display:block";}else echo "display:none";?>">
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php esc_html_e('Video Url','car-rental');?></label>
                  </li>
                  <li class="to-field">
                    <div class="input-sec">
                      <input id="cs_section_video_url_<?php echo esc_attr($randomno)?>" name="cs_section_video_url[]" value="<?php echo esc_url($cs_section_video_url);;?>" type="text" />
                      <label class="cs-browse">
                        <input name="cs_section_video_url_<?php echo esc_attr($randomno);?>" type="button" class="uploadMedia left" value="<?php esc_html_e('Browse','car-rental');?>" />
                      </label>
                    </div>
                    <div class="left-info">
                      <p><?php esc_html_e('Please enter Vimeo/Youtube Video Url','car-rental');?></p>
                    </div>
                  </li>
                </ul>
                <ul class="form-elements">
                <li class="to-label">
                  <label><?php esc_html_e('Enable Mute','car-rental');?></label>
                </li>
                <li class="to-field">
                  <div class="input-sec">
                    <div class="select-style">
                      <select name="cs_section_video_mute[]" class="select_dropdown">
                        <option value="yes" <?php if ($cs_section_video_mute=='yes')echo "selected";?>><?php esc_html_e('Yes','car-rental');?></option>
                        <option value="no" <?php if ($cs_section_video_mute=='no')echo "selected";?>><?php esc_html_e('No','car-rental');?></option>
                      </select>
                    </div>
                  </div>
                </li>
              </ul>
              <ul class="form-elements">
                <li class="to-label">
                  <label><?php esc_html_e('Video Auto Play','car-rental');?></label>
                </li>
                <li class="to-field">
                  <div class="input-sec">
                    <div class="select-style">
                      <select name="cs_section_video_autoplay[]" class="select_dropdown">
                        <option value="yes" <?php if ($cs_section_video_autoplay=='yes')echo "selected";?>><?php esc_html_e('Yes','car-rental');?></option>
                        <option value="no" <?php if ($cs_section_video_autoplay=='no')echo "selected";?>><?php esc_html_e('No','car-rental');?></option>
                      </select>
                    </div>
                  </div>
                </li>
              </ul>
          </div>
              <ul class="form-elements noborder">
                <li class="to-label">
                  <label><?php esc_html_e('Enable Parallax','car-rental');?></label>
                </li>
                <li class="to-field">
                  <div class="input-sec">
                    <div class="select-style">
                      <select name="cs_section_parallax[]" class="select_dropdown">
                        <option value="no" <?php if ($cs_section_parallax=='no')echo "selected";?>><?php esc_html_e('No','car-rental');?></option>
                        <option value="yes" <?php if ($cs_section_parallax=='yes')echo "selected";?>><?php esc_html_e('Yes','car-rental');?></option>
                      </select>
                    </div>
                  </div>
                </li>
              </ul>
              <ul class="form-elements">
                <li class="to-label">
                  <label><?php esc_html_e('Select View','car-rental');?></label>
                </li>
                <li class="to-field">
                  <div class="input-sec">
                    <div class="select-style">
                      <select name="cs_section_view[]" class="select_dropdown">
                        <option value="container" <?php if ($cs_section_view=='container')echo "selected";?>><?php esc_html_e('Box','car-rental');?></option>
                        <option value="wide" <?php if ($cs_section_view=='wide')echo "selected";?>><?php esc_html_e('Wide','car-rental');?></option>
                      </select>
                    </div>
                  </div>
                </li>
              </ul>
              <ul class="form-elements noborder">
                <li class="to-label">
                  <label><?php esc_html_e('Background Color','car-rental');?></label>
                </li>
                <li class="to-field">
                  <div class="input-sec">
                    <input type="text" name="cs_section_bg_color[]" class="bg_color" value="<?php if(isset($cs_section_bg_color)) echo esc_attr($cs_section_bg_color);?>" />
                  </div>
                </li>
              </ul>
              <ul class="form-elements">
                <li class="to-label">
                  <label><?php esc_html_e('Padding Top','car-rental');?></label>
                </li>
                <li class="to-field">
                  <div class="input-sec">
                    <div class="cs-drag-slider" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="<?php echo absint($cs_section_padding_top)?>"></div>
                    <input  class="cs-range-input"  name="cs_section_padding_top[]" type="text" value="<?php echo absint($cs_section_padding_top)?>"   />
                    <p><?php esc_html_e('Set the Padding top (In px)','car-rental');?></p>
                  </div>
                </li>
              </ul>
              <ul class="form-elements">
                <li class="to-label">
                  <label><?php esc_html_e('Padding Bottom','car-rental');?></label>
                </li>
                <li class="to-field">
                  <div class="input-sec">
                    <div class="cs-drag-slider" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="<?php echo absint($cs_section_padding_bottom);?>"></div>
                    <input  class="cs-range-input"  name="cs_section_padding_bottom[]" type="text" value="<?php echo absint($cs_section_padding_bottom);?>"   />
                    <p><?php esc_html_e('Set the Padding Bottom (In px)','car-rental');?></p>
                  </div>
                </li>
              </ul>
              <ul class="form-elements">
                <li class="to-label">
                  <label><?php esc_html_e('Margin Top','car-rental');?></label>
                </li>
                <li class="to-field">
                  <div class="input-sec">
                    <div class="cs-drag-slider" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="<?php echo cs_allow_special_char($cs_section_margin_top);?>"></div>
                    <input  class="cs-range-input"  name="cs_section_margin_top[]" type="text" value="<?php echo cs_allow_special_char($cs_section_margin_top);?>"   />
                    <p><?php esc_html_e('Set the Border Bottom (In px)','car-rental');?></p>
                  </div>
                </li>
              </ul>
              <ul class="form-elements">
                <li class="to-label">
                  <label><?php esc_html_e('Margin Bottom','car-rental');?></label>
                </li>
                <li class="to-field">
                  <div class="input-sec">
                    <div class="cs-drag-slider" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="<?php echo cs_allow_special_char($cs_section_margin_bottom);?>"></div>
                    <input  class="cs-range-input"  name="cs_section_margin_bottom[]" type="text" value="<?php echo cs_allow_special_char($cs_section_margin_bottom);?>"   />
                    <p><?php esc_html_e('Set the Margin Bottom (In px)','car-rental');?></p>
                  </div>
                </li>
              </ul>
              <ul class="form-elements">
                <li class="to-label">
                  <label><?php esc_html_e('Border Top','car-rental');?></label>
                </li>
                <li class="to-field">
                  <div class="input-sec">
                    <div class="cs-drag-slider" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="<?php echo absint($cs_section_border_top);?>"></div>
                    <input  class="cs-range-input"  name="cs_section_border_top[]" type="text" value="<?php echo absint($cs_section_border_top);?>"   />
                    <p><?php esc_html_e('Set the Border top (In px)','car-rental');?></p>
                  </div>
                </li>
              </ul>
              <ul class="form-elements">
                <li class="to-label">
                  <label><?php esc_html_e('Border Bottom','car-rental');?></label>
                </li>
                <li class="to-field">
                  <div class="input-sec">
                    <div class="cs-drag-slider" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="<?php echo absint($cs_section_border_bottom);?>"></div>
                    <input  class="cs-range-input"  name="cs_section_border_bottom[]" type="text" value="<?php echo absint($cs_section_border_bottom);?>"   />
                    <p><?php esc_html_e('Set the Border Bottom (In px)','car-rental');?></p>
                  </div>
                </li>
              </ul>
              <ul class="form-elements">
                <li class="to-label">
                  <label><?php esc_html_e('Border Color','car-rental');?></label>
                </li>
                <li class="to-field">
                  <div class="input-sec">
                    <input type="text" name="cs_section_border_color[]" class="bg_color" value="<?php echo esc_attr($cs_section_border_color);?>" />
                  </div>
                </li>
              </ul>
              <ul class="form-elements">
                <li class="to-label">
                  <label><?php esc_html_e('Custom Id','car-rental');?></label>
                </li>
                <li class="to-field">
                  <div class="input-sec">
                    <input type="text" name="cs_section_css_id[]" class="txtfield" value="<?php echo esc_attr($cs_section_css_id);?>" />
                  </div>
                </li>
              </ul>
              <div class="form-elements elementhiddenn">
                    <ul class="noborder">
                      <li class="to-label">
                        <label><?php esc_html_e('Select Layout','car-rental');?></label>
                      </li>
                      <li class="to-field">
                        <div class="meta-input">
                          <div class="meta-input pattern">
                            <div class='radio-image-wrapper'>
                              <input <?php if($cs_layout=="none")echo "checked"?> onclick="show_sidebar('none','<?php echo esc_js($randomno);?>')" type="radio" name="cs_layout[<?php echo esc_attr($rand);?>][]" class="radio_cs_sidebar" value="none" id="radio_1<?php echo esc_attr($randomno);?>" />
                              <label for="radio_1<?php echo esc_attr($randomno)?>"> <span class="ss"><img src="<?php echo esc_url(get_template_directory_uri().'/include/assets/images/no_sidebar.png')?>"  alt="no_sidebar" /></span> <span <?php if($cs_layout=="none")echo "class='check-list'"?> id="check-list"></span> </label>
                            </div>
                            <div class='radio-image-wrapper'>
                              <input <?php if($cs_layout=="right")echo "checked"?> onclick="show_sidebar('right','<?php echo esc_js($randomno)?>')" type="radio" name="cs_layout[<?php echo esc_attr($rand);?>][]" class="radio_cs_sidebar" value="right" id="radio_2<?php echo esc_attr($randomno);?>"  />
                              <label for="radio_2<?php echo esc_attr($randomno)?>"> <span class="ss"><img src="<?php echo esc_url(get_template_directory_uri().'/include/assets/images/sidebar_right.png')?>" alt="sidebar_right" /></span> <span <?php if($cs_layout=="right")echo "class='check-list'"?> id="check-list"></span> </label>
                            </div>
                            <div class='radio-image-wrapper'>
                              <input <?php if($cs_layout=="left")echo "checked"?> onclick="show_sidebar('left','<?php echo esc_attr($randomno);?>')" type="radio" name="cs_layout[<?php echo esc_attr($rand)?>][]" class="radio_cs_sidebar" value="left" id="radio_3<?php echo esc_attr($randomno);?>" />
                              <label for="radio_3<?php echo esc_attr($randomno);?>"> <span class="ss"><img src="<?php echo esc_url(get_template_directory_uri().'/include/assets/images/sidebar_left.png');?>" alt="sidebar_left" /></span> <span <?php if($cs_layout=="left")echo "class='check-list'"?> id="check-list"></span> </label>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
               	<ul class="meta-body" style=" <?php if($cs_layout == "left"){echo "display:block";}else echo "display:none";?>" id="<?php echo esc_attr($randomno);?>_sidebar_left" >
                      <li class="to-label">
                        <label><?php esc_html_e('Select Left Sidebar','car-rental');?></label>
                      </li>
                      <li class="to-field">
                        <select name="cs_sidebar_left[]" class="select_dropdown">
                          <?php
                                   global $wpdb, $cs_theme_options;
                                 //$cs_theme_options = $cs_theme_options;
                            //$cs_theme_option = get_option('cs_theme_options');
                            if ( isset($cs_theme_options['sidebar']) and count($cs_theme_options['sidebar']) > 0 ) {
                                foreach ( $cs_theme_options['sidebar'] as $sidebar ){?>
                            <option <?php if ($cs_sidebar_left==$sidebar)echo "selected";?> ><?php echo esc_attr($sidebar);;?></option>
                            <?php
                                }
                            }
                         ?>
                        </select>
                        <p> <?php esc_html_e('Add New Sidebar','car-rental');?> <a href="<?php echo esc_url(admin_url('themes.php?page=cs_theme_options#tab-manage-sidebars-show'))?>" target="_blank"><?php esc_html_e('Click Here','car-rental');?></a></p>
                      </li>
                </ul>
                	<ul class="meta-body" style=" <?php if($cs_layout == "right"){echo "display:block";}else echo "display:none";?>" id="<?php echo esc_attr($randomno);?>_sidebar_right" >
                      <li class="to-label">
                        <label><?php esc_html_e('Select Right Sidebar','car-rental');?></label>
                      </li>
                      <li class="to-field">
                        <select name="cs_sidebar_right[]" class="select_dropdown">
                          <?php
                            if ( isset($cs_theme_options['sidebar']) and count($cs_theme_options['sidebar']) > 0 ) {
                                foreach ( $cs_theme_options['sidebar'] as $sidebar ){
                                ?>
                          <option <?php if ($cs_sidebar_right==$sidebar)echo "selected";?> ><?php echo esc_attr($sidebar);?></option>
                          <?php
                                }
                            }
                            ?>
                        </select>
                        <p>><?php esc_html_e('Add New Sidebar','car-rental');?>  <a href="<?php echo esc_url(admin_url('themes.php?page=cs_theme_options#tab-manage-sidebars-show'));?>" target="_blank"><?php esc_html_e('Click Here','car-rental');?></a></p>
                      </li>
                </ul>
              </div>
              <ul class="form-elements noborder">
                <li class="to-label"></li>
                <li class="to-field">
                  <input type="button" value="<?php esc_html_e('Save','car-rental');?>" style="margin-right:10px;" onclick="javascript:removeoverlay('<?php echo esc_js($column_class.$randomno);?>','filterdrag')" />
                </li>
              </ul>
        </div>
        </div>
      </div>
      <input type="hidden" name="column_rand_id[]" value="<?php echo esc_attr($rand);?>" />
      <input type="hidden" name="column_class[]" value="<?php echo esc_attr($column_class);?>" />
      <input type="hidden" name="total_column[]" value="<?php echo esc_attr($total_column);?>" />
    </div>
<?php

        }
    
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_column_pb', 'cs_column_pb');
}

/**
 * @Media Pagination for slider/gallery in admin side
 *
 *
 */
if ( ! function_exists( 'media_pagination' ) ) {
    function media_pagination($id='',$func='clone'){
        foreach ( $_REQUEST as $keys=>$values) {
            $$keys = $values;
        }
        $records_per_page = 18;
        if ( empty($page_id) ) $page_id = 1;
        $offset = $records_per_page * ($page_id-1);
    
    ?>
<ul class="gal-list">
  <?php
        $query_images_args = array('post_type' => 'attachment', 'post_mime_type' =>'image', 'post_status' => 'inherit', 'posts_per_page' => -1,);
        $query_images = new WP_Query( $query_images_args );
        if ( empty($total_pages) ) $total_pages = count( $query_images->posts );
        $query_images_args = array('post_type' => 'attachment', 'post_mime_type' =>'image', 'post_status' => 'inherit', 'posts_per_page' => $records_per_page, 'offset' => $offset,);
        $query_images = new WP_Query( $query_images_args );
        $images = array();
        foreach ( $query_images->posts as $image) {
            $image_path = wp_get_attachment_image_src( $image->ID, array( get_option("thumbnail_size_w"),get_option("thumbnail_size_h") ) );

        ?>
  <li style="cursor:pointer"><img src="<?php echo esc_url($image_path[0]);?>" onclick="javascript:<?php echo esc_attr($func);?>('<?php echo esc_js($image->ID)?>','gal-sortable-<?php echo esc_js($id);?>')" alt="sortable" /></li>
  <?php } ?>
</ul>
<br />
<div class="pagination-cus">
      <ul>
        <?php
            if ( $page_id > 1 ) echo "<li><a href='javascript:show_next(".($page_id-1).",$total_pages)'>esc_html__('Prev','car-rental')</a></li>";
                for ( $i = 1; $i <= ceil($total_pages/$records_per_page); $i++ ) {
                    if ( $i <> $page_id ) echo "<li><a href='javascript:show_next($i,$total_pages)'>" . $i . "</a></li> ";
                    else echo "<li class='active'><a>" . $i . "</a></li>";
                }
            if ( $page_id < $total_pages/$records_per_page ) echo "<li><a href='javascript:show_next(".($page_id+1).",$total_pages)'>esc_html__('Next','car-rental')</a></li>";
        ?>
      </ul>
</div>
<?php
        if ( isset($_POST['action']) ) die();
    }
    add_action('wp_ajax_media_pagination', 'media_pagination');
}

/**
 * @Media Slider Pagination
 *
 *
 */
if ( ! function_exists( 'cs_slider_media_pagination' ) ) {
    function cs_slider_media_pagination($id='',$func='clone'){
      
        foreach ( $_REQUEST as $keys=>$values) {
            $$keys = $values;
        }
        $records_per_page = 18;
        if ( empty($page_id) ) $page_id = 1;
        $offset = $records_per_page * ($page_id-1);
    
    ?>
    <ul class="gal-list">
      <?php
            $query_images_args = array('post_type' => 'attachment', 'post_mime_type' =>'image', 'post_status' => 'inherit', 'posts_per_page' => -1,);
            $query_images = new WP_Query( $query_images_args );
            if ( empty($total_pages) ) $total_pages = count( $query_images->posts );
            $query_images_args = array('post_type' => 'attachment', 'post_mime_type' =>'image', 'post_status' => 'inherit', 'posts_per_page' => $records_per_page, 'offset' => $offset,);
            $query_images = new WP_Query( $query_images_args );
            $images = array();
            foreach ( $query_images->posts as $image) {
                $image_path = wp_get_attachment_image_src( $image->ID, array( get_option("thumbnail_size_w"),get_option("thumbnail_size_h") ) );
            ?>
      <li style="cursor:pointer"><img src="<?php echo esc_url($image_path[0]);?>" onclick="javascript:slider('<?php echo esc_js($image->ID)?>','gal-sortable-<?php echo esc_js($id);?>')" alt="sortable" /></li>
      <?php  } ?>
    </ul>
<br />
<div class="pagination-cus">
      <ul>
        <?php
            if ( $page_id > 1 ) echo "<li><a href='javascript:slider_show_next(".($page_id-1).",$total_pages)'>esc_html__('Prev','car-rental')</a></li>";
                for ( $i = 1; $i <= ceil($total_pages/$records_per_page); $i++ ) {
                    if ( $i <> $page_id ) echo "<li><a href='javascript:slider_show_next($i,$total_pages)'>" . $i . "</a></li> ";
                    else echo "<li class='active'><a>" . $i . "</a></li>";
                }
            if ( $page_id < $total_pages/$records_per_page ) echo "<li><a href='javascript:slider_show_next(".($page_id+1).",$total_pages)'>esc_html__('Next','car-rental')</a></li>";
    
            ?>
      </ul>
</div>
<?php
        if ( isset($_POST['action']) ) die();
    }
    add_action('wp_ajax_cs_slider_media_pagination', 'cs_slider_media_pagination');
}
/**
 * @Make a copy of media image for slider start
 *
 *
 */
if ( ! function_exists( 'cs_slider_clone' ) ) {
    function cs_slider_clone(){
        global $cs_node, $cs_counter;
        if( isset($_POST['action']) ) {
            $cs_node = array();
            $cs_node['cs_slider_title'] 	  = '';
            $cs_node['cs_slider_description'] = '';
            $cs_node['cs_slider_link'] 		  = '';
            $cs_node['cs_slider_link_target'] = '';
            $cs_node['slider_use_image_as']   = '';
            $cs_node['slider_video_code'] 	  = '';
        }
        if ( isset($_POST['counter']) ) $cs_counter = $_POST['counter'];
        if ( isset($_POST['path']) ) $cs_node['cs_slider_path'] = $_POST['path'];
    
    ?>
<li class="ui-state-default" id="<?php echo esc_attr($cs_counter)?>">
      <div class="thumb-secs">
        <?php $image_path = wp_get_attachment_image_src( $cs_node['cs_slider_path'], array( get_option("thumbnail_size_w"),get_option("thumbnail_size_h") ) );?>
        <img src="<?php echo esc_url($image_path[0])?>" alt="$image_path">
        <div class="gal-edit-opts"> 
          <a href="javascript:slidedit(<?php echo esc_attr($cs_counter)?>)" class="edit"></a> <a href="javascript:del_this('wrapper_post_detail_slider',<?php echo esc_js($cs_counter)?>)" class="delete"></a> </div>
  </div>
  <div class="poped-up" id="edit_<?php echo esc_attr($cs_counter)?>">
    <div class="cs-heading-area">
      <h5><?php esc_html_e('Edit Options','car-rental');?></h5>
      <a href="javascript:slideclose(<?php echo esc_js($cs_counter)?>)" class="closeit">&nbsp;</a>
      <div class="clear"></div>
    </div>
    <div class="cs-pbwp-content">
          <ul class="form-elements">
            <li class="to-label">
              <label><?php esc_html_e('Image Title','car-rental');?></label>
            </li>
            <li class="to-field">
              <input type="text" name="cs_slider_title[]" value="<?php echo htmlspecialchars($cs_node['cs_slider_title'])?>" class="txtfield" />
            </li>
          </ul>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php esc_html_e('Image Description','car-rental');?></label>
            </li>
            <li class="to-field">
              <textarea class="txtarea" name="cs_slider_description[]"><?php echo htmlspecialchars($cs_node['cs_slider_description'])?></textarea>
            </li>
          </ul>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php esc_html_e('Link','car-rental');?></label>
            </li>
            <li class="to-field">
              <input type="text" name="cs_slider_link[]" value="<?php echo htmlspecialchars($cs_node['cs_slider_link'])?>" class="txtfield" />
            </li>
          </ul>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php esc_html_e('Link Target','car-rental');?></label>
            </li>
            <li class="to-field">
              <select name="cs_slider_link_target[]" class="select_dropdown">
                <option <?php if($cs_node['link_target']=="_self")echo "selected";?> >_self</option>
                <option <?php if($cs_node['link_target']=="_blank")echo "selected";?> >_blank</option>
              </select>
              <p><?php esc_html_e('Please select image target','car-rental');?></p>
            </li>
          </ul>
          <ul class="form-elements">
            <li class="to-label"></li>
            <li class="to-field">
              <input type="hidden" name="cs_slider_path[]" value="<?php echo esc_attr($cs_node['cs_slider_path'])?>" />
              <input type="button" value="Submit" onclick="javascript:slideclose(<?php echo esc_js($cs_counter)?>)" class="close-submit" />
            </li>
          </ul>
      <div class="clear"></div>
    </div>
  </div>
</li>
<?php
        if ( isset($_POST['action']) ) die();
    }
    add_action('wp_ajax_slider_clone', 'cs_slider_clone');
}


/**
 * @Make a copy of media image for gallery start
 *
 *
 */
if ( ! function_exists( 'cs_gallery_clone' ) ) {
    function cs_gallery_clone($clone_field_name = ''){
        global $cs_node, $cs_counter;
        if( isset($_POST['action']) ) {
            $cs_node = array();
            $cs_node['title'] 				= "";
            $cs_node['use_image_as']  		= "";
            $cs_node['video_code'] 			= "";
            $cs_node['link_url'] 			= "";
            $cs_node['use_image_as_db']   	= "";
            $cs_node['link_url_db'] 		= "";
        }
        if ( isset($_POST['counter']) ) $cs_counter = $_POST['counter'];
        if ( isset($_POST['path']) ) $cs_node['path'] = $_POST['path'];
    ?>
<li class="ui-state-default" id="<?php echo esc_attr($cs_counter);?>">
  <div class="thumb-secs">
    <?php $image_path = wp_get_attachment_image_src( $cs_node['path'], array( get_option("thumbnail_size_w"),get_option("thumbnail_size_h") ) );?>
    <img src="<?php echo esc_url($image_path[0]);?>" alt="image_path">
        <div class="gal-edit-opts"> 
          <a href="javascript:galedit(<?php echo esc_js($cs_counter)?>)" class="edit"></a> <a href="javascript:del_this('wrapper_thumb_slider',<?php echo esc_js($cs_counter);?>)" class="delete"></a>
         </div>
  </div>
 	 <div class="poped-up" id="edit_<?php echo esc_attr($cs_counter);?>">
        <div class="cs-heading-area">
          <h5><?php esc_html_e('Edit Options','car-rental');?></h5>
          <a href="javascript:galclose(<?php echo esc_attr($cs_counter);?>)" class="closeit">&nbsp;</a>
         </div>
        <div class="cs-pbwp-content">
              <ul class="form-elements">
                    <li class="to-label">
                      <label><?php esc_html_e('Image Title','car-rental');?></label>
                    </li>
                    <li class="to-field">
                      <input type="text" name="<?php echo esc_attr($clone_field_name);?>title[]" value="<?php echo htmlspecialchars($cs_node['title'])?>" class="txtfield" />
                    </li>
              </ul>
              <ul class="form-elements">
                    <li class="to-label">
                      <label><?php esc_html_e('Use Image As','car-rental');?></label>
                    </li>
                    <li class="to-field">
                      <select name="<?php echo esc_attr($clone_field_name);?>use_image_as[]" class="select_dropdown" onchange="cs_toggle_gal(this.value, <?php echo esc_js($cs_counter)?>)">
                        <option <?php if($cs_node['use_image_as']=="0")echo "selected";?> value="0"><?php esc_html_e('LightBox to current thumbnail','car-rental');?></option>
                        <option <?php if($cs_node['use_image_as']=="1")echo "selected";?> value="1"><?php esc_html_e('LightBox to Video','car-rental');?></option>
                        <option <?php if($cs_node['use_image_as']=="2")echo "selected";?> value="2"><?php esc_html_e('Url','car-rental');?></option>
                      </select>
                      <p><?php esc_html_e('Please select Image link where it will go','car-rental');?></p>
                    </li>
              </ul>
              <ul class="form-elements" id="video_code<?php echo esc_attr($cs_counter);?>" <?php if($cs_node['use_image_as']=="0" or $cs_node['use_image_as']=="" or $cs_node['use_image_as']=="2")echo 'style="display:none"';?> >
                    <li class="to-label">
                      <label><?php esc_html_e('Video URL','car-rental');?></label>
                    </li>
                    <li class="to-field">
                      <input type="text" name="<?php echo esc_attr($clone_field_name);?>video_code[]" value="<?php echo htmlspecialchars($cs_node['video_code'])?>" class="txtfield" />
                      <p><?php esc_html_e('Enter Specific Video Url Youtube or Vimeo','car-rental');?></p>
                    </li>
              </ul>
              <ul class="form-elements" id="<?php echo esc_attr($clone_field_name);?>link_url<?php echo esc_attr($cs_counter)?>" <?php if($cs_node['use_image_as']=="0" or $cs_node['use_image_as']=="" or $cs_node['use_image_as']=="1")echo 'style="display:none"';?> >
                    <li class="to-label">
                      <label><?php esc_html_e('Url','car-rental');?></label>
                    </li>
                    <li class="to-field">
                      <input type="text" name="<?php echo esc_attr($clone_field_name);?>link_url[]" value="<?php echo htmlspecialchars($cs_node['link_url'])?>" class="txtfield" />
                      <p><?php esc_html_e('Enter Specific Link','car-rental');?></p>
                    </li>
              </ul>
              <ul class="form-elements">
                    <li class="to-label"></li>
                    <li class="to-field">
                      <input type="hidden" name="<?php echo esc_attr($clone_field_name);?>path[]" value="<?php echo esc_attr($cs_node['path']);?>" />
                      <input type="button" onclick="javascript:galclose(<?php echo esc_attr($cs_counter);?>)" value="Submit" class="close-submit" />
                    </li>
              </ul>
          <div class="clear"></div>
        </div>
  </div>
</li>
<?php
        if ( isset($_POST['action']) ) die();
    }
    add_action('wp_ajax_gallery_clone', 'cs_gallery_clone');
}

/**
 * @Section element Size(s)
 *
 *
 */
if ( ! function_exists( 'element_size_data_array_index' ) ) {
	function element_size_data_array_index($size){
		if ( $size == "" or $size == 100 ) return 0;
		else if ( $size == 75 ) return 1;
		else if ( $size == 67 ) return 2;
		else if ( $size == 50 ) return 3;
		else if ( $size == 33 ) return 4;
		else if ( $size == 25 ) return 5;
	
	}
}

/**
 * @Section element Size(s)
 *
 *
 */
if ( ! function_exists( 'cs_element_size_data_array_index' ) ) {
    function cs_element_size_data_array_index($size){
        if ( $size == "" or $size == 100 ) return 0;
        else if ( $size == 75 ) return 1;
        else if ( $size == 67 ) return 2;
        else if ( $size == 50 ) return 3;
        else if ( $size == 33 ) return 4;
        else if ( $size == 25 ) return 5;
    
    }
}

/**
 * @Get  all Categories of posts or Custom posts
 *
 *
 */
if ( ! function_exists( 'cs_show_all_cats' ) ) {
    function cs_show_all_cats($parent, $separator, $selected = "", $taxonomy) {
        if ($parent == "") {
            global $wpdb;
            $parent = 0;
        }
        else
        $separator .= " &ndash; ";
        $args = array(
            'parent'      => $parent,
            'hide_empty' => 0,
            'taxonomy'      => $taxonomy
        );
        $categories = get_categories($args);
        foreach ( $categories as $category ) {
            ?>
        <option <?php if ( $selected == $category->slug ) echo "selected"; ?> value="<?php echo esc_attr($category->slug); ?>"><?php echo esc_attr($separator . $category->cat_name); ?></option>
<?php
        cs_show_all_cats($category->term_id, $separator, $selected, $taxonomy);
        }
    }
}


/**
 * @Add Social Icons
 *
 *
 */
$counter_icon = 0;
if ( ! function_exists( 'cs_add_social_icon' ) ) {
    function cs_add_social_icon(){
    
        $template_path = get_template_directory_uri() . '/include/assets/scripts/media_upload.js';
    
        wp_enqueue_script('my-upload2', $template_path, array('jquery', 'media-upload', 'thickbox', 'jquery-ui-droppable', 'jquery-ui-datepicker', 'jquery-ui-slider', 'wp-color-picker'));
        if($_POST['social_net_awesome']){
             
            $icon_awesome = $_POST['social_net_awesome'];
        }
        $socail_network=get_option('cs_social_network');
        echo '<tr id="del_' .str_replace(' ','-',$_POST['social_net_tooltip']).'">
    
            <td>';if(isset($icon_awesome) and $icon_awesome<>''){;echo '<i style="color:'.$_POST['social_font_awesome_color'].'!important;" class="fa '.$_POST['social_net_awesome'].' icon-2x"></i>';}else{;echo '<img width="50" src="' .esc_url($_POST['social_net_icon_path']). '">';}echo '</td> 
            
            <td>' .$_POST['social_net_tooltip']. '</td> 
    
            <td><a href="#">' .$_POST['social_net_url'].'</a></td> 
    
            <td class="centr"> 
                <a class="remove-btn" onclick="javascript:return confirm(\'Are you sure! You want to delete this\')" href="javascript:social_icon_del(\''.str_replace(' ','-',$_POST['social_net_tooltip']).'\')"><i class="icon-times"></i></a>
                 <a href="javascript:cs_toggle(\''.str_replace(' ','-',$_POST['social_net_tooltip']).'\')"><i class="icon-edit"></i></a>
            </td></tr>
    
        </tr>';
        
        echo '<tr id="'.str_replace(' ','-',$_POST['social_net_tooltip']).'" style="display:none">
                <td colspan="3"><ul class="form-elements">
                <li><a onclick="cs_toggle(\''.str_replace(' ','-',$_POST['social_net_tooltip']).'\')"><img src="'.esc_url(get_template_directory_uri()).'/include/assets/images/close-red.png" /></a></li>
                  <li class="to-label">
                      <label>"'.esc_html__('Title','car-rental').'"</label>
                    </li>
                    <li class="to-field">
                      <input class="small" type="text" id="social_net_tooltip" name="social_net_tooltip[]" value="'.$_POST['social_net_tooltip'].'"  />
                      <p>esc_html__("Please enter text for icon tooltip","car-rental").</p>
                    </li>
                    <li class="to-label">
                      <label>"'.esc_html__('Url','car-rental').'"</label>
                    </li>
                    <li class="to-field">
                      <input class="small" type="text" id="social_net_url" name="social_net_url[]" value="'.$_POST['social_net_url'].'"/>
                      <p>"'.esc_html__('Please enter full Url','car-rental').'"</p>
                    </li>
                    <li class="full">&nbsp;</li>
                    <li class="to-label">
                      <label>"'.esc_html__('Icon Path','car-rental').'"</label>
                    </li>
                    <li class="to-field">
                      <input id="social_net_icon_path'.$counter_icon.'" name="social_net_icon_path[]" value="'.$_POST['social_net_icon_path'].'" type="text" class="small" />
                      <label class="browse-icon"><input id="social_net_icon_path'.$counter_icon.'" name="social_net_icon_path'.$i.'" type="button" class="uploadMedia left" value="'.esc_html__('Browse','car-rental').'"/></label>
                    </li>
                    
                    <li style="padding: 10px 0px 20px;" class="full">
                       <ul id="cs_infobox_networks'.$counter_icon.'">
                          <li class="to-label">
                            <label>"'.esc_html__('Fontawsome Icon','car-rental').'"</label>
                          </li>
                          <li class="to-field">'.cs_fontawsome_theme_options($_POST['social_net_awesome'],"networks".$counter_icon,'social_net_awesome').'
                            
                          </li>
                       </ul>
                      </li>
                    <li class="to-label">
                      <label>"'.esc_html__('Font Awesome Color','car-rental').'"<span></span></label>
                    </li>
                    <li class="to-field">
                      <div class="input-sec">
                      <input type="text" name="social_font_awesome_color[]" id="social_font_awesome_color" value="'.$_POST['social_font_awesome_color'].'" class="bg_color" data-default-color="'.$_POST['social_font_awesome_color'].'" /></div>
                      <div class="left-info">
                          <p></p>
                      </div>
                    </li>
                    <li class="full">&nbsp;</li>
                    
                  </ul></td>
              </tr>';
              $counter_icon++;
        die;
    
    }
    add_action('wp_ajax_cs_add_social_icon', 'cs_add_social_icon');
}

// Fontawsome icon box
if ( ! function_exists( 'cs_fontawsome_icons_box') ) {
    function cs_fontawsome_icons_box($icon_value='',$id='',$name=''){
        ob_start();
        ?>
        <script>
            jQuery(document).ready(function($) {
                
            var e9_element = $('#e9_element_<?php echo cs_allow_special_char($id);?>').fontIconPicker({
                theme: 'fip-bootstrap'
                });
                    // Add the event on the button
                $('#e9_buttons_<?php echo cs_allow_special_char($id);?> button').on('click', function(e) {
                        e.preventDefault();
                        // Show processing message
                        $(this).prop('disabled', true).html('<i class="icon-cog demo-animate-spin"></i> Please wait...');
                        $.ajax({
                            url: '<?php echo get_template_directory_uri();?>/include/assets/icon/js/selection.json',
                            type: 'GET',
                            dataType: 'json'
                        })
                        .done(function(response) {
                            // Get the class prefix
                            var classPrefix = response.preferences.fontPref.prefix,
                                icomoon_json_icons = [],
                                icomoon_json_search = [];
                            $.each(response.icons, function(i, v) {
                                icomoon_json_icons.push( classPrefix + v.properties.name );
                                if ( v.icon && v.icon.tags && v.icon.tags.length ) {
                                    icomoon_json_search.push( v.properties.name + ' ' + v.icon.tags.join(' ') );
                                } else {
                                    icomoon_json_search.push( v.properties.name );
                                }
                            });
                            // Set new fonts on fontIconPicker
                            e9_element.setIcons(icomoon_json_icons, icomoon_json_search);
                            // Show success message and disable
                            $('#e9_buttons_<?php echo cs_allow_special_char($id);?> button').removeClass('btn-primary').addClass('btn-success').text('Successfully loaded icons').prop('disabled', true);
                        })
                        .fail(function() {
                            // Show error message and enable
                            $('#e9_buttons_<?php echo cs_allow_special_char($id);?> button').removeClass('btn-primary').addClass('btn-danger').text('Error: Try Again?').prop('disabled', false);
                        });
                        e.stopPropagation();
                    });
                });
                
                jQuery("#e9_buttons_<?php echo cs_allow_special_char($id);?> button").click();
        </script>
        <input type="text" id="e9_element_<?php echo cs_allow_special_char($id);?>" name="<?php echo cs_allow_special_char($name);?>[]" value="<?php echo cs_allow_special_char($icon_value);?>"/>
        <span id="e9_buttons_<?php echo cs_allow_special_char($id);?>" style="display:none">
            <button autocomplete="off" type="button" class="btn btn-primary">Load from IcoMoon selection.json</button>
        </span>
    <?php 
        $fontawesome = ob_get_clean();
        echo cs_allow_special_char($fontawesome);
    }
}


// Fontawsome icon box for Theme Options
if ( ! function_exists( 'cs_fontawsome_theme_options') ) {
    function cs_fontawsome_theme_options($icon_value='',$id='',$name=''){
        ob_start();
        ?>
        <script>
            jQuery(document).ready(function($) {
                
                var e9_element = $('#e9_element_<?php echo cs_allow_special_char($id);?>').fontIconPicker({
                theme: 'fip-bootstrap'
                });
                    // Add the event on the button
                $('#e9_buttons_<?php echo cs_allow_special_char($id);?> button').on('click', function(e) {
                        e.preventDefault();
                        // Show processing message
                        $(this).prop('disabled', true).html('<i class="icon-cog demo-animate-spin"></i> Please wait...');
                        $.ajax({
                            url: '<?php echo get_template_directory_uri();?>/include/assets/icon/js/selection.json',
                            type: 'GET',
                            dataType: 'json'
                        })
                        .done(function(response) {
                            // Get the class prefix
                            var classPrefix = response.preferences.fontPref.prefix,
                                icomoon_json_icons = [],
                                icomoon_json_search = [];
                            $.each(response.icons, function(i, v) {
                                icomoon_json_icons.push( classPrefix + v.properties.name );
                                if ( v.icon && v.icon.tags && v.icon.tags.length ) {
                                    icomoon_json_search.push( v.properties.name + ' ' + v.icon.tags.join(' ') );
                                } else {
                                    icomoon_json_search.push( v.properties.name );
                                }
                            });
                            // Set new fonts on fontIconPicker
                            e9_element.setIcons(icomoon_json_icons, icomoon_json_search);
                            // Show success message and disable
                            $('#e9_buttons_<?php echo cs_allow_special_char($id);?> button').removeClass('btn-primary').addClass('btn-success').text('Successfully loaded icons').prop('disabled', true);
                        })
                        .fail(function() {
                            // Show error message and enable
                            $('#e9_buttons_<?php echo cs_allow_special_char($id);?> button').removeClass('btn-primary').addClass('btn-danger').text('Error: Try Again?').prop('disabled', false);
                        });
                        e.stopPropagation();
                    });
                
                jQuery("#e9_buttons_<?php echo cs_allow_special_char($id);?> button").click();
            });
                
                
        </script>
        <input type="text" id="e9_element_<?php echo cs_allow_special_char($id);?>" name="<?php echo cs_allow_special_char($name);?>[]" value="<?php echo cs_allow_special_char($icon_value);?>"/>
        <span id="e9_buttons_<?php echo cs_allow_special_char($id);?>" style="display:none">
            <button autocomplete="off" type="button" class="btn btn-primary">Load from IcoMoon selection.json</button>
        </span>
    <?php 
    
        $fontawesome = ob_get_clean();
        return $fontawesome;
    }
}

/**
 * @Get  all Categories of posts or Custom posts
 *
 *
 */
if ( ! function_exists( 'show_all_cats' ) ) {
	function show_all_cats($parent, $separator, $selected = "", $taxonomy) {
		if ($parent == "") {
			global $wpdb;
			$parent = 0;
		}
		else
		$separator .= " &ndash; ";
		$args = array(
			'parent' => $parent,
			'hide_empty' => 0,
			'taxonomy' => $taxonomy
		);
		$categories = get_categories($args);
		foreach ($categories as $category) {
			?>
		<option <?php if ($selected == $category->slug) echo "selected"; ?> value="<?php echo esc_attr($category->slug); ?>"><?php echo esc_attr($separator . $category->cat_name); ?></option>
<?php
		show_all_cats($category->term_id, $separator, $selected, $taxonomy);
		}
	}
}

/**
 * @Add Banner Fields
 *
 */
if ( ! function_exists( 'cs_add_banner_fields' ) ) {
    function cs_add_banner_fields(){
        $cs_rand_id = rand(45764,565468990);
        $cs_top_banner_selected = $_POST['banner_field_style'] == 'top_banner' ? 'selected' : '';
        $cs_bottom_banner_selected = $_POST['banner_field_style'] == 'bottom_banner' ? 'selected' : '';
        $cs_sidebar_banner_selected = $_POST['banner_field_style'] == 'sidebar_banner' ? 'selected' : '';
        $cs_vertical_banner_selected = $_POST['banner_field_style'] == 'vertical_banner' ? 'selected' : '';
        // Banner Type Check
        $cs_image_banner_selected = $_POST['banner_field_type'] == 'image' ? 'selected' : '';
        $cs_code_banner_selected = $_POST['banner_field_type'] == 'code' ? 'selected' : '';
        // Banner Type Display Block Check
        $cs_baner_image_display = $_POST['banner_field_type'] == 'image' ? 'block' : 'none';
        $cs_baner_code_display = $_POST['banner_field_type'] == 'code' ? 'block' : 'none';
        // Target Check
        $cs_self_target_selected = $_POST['banner_field_url_target'] == '_self' ? 'selected' : '';
        $cs_blank_target_selected = $_POST['banner_field_url_target'] == '_blank' ? 'selected' : '';
                                  
        if( $_POST['banner_field_style'] == 'top_banner' ) {
            $cs_banner_group = 'Top';
        }else if( $_POST['banner_field_style'] == 'bottom_banner' ) {
            $cs_banner_group = 'Bottom';
        }else if( $_POST['banner_field_style'] == 'sidebar_banner' ) {
            $cs_banner_group = 'Sidebar';
        }else {
            $cs_banner_group = 'Vertical';
        }
                                  
        echo '<tr id="del_' .cs_slugify_text($_POST['banner_field']).'">
                <td>' .$_POST['banner_field']. '</td>
                <td>' .$cs_banner_group. '</td>';
                if($_POST['banner_field_type'] == 'image'){
                    echo '<td><img src="' . esc_url($_POST['banner_field_image']) . '" alt="banner_field_image" width="100" /></td>';
                }
                else{
                    echo '<td>Custom Code</td>';
                }
          echo '<td>&nbsp;</td>';
          echo '<td>[cs_ads id="'.$cs_rand_id.'"]</td>';
          echo '<td class="centr">
                    <a class="remove-btn" onclick="javascript:return confirm(\'Are you sure! You want to delete this\')" href="javascript:social_icon_del(\''.cs_slugify_text($_POST['banner_field']).'\')"><i class="icon-cross3"></i></a>
                    <a href="javascript:cs_toggle(\''.cs_slugify_text($_POST['banner_field']).'\')"><i class="icon-pencil6"></i></a>
                </td>
              </tr>';
        echo '<tr id="'.cs_slugify_text($_POST['banner_field']).'" style="display:none;">
                <td colspan="3">
                  <ul class="form-elements">
                    <li><a onclick="cs_toggle(\''.cs_slugify_text($_POST['banner_field']).'\')"><img src="'.esc_url(get_template_directory_uri()).'/include/assets/images/close-red.png" /></a></li>
                    <li class="to-label">
                      <label>"'.esc_html__('Title','car-rental').'"</label>
                    </li>
                    <li class="to-field">
                      <input class="small" type="text" name="banner_field_title[]" value="'.$_POST['banner_field'].'"  />
                      <p>"'.esc_html__('Please enter Banner Title','car-rental').'"</p>
                    </li>
                    <li class="to-label">
                      <label>"'.esc_html__('Banner Style','car-rental').'"</label>
                    </li>
                    <li class="to-field select-style">
                      <select name="banner_field_style[]">
                        <option '. $cs_top_banner_selected .' value="top_banner">"'.esc_html__('Top Banner','car-rental').'"</option>
                        <option '. $cs_bottom_banner_selected .' value="bottom_banner">"'.esc_html__('Bottom Banner','car-rental').'"</option>
                        <option '. $cs_sidebar_banner_selected .' value="sidebar_banner">"'.esc_html__('Sidebar Banner','car-rental').'"</option>
                        <option '. $cs_vertical_banner_selected .' value="vertical_banner">"'.esc_html__('Vertical Banner','car-rental').'"</option>
                      </select>
                      <p>"'.esc_html__('Please enter Banner Banner Style','car-rental').'"</p>
                    </li>
                    <li class="full">&nbsp;</li>
                    <li class="to-label">
                      <label>"'.esc_html__('Banner Type','car-rental').'"</label>
                    </li>
                    <li class="to-field select-style">
                      <select name="banner_field_type[]" onchange="cs_banner_type_toggle(this.value, \''.$cs_rand_id.'\')">
                        <option '. $cs_image_banner_selected .' value="image">Image</option>
                        <option '. $cs_code_banner_selected .' value="code">Code</option>
                      </select>
                      <p>"'.esc_html__('Please enter Banner Banner Type','car-rental').'"</p>
                    </li>
                    <li class="full">&nbsp;</li>
                    <li class="to-label" id="cs_banner_image_field_'.$cs_rand_id.'" style="display:'.$cs_baner_image_display.';">
                      <label>Image</label>
                    </li>
                    <li class="to-field" id="cs_banner_image_value_'.$cs_rand_id.'" style="display:'.$cs_baner_image_display.';">
                      <ul class="form-elements" id="'.$cs_rand_id.'_upload">
                        <li class="to-field">
                          <input id="banner_field_image'.$cs_rand_id.'" name="banner_field_image[]" type="hidden" class="" value="'.$_POST['banner_field_image'].'"/>
                          <label class="browse-icon">
                          <input name="banner_field_image'.$cs_rand_id.'" type="button" class="uploadMedia left" value="'.esc_html__('Browse','car-rental').'"/></label>
                        </li>
                      </ul>
                      <div class="page-wrap" style="overflow:hidden;display:block;" id="banner_field_image'.$cs_rand_id.'_box" >
                        <div class="gal-active" style="padding-left:0 !important;">
                          <div class="dragareamain" style="padding-bottom:0px;">
                            <ul id="gal-sortable">
                              <li class="ui-state-default">
                                <div class="thumb-secs"> <img src="'.esc_url($_POST['banner_field_image']).'" id="banner_field_image'.$cs_rand_id.'_img" width="100" />
                                  <div class="gal-edit-opts"> <a href=javascript:del_media("banner_field_image'.$cs_rand_id.'") class="delete"></a> </div>
                                </div>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <p>"'.esc_html__('Please enter Banner Image','car-rental').'"</p>
                    </li>
                    <li class="full">&nbsp;</li>
                    <li class="to-label">
                      <label>"'.esc_html__('Url','car-rental').'"</label>
                    </li>
                    <li class="to-field">
                      <input class="small" type="text" name="banner_field_url[]" value="'.$_POST['banner_field_url'].'" />
                      <p>"'.esc_html__('Please enter Banner Url','car-rental').'"</p>
                    </li>
                    <li class="full">&nbsp;</li>
                    <li class="to-label">
                      <label>"'.esc_html__('Target','car-rental').'"</label>
                    </li>
                    <li class="to-field select-style">
                      <select name="banner_field_url_target[]">
                        <option '. $cs_self_target_selected .' value="_self">"'.esc_html__('Self','car-rental').'"</option>
                        <option '. $cs_blank_target_selected .' value="_blank">"'.esc_html__('Blank','car-rental').'"</option>
                      </select>
                      <p>"'.esc_html__('Please select Banner Link Target','car-rental').'"</p>
                    </li>
                    <li class="full">&nbsp;</li>
                    <li class="to-label" id="cs_banner_code_field_'.$cs_rand_id.'" style="display:'.$cs_baner_code_display.';">
                      <label>"'.esc_html__('Adsense Code','car-rental').'"</label>
                    </li>
                    <li class="to-field" id="cs_banner_code_value_'.$cs_rand_id.'" style="display:'.$cs_baner_code_display.';">
                      <textarea name="banner_adsense_code[]">'.esc_html($_POST['banner_field_code']).'</textarea>
                      <p>"'.esc_html__('Please enter Banner Adsense','car-rental').'" Code.</p>
                    </li>
                    <li class="full">&nbsp;</li>
                    <input type="hidden" name="banner_field_code_no[]" value="'.$cs_rand_id.'" />
                  </ul>
                </td>
              </tr>';
        die;
    }
    add_action('wp_ajax_cs_add_banner_fields', 'cs_add_banner_fields');
}