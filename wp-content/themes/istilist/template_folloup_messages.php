<?php /* Template Name: Folloup Messages */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    ?>
<?php global $user_ID;
    global $wpdb; ?>
<?php $store_owner_id = get_user_meta($user_ID, 'store_id', true); ?>
<?php $store_id = get_user_meta($user_ID, 'store_id', true); ?>
<?php $user_role = get_user_role($user_ID); ?>
<?php
  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php'); ?>

<?php $table_name = $wpdb->prefix.'folloup_messages'; ?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <h1><?php the_title(); ?></h1>
                    <div class="reportBox">
                        <h3>Thank You Email Template</h3>
                        <?php
                            if (isset($_POST['thankyou_template'])) {
                                $update_query = "UPDATE $table_name SET subject = '".$_POST['thankyou_subject']."', body = '".nl2br($_POST['thankyou_email_body'])."' WHERE message_type = 'thankyou' and store_id = $store_id";
                                $update = $wpdb->query($update_query);
                                if ($update == 1) {
                                    echo '<p class="successMsg">Your email template is updated successfully.</p>';
                                } else {
                                    echo '<p class="successMsg">Some thing goes wrong.</p>';
                                }
                            }
    $sql1 = "SELECT * FROM $table_name WHERE message_type = 'thankyou' and store_id = $store_id";
    $result1 = $wpdb->get_row($sql1); ?>
                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="section group">
                                <div class="col span_2_of_12">Subject</div>
                                <div class="col span_10_of_12"><input type="text" name="thankyou_subject" value="<?php echo $result1->subject; ?>" /></div>
                            </div>
                            <div class="section group">
                                <div class="col span_2_of_12">Email Body</div>
                                <div class="col span_10_of_12">
                                <?php
                                    $settings1 = array(
                                        'wpautop' => true,
                                        'media_buttons' => true,
                                        'textarea_name' => 'thankyou_email_body',
                                        'textarea_rows' => 40,
                                        'tabindex' => '',
                                        'editor_css' => '',
                                        'editor_class' => 'msgClass',
                                        'teeny' => false,
                                        'dfw' => true,
                                        'tinymce' => true,
                                        'quicktags' => true,
                                        'drag_drop_upload' => true
                                    );

    wp_editor($result1->body, 'thankyou_email_body', $settings1); ?>
                                <div class="allowedtag">
                                    <em>{Shopper's Name}</em> for auto generated shopper's name<br />
                                    <em>{Stylist's Name}</em> for auto generated stylist's name
                                </div>
                            </div>

                            <div class="section group">
                                <div class="col span_12_of_12">
                                    <div class="alignright">
                                        <input type="submit" name="thankyou_template" value="Update Template" />
                                    </div>
                                </div>
                            </div>
                            </div>
                        </form>
                    </div>
                    <div class="reportBox">
                        <h3>Promo Email Template</h3>
                        <?php
                            if (isset($_POST['promo_template'])) {
                                $update_query = "UPDATE $table_name SET subject = '".$_POST['promo_subject']."', body = '".$_POST['promo_email_body']."' WHERE message_type = 'promo' and store_id = $store_id";
                                $update = $wpdb->query($update_query);
                                if ($update == 1) {
                                    echo '<p class="successMsg">Your email template is updated successfully.</p>';
                                } else {
                                    echo '<p class="successMsg">Some thing goes wrong.</p>';
                                }
                            }
    $sql3 = "SELECT * FROM $table_name WHERE message_type = 'promo' and store_id = $store_id";
    $result3 = $wpdb->get_row($sql3); ?>
                        <form method="post" action="">
                            <div class="section group">
                                <div class="col span_2_of_12">Subject</div>
                                <div class="col span_10_of_12"><input type="text" name="promo_subject" value="<?php echo $result3->subject; ?>" /></div>
                            </div>
                            <div class="section group">
                                <div class="col span_2_of_12">Email Body</div>
                                <div class="col span_10_of_12">
                                <?php
                                    $settings3 = array(
                                        'wpautop' => true,
                                        'media_buttons' => true,
                                        'textarea_name' => 'promo_email_body',
                                        'textarea_rows' => 40,
                                        'tabindex' => '',
                                        'editor_css' => '',
                                        'editor_class' => 'msgClass',
                                        'teeny' => false,
                                        'dfw' => true,
                                        'tinymce' => true,
                                        'quicktags' => true,
                                        'drag_drop_upload' => true
                                    ); ?>

                                <?php wp_editor($result3->body, 'promo_email_body', $settings3); ?>
                                <div class="allowedtag">
                                    <em>{Shopper's Name}</em> for auto generated shopper's name<br />
                                    <em>{Stylist's Name}</em> for auto generated stylist's name
                                </div>
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                    <div class="alignright">
                                        <input type="submit" name="promo_template" value="Update Template" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="reportBox">
                      <h3>Send Email to all Shoppers</h3>
                      <form name='shopper_email_form' id='shopper_email_form' method='post' action='<?php bloginfo('url'); ?>/send-all-shoppers-email'>
                        <div class="section group">
                            <div class="col span_2_of_12">Subject</div>
                            <div class="col span_10_of_12"><input type="text" name="shopper_email_subject" value=""/></div>
                        </div>
                        <div class="section group">
                            <div class="col span_2_of_12">Email Body</div>
                            <div class="col span_10_of_12">
                            <?php
                                $settings3 = array(
                                    'wpautop' => true,
                                    'media_buttons' => true,
                                    'textarea_name' => 'shopper_email_body',
                                    'textarea_rows' => 40,
                                    'tabindex' => '',
                                    'editor_css' => '',
                                    'editor_class' => 'msgClass',
                                    'teeny' => false,
                                    'dfw' => true,
                                    'tinymce' => true,
                                    'quicktags' => true,
                                    'drag_drop_upload' => true
                                ); ?>

                            <?php wp_editor('', 'shopper_email_body', $settings3); ?>
                            <div class="allowedtag">
                                <em>{Shopper's Name}</em> for auto generated shopper's name<br />
                                <em>{Stylist's Name}</em> for auto generated stylist's name
                            </div>
                            </div>
                        </div>
                        <div class="section group">
                            <div class="col span_12_of_12">
                                <div class="alignright">
                                    <input type="submit" name="shopper_email_template" value="Send Test E-mail" />
                                    <input type="submit" name="shopper_email_template" value="Send E-mail" />
                                </div>
                            </div>
                        </div>

                      </form>
                    </div>
                    <div class="reportBox">
                        <h3>Thank You Text Template</h3>
                        <?php
                            if (isset($_POST['thankyou_text_template'])) {
                                $update_query = "UPDATE $table_name SET  body = '".$_POST['thankyou_text_body']."' WHERE message_type = 'thankyoutext' and store_id = $store_id";
                                $update = $wpdb->query($update_query);
                                if ($update == 1) {
                                    echo '<p class="successMsg">Your text template is updated successfully.</p>';
                                } else {
                                    echo '<p class="successMsg">Some thing goes wrong.</p>';
                                }
                            }
    $sql4 = "SELECT * FROM $table_name WHERE message_type='thankyoutext' AND store_id = $store_id";
    $result4 = $wpdb->get_row($sql4); ?>
                        <form id='thankyou_text_form' method="post" action="">
                            <div class="section group">
                                <div class="col span_2_of_12">Message Body</div>
                                <div class="col span_10_of_12">
                                    <textarea form='thankyou_text_form' name="thankyou_text_body"><?php echo $result4->body; ?></textarea>
                                    <div class="allowedtag">
                                    <em>{Shopper's Name}</em> for auto generated shopper's name<br />
                                    <em>{Stylist's Name}</em> for auto generated stylist's name
                                </div>
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                    <div class="alignright">
                                        <input type="submit" name="thankyou_text_template" value="Update Template" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="reportBox">
                        <h3>Promo Text Template</h3>
                        <?php
                            if (isset($_POST['promo_text_template'])) {
                                $update_query = "UPDATE $table_name SET body = '".$_POST['promo_text_body']."' WHERE message_type = 'promotext' and store_id = $store_id";
                                $update = $wpdb->query($update_query);
                                if ($update == 1) {
                                    echo '<p class="successMsg">Your text template is updated successfully.</p>';
                                } else {
                                    echo '<p class="successMsg">Some thing goes wrong.</p>';
                                }
                            }
    $sql5 = "SELECT * FROM $table_name WHERE message_type='promotext' AND store_id = $store_id";
    $result5 = $wpdb->get_row($sql5); ?>
                        <form id="promo_text_form" method="post" action="">
                            <div class="section group">
                                <div class="col span_2_of_12">Message Body</div>
                                <div class="col span_10_of_12">
                                    <textarea form='promo_text_form' name='promo_text_body'><?php echo $result5->body; ?></textarea>
                                    <div class="allowedtag">
                                        <em>{Shopper's Name}</em> for auto generated shopper's name<br />
                                        <em>{Stylist's Name}</em> for auto generated stylist's name
                                    </div>
                                </div>

                            </div>
                            <div class="section group">
                                <div class="col span_12_of_12">
                                    <div class="alignright">
                                        <input type="submit" name="promo_text_template" value="Update Template" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php get_footer(); ?>
	        </div>
	    </div>
	</div>
</div>
<?php
} else {
        header('Location: '.get_bloginfo('url').'/login');
    } ?>
