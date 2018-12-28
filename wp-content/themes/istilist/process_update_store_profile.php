<?php /* Template Name: Process Update Store Profile */ ?>
<?php 
    get_header();
    if (is_user_logged_in()) {
        global $user_ID; 
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
        }
    } 
?> 