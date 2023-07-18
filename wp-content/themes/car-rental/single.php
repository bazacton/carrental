<?php
/**
 * The template for displaying single post
 */
global $post, $cs_theme_options;
$cs_uniq = rand(11111111, 9999999);

$cs_postObject = get_post_meta($post->ID, 'cs_full_data', true);
$cs_gallery_ids = get_post_meta($post->ID, 'cs_post_list_gallery', true);
$cs_gallery_slider_ids = get_post_meta($post->ID, 'cs_post_detail_gallery', true);
$cs_gallery = explode(',', $cs_gallery_ids);
$cs_gallery_slider = explode(',', $cs_gallery_slider_ids);

get_header();

$cs_layout = '';
$leftSidebarFlag = false;
$rightSidebarFlag = false;

$cs_layout = get_post_meta($post->ID, 'cs_page_layout', true);
$cs_sidebar_left = get_post_meta($post->ID, 'cs_page_sidebar_left', true);
$cs_sidebar_right = get_post_meta($post->ID, 'cs_page_sidebar_right', true);
$post_tags_show = get_post_meta($post->ID, 'cs_post_tags_show', true);
$cs_post_social_sharing = get_post_meta($post->ID, 'cs_post_social_sharing', true);
$cs_related_post = get_post_meta($post->ID, 'cs_related_post', true);
$post_pagination_show = get_post_meta($post->ID, 'cs_post_pagination_show', true);
$inside_post_view = get_post_meta($post->ID, 'cs_detail_view', true);
$post_audio = get_post_meta($post->ID, 'cs_post_detail_audio', true);
$post_video = get_post_meta($post->ID, 'cs_post_detail_video', true);

$post_audio = get_post_meta($post->ID, 'cs_post_detail_audio', true);
$post_video = get_post_meta($post->ID, 'cs_post_detail_video', true);

$cs_frame = '<' . 'iframe ';

if ($cs_layout == "left") {
    $cs_layout = "page-content";
    $leftSidebarFlag = true;
    $custom_height = 300;
} else if ($cs_layout == "right") {
    $cs_layout = "page-content";
    $rightSidebarFlag = true;
    $custom_height = 300;
} else {
    $cs_layout = "page-content-fullwidth";
    $custom_height = 408;
}

if (!isset($cs_layout)) {
    $cs_layout = isset($cs_theme_options['cs_single_post_layout']) ? $cs_theme_options['cs_single_post_layout'] : '';
    if (isset($cs_layout) && $cs_layout == "sidebar_left") {
        $cs_layout = "page-content";
        $cs_sidebar_left = $cs_theme_options['cs_single_layout_sidebar'];
        $leftSidebarFlag = true;
        $custom_height = 300;
    } else if (isset($cs_layout) && $cs_layout == "sidebar_right") {
        $cs_layout = "page-content";
        $cs_sidebar_right = $cs_theme_options['cs_single_layout_sidebar'];
        $rightSidebarFlag = true;
        $custom_height = 300;
    }
}

$width = 825;
$height = 345;
?>

