<?php /* Template Name: Timed Promo Text */ ?>
<?php
require ("twilio-php-master/Twilio/autoload.php");
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
			'after' => '20 minutes ago',
		)
	)
);

$shopper_data = new WP_Query($shopper_args);
if($shopper_data->have_posts()){
	while($shopper_data->have_posts()) : $shopper_data->the_post();
		$shopper_id = get_the_ID();
		$body = get_user_meta(get_post_meta($shopper_id, 'store_id', TRUE), 'daily_promo_text', TRUE);
		if (trim(strtolower($body)) != 'na') {
			$sid = 'ACdb92d82faf7befbb1538a208224133a4';
			$token = '1859b70bd4b570f6c8ff702b1ffd005d';
			$client = new Client($sid, $token);
			try {
				$sms = $client->account->messages->create(
					'+1' . get_post_meta( $shopper_id, 'customer_phone', true ),
					array(
						'from' => get_option( 'twilio_number' ),
						'body' => $body
					)
				);
			} catch (Exception $e){
				echo 'Message: ' .$e->getMessage();
			}
		}
	endwhile;
}
?>