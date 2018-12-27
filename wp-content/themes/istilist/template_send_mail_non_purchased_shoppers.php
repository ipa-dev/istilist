<?php /* Template Name: Send Mail Non Purchased Shoppers */ ?>
<?php
global $user_ID; global $wpdb;
$store_owner_id = get_user_meta($user_ID, 'store_id', true);
$store_id = get_user_meta($user_ID, 'store_id', true);
$table_name = $wpdb->prefix.'folloup_messages';

if (isset($_POST['non_purchased_shopper_test_email'])) {
    $store_name = get_the_author_meta('display_name', $store_id);
    $from       = get_user_meta($store_id, 'email_to_shopper', true);

    $sql7 = "SELECT * FROM $table_name WHERE message_type = 'non-purchased-shoppers' and store_id = $store_id";
    $result7 = $wpdb->get_row($sql7);

    $headers      = 'From: ' . $store_name . '<' . $from . '>' . "\r\n";
    $headers      .= "Reply-To: " . strip_tags($from) . "\r\n";
    $headers      .= "MIME-Version: 1.0\r\n";
    $headers      .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $subject      = $result7->subject;
    $msg          = $result7->body;

    $email_address = get_user_meta($store_id, 'reporting', true);
    if (!empty($email_address)) {
        $email_address_array = explode(',', $email_address);
    }

    if (! empty($store_name)) {
        if (! empty($from)) {
            //wp_mail( $shopper_email, $subject, $msg, $headers );
            if (!empty($email_address_array)) {
                foreach ($email_address_array as $email_address) {
                    mail($email_address, $subject, $msg, $headers);
                }
            }
        }
    }
    header('Location: '.get_bloginfo('url').'/folloup-messages/');
}

if (isset($_POST['non_purchased_shopper_send'])) {
    $store_name = get_the_author_meta('display_name', $store_id);
    $from       = get_user_meta($store_id, 'email_to_shopper', true);

    $sql = "SELECT * FROM $table_name WHERE message_type = 'non-purchased-shoppers' and store_id = $store_id";
    $result = $wpdb->get_row($sql);

    $headers      = 'From: ' . $store_name . '<' . $from . '>' . "\r\n";
    $headers      .= "Reply-To: " . strip_tags($from) . "\r\n";
    $headers      .= "MIME-Version: 1.0\r\n";
    $headers      .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $subject      = $result->subject;

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
        $purchase_array = get_post_meta($shopper_id, 'purchase_array', true);
        if (! empty($purchase_array)) {
            if ($purchase_array[0] == 'true') {
                $purchase_status = 'YES';
            } else {
                $purchase_status = 'NO';
            }
        }
        if ($purchase_status == 'NO') {
            $shopper_name = get_post_meta($shopper_id, 'customer_fname', true) . ' ' . get_post_meta($shopper_id, 'customer_lname', true);
            $shopper_email_address = get_post_meta($shopper_id, 'customer_email', true);
            $msg = str_replace("{Shopper's Name}", $shopper_name, $result->body);
            mail($shopper_email_address, $subject, $msg, $headers);
        }
        endwhile;
    }
    header('Location: '.get_bloginfo('url').'/folloup-messages/');
}

if (isset($_POST['non_purchased_shopper_save'])) {
    $sql7 = "SELECT * FROM $table_name WHERE message_type = 'non-purchased-shoppers' and store_id = $store_id";
    $result7 = $wpdb->get_row($sql7);

    if (!empty($result7)) {
        echo $update_query = "UPDATE $table_name SET subject = '".$_POST['non_purchased_shopper_subject']."', body = '".nl2br($_POST['non_purchased_shopper_email_body'])."' WHERE message_type = 'non-purchased-shoppers' and store_id = $store_id";
        $update = $wpdb->query($update_query);
    } else {
        echo $insert_query = "INSERT INTO $table_name SET subject = '".$_POST['non_purchased_shopper_subject']."', body = '".nl2br($_POST['non_purchased_shopper_email_body'])."', message_type = 'non-purchased-shoppers', store_id = $store_id";
        $update = $wpdb->query($insert_query);
    }
    header('Location: '.get_bloginfo('url').'/folloup-messages/');
}
?>
