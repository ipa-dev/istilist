<?php /* Template Name: Declien User */ ?>
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
                            if(!empty($_GET['udi'])){ 
                                global $wpdb;
                                require_once(ABSPATH.'wp-admin/includes/user.php' );
                                wp_delete_user($_GET['udi']);
                                echo '<div class="successMsg">User Declined.</div>';
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