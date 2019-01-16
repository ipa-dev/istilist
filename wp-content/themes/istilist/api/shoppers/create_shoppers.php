<?php
require_once ABSPATH . 'vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(ABSPATH);
$dotenv->load();

use Twilio\Rest\Client;

function create_shoppers( $request ) {

    $store_id = $request['store_id'];
    $user_role = get_user_role( $store_id );
    global $wpdb;

    $post_arg = array(
        'post_type' => 'shopper',
        'post_title' => $request['customer_fname'].' '.$request['customer_lname'],
        'post_content' => $request['shoppers_feedback'],
        'post_author' => $store_id,
        'post_status' => 'publish',
    );

    $new_post_id = wp_insert_post($post_arg);

    foreach ($request as $field => $value) {
        add_post_meta( $new_post_id, $field, $value);
    }

    if ( $request['sms_agreement'] == 'yes' && isset( $request['customer_phone'] ) ) {
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_AUTH_KEY");
        $client = new Client($sid, $token);
        $sms = $client->account->messages->create(

            '+1'.$request['customer_phone'],

            array(
                'from' => getenv('TWILIO_DEFAULT_NUMBER'),

                'body' => "Hey, ".$_request['customer_fname'].", welcome to ".get_user_meta($store_id, 'store_name', true)."! Text YES to receive alerts and special offers (Up to 6 autodialed msgs/mo. Consent not required to purchase. Msg&data rates may apply. Text STOP to stop)."
            )
        );
    }

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

    header('Location: ' . $request['callback_url']);
}
?>