<main id="main-content">
    <div class="container">
        <div class="row">
            <?php if (isset($cs_post_social_sharing) and $cs_post_social_sharing == "on" || isset($post_pagination_show) and $post_pagination_show == "on" || isset($cs_related_post) and $cs_related_post == "on") { ?>
                <aside class="page-sidebar">
                    <div class=" widget cs-postarea element-size-100">
                        <?php
                        if (isset($post_pagination_show) and $post_pagination_show == 'on') {
                            cs_next_prev_custom_links();
                        }
                        ?>
                        <div class="detail-post">
                            <?php
                            if ($cs_post_social_sharing == "on") {

                                $post_social_sharing_text = esc_html__('Share Post', 'car-rental');
                                cs_social_share_blog(false, true, $post_social_sharing_text);
                            }
                            ?>
                        </div>
                    </div>
                    <?php if (isset($cs_related_post) and $cs_related_post == 'on') { ?>
                        <div class=" widget element-size-100 related-post">
                            <div class="widget-section-title">
                                <h6>
                                    <?php esc_html_e('Related Posts', 'car-rental'); ?>
                                </h6>
                            </div>
                            <ul>
                                <?php
                                $args = array(
                                    'posts_per_page' => "3",
                                    'post_type' => 'post',
                                    'post_status' => 'publish'
                                );
                                $query = new WP_Query($args);
                                $post_count = $query->post_count;
                                if ($query->have_posts()) {
                                    $postCounter = 0;
                                    while ($query->have_posts()) : $query->the_post();
                                        ?>
                                        <li>
                                            <div class="text">
                                                <h6><a href="<?php esc_url(the_permalink()); ?>">
                <?php the_title(); ?>
                                                    </a></h6>
                                                <time datetime="<?php echo date_i18n('Y-m-d', strtotime(get_the_date())); ?>"><small><?php echo date_i18n('F j, Y', strtotime(get_the_date())); ?></small></time>
                                            </div>
                                        </li>
                <?php
            endwhile;
        } else {

            $cs_notification->error('No blog post found.');
        }
        ?>
                            </ul>
                        </div>
                    <?php } ?>
                </aside>

            <?php } ?>
            <div class="page-content">
                <section class="page-section">
                    <div class="container">
                        <div class="row">
                            <?php
                            if (have_posts()):
                                while (have_posts()) : the_post();
                                    $image_url = cs_get_post_img_src($post->ID, $width, $height);
                                    ?>
                                    <div class="blog-detail">
                                        <div class="col-md-12">
                                            <article>
                                                <div class="cs-title"> <span class="cs-categroies">
                                                        <?php
                                                        $categories_list = get_the_term_list(get_the_id(), 'category', '', ', ', '');
                                                        if ($categories_list)
                                                            printf('%1$s', $categories_list);
                                                        ?>
                                                    </span>
                                                    <ul class="post-options">
                                                        <li><i class=" icon-clock-o"></i>
                                                            <time datetime="<?php echo date_i18n('Y-m-d', strtotime(get_the_date())); ?>"><small><?php echo date_i18n('F j, Y', strtotime(get_the_date())); ?></small></time>
                                                        </li>
                                                    </ul>
                                                    <h1>
        <?php the_title(); ?>
                                                    </h1>
                                                </div>
                                                <div class="cs-media">
                                                    <?php
                                                    if ($inside_post_view == 'single') {
                                                        if (isset($image_url) && $image_url != '') {
                                                            ?>
                                                            <figure><a href="<?php esc_url(the_permalink()); ?>"><img src="<?php echo esc_url($image_url); ?>" alt="the_permalink"></a></figure>
                                                            <?php
                                                        }
                                                    } else if ($inside_post_view == 'slider' && is_array($cs_gallery_slider) and count($cs_gallery_slider) > 0) {

                                                        cs_post_flex_slider($width, $height, get_the_id(), 'post');
                                                    } elseif ($inside_post_view == 'audio') {

                                                        $viewType = '<i class="icon-microphone"></i>';
                                                        echo '<div class="cs-media"><figure class="detailpost">';
                                                        echo do_shortcode('[audio mp3="' . $post_audio . '"][/audio]');
                                                        echo '</figure></div>';
                                                    } elseif ($inside_post_view == 'video') {

                                                        $viewType = '<i class="icon-film"></i>';
                                                        $url = parse_url($post_video);
                                                        if ($url['host'] == cs_check_host_theme('SERVER_NAME')) {
                                                            echo '<div class="cs-media"><figure class="detailpost cs-detail-post">';
                                                            echo do_shortcode('[video width="' . $width . '" height="' . $height . '" src="' . $post_video . '"][/video]');
                                                            echo '</figure></div>';
                                                        } else {

                                                            if ($url['host'] == 'vimeo.com') {
                                                                echo '<div class="cs-media"><figure class="detailpost">';
                                                                $content_exp = explode("/", $post_video);
                                                                $content_vimo = array_pop($content_exp);
                                                                echo '<figure>' . $cs_frame . ' width="' . $width . '" height="' . $height . '" src="https://player.vimeo.com/video/' . $content_vimo . '" 
											frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
                                                                echo '</figure></div>';
                                                            } elseif ($url['host'] == 'soundcloud.com') {
                                                                $video = wp_oembed_get($post_video, array('height' => $custom_height));
                                                                $search = array('webkitallowfullscreen', 'mozallowfullscreen', 'frameborder="0"');
                                                                echo '<div class="cs-media"><figure>';
                                                                echo str_replace($search, '', $video);
                                                                echo '</figure>';
                                                                echo '</div>';
                                                            } else {
                                                                echo '<div class="cs-media"><figure class="detailpost cs-detail-post">';
                                                                $content = str_replace(array('watch?v=', 'http://www.dailymotion.com/'), array('embed/', '//www.dailymotion.com/embed/'), $post_video);
                                                                echo cs_allow_special_char($cs_frame) . ' width="' . $width . '" height="' . $height . '" src="' . $content . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
                                                                echo '</figure></div>';
                                                            }
                                                        }
                                                    } else if ($inside_post_view == "" && $image_url <> '') {
                                                        echo '<div class="cs-main-post">
									<figure >';
                                                        echo '<img src="' . esc_url($image_url) . '" alt="image_url" >';
                                                        echo '</figure>
								</div>';
                                                    }
                                                    ?>
                                                </div>
                                                    <?php $posttags = get_the_tags();
                                                    if (isset($posttags) and $post_tags_show == "on") {
                                                        ?>
                                                    <div class="cs-tags"> <i class=" icon-tag4"></i>
                                                        <ul>
            <?php
            foreach ($posttags as $tag) {
                ?>
                                                                <li><a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"><?php echo esc_html($tag->name); ?></a></li>
                                                                <?php
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                        <?php } ?>
                                                <div class="blog-text">
                                                <?php the_content();
  wp_link_pages(array('before' => '<div class="page-links"><span class="page-links-title">' . esc_html__('Pages:', 'car-rental') . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>'));
												?>
                                                </div>
                                            </article>
                                        </div>
                                        <div class="col-md-12">
        <?php
        comments_template('', true);
        ?>
                                        </div>
                                    </div>
                                            <?php
                                        endwhile;
                                    endif;
                                    ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>
<?php
get_footer();
