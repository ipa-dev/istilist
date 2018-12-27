<?php /* Template Name: Activation */ ?>
<?php get_header(); ?>
<div class="loginBlock">
    <div class="maincontent">
        <div class="section group">
            <div class="col span_3_of_12"></div>
            <div class="col span_6_of_12">
                <div class="logo"><h1><?php echo $options['general-logo']; ?></h1></div>
                <div class="loginSection">
                    <div class="box">
            <?php if (!is_user_logged_in()) {
    global $wpdb;
    $user_status = 1;
    $key = $_GET['key'];
    $wpdb->update($wpdb->users, array('user_status' => $user_status), array('user_activation_key' => $key));
    echo '<div class="successMsg"><strong>Congratulations</strong><br /><br />Your account has been activated. Click here to <strong><a href="'.get_bloginfo('home').'/login">Login</a></strong></div>';
}
                
                
                
 
            ?>
                    </div>
                </div>
            </div>
            <div class="col span_3_of_12"></div>
        </div>
    </div>
</div>
<?php get_footer(); ?>