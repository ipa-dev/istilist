<?php /* Template Name: Dashboard */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    ?>
<?php global $user_ID;
    global $wpdb; ?>
<?php $store_id = get_user_meta($user_ID, 'store_id', true); ?>
<div id="stylistpopup">
	<?php
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
    <form method="post" action="<?= get_bloginfo('url'); ?>/process-assign-stylist">
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
                <input type="submit" name="assign_stylist" value="Assign"/>
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
                    <?php 
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
                        require 'php_modules/template-dashboard/shopper-single.php';
                    }
							echo '<div style="clear: both;"></div><div class="customPagination" style="margin-bottom:2%">' . $pageNumbers . '</div>'; ?>
							<?php
            } // end of foreach loop?>
							<?php
        } // end of if loop?>
							<?php
    } // end of main if loop?>

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
        <script>
            function sendTextNotification(shopper_id) {
                /*
					Send AJAX notification if customer_phone meta is set
				*/
                //Send AJAX request to PHP script that sends text message to shopper
                jQuery.ajax({
                    url: "https://istilist.com/notify-shopper/",
                    method: "POST",
                    data: {shopperID: shopper_id},
                    error: function (e) { //TODO: Make this error message if there is no phone number for customer
                        if (e == 'na') {
                            swal({
                                title: "Error",
                                text: "This shopper did not authorize text messages.",
                                type: "error"
                            });
                        }
                    }
                });
                
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
                                        swal.close();
                                        location.reload();
                                    },
                                    error: function (responce) {
                                        console.log(responce);
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
