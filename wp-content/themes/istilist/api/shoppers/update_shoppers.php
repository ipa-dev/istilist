<?php
function update_shoppers( $request ) {
    global $wpdb;
    $post_arg = array(
        'ID'            => $shopper_id,
        'post_title'    => $_POST['customer_fname'].' '.$_POST['customer_lname'],
        'post_content'  => $_POST['shoppers_feedback'],
        'post_author'   => $user_ID
    );

    wp_update_post($post_arg);

    update_post_meta($shopper_id, 'customer_fname', $_POST['customer_fname']);
    update_post_meta($shopper_id, 'customer_lname', $_POST['customer_lname']);
    update_post_meta($shopper_id, 'school_event', $_POST['school_event']);
    update_post_meta($shopper_id, 'graduation_year', $_POST['graduation_year']);
    update_post_meta($shopper_id, 'customer_email', $_POST['customer_email']);
    update_post_meta($shopper_id, 'customer_phone', $_POST['customer_phone']);
    update_post_meta($shopper_id, 'design_preferences', $_POST['design_preferences']);
    update_post_meta($shopper_id, 'style_preferences', $_POST['style_preferences']);
    update_post_meta($shopper_id, 'color_preferences', $_POST['color_preferences']);
    update_post_meta($shopper_id, 'customer_size', $_POST['customer_size']);
    update_post_meta($shopper_id, 'customer_address', $_POST['customer_address']);
    update_post_meta($shopper_id, 'customer_city', $_POST['customer_city']);
    update_post_meta($shopper_id, 'customer_state', $_POST['customer_state']);
    update_post_meta($shopper_id, 'customer_zip', $_POST['customer_zip']);
    update_post_meta($shopper_id, 'sms_agreement', $_POST['sms_agreement']);

    $store_id = get_user_meta($user_ID, 'store_id', true);
    $table_name3 = $wpdb->prefix.'dynamic_form';
    $sql3 = "SELECT * FROM $table_name3 WHERE store_owner_id = $store_id AND is_custom = 1 ORDER BY id";
    $results3 = $wpdb->get_results($sql3);
    if (!empty($results3)) {
        foreach ($results3 as $r3) {
            $var1 = $r3->form_slug;
            $var2 = $_POST[$r3->form_slug];
            update_post_meta($shopper_id, $var1, $var2);
        }
    }

    if ($shopper_id) {
        echo '<p class="successMsg">Thank you for your valuable time and information.</p>';
        header("Location: ".get_bloginfo('url')."/dashboard");
    } else {
        echo '<p class="errorMsg">Sorry, your information is not updated.</p>';
    }
}
?>