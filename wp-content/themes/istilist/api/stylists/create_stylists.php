<?php
function create_stylists( $request ) {
    global $wpdb;

    $new_user_id = -1;

    if ( email_exists( $request['emailAddress'] ) ) {
        $errorCode = 1;
    } else {
        $new_user_id = wp_insert_user(
            array(
                'user_login'		=> $request['emailAddress'],
                'user_pass'             => $request['password'],
                'user_email'		=> $request['emailAddress'],
                'display_name'		=> $request['employeeName'],
                'role'              => $request['employeerole'],
                'user_registered'	=> date('Y-m-d H:i:s')
            )
        );
                
        $wpdb->update($wpdb->prefix.'users', array('user_status' => 2), array('ID' => $new_user_id));
        add_user_meta($new_user_id, 'phone_number', $request['phoneNumber']);
        $store_id = get_user_meta($request['store_id'], 'store_id', true);
        $store_name = get_user_meta($request['store_id'], 'store_name', true);
        add_user_meta($new_user_id, 'store_id', $store_id);
        add_user_meta($new_user_id, 'store_name', $store_name);
        
        $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $request['emailAddress']));
        if (empty($key)) {
            $key = wp_generate_password(20, false);
            $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $request['emailAddress']));
        }
                                                                
    }
}
?>