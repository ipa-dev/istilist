<?php /* Template Name: Notify Shopper */
    require_once "/home3/istilist/public_html/vendor/autoload.php";

    $dotenv = new Dotenv\Dotenv("/home3/istilist/public_html/");
    $dotenv->load();
    
    use Twilio\Rest\Client;

    if (get_post_meta($_POST['shopperID'], 'sms_agreement', true) == 'yes') {
        $sid = getenv('TWILIO_SID');
        $token = getenv('TWILIO_AUTH_KEY');

        $client = new Client($sid, $token);
        $sms = $client->account->messages->create(
    
        // the number we are sending to - Any phone number
        '+1'.get_post_meta($_POST['shopperID'], 'customer_phone', true),
        
        array(
        //Step 6: Change the 'From' number below to be a valid Twilio number
        // that you've purchased
            'from' => get_option('twilio_number'),
        
            // the sms body
            'body' => "Hey, ".get_post_meta($_POST['shopperID'], 'customer_fname', true).", your fitting room at ".get_user_meta(get_post_meta($_POST['shopperID'], 'store_id', true), 'store_name', true)." is now available."
        )
        );
        add_post_meta($_POST['shopperID'], 'notified', 'true');
    } else {
        echo "not authorized";
    }
