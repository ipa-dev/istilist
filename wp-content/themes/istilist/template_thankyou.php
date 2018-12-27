<?php /* Template Name: Thank You */ ?>
<?php get_header(); ?>
<div class="loginBlock">
    <div class="maincontent">
        <div class="section group">
            <div class="col span_3_of_12"></div>
            <div class="col span_6_of_12">
                <div class="logo"><h1><?php echo $options['general-logo']; ?></h1></div>
                <div class="loginSection">
                    <div class="box">
            <?php
            $action = decripted($_GET['action']);
                if ($action == 'registration') {
                    echo "<p class='successMsg'>Thank you for registering for istilist!  Please check your inbox for your approval email.</p>";
                }
                if ($action == 'forgotpassword') {
                    echo "<p class='successMsg'>Please check your registered email and click on the reset password link.</p>";
                }
                if ($action == 'resetpassword') {
                    echo '<p class="successMsg">Your password updated successfully. Please click here to <a class="alink" href="'.get_bloginfo('home').'/login">Login</a>.</p>';
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