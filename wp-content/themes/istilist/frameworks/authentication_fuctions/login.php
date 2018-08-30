<?php ob_start(); ?>
<?php
if (isset($_POST['login'])) {
    global $wpdb;
    $username = $wpdb->escape($_POST['email_id']);
    $pwd = $wpdb->escape($_POST['pwd']);
    $user_status = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users WHERE user_login = %s", $username));
    if ($user_status[0]->user_status == 1) {
        $login_data = array();
        $login_data['user_login'] = $username;
        $login_data['user_password'] = $pwd;
        $login_data['remember'] = 'false';
        $user_verify = wp_signon($login_data, true);
        if (is_wp_error($user_verify)) {
            $user_verify->get_error_message();
            $errorCode = 1;
        } else {
            global $user_ID;
            if (!empty($_SESSION) && !empty($user_ID)) {
                foreach ($_SESSION as $key=>$val) {
                    if (!update_user_meta($user_ID, $key, $val)) {
                        add_user_meta($user_ID, $key, $val, true);
                    }
                }
            }
            header('Location: '.get_bloginfo('home').'/dashboard');
        }
        //exit();
    } else {
        $errorCode = 2; // invalid login details
    }
}
?>
<?php if ($errorCode == 1) {
    ?>
    <div class="errorMsg">Incorrect login details...Please try again.</div>
<?php
} ?>
<?php if ($errorCode == 2) {
        ?>
    <div class="errorMsg">Your account is not activated...Please check your mail and activate your account.</div>
<?php
    } ?>
<div class="commonForm">
    <h2 style="text-align: center;">Login</h2>
    <form id="forms" action="" method="POST">
        <div>
            <label>Email ID</label>
            <input type="text" name="email_id" />
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="pwd" />
        </div>
        <div></div>
        <div></div>
        <div><input type="submit" name="login" value="Login" /></div>
        <div class="forgot"><a href="<?php bloginfo('url') ?>/forgot-password">Forgot Password?</a></div>
    </form>
</div>
<script>
    jQuery(document).ready(function(){
        jQuery('#forms').validate({
            rules: {
                email_id: {
                    required: true,
                    email: true
                },
                pwd: {
                    required: true
                }
            },
            messages: {
                
            }
        })
    });
</script>