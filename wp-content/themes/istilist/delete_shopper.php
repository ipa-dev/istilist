<?php /* Template Name: Delete Shopper */ ?>
<?php
require_once(ABSPATH.'wp-admin/includes/user.php');
global $wpdb;
$shopper_id = $_POST['shopper_id'];
wp_trash_post($shopper_id);
?>