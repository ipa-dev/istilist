<?php /* Template Name: Test Promo Text */ ?>
<?php get_header(); ?>
<?  
    require("/home3/istilist/public_html/vendor/autoload.php");

    $dotenv = new Dotenv\Dotenv("/home3/istilist/public_html/");
    $dotenv->load();

    require_once "/home3/istilist/public_html/wp-content/themes/istilist/twilio-php-master/Twilio/autoload.php";
    use Twilio\Rest\Client; 
?>
<?php if (is_user_logged_in()) { ?>
<?php
    

    global $user_ID;
    $body = get_user_meta($user_ID, 'daily_promo_text', true);
    if (trim(strtolower($body)) != 'na') {
        $sid = 'ACdb92d82faf7befbb1538a208224133a4';
        $token = getenv("TWILIO_AUTH_KEY");
        $client = new Client($sid, $token);
        echo get_the_ID();
        exit();
        $sms = $client->account->messages->create(
            // the number we are sending to
            '+1'.get_user_meta($user_ID, 'mobile_number', true),
                            
            array(
                'from' => get_option('twilio_number'),
                // the sms body
                'body' => $body
            )
        );
    }
    header("Location: https://istilist.com/store-profile/");
?>
<?php } else { header("Location: " . get_bloginfo( 'url' ) . '/login'); } ?>