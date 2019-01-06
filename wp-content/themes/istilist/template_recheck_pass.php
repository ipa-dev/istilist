<?php /* Template Name: ajax-recheck-pass */ ?>
<?php
global $user_ID;
$recheck_pass = $_POST['recheck_pass'];
$user = get_user_by('ID', $user_ID);
if ($user && wp_check_password($recheck_pass, $user->data->user_pass, $user_ID)) {
    echo 1;
    
} else {
    echo 0;
}
?>
