<?php
/**
 * The template for displaying Footer
 */
global $cs_theme_options;

$cs_footer_logo = isset($cs_theme_options['cs_footer_logo']) ? $cs_theme_options['cs_footer_logo'] : '';
$cs_footer_switch = isset($cs_theme_options['cs_footer_switch']) ? $cs_theme_options['cs_footer_switch'] : '';
$cs_footer_widget = isset($cs_theme_options['cs_footer_widget']) ? $cs_theme_options['cs_footer_widget'] : '';
$cs_footer_widget = isset($cs_theme_options['cs_footer_widget']) ? $cs_theme_options['cs_footer_widget'] : '';
$cs_footer_google_play = isset($cs_theme_options['cs_footer_google_play']) ? $cs_theme_options['cs_footer_google_play'] : '';
$cs_footer_app = isset($cs_theme_options['cs_footer_app']) ? $cs_theme_options['cs_footer_app'] : '';
$cs_footer_logo_link = isset($cs_theme_options['cs_footer_logo_link']) ? $cs_theme_options['cs_footer_logo_link'] : '';
$cs_footer_app_link = isset($cs_theme_options['cs_footer_app_link']) ? $cs_theme_options['cs_footer_app_link'] : '';
$cs_footer_google_app = isset($cs_theme_options['cs_footer_google_app']) ? $cs_theme_options['cs_footer_google_app'] : '';
$cs_copy_right = isset($cs_theme_options['cs_copy_right']) ? $cs_theme_options['cs_copy_right'] : '';
?>
</div>
</main>
<?php if ((isset($cs_footer_switch) and $cs_footer_switch == 'on') || (isset($cs_footer_widget) and $cs_footer_widget == 'on')) { ?> 
    <footer id="footer">
        <div class="container">
            <div class="footer-inner">
                <div class="row">

                    <?php
                    if (isset($cs_footer_widget) and $cs_footer_widget == 'on') {
                        $cs_footer_sidebar = (isset($cs_theme_options['cs_footer_widget_sidebar']) and $cs_theme_options['cs_footer_widget_sidebar'] <> "select sidebar") ? $cs_theme_options['cs_footer_widget_sidebar'] : 'footer-widget-1';
                        if (!function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_footer_sidebar)) : endif;
                    }
                    ?>
                </div>
            </div>
            <div class="copyrigts">
                <div class="footer-logo col-md-8">
                    <?php if(isset($cs_footer_logo) && $cs_footer_logo != '') { ?><a href="<?php echo esc_url($cs_footer_logo_link); ?>"><img src="<?php echo esc_url($cs_footer_logo); ?>" alt="cs_footer_logo"></a> <?php } ?>
                    <span class="copyright-text">
                        <?php
                        if (isset($cs_copy_right) and $cs_copy_right <> '') {
                            $cs_allowed_tags = array(
                                'a' => array('href' => array(), 'class' => array()),
                                'b' => array(),
                                'i' => array('class' => array()),
                            );
                            echo wp_kses(wp_specialchars_decode($cs_copy_right), $cs_allowed_tags);
                        } else {
                            echo '<p>&copy;' . gmdate("Y") . ' ' . get_option("blogname") . ' Wordpress ' . esc_html__('All rights reserved', 'car-rental') . '.</p>';
                        }
                        ?> </span> </div>
                <div class="apps col-md-4 pull-right"> 
                       <?php if(isset($cs_footer_app) && $cs_footer_app != '') { ?><a href="<?php echo esc_url($cs_footer_app_link); ?>"><img src="<?php echo esc_url($cs_footer_app); ?>" alt="cs_footer_app"></a>  <?php } ?>
                      <?php if(isset($cs_footer_google_play) && $cs_footer_google_play != '') { ?> <a href="<?php echo esc_url($cs_footer_google_app); ?>"><img src="<?php echo esc_url($cs_footer_google_play); ?>" alt="cs_footer_google_play"></a><?php } ?> 
                </div>
            </div>

        </div>
    </footer>
<?php } ?>
<div class="clear"></div>
</div>
<!-- Wrapper End --> 
<?php wp_footer(); ?>
</body>
</html>