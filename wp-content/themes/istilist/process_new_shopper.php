<?php /* Template Name: Process New shopper */ ?>
<?php get_header(); ?>
<?php
if (is_user_logged_in()) {
    global $user_ID;
    $user_role = get_user_role($user_ID);
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
            $sid = getenv("TWILIO_SID");
            $token = getenv("TWILIO_AUTH_KEY");
            $client = new Client($sid, $token);
            $sms = $client->account->messages->create(

                '+1'.$_POST['customer_phone'],

                array(
                    'from' => getenv('TWILIO_DEFAULT_NUMBER'),

                    'body' => "Hey, ".$_POST['customer_fname'].", welcome to ".get_user_meta($user_ID, 'store_name', true)."! Text YES to receive alerts and special offers (Up to 6 autodialed msgs/mo. Consent not required to purchase. Msg&data rates may apply. Text STOP to stop)."
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


        /* Profile Picture - No Longer Used 
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
          End Profile Picture */

        if ($new_post_id) {
            echo '<p class="successMsg">Thank you for your valuable time and information.</p>';
            header("Location: ".get_bloginfo('home')."/dashboard");
        } else {
            echo '<p class="errorMsg">Sorry, your information is not updated.</p>';
        }
        header('Location: ' .get_bloginfo('url') . '/dashboard');
    }
}
else { header('Location: '. get_bloginfo('url'). '/login'); }
?>