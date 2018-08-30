<?php 

if ($_POST['bulk_select'] == 'purchased') {
    foreach ($_POST as $key=>$value) {
        if ($value == "yes") {
            update_post_meta($key, 'complete_purchase', 1);
            update_post_meta($key, 'dollar_button_clicked', 1);

            $shopper_email = get_post_meta($key, 'customer_email', true);

            $table_name1 = $wpdb->prefix.'folloup_messages';
            $sql2 = "SELECT * FROM $table_name1 WHERE message_type = 'thankyou' and store_id = $store_id";
            $result2 = $wpdb->get_row($sql2);

            $shopper_name1 = get_post_meta($key, 'customer_fname', true).' '.get_post_meta($key, 'customer_lname', true);
            $msg_body1 = str_replace("{Shopper's Name}", $shopper_name1, $result2->body);

            $styist_id = get_post_meta($key, 'stylist_id', true);

            $stylist_name = get_the_author_meta('display_name', $styist_id);
            $msg_body2 = str_replace("{Stylist's Name}", $stylist_name, $msg_body1);


            $store_name = get_the_author_meta('display_name', $store_id);
            $from = get_user_meta($store_id, 'email_to_shopper', true);

            $shopper_name  = $shopper_name1;
            $headers = 'From: '.$store_name.'<'.$from.'>'."\r\n";
            $headers .= "Reply-To: ". strip_tags($from) . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $subject = $result2->subject;
            $msg = $msg_body2;


            if (!empty($store_name)) {
                if (!empty($from)) {
                    wp_mail($shopper_email, $subject, $msg, $headers);
                }
            }
        }
    }
}
if ($_POST['bulk_select'] == 'not-purchased') {
    foreach ($_POST as $key=>$value) {
        if ($value == "yes") {
            $reason = ".";
            $table_name1 = $wpdb->prefix.'folloup_messages';

            update_post_meta($key, 'reason_not_purchased', $reason);
            update_post_meta($key, 'dollar_button_clicked', 1);

            $shopper_email = get_post_meta($key, 'customer_email', true);

            $sql2 = "SELECT * FROM $table_name1 WHERE message_type = 'promo' and store_id = $store_id";
            $result2 = $wpdb->get_row($sql2);

            $shopper_name1 = get_post_meta($key, 'customer_fname', true).' '.get_post_meta($key, 'customer_lname', true);
            $msg_body1 = str_replace("{Shopper's Name}", $shopper_name1, $result2->body);

            $styist_id = get_post_meta($key, 'stylist_id', true);

            $stylist_name = get_the_author_meta('display_name', $styist_id);
            $msg_body2 = str_replace("{Stylist's Name}", $stylist_name, $msg_body1);

            if ($options['smtp-active'] == 1) {
                $from = get_user_meta($user_ID, 'email_to_shopper', true); //get_the_author_meta('user_email', $store_id);
            } else {
                $from = get_user_meta($user_ID, 'email_to_shopper', true); //get_the_author_meta('user_email', $store_id);
            }

            $store_name = get_the_author_meta('display_name', $store_id);
            $from = get_user_meta($store_id, 'email_to_shopper', true);

            $shopper_name  = $shopper_name1;
            $headers = 'From: '.$store_name.'<'.$from.'>'."\r\n";
            $headers .= "Reply-To: ". strip_tags($from) . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $subject = $result2->subject;
            $msg = $msg_body2;

            if (!empty($store_name)) {
                if (!empty($from)) {
                    //wp_mail( $shopper_email, $subject, $msg, $headers );
                    mail($shopper_email, $subject, $msg, $headers);
                }
            }
        }
    }
}
