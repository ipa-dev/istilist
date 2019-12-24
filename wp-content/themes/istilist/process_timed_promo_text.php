<?php /* Template Name: Timed Promo Text */ ?>
<?php

require_once ABSPATH . "vendor/autoload.php";

$dotenv = new Dotenv\Dotenv("/home3/istilist/public_html/");
$dotenv->load();

use Twilio\Rest\Client;

$shopper_args = array(
    'post_type' => 'shopper',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key' => 'promo_list',
            'compare' => '!=',
            'value' => '',
        ),
        array(
            'key' => 'customer_phone',
            'compare' => '!=',
            'value' => '',
        )
    ),
    'date_query' => array(
        array(
            'column' => 'post_date',
            'after' => '5 minutes ago',
        )
    )
);

$shopper_data = new WP_Query($shopper_args);
if ($shopper_data->have_posts()) {
    while ($shopper_data->have_posts()) : $shopper_data->the_post();
    $shopper_id = get_the_ID();
    $body = get_user_meta(get_post_meta($shopper_id, 'store_id', true), 'daily_promo_text', true);
    if (trim(strtolower($body)) != 'na') {
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_AUTH_KEY");
        $client = new Client($sid, $token);
        try {
            $sms = $client->account->messages->create(
                    '+1' . get_post_meta($shopper_id, 'customer_phone', true),
                    array(
                        'from' => getenv('TWILIO_DEFAULT_NUMBER'),
                        'body' => $body
                    )
                );
        } catch (Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
    }
    endwhile;
}
?>