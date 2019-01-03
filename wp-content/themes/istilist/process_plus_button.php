<?php /* Template Name: Add New shopper */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
	global $user_ID;
	$store_owner_id = get_user_meta($user_ID, 'store_id', true);
	$store_id = get_user_meta($user_ID, 'store_id', true);
	$user_role = get_user_role($user_ID); 
?>

<?php } ?>