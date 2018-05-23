<?php /* Template Name: Delete Dress Registration */ ?>
<?php
$dress_reg_id = decripted($_GET['rid']);
if(!empty($dress_reg_id)){
    $post_metas = get_post_meta($dress_reg_id, false);
    foreach($post_metas as $meta_key => $meta_value){
        delete_post_meta($dress_reg_id, $meta_key);
    }
    wp_delete_post($dress_reg_id);
    header('Location: '.get_bloginfo('url').'/dress-registration/');
}
?>