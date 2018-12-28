<?php /* Template Name: Store Profile */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
      global $user_ID; 
?>
<?php $user_reverse_order = get_user_meta($user_ID, 'reverse_order', true); ?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <h1><?php the_title(); ?></h1>
                    <div class="box">
                        <form id="forms" method="post" action="<?php bloginfo( 'url' ); ?>/process-update-store-profile" enctype="multipart/form-data">
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
                                <div class="col span_6_of_12">
                                    <label>Email ID to Shoppers</label>
                                    <input type="text" name="email_to_shopper" value="<?php echo get_user_meta($user_ID, 'email_to_shopper', true); ?>" />
                                </div>
                                
                                <!-- Reverse Ordering -->
                                <?php require_once 'php_modules/template-store-profile/reverse-order.php'; ?>
                            </div>
                            
                            <!-- Profile Picture 
                            <div class="section group">
                                <?php //require_once 'php_modules/template-store-profile/profile-picture.php'; ?>
                            </div>
                            -->

                            <div class="section group">
                                <!-- Timed Promo Text -->
                                <?php require_once 'php_modules/template-store-profile/timed-promo-text.php'; ?>
                                
                                <!-- Text Credits -->
                                <div class="col span_5_of_12">
                                    <label>Text Credit</label>
                                    <input type="text" disabled name="text_credit" value="<?php echo get_user_meta($user_ID, 'text_credit', true); ?>" />
                                    <div class="divnote">Current number of subscribers:
                                        <?php 
                                            $data = new WP_Query(array(
                                                'post_type' => 'shopper',
                                                'post_status' => 'publish',
                                                'author' => get_user_meta($user_ID, 'store_id', true),
                                                'posts_per_page' => -1,
                                                // 'meta_key' => 'sms_agreement',
                                                // 'meta_value' => 'yes',
                                            ));
                                            echo $data->found_posts;
                                        ?>
                                    </div>
                                </div>
                                <div class="col span_1_of_12">
                                    <?php echo '<a class="submit" href="' . get_bloginfo( 'url' ) . '/purchase-texts">Buy</a>'; ?>
                                </div>
                            </div>

                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <div style="text-align: right;">
                                        <input type="submit" name="update_store_profile" value="Update" />
                                    </div>
                                </div>
                            </div>
                            <!-- User Timezone HIDDEN -->
                            <?php require_once 'php_modules/template-store-profile/select-timezone.php'; ?>
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