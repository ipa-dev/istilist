<?php /* Template Name: Delete Preference */ ?>
<?php if(is_user_logged_in()){ ?>
<?php
$pref_id = decripted($_GET['id']);
delete_post_meta($pref_id, 'store_id');
wp_delete_post($pref_id);
header('Location: '.get_bloginfo('url').'/store-preferences');
?>
<?php } else { header('Location: '.get_bloginfo('url').'/login'); } ?>