<?php /* Template Name: Send Mail Stylist Employees */ ?>
<?php
global $user_ID; global $wpdb;
$store_owner_id = get_user_meta($user_ID, 'store_id', true);
$store_id = get_user_meta($user_ID, 'store_id', true);
$table_name = $wpdb->prefix.'folloup_messages';

if(isset($_POST['stylist_employees_test_email']))	{
	$store_name = get_the_author_meta( 'display_name', $store_id );
	$from       = get_user_meta( $store_id, 'email_to_shopper', true );

	$sql7 = "SELECT * FROM $table_name WHERE message_type = 'stylist-employees' and store_id = $store_id";
	$result7 = $wpdb->get_row($sql7);

	$headers      = 'From: ' . $store_name . '<' . $from . '>' . "\r\n";
	$headers      .= "Reply-To: " . strip_tags( $from ) . "\r\n";
	$headers      .= "MIME-Version: 1.0\r\n";
	$headers      .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$subject      = $result7->subject;
	$msg          = $result7->body;

	$email_address = get_user_meta($store_id, 'reporting', true);
	if(!empty($email_address)){
		$email_address_array = explode(',', $email_address);
	}

	if ( ! empty( $store_name ) ) {
		if ( ! empty( $from ) ) {
			//wp_mail( $shopper_email, $subject, $msg, $headers );
			if(!empty($email_address_array)){
				foreach($email_address_array as $email_address){
					mail( $email_address, $subject, $msg, $headers );
				}
			}
		}
	}
	header('Location: '.get_bloginfo('url').'/folloup-messages/');
}

if(isset($_POST['stylist_employees_send']))	{
	$store_name = get_the_author_meta( 'display_name', $store_id );
	$from       = get_user_meta( $store_id, 'email_to_shopper', true );

	$sql = "SELECT * FROM $table_name WHERE message_type = 'stylist-employees' and store_id = $store_id";
	$result = $wpdb->get_row($sql);

	$headers      = 'From: ' . $store_name . '<' . $from . '>' . "\r\n";
	$headers      .= "Reply-To: " . strip_tags( $from ) . "\r\n";
	$headers      .= "MIME-Version: 1.0\r\n";
	$headers      .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$subject      = $result->subject;

	$user_query = new WP_User_Query(
		array(
			'role__in' => array('storeemployee', 'storesupervisor'),
			'meta_key' => 'store_id',
			'meta_value' => $store_id,
			'orderby' => 'display_name',
			'order' => 'ASC'
		)
	);
	if(!empty($user_query->results)){
		foreach($user_query->results as $user){
			$user_status = get_the_author_meta('user_status', $user->ID);
			if($user_status == 2){
				$stylist_email = get_the_author_meta('user_email', $user->ID);
				$stylist_name = $user->display_name;
				$msg = str_replace( "{Stylist-Employee's Name}", $stylist_name, $result->body);
				mail( $stylist_email, $subject, $msg, $headers );
			}
		}
	}
	header('Location: '.get_bloginfo('url').'/folloup-messages/');
}

if(isset($_POST['stylist_employees_save']))	{
	$sql7 = "SELECT * FROM $table_name WHERE message_type = 'stylist-employees' and store_id = $store_id";
	$result7 = $wpdb->get_row($sql7);

	if(!empty($result7)){
		echo $update_query = "UPDATE $table_name SET subject = '".$_POST['stylist_employees_subject']."', body = '".nl2br($_POST['stylist_employees_email_body'])."' WHERE message_type = 'stylist-employees' and store_id = $store_id";
		$update = $wpdb->query($update_query);
	} else {
		echo $insert_query = "INSERT INTO $table_name SET subject = '".$_POST['stylist_employees_subject']."', body = '".nl2br($_POST['stylist_employees_email_body'])."', message_type = 'stylist-employees', store_id = $store_id";
		$update = $wpdb->query($insert_query);
	}
	header('Location: '.get_bloginfo('url').'/folloup-messages/');
}
?>
