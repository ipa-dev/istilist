<?php /* Template Name: Process Bulk Actions */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    global $user_ID;
    global $wpdb; 
    $store_id = get_user_meta($user_ID, 'store_id', true);
    if (isset($_POST['bulk_select'])) {
        if ($_POST['bulk_select'] == 'all-shoppers') {
            global $user_ID;
            global $wpdb;
            $store_owner_id = get_user_meta($user_ID, 'store_id', true);
            $store_id = get_user_meta($user_ID, 'store_id', true);
            $table_name = $wpdb->prefix.'folloup_messages';

            $store_name = get_the_author_meta('display_name', $store_id);
            $from       = get_user_meta($store_id, 'email_to_shopper', true);

            $sql = "SELECT * FROM $table_name WHERE message_type = 'purchased-shoppers' and store_id = $store_id";
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
                if (($purchase_status == 'YES') || ($purchase_status = 'NO')) {
                    $shopper_name = get_post_meta($shopper_id, 'customer_fname', true) . ' ' . get_post_meta($shopper_id, 'customer_lname', true);
                    $shopper_email_address = get_post_meta($shopper_id, 'customer_email', true);
                    $msg = str_replace("{Shopper's Name}", $shopper_name, $result->body);
                    mail($shopper_email_address, $subject, $msg, $headers);
                }
                endwhile;
            }
        }
        if ($_POST['bulk_select'] == 'purchased') {
            foreach ($_POST as $key => $value) {
                if ($value == "yes") {
                    update_post_meta($key, 'complete_purchase', 1);
                    update_post_meta($key, 'dollar_button_clicked', 1);

                    $shopper_email = get_post_meta($key, 'customer_email', true);

                    $table_name1 = $wpdb->prefix . 'folloup_messages';
                    $sql2        = "SELECT * FROM $table_name1 WHERE message_type = 'thankyou' and store_id = $store_id";
                    $result2     = $wpdb->get_row($sql2);

                    $shopper_name1 = get_post_meta($key, 'customer_fname', true) . ' ' . get_post_meta($key, 'customer_lname', true);
                    $msg_body1     = str_replace("{Shopper's Name}", $shopper_name1, $result2->body);

                    $styist_id = get_post_meta($key, 'stylist_id', true);

                    $stylist_name = get_the_author_meta('display_name', $styist_id);
                    $msg_body2    = str_replace("{Stylist's Name}", $stylist_name, $msg_body1);


                    $store_name = get_the_author_meta('display_name', $store_id);
                    $from       = get_user_meta($store_id, 'email_to_shopper', true);

                    $shopper_name = $shopper_name1;
                    $headers      = 'From: ' . $store_name . '<' . $from . '>' . "\r\n";
                    $headers      .= "Reply-To: " . strip_tags($from) . "\r\n";
                    $headers      .= "MIME-Version: 1.0\r\n";
                    $headers      .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $subject      = $result2->subject;
                    $msg          = $msg_body2;


                    if (! empty($store_name)) {
                        if (! empty($from)) {
                            wp_mail($shopper_email, $subject, $msg, $headers);
                        }
                    }
                }
            }
        }
        if ($_POST['bulk_select'] == 'not-purchased') {
            foreach ($_POST as $key => $value) {
                if ($value == "yes") {
                    $reason      = ".";
                    $table_name1 = $wpdb->prefix . 'folloup_messages';

                    update_post_meta($key, 'reason_not_purchased', $reason);
                    update_post_meta($key, 'dollar_button_clicked', 1);

                    $shopper_email = get_post_meta($key, 'customer_email', true);

                    $sql2    = "SELECT * FROM $table_name1 WHERE message_type = 'promo' and store_id = $store_id";
                    $result2 = $wpdb->get_row($sql2);

                    $shopper_name1 = get_post_meta($key, 'customer_fname', true) . ' ' . get_post_meta($key, 'customer_lname', true);
                    $msg_body1     = str_replace("{Shopper's Name}", $shopper_name1, $result2->body);

                    $styist_id = get_post_meta($key, 'stylist_id', true);

                    $stylist_name = get_the_author_meta('display_name', $styist_id);
                    $msg_body2    = str_replace("{Stylist's Name}", $stylist_name, $msg_body1);

                    if ($options['smtp-active'] == 1) {
                        $from = get_user_meta($user_ID, 'email_to_shopper', true); //get_the_author_meta('user_email', $store_id);
                    } else {
                        $from = get_user_meta($user_ID, 'email_to_shopper', true); //get_the_author_meta('user_email', $store_id);
                    }

                    $store_name = get_the_author_meta('display_name', $store_id);
                    $from       = get_user_meta($store_id, 'email_to_shopper', true);

                    $shopper_name = $shopper_name1;
                    $headers      = 'From: ' . $store_name . '<' . $from . '>' . "\r\n";
                    $headers      .= "Reply-To: " . strip_tags($from) . "\r\n";
                    $headers      .= "MIME-Version: 1.0\r\n";
                    $headers      .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $subject      = $result2->subject;
                    $msg          = $msg_body2;

                    if (! empty($store_name)) {
                        if (! empty($from)) {
                            //wp_mail( $shopper_email, $subject, $msg, $headers );
                            mail($shopper_email, $subject, $msg, $headers);
                        }
                    }
                }
            }
        }
        if ($_POST['bulk_select'] == 'stylist-employees') {
            global $user_ID;
            global $wpdb;
            $store_owner_id = get_user_meta($user_ID, 'store_id', true);
            $store_id = get_user_meta($user_ID, 'store_id', true);
            $table_name = $wpdb->prefix.'folloup_messages';

            $store_name = get_the_author_meta('display_name', $store_id);
            $from       = get_user_meta($store_id, 'email_to_shopper', true);

            $sql = "SELECT * FROM $table_name WHERE message_type = 'stylist-employees' and store_id = $store_id";
            $result = $wpdb->get_row($sql);

            $headers      = 'From: ' . $store_name . '<' . $from . '>' . "\r\n";
            $headers      .= "Reply-To: " . strip_tags($from) . "\r\n";
            $headers      .= "MIME-Version: 1.0\r\n";
            $headers      .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $subject      = $result->subject;

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
                    if ($user_status == 2) {
                        $stylist_email = get_the_author_meta('user_email', $user->ID);
                        $stylist_name = $user->display_name;
                        $msg = str_replace("{Stylist-Employee's Name}", $stylist_name, $result->body);
                        mail($stylist_email, $subject, $msg, $headers);
                    }
                }
            }
        }
    }
    else {
        //TODO: what to do here?
    }
} else { header('Location: ' . get_bloginfo('url') . '/login'); }
?>