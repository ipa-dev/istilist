<?php /* Template Name: Register */ ?>
<?php get_header(); ?>
<?php global $options; ?>
<div class="loginBlock">
    <div class="maincontent">
        <div class="section group">
            <div class="col span_4_of_12"></div>
            <div class="col span_4_of_12">
                <div class="logo"><h1><?php echo $options['general-logo']; ?></h1></div>
                <div class="loginSection">
                    <div class="box">
                        
                        
                        <?php
                        if (isset($_POST['register'])) {
                            //require_once(TEMPLATEPATH.'/smtp/class.phpmailer.php');
                            /*function smtpmailer($to, $from, $from_name, $subject, $body) {
                            	$mail = new PHPMailer();  // create a new object
                            	$mail->IsSMTP(); // enable SMTP
                            	$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
                            	$mail->SMTPAuth = true;  // authentication enabled
                            	//$mail->SMTPSecure = $options['smtp-authentication-type']; // secure transfer enabled REQUIRED for GMail
                                $mail->SMTPSecure = 'SSL'; // secure transfer enabled REQUIRED for GMail
                            	$mail->Host = $options['smtp-host'];
                            	$mail->Port = $options['smtp-port'];
                            	$mail->Username = $options['smtp-username'];
                            	$mail->Password = $options['smtp-password'];
                            	$mail->SetFrom($from, $from_name);
                            	$mail->Subject = $subject;
                                $mail->IsHTML(true);
                            	$mail->Body = $body;
                            	$mail->AddAddress($to);
                            	if(!$mail->Send()) {
                            		$error = 'Mail error: '.$mail->ErrorInfo;
                            	} else {
                            		$error = 'Message sent!';
                            	}
                            }*/
                            
                            global $wpdb;
                            $email_addr = explode(',', $_POST['email_address']);
                            $email_addr[0];
                            if (email_exists($email_addr[0])) {
                                $errorCode = 1;
                            } else {
                                $new_user_id = wp_insert_user(
                                    array(
                                        //'user_login'		=> $_POST['email_address'],
                                        'user_login'		=> $email_addr[0],
                                        'user_pass'			=> $_POST['pwd'],
                                        'user_email'		=> $email_addr[0],
                                        'display_name'		=> $_POST['store_name'],
                                        'role'              => 'storeowner',
                                        'user_registered'	=> date('Y-m-d H:i:s')
                                    )
                                );
                                add_user_meta($new_user_id, 'contact_name', $_POST['contact_name']);
                                add_user_meta($new_user_id, 'address', $_POST['address']);
                                add_user_meta($new_user_id, 'phone_number', $_POST['phone_number']);
                                add_user_meta($new_user_id, 'mobile_number', $_POST['mobile_number']);
                                add_user_meta($new_user_id, 'website', $_POST['website']);
                                add_user_meta($new_user_id, 'security_questions', $_POST['security_questions']);
                                add_user_meta($new_user_id, 'security_answer', $_POST['security_answer']);
                                add_user_meta($new_user_id, 'city', $_POST['city']);
                                add_user_meta($new_user_id, 'state', $_POST['state']);
                                add_user_meta($new_user_id, 'zipcode', $_POST['zipcode']);
                                add_user_meta($new_user_id, 'mobile_number_optin', $_POST['mobile_number_optin']);
                                add_user_meta($new_user_id, 'reporting', $_POST['email_address']);
                                add_user_meta($new_user_id, 'store_id', $new_user_id);
                                add_user_meta($new_user_id, 'store_name', $_POST['store_name']);
                                add_user_meta($new_user_id, 'email_to_shopper', $_POST['email_to_shopper']);
                                                                                        
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
                                    
                                    $table_name2 = $wpdb->prefix.'dynamic_form';
                                    $insert_form = "INSERT INTO $table_name2 (form_display_name, form_slug, form_type, is_required, is_active, store_owner_id) VALUES ('First Name', 'customer_fname', 'text', '1', '1', '".$new_user_id."'),('Last Name', 'customer_lname', 'text', '1', '1', '".$new_user_id."'), ('Profile Picture', 'profile_pic', 'file', '1', '1', '".$new_user_id."'), ('School/Event', 'school_event', 'text', '1', '1', '".$new_user_id."'),('Graduation Year', 'graduation_year', 'select', '1', '1', '".$new_user_id."'),('Email', 'customer_email', 'text', '1', '1', '".$new_user_id."'),('Phone','customer_phone', 'text', '1', '1', '".$new_user_id."'),('Address', 'customer_address', 'text', '1', '1', '".$new_user_id."'),('City', 'customer_city', 'text','1', '1', '".$new_user_id."'),('State', 'customer_state', 'text', '1', '1', '".$new_user_id."'),('ZIP', 'customer_zip', 'text', '1', '1', '".$new_user_id."'),('Designer Preference','design_preferences', 'checkbox', '1', '1', '".$new_user_id."'),('Style Preference', 'style_preferences', 'checkbox', '1', '1', '".$new_user_id."'),('Color Preferences', 'color_preferences', 'text', '1', '1', '".$new_user_id."'),('Size', 'customer_size', 'select', '1', '1', '".$new_user_id."')";
                                    $wpdb->query($insert_form);
                                }
                                // mail to user
                                /*$to1 = $email_addr[0];
                                if($options['smtp-active'] == 1){
                                    $from1 = $options['smtp-from-email'];
                                } else {
                                    $from1 = "info@istilist.com";
                                }

                            	$headers1 = 'From: '.$from1. "\r\n";
                                $headers1 .= "Reply-To: ".get_option('admin_email')."\r\n";
                                $headers1 .= "MIME-Version: 1.0\n";
                                $headers1 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                            	$subject1 = "Activate your account";
                                $msg1 = 'Welcome to '.get_bloginfo('name').'! Please click on the link to complete the registration process<br><br>Activation Link :<a href="'.get_site_url().'/activation?key='.$key.'" target="_blank">'.get_site_url().'/activation?key='.$key.'</a><br><br>Regards,<br>'.$admin_name;

                                if($options['smtp-active'] != 1){
                                    wp_mail( $to1, $subject1, $msg1, $headers1 );
                                } else {
                                    //smtpmailer($to1, $from1, $options['smtp-from-name'], $subject1, $msg1);
                                    if (($error = smtpmailer($to1, $from1, $options['smtp-from-name'], $subject1, $msg1)) === true){
                                         echo 'Email send';
                                    } else {
                                        echo $error;
                                    }
                                }*/
                                //
                                
                                
                                
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
                                            <td>'.$_POST['store_name'].'</td>
                                          </tr>
                                          <tr>
                                            <td width="45%"><strong>Contact Name : </strong></td>
                                            <td>'.$_POST['contact_name'].'</td>
                                          </tr>
                                          <tr>
                                            <td><strong>Address : </strong></td>
                                            <td>'.$_POST['address'].'</td>
                                          </tr>
                                          <tr>
                                            <td><strong>Email Address : </strong></td>
                                            <td>'.$email_addr[0].'</td>
                                          </tr>
                                          <tr>
                                            <td><strong>Phone : </strong></td>
                                            <td>'.$_POST['phone_number'].'</td>
                                          </tr>
                                          <tr>
                                            <td><strong>Mobile Number : </strong></td>
                                            <td>'.$_POST['mobile_number'].'</td>
                                          </tr>
                                          <tr>
                                            <td><strong>Website : </strong></td>
                                            <td>'.$_POST['website'].'</td>
                                          </tr>
                                          <tr>
                                            <td></td>
                                            <td><a href="'.get_bloginfo('url').'/approve-user/?udi='.$new_user_id.'" style="display:inline-block; padding:8px 15px; background:#006400; color:#ffffff;" target="_blank">Approve</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.get_bloginfo('url').'/decline-user/?udi='.$new_user_id.'" style="display:inline-block; padding:8px 15px; background:#800000; color:#ffffff;" target="_blank">Decline</a></td>
                                          </tr>
                                        </table><br><br>Regards,<br>'.$admin_name;
                                        
                                wp_mail($to, $subject, $msg, $headers);
                                        
                                //if($options['smtp-active'] != 1){
                                            
                                /*} else {
                                    echo "SMTP";
                                    exit();
                                    smtpmailer($to, $from, $options['smtp-from-name'], $subject, $msg);
                                    if (($error = smtpmailer($to, $from, $options['smtp-from-name'], $subject, $msg)) === true){
                                         echo 'Email send';
                                    } else {
                                        echo $error;
                                    }
                                }*/
                                //
                                header("Location: ".get_bloginfo('home')."/thank-you/?action=".encripted('registration'));
                            }
                        }
                        ?>
                        <?php if ($errorCode == 1) {
                            ?>
                            <div class="errorMsg">Email address already exists. Please select different valid email address.</div>
                        <?php
                        }?>
                        <div class="commonForm">
                            <form id="forms" method="post" action="">
                                <div>
                                    <label>Store</label>
                                    <input type="text" name="store_name" />
                                </div>
                                <div>
                                    <label>Contact</label>
                                    <input type="text" name="contact_name" />
                                </div>
                                <div>
                                    <label>Address</label>
                                    <input type="text" name="address" />
                                </div>
                                <div>
                                    <label>City</label>
                                    <input type="text" name="city" />
                                </div>
                                <div>
                                    <label>State</label>
                                    <input type="text" name="state" />
                                </div>
                                <div>
                                    <label>ZIP Code</label>
                                    <input type="text" name="zipcode" />
                                </div>
                                <div>
                                    <label>Phone</label>
                                    <input type="text" name="phone_number" />
                                </div>
                                <div>
                                    <label>Mobile</label>
                                    <input type="text" name="mobile_number" />
                                </div>
                                <div>
                                    <input type="checkbox" name="mobile_number_optin" value="1" /> Yes, I want istilist texts! <a href="#inlinemsg" class="fancybox"><i class="fa fa-info-circle"></i></a>
                                    <div style="display: none;" id="inlinemsg"><p>By checking this box I agree to get up to 4 istilisttext messages per month sent via automated technology to this phone number, and by checking the box, I also agree to our Texting/Messaging Terms & Conditions. Agreeing to get istilisttext messages is not required to use istilist.  Message & data rates may apply. Reply HELP for help and STOP to stop. You may receive additional program confirmation texts.</p></div>
                                </div>
                                <div>
                                    <label>Email addresses to be used for reporting</label>
                                    <input type="text" name="email_address" />
                                    <div style="font-size: 12px;"><em>Separate multiple email addresses with a comma</em></div>
                                </div>
                                <div>
                                    <label>What email address should shopper emails be sent from?</label>
                                    <input type="text" name="email_to_shopper" />
                                <div>
                                    <label>Website</label>
                                    <input type="text" name="website" />
                                </div>
                                <div>
                                    <label>Password</label>
                                    <input type="password" name="pwd" />
                                </div>
                                <div>
                                    <label>Security question for password reset</label>
                                    <select name="security_questions">
                                        <option value="What was your childhood nickname?">What was your childhood nickname?</option>
                                        <option value="What is the name of your favorite childhood friend?">What is the name of your favorite childhood friend?</option>
                                        <option value="What street did you live on in third grade?">What street did you live on in third grade?</option>
                                        <option value="What school did you attend for sixth grade?">What school did you attend for sixth grade?</option>
                                        <option value="In what city or town was your first job?">In what city or town was your first job?</option>
                                    </select><br />
                                    <input type="text" name="security_answer" placeholder="Your answer" />
                                </div>
                                <div style="text-align: center;">
                                <!--<a class="buttonLink1" href="<?php bloginfo('url'); ?>/login">Login</a>-->
                                <input type="submit" name="register" value="REGISTER" />
                                
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col span_4_of_12"></div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function(){
    jQuery('#forms').validate({
        rules: {
            store_name: {
                required: true
            },
            contact_name: {
                required: true
            },
            phone_number: {
                required: true
            },
            email_address: {
                required: true,
                //email: true,
                multiemail: true
            },
            pwd: {
                required: true
            },
            security_answer: {
                required: true
            }
        },
        messages: {
            
        }
    })
});
</script>
<?php get_footer(); ?>