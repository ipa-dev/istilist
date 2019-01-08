<?php /* Template Name: Dashboard */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    global $user_ID;
    global $wpdb; 
    $store_id = get_user_meta($user_ID, 'store_id', true);
    require_once ABSPATH . 'wp-content/themes/istilist/php_modules/template-dashboard/stylist-popup.php';
?>
<input id="store_id" type="hidden" value="<?= get_user_meta($user_ID, 'store_id', true); ?>"/>
<div id="dashboard">
    <div class="maincontent noPadding">
        <div class="section group">
			<?php get_sidebar('menu'); ?>
            <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <div class="banner">
						<?php echo do_shortcode('[rev_slider home]'); ?>
                    </div>
                    <form method="get" action="<?= get_bloginfo('url'); ?>/dashboard" style="width:25%;margin-bottom:2%;float:right;">
                        <div class="searchForm">
                            <input type="text" id="search_query" name="search_query" value="<?php echo $_GET['search_query']; ?>"/>
                            <input type="submit" id="search_btn" name="search_btn" value="&#xf002"/>
                        </div>
                    </form>
                    <?php require_once ABSPATH . 'wp-content/themes/istilist/php_modules/template-dashboard/bulk-action-form.php';
                    if (! isset($_GET['search_query'])) {
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                        $post_args = array(
                            'post_type'      => 'shopper',
                            'post_status'    => 'publish',
                            'meta_key'       => 'store_id',
                            'meta_value'     => $store_id,
                            'paged'          => $paged,
                            'posts_per_page' => 30,
                            'orderby'        => 'date',
                        );

                        $store_reverse_order = get_user_meta($store_id, 'reverse_order', true);

                        if (empty($store_reverse_order) || $store_reverse_order == null) {
                            $post_args['order'] = 'DESC';
                        } elseif ($store_reverse_order == "on") {
                            $post_args['order'] = 'ASC';
                        }


                        $the_query = new WP_Query($post_args);

                        if ($the_query->have_posts()) {
                            while ($the_query->have_posts()) : 
                                $the_query->the_post();
                                $shopper_id = get_the_ID();
                                require 'php_modules/template-dashboard/shopper-single.php';
                            endwhile;
                        } else {
                        ?>
                            <div class="box">
                                <p style="text-align: center; padding-bottom: 0;">No Shopper Registered</p>
                            </div>
                    <?php }
                        wp_reset_postdata(); 
                    ?>
        <div class="paginationWrapper">
            <?php 
                if (function_exists('wp_pagenavi')) {
                    wp_pagenavi(array( 'query' => $the_query ));
                } 
            ?>
        </div>
		<?php
            } 
		if (isset($_GET['search_query'])) {
            include 'pagination.class.php';

            add_filter('posts_where', 'name_filter', 10, 2); // for partial searches
            $query = new WP_Query(array(
                'search_shopper_name' => $_GET['search_query'],
                'post_type'           => 'shopper',
                'post_status'         => 'publish',
                'posts_per_page'      => - 1
            ));
            remove_filter('posts_where', 'name_filter', 10, 2);

            $storeposts = array();
            while ($query->have_posts()) : 
                $query->the_post();
                $shopper_store_id = get_post_meta(get_the_ID(), 'store_id', true);
                if ($shopper_store_id == get_user_meta($user_ID, 'store_id', true)) {
                    array_push($storeposts, get_the_ID());
                }
            endwhile;

            if (count($storeposts)) {
                $pagination = new pagination($storeposts, (isset($_GET['pageno']) ? $_GET['pageno'] : 1), 5);
                $pagination->setShowFirstAndLast(false);
                $pagination->setMainSeperator('  ');
                $productPages = $pagination->getResults();
                if (count($productPages) != 0) {
                    $pageNumbers = '<div class="numbers">' . $pagination->getLinks($_GET) . '</div>';
                    foreach ($productPages as $shopper_id) {
                        require 'php_modules/template-dashboard/shopper-single.php';
                    }
                    echo '<div style="clear: both;"></div><div class="customPagination" style="margin-bottom:2%">' . $pageNumbers . '</div>';
                }				
            }
        }?>

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