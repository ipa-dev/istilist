<?php /* Template Name: Dashboard */ ?>
<?php get_header(); ?>
<?php if(is_user_logged_in()){ ?>
<?php global $user_ID; global $wpdb; ?>
<?php $store_id = get_user_meta($user_ID, 'store_id', true); ?>
<div id="stylistpopup">
<?php

//When user presses assign stylist button
if(isset($_POST['submit'])){
    $shopper_id = $_POST['shopper_id'];
    $current_date = date('Y-m-d H:i:s');
    global $wpdb;
    update_post_meta($shopper_id, 'assign_stylist', $current_date);
    update_post_meta($shopper_id, 'stylist_id', $_POST['stylist_id']);
    update_post_meta($shopper_id, 'fitting_room_id', $_POST['fitting_room_id']);


    $prev_stylist_array = get_post_meta($shopper_id, 'prev_stylists', TRUE);

    //Store all information on each stylist girl has been assigned
    if (empty($prev_stylist_array)) {
    	$prev_stylist_array = array (
    		array (
   			'assignment_date' => $current_date,
   			'stylist_id' => $_POST['stylist_id'],
   			'fitting_room_id' => $_POST['fitting_room_id']
       		)
    	);
    	add_post_meta($shopper_id, 'prev_stylists', $prev_stylist_array);
    }
    else if (!empty($prev_stylist_array)) {
    	$array_addition = array ('assignment_date'  => $current_date, 'stylist_id' => $_POST['stylist_id'], 'fitting_room_id' => $_POST['fitting_room_id']);
    	array_push($prev_stylist_array, $array_addition);
    	update_post_meta($shopper_id, 'prev_stylists', $prev_stylist_array);
    }

    $hit_plus = get_post_meta($_POST['shopper_id'], 'hit_plus', true);
    if (!empty($hit_plus)) {
    	if ($hit_plus == 'true') {
    	    $my_post = array(
	        'ID' => $_POST['shopper_id'],
	        'post_modified' => date('Y-m-d H:i:s')
	    );
	    wp_update_post($my_post);
	    $timestamp_array = get_post_meta($_POST['shopper_id'], 'timestamps', true);
	    if (!empty($timestamp_array)) {
	    	array_push($timestamp_array, date('Y-m-d H:i:s'));
	    	update_post_meta($_POST['shopper_id'], 'timestamps', $timestamp_array);
	    }
	    else {
	    	$timestamp_array = array();
	    	array_push($timestamp_array, date('Y-m-d H:i:s'));
	    	add_post_meta($_POST['shopper_id'], 'timestamps', $timestamp_array);
	    }
	    update_post_meta($_POST['shopper_id'], 'hit_plus', 'false');
    	}
    	else if ($hit_plus == 'false') {
    		update_post_meta($_POST['shopper_id'], 'hit_plus', 'true');
    	}
    }
    else {
    	//user has not hit plus button after first round
    	$test = 0;
    }

    header('Location: '.get_bloginfo('url').'/dashboard');
}

//When user presses plus button
if(isset($_POST['plusbtn'])){



    $my_post = array(
        'ID' => $_POST['shopper_id'],
        'post_modified' => date('Y-m-d H:i:s')
    );
    wp_update_post($my_post);


    update_post_meta($_POST['shopper_id'], 'dollar_button_clicked', 0);
    update_post_meta($_POST['shopper_id'], 'complete_purchase', 0);
    update_post_meta($_POST['shopper_id'], 'reason_not_purchased', '');
    delete_post_meta($_POST['shopper_id'], 'notified');


    $timestamp_array = get_post_meta($_POST['shopper_id'], 'timestamps', true);
    if (!empty($timestamp_array)) {
    	array_push($timestamp_array, date('Y-m-d H:i:s'));
    	update_post_meta($_POST['shopper_id'], 'timestamps', $timestamp_array);
    }
    else {
    	$timestamp_array = array();
    	array_push($timestamp_array, date('Y-m-d H:i:s'));
    	add_post_meta($_POST['shopper_id'], 'timestamps', $timestamp_array);
    }

    $hit_plus = get_post_meta($_POST['shopper_id'], 'hit_plus', true);
    if (!empty($hit_plus)) {
    	update_post_meta($_POST['shopper_id'], 'hit_plus', 'false');
    }
    else {
    	add_post_meta($_POST['shopper_id'], 'hit_plus', 'false');
    }
    header('Location: '.get_bloginfo('url').'/dashboard');
}
if (isset($_POST['bulk_select'])) {

	if ($_POST['bulk_select'] == 'purchased') {
		foreach ($_POST as $key=>$value) {
			if ($value == "yes") {
					update_post_meta($key, 'complete_purchase', 1);
					update_post_meta($key, 'dollar_button_clicked', 1);

					$shopper_email = get_post_meta($key, 'customer_email', true);

					$table_name1 = $wpdb->prefix.'folloup_messages';
					$sql2 = "SELECT * FROM $table_name1 WHERE message_type = 'thankyou' and store_id = $store_id";
					$result2 = $wpdb->get_row($sql2);

					$shopper_name1 = get_post_meta($key, 'customer_fname', true).' '.get_post_meta($key, 'customer_lname', true);
					$msg_body1 = str_replace("{Shopper's Name}",$shopper_name1,$result2->body);

					$styist_id = get_post_meta($key, 'stylist_id', true);

					$stylist_name = get_the_author_meta('display_name', $styist_id);
					$msg_body2 = str_replace("{Stylist's Name}",$stylist_name,$msg_body1);


					$store_name = get_the_author_meta('display_name', $store_id);
					$from = get_user_meta($store_id, 'email_to_shopper', true);

					$shopper_name  = $shopper_name1;
					$headers = 'From: '.$store_name.'<'.$from.'>'."\r\n";
					$headers .= "Reply-To: ". strip_tags($from) . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
					$subject = $result2->subject;
					$msg = $msg_body2;


					if(!empty($store_name)){
					    if(!empty($from)){
					        wp_mail( $shopper_email, $subject, $msg, $headers);
					    }
					}


			}

		}
	}
	if ($_POST['bulk_select'] == 'not-purchased') {
		foreach ($_POST as $key=>$value) {
			if ($value == "yes") {

					$reason = ".";
					$table_name1 = $wpdb->prefix.'folloup_messages';

					update_post_meta($key, 'reason_not_purchased', $reason);
					update_post_meta($key, 'dollar_button_clicked', 1);

					$shopper_email = get_post_meta($key, 'customer_email', true);

					$sql2 = "SELECT * FROM $table_name1 WHERE message_type = 'promo' and store_id = $store_id";
					$result2 = $wpdb->get_row($sql2);

					$shopper_name1 = get_post_meta($key, 'customer_fname', true).' '.get_post_meta($key, 'customer_lname', true);
					$msg_body1 = str_replace("{Shopper's Name}",$shopper_name1,$result2->body);

					$styist_id = get_post_meta($key, 'stylist_id', true);

					$stylist_name = get_the_author_meta('display_name', $styist_id);
					$msg_body2 = str_replace("{Stylist's Name}",$stylist_name,$msg_body1);

					if($options['smtp-active'] == 1){
					    $from = get_user_meta($user_ID, 'email_to_shopper', true); //get_the_author_meta('user_email', $store_id);
					} else {
					    $from = get_user_meta($user_ID, 'email_to_shopper', true); //get_the_author_meta('user_email', $store_id);
					}

					$store_name = get_the_author_meta('display_name', $store_id);
					$from = get_user_meta($store_id, 'email_to_shopper', true);

					$shopper_name  = $shopper_name1;
					$headers = 'From: '.$store_name.'<'.$from.'>'."\r\n";
					$headers .= "Reply-To: ". strip_tags($from) . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
					$subject = $result2->subject;
					$msg = $msg_body2;

					if(!empty($store_name)){
					    if(!empty($from)){
					        //wp_mail( $shopper_email, $subject, $msg, $headers );
					        mail( $shopper_email, $subject, $msg, $headers);
					    }
					}

			}

		}
	}

}
?>
<form method="post" action="">
    <div class="section group">
        <div class="col span_12_of_12">
            <h3>Assign Stylist</h3>
            <label>Select Stylist</label>
            <select name="stylist_id">
                <?php
                $user_query = new WP_User_Query( array( 'role' => 'storeemployee', 'meta_key' => 'store_id', 'meta_value' => $store_id, 'orderby' => 'display_name', 'order' => 'ASC' ) );
                if ( ! empty( $user_query->results ) ) {
                	foreach ( $user_query->results as $user ) {
                	   $user_status = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users WHERE ID = %s", $user->ID));
                       if($user_status[0]->user_status != 0){
                            echo '<option value="'.$user->ID.'">' .$user->display_name . '</option>';
                       }
                	}
                }
                ?>
            </select>

        </div>
    </div>
    <div class="section group">
        <div class="col span_12_of_12">
            <label>Fitting Room ID</label>
            <input type="number" pattern="[0-9]*" inputmode="numeric" name="fitting_room_id" />
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
                        <?php echo do_shortcode('[rev_slider home]'); ?>
                    </div>
                    <form method="post" action="http://istilist.com/dashboard" style="width:25%;margin-bottom:2%;float:right;">
                    	<div class="searchForm">
                        	<input type="text" id="search_query" name="search_query" value="<?php echo $_POST['search_query']; ?>" />
                        	<input type="submit" id="search_btn" name="search_btn" value="&#xf002" />
                     	</div>
                    </form>
                    <div class="bullkActionsForm">
	                    <form method="post" action="http://istilist.com/dashboard" id="bulkActionForm" style="">
	                    	<!--<input type="submit" name="bulk_btn" value="Submit" style=""/>-->
	                    	<div class="submit" style="width:50px;" onclick="confirmation();">Submit</div>
	                    </form>
	                    <select form="bulkActionForm" id="bulk_select" name="bulk_select"  style="">
	                    	<option value="NULL" selected="selected">Bulk Actions...</option>
	                    	<option value="purchased">Purchased</option>
	                    	<option value="not-purchased">Not Purchased</option>
	                    </select>

                    </div>
                    <?php if (!isset($_POST['search_query'])) { ?>
                    <?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
                    <?php

                             $post_args = array(
                            'post_type' => 'shopper',
                            'post_status' => 'publish',
                            'meta_key' => 'store_id',
                            'meta_value' => $store_id,
                            'paged' => $paged,
                            'posts_per_page' => 30,
                            'orderby'=> 'modified',
                             );

                             $store_reverse_order = get_user_meta($store_id, 'reverse_order', true);
                             if (empty($store_reverse_order) || $store_reverse_order == NULL) {
                             	$post_args['order'] = 'DESC';
                             }
                             else if ($store_reverse_order == "on") {
                             	$post_args['order'] = 'ASC';
                             }


                        $the_query = new WP_Query( $post_args );

                        if ( $the_query->have_posts() ){
                            while ( $the_query->have_posts() ) : $the_query->the_post();
                            $shopper_id = get_the_ID();
                    ?>
                        <?php if(get_post_meta($shopper_id, 'dollar_button_clicked', true) == 1){ ?>
                            <div class="box active">
                        <?php } else { ?>
                            <div class="box">
                        <?php } ?>
                            <div class="box_pic">
                            <?php echo get_profile_img($shopper_id); ?>
                            </div>
                            <div class="box_description" data-shopperid="<?php echo $shopper_id; ?>">
                                <h2>
                                	<?php echo get_post_meta($shopper_id, 'customer_fname', true); ?> <?php echo get_post_meta($shopper_id, 'customer_lname', true); ?>

                                	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                	<span><strong>Event:</strong> <?php echo get_post_meta($shopper_id, 'school_event', true); ?></span>
                                	<br />
                                	<?php
						$timestamps = get_post_meta($shopper_id, 'timestamps', true);
            $purchases = get_post_meta($shopper_id, 'purchase_array', true);
                                		if (!empty($timestamps)) {
                                			$index = count($timestamps);
							while($index) {
  								echo "<span>on ".date('m.d.Y', strtotime($timestamps[--$index]))." at ".date('h:i a', strtotime($timestamps[$index]));
                    if ($index != count($timestamps) - 1) {
                      if ($purchases[$index] == 'true') echo "\tPurchase";
                      else echo "\tNo Purchase";
                    }

                  echo "</span><br />";
							}
                                		}
                                	?>
                                	<span>on <?php echo date('m.d.Y', strtotime(get_post_meta($shopper_id, 'entry_date', true))); ?> at <?php echo date('h:i a', strtotime(get_post_meta($shopper_id, 'entry_date', true))); ?></span>
                                </h2>
                                <?php $assign_stylist = get_post_meta($shopper_id, 'assign_stylist', true); ?>
                                <?php if(!empty($assign_stylist)){ ?>
                                <div class="section group">
                                    <div class="col span_6_of_12">
                                        <p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Stylist Name </strong>: <span ><?php echo get_the_author_meta('display_name', get_post_meta($shopper_id, 'stylist_id', true)); ?></span></p>
                                        <p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Stylist Assigned </strong>: <span id="assignStylist_<?php echo $shopper_id; ?>"><?php echo date('h:i a', strtotime($assign_stylist)); ?></span></p>
                                    </div>
                                    <div class="col span_6_of_12">
                                    <p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Fitting Room ID </strong>: <span ><?php echo get_post_meta($shopper_id, 'fitting_room_id', true); ?></span></p>
                                    <?php if (empty($timestamps)) { ?>
                                    	<p class="assignStylistClass"><strong>Waiting Time :</strong> <span><?php elapsedtime(date('h:i:s', strtotime(get_post_meta($shopper_id, 'entry_date', true))), date('h:i:s', strtotime($assign_stylist))); ?></span></p>
                                    <?php } else if (count($timestamps) == 1 && get_post_meta($shopper_id, 'hit_plus', true) == 'false') { ?>
                                    	<p class="assignStylistClass"><strong>Waiting Time :</strong> <span><?php elapsedtime(date('h:i:s', strtotime(get_post_meta($shopper_id, 'entry_date', true))), date('h:i:s', strtotime($assign_stylist))); ?></span></p>
                                    <?php } else if (!empty($timestamps) && get_post_meta($shopper_id, 'hit_plus', true) == 'false') { ?>
                                    	<p class="assignStylistClass"><strong>Waiting Time :</strong><span><?php elapsedtime(date('h:i:s', strtotime($timestamps[count($timestamps) - 2])), date('h:i:s', strtotime($assign_stylist))); ?></span></p>
                                    <?php } else if (!empty($timestamps) && get_post_meta($shopper_id, 'hit_plus', true)  == 'true') { ?>
                                    	<p class="assignStylistClass"><strong>Waiting Time :</strong><span><?php elapsedtime(date('h:i:s', strtotime($timestamps[count($timestamps) - 1])), date('h:i:s', strtotime($assign_stylist))); ?></span></p>
                                    <?php } ?>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="section group">
                                	<div class="col span_12_of_12">
	                                    	<?php   //Adds number to show how many times a girl has gone in a fitting room on the current date
	                                		$daily_count = 0;
	                                		if (date('m.d.Y', strtotime(get_post_meta($shopper_id, 'entry_date', true))) == date('m.d.Y')) $daily_count++;
	                                		foreach ($timestamps as $timestamp) {
	                                			if (date('m.d.Y', strtotime($timestamp)) == date('m.d.Y')) $daily_count++;
	                                		}
	                                		if ($daily_count > 1) {
	                                			echo  "<p class='daily_rounds'>Fitting Room Rounds: ".$daily_count."</p>";
	                                		}
	                                	?>
                                	</div>
                                </div>
                                <p><?php echo excerpt(40); ?></p>
                                <?php if (get_post_meta($shopper_id, 'dollar_button_clicked', true) == 1 && get_post_meta($shopper_id, 'hit_plus', true) == 'false') { ?>
                                <?php $purchased = get_post_meta($shopper_id, 'reason_not_purchased', true); ?>
                                <?php if($purchased){ ?>
                                    <p class="reasone" style="padding-bottom: 5px;"><strong>Purchased?: </strong>NO</p>
                                    <p class="reasone"><strong>Reasons not to buy: </strong><?php echo get_post_meta($shopper_id, 'reason_not_purchased', true); ?></p>
                                <?php } else { ?>
                                    <p class="reasone"><strong>Purchased?: </strong>YES</p>
                                <?php } ?>
                                <?php } ?>
                            </div>
                            <div class="box_actions">
                                <ul>
                                    <li><a href="<?php bloginfo('url'); ?>/edit-shoppers?id=<?php echo encripted($shopper_id); ?>"><i class="fa fa-pencil"></i></a></li>
                                    <li><a href="javascript:void(0)" id="<?php echo $shopper_id; ?>-bell" <?php if (get_post_meta($shopper_id, 'notified', TRUE) == 'true') echo "style='color:#14b9d6'"; ?> onClick="sendTextNotification(<?php $customer_phone = get_post_meta($shopper_id, 'customer_phone', TRUE); if (!empty($customer_phone)) echo $shopper_id.', \'TRUE\''; else echo $shopper_id.', \'FALSE\''; ?>)"><i class="fa fa-bell"></i></a></li>
                                    <li><a href="#stylistpopup" class="assignStylist" <?php $assign_stylist = get_post_meta($shopper_id, 'assign_stylist', TRUE); if (!empty($assign_stylist)) echo "style='color:#14b9d6'"; ?> rel="<?php echo $shopper_id; ?>"><i class="icon-clothes4"></i></a></li>
                                    <!-- <li><a href="javascript:void(0);"><i class="fa fa-plus"></i></a></li> -->
                                    <li>
                                        <form method="post" action="">
                                            <input hidden="" value="<?php echo get_the_modified_date('Y-m-d H:i:s'); ?>" />
                                            <input type="hidden" name="shopper_id" value="<?php echo $shopper_id; ?>" />
                                            <input class="submitbtnimg" <?php $timestamps = get_post_meta($shopper_id, 'timestamps', TRUE); if (!empty($timestamps)) echo "style='color:#14b9d6'"; ?> type="submit" name="plusbtn" value="&#xf067;" />
                                        </form>
                                    </li>
                                    <li><a href="javascript:void(0);" <?php if (get_post_meta($shopper_id, 'dollar_button_clicked', true) == 1) echo "style='color:#14b9d6'"; ?> class="dollar" rel="<?php echo $shopper_id; ?>"><i class="fa fa-usd"></i></a></li>
                                    <li><a href="javascript:void(0);" onclick="check(<?php echo $shopper_id; ?>);" id="checkBox<?php echo $shopper_id; ?>"></a></li>
                                    <input type="hidden" value="no" form="bulkActionForm" id="checkInput<?php echo $shopper_id; ?>" name="<?php echo $shopper_id; ?>"/>
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
                        <?php } ?>
                        <?php wp_reset_postdata(); ?>
                        <div class="paginationWrapper"><?php if(function_exists('wp_pagenavi')) { wp_pagenavi( array( 'query' => $the_query ) ); } ?></div>
                    <?php } ?>
                    <?php if (isset($_POST['search_query'])) {
                    	include 'pagination.class.php';
                        $current_user_store_id = get_user_meta($user_ID, 'store_id', true);
                        /*Query 2 */
                        $arg2 = array(
                        	'meta_key' => 'customer_email',
                                'meta_value' => $_POST['search_query'],
                                'post_type' => 'shopper',
                                'post_status' => 'publish',
                                'posts_per_page' => -1
                        );
                        $query2 = new WP_Query($arg2);
                        $ids2 = array();
                        while ( $query2->have_posts() ) : $query2->the_post();
                        	array_push($ids2,get_the_ID());
                        endwhile;

                        /* Query 3 */
                        $arg3 = array(
                        	'meta_key' => 'customer_address',
                                'meta_value' => $_POST['search_query'],
                                'post_type' => 'shopper',
                                'post_status' => 'publish',
                                'posts_per_page' => -1
                        );
                        $query3 = new WP_Query($arg3);
                        $ids3 = array();
                        while ( $query3->have_posts() ) : $query3->the_post();
                        	array_push($ids3,get_the_ID());
                        endwhile;

                        /* Query 4 */
                        $arg4 = array(
                        	'meta_key' => 'customer_city',
                                'meta_value' => $_POST['search_query'],
                                'post_type' => 'shopper',
                                'post_status' => 'publish',
                                'posts_per_page' => -1
                        );
                        $query4 = new WP_Query($arg4);
                        $ids4 = array();
                        while ( $query4->have_posts() ) : $query4->the_post();
                        	array_push($ids4,get_the_ID());
                        endwhile;

                        /* Query 5 */
                        $arg5 = array(
                        	'meta_key' => 'customer_state',
                                'meta_value' => $_POST['search_query'],
                                'post_type' => 'shopper',
                                'post_status' => 'publish',
                                'posts_per_page' => -1
                        );
                        $query5 = new WP_Query($arg5);
                        $ids5 = array();
                        while ( $query5->have_posts() ) : $query5->the_post();
                        	array_push($ids5,get_the_ID());
                        endwhile;

                        /* Query 6 */
                        $arg6 = array(
                        	'meta_key' => 'customer_zip',
                                'meta_value' => $_POST['search_query'],
                                'post_type' => 'shopper',
                                'post_status' => 'publish',
                                'posts_per_page' => -1
                        );
                        $query6 = new WP_Query($arg6);
                        $ids6 = array();
                        while ( $query6->have_posts() ) : $query6->the_post();
                        	array_push($ids6,get_the_ID());
                        endwhile;

                        /* Query 7 */
                        /* Code Allows for Partial Searches */
                        function name_filter( $where, &$query7 )
                        {
                        	global $wpdb;
                                if ( $search_term = $query7->get( 'search_shopper_name' ) ) {
                                	$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql( like_escape( $search_term ) ) . '%\'';
                                }
                                return $where;
                        }
                        /***********************************/
                        $arg7 = array(
                        	'meta_key' => 'customer_fname',
                        	//'meta_value' => $_POST['search_query'], //original code
                       		 'search_shopper_name' => $_POST['search_query'], // added code for partial searches
                        	'post_type' => 'shopper',
                        	'post_status' => 'publish',
                                'posts_per_page' => -1
                        );
                        add_filter( 'posts_where', 'name_filter', 10, 2 ); //added code for partial searches
                        $query7 = new WP_Query($arg7);
                        remove_filter( 'posts_where', 'name_filter', 10, 2 ); //added code for partial searches
                        $ids7 = array();
                        while ( $query7->have_posts() ) : $query7->the_post();
                        	array_push($ids7,get_the_ID());
                        endwhile;

                        /* Query 8 */
                        $arg8 = array(
                        	'meta_key' => 'customer_lname',
                                'meta_value' => $_POST['search_query'],
                                'post_type' => 'shopper',
                                'post_status' => 'publish',
                                'posts_per_page' => -1
                        );
                        $query8 = new WP_Query($arg8);
                        $ids8 = array();
                        while ( $query8->have_posts() ) : $query8->the_post();
                        	array_push($ids8,get_the_ID());
                        endwhile;

                        /* Marge IDs and get Unique IDs*/
                        $mergedposts = array_merge( $ids2, $ids3, $ids4, $ids5, $ids6, $ids7, $ids8 );
                        $postids = array();
                        foreach( $mergedposts as $item ) {
                        	array_push($postids, $item);
                        }
                                        $uniqueposts1 = array_unique($postids);
                                        $uniqueposts = array();
                                        //print_r($uniqueposts);
                                        for($i=0; $i<count($uniqueposts1); $i++){
                                            $shopper_store_id = get_post_meta($uniqueposts1[$i], 'store_id', true);
                                            if($shopper_store_id == $current_user_store_id){
                                                array_push($uniqueposts,$uniqueposts1[$i]);
                                            }
                                        }


                        ?>
                                <?php if (count($uniqueposts)) { ?>
                                <?php $pagination = new pagination($uniqueposts, (isset($_GET['pageno']) ? $_GET['pageno'] : 1), 5); ?>
                                <?php
                                    $pagination->setShowFirstAndLast(false);
                                    $pagination->setMainSeperator('  ');
                                    $productPages = $pagination->getResults();
                                    if (count($productPages) != 0) {
                                        $pageNumbers = '<div class="numbers">'.$pagination->getLinks($_GET).'</div>';
                                ?>
                                <?php foreach ($productPages as $shopper_id) { ?>
                                <?php //foreach($uniqueposts as $shopper_id){ ?>
                                    <?php if(get_post_meta($shopper_id, 'dollar_button_clicked', true) == 1){ ?>
                                        <div class="box active">
                                    <?php } else { ?>
                                        <div class="box">
                                    <?php } ?>
                                    <div class="box_pic noprofileimg"><img src="<?php bloginfo('template_directory'); ?>/images/noprofileimg.png" /></div>
                                    <div class="box_description" data-shopperid="<?php echo $shopper_id; ?>">
                                <h2><?php echo get_post_meta($shopper_id, 'customer_fname', true); ?> <?php echo get_post_meta($shopper_id, 'customer_lname', true); ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><strong>Event:</strong> <?php echo get_post_meta($shopper_id, 'school_event', true); ?></span> <br /><span>on <?php echo date('m.d.Y', strtotime(get_post_meta($shopper_id, 'entry_date', true))); ?> at <?php echo date('h:i a', strtotime(get_post_meta($shopper_id, 'entry_date', true))); ?></span></h2>
                                <?php $assign_stylist = get_post_meta($shopper_id, 'assign_stylist', true); ?>
                                <?php if(!empty($assign_stylist)){ ?>
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
                                <?php } ?>
                                <p><?php echo excerpt(40); ?></p>
                                <?php if (get_post_meta($shopper_id, 'dollar_button_clicked', true) == 1) { ?>
                                <?php $purchased = get_post_meta($shopper_id, 'reason_not_purchased', true); ?>
                                <?php if($purchased){ ?>
                                    <p class="reasone" style="padding-bottom: 5px;"><strong>Purchased?: </strong>NO</p>
                                    <p class="reasone"><strong>Reasons not to buy: </strong><?php echo get_post_meta($shopper_id, 'reason_not_purchased', true); ?></p>
                                <?php } else { ?>
                                    <p class="reasone"><strong>Purchased?: </strong>YES</p>
                                <?php } ?>
                                <?php } ?>
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
                                    <?php } // end of if loop ?>
                                    <?php echo '<div style="clear: both;"></div><div class="customPagination" style="margin-bottom:2%">'.$pageNumbers.'</div>'; ?>
                                <?php } // end of foreach loop ?>
                                <?php } // end of if loop ?>
                                <?php } // end of main if loop ?>

                    <!-- BX Slider -->
                    <div class="slider">
                        <div class="bxslider">
                            <?php
                                $tips_args = array(
                                    'post_type' => 'tips',
                                    'post_status' => 'publish',
                                    'posts_per_page' => 3
                                );

                                $tips_query = new WP_Query($tips_args);
                                if($tips_query->have_posts()){
                                    while ( $tips_query->have_posts() ) : $tips_query->the_post();
                            ?>
                                <div>
                                    <h2><?php the_title(); ?></h2>
                                    <?php the_content(); ?>
                                </div>
                                <?php endwhile ?>
                            <?php } else{ ?>
                                <div>No Tips</div>
                            <?php } ?>
                            <?php wp_reset_postdata(); ?>
                        </div>
                        <div class="slider_controls">
                            <div class="slider_prev"></div>
                            <div class="slider_next"></div>
                        </div>
                    </div>
                </div>
                <?php get_footer(); ?>
	        </div>
	    </div>
	</div>
</div>
<script>
function sendTextNotification(shopper_id, is_phone) {
    /*
        Send AJAX notification if customer_phone meta is set

    */
    if (is_phone == 'TRUE') {
            //Send AJAX request to PHP script that sends text message to shopper
            jQuery.ajax({
               url: "http://istilist.com/notify-shopper/",
               method: "POST",
               data: {shopperID: shopper_id},
               success: function (e) {
                   if (e == 'na') {
                       swal({
                          title: "Error",
                          text: "This shopper did not authorize text messages.",
                          type: "info"
                       });
                   }
               },
               error: function (e) {
                   if (e == 'na') {
                       swal({
                          title: "Error",
                          text: "This shopper did not authorize text messages.",
                          type: "info"
                       });
                   }
               }
            });
    }
    else {
            //Do swal and let user know that phone number is not set
            swal({
                title: "Error",
                text: "This shopper does not have a phone number listed.",
                type: 'info'
            });
    }
    jQuery('#'+shopper_id+'-bell').css('color', '#14b9d6');
}

function check(shopper_id) {
	var input = document.getElementById('checkInput'+shopper_id);

	if (input.value == "" || input.value == "no") {
		input.value = "yes";
		jQuery('#checkBox'+shopper_id).append('<i class="fa fa-check"></i>');
		jQuery('#checkBox'+shopper_id).css('color', '#14b9d6');
	}
	else if (input.value === "yes") {
		input.value = "no";
		jQuery('#checkBox'+shopper_id).empty();
		jQuery('#checkBox'+shopper_id).css('color', '');
	}
}

function confirmation() {
	swal({
		title: "Are you sure?",
		text: "This action cannot be undone.",
		type: "warning",
		showCancelButton: true,
		showCancelButton: true,
	        confirmButtonColor: "#DD6B55",
	        confirmButtonText: "Yes",
	        cancelButtonText: "No",
	        closeOnConfirm: false,
	        closeOnCancel: true
	}, function (isConfirm) {
		if (isConfirm) {
			document.getElementById("bulkActionForm").submit();
		}
	});
}

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
                        swal({
                            title: "Thank You",
                            type: "success",
                        }, function(){
                            location.reload();
                        });
                	},
                	error:function(responce){
                	    console.log(responce);
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
                        inputValue = ".";
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
                    	error:function(responce){
                    	   console.log(responce);
                    		alert("failure : "+responce);
                    	}
                    });
                });
            }
        });
    });
});
</script>
<?php } else { header('Location: '.get_bloginfo('url').'/login'); } ?>
