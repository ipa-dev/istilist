<?php /* Template Name: Dashboard */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    global $user_ID;
    global $wpdb; 
    $store_id = get_user_meta($user_ID, 'store_id', true);
    require_once ABSPATH . 'wp-content/themes/istilist/php_modules/template-dashboard/shopper-single.php';
?>
<div id="dashboard">
    <div class="maincontent noPadding">
        <div class="section group">
			<?php get_sidebar('menu'); ?>
            <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <div class="banner">
						<?php echo do_shortcode('[rev_slider home]'); ?>
                    </div>
                    <div class="searchForm">
                        <input type="text" id="search_query" name="search_query"/>
                        <span class="custom_button" id="search_btn">&#xf002</span>
                    </div>
                    <?php require_once ABSPATH . 'wp-content/themes/istilist/php_modules/template-dashboard/bulk-action-form.php'; ?>
                    <div class="box">
                        <!-- GET SHOPPERS -->
                        
                        <p style="text-align: center; padding-bottom: 0;">No Shopper Registered</p>
                    </div>
                    <div class="paginationWrapper">
                    </div>
                    <div class="slider">
                        <h2>International Prom Association</h2>
                        <p>
                            <a href="http://www.internationalprom.com">internationalprom.com</a><br>
                            Phone: (877) 259-5775<br>
                            Address: 281 W Old Andrew Johnson Hwy, Jefferson City, TN 37760
                        </p>
                    </div>
                </div>
                <?php get_footer(); ?>
            </div>
        </div>
    </div>
</div>
<?php } else { header('Location: ' . get_bloginfo('url') . '/login'); } ?>