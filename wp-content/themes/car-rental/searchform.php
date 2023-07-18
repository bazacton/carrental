<?php 
/**
 * The template for displaying Search Form
 */
global $cs_theme_options
?>
<div class="cs-search-area">
    <form method="get" action="<?php echo esc_url(home_url('/')); ?>" role="search">
        <input type="text" class="form-control" placeholder="<?php esc_html_e('Enter your search','car-rental');?>" value="" name="s" id="s">
        <label class="search-submit">
            <input type="submit"  value="<?php esc_html_e('Search','car-rental');?>" id="searchsubmit">
        </label>
    </form>
</div>