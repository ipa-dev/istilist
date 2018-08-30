<?php /* Template Name: History */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    ?>
<?php global $user_ID; ?>
<?php $store_id = get_user_meta($user_ID, 'store_id', true); ?>
<?php if (isset($_POST['plusbtn'])) {
        update_post_meta($_POST['shopper_id'], 'dollar_button_clicked', 0);
        update_post_meta($_POST['shopper_id'], 'complete_purchase', 0);
        update_post_meta($_POST['shopper_id'], 'reason_not_purchased', '');
        $my_post = array(
        'ID' => $_POST['shopper_id'],
        'post_modified' => date('Y-m-d H:i:s')
    );
        wp_update_post($my_post);
    
        $timestamp_array = get_post_meta($_POST['shopper_id'], 'timestamps', true);
        if (!empty($timestamp_array)) {
            array_push($timestamp_array, date('Y-m-d H:i:s'));
            update_post_meta($_POST['shopper_id'], 'timestamps', $timestamp_array);
        } else {
            $timestamp_array = array();
            array_push($timestamp_array, date('Y-m-d H:i:s'));
            add_post_meta($_POST['shopper_id'], 'timestamps', $timestamp_array);
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
                    $msg_body1 = str_replace("{Shopper's Name}", $shopper_name1, $result2->body);
                    
                    $styist_id = get_post_meta($key, 'stylist_id', true);
                    
                    $stylist_name = get_the_author_meta('display_name', $styist_id);
                    $msg_body2 = str_replace("{Stylist's Name}", $stylist_name, $msg_body1);
                    
                    
                    $store_name = get_the_author_meta('display_name', $store_id);
                    $from = get_user_meta($store_id, 'email_to_shopper', true);
                    
                    $shopper_name  = $shopper_name1;
                    $headers = 'From: '.$store_name.'<'.$from.'>'."\r\n";
                    $headers .= "Reply-To: ". strip_tags($from) . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $subject = $result2->subject;
                    $msg = $msg_body2;
                    
                    
                    if (!empty($store_name)) {
                        if (!empty($from)) {
                            //wp_mail( $shopper_email, $subject, $msg, $headers);
                            mail($shopper_email, $subject, $msg, $headers);
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
                    $msg_body1 = str_replace("{Shopper's Name}", $shopper_name1, $result2->body);
                    
                    $styist_id = get_post_meta($key, 'stylist_id', true);
                    
                    $stylist_name = get_the_author_meta('display_name', $styist_id);
                    $msg_body2 = str_replace("{Stylist's Name}", $stylist_name, $msg_body1);
                    
                    if ($options['smtp-active'] == 1) {
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
                    
                    if (!empty($store_name)) {
                        if (!empty($from)) {
                            //wp_mail( $shopper_email, $subject, $msg, $headers );
                            mail($shopper_email, $subject, $msg, $headers);
                        }
                    }
                }
            }
        }
    } ?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                
                   
                    <div class="dash_content"> 
                    	<h1><?php the_title(); ?></h1>
	        	<form id="update_shoppers" action="http://istilist.com/history" method="POST" style="float:right;">
                    		<div style="margin-bottom:2%">
                    			<span style="float:left;margin: 2px 1% 0 0">Select Date</span>
                    			<input id="shoppersfromdate" name="shoppersfromdate" type="text" style="width:40%;height:25px;">
                    		</div>
                    	</form>
                    	<div class="bullkActionsForm" style="margin-bottom:7%;">
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
                    	<script>
                    		jQuery("#shoppersfromdate").on("change", function () {
                    			jQuery("#update_shoppers").submit();
                   		 });
                    	</script>
                    	<?php
                            if ($_POST["shoppersfromdate"]) {
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
                                					<span>on <?php echo date('m.d.Y', strtotime(get_post_meta($shopper_id, 'entry_date', true))); ?> at <?php echo date('h:i a', strtotime(get_post_meta($shopper_id, 'entry_date', true))); ?></span></h2>
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
                                                    $prev_stylist_array = get_post_meta($shopper_id, 'prev_stylists', true);
            if (!empty($prev_stylist_array)) {
                $index = count($prev_stylist_array) - 2;
                while ($index >= 0) {
                    ?>
                                							<div class="section group">
                                								<div class="col span_6_of_12">
			                                        					<p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Stylist Name </strong>: <span ><?php echo get_the_author_meta('display_name', $prev_stylist_array[$index]['stylist_id']); ?></span></p>
			                                        					<p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Stylist Assigned </strong>: <span id="assignStylist_<?php echo $shopper_id; ?>"><?php echo date('h:i a', strtotime($prev_stylist_array[$index]['assignment_date'])); ?></span></p>
		                                    						</div>
			                                    					<div class="col span_6_of_12">
			                                    						<p class="assignStylistClass" style="padding-bottom: 6px;"><strong>Fitting Room ID </strong>: <span ><?php echo $prev_stylist_array[$index]['fitting_room_id']?></span></p>
			                                    						<!-- <p class="assignStylistClass"><strong>Waiting Time :</strong> <span><?php elapsedtime(date('h:i:s', strtotime(get_post_meta($shopper_id, 'entry_date', true))), date('h:i:s', strtotime($assign_stylist))); ?></span></p> -->
			                                    					</div>
                                							</div>
                                				<?php
                                                            $index--;
                }
            } ?>
                                			<?php
        } ?>
                                			<p><?php echo excerpt(40); ?></p>
                           				 </div>
                           				 <div class="box_actions">
                                				<ul>
                                    					<li><a href="<?php bloginfo('url'); ?>/edit-shoppers?id=<?php echo encripted($shopper_id); ?>"><i class="fa fa-pencil"></i></a></li>
                                    					<li>
                                        					<form method="post" action="">
                                            						<input hidden="" value="<?php echo get_the_modified_date('Y-m-d H:i:s'); ?>" />
                                            						<input type="hidden" name="shopper_id" value="<?php echo $shopper_id; ?>" />
                                            						<input class="submitbtnimg" type="submit" name="plusbtn" value="&#xf067;" />
                                        					</form> 
                                    					</li>
                                    					<li><a href="javascript:void(0);" class="dollar" rel="<?php echo $shopper_id; ?>"><i class="fa fa-usd"></i></a></li>
                                    					<li><a href="javascript:void(0);" onclick="check(<?php echo $shopper_id; ?>);" id="checkBox<?php echo $shopper_id; ?>"></a></li>
                                    					<input type="hidden" value="no" form="bulkActionForm" id="checkInput<?php echo $shopper_id; ?>" name="<?php echo $shopper_id; ?>"/>
                                				</ul>
                            				</div>
                            				<div style="clear: both;"></div>

                    				</div>
                    		<?php endwhile;
    } ?>
                    </div>

                            
                <?php get_footer(); ?>
                </div>
                
            </div>
        </div>
</div>
<script>
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
                	   //alert(responce);
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
</script>
<?php
} else {
        header('Location: '.get_bloginfo('url').'/login');
    } ?>