<?php /* Template Name: Dashboard */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    ?>
<?php global $user_ID;
    global $wpdb; ?>
<?php $store_id = get_user_meta($user_ID, 'store_id', true); ?>
<div id="stylistpopup">
	<?php
    //When user presses assign stylist button
    if (isset($_POST['submit'])) {
        $shopper_id   = $_POST['shopper_id'];
        $current_date = date('Y-m-d H:i:s');
        global $wpdb;
        update_post_meta($shopper_id, 'assign_stylist', $current_date);
        update_post_meta($shopper_id, 'stylist_id', $_POST['stylist_id']);
        update_post_meta($shopper_id, 'fitting_room_id', $_POST['fitting_room_id']);


        $prev_stylist_array = get_post_meta($shopper_id, 'prev_stylists', true);

        //Store all information on each stylist girl has been assigned
        if (empty($prev_stylist_array)) {
            $prev_stylist_array = array(
                array(
                    'assignment_date' => $current_date,
                    'stylist_id'      => $_POST['stylist_id'],
                    'fitting_room_id' => $_POST['fitting_room_id']
                )
            );
            add_post_meta($shopper_id, 'prev_stylists', $prev_stylist_array);
        } elseif (! empty($prev_stylist_array)) {
            $array_addition = array(
                'assignment_date' => $current_date,
                'stylist_id'      => $_POST['stylist_id'],
                'fitting_room_id' => $_POST['fitting_room_id']
            );
            array_push($prev_stylist_array, $array_addition);
            update_post_meta($shopper_id, 'prev_stylists', $prev_stylist_array);
        }

        $hit_plus = get_post_meta($_POST['shopper_id'], 'hit_plus', true);
        if (! empty($hit_plus)) {
            if ($hit_plus == 'true') {
                $my_post = array(
                    'ID'            => $_POST['shopper_id'],
                    'post_modified' => date('Y-m-d H:i:s')
                    //'orderby' => 'date',
                    //'order' => 'ASC'
                );
                wp_update_post($my_post);
                $timestamp_array = get_post_meta($_POST['shopper_id'], 'timestamps', true);
                if (! empty($timestamp_array)) {
                    array_push($timestamp_array, date('Y-m-d H:i:s'));
                    update_post_meta($_POST['shopper_id'], 'timestamps', $timestamp_array);
                } else {
                    $timestamp_array = array();
                    array_push($timestamp_array, date('Y-m-d H:i:s'));
                    add_post_meta($_POST['shopper_id'], 'timestamps', $timestamp_array);
                }
                update_post_meta($_POST['shopper_id'], 'hit_plus', 'false');
            } elseif ($hit_plus == 'false') {
                update_post_meta($_POST['shopper_id'], 'hit_plus', 'true');
            }
        } else {
            //user has not hit plus button after first round
            $test = 0;
        }

        header('Location: ' . get_bloginfo('url') . '/dashboard');
    }

    //When user presses plus button
    if (isset($_POST['plusbtn'])) {
        $my_post = array(
            'ID'            => $_POST['shopper_id'],
            'post_date' => date('Y-m-d H:i:s'),
            'post_modified' => date('Y-m-d H:i:s')
        );
        wp_update_post($my_post);

        $purchases = get_post_meta($_POST['shopper_id'], 'purchase_array', true);
        if ('dollar_button_clicked' == 0) {
            if (empty($purchases)) {
                add_post_meta($_POST['shopper_id'], 'purchase_array', [ 'false' ]);
            } else {
                array_push($purchases, 'false');
                update_post_meta($_POST['shopper_id'], 'purchase_array', $purchases);
            }
        }
        update_post_meta($_POST['shopper_id'], 'dollar_button_clicked', 0);
        update_post_meta($_POST['shopper_id'], 'complete_purchase', 0);
        update_post_meta($_POST['shopper_id'], 'reason_not_purchased', '');
        delete_post_meta($_POST['shopper_id'], 'notified');


        $timestamp_array = get_post_meta($_POST['shopper_id'], 'timestamps', true);
        if (! empty($timestamp_array)) {
            array_push($timestamp_array, date('Y-m-d H:i:s'));
            update_post_meta($_POST['shopper_id'], 'timestamps', $timestamp_array);
        } else {
            $timestamp_array = array();
            array_push($timestamp_array, date('Y-m-d H:i:s'));
            add_post_meta($_POST['shopper_id'], 'timestamps', $timestamp_array);
        }

        $hit_plus = get_post_meta($_POST['shopper_id'], 'hit_plus', true);
        if (! empty($hit_plus)) {
            update_post_meta($_POST['shopper_id'], 'hit_plus', 'false');
        } else {
            add_post_meta($_POST['shopper_id'], 'hit_plus', 'false');
        }
        header('Location: ' . get_bloginfo('url') . '/dashboard');
    }
    if (isset($_POST['bulk_select'])) {
        if ($_POST['bulk_select'] == 'all-shoppers') {
            global $user_ID;
            global $wpdb;
            $store_owner_id = get_user_meta($user_ID, 'store_id', true);
            $store_id = get_user_meta($user_ID, 'store_id', true);
            $table_name = $wpdb->prefix.'folloup_messages';

            $store_name = get_the_author_meta('display_name', $store_id);
            $from       = get_user_meta($store_id, 'email_to_shopper', true);

            $sql = "SELECT * FROM $table_name WHERE message_type = 'purchased-shoppers' and store_id = $store_id";
            $result = $wpdb->get_row($sql);

            $headers      = 'From: ' . $store_name . '<' . $from . '>' . "\r\n";
            $headers      .= "Reply-To: " . strip_tags($from) . "\r\n";
            $headers      .= "MIME-Version: 1.0\r\n";
            $headers      .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $subject      = $result->subject;

            $shopper_args = array(
                'post_type' => 'shopper',
                'post_status' => 'publish',
                'author' => $store_id,
                'posts_per_page' => -1
            );
            $shopper_data = new WP_Query($shopper_args);
            if ($shopper_data->have_posts()) {
                while ($shopper_data->have_posts()) : $shopper_data->the_post();
                $shopper_id = get_the_ID();
                $purchase_array = get_post_meta($shopper_id, 'purchase_array', true);
                if (! empty($purchase_array)) {
                    if ($purchase_array[0] == 'true') {
                        $purchase_status = 'YES';
                    } else {
                        $purchase_status = 'NO';
                    }
                }
                if (($purchase_status == 'YES') || ($purchase_status = 'NO')) {
                    $shopper_name = get_post_meta($shopper_id, 'customer_fname', true) . ' ' . get_post_meta($shopper_id, 'customer_lname', true);
                    $shopper_email_address = get_post_meta($shopper_id, 'customer_email', true);
                    $msg = str_replace("{Shopper's Name}", $shopper_name, $result->body);
                    mail($shopper_email_address, $subject, $msg, $headers);
                }
                endwhile;
            }
        }
        if ($_POST['bulk_select'] == 'purchased') {
            foreach ($_POST as $key => $value) {
                if ($value == "yes") {
                    update_post_meta($key, 'complete_purchase', 1);
                    update_post_meta($key, 'dollar_button_clicked', 1);

                    $shopper_email = get_post_meta($key, 'customer_email', true);

                    $table_name1 = $wpdb->prefix . 'folloup_messages';
                    $sql2        = "SELECT * FROM $table_name1 WHERE message_type = 'thankyou' and store_id = $store_id";
                    $result2     = $wpdb->get_row($sql2);

                    $shopper_name1 = get_post_meta($key, 'customer_fname', true) . ' ' . get_post_meta($key, 'customer_lname', true);
                    $msg_body1     = str_replace("{Shopper's Name}", $shopper_name1, $result2->body);

                    $styist_id = get_post_meta($key, 'stylist_id', true);

                    $stylist_name = get_the_author_meta('display_name', $styist_id);
                    $msg_body2    = str_replace("{Stylist's Name}", $stylist_name, $msg_body1);


                    $store_name = get_the_author_meta('display_name', $store_id);
                    $from       = get_user_meta($store_id, 'email_to_shopper', true);

                    $shopper_name = $shopper_name1;
                    $headers      = 'From: ' . $store_name . '<' . $from . '>' . "\r\n";
                    $headers      .= "Reply-To: " . strip_tags($from) . "\r\n";
                    $headers      .= "MIME-Version: 1.0\r\n";
                    $headers      .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $subject      = $result2->subject;
                    $msg          = $msg_body2;


                    if (! empty($store_name)) {
                        if (! empty($from)) {
                            wp_mail($shopper_email, $subject, $msg, $headers);
                        }
                    }
                }
            }
        }
        if ($_POST['bulk_select'] == 'not-purchased') {
            foreach ($_POST as $key => $value) {
                if ($value == "yes") {
                    $reason      = ".";
                    $table_name1 = $wpdb->prefix . 'folloup_messages';

                    update_post_meta($key, 'reason_not_purchased', $reason);
                    update_post_meta($key, 'dollar_button_clicked', 1);

                    $shopper_email = get_post_meta($key, 'customer_email', true);

                    $sql2    = "SELECT * FROM $table_name1 WHERE message_type = 'promo' and store_id = $store_id";
                    $result2 = $wpdb->get_row($sql2);

                    $shopper_name1 = get_post_meta($key, 'customer_fname', true) . ' ' . get_post_meta($key, 'customer_lname', true);
                    $msg_body1     = str_replace("{Shopper's Name}", $shopper_name1, $result2->body);

                    $styist_id = get_post_meta($key, 'stylist_id', true);

                    $stylist_name = get_the_author_meta('display_name', $styist_id);
                    $msg_body2    = str_replace("{Stylist's Name}", $stylist_name, $msg_body1);

                    if ($options['smtp-active'] == 1) {
                        $from = get_user_meta($user_ID, 'email_to_shopper', true); //get_the_author_meta('user_email', $store_id);
                    } else {
                        $from = get_user_meta($user_ID, 'email_to_shopper', true); //get_the_author_meta('user_email', $store_id);
                    }

                    $store_name = get_the_author_meta('display_name', $store_id);
                    $from       = get_user_meta($store_id, 'email_to_shopper', true);

                    $shopper_name = $shopper_name1;
                    $headers      = 'From: ' . $store_name . '<' . $from . '>' . "\r\n";
                    $headers      .= "Reply-To: " . strip_tags($from) . "\r\n";
                    $headers      .= "MIME-Version: 1.0\r\n";
                    $headers      .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $subject      = $result2->subject;
                    $msg          = $msg_body2;

                    if (! empty($store_name)) {
                        if (! empty($from)) {
                            //wp_mail( $shopper_email, $subject, $msg, $headers );
                            mail($shopper_email, $subject, $msg, $headers);
                        }
                    }
                }
            }
        }
        if ($_POST['bulk_select'] == 'stylist-employees') {
            global $user_ID;
            global $wpdb;
            $store_owner_id = get_user_meta($user_ID, 'store_id', true);
            $store_id = get_user_meta($user_ID, 'store_id', true);
            $table_name = $wpdb->prefix.'folloup_messages';

            $store_name = get_the_author_meta('display_name', $store_id);
            $from       = get_user_meta($store_id, 'email_to_shopper', true);

            $sql = "SELECT * FROM $table_name WHERE message_type = 'stylist-employees' and store_id = $store_id";
            $result = $wpdb->get_row($sql);

            $headers      = 'From: ' . $store_name . '<' . $from . '>' . "\r\n";
            $headers      .= "Reply-To: " . strip_tags($from) . "\r\n";
            $headers      .= "MIME-Version: 1.0\r\n";
            $headers      .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $subject      = $result->subject;

            $user_query = new WP_User_Query(
                array(
                    'role__in' => array('storeemployee', 'storesupervisor'),
                    'meta_key' => 'store_id',
                    'meta_value' => $store_id,
                    'orderby' => 'display_name',
                    'order' => 'ASC'
                )
            );
            if (!empty($user_query->results)) {
                foreach ($user_query->results as $user) {
                    $user_status = get_the_author_meta('user_status', $user->ID);
                    if ($user_status == 2) {
                        $stylist_email = get_the_author_meta('user_email', $user->ID);
                        $stylist_name = $user->display_name;
                        $msg = str_replace("{Stylist-Employee's Name}", $stylist_name, $result->body);
                        mail($stylist_email, $subject, $msg, $headers);
                    }
                }
            }
        }
    } ?>
    <form method="post" action="">
        <div class="section group">
            <div class="col span_12_of_12">
                <h3>Assign Stylist</h3>
                <label>Select Stylist</label>
                <select name="stylist_id">
					<?php
                    $user_query = new WP_User_Query(array(
                        'role'       => 'storeemployee',
                        'meta_key'   => 'store_id',
                        'meta_value' => $store_id,
                        'orderby'    => 'display_name',
                        'order'      => 'ASC'
                    ));
    if (! empty($user_query->results)) {
        foreach ($user_query->results as $user) {
            $user_status = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users WHERE ID = %s", $user->ID));
            if ($user_status[0]->user_status != 0) {
                echo '<option value="' . $user->ID . '">' . $user->display_name . '</option>';
            }
        }
    } ?>
                </select>

            </div>
        </div>
        <div class="section group">
            <div class="col span_12_of_12">
                <label>Fitting Room ID</label>
                <input type="number" pattern="[0-9]*" inputmode="numeric" name="fitting_room_id"/>
            </div>
        </div>
        <div class="section group">
            <div class="col span_12_of_12">
                <input id="shopper_id" type="hidden" name="shopper_id" value=""/>
                <input type="submit" name="submit" value="Assign"/>
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
                    <form method="get" action="https://istilist.com/dashboard" style="width:25%;margin-bottom:2%;float:right;">
                        <div class="searchForm">
                            <input type="text" id="search_query" name="search_query" value="<?php echo $_GET['search_query']; ?>"/>
                            <input type="submit" id="search_btn" name="search_btn" value="&#xf002"/>
                        </div>
                    </form>
                    <div class="bullkActionsForm">
                        <form method="post" action="https://istilist.com/dashboard" id="bulkActionForm" style="">
                            <!--<input type="submit" name="bulk_btn" value="Submit" style=""/>-->
                            <div class="submit" style="width:50px;" onclick="confirmation();">Submit</div>
                        </form>
                        <select form="bulkActionForm" id="bulk_select" name="bulk_select" style="">
                            <option value="NULL" selected="selected">Bulk Actions...</option>
                            <option value="all-shoppers">All Shoppers</option>
                            <option value="purchased">Purchased Shoppers</option>
                            <option value="not-purchased">Not Purchased Shoppers</option>
                            <option value="stylist-employees">Stylist/Employees</option>
                        </select>

                    </div>
					<?php if (! isset($_GET['search_query'])) {
        ?>
					<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
					<?php

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
            while ($the_query->have_posts()) : $the_query->the_post();
            $shopper_id = get_the_ID(); ?>
					
                        <?php
                            require 'php_modules/template-dashboard/shopper-single.php';
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
						<?php
    } ?>
		<?php if (isset($_GET['search_query'])) {
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
        while ($query->have_posts()) : $query->the_post();
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
					if (get_post_meta($shopper_id, 'dollar_button_clicked', true) == 1) {
        ?>
                        <div class="box active">
		<?php
                    } else {
        ?>
                            <div class="box">
		<?php       } ?>
                                <!--<div class="box_pic noprofileimg"><img
                                            src="<?php bloginfo('template_directory'); ?>/images/noprofileimg.png"/>
                                </div>-->
                                <div class="box_description" data-shopperid="<?php echo $shopper_id; ?>">
                                    <h2><?php echo get_post_meta($shopper_id, 'customer_fname', true); ?> <?php echo get_post_meta($shopper_id, 'customer_lname', true); ?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><strong>Event:</strong> <?php echo get_post_meta($shopper_id, 'school_event', true); ?></span>
                                        <br/><span><?php echo date('m.d.Y', strtotime(get_post_meta($shopper_id, 'entry_date', true))); ?>
                                            at <?php echo date('h:i a', strtotime(get_post_meta($shopper_id, 'entry_date', true))); ?>
                                            - <?php $stylist_id = get_post_meta($shopper_id, 'stylist_id', true);
                    if (! empty($stylist_id)) {
                        echo get_the_author_meta('display_name', get_post_meta($shopper_id, 'stylist_id', true));
                    } ?>
											<?php
                                            $purchases = get_post_meta($shopper_id, 'purchase_array', true);
                    if (! empty($purchases)) {
                        if ($purchases[0] == 'true') {
                            echo "\t - YES";
                        } else {
                            echo "\t - NO";
                        }
                    } ?></span></h2>
									<?php $assign_stylist = get_post_meta($shopper_id, 'assign_stylist', true); ?>
									<?php if (! empty($assign_stylist)) {
                        ?>
                                        <div class="section group">
                                            <div class="col span_6_of_12">
                                                <p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Stylist
                                                        Name </strong>:
                                                    <span><?php echo get_the_author_meta('display_name', get_post_meta($shopper_id, 'stylist_id', true)); ?></span>
                                                </p>
                                                <p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Stylist
                                                        Assigned </strong>: <span
                                                            id="assignStylist_<?php echo $shopper_id; ?>"><?php echo date('h:i a', strtotime($assign_stylist)); ?></span>
                                                </p>
                                            </div>
                                            <div class="col span_6_of_12">
                                                <p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Fitting
                                                        Room ID </strong>:
                                                    <span><?php echo get_post_meta($shopper_id, 'fitting_room_id', true); ?></span>
                                                </p>
                                                <p class="assignStylistClass"><strong>Waiting Time :</strong>
                                                    <span><?php elapsedtime(date('h:i:s', strtotime(get_post_meta($shopper_id, 'entry_date', true))), date('h:i:s', strtotime($assign_stylist))); ?></span>
                                                </p>
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
                                            <p class="reasone" style="padding-bottom: 5px;">
                                                <strong>Purchased?: </strong>NO</p>
                                            <p class="reasone"><strong>Reasons not to
                                                    buy: </strong><?php echo get_post_meta($shopper_id, 'reason_not_purchased', true); ?>
                                            </p>
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
                                        <li title="Update Shopper">
                                            <a href="<?php bloginfo('url'); ?>/edit-shoppers?id=<?php echo encripted($shopper_id); ?>"><i
                                                        class="fa fa-pencil"></i></a></li>
                                        <li title="Assign Stylist"><a href="#stylistpopup" class="assignStylist"
                                               rel="<?php echo $shopper_id; ?>"><i class="icon-clothes4"></i></a></li>
                                        <li title="Return To Top">
                                            <form method="post" action="">
                                                <input hidden=""
                                                       value="<?php echo get_the_modified_date('Y-m-d H:i:s'); ?>"/>
                                                <input type="hidden" name="shopper_id"
                                                       value="<?php echo $shopper_id; ?>"/>
                                                <input class="submitbtnimg" type="submit" name="plusbtn"
                                                       value="&#xf067;"/>
                                            </form>
                                        </li>
                                        <li title="Purchased?"><a href="javascript:void(0);" class="dollar"
                                               rel="<?php echo $shopper_id; ?>"><i class="fa fa-usd"></i></a></li>
                                    </ul>
                                </div>
                                <div style="clear: both;"></div>
                            </div>
							<?php
                } // end of if loop?>
							<?php echo '<div style="clear: both;"></div><div class="customPagination" style="margin-bottom:2%">' . $pageNumbers . '</div>'; ?>
							<?php
            } // end of foreach loop?>
							<?php
        } // end of if loop?>
							<?php
    } // end of main if loop?>

                            <!-- BX Slider -->
                            <div class="slider">
                                <div class="bxslider">
									<?php
                                    $tips_args = array(
                                        'post_type'      => 'tips',
                                        'post_status'    => 'publish',
                                        'posts_per_page' => 3
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
                        url: "https://istilist.com/notify-shopper/",
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
                jQuery('#' + shopper_id + '-bell').css('color', '#14b9d6');
            }

            function check(shopper_id) {
                var input = document.getElementById('checkInput' + shopper_id);

                if (input.value == "" || input.value == "no") {
                    input.value = "yes";
                    jQuery('#checkBox' + shopper_id).append('<i class="fa fa-check"></i>');
                    jQuery('#checkBox' + shopper_id).css('color', '#14b9d6');
                }
                else if (input.value === "yes") {
                    input.value = "no";
                    jQuery('#checkBox' + shopper_id).empty();
                    jQuery('#checkBox' + shopper_id).css('color', '');
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

            jQuery(document).ready(function () {

                // this is for second button
                jQuery('.assignStylist').click(function () {
                    var shopper_id = jQuery(this).attr('rel');
                    jQuery('#shopper_id').val(shopper_id);
                });

                // this is for 3rd button
                jQuery('.dollar').click(function () {
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
                    }, function (isConfirm) {
                        if (isConfirm) {
                            jQuery.ajax({
                                url: "<?php echo get_bloginfo('url'); ?>/complete-purchase",
                                type: "post",
                                data: {
                                    "store_id": <?php echo get_user_meta($user_ID, 'store_id', true); ?>,
                                    "shopper_id": shopper_id
                                },
                                success: function (responce) {
                                    swal({
                                        title: "Thank You",
                                        type: "success",
                                    }, function () {
                                        location.reload();
                                    });
                                },
                                error: function (responce) {
                                    console.log(responce);
                                    alert("failure : " + responce);
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
                            }, function (inputValue) {
                                if (inputValue === false) return false;
                                if (inputValue === "") {
                                    inputValue = ".";
                                }
                                jQuery.ajax({
                                    url: "<?php echo get_bloginfo('url'); ?>/no-purchase",
                                    type: "post",
                                    data: {
                                        "store_id": <?php echo get_user_meta($user_ID, 'store_id', true); ?>,
                                        "shopper_id": shopper_id,
                                        "reason": inputValue
                                    },
                                    success: function (responce) {
                                        //alert("success : "+responce);
                                        swal.close();
                                        location.reload();
                                    },
                                    error: function (responce) {
                                        console.log(responce);
                                        alert("failure : " + responce);
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
        header('Location: ' . get_bloginfo('url') . '/login');
    } ?>
