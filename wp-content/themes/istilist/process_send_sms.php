<?php /* Template Name: Send SMS */
// Get the PHP helper library from twilio.com/docs/php/install

require_once 'twilio-php-master/Twilio/autoload.php'; // Loads the library
use Twilio\Twiml;

$response = new Twiml;
$body = $_REQUEST['Body'];
$from = $_REQUEST['From'];
if (strtolower(trim($body)) == 'yes') {
    $args = array(
        'post_status' => 'publish',
        'post_type'		=>	'shopper',
        'meta_query'	=>	array(
            array(
                'key' => 'customer_phone',
                'value'	=>	substr($from, 2),
                'compare' => 'LIKE'
            )
        )
    );
    $my_query = new WP_Query($args);
    
    if ($my_query->have_posts()) {
        while ($my_query->have_posts()) {
            $my_query->the_post();
            add_post_meta(get_the_ID(), 'promo_list', get_the_author_meta('ID'));
            add_post_meta(get_the_ID(), 'promo_list_timestamp', date(DATE_RFC2822));
        }
    }
    
    $response->message("Congratulations! You've enrolled in PromPerks. You'll get the latest info on the best deals. Reply STOP to stop receiving messages from us.");
}
print $response;
