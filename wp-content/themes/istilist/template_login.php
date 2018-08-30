<?php /* Template Name: Login */ ?>
<?php get_header(); ?>
<div class="loginBlock">
    <div class="maincontent">
        <div class="section group">
            <div class="col span_4_of_12"></div>
            <div class="col span_4_of_12">
            <?php
                if (isset($_POST['login'])) {
                    global $wpdb;
                    $username = $wpdb->escape($_POST['useremail']);
                    $pwd = $wpdb->escape($_POST['pwd']);
                    $user_status = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users WHERE user_login = %s", $username));
                    if ($user_status[0]->user_status == 2) {
                        $login_data = array();
                        $login_data['user_login'] = $username;
                        $login_data['user_password'] = $pwd;
                        $login_data['remember'] = 'false';
                        $user_verify = wp_signon($login_data, true);
                        if (is_wp_error($user_verify)) {
                            $user_verify->get_error_message();
                            $errorCode = 1;
                        } else {
                            header('Location: '.get_bloginfo('home').'/dashboard');
                        }
                        //exit();
                    } else {
                        $errorCode = 2; // invalid login details
                    }
                }
                ?>
                
                <div class="logo"><h1><?php echo $options['general-logo']; ?></h1></div>
                
                <div class="loginSection">
                    <div class="box">
                        <?php if ($errorCode == 1) {
                    ?>
                            <div class="errorMsg">Incorrect login details...Please try again.</div>
                        <?php
                } ?>
                        <?php if ($errorCode == 2) {
                    ?>
                            <!-- <div class="errorMsg">Your account is not activated...Please check your mail and activate your account.</div> -->
                            <div class="infoMsg">Your istilist user account has not been activated. Please check your inbox and spam/junk folder for your approval email.</div>
                        <?php
                } ?>
                        <div class="commonForm">
                            <form method="post" action="">
                                <div>
                                    <label>Email Address</label>
                                    <input type="text" name="useremail" />
                                </div>
                                <div>
                                    <label>Password</label>
                                    <input type="password" name="pwd" />
                                </div>
                                <div style="text-align: center;"><input type="submit" name="login" value="LOGIN" /></div>
                                <div class="loginLinks">
                                    <div><a href="<?php bloginfo('url'); ?>/forgot-password">Forgot Password?</a></div>
                                    <div><a href="<?php bloginfo('url'); ?>/register">Register</a></div>
                                    <div style="clear: both;"></div>
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
<?php get_footer(); ?>