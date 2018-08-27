<?php /* Template Name: Change Password */ ?>
<?php global $user_ID; ?>
<?php $employee_id = $_POST['employee_id'];
      wp_set_password($_POST['pass'], $employee_id);
?>

