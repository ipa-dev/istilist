<?php
function update_store( $param ) {
    wp_update_user(array( 'ID' => $request['store_id'], 'display_name' => $request['store_name'] ));
    update_user_meta($request['store_id'], 'contact_name', $request['contact_name']);
    update_user_meta($request['store_id'], 'address', $request['address']);
    update_user_meta($request['store_id'], 'phone_number', $request['phone_number']);
    update_user_meta($request['store_id'], 'mobile_number', $request['mobile_number']);
    update_user_meta($request['store_id'], 'website', $request['website']);
    update_user_meta($request['store_id'], 'security_questions', $request['security_questions']);
    update_user_meta($request['store_id'], 'security_answer', $request['security_answer']);
    update_user_meta($request['store_id'], 'city', $request['city']);
    update_user_meta($request['store_id'], 'state', $request['state']);
    update_user_meta($request['store_id'], 'zipcode', $request['zipcode']);
    update_user_meta($request['store_id'], 'reporting', $request['email_address']);
    update_user_meta($request['store_id'], 'email_to_shopper', $request['email_to_shopper']);
    
    $user_reverse_order = get_user_meta( $request['store_id'], 'reverse_order', true );
    if (!empty($user_reverse_order) || $user_reverse_order == null) {
        update_user_meta($request['store_id'], 'reverse_order', $request['reverse_order']);
    } else {
        add_user_meta($request['store_id'], 'reverse_order', $request['reverse_order']);
    }

    $user_daily_text_promo = get_user_meta($request['store_id'], 'daily_promo_text', true);
    if (!empty($user_daily_text_promo)) {
        update_user_meta($request['store_id'], 'daily_promo_text', $request['daily_promo_text']);
    } else {
        add_user_meta($request['store_id'], 'daily_promo_text', $request['daily_promo_text']);
    }
    
    if (!empty($request['pwd'])) {
        wp_set_password($request['pwd'], $request['store_id']);
    }

    wp_send_json_success();
}
?>