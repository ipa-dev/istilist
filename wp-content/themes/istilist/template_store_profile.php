<?php /* Template Name: Store Profile */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    ?>
<?php global $user_ID; ?>
<?php $user_reverse_order = get_user_meta($user_ID, 'reverse_order', true); ?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <h1><?php the_title(); ?></h1>
                    <div class="box">
                        <?php
                            if (isset($_POST['update_store_profile'])) {
                                wp_update_user(array( 'ID' => $user_ID, 'display_name' => $_POST['store_name'] ));
                                update_user_meta($user_ID, 'contact_name', $_POST['contact_name']);
                                update_user_meta($user_ID, 'address', $_POST['address']);
                                update_user_meta($user_ID, 'phone_number', $_POST['phone_number']);
                                update_user_meta($user_ID, 'mobile_number', $_POST['mobile_number']);
                                update_user_meta($user_ID, 'website', $_POST['website']);
                                update_user_meta($user_ID, 'security_questions', $_POST['security_questions']);
                                update_user_meta($user_ID, 'security_answer', $_POST['security_answer']);
                                update_user_meta($user_ID, 'city', $_POST['city']);
                                update_user_meta($user_ID, 'state', $_POST['state']);
                                update_user_meta($user_ID, 'zipcode', $_POST['zipcode']);
                                update_user_meta($user_ID, 'reporting', $_POST['email_address']);
                                update_user_meta($user_ID, 'selecttimezone', $_POST['selecttimezone']);
                                var_dump($_POST['selecttimezone']);
                                exit();
                                update_user_meta($user_ID, 'profile_pic_on_off', $_POST['profile_pic_on_off']);
                                update_user_meta($user_ID, 'email_to_shopper', $_POST['email_to_shopper']);
                                
                                if (!empty($user_reverse_order) || $user_reverse_order == null) {
                                    update_user_meta($user_ID, 'reverse_order', $_POST['reverse_order']);
                                } else {
                                    add_user_meta($user_ID, 'reverse_order', $_POST['reverse_order']);
                                }
                                $user_daily_text_promo = get_user_meta($user_ID, 'daily_promo_text', true);
                                if (!empty($user_daily_text_promo)) {
                                    update_user_meta($user_ID, 'daily_promo_text', $_POST['daily_promo_text']);
                                } else {
                                    add_user_meta($user_ID, 'daily_promo_text', $_POST['daily_promo_text']);
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
                                        
                                        $attach_id = wp_insert_attachment($attachment, $file[ 'file' ], $user_ID);
                                        $attach_data = wp_generate_attachment_metadata($attach_id, $file['file']);
                                        wp_update_attachment_metadata($attach_id, $attach_data);
                                        if (!update_user_meta($user_ID, 'profile_pic', $attach_id)) {
                                            add_user_meta($user_ID, 'profile_pic', $attach_id);
                                        }
                                    } else {
                                        wp_die('No image was uploaded.');
                                    }
                                }
                                
                                if (!empty($_POST['pwd'])) {
                                    wp_set_password($_POST['pwd'], $user_ID);
                                }
                                echo '<p class="successMsg">Your store profile updated.</p>';
                                header('Location: '.get_header('url').'/store-profile/');
                            } ?>
                        <form id="forms" method="post" action="" enctype="multipart/form-data">
                            <div class="section group">
                                <div class="col span_4_of_12">
                                    <label>Store</label>
                                    <input type="text" name="store_name" value="<?php echo get_the_author_meta('display_name', $user_ID); ?>" />
                                </div>
                                <div class="col span_4_of_12">
                                    <label>Contact Name</label>
                                    <input type="text" name="contact_name" value="<?php echo get_user_meta($user_ID, 'contact_name', true); ?>" />
                                </div>
                                <div class="col span_4_of_12">
                                    <label>Business Phone</label>
                                    <input type="text" name="phone_number" value="<?php echo get_user_meta($user_ID, 'phone_number', true); ?>" />
                                </div>
                                
                            </div>
                            <div class="section group">
                                <div class="col span_4_of_12">
                                    <label>Mobile Phone</label>
                                    <input type="text" name="mobile_number" value="<?php echo get_user_meta($user_ID, 'mobile_number', true); ?>" />
                                    <input type="checkbox" name="mobile_number_optin" value="<?php echo get_user_meta($user_ID, 'mobile_number_optin', true); ?>" /> Yes, I want istilist texts!
                                </div>
                                <div class="col span_4_of_12">
                                    <label>Address</label>
                                    <input type="text" name="address" value="<?php echo get_user_meta($user_ID, 'address', true); ?>" />
                                </div>
                                <div class="col span_4_of_12">
                                    <label>Email</label>
                                    <input type="text" name="email_address" value="<?php echo get_the_author_meta('reporting', $user_ID); ?>" />
                                    <div class="divnote">Separate multiple email addresses with a comma.</div>
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_4_of_12">
                                    <label>City</label>
                                    <input type="text" name="city" value="<?php echo get_user_meta($user_ID, 'city', true); ?>" />
                                </div>
                                <div class="col span_4_of_12">
                                    <label>State</label>
                                    <input type="text" name="state" value="<?php echo get_user_meta($user_ID, 'state', true); ?>" />
                                </div>
                                <div class="col span_4_of_12">
                                    <label>Zip Code</label>
                                    <input type="text" name="zipcode" value="<?php echo get_user_meta($user_ID, 'zipcode', true); ?>" />
                                </div>
                            </div>
                            <div class="section group">
                                
                            </div>
                            <div class="section group">
                                <div class="col span_4_of_12">
                                    <label>Website</label>
                                    <input type="text" name="website" value="<?php echo get_user_meta($user_ID, 'website', true); ?>" />
                                </div>
                                
                                <!-- Security Questions -->
                                <?php require_once 'php_modules/template-store-profile/security-question.php'; ?>
                                
                                <!-- Security Question Answer -->
                                <?php require_once 'php_modules/template-store-profile/security-question-answer.php'; ?>

                            </div>
                            <div class="section group">
                                <!--<div class="col span_6_of_12">
                                    <label>Password</label>
                                    <input type="password" name="pwd" />
                                </div>-->

                            </div>
                            <div class="section group">


                                <!-- User Timezone -->
                                <?php require_once 'php_modules/template-store-profile/select-timezone.php'; ?>

                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Email ID to Shoppers</label>
                                    <input type="text" name="email_to_shopper" value="<?php echo get_user_meta($user_ID, 'email_to_shopper', true); ?>" />
                                </div>
                                
                            </div>
                            
                            <!-- Profile Picture 
                            <div class="section group">
                                <?php //require_once 'php_modules/template-store-profile/profile-picture.php'; ?>
                            </div>
                            -->
                            <!-- Reverse Ordering -->
                            <div class="section group">
                            	<?php require_once 'php_modules/template-store-profile/reverse-order.php'; ?>
                            </div>

                            <!-- Timed Promo Text -->
                            <div class="section group">
                                <?php require_once 'php_modules/template-store-profile/timed-promo-text.php'; ?>
                            </div>

                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <div style="text-align: left;">
                                        <?php echo '<a class="custom_button" href="' . get_bloginfo( 'url' ) . '/purchase-texts">Purchase Texts</a>'; ?>
                                    </div>
                                </div>
                                <div class="col span_6_of_12">
                                    <div style="text-align: right;">
                                        <input type="submit" name="update_store_profile" value="Update" />
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
<script>
jQuery(document).ready(function(){
    jQuery('#forms').validate({
        rules: {
            store_name: {
                required: true
            },
            contact_name: {
                required: true
            },
            phone_number: {
                required: true
            },
            email_address: {
                required: true,
                multiemail: true
            },
            security_answer: {
                required: true
            }
        },
        messages: {
            
        }
    })
    var text_max = 160;
    jQuery('#textarea_feedback').html(text_max + ' characters remaining');

    jQuery('#daily_promo_text').keyup(function() {
        var text_length = jQuery('#daily_promo_text').val().length;
        var text_remaining = text_max - text_length;

        jQuery('#textarea_feedback').html(text_remaining + ' characters remaining');
    });
});
</script>
<?php
} else {
        header('Location: '.get_bloginfo('url').'/login');
    } ?>