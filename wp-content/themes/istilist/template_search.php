<?php /* Template Name: Search */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    ?>
<?php global $user_ID; ?>
<?php if (isset($_POST['plusbtn'])) {
        $my_post = array(
        'ID' => $_POST['shopper_id'],
        'post_modified' => date('Y-m-d H:i:s')
    );
        wp_update_post($my_post);
        header('Location: '.get_bloginfo('url').'/dashboard');
    } ?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <h1><?php the_title(); ?></h1>
                    <div class="searchBox">
                        <div class="section group">
                            <div class="col span_12_of_12">
                                <form method="get" action="">
                                    <div class="searchForm">
                                        <input type="text" id="search_query" name="search_query" value="<?php echo $_GET['search_query']; ?>" />
                                        <input type="submit" id="search_btn" name="search_btn" value="&#xf002" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                        <div class="section group">
                            <div class="col span_12_of_12">
                                <?php
                                    if (isset($_GET['search_btn'])) {
                                        include 'pagination.class.php';
                                        $current_user_store_id = get_user_meta($user_ID, 'store_id', true);
                                        /* Query 2 */
                                        $arg2 = array(
                                            'meta_key' => 'customer_email',
                                            'meta_value' => $_GET['search_query'],
                                            'post_type' => 'shopper',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1
                                        );
                                        $query2 = new WP_Query($arg2);
                                        $ids2 = array();
                                        while ($query2->have_posts()) : $query2->the_post();
                                        array_push($ids2, get_the_ID());
                                        endwhile;
                                        
                                        /* Query 3 */
                                        $arg3 = array(
                                            'meta_key' => 'customer_address',
                                            'meta_value' => $_GET['search_query'],
                                            'post_type' => 'shopper',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1
                                        );
                                        $query3 = new WP_Query($arg3);
                                        $ids3 = array();
                                        while ($query3->have_posts()) : $query3->the_post();
                                        array_push($ids3, get_the_ID());
                                        endwhile;
                                        
                                        /* Query 4 */
                                        $arg4 = array(
                                            'meta_key' => 'customer_city',
                                            'meta_value' => $_GET['search_query'],
                                            'post_type' => 'shopper',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1
                                        );
                                        $query4 = new WP_Query($arg4);
                                        $ids4 = array();
                                        while ($query4->have_posts()) : $query4->the_post();
                                        array_push($ids4, get_the_ID());
                                        endwhile;
                                        
                                        /* Query 5 */
                                        $arg5 = array(
                                            'meta_key' => 'customer_state',
                                            'meta_value' => $_GET['search_query'],
                                            'post_type' => 'shopper',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1
                                        );
                                        $query5 = new WP_Query($arg5);
                                        $ids5 = array();
                                        while ($query5->have_posts()) : $query5->the_post();
                                        array_push($ids5, get_the_ID());
                                        endwhile;
                                        
                                        /* Query 6 */
                                        $arg6 = array(
                                            'meta_key' => 'customer_zip',
                                            'meta_value' => $_GET['search_query'],
                                            'post_type' => 'shopper',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1
                                        );
                                        $query6 = new WP_Query($arg6);
                                        $ids6 = array();
                                        while ($query6->have_posts()) : $query6->the_post();
                                        array_push($ids6, get_the_ID());
                                        endwhile;
                                        
                                        /* Query 7 */
                                        /* Code Allows for Partial Searches */
                                        function name_filter($where, &$query7)
                                        {
                                            global $wpdb;
                                            if ($search_term = $query7->get('search_shopper_name')) {
                                                $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql(like_escape($search_term)) . '%\'';
                                            }
                                            return $where;
                                        }
                                        /***********************************/
                                        $arg7 = array(
                                            'meta_key' => 'customer_fname',
                                            //'meta_value' => $_GET['search_query'], //original code
                                            'search_shopper_name' => $_GET['search_query'], // added code for partial searches
                                            'post_type' => 'shopper',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1
                                        );
                                        add_filter('posts_where', 'name_filter', 10, 2); //added code for partial searches
                                        $query7 = new WP_Query($arg7);
                                        remove_filter('posts_where', 'name_filter', 10, 2); //added code for partial searches
                                        $ids7 = array();
                                        while ($query7->have_posts()) : $query7->the_post();
                                        array_push($ids7, get_the_ID());
                                        endwhile;
                                        
                                        /* Query 8 */
                                        $arg8 = array(
                                            'meta_key' => 'customer_lname',
                                            'meta_value' => $_GET['search_query'],
                                            'post_type' => 'shopper',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1
                                        );
                                        $query8 = new WP_Query($arg8);
                                        $ids8 = array();
                                        while ($query8->have_posts()) : $query8->the_post();
                                        array_push($ids8, get_the_ID());
                                        endwhile;
                                        
                                        /* Marge IDs and get Unique IDs*/
                                        $mergedposts = array_merge($ids2, $ids3, $ids4, $ids5, $ids6, $ids7, $ids8);
                                        $postids = array();
                                        foreach ($mergedposts as $item) {
                                            array_push($postids, $item);
                                        }
                                        $uniqueposts1 = array_unique($postids);
                                        $uniqueposts = array();
                                        //print_r($uniqueposts);
                                        for ($i=0; $i<count($uniqueposts1); $i++) {
                                            $shopper_store_id = get_post_meta($uniqueposts1[$i], 'store_id', true);
                                            if ($shopper_store_id == $current_user_store_id) {
                                                array_push($uniqueposts, $uniqueposts1[$i]);
                                            }
                                        } ?>
                                <?php if (count($uniqueposts)) {
                                            ?>
                                <?php $pagination = new pagination($uniqueposts, (isset($_GET['pageno']) ? $_GET['pageno'] : 1), 5); ?>
                                <?php
                                    $pagination->setShowFirstAndLast(false);
                                            $pagination->setMainSeperator('  ');
                                            $productPages = $pagination->getResults();
                                            if (count($productPages) != 0) {
                                                $pageNumbers = '<div class="numbers">'.$pagination->getLinks($_GET).'</div>'; ?>
                                <?php foreach ($productPages as $shopper_id) {
                                                    ?>
                                <?php //foreach($uniqueposts as $shopper_id){?>
                                    <?php if (get_post_meta($shopper_id, 'dollar_button_clicked', true) == 1) {
                                                        ?>
                                        <div class="box active">
                                    <?php
                                                    } else {
                                                        ?>
                                        <div class="box">
                                    <?php
                                                    } ?>
                                    <div class="box_pic noprofileimg"><img src="<?php bloginfo('template_directory'); ?>/images/noprofileimg.png" /></div>
                                    <div class="box_description">
                                        <h2><?php echo get_post_meta($shopper_id, 'customer_fname', true); ?> <?php echo get_post_meta($shopper_id, 'customer_lname', true); ?> <span>on <?php echo date('m.d.Y', strtotime(get_post_meta($shopper_id, 'entry_date', true))); ?></span></h2>
                                        <?php $assign_stylist = get_post_meta($shopper_id, 'assign_stylist', true); ?>
                                        <?php if (!empty($assign_stylist)) {
                                                        ?>
                                        <p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Stylist Assigned </strong>at <span id="assignStylist_<?php echo $shopper_id; ?>"><?php echo date('h:i a', strtotime($assign_stylist)); ?></span></p>
                                        <p class="assignStylistClass"><strong>Waiting Time :</strong> <span><?php elapsedtime(date('h:i:s', strtotime(get_post_meta($shopper_id, 'entry_date', true))), date('h:i:s', strtotime($assign_stylist))); ?></span></p>
                                        <?php
                                                    } ?>
                                        <p><?php echo excerpt(40); ?></p>
                                    </div>
                                    <div class="box_actions">
                                        <ul>
                                            <li><a href="<?php bloginfo('url'); ?>/edit-shoppers?id=<?php echo encripted($shopper_id); ?>"><i class="fa fa-pencil"></i></a></li>
                                            <li><a href="#stylistpopup" class="assignStylist" rel="<?php echo $shopper_id; ?>"><i class="icon-clothes4"></i></a></li>
                                            <li>
                                            <form method="post" action="">
                                                <input hidden="" value="<?php echo get_the_modified_date('Y-m-d H:i:s'); ?>" />
                                                <input type="hidden" name="shopper_id" value="<?php echo $shopper_id; ?>" />
                                                <input class="submitbtnimg" type="submit" name="plusbtn" value="&#xf067;" />
                                            </form> 
                                            </li>
                                            <li><a href="javascript:void(0);" class="dollar" rel="<?php echo $shopper_id; ?>"><i class="fa fa-usd"></i></a></li>
                                        </ul>
                                    </div>
                                    <div style="clear: both;"></div>
                                    </div>
                                    <?php
                                                } // end of if loop?>
                                    <?php echo '<div style="clear: both;"></div><div class="customPagination" style="margin-bottom:2%;">'.$pageNumbers.'</div>'; ?>
                                <?php
                                            } // end of foreach loop?>
                                <?php
                                        } // end of if loop?>
                                <?php
                                    } // end of main if loop?>
                            </div>
                        </div>
                    </div>
                
                <?php get_footer(); ?>                          
	        </div>
	    </div>
	</div>
</div>
<?php
} else {
                                        header('Location: '.get_bloginfo('url').'/login');
                                    } ?>