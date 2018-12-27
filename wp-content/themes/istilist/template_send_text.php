<?php /* Template Name: Send Text */ ?>
<?php
require("twilio-php-master/Twilio/autoload.php");
use Twilio\Rest\Client;

?>
<?php if (is_user_logged_in()) {
    ?>
<?php
    $textto = $_POST['textto'];
    $message_text = $_POST['message_text'];
    global $user_ID;
    global $wpdb;
    $store_id = get_user_meta($user_ID, 'store_id', true);
    $sid = 'ACdb92d82faf7befbb1538a208224133a4';
    $token = '1859b70bd4b570f6c8ff702b1ffd005d';
    $client = new Client($sid, $token);

    if (($textto == 'all-shoppers') && isset($_POST['send_text'])) {
        $shopper_args = array(
            'post_type' => 'shopper',
            'post_status' => 'publish',
            'author' => $store_id,
            'posts_per_page' => -1
        );
        $shopper_data = new WP_Query($shopper_args);
        if ($shopper_data->have_posts()) {
            while ($shopper_data->have_posts()) : $shopper_data->the_post();
            $shopper_id = get_the_ID();
            $shopper_name = get_post_meta($shopper_id, 'customer_fname', true) . ' ' . get_post_meta($shopper_id, 'customer_lname', true);
            $shopper_phone = get_post_meta($shopper_id, 'customer_phone', true);
            $purchase_array = get_post_meta($shopper_id, 'purchase_array', true);
            if (! empty($purchase_array)) {
                if ($purchase_array[0] == 'true') {
                    $purchase_status = 'YES';
                } else {
                    $purchase_status = 'NO';
                }
            }
            if ($purchase_status == 'YES' || $purchase_status == 'NO') {
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
        header('Location: '.get_bloginfo('url').'/folloup-messages/');
    }
    if (($textto == 'purchased') && isset($_POST['send_text'])) {
        $shopper_args = array(
            'post_type' => 'shopper',
            'post_status' => 'publish',
            'author' => $store_id,
            'posts_per_page' => -1
        );
        $shopper_data = new WP_Query($shopper_args);
        if ($shopper_data->have_posts()) {
            while ($shopper_data->have_posts()) : $shopper_data->the_post();
            $shopper_id = get_the_ID();
            $shopper_name = get_post_meta($shopper_id, 'customer_fname', true) . ' ' . get_post_meta($shopper_id, 'customer_lname', true);
            $shopper_phone = get_post_meta($shopper_id, 'customer_phone', true);
            $purchase_array = get_post_meta($shopper_id, 'purchase_array', true);
            if (! empty($purchase_array)) {
                if ($purchase_array[0] == 'true') {
                    $purchase_status = 'YES';
                } else {
                    $purchase_status = 'NO';
                }
            }
            if ($purchase_status == 'YES') {
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
        header('Location: '.get_bloginfo('url').'/folloup-messages/');
    }
    if (($textto == 'not-purchased') && isset($_POST['send_text'])) {
        $shopper_args = array(
            'post_type' => 'shopper',
            'post_status' => 'publish',
            'author' => $store_id,
            'posts_per_page' => -1
        );
        $shopper_data = new WP_Query($shopper_args);
        if ($shopper_data->have_posts()) {
            while ($shopper_data->have_posts()) : $shopper_data->the_post();
            $shopper_id = get_the_ID();
            $shopper_name = get_post_meta($shopper_id, 'customer_fname', true) . ' ' . get_post_meta($shopper_id, 'customer_lname', true);
            $shopper_phone = get_post_meta($shopper_id, 'customer_phone', true);
            $purchase_array = get_post_meta($shopper_id, 'purchase_array', true);
            if (! empty($purchase_array)) {
                if ($purchase_array[0] == 'true') {
                    $purchase_status = 'YES';
                } else {
                    $purchase_status = 'NO';
                }
            }
            if ($purchase_status == 'NO') {
                try {
                    $sms = $client->account->messages->create(
                            '+1' . $shopper_phone,
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
        header('Location: '.get_bloginfo('url').'/folloup-messages/');
    }
    if (($textto == 'stylist-employees') && isset($_POST['send_text'])) {
        $user_query = new WP_User_Query(
            array(
                'role__in' => array('storeemployee', 'storesupervisor'),
                'meta_key' => 'store_id',
                'meta_value' => $store_id,
                'orderby' => 'display_name',
                'order' => 'ASC'
            )
        );
        if (!empty($user_query->results)) {
            foreach ($user_query->results as $user) {
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
        header('Location: '.get_bloginfo('url').'/folloup-messages/');
    } ?>
<?php
} ?>