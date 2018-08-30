<?php /* Template Name: Self Registration */ ?>
<?php //a lot of duplicate code from add new shopper?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    ?>
<?php global $user_ID;
    global $wpdb; ?>
<?php $store_id = get_user_meta($user_ID, 'store_id', true); ?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php //get_sidebar('menu');?>
          <div class="col span_2_of_12"></div>
          <?php
            if (isset($_POST['add_new_shopper'])) {
                global $wpdb;

                $post_arg = array(
                    'post_type' => 'shopper',
                    'post_title' => $_POST['customer_fname'].' '.$_POST['customer_lname'],
                    'post_content' => $_POST['shoppers_feedback'],
                    'post_author' => $user_ID,
                    'post_status' => 'publish',
                );

                $new_post_id = wp_insert_post($post_arg);

                add_post_meta($new_post_id, 'customer_fname', $_POST['customer_fname']);
                add_post_meta($new_post_id, 'customer_lname', $_POST['customer_lname']);
                add_post_meta($new_post_id, 'school_event', $_POST['school_event']);
                add_post_meta($new_post_id, 'graduation_year', $_POST['graduation_year']);
                add_post_meta($new_post_id, 'customer_email', $_POST['customer_email']);
                add_post_meta($new_post_id, 'customer_phone', $_POST['customer_phone']);
                add_post_meta($new_post_id, 'design_preferences', $_POST['design_preferences']);
                add_post_meta($new_post_id, 'style_preferences', $_POST['style_preferences']);
                add_post_meta($new_post_id, 'color_preferences', $_POST['color_preferences']);
                add_post_meta($new_post_id, 'customer_size', $_POST['customer_size']);
                add_post_meta($new_post_id, 'customer_address', $_POST['customer_address']);
                add_post_meta($new_post_id, 'customer_city', $_POST['customer_city']);
                add_post_meta($new_post_id, 'customer_state', $_POST['customer_state']);
                add_post_meta($new_post_id, 'customer_zip', $_POST['customer_zip']);
                add_post_meta($new_post_id, 'sms_agreement', $_POST['sms_agreement']);
                add_post_meta($new_post_id, 'entry_date', date('Y-m-d H:i:s'));
                add_post_meta($new_post_id, 'store_id', get_user_meta($user_ID, 'store_id', true));
                add_post_meta($new_post_id, 'hit_plus', 'false');

                if ($_POST['sms_agreement'] == 'yes' && isset($_POST['customer_phone'])) {
                    $sid = 'ACdb92d82faf7befbb1538a208224133a4';
                    $token = '1859b70bd4b570f6c8ff702b1ffd005d';
                    $client = new Client($sid, $token);
                    $sms = $client->account->messages->create(

                        // the number we are sending to - Any phone number
                        '+1'.$_POST['customer_phone'],

                        array(
                            // Step 6: Change the 'From' number below to be a valid Twilio number
                            // that you've purchased
                            'from' => get_option('twilio_number'),

                            // the sms body
                            'body' => "Hey, ".$_POST['customer_fname'].", welcome to ".get_user_meta($user_ID, 'store_name', true).".Text YES to get messages from us."
                        )
                    );
                }

                $store_id = get_user_meta($user_ID, 'store_id', true);
                $table_name3 = $wpdb->prefix.'dynamic_form';
                $sql3 = "SELECT * FROM $table_name3 WHERE store_owner_id = $store_id AND is_custom = 1 ORDER BY id";
                $results3 = $wpdb->get_results($sql3);
                if (!empty($results3)) {
                    foreach ($results3 as $r3) {
                        $var1 = $r3->form_slug;
                        $var2 = $_POST[$r3->form_slug];
                        add_post_meta($new_post_id, $var1, $var2);
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

                        $attach_id = wp_insert_attachment($attachment, $file[ 'file' ], $new_post_id);
                        $attach_data = wp_generate_attachment_metadata($attach_id, $file['file']);
                        wp_update_attachment_metadata($attach_id, $attach_data);
                        add_post_meta($new_post_id, 'profile_pic', $attach_id);
                    } else {
                        wp_die('No image was uploaded.');
                    }
                }

                if (!$new_post_id) {
                    echo '<p class="errorMsg">Sorry, your information is not updated.</p>';
                } ?>
            <div class="col span_8_of_12">
              <p class="successMsg">Thank you for your valuable time and information!</p>
              <a style="justify-content:center" href="<?php bloginfo('url'); ?>/self-registration">New user? Register!</a>
            </div>
          <?php
            } else {
                ?>
	        <div class="col span_8_of_12 matchheight">
                <div class="dash_content">
                  <form id="forms" method="post" action="" enctype="multipart/form-data">
                      <div class="section group form_list">
                          <?php if (check_is_active('customer_fname') == 1) {
                    ?>
                              <div class="col span_6_of_12 matchheight">
                                  <label>First Name <span>*</span></label>
                                  <input type="text" name="customer_fname" />
                              </div>
                          <?php
                } ?>
                          <?php if (check_is_active('customer_lname') == 1) {
                    ?>
                              <div class="col span_6_of_12 matchheight">
                                  <label>Last Name <span>*</span></label>
                                  <input type="text" name="customer_lname" />
                              </div>
                          <?php
                } ?>
                          <?php if (check_is_active('profile_pic') == 1) {
                    ?>
                              <div class="col span_6_of_12 matchheight">
                                  <label>Profile Picture</label>
                                  <input type="file" name="profile_pic" />
                              </div>
                          <?php
                } ?>
                          <?php if (check_is_active('school_event') == 1) {
                    ?>
                              <div class="col span_6_of_12 matchheight">
                                  <label>School/Event <span>*</span></label>
                                  <input type="text" name="school_event" />
                              </div>
                          <?php
                } ?>
                          <?php if (check_is_active('graduation_year') == 1) {
                    ?>
                              <div class="col span_6_of_12 matchheight">
                                  <label>Graduation Year <span>*</span></label>
                                  <select name="graduation_year">
                                      <option value="graduate">Graduated</option>
                                      <?php for ($i=2017; $i<=2030; $i++) {
                        ?>
                                      <option value="<?php echo $i; ?>" <?php if ($i == 2017) {
                            echo "selected='selected'";
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
                              <label>Email</label>
                              <input type="text" name="customer_email" />
                          </div>
                          <?php
                } ?>
                          <?php if (check_is_active('customer_phone') == 1) {
                    ?>
                          <div class="col span_6_of_12 matchheight">
                              <label>Phone</label>
                              <input type="tel" name="customer_phone">
                          </div>
                          <?php
                } ?>
                          <?php if (check_is_active('customer_address') == 1) {
                    ?>
                          <div class="col span_6_of_12 matchheight">
                              <label>Address</label>
                              <input type="text" name="customer_address" />
                          </div>
                          <?php
                } ?>
                          <?php if (check_is_active('customer_city') == 1) {
                    ?>
                          <div class="col span_6_of_12 matchheight">
                              <label>City</label>
                              <input type="text" name="customer_city" />
                          </div>
                          <?php
                } ?>
                          <?php if (check_is_active('customer_state') == 1) {
                    ?>
                          <div class="col span_6_of_12 matchheight">
                              <label>State</label>
                              <input type="text" name="customer_state" />
                          </div>
                          <?php
                } ?>
                          <?php if (check_is_active('customer_zip') == 1) {
                    ?>
                          <div class="col span_6_of_12 matchheight">
                              <label>ZIP</label>
                              <input type="text" name="customer_zip" />
                          </div>
                          <?php
                } ?>
                      </div>
                      <?php if (check_is_active('customer_phone') == 1) {
                    ?>
                      <div class="section group">
                          <div class="col span_12_of_12">
                              <input type="checkbox" name="sms_agreement" value="yes" /> Yes, I want istilist texts!<br /><br />
                              <p>Up to 6 autodialed msgs/mo.  Consent not required to purchase. Msg&data rates may apply. Text STOP to stop, HELP for help. Terms:<a href="internationalprom.com/privacy-policy">internationalprom.com</a></p>
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
                        while ($sizes->have_posts()) : $sizes->the_post(); ?>
                                  <!-- <input type="checkbox" name="design_preferences[]" value="<?php the_title(); ?>" /> <?php the_title(); ?>&nbsp;&nbsp; -->
                                  <option value="<?php the_title(); ?>"><?php the_title(); ?></option>
                                  <?php endwhile; ?>
                                  <?php
                    } ?>
                              </select>
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
                        while ($sizes->have_posts()) : $sizes->the_post(); ?>
                              <!-- <input type="checkbox" name="style_preferences[]" value="<?php the_title(); ?>" /> <?php the_title(); ?>&nbsp;&nbsp; -->
                                  <option value="<?php the_title(); ?>"><?php the_title(); ?></option>
                              <?php endwhile; ?>
                                  <?php
                    } ?>
                              </select>
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
                        while ($colors->have_posts()) : $colors->the_post(); ?>
                              <!-- <input type="checkbox" name="color_preferences[]" value="<?php the_title(); ?>" /> <?php the_title(); ?>&nbsp;&nbsp; -->
                              <option value="<?php the_title(); ?>"><?php the_title(); ?></option>
                              <?php endwhile; ?>
                                  <?php
                    } ?>
                              </select>
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
                                  <option value="<?php the_title(); ?>"><?php the_title(); ?></option>
                                  <?php endwhile; ?>
                                  <?php
                    } ?>
                              </select>
                          </div>
                          <?php
                } ?>
                      </div>
                      <div class="section group">
                      <?php

                          $table_name2 = $wpdb->prefix.'dynamic_form';
                $sql2 = "SELECT * FROM $table_name2 WHERE store_owner_id = $store_owner_id AND is_custom = 1 ORDER BY id";
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
                              <input type="text" name="<?php echo $r2->form_slug; ?>" />
                          <?php
                        } ?>

                          <?php if ($r2->form_type == 'textarea') {
                            ?>
                              <textarea name="<?php echo $r2->form_slug; ?>"></textarea>
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
                              <textarea name="shoppers_feedback"></textarea>
                          </div>
                      </div>
                      <div class="section group">
                          <div class="col span_12_of_12">
                              <div style="text-align: right;">
                                  <input type="submit" name="add_new_shopper" value="Register!" />
                              </div>
                          </div>
                      </div>
                  </form>
                </div>
          </div>
          <?php
            } ?>
          <div class="col span_2_of_12"></div>
      </div>
      <?php //get_footer();?>
  </div>
</div>
<script>
jQuery(document).ready(function(){
    jQuery('#forms').validate({
        rules: {
            customer_fname: {
                required: true
            },
            customer_lname: {
                required: true
            },
            school_event: {
                required: true
            },
            graduation_year: {
                required: true
            },
            <?php
            $store_owner_id1 = get_user_meta($user_ID, 'store_id', true);
    $table_name21 = $wpdb->prefix.'dynamic_form';
    $sql21 = "SELECT * FROM $table_name21 WHERE store_owner_id = $store_owner_id1 AND is_custom = 1 ORDER BY id";
    $results21 = $wpdb->get_results($sql21);
    foreach ($results21 as $r21) {
        echo $r21->form_slug.": {
                required: true
            },\n";
    } ?>
        },
        messages: {

        }
    })
});
</script>
<?php
} else {
        header('Location: '.get_bloginfo('url').'/login');
    } ?>
