<?php /* Template Name: Dashboard */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    global $user_ID;
    global $wpdb; 
    $store_id = get_user_meta($user_ID, 'store_id', true);
    require_once ABSPATH . 'wp-content/themes/istilist/php_modules/template-dashboard/stylist-popup.php';
    require_once ABSPATH . 'wp-content/themes/istilist/php_modules/template-dashboard/shopper-single.php';
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
                    <form method="get" id="dash_search" action="<?= get_bloginfo('url'); ?>/dashboard">
                        <div class="searchForm">
                            <input type="text" id="search_query" name="search_query" value="<?= isset($_GET['search_query']) ? $_GET['search_query'] : ''; ?>"/>
                            <input type="submit" id="search_btn" name="search_btn" value="&#xf002"/>
                        </div>
                    </form>
                    <?php 
                    //TODO MASON: Re-enable reverse order
                    require_once ABSPATH . 'wp-content/themes/istilist/php_modules/template-dashboard/bulk-action-form.php';
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                    if (! isset($_GET['search_query'])) {

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
                            echo_shoppers( $the_query );
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

            $query = new WP_Query(array(
                'post_type'           => 'shopper',
                'post_status'         => 'publish',
                'posts_per_page'      => - 1,
                'meta_key'            => 'store_id',
                'meta_vaue'           => get_user_meta( $user_ID, 'store_id', true ),
                'paged'          => $paged,
                'posts_per_page' => 15,
                'orderby'        => 'date',
                'meta_query' => array(
                    array(
                        'key' => 'store_id',
                        'value' => $store_id
                    ),
                    array(
                        array(
                            'key' => 'customer_fname',
                            'value' => $_GET['search_query'],
                            'compare' => 'LIKE'
                        ),
                        array(
                            'key' => 'customer_lname',
                            'value' => $_GET['search_query'],
                            'compare' => 'LIKE'
                        ),
                        // TODO MASON: NEED ANOTHER ONE FOR THE WHOLE NAME/REALLY JUST REPLACE THESE WITH ONE FOR THE WHOLE NAME
                        'relation' => 'OR'
                    ),
                    'relation' => 'AND'
                )
            ));

            echo_shoppers( $query );
            wp_reset_postdata(); 
?>
      <div class="paginationWrapper">
            <?php 
                if (function_exists('wp_pagenavi')) {
                    wp_pagenavi(array( 'query' => $query ));
                } 
            ?>
        </div>
<?php
        }
        ?>

  
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