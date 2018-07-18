<?php /* Template Name: Send All Shoppers Email */ ?>
<?php get_header(); ?>
<?php if(is_user_logged_in()){ ?>
<?php global $user_ID; global $wpdb; ?>
<?php $store_id = get_user_meta($user_ID, 'store_id', true); ?>
<?php $user_role = get_user_role($user_ID); ?>
<?php

$store_name = get_the_author_meta('display_name', $store_id);
$from = get_user_meta($store_id, 'email_to_shopper', true);
$headers = 'From: '.$store_name.'<'.$from.'>'."\r\n";
$headers .= "Reply-To: ". strip_tags($from) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

if ($_POST['shopper_email_template'] == 'Send Test E-mail') {
  mail( get_user_meta($user_ID, 'store_email', true), $POST['shopper_email_subject'],
    $POST['shopper_email_body'], $headers);
}
else {
  $unique_shopper_emails = get_unique_post_meta_values('store_id',
    $store_id, 'publish', 'shopper', 'customer_email');
  foreach ($unique_shopper_emails as $unique_shopper_email) {

    $filtered_email = filter_var($unique_shopper_email, FILTER_VALIDATE_EMAIL);
    if ($filtered_email) {

      mail( $filtered_email, $POST['shopper_email_subject'],
        $POST['shopper_email_body'], $headers);
    }
  }
}
}
?>
