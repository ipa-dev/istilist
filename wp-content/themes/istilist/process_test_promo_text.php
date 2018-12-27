<?php /* Template Name: Test Promo Text */ ?>
<?php
    require("/home3/istilist/public_html/wp-content/themes/istilist/twilio-php-master/Twilio/autoload.php");
    use Twilio\Rest\Client;

    global $user_ID;
    $body = get_user_meta(get_post_meta(get_the_ID(), 'store_id', true), 'daily_promo_text', true);
    if (trim(strtolower($body)) != 'na') {
        $sid = 'ACdb92d82faf7befbb1538a208224133a4';
        $token = '1859b70bd4b570f6c8ff702b1ffd005d';
        $client = new Client($sid, $token);
        echo get_post_meta(get_the_ID(), 'store_id', true);
        $sms = $client->account->messages->create(
            // the number we are sending to
            '+1'.get_user_meta(get_post_meta(get_the_ID(), 'store_id', true), 'mobile_number', true),
                            
            array(
                'from' => get_option('twilio_number'),
                // the sms body
                'body' => $body
            )
        );
    }
    header("Location: https://istilist.com/store-profile/");
    
?>