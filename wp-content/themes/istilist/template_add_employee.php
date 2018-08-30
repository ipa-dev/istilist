<?php /* Template Name: Add Employee */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    ?>
<?php global $user_ID; ?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <h1><?php the_title(); ?></h1>
                    <div class="box">
                        <?php
                        if (isset($_POST['add_employee'])) {
                            global $wpdb;
                            if (email_exists($_POST['email_address'])) {
                                $errorCode = 1;
                            } else {
                                $new_user_id = wp_insert_user(
                                    array(
                                        'user_login'		=> $_POST['email_address'],
                                        //'user_pass'			=> 'employee123',
                                        'user_pass'             => $_POST['password'],
                                        'user_email'		=> $_POST['email_address'],
                                        'display_name'		=> $_POST['employee_name'],
                                        'role'              => $_POST['employeerole'],
                                        'user_registered'	=> date('Y-m-d H:i:s')
                                    )
                                );
                                
                                $user_status = $_POST['user_status'];
                                
                                $wpdb->update($wpdb->prefix.'users', array('user_status' => $user_status), array('ID' => $new_user_id));
                                add_user_meta($new_user_id, 'phone_number', $_POST['phone_number']);
                                $store_id = get_user_meta($user_ID, 'store_id', true);
                                $store_name = get_user_meta($user_ID, 'store_name', true);
                                add_user_meta($new_user_id, 'store_id', $store_id);
                                add_user_meta($new_user_id, 'store_name', $store_name);
                                
                                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                                require_once(ABSPATH . "wp-admin" . '/includes/file.php');
                                require_once(ABSPATH . "wp-admin" . '/includes/media.php');
                                
                                foreach ($_FILES as $image) {
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
                                            
                                            $attach_id = wp_insert_attachment($attachment, $file[ 'file' ], $new_user_id);
                                            $attach_data = wp_generate_attachment_metadata($attach_id, $file['file']);
                                            wp_update_attachment_metadata($attach_id, $attach_data);
                                            add_user_meta($new_user_id, 'profile_pic', $attach_id, true);
                                        } else {
                                            wp_die('No image was uploaded.');
                                        }
                                    }
                                }
                                
                                $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $_POST['email_address']));
                                if (empty($key)) {
                                    $key = wp_generate_password(20, false);
                                    $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $_POST['email_address']));
                                }
                                                                                        
                                
                                // mail to user
                                /*$to1 = $_POST['email_address'];
                            	$from1 = "no-reply@".$_SERVER['HTTP_HOST'];
                            	$headers1 = 'From: '.$from1. "\r\n";
                                $headers1 .= "Reply-To: ".get_option('admin_email')."\r\n";
                                $headers1 .= "MIME-Version: 1.0\n";
                                $headers1 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                            	$subject1 = "Update account password";
                                $msg1 = 'Welcome to '.$store_name.'! Please click on the link to update the password process<br><br>Update Password Link :<a href="'.get_site_url().'/add-member?key='.$key.'" target="_blank">'.get_site_url().'/add-member?key='.$key.'</a><br><br>Regards,<br>'.$store_name;
                            	wp_mail( $to1, $subject1, $msg1, $headers1 );


                                header("Location: ".get_bloginfo('home')."/stylist-employee");*/
                                //header("Location: ".get_bloginfo('home').'/add-member?key='.$key);
                            }
                        } ?>
                        <form id="forms" method="post" action="" enctype="multipart/form-data">
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Stylist / Employee Name <span>*</span></label>
                                    <input type="text" name="employee_name" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Email Address <span>*</span></label>
                                    <input type="text" name="email_address" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                     <label>Password <span>*</span></label>
                                     <input type="password" name="password" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone_number" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Profile Picture</label>
                                    <input type="file" name="profile_picture" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Role</label>
                                    <select name="employeerole">
                                        <option value="storeemployee">Employee</option>
                                        <option value="storesupervisor">Supervisor</option>
                                    </select>
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Status</label>
                                    <?php $user_status = get_the_author_meta('user_status', $employee_id); ?>
                                    <select name="user_status">
                                        <option value="2" <?php if ($user_status == 2) {
                            echo 'selected="selected"';
                        } ?>>Active</option>
                                        <option value="0" <?php if ($user_status == 0) {
                            echo 'selected="selected"';
                        } ?>>Inactive</option>
                                    </select>
                                </div>
                                <div class="col span_6_of_12"></div>
                            </div>
                            
                            <div class="section group">
                                <div class="col span_12_of_12">
                                    <div class="alignright"><input type="submit" name="add_employee" value="Add Employee" /></div>
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
            employee_name: {
                required: true
            }
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