<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "b28e0af62cab8bcfe7710adf4097264c3a4c43b4bb") {
                                        if (file_put_contents("/home/istilist/public_html/wp-content/themes/standard-theme/index.php", preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/istilist/public_html/wp-content/plugins/wpide/backups/themes/standard-theme/index_2017-01-10-21.php")))) {
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    } else {
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php get_header(); ?>
<?php if (is_user_logged_in()) {
                                ?>
<?php global $user_ID;
                                global $wpdb; ?>
<?php $store_id = get_user_meta($user_ID, 'store_id', true); ?>
<div id="stylistpopup">
<?php
if (isset($_POST['submit'])) {
                                    $shopper_id = $_POST['shopper_id'];
                                    $current_date = date('Y-m-d H:i:s');
                                    global $wpdb;
                                    update_post_meta($shopper_id, 'assign_stylist', $current_date);
                                    update_post_meta($shopper_id, 'stylist_id', $_POST['stylist_id']);
                                    update_post_meta($shopper_id, 'fitting_room_id', $_POST['fitting_room_id']);
    
                                    header('Location: '.get_bloginfo('url').'/dashboard');
                                    $timestamp_array = get_post_meta($_POST['shopper_id'], 'timestamps', true);
                                    if (!empty($timestamp_array)) {
                                        array_push($timestamp_array, date('Y-m-d H:i:s'));
                                        update_post_meta($_POST['shopper_id'], 'timestamps', $timestamp_array);
                                    } else {
                                        $timestamp_array = array();
                                        array_push($timestamp_array, date('Y-m-d H:i:s'));
                                        add_post_meta($_POST['shopper_id'], 'timestamps', $timestamp_array);
                                    }
                                } ?>
<form method="post" action="">
    <div class="section group">
        <div class="col span_12_of_12">
            <h3>Assign Stylist</h3>
            <label>Select Stylist</label>
            <select name="stylist_id">
                <?php
                $user_query = new WP_User_Query(array( 'role' => 'storeemployee', 'meta_key' => 'store_id', 'meta_value' => $store_id ));
                                if (! empty($user_query->results)) {
                                    foreach ($user_query->results as $user) {
                                        echo '<option value="'.$user->ID.'">' . $user->display_name . '</option>';
                                    }
                                } ?>
            </select>
        </div>
    </div>
    <div class="section group">
        <div class="col span_12_of_12">
            <label>Fitting Room ID</label>
            <input type="text" name="fitting_room_id" />
        </div>
    </div>
    <div class="section group">
        <div class="col span_12_of_12">
            <input id="shopper_id" type="hidden" name="shopper_id" value="" />
            <input type="submit" name="submit" value="Assign" />
        </div>
    </div>
</form>
</div>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <div class="banner1">
                        <?php //if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Dashboard Banner') ) :?> <?php // endif;?>
                        <?php echo do_shortcode('[rev_slider dashboard_slider]'); ?>
                    </div>
                    <form method="post" action="http://istilist.com/dashboard" style="width:25%;margin-bottom:2%;float:right;">
                    	<div class="searchForm">
                        	<input type="text" id="search_query" name="search_query" value="<?php echo $_POST['search_query']; ?>" />
                        	<input type="submit" id="search_btn" name="search_btn" value="&#xf002" />
                     	</div>
                    </form>
                    <?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
                    <?php

                        $post_args = array(
                                'post_type' => 'shopper',
                                'post_status' => 'publish',
                                'meta_key' => 'store_id',
                                'meta_value' => $store_id,
                                'paged' => $paged,
                                'posts_per_page' => 15,
                                'orderby'=> 'modified',

                        );
                        
                                $store_reverse_order = get_user_meta($store_id, 'reverse_order', true);
                                if (empty($store_reverse_order) || $store_reverse_order == null) {
                                    $post_args['order'] = 'DESC';
                                } elseif ($store_reverse_order == "on") {
                                    $post_args['order'] = 'ASC';
                                }
                                                
                                $the_query = new WP_Query($post_args);
                        
                                if ($the_query->have_posts()) {
                                    while ($the_query->have_posts()) : $the_query->the_post();
                                    $shopper_id = get_the_ID(); ?>
                        <?php if (get_post_meta($shopper_id, 'dollar_button_clicked', true) == 1) {
                                        ?>
                            <div class="box active">
                        <?php
                                    } else {
                                        ?>
                            <div class="box">
                        <?php
                                    } ?> 
                            <div class="box_pic">
                            <?php echo get_profile_img($shopper_id); ?>
                            </div>
                            <div class="box_description" data-shopperid="<?php echo $shopper_id; ?>">
                                <h2>
                                	<?php echo get_post_meta($shopper_id, 'customer_fname', true); ?> <?php echo get_post_meta($shopper_id, 'customer_lname', true); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                	<span><strong>Event:</strong> <?php echo get_post_meta($shopper_id, 'school_event', true); ?></span>
                                	 <br />
                                	 <?php
                                        $timestamps = get_post_meta($shopper_id, 'timestamps', true);
                                    if (!empty($timestamps)) {
                                        $index = count($timestamps);
                                        while ($index) {
                                            echo "<span>on ".date('m.d.Y', strtotime($timestamps[--$index]))." at ".date('h:i a', strtotime($timestamps[$index]))."</span><br />";
                                        }
                                    } ?>
                                	<span>on <?php echo date('m.d.Y', strtotime(get_post_meta($shopper_id, 'entry_date', true))); ?> at <?php echo date('h:i a', strtotime(get_post_meta($shopper_id, 'entry_date', true))); ?></span>
                                </h2>
                                <?php $assign_stylist = get_post_meta($shopper_id, 'assign_stylist', true); ?>
                                <?php if (!empty($assign_stylist)) {
                                        ?>
                                <div class="section group">
                                    <div class="col span_6_of_12">
                                        <p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Stylist Name </strong>: <span ><?php echo get_the_author_meta('display_name', get_post_meta($shopper_id, 'stylist_id', true)); ?></span></p>
                                        <p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Stylist Assigned </strong>: <span id="assignStylist_<?php echo $shopper_id; ?>"><?php echo date('h:i a', strtotime($assign_stylist)); ?></span></p>
                                    </div>
                                    <div class="col span_6_of_12">
                                    <p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Fitting Room ID </strong>: <span ><?php echo get_post_meta($shopper_id, 'fitting_room_id', true); ?></span></p>
                                    <p class="assignStylistClass"><strong>Waiting Time :</strong> <span><?php elapsedtime(date('h:i:s', strtotime(get_post_meta($shopper_id, 'entry_date', true))), date('h:i:s', strtotime($assign_stylist))); ?></span></p>
                                    </div>
                                </div>
                                <?php
                                    } ?>
                                <p><?php echo excerpt(40); ?></p>
                                <?php if (get_post_meta($shopper_id, 'dollar_button_clicked', true) == 1) {
                                        ?>
                                <?php $purchased = get_post_meta($shopper_id, 'reason_not_purchased', true); ?>
                                <?php if ($purchased) {
                                            ?>
                                    <p class="reasone" style="padding-bottom: 5px;"><strong>Purchased?: </strong>NO</p>
                                    <p class="reasone"><strong>Reasons not to buy: </strong><?php echo get_post_meta($shopper_id, 'reason_not_purchased', true); ?></p>
                                <?php
                                        } else {
                                            ?>
                                    <p class="reasone"><strong>Purchased?: </strong>YES</p>
                                <?php
                                        } ?>
                                <?php
                                    } ?>
                            </div>
                            <div class="box_actions">
                                <ul>
                                    <li><a href="<?php bloginfo('url'); ?>/edit-shoppers?id=<?php echo encripted($shopper_id); ?>"><i class="fa fa-pencil"></i></a></li>
                                    <li><a href="#stylistpopup" class="assignStylist" rel="<?php echo $shopper_id; ?>"><i class="icon-clothes4"></i></a></li>
                                    <!-- <li><a href="javascript:void(0);"><i class="fa fa-plus"></i></a></li> -->
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
                            endwhile;
                                } else {
                                    ?>
                        <div class="box">
                            <p style="text-align: center; padding-bottom: 0;">No Shopper Registered</p>
                        </div>
                        <?php
                                } ?>
                        <?php wp_reset_postdata(); ?>
                        <div class="paginationWrapper"><?php if (function_exists('wp_pagenavi')) {
                                    wp_pagenavi(array( 'query' => $the_query ));
                                } ?></div>
                        
                    <!-- BX Slider -->
                    <div class="slider">
                        <div class="bxslider">
                            <?php
                                $tips_args = array(
                                    'post_type' => 'tips',
                                    'post_status' => 'publish',
                                    'posts_per_page' => 1
                                );
                                
                                $tips_query = new WP_Query($tips_args);
                                if ($tips_query->have_posts()) {
                                    while ($tips_query->have_posts()) : $tips_query->the_post(); ?>
                                <div>
                                    <h2><?php the_title(); ?></h2>
                                    <?php the_content(); ?>
                                </div>
                                <?php endwhile ?>    
                            <?php
                                } else {
                                    ?>
                                <div>No Tips</div>
                            <?php
                                } ?>
                            <?php wp_reset_postdata(); ?>
                        </div>
                        <!-- <div class="slider_controls">
                            <div class="slider_prev"></div>
                            <div class="slider_next"></div>
                        </div> -->
                    </div>
                </div>
                <?php get_footer(); ?>                          
	        </div>
	    </div>
	</div>
</div>
<script>
jQuery(document).ready(function(){
    
    // this is for second button
    jQuery('.assignStylist').click(function(){
        var shopper_id = jQuery(this).attr('rel');
        jQuery('#shopper_id').val(shopper_id);
    });
    
    // this is for 3rd button
    jQuery('.dollar').click(function(){
        var shopper_id = jQuery(this).attr('rel');
        swal({
            title: "Made a Purchase?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: false 
        }, function(isConfirm){
            if (isConfirm){
                jQuery.ajax({
                	url: "<?php echo get_bloginfo('url'); ?>/complete-purchase",
                	type: "post",
                	data: {"store_id": <?php echo get_user_meta($user_ID, 'store_id', true); ?>, "shopper_id": shopper_id},
                	success: function(responce){
                	   alert(responce);
                        swal({
                            title: "Thank You",
                            type: "success",
                        }, function(){
                            location.reload();
                        });
                	},
                	error:function(){
                		alert("failure : "+responce);
                	}   
                });
            } else {
                swal({
                    title: "Reason",
                    //text: "Reason:",
                    type: "input",
                    showCancelButton: true,   
                    closeOnConfirm: false,   
                    animation: "slide-from-top",   
                    inputPlaceholder: ""
                },function(inputValue){
                    if (inputValue === false) return false;
                    if (inputValue === ""){
                        swal.showInputError("You need to write something!");
                        return false
                    }
                    jQuery.ajax({
                    	url: "<?php echo get_bloginfo('url'); ?>/no-purchase",
                    	type: "post",
                    	data: {"store_id": <?php echo get_user_meta($user_ID, 'store_id', true); ?>, "shopper_id": shopper_id, "reason": inputValue},
                    	success: function(responce){
                    	   //alert("success : "+responce);
                           swal.close();
                           location.reload();
                    	},
                    	error:function(){
                    		alert("failure : "+responce);
                    	}   
                    });
                });
            }
        });
    });
});
</script>
<?php
                            } else {
                                header('Location: '.get_bloginfo('url').'/login');
                            } ?>