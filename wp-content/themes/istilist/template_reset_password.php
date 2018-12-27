<?php /* Template Name: Reset Password */ ?>
<?php get_header(); ?>
<div class="loginBlock">
    <div class="maincontent">
        <div class="section group">
            <div class="col span_4_of_12"></div>
            <div class="col span_4_of_12">
                <?php
                    $key = $_GET['action'];
                    if (isset($_POST['Submit']) && !empty($key)) {
                        $user = get_user_by('email', $key);
                        $pwd = $_POST['pwd1'];
                        //$user_data = $wpdb->get_row($wpdb->prepare("SELECT ID  FROM $wpdb->users WHERE user_email = %s", $key));
                        wp_set_password($pwd, $user->ID);
                        header("Location: ".get_bloginfo('home')."/thank-you/?action=".encripted('resetpassword'));
                    }
                ?>
                <div class="logo"><h1><?php echo $options['general-logo']; ?></h1></div>
                <div class="loginSection">
                    <div class="box">
                        <div class="commonForm">
                            <form id="reset" method="post" action="">
                                <div>
                                    <label>Password</label>
                                    <input type="password" name="pwd1" id="pwd1" />
                                </div>
                                <div>
                                    <label>Confirm Password</label>
                                    <input type="password" name="pwd2" id="pwd2" />
                                </div>
                                <div class="submitBtn"><input type="submit" name="Submit" id="Submit" value="Reset" /></div>
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
    jQuery('#reset').validate({
        rules: {
            pwd1:{
                required: true,
                minlength: 8
            },
            pwd2:{
                required: true,
                minlength: 8,
                equalTo: '#pwd1'
            }
        },
        messages: {
            
        }
    })
});
</script>
<?php get_footer(); ?>