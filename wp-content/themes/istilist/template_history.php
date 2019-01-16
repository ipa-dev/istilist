<?php /* Template Name: History */ ?>
<?php 
get_header(); 
if (is_user_logged_in()) {
    global $user_ID;
    $store_id = get_user_meta($user_ID, 'store_id', true); 
?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                    <div class="dash_content"> 
                    	<h1><?php the_title(); ?></h1>
	        	        <form id="update_shoppers" action="<?= get_bloginfo('url') ?>/history" method="POST">
                    		<div>
                    			<span>Select Date</span>
                    			<input id="shoppersfromdate" name="shoppersfromdate" type="text" size="30" autocomplete="off">
                    		</div>
                    	</form>
                        <?php require_once ABSPATH . 'wp-content/themes/istilist/php_modules/template-dashboard/bulk-action-form.php'; ?>
                    	<script>
                    		jQuery("#shoppersfromdate").on("change", function () {
                    			jQuery("#update_shoppers").submit();
                   		 });
                    	</script>
                    	<?php
                            if (isset($_POST["shoppersfromdate"])) {
                                $post_args = array(
                                'post_type' => 'shopper',
                                    'post_status' => 'publish',
                                   'meta_key' => 'store_id',
                                   'meta_value' => $store_id,
                                    'meta_query' => array(
                                        array(
                                            array("key" => "entry_date", "value" => $_POST["shoppersfromdate"]." ", "compare" => "LIKE"),
                                        )
                                    ),
                                    'paged' => $paged,
                                    'posts_per_page' => -1,
                                    'orderby'=> 'modified',
                                    'order' => 'DESC',
                                );
                            } else {
                                $post_args = array(
                                    'post_type' => 'shopper',
                                    'post_status' => 'publish',
                                    'meta_key' => 'store_id',
                                    'meta_value' => $store_id,
                                    'paged' => $paged,
                                    'posts_per_page' => 15,
                                    'orderby'=> 'modified',
                                    'order' => 'DESC',
                                     );
                            }
    $the_query = new WP_Query($post_args);
                        
    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) :
            $the_query->the_post();
            $shopper_id = get_the_ID(); 
            require ABSPATH . 'wp-content/themes/istilist/php_modules/template-dashboard/shopper-single.php';
        endwhile;
    } ?>
    </div>         
    <?php get_footer(); ?>
        </div>
    </div>
</div>
</div>
<?php } else { header('Location: '.get_bloginfo('url').'/login'); } ?>