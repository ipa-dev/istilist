<?php
function create_store($request) {

    global $wpdb;

    $email_addr = explode(',', $request['email_address']);
    $email_addr[0];

    if (email_exists($email_addr[0])) {
        return new WP_Error( 'email_taken', 'A user already exists with this e-mail address', array( 'status' => 500 ) );
    } 

    $new_user_id = wp_insert_user(
        array(
            'user_login'		=> $email_addr[0],
            'user_pass'			=> $request['pwd'],
            'user_email'		=> $email_addr[0],
            'display_name'		=> $request['store_name'],
            'role'              => 'storeowner',
            'user_registered'	=> date('Y-m-d H:i:s')
        )
    );

    add_user_meta($new_user_id, 'contact_name', $request['contact_name']);
    add_user_meta($new_user_id, 'address', $request['address']);
    add_user_meta($new_user_id, 'phone_number', $request['phone_number']);
    add_user_meta($new_user_id, 'mobile_number', $request['mobile_number']);
    add_user_meta($new_user_id, 'website', $request['website']);
    add_user_meta($new_user_id, 'security_questions', $request['security_questions']);
    add_user_meta($new_user_id, 'security_answer', $request['security_answer']);
    add_user_meta($new_user_id, 'city', $request['city']);
    add_user_meta($new_user_id, 'state', $request['state']);
    add_user_meta($new_user_id, 'zipcode', $request['zipcode']);
    add_user_meta($new_user_id, 'mobile_number_optin', $request['mobile_number_optin']);
    add_user_meta($new_user_id, 'reporting', $request['email_address']);
    add_user_meta($new_user_id, 'store_id', $new_user_id);
    add_user_meta($new_user_id, 'store_name', $request['store_name']);
    add_user_meta($new_user_id, 'email_to_shopper', $request['email_to_shopper']);
                                                            
    $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $email_addr[0]));
    if (empty($key)) {
        $key = wp_generate_password(20, false);
        $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $email_addr[0]));
        
        $table_name = $wpdb->prefix.'folloup_messages';
        
        $thankyou = "INSERT INTO $table_name(message_type, store_id, subject, body, sender) VALUES ('thankyou', '".$new_user_id."', 'Thank You', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec tristique, neque quis pharetra tempor, eros tellus mattis odio, egestas pellentesque nisi lacus ut elit. Fusce vitae leo nunc. Phasellus gravida ultrices sem ut consectetur. Aliquam et neque et leo consequat semper. Praesent eget sem mauris. Suspendisse interdum enim at nisl sodales luctus. Mauris mattis, sem at efficitur sollicitudin, tellus arcu aliquam leo, sit amet placerat ante purus at augue. Morbi iaculis lorem orci, quis egestas libero feugiat sed.', 'Regards,<br>Store Name.')";
        $promo = "INSERT INTO $table_name(message_type, store_id, subject, body, sender) VALUES ('promo', '".$new_user_id."', 'Promotional Mail', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec tristique, neque quis pharetra tempor, eros tellus mattis odio, egestas pellentesque nisi lacus ut elit. Fusce vitae leo nunc. Phasellus gravida ultrices sem ut consectetur. Aliquam et neque et leo consequat semper. Praesent eget sem mauris. Suspendisse interdum enim at nisl sodales luctus. Mauris mattis, sem at efficitur sollicitudin, tellus arcu aliquam leo, sit amet placerat ante purus at augue. Morbi iaculis lorem orci, quis egestas libero feugiat sed.', 'Regards,<br>Store Name.')";
        $thankyoutext = "INSERT INTO $table_name(message_type, store_id) VALUES ('thankyoutext', '".$new_user_id."')";
        $promotext = "INSERT INTO $table_name(message_type, store_id) VALUES ('promotext', '".$new_user_id."')";
        
        $wpdb->query($thankyou);
        $wpdb->query($promo);
        $wpdb->query($thankyoutext);
        $wpdb->query($promotext);
        
        $table_name = $wpdb->prefix.'dynamic_form';
        $insert_form = "INSERT INTO $table_name (form_display_name, form_slug, form_type, is_required, is_active, store_owner_id) VALUES ('First Name', 'customer_fname', 'text', '1', '1', '".$new_user_id."'),('Last Name', 'customer_lname', 'text', '1', '1', '".$new_user_id."'), ('Profile Picture', 'profile_pic', 'file', '1', '1', '".$new_user_id."'), ('School/Event', 'school_event', 'text', '1', '1', '".$new_user_id."'),('Graduation Year', 'graduation_year', 'select', '1', '1', '".$new_user_id."'),('Email', 'customer_email', 'text', '1', '1', '".$new_user_id."'),('Phone','customer_phone', 'text', '1', '1', '".$new_user_id."'),('Address', 'customer_address', 'text', '1', '1', '".$new_user_id."'),('City', 'customer_city', 'text','1', '1', '".$new_user_id."'),('State', 'customer_state', 'text', '1', '1', '".$new_user_id."'),('ZIP', 'customer_zip', 'text', '1', '1', '".$new_user_id."'),('Designer Preference','design_preferences', 'checkbox', '1', '1', '".$new_user_id."'),('Style Preference', 'style_preferences', 'checkbox', '1', '1', '".$new_user_id."'),('Color Preferences', 'color_preferences', 'text', '1', '1', '".$new_user_id."'),('Size', 'customer_size', 'select', '1', '1', '".$new_user_id."')";
        $wpdb->query($insert_form);
    }
    
    // Mail to admin
    $admin_name = get_bloginfo('name');
    $to = get_option('admin_email');
    $from = "info@istilist.com";
    $headers = 'From: '.$from . "\r\n";
    $headers .= "Reply-To: ".get_option('admin_email')."\r\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: text/html\r\n";
    $subject = "New User registered";
    $msg ='<strong>New User registered</strong><br><br><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                <td width="45%"><strong>Store Name : </strong></td>
                <td>'.$request['store_name'].'</td>
                </tr>
                <tr>
                <td width="45%"><strong>Contact Name : </strong></td>
                <td>'.$request['contact_name'].'</td>
                </tr>
                <tr>
                <td><strong>Address : </strong></td>
                <td>'.$request['address'].'</td>
                </tr>
                <tr>
                <td><strong>Email Address : </strong></td>
                <td>'.$email_addr[0].'</td>
                </tr>
                <tr>
                <td><strong>Phone : </strong></td>
                <td>'.$request['phone_number'].'</td>
                </tr>
                <tr>
                <td><strong>Mobile Number : </strong></td>
                <td>'.$request['mobile_number'].'</td>
                </tr>
                <tr>
                <td><strong>Website : </strong></td>
                <td>'.$request['website'].'</td>
                </tr>
                <tr>
                <td></td>
                <td><a href="'.get_bloginfo('url').'/approve-user/?udi='.$new_user_id.'" style="display:inline-block; padding:8px 15px; background:#006400; color:#ffffff;" target="_blank">Approve</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.get_bloginfo('url').'/decline-user/?udi='.$new_user_id.'" style="display:inline-block; padding:8px 15px; background:#800000; color:#ffffff;" target="_blank">Decline</a></td>
                </tr>
            </table><br><br>Regards,<br>'.$admin_name;
            
    wp_mail($to, $subject, $msg, $headers);
                                                        
    wp_send_json_success(); 
}
?>