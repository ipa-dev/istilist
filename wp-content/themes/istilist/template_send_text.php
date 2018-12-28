<?php /* Template Name: Send Text */ ?>
<?php
require("twilio-php-master/Twilio/autoload.php");
use Twilio\Rest\Client;
if (is_user_logged_in() && isset($_POST['send_text'])) {
    $textto = $_POST['textto'];
    $message_text = $_POST['message_text'];
    global $user_ID;
    global $wpdb;
    $store_id = get_user_meta($user_ID, 'store_id', true);
    $text_credit = get_user_meta($user_ID, 'text_credit', true);
    $sid = 'ACdb92d82faf7befbb1538a208224133a4';
    $token = '1859b70bd4b570f6c8ff702b1ffd005d';
    $client = new Client($sid, $token);

    if ($textto == 'all-shoppers' || $textto == 'purchased' || $textto == 'not-purchased') {
        $data = new WP_Query(array(
            'post_type' => 'shopper',
            'post_status' => 'publish',
            'author' => $store_id,
            'posts_per_page' => -1,
            
        ));
        $shopper = true;
    }
    else if ($textto == 'stylist-employees') {
        $data = new WP_User_Query(array(
            'role__in' => array('storeemployee', 'storesupervisor'),
            'meta_key' => 'store_id',
            'meta_value' => $store_id,
            'orderby' => 'display_name',
            'order' => 'ASC'
        ));
        $shopper = false;
    }
    else {
        echo "Invalid Text To Field\n";
        exit(1);
    }
    
    if ($shopper) {
        if ($data->have_posts()) {
            
            while ($data->have_posts()) : $data->the_post();
                $shopper_id = get_the_ID();
                $shopper_name = get_post_meta($shopper_id, 'customer_fname', true) . ' ' . get_post_meta($shopper_id, 'customer_lname', true);
                $shopper_phone = get_post_meta($shopper_id, 'customer_phone', true);
                $shopper_smsagreement = get_post_meta($shopper_id, 'sms_agreement', true);
                $shopper_condition = ($shopper_smsagreement == 'yes');

                $purchase_array = get_post_meta($shopper_id, 'purchase_array', true);
                if (! empty($purchase_array)) {
                    if ($purchase_array[0] == 'true') {
                        $purchase_status = 'YES';
                    } else {
                        $purchase_status = 'NO';
                    }
                }

                if ($textto == 'purchased') {
                    $shopper_condition = $shopper_condition && $purchase_status == 'YES';
                } 
                else if ($textto == 'not-purchased') {
                    $shopper_condition = $shopper_condition && $purchase_status == 'NO';
                }

                if ($shopper_condition) {
                    try {
                        $sms = $client->account->messages->create(
                                '+1'.$shopper_phone,
                                array(
                                    'from' => get_option('twilio_number'),
                                    'body' => $message_text
                                )
                            );
                    } catch (Exception $e) {
                        //echo 'Message: ' .$e->getMessage();
                    }
                }
            endwhile;
        }
    }
    else {
        if (!empty($data->results)) {
            foreach ($data->results as $user) {
                $user_status = get_the_author_meta('user_status', $user->ID);
                $phone_number = get_user_meta($user->ID, 'phone_number', true);
                if ($user_status == 2) {
                    $stylist_name = $user->display_name;
                    try {
                        $sms = $client->account->messages->create(
                            '+1'.$phone_number,
                            array(
                                'from' => get_option('twilio_number'),
                                'body' => $message_text
                            )
                        );
                    } catch (Exception $e) {
                        //echo 'Message: ' .$e->getMessage();
                    }
                }
            }
        }
    } 
    header('Location: '.get_bloginfo('url').'/folloup-messages/');
} ?>