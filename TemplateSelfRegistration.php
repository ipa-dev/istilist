<?php /* Template Name: Self Registration */ ?>
<?php get_header(); ?>
<?php if(is_user_logged_in()){ ?>
<?php global $user_ID; global $wpdb; ?>
<?php $store_id = get_user_meta($user_ID, 'store_id', true); ?>

<? } ?>
