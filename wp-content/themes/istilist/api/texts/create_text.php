<?php
global $wpdb;

require_once ABSPATH . 'vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(ABSPATH);
$dotenv->load();

use Twilio\Rest\Client;

function create_text( $request ) {

    if ( !isset( $request['type'] ) ) {
        return new WP_Error( 'no_type', 'No text type given', array( 'status' => 404 ) );
    }
    if ( !isset( $request['store_id'] ) ) {
        return new WP_Error( 'no_store_id', 'No store ID given', array( 'status' => 404 ) );
    }
    if ( !isset( $request['shopper_id'] ) ) {
        return new WP_Error( 'no_shopper_id', 'No shopper ID given', array( 'status' => 404 ) );
    }

    $store_id = $request['store_id'];
    $shopper_id = $request['shopper_id'];
    $shopper_name = get_post_meta($shopper_id, 'customer_fname', true).' '.get_post_meta($shopper_id, 'customer_lname', true);
    $stylist_id = get_post_meta($shopper_id, 'stylist_id', true);
    $stylist_name = get_the_author_meta('display_name', $stylist_id);
    $shopper_phone = get_post_meta($shopper_id, 'customer_phone', true);
    $sms_agreement = get_post_meta($shopper_id, 'sms_agreement', true);

    if (!empty($shopper_phone) && $sms_agreement == 'yes') {
        if ( $request['type'] == 'no-purchase' ) {
            $sql = "SELECT * FROM $table_name WHERE store_id = $store_id and message_type='promotext'";
            $result = $wpdb->get_row($sql);
        }
        elseif ( $request['type'] == 'purchase' ) {
            $sql = "SELECT * FROM $table_name1 WHERE message_type = 'thankyoutext' and store_id = $store_id";
            $result = $wpdb->get_row($sql);
        }
    }
    if ( $request['type'] == 'test-timed-promo' ) {
        $shopper_name = 'Shopping Shopper';
        $stylist_name = 'Stylish Stylist';
        $shopper_phone = get_user_meta($store_id, 'mobile_number', true);
        $result = ( object ) array(
            'body' => get_user_meta( $store_id, 'daily_promo_text', true )
        );
    }

    if (!empty($result->body)) {
        $msg = str_replace("{Shopper's Name}", $shopper_name, $result->body);
        $msg = str_replace("{Stylist's Name}", $stylist_name, $msg);

        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_AUTH_KEY");
        $client = new Client($sid, $token);

        try {
            $sms = $client->account->messages->create(

                '+1' . $shopper_phone,
                array(
                    'from' => getenv('TWILIO_DEFAULT_NUMBER'),

                    'body' => $msg
                )
            );
        } catch ( \Services_Twilio_RestException $e ) {
            return new WP_Error( 'twilio', $e->getMessage(), array( 'status' => 500 ) );
        }

        wp_send_json_success();
    }
}