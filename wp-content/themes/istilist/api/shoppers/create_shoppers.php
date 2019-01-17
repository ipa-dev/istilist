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

    add_post_meta($new_post_id, 'customer_fname', $request['customer_fname']);
    add_post_meta($new_post_id, 'customer_lname', $request['customer_lname']);
    add_post_meta($new_post_id, 'school_event', $request['school_event']);
    add_post_meta($new_post_id, 'graduation_year', $request['graduation_year']);
    add_post_meta($new_post_id, 'customer_email', $request['customer_email']);
    add_post_meta($new_post_id, 'customer_phone', $request['customer_phone']);
    add_post_meta($new_post_id, 'design_preferences', $request['design_preferences']);
    add_post_meta($new_post_id, 'style_preferences', $request['style_preferences']);
    add_post_meta($new_post_id, 'color_preferences', $request['color_preferences']);
    add_post_meta($new_post_id, 'customer_size', $request['customer_size']);
    add_post_meta($new_post_id, 'customer_address', $request['customer_address']);
    add_post_meta($new_post_id, 'customer_city', $request['customer_city']);
    add_post_meta($new_post_id, 'customer_state', $request['customer_state']);
    add_post_meta($new_post_id, 'customer_zip', $request['customer_zip']);
    add_post_meta($new_post_id, 'sms_agreement', $request['sms_agreement']);
    add_post_meta($new_post_id, 'entry_date', date('Y-m-d H:i:s'));
    add_post_meta($new_post_id, 'store_id', $store_id );
    add_post_meta($new_post_id, 'hit_plus', 'false');

    if ($request['sms_agreement'] == 'yes' && isset($request['customer_phone'])) {
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_AUTH_KEY");
        $client = new Client($sid, $token);
        $sms = $client->account->messages->create(

            '+1'.$request['customer_phone'],

            array(
                'from' => getenv('TWILIO_DEFAULT_NUMBER'),

                'body' => "Hey, ".$request['customer_fname'].", welcome to ".get_user_meta($store_id, 'store_name', true)."! Text YES to receive alerts and special offers (Up to 6 autodialed msgs/mo. Consent not required to purchase. Msg&data rates may apply. Text STOP to stop)."
            )
        );
    }

    $table_name3 = $wpdb->prefix.'dynamic_form';
    $sql3 = "SELECT * FROM $table_name3 WHERE store_owner_id = $store_id AND is_custom = 1 ORDER BY id";
    $results3 = $wpdb->get_results($sql3);
    if (!empty($results3)) {
        foreach ($results3 as $r3) {
            $var1 = $r3->form_slug;
            $var2 = $request[$r3->form_slug];
            add_post_meta($new_post_id, $var1, $var2);
        }
    }

    return rest_ensure_response( $new_post_id );
}
?>