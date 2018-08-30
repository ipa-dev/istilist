<?php /* Template Name: Edit Employee */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    ?>
<?php global $user_ID; ?>
<?php $store_id = get_user_meta($user_ID, 'store_id', true); ?>
<?php $employee_id = decripted($_GET['eid']); ?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <h1><?php the_title(); ?></h1>
                    <div class="box">
                        <?php
                        if (isset($_POST['update_employee'])) {
                            global $wpdb;
                            wp_update_user(
                                    array(
                                        'ID' => $employee_id,
                                        'user_login'		=> $_POST['email_address'],
                                        'user_email'		=> $_POST['email_address'],
                                        'display_name'		=> $_POST['employee_name'],
                                        'role'              => $_POST['employeerole']
                                    )
                                );
                                
                            $wpdb->update($wpdb->users, array('user_status' => $_POST['user_status']), array('ID' => $employee_id));
                            //$wpdb->query('UPDATE wp_users SET user_status = 1 WHERE ID = '.$employee_id);
                            update_user_meta($employee_id, 'phone_number', $_POST['phone_number']);
                                
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
                                            
                                        $attach_id = wp_insert_attachment($attachment, $file[ 'file' ], $employee_id);
                                        $attach_data = wp_generate_attachment_metadata($attach_id, $file['file']);
                                        wp_update_attachment_metadata($attach_id, $attach_data);
                                        update_user_meta($employee_id, 'profile_pic', $attach_id, true);
                                    } else {
                                        wp_die('No image was uploaded.');
                                    }
                                }
                            }
                                
                            header("Location: ".get_bloginfo('home')."/stylist-employee");
                        } ?>
                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Stylist / Employee Name</label>
                                    <input type="text" name="employee_name" value="<?php echo get_the_author_meta('display_name', $employee_id); ?>" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Email Address</label>
                                    <input type="text" name="email_address" value="<?php echo get_the_author_meta('user_email', $employee_id); ?>" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone_number" value="<?php echo get_user_meta($employee_id, 'phone_number', true); ?>" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Profile Picture</label>
                                    <input type="file" name="profile_picture" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Role</label>
                                    <select name="employeerole">
                                        <option value="storeemployee" <?php if (get_user_role($employee_id) == 'storeemployee') {
                            echo 'selected="selected"';
                        } ?>>Employee</option>
                                        <option value="storesupervisor" <?php if (get_user_role($employee_id) == 'storesupervisor') {
                            echo 'selected="selected"';
                        } ?>>Supervisor</option>
                                    </select>
                                </div>
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
                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                    <div class="alignright"><input type="submit" name="update_employee" value="Update" /></div>
                                    <div id="delete" class="alignright" style="margin-right:1%;">Delete</div>
                                    <div id="changepass" class="alignright" style="margin-right:1%;text-decoration:none;color:white;">Change Password</div>
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
jQuery(document).ready(function () {
     jQuery('#delete').click(function () {
          swal({              
            title: "Are you sure?",
            text: "You will not be able to recover this employee",
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
                	url: "<?php echo get_bloginfo('url'); ?>/delete-employee",
                	type: "post",
                        data: {"store_user_id": <?php echo $store_id; ?>, "employee_id": <?php echo $employee_id; ?>},
                	success: function(responce){
                	   //alert(responce);
                        swal({
                            title: "Deleted",
                            text: "This employee has been deleted.",
                            type: "success",
                        }, function(){
                            window.location="http://istilist.com/stylist-employee/";
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
     jQuery('#changepass').click(function () {
         swal({
            title: "Enter new password",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            inputPlaceholder: "New Password"
            },
            function (pass) {
              if (pass === false) return false;
  
              if (pass === "") {
                 swal.showInputError("Must input a new password");
                 return false;
              }
              swal({
                title: "Confirm new password",
                type: "input",
                showCancelButton: false,
                closeOnConfirm: true,
                inputPlaceholder: "New Password"
                },
                function (confirmpass) {
                  if (confirmpass === false) return false;
  
                  if (confirmpass === "") {
                     swal.showInputError("Must input a new password");
                     return false;
                  }
                  if (pass === confirmpass) {
                     jQuery.ajax({
                        url: "<?php echo get_bloginfo('url'); ?>/change-password",
                        type: "post",
                        data: {"employee_id": <?php echo $employee_id; ?>, "pass": pass},
                        success: function (responce) {
                           swal({
                            title: "Password Changed",
                            text: "",
                            type: "success",
                           }, function(){
                            window.location="http://istilist.com/stylist-employee/";
                           });
                        },
                        error:function (responce) {
                          console.log(responce);
                          alert("failure: "+responce);
                        }
                     });
                  }
                  else {
                     swal.showInputError("Passwords do not match!");
                     return false;
                  } 
                });
         });
         
     });
});
</script>
<?php
} else {
                            header('Location: '.get_bloginfo('url').'/login');
                        } ?>