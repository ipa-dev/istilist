<?php /* Template Name: Complete Purchase */ ?>
<?php
global $wpdb;
global $user_ID;
$store_id = get_user_meta($user_ID, 'store_id', true);
$shopper_id = $_POST['shopper_id'];

require_once "/home3/istilist/public_html/vendor/autoload.php";

$dotenv = new Dotenv\Dotenv("/home3/istilist/public_html/");
$dotenv->load();

use Twilio\Rest\Client;

update_post_meta($shopper_id, 'complete_purchase', 1);
update_post_meta($shopper_id, 'dollar_button_clicked', 1);
$purchase_array = get_post_meta($shopper_id, 'purchase_array', true);
if (empty($purchase_array)) {
    $purchase_array = array('true');
    add_post_meta($shopper_id, 'purchase_array', $purchase_array, true);
} else {
    array_push($purchase_array, 'true');
    update_post_meta($shopper_id, 'purchase_array', $purchase_array);
}

$shopper_email = get_post_meta($shopper_id, 'customer_email', true);

$table_name1 = $wpdb->prefix.'folloup_messages';
$sql2 = "SELECT * FROM $table_name1 WHERE message_type = 'thankyou' and store_id = $store_id";
$result2 = $wpdb->get_row($sql2);

$shopper_name1 = get_post_meta($shopper_id, 'customer_fname', true).' '.get_post_meta($shopper_id, 'customer_lname', true);
$msg_body1 = str_replace("{Shopper's Name}", $shopper_name1, $result2->body);

$styist_id = get_post_meta($shopper_id, 'stylist_id', true);

$stylist_name = get_the_author_meta('display_name', $styist_id);
$msg_body2 = str_replace("{Stylist's Name}", $stylist_name, $msg_body1);


$store_name = get_the_author_meta('display_name', $store_id);
$from = get_user_meta($store_id, 'email_to_shopper', true);

$shopper_name  = $shopper_name1;
$headers = 'From: '.$store_name.'<'.$from.'>'."\r\n";
$headers .= "Reply-To: ". strip_tags($from) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$subject = $result2->subject;
$msg = $msg_body2;

if (!empty($store_name)) {
    if (!empty($from)) {
        wp_mail($shopper_email, $subject, $msg, $headers);
    }
}

$shopper_phone = get_post_meta($shopper_id, 'customer_phone', true);
$sms_agreement = get_post_meta($shopper_id, 'sms_agreement', true);
if (!empty($shopper_phone) && $sms_agreement == 'yes') {
    $sql3 = "SELECT * FROM $table_name1 WHERE message_type = 'thankyoutext' and store_id = $store_id";
    $result3 = $wpdb->get_row($sql3);

    if (!empty($result3->body)) {
        $msg_body1 = str_replace("{Shopper's Name}", $shopper_name1, $result3->body);
        $msg_body2 = str_replace("{Stylist's Name}", $stylist_name, $msg_body1);

        $client = new Client(getenv('TWILIO_SID'), getenv('TWILIO_AUTH_KEY'));
        $sms = $client->account->messages->create(

            '+1'.$shopper_phone,
            array(

                'from' => getenv('TWILIO_DEFAULT_NUMBER'),

                'body' => $msg_body2
            )
        );
    }
}
?>
