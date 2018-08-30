<?php /* Template Name: Edit Shoppers */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    ?>
<?php global $user_ID; ?>
<?php $store_id = get_user_meta($user_ID, 'store_id', true); ?>
<?php $shopper_id = decripted($_GET['id']); ?>
<div id="dashboard" data-shopper_id="<?php echo $shopper_id; ?>">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <h1><?php the_title(); ?></h1>
                    <div class="box addnewshoppers">
                        <?php
                            if (isset($_POST['update_shopper'])) {
                                global $wpdb;
                                $post_arg = array(
                                    'ID' => $shopper_id,
                                    'post_title' => $_POST['customer_fname'].' '.$_POST['customer_lname'],
                                    'post_content' => $_POST['shoppers_feedback'],
                                    'post_author' => $user_ID
                                );

                                wp_update_post($post_arg);

                                update_post_meta($shopper_id, 'customer_fname', $_POST['customer_fname']);
                                update_post_meta($shopper_id, 'customer_lname', $_POST['customer_lname']);
                                update_post_meta($shopper_id, 'school_event', $_POST['school_event']);
                                update_post_meta($shopper_id, 'graduation_year', $_POST['graduation_year']);
                                update_post_meta($shopper_id, 'customer_email', $_POST['customer_email']);
                                update_post_meta($shopper_id, 'customer_phone', $_POST['customer_phone']);
                                update_post_meta($shopper_id, 'design_preferences', $_POST['design_preferences']);
                                update_post_meta($shopper_id, 'style_preferences', $_POST['style_preferences']);
                                update_post_meta($shopper_id, 'color_preferences', $_POST['color_preferences']);
                                update_post_meta($shopper_id, 'customer_size', $_POST['customer_size']);
                                update_post_meta($shopper_id, 'customer_address', $_POST['customer_address']);
                                update_post_meta($shopper_id, 'customer_city', $_POST['customer_city']);
                                update_post_meta($shopper_id, 'customer_state', $_POST['customer_state']);
                                update_post_meta($shopper_id, 'customer_zip', $_POST['customer_zip']);
                                update_post_meta($shopper_id, 'sms_agreement', $_POST['sms_agreement']);
                                //update_post_meta($shopper_id, 'entry_date', date('Y-m-d H:i:s'));
                                //update_post_meta($shopper_id, 'store_id', get_user_meta($user_ID, 'store_id', true));

                                $store_id = get_user_meta($user_ID, 'store_id', true);
                                $table_name3 = $wpdb->prefix.'dynamic_form';
                                $sql3 = "SELECT * FROM $table_name3 WHERE store_owner_id = $store_id AND is_custom = 1 ORDER BY id";
                                $results3 = $wpdb->get_results($sql3);
                                if (!empty($results3)) {
                                    foreach ($results3 as $r3) {
                                        $var1 = $r3->form_slug;
                                        $var2 = $_POST[$r3->form_slug];
                                        update_post_meta($shopper_id, $var1, $var2);
                                    }
                                }

                                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                                require_once(ABSPATH . "wp-admin" . '/includes/file.php');
                                require_once(ABSPATH . "wp-admin" . '/includes/media.php');

                                $image = $_FILES['profile_pic'];
                                if ($image['size']) {     // if it is an image
                                    if (preg_match('/(jpg|jpeg|png|gif)$/', $image['type'])) {
                                        $override = array('test_form' => false);       // save the file, and store an array, containing its location in $file
                                        $file = wp_handle_upload($image, $override);
                                        $attachment = array(
                                            'post_title' => $image['name'],
                                            'post_content' => '',
                                            'post_type' => 'attachment',
                                            'post_mime_type' => $image['type'],
                                            'guid' => $file['url']
                                        );

                                        $attach_id = wp_insert_attachment($attachment, $file[ 'file' ], $shopper_id);
                                        $attach_data = wp_generate_attachment_metadata($attach_id, $file['file']);
                                        wp_update_attachment_metadata($attach_id, $attach_data);
                                        update_post_meta($shopper_id, 'profile_pic', $attach_id);
                                    } else {
                                        wp_die('No image was uploaded.');
                                    }
                                }

                                if ($shopper_id) {
                                    echo '<p class="successMsg">Thank you for your valuable time and information.</p>';
                                    header("Location: ".get_bloginfo('home')."/dashboard");
                                } else {
                                    echo '<p class="errorMsg">Sorry, your information is not updated.</p>';
                                }
                            } ?>
                        <form id="forms" method="post" action="" enctype="multipart/form-data">
                            <div class="section group form_list" data-shopper_id="<?php echo $shopper_id; ?>">
                                <?php if (check_is_active('customer_fname') == 1) {
                                ?>
                                    <div class="col span_6_of_12 matchheight">
                                        <label>First Name <span>*</span></label>
                                        <input type="text" name="customer_fname" value="<?php echo get_post_meta($shopper_id, 'customer_fname', true); ?>" />
                                    </div>
                                <?php
                            } ?>
                                <?php if (check_is_active('customer_lname') == 1) {
                                ?>
                                    <div class="col span_6_of_12 matchheight">
                                        <label>Last Name <span>*</span></label>
                                        <input type="text" name="customer_lname" value="<?php echo get_post_meta($shopper_id, 'customer_lname', true); ?>" />
                                    </div>
                                <?php
                            } ?>
                                <?php if (check_is_active('profile_pic') == 1) {
                                ?>
                                    <div class="col span_6_of_12 matchheight">
                                        <label>Profile Picture</label>
                                        <input type="file" name="profile_pic" />
                                        <?php echo get_profile_img($shopper_id); ?>
                                    </div>
                                <?php
                            } ?>
                                <?php if (check_is_active('school_event') == 1) {
                                ?>
                                    <div class="col span_6_of_12 matchheight">
                                        <label>School/Event <span>*</span></label>
                                        <input type="text" name="school_event" value="<?php echo get_post_meta($shopper_id, 'school_event', true); ?>" />
                                    </div>
                                <?php
                            } ?>
                                <?php if (check_is_active('graduation_year') == 1) {
                                ?>
                                    <div class="col span_6_of_12 matchheight">
                                        <label>Graduation Year <span>*</span></label>
                                        <select name="graduation_year">
                                            <?php for ($i=2015; $i<=2030; $i++) {
                                    ?>
                                            <option value="<?php echo $i; ?>" <?php if (get_post_meta($shopper_id, 'graduation_year', true) == $i) {
                                        echo 'selected="selected"';
                                    } ?>><?php echo $i; ?></option>
                                            <?php
                                } ?>
                                        </select>
                                    </div>
                                <?php
                            } ?>
                                <?php if (check_is_active('customer_email') == 1) {
                                ?>
                                <div class="col span_6_of_12 matchheight">
                                    <label>Email <span>*</span></label>
                                    <input type="text" name="customer_email" value="<?php echo get_post_meta($shopper_id, 'customer_email', true); ?>" />
                                </div>
                                <?php
                            } ?>
                                <?php if (check_is_active('customer_phone') == 1) {
                                ?>
                                <div class="col span_6_of_12 matchheight">
                                    <label>Phone</label>
                                    <input type="text" name="customer_phone" value="<?php echo get_post_meta($shopper_id, 'customer_phone', true); ?>" />
                                </div>
                                <?php
                            } ?>
                                <?php if (check_is_active('customer_address') == 1) {
                                ?>
                                <div class="col span_6_of_12 matchheight">
                                    <label>Address <span>*</span></label>
                                    <input type="text" name="customer_address" value="<?php echo get_post_meta($shopper_id, 'customer_address', true); ?>" />
                                </div>
                                <?php
                            } ?>
                                <?php if (check_is_active('customer_city') == 1) {
                                ?>
                                <div class="col span_6_of_12 matchheight">
                                    <label>City <span>*</span></label>
                                    <input type="text" name="customer_city" value="<?php echo get_post_meta($shopper_id, 'customer_city', true); ?>" />
                                </div>
                                <?php
                            } ?>
                                <?php if (check_is_active('customer_state') == 1) {
                                ?>
                                <div class="col span_6_of_12 matchheight">
                                    <label>State <span>*</span></label>
                                    <input type="text" name="customer_state" value="<?php echo get_post_meta($shopper_id, 'customer_state', true); ?>" />
                                </div>
                                <?php
                            } ?>
                                <?php if (check_is_active('customer_zip') == 1) {
                                ?>
                                <div class="col span_6_of_12 matchheight">
                                    <label>ZIP</label>
                                    <input type="text" name="customer_zip" value="<?php echo get_post_meta($shopper_id, 'customer_zip', true); ?>" />
                                </div>
                                <?php
                            } ?>
                            </div>
                            <?php if (check_is_active('customer_phone') == 1) {
                                ?>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                    <input type="checkbox" name="sms_agreement" value="yes" <?php if (get_post_meta($shopper_id, 'sms_agreement', true) == 'yes') {
                                    echo 'checked="checked"';
                                } ?> /> Yes, I want istilist texts!<br /><br />
                                    <p>By checking this box I agree to get up to 4 istilisttext messages per month sent via automated technology to this phone number, and by checking the box, I also agree to our Texting/Messaging Terms & Conditions. Agreeing to get istilisttext messages is not required to use istilist. Message & data rates may apply. Reply HELP for help and STOP to stop. You may receive additional program confirmation texts.</p>
                                </div>
                            </div>
                            <?php
                            } ?>
                            <div class="section group form_list">
                                <?php if (check_is_active('design_preferences') == 1) {
                                ?>
                                <div class="col span_6_of_12 matchheight">
                                    <label>Designer Preference</label>
                                    <select name="design_preferences">
                                        <option value="">Select Designer</option>
                                        <?php
                                            $size_args = array(
                                                'post_type' => 'designer_pref',
                                                'post_status' => 'publish',
                                                'posts_per_page' => -1,
                                                'meta_key' => 'store_id',
                                                'meta_value' => $store_id,
                                                'orderby' => 'title',
                                                'order' => 'ASC'
                                            );
                                $sizes = new WP_Query($size_args);
                                if ($sizes->have_posts()) {
                                    $i=0;
                                    while ($sizes->have_posts()) : $sizes->the_post(); ?>
                                        <?php $design_preferences = get_post_meta($shopper_id, 'design_preferences', true); ?>
                                        <!-- <input type="checkbox" name="design_preferences[]" value="<?php //the_title();?>" <?php //if($design_preferences[$i] == get_the_title(get_the_ID())){ echo 'checked="checked"'; }?> /> <?php //the_title();?> -->
                                        <option value="<?php the_title(); ?>" <?php if ($design_preferences == get_the_title(get_the_ID())) {
                                        echo 'selected="selected"';
                                    } ?>><?php the_title(); ?></option>
                                        <?php $i++; ?>
                                        <?php endwhile; ?>
                                        <?php
                                } ?>
                                    </select>
                                        <?php wp_reset_postdata(); ?>
                                </div>
                                <?php
                            } ?>
                                <?php if (check_is_active('style_preferences') == 1) {
                                ?>
                                <div class="col span_6_of_12 matchheight">
                                    <label>Style Preference</label>
                                    <select name="style_preferences">
                                        <option value="">Select Style</option>
                                    <?php
                                        $size_args = array(
                                            'post_type' => 'style_pref',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1,
                                            'meta_key' => 'store_id',
                                            'meta_value' => $store_id,
                                            'orderby' => 'title',
                                            'order' => 'ASC'
                                        );
                                $sizes = new WP_Query($size_args);
                                if ($sizes->have_posts()) {
                                    $i=0;
                                    while ($sizes->have_posts()) : $sizes->the_post(); ?>
                                    <?php $style_preferences = get_post_meta($shopper_id, 'style_preferences', true); ?>
                                    <?php //print_r($style_preferences);?>
                                    <!-- <input type="checkbox" name="style_preferences[]" value="<?php //the_title();?>" <?php //if($style_preferences[$i] == get_the_title(get_the_ID())){ echo 'checked="checked"'; }?> /> <?php //the_title();?><br /> -->
                                    <option value="<?php the_title(); ?>" <?php if (get_post_meta($shopper_id, 'style_preferences', true) == get_the_title(get_the_ID())) {
                                        echo 'selected="selected"';
                                    } ?>><?php the_title(); ?></option>
                                    <?php $i++; ?>
                                    <?php endwhile; ?>
                                    <?php
                                } ?>
                                    </select>
                                    <?php wp_reset_postdata(); ?>
                                </div>
                                <?php
                            } ?>
                                <?php if (check_is_active('color_preferences') == 1) {
                                ?>
                                <div class="col span_6_of_12 matchheight">
                                    <label>Color Preferences</label>
                                    <select name="color_preferences">
                                        <option value="">Select Color</option>
                                    <?php
                                        $color_args = array(
                                            'post_type' => 'color_pref',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1,
                                            'meta_key' => 'store_id',
                                            'meta_value' => $store_id,
                                            'orderby' => 'title',
                                            'order' => 'ASC'
                                        );
                                $colors = new WP_Query($color_args);
                                if ($colors->have_posts()) {
                                    //$i=0;
                                            while ($colors->have_posts()) : $colors->the_post(); ?>
                                    <?php $color_preferences = get_post_meta($shopper_id, 'color_preferences', true); ?>
                                    <!-- <input type="checkbox" name="color_preferences[]" value="<?php //the_title();?>" <?php //if($color_preferences[$i] == get_the_title(get_the_ID())){ echo 'checked="checked"'; }?> /> <?php //the_title();?><br /> -->
                                    <?php //$i++;?>
                                    <option value="<?php the_title(); ?>" <?php if (get_post_meta($shopper_id, 'color_preferences', true) == get_the_title(get_the_ID())) {
                                                echo 'selected="selected"';
                                            } ?>><?php the_title(); ?></option>
                                    <?php endwhile; ?>
                                    <?php
                                } ?>
                                    </select>
                                    <?php wp_reset_postdata(); ?>
                                </div>
                                <?php
                            } ?>
                                <?php if (check_is_active('customer_size') == 1) {
                                ?>
                                <div class="col span_6_of_12 matchheight">
                                    <label>Size</label>
                                    <select name="customer_size">
                                        <option value="">Select Size</option>
                                        <?php
                                            $size_args = array(
                                                'post_type' => 'size_pref',
                                                'post_status' => 'publish',
                                                'posts_per_page' => -1,
                                                'meta_key' => 'store_id',
                                                'meta_value' => $store_id
                                            );
                                $sizes = new WP_Query($size_args); ?>
                                        <?php if ($sizes->have_posts()) {
                                    ?>
                                        <?php while ($sizes->have_posts()) : $sizes->the_post(); ?>
                                        <option value="<?php the_title(); ?>" <?php if (get_post_meta($shopper_id, 'customer_size', true) == get_the_title(get_the_ID())) {
                                        echo 'selected="selected"';
                                    } ?>><?php the_title(); ?></option>
                                        <?php endwhile; ?>
                                        <?php
                                } ?>
                                    </select>
                                    <?php wp_reset_postdata(); ?>
                                </div>
                                <?php
                            } ?>
                            </div>
                            <div class="section group">
                            <?php

                                $table_name2 = $wpdb->prefix.'dynamic_form';
    $sql2 = "SELECT * FROM $table_name2 WHERE store_owner_id = $store_id AND is_custom = 1 ORDER BY id";
    $results2 = $wpdb->get_results($sql2);
    foreach ($results2 as $r2) {
        ?>
                           <?php if (check_is_active($r2->form_slug) == 1) {
            ?>
                            <div class="col span_6_of_12">
                                <label><?php echo $r2->form_display_name; ?> <?php if ($r2->is_required == 1) {
                ?><span>*</span><?php
            } ?></label>
                                <?php if ($r2->form_type == 'text') {
                ?>
                                    <input type="text" name="<?php echo $r2->form_slug; ?>" value="<?php echo get_post_meta($shopper_id, $r2->form_slug, true); ?>" />
                                <?php
            } ?>

                                <?php if ($r2->form_type == 'textarea') {
                ?>
                                    <textarea name="<?php echo $r2->form_slug; ?>"><?php echo get_post_meta($shopper_id, $r2->form_slug, true); ?></textarea>
                                <?php
            } ?>
                            </div>
                           <?php
        } ?>
                            <?php
    } ?>
                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                    <label>Shopper Feedback</label>
                                    <textarea name="shoppers_feedback"><?php $post = get_page($shopper_id);
    $content = apply_filters('the_content', $post->post_content);
    echo strip_tags($content); ?></textarea>
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                    <div style="text-align: right; float: right;">
                                        <input type="submit" name="update_shopper" value="Update" />
                                    </div>
                                    <div id="delete_shopper" class="" style="float: right; margin-right: 1%;">
                                       Delete Shopper?
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php get_footer(); ?>
	        </div>
	    </div>
	</div>
</div>
<script type="text/javascript">

	jQuery(document).ready( function () {
		jQuery('#delete_shopper').click(function () {
	          swal({
	            title: "Are you sure?",
	            text: "You will not be able to recover this shopper's information",
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
	                	url: "<?php echo get_bloginfo('url'); ?>/delete-shopper",
	                	type: "post",
	                        data: {"shopper_id": <?php echo $shopper_id; ?>},
	                	success: function(responce){
	                	   //alert(responce);
	                        swal({
	                            title: "Deleted",
	                            text: "This shopper has been deleted.",
	                            type: "success",
	                        }, function(){
	                            window.location="<?php bloginfo('url') ?>/dashboard/";
	                        });
	                	},
	                	error:function(responce){
	                	    console.log(responce);
	                	    alert("failure : "+responce);
	                	}
	                });
	            } else {
	                swal.close();
	            }
	        });
     	     });
	});

</script>
<?php
} else {
        header('Location: '.get_bloginfo('url').'/login');
    } ?>
