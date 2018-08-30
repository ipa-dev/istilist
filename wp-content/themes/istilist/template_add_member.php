<?php /* Template Name: Add Member */ ?>
<?php get_header(); ?>
<div class="loginBlock">
    <div class="maincontent">
        <div class="section group">
            <div class="col span_4_of_12"></div>
            <div class="col span_4_of_12">
                <?php
                    $key = $_GET['key'];
                    if (isset($_POST['add_member']) && !empty($key)) {
                        global $wpdb;
                        $user_status = 1;
                        $wpdb->update($wpdb->users, array('user_status' => $user_status), array('user_activation_key' => $key));
                        
                        $user_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users WHERE user_activation_key = %s", $key));
                        $userid = $user_data[0]->ID;

                        $pwd = $_POST['pwd1'];
                        wp_set_password($pwd, $userid);
                        echo '<div class="successMsg"><strong>Congratulations</strong><br /><br />Your account has been activated. Click here to <strong><a href="'.get_bloginfo('home').'/login">Login</a></strong></div>';
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
                            <div class="errorMsg">Your account is not activated...Please check your mail and activate your account.</div>
                        <?php
                } ?>
                        <div class="commonForm">
                            <form id="forms" method="post" action="">
                                <div>
                                    <label>Password</label>
                                    <input type="password" name="pwd1" id="pwd1" />
                                </div>
                                <div>
                                    <label>Confirm Password</label>
                                    <input type="password" name="pwd2" id="pwd2" />
                                </div>
                                <div style="text-align: center;"><input type="submit" name="add_member" value="JOIN" /></div>
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
            pwd1: {
                required: true,
                minlength : 8
            },
            pwd2: {
                equalTo: '#pwd1',
                minlength : 8
            }
        },
        messages: {
            
        }
    })
});
</script>
<?php get_footer(); ?>