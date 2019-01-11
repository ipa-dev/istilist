<?php /* Template Name: Notify Shopper */
    require_once ABSPATH . "vendor/autoload.php";

    $dotenv = new Dotenv\Dotenv(ABSPATH);
    $dotenv->load();
    
    use Twilio\Rest\Client;

    if (get_post_meta($_POST['shopperID'], 'sms_agreement', true) == 'yes') {
        $sid = getenv('TWILIO_SID');
        $token = getenv('TWILIO_AUTH_KEY');

        $client = new Client($sid, $token);
        try {
            $sms = $client->account->messages->create(
        
                '+1'.get_post_meta($_POST['shopperID'], 'customer_phone', true),
            
                array(
                    'from' => getenv('TWILIO_DEFAULT_NUMBER'),
                
                    // the sms body
                    'body' => "Hey, ".get_post_meta($_POST['shopperID'], 'customer_fname', true).", your fitting room at ".get_user_meta(get_post_meta($_POST['shopperID'], 'store_id', true), 'store_name', true)." is now available."
                )
            );
        } catch ( Twilio\Exceptions\RestException $e ) {
            if ( $e->getCode() == 21610 ) {
                header('HTTP/1.1 500 Internal Server Error');
                die( 'na' );
            }
        }
        
        add_post_meta($_POST['shopperID'], 'notified', 'true', true);
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        die( 'na' );
    }
