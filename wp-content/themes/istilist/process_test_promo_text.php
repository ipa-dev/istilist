<?php /* Template Name: Test Promo Text */ ?>
<?php
    require("/home/istilist/public_html/wp-content/themes/standard-theme/twilio-php-master/Twilio/autoload.php");
    use Twilio\Rest\Client;

    $body = get_user_meta(get_post_meta(get_the_ID(), 'store_id', true), 'daily_promo_text', true);
    if (trim(strtolower($body)) != 'na') {
        $sid = 'ACdb92d82faf7befbb1538a208224133a4';
        $token = '1859b70bd4b570f6c8ff702b1ffd005d';
        $client = new Client($sid, $token);
        $sms = $client->account->messages->create(

            // the number we are sending to - Any phone number
            '+1'.get_user_meta(get_post_meta(get_the_ID(), 'store_id', true), 'mobile_number', true),
                            
            array(
                // Step 6: Change the 'From' number below to be a valid Twilio number
                // that you've purchased
                'from' => get_option('twilio_number'),
                // the sms body
                'body' => $body
            )
        );
    }
    header("Location: http://istilist.com/store-profile/");
    
?>