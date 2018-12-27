<?php /* Template Name: Send Mails */ ?>
<?php if(is_user_logged_in()){ ?>
<?php
$emailto = $_POST['emailto'];
global $user_ID; global $wpdb;
$store_owner_id = get_user_meta($user_ID, 'store_id', true);
$store_id = get_user_meta($user_ID, 'store_id', true);
$table_name = $wpdb->prefix.'folloup_messages';

if(($emailto == 'all-shoppers') && isset($_POST['send_email'])){
	$store_name = get_the_author_meta( 'display_name', $store_id );
	$from       = get_user_meta( $store_id, 'email_to_shopper', true );

	$headers      = 'From: ' . $store_name . '<' . $from . '>' . "\r\n";
	$headers      .= "Reply-To: " . strip_tags( $from ) . "\r\n";
	$headers      .= "MIME-Version: 1.0\r\n";
	$headers      .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$subject      = $_POST['shopper_email_subject'];
	$msg_temp     = $_POST['shopper_email_body'];

	$shopper_args = array(
		'post_type' => 'shopper',
		'post_status' => 'publish',
		'author' => $store_id,
		'posts_per_page' => -1
	);
	$shopper_data = new WP_Query($shopper_args);
	if($shopper_data->have_posts()){
		while($shopper_data->have_posts()) : $shopper_data->the_post();
			$shopper_id = get_the_ID();
			$purchase_array = get_post_meta($shopper_id, 'purchase_array', true);
			if ( ! empty( $purchase_array ) ) {
				if ( $purchase_array[0] == 'true' ) {
					$purchase_status = 'YES';
				} else {
					$purchase_status = 'NO';
				}
			}
			if($purchase_status == 'YES' || $purchase_status == 'NO'){
				$shopper_name = get_post_meta( $shopper_id, 'customer_fname', true ) . ' ' . get_post_meta( $shopper_id, 'customer_lname', true );
				$shopper_email_address = get_post_meta($shopper_id, 'customer_email', true);
				$msg = str_replace( "{Shopper's Name}", $shopper_name, $msg_temp);
				mail( $shopper_email_address, $subject, $msg, $headers );
			}
		endwhile;
	}
	header('Location: '.get_bloginfo('url').'/folloup-messages/');
}
if(($emailto == 'purchased') && isset($_POST['send_email'])){
	$store_name = get_the_author_meta( 'display_name', $store_id );
	$from       = get_user_meta( $store_id, 'email_to_shopper', true );

	$headers      = 'From: ' . $store_name . '<' . $from . '>' . "\r\n";
	$headers      .= "Reply-To: " . strip_tags( $from ) . "\r\n";
	$headers      .= "MIME-Version: 1.0\r\n";
	$headers      .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$subject      = $_POST['shopper_email_subject'];
	$msg_temp     = $_POST['shopper_email_body'];

	$shopper_args = array(
		'post_type' => 'shopper',
		'post_status' => 'publish',
		'author' => $store_id,
		'posts_per_page' => -1
	);
	$shopper_data = new WP_Query($shopper_args);
	if($shopper_data->have_posts()){
		while($shopper_data->have_posts()) : $shopper_data->the_post();
			$shopper_id = get_the_ID();
			$purchase_array = get_post_meta($shopper_id, 'purchase_array', true);
			if ( ! empty( $purchase_array ) ) {
				if ( $purchase_array[0] == 'true' ) {
					$purchase_status = 'YES';
				} else {
					$purchase_status = 'NO';
				}
			}
			if($purchase_status == 'YES'){
				$shopper_name = get_post_meta( $shopper_id, 'customer_fname', true ) . ' ' . get_post_meta( $shopper_id, 'customer_lname', true );
				$shopper_email_address = get_post_meta($shopper_id, 'customer_email', true);
				$msg = str_replace( "{Shopper's Name}", $shopper_name, $msg_temp);
				mail( $shopper_email_address, $subject, $msg, $headers );
			}
		endwhile;
	}
	header('Location: '.get_bloginfo('url').'/folloup-messages/');
}
if(($emailto == 'not-purchased') && isset($_POST['send_email'])){
	$store_name = get_the_author_meta( 'display_name', $store_id );
	$from       = get_user_meta( $store_id, 'email_to_shopper', true );

	$headers      = 'From: ' . $store_name . '<' . $from . '>' . "\r\n";
	$headers      .= "Reply-To: " . strip_tags( $from ) . "\r\n";
	$headers      .= "MIME-Version: 1.0\r\n";
	$headers      .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$subject      = $_POST['shopper_email_subject'];
	$msg_temp     = $_POST['shopper_email_body'];

	$shopper_args = array(
		'post_type' => 'shopper',
		'post_status' => 'publish',
		'author' => $store_id,
		'posts_per_page' => -1
	);
	$shopper_data = new WP_Query($shopper_args);
	if($shopper_data->have_posts()){
		while($shopper_data->have_posts()) : $shopper_data->the_post();
			$shopper_id = get_the_ID();
			$purchase_array = get_post_meta($shopper_id, 'purchase_array', true);
			if ( ! empty( $purchase_array ) ) {
				if ( $purchase_array[0] == 'true' ) {
					$purchase_status = 'YES';
				} else {
					$purchase_status = 'NO';
				}
			}
			if($purchase_status == 'NO'){
				$shopper_name = get_post_meta( $shopper_id, 'customer_fname', true ) . ' ' . get_post_meta( $shopper_id, 'customer_lname', true );
				$shopper_email_address = get_post_meta($shopper_id, 'customer_email', true);
				$msg = str_replace( "{Shopper's Name}", $shopper_name, $msg_temp);
				mail( $shopper_email_address, $subject, $msg, $headers );
			}
		endwhile;
	}
	header('Location: '.get_bloginfo('url').'/folloup-messages/');
}
if(($emailto == 'stylist-employees') && isset($_POST['send_email'])){
	$store_name = get_the_author_meta( 'display_name', $store_id );
	$from       = get_user_meta( $store_id, 'email_to_shopper', true );

	$headers      = 'From: ' . $store_name . '<' . $from . '>' . "\r\n";
	$headers      .= "Reply-To: " . strip_tags( $from ) . "\r\n";
	$headers      .= "MIME-Version: 1.0\r\n";
	$headers      .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$subject      = $_POST['shopper_email_subject'];
	$msg_temp     = $_POST['shopper_email_body'];

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
				$msg = str_replace( "{Stylist-Employee's Name}", $stylist_name, $msg_temp);
				mail( $stylist_email, $subject, $msg, $headers );
			}
		}
	}
	header('Location: '.get_bloginfo('url').'/folloup-messages/');
}
if(isset($_POST['send_test_mail'])){
	$store_name = get_the_author_meta( 'display_name', $store_id );
	$from       = get_user_meta( $store_id, 'email_to_shopper', true );

	$headers      = 'From: ' . $store_name . '<' . $from . '>' . "\r\n";
	$headers      .= "Reply-To: " . strip_tags( $from ) . "\r\n";
	$headers      .= "MIME-Version: 1.0\r\n";
	$headers      .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$subject      = $_POST['shopper_email_subject'];
	$msg          = $_POST['shopper_email_body'];

	$email_address = get_user_meta($store_id, 'reporting', true);
	if(!empty($email_address)){
		$email_address_array = explode(',', $email_address);
	}

	$sql1 = "SELECT * FROM $table_name WHERE message_type = 'shoppers-stylist-employees' and store_id = $store_id";
	$result1 = $wpdb->get_row($sql1);
	if(!empty($result1)){
		$update_query = "UPDATE $table_name SET subject = '".$_POST['shopper_email_subject']."', body = '".nl2br($_POST['shopper_email_body'])."' WHERE message_type = 'shoppers-stylist-employees' and store_id = $store_id";

		$update = $wpdb->query($update_query);
	} else {
		$insert_query = "INSERT INTO $table_name (subject, body, message_type, store_id) VALUES('".$_POST['shopper_email_subject']."', '".$_POST['shopper_email_body']."', 'shoppers-stylist-employees', $store_id)";

		//echo 'INSERT: '.$insert_query;

		$wpdb->query($insert_query);
	}

	if ( ! empty( $store_name ) ) {
		if ( ! empty( $from ) ) {
			if(!empty($email_address_array)){
				foreach($email_address_array as $email_address){
					mail( $email_address, $subject, $msg, $headers );
				}
			}
		}
	}
	header('Location: '.get_bloginfo('url').'/folloup-messages/');
}
?>
<?php } ?>
