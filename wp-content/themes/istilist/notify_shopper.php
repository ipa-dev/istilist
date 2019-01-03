<?php /* Template Name: Notify Shopper */
    require("twilio-php-master/Twilio/autoload.php");
    use Twilio\Rest\Client;

    if (get_post_meta($_POST['shopperID'], 'sms_agreement', true) == 'yes') {
        $sid = 'ACdb92d82faf7befbb1538a208224133a4';
        $token = 'c6481d599afc5bedced939b8c53fbf5f';
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
