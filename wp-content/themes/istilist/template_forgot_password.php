<?php /* Template Name: Forgot Password */ ?>
<?php get_header(); ?>
<div class="loginBlock">
    <div class="maincontent">
        <div class="section group">
            <div class="col span_4_of_12"></div>
            <div class="col span_4_of_12">
                <?php
                    global $wpdb; global $user_ID;
                    if (empty($user_ID)) {
                        if (isset($_POST['sendid'])) {
                            $user_login = $_POST['userid'];
                            $user = get_user_by('email', $user_login);
                            //print_r($user->ID);
                            $security_questions = get_user_meta($user->ID, 'security_questions', true);
                            $security_answer = get_user_meta($user->ID, 'security_answer', true);
                            
                            if (($security_questions == $_POST['security_questions']) && ($security_answer == $_POST['security_answer'])) {
                                $from = get_option('admin_email');
                                $headers = "From: $from";
                                $subject = "Reset Password";
                                $headers .= "MIME-Version: 1.0\n";
                                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                                $useremail = $user->user_email;
                                $reseturl = get_bloginfo('url');
                                $emailmsg = "Please click on the following link to reset your password <a href='$reseturl/reset-password/?action=$useremail'>Reset Password</a>";
                                wp_mail($useremail, $subject, $emailmsg, $headers);
                                //wp_mail( 'bhulbhal1981@gmail.com', $subject, $emailmsg, $headers );
                                $sucess= 'Please check your registered email and click on the reset password link.';
                                header("Location: ".get_bloginfo('home')."/thank-you/?action=".encripted('forgotpassword'));
                            //}
                            } else {
                                $errorCode = 1;
                            }
                        }
                    } else {
                        header('Location:'.get_bloginfo('home').'/forgot-password');
                    }
                ?>
                <div class="logo"><h1><?php echo $options['general-logo']; ?></h1></div>
                <div class="loginSection">
                    <div class="box">
                        
                        <div class="commonForm">
                            <?php if ($errorCode == 1) {
                    echo '<p class="errorMsg">Authentication Failed. Please try again.</p>';
                } ?>
                            <form id="forms" method="post" action="">
                                <div>
                                    <label>Registered Email Address</label>
                                    <input type="text" name="userid" />
                                </div>
                                <div>
                                    <label>Security Question</label>
                                    <select name="security_questions">
                                        <option value="What was your childhood nickname?">What was your childhood nickname?</option>
                                        <option value="What is the name of your favorite childhood friend?">What is the name of your favorite childhood friend?</option>
                                        <option value="What street did you live on in third grade?">What street did you live on in third grade?</option>
                                        <option value="What school did you attend for sixth grade?">What school did you attend for sixth grade?</option>
                                        <option value="In what city or town was your first job?">In what city or town was your first job?</option>
                                    </select><br />
                                    <input type="text" name="security_answer" placeholder="Your answer" />
                                </div>
                                <div><input type="submit" name="sendid" value="Send Request" class="fullwidthBtn" /></div>
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
            userid: {
                required: true,
                email: true
            }
        },
        messages: {
            
        }
    })
});
</script>
<?php get_footer(); ?>