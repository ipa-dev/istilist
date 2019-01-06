<?php /* Template Name: Notify Shopper */
    require_once ABSPATH . "vendor/autoload.php";

    $dotenv = new Dotenv\Dotenv(ABSPATH);
    $dotenv->load();
    
    use Twilio\Rest\Client;

    if (get_post_meta($_POST['shopperID'], 'sms_agreement', true) == 'yes') {
        $sid = getenv('TWILIO_SID');
        $token = getenv('TWILIO_AUTH_KEY');

        $client = new Client($sid, $token);
        $sms = $client->account->messages->create(
    
        // customer phone number
        '+1'.get_post_meta($_POST['shopperID'], 'customer_phone', true),
        
        array(
            'from' => getenv('TWILIO_DEFAULT_NUMBER'),
        
            // the sms body
            'body' => "Hey, ".get_post_meta($_POST['shopperID'], 'customer_fname', true).", your fitting room at ".get_user_meta(get_post_meta($_POST['shopperID'], 'store_id', true), 'store_name', true)." is now available."
        )
        );
        // TODO: figure out twilio error handling
        // if ($sms->code == '21610') { //error code for blacklisted number
        //     echo "na";
        // }
        // else {
            add_post_meta($_POST['shopperID'], 'notified', 'true');
        // }
    } else {
        echo "na";
    }
