<?php /* Template Name: Approve User */ ?>
<?php get_header(); ?>
<div class="loginBlock">
    <div class="maincontent">
        <div class="section group">
            <div class="col span_3_of_12"></div>
            <div class="col span_6_of_12">
                <div class="logo"><h1><?php echo $options['general-logo']; ?></h1></div>
                <div class="loginSection">
                    <div class="box">
                        <?php if (!empty($_GET['udi'])) {
    //require_once(TEMPLATEPATH.'/smtp/class.phpmailer.php');
    global $wpdb;
    $user_status = 2;
    $key = $_GET['key'];
    $user_obj = $wpdb->get_row("SELECT * FROM $wpdb->users WHERE ID=".$_GET['udi']);
    $wpdb->print_error();
    $wpdb->update($wpdb->users, array('user_status' => $user_status), array('ID' => $_GET['udi']));
    echo '<div class="successMsg">User Approved.</div>';

    $to1 = "{$user_obj->user_email}";
    if ($options['smtp-active'] == 1) {
        $from1 = $options['smtp-from-email'];
    } else {
        $from1 = "info@istilist.com";
    }
    $headers1 = 'From: '.$from1. "\r\n";
    $headers1 .= "Reply-To: ".get_option('admin_email')."\r\n";
    $headers1 .= "MIME-Version: 1.0\n";
    $headers1 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $subject1 = "Your iSTiLiST account has been activated";
    $msg1 = 'Welcome to '.get_bloginfo('name').'! Please click on the link to login to your account.<br><br><a href="'.get_site_url().'" target="_blank">'.get_site_url().'</a><br><br>Regards,<br>'.get_bloginfo('name');
                                
    //if($options['smtp-active'] != 1){
    wp_mail($to1, $subject1, $msg1, $headers1);
    /*} else {
        //smtpmailer($to1, $from1, $options['smtp-from-name'], $subject1, $msg1);
        if (($error = smtpmailer($to1, $from1, $options['smtp-from-name'], $subject1, $msg1)) === true){
             echo 'Email send';
        } else {
            echo $error;
        }
    }*/
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