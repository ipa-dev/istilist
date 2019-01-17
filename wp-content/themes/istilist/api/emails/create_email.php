<?php
global $wpdb;

function create_email( $request ) {

    if ( !isset( $request['type'] ) ) {
        return new WP_Error( 'no_type', 'No e-mail type given', array( 'status' => 404 ) );
    }
    if ( !isset( $request['store_id'] ) ) {
        return new WP_Error( 'no_store_id', 'No store ID given', array( 'status' => 404 ) );
    }
    if ( !isset( $request['shopper_id'] ) ) {
        return new WP_Error( 'no_shopper_id', 'No shopper ID given', array( 'status' => 404 ) );
    }

    $store_id = $request['store_id'];
    $shopper_id = $request['shopper_id'];
    $table_name = $wpdb->prefix.'folloup_messages';

    if ( $request['type'] == 'no-purchase' ) {
        $reason = $request['reason'] ?: '';

        update_post_meta($shopper_id, 'reason_not_purchased', $reason);
        update_post_meta($shopper_id, 'dollar_button_clicked', 1);

        $purchase_array = get_post_meta($shopper_id, 'purchase_array', true);
        if (empty($purchase_array)) {
            $purchase_array = array('false');
            add_post_meta($shopper_id, 'purchase_array', $purchase_array, true);
        } else {
            array_push($purchase_array, 'false');
            update_post_meta($shopper_id, 'purchase_array', $purchase_array);
        }

        $sql = "SELECT * FROM $table_name WHERE message_type = 'promo' and store_id = $store_id";
    }
    elseif ( $request['type'] == 'purchase' ) {
        update_post_meta($shopper_id, 'complete_purchase', 1);
        update_post_meta($shopper_id, 'dollar_button_clicked', 1);
        $purchase_array = get_post_meta($shopper_id, 'purchase_array', true);
        if (empty($purchase_array)) {
            $purchase_array = array('true');
            add_post_meta($shopper_id, 'purchase_array', $purchase_array, true);
        } else {
            array_push($purchase_array, 'true');
            update_post_meta($shopper_id, 'purchase_array', $purchase_array);
        }

        $sql = "SELECT * FROM $table_name WHERE message_type = 'thankyou' and store_id = $store_id";
    }
    elseif ( $request['type'] == 'employee' ) {

    }
    else {
        return new WP_Error( 'invalid_type', 'E-mail type given is invalid', array( 'status' => 404 ) );
    }

    $shopper_email = get_post_meta($shopper_id, 'customer_email', true);
    $shopper_name = get_post_meta($shopper_id, 'customer_fname', true).' '.get_post_meta($shopper_id, 'customer_lname', true);
    $stylist_id = get_post_meta($shopper_id, 'stylist_id', true);
    $stylist_name = get_the_author_meta('display_name', $stylist_id);
    $store_name = get_the_author_meta('display_name', $store_id);
    $from = get_user_meta($store_id, 'email_to_shopper', true);


    $result = $wpdb->get_row($sql);
    $msg = str_replace("{Shopper's Name}", $shopper_name, $result->body);
    $msg = str_replace("{Stylist's Name}", $stylist_name, $msg_body);

    $headers = 'From: '.$store_name.'<'.$from.'>'."\r\n";
    $headers .= "Reply-To: ". strip_tags($from) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $subject = $result->subject;

    if (!empty($store_name)) {
        if (!empty($from)) {
            wp_mail($shopper_email, $subject, $msg, $headers);
        }
    }
}
?>