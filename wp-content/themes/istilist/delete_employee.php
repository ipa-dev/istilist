<?php /* Template Name: Delete Employee */ ?>
<?php
require_once(ABSPATH.'wp-admin/includes/user.php');
global $wpdb;
$store_id = $_POST['store_user_id'];
$employee_id = $_POST['employee_id'];
wp_delete_user($employee_id, $store_id);
?>