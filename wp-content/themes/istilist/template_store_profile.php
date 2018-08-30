<?php /* Template Name: Store Profile */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    ?>
<?php global $user_ID; ?>
<?php get_header(); ?>
<?php $user_reverse_order = get_user_meta($user_ID, 'reverse_order', true); ?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <h1><?php the_title(); ?></h1>
                    <div class="box">
                        <?php

                            if (isset($_POST['update_store_profile'])) {
                                wp_update_user(array( 'ID' => $user_ID, 'display_name' => $_POST['store_name'] ));
                                update_user_meta($user_ID, 'contact_name', $_POST['contact_name']);
                                update_user_meta($user_ID, 'address', $_POST['address']);
                                update_user_meta($user_ID, 'phone_number', $_POST['phone_number']);
                                update_user_meta($user_ID, 'mobile_number', $_POST['mobile_number']);
                                update_user_meta($user_ID, 'website', $_POST['website']);
                                update_user_meta($user_ID, 'security_questions', $_POST['security_questions']);
                                update_user_meta($user_ID, 'security_answer', $_POST['security_answer']);
                                update_user_meta($user_ID, 'city', $_POST['city']);
                                update_user_meta($user_ID, 'state', $_POST['state']);
                                update_user_meta($user_ID, 'zipcode', $_POST['zipcode']);
                                update_user_meta($user_ID, 'reporting', $_POST['email_address']);
                                update_user_meta($user_ID, 'selecttimezone', $_POST['selecttimezone']);
                                update_user_meta($user_ID, 'profile_pic_on_off', $_POST['profile_pic_on_off']);
                                update_user_meta($user_ID, 'email_to_shopper', $_POST['email_to_shopper']);
                                
                                if (!empty($user_reverse_order) || $user_reverse_order == null) {
                                    update_user_meta($user_ID, 'reverse_order', $_POST['reverse_order']);
                                } else {
                                    add_user_meta($user_ID, 'reverse_order', $_POST['reverse_order']);
                                }
                                $user_daily_text_promo = get_user_meta($user_ID, 'daily_promo_text', true);
                                if (!empty($user_daily_text_promo)) {
                                    update_user_meta($user_ID, 'daily_promo_text', $_POST['daily_promo_text']);
                                } else {
                                    add_user_meta($user_ID, 'daily_promo_text', $_POST['daily_promo_text']);
                                }
                                
                                                                                               
                                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                                require_once(ABSPATH . "wp-admin" . '/includes/file.php');
                                require_once(ABSPATH . "wp-admin" . '/includes/media.php');
                                
                                $image = $_FILES['profile_pic'];
                                if ($image['size']) {     // if it is an image
                                    if (preg_match('/(jpg|jpeg|png|gif)$/', $image['type'])) {
                                        $override = array('test_form' => false);       // save the file, and store an array, containing its location in $file
                                        $file = wp_handle_upload($image, $override);
                                        $attachment = array(
                                            'post_title' => $image['name'],
                                            'post_content' => '',
                                            'post_type' => 'attachment',
                                            'post_mime_type' => $image['type'],
                                            'guid' => $file['url']
                                        );
                                        
                                        $attach_id = wp_insert_attachment($attachment, $file[ 'file' ], $user_ID);
                                        $attach_data = wp_generate_attachment_metadata($attach_id, $file['file']);
                                        wp_update_attachment_metadata($attach_id, $attach_data);
                                        if (!update_user_meta($user_ID, 'profile_pic', $attach_id)) {
                                            add_user_meta($user_ID, 'profile_pic', $attach_id);
                                        }
                                    } else {
                                        wp_die('No image was uploaded.');
                                    }
                                }
                                
                                if (!empty($_POST['pwd'])) {
                                    wp_set_password($_POST['pwd'], $user_ID);
                                }
                                echo '<p class="successMsg">Your store profile updated.</p>';
                                header('Location: '.get_header('url').'/store-profile/');
                            } ?>
                        <form id="forms" method="post" action="" enctype="multipart/form-data">
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Store</label>
                                    <input type="text" name="store_name" value="<?php echo get_the_author_meta('display_name', $user_ID); ?>" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Contact</label>
                                    <input type="text" name="contact_name" value="<?php echo get_user_meta($user_ID, 'contact_name', true); ?>" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Address</label>
                                    <input type="text" name="address" value="<?php echo get_user_meta($user_ID, 'address', true); ?>" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Phone</label>
                                    <input type="text" name="phone_number" value="<?php echo get_user_meta($user_ID, 'phone_number', true); ?>" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Mobile</label>
                                    <input type="text" name="mobile_number" value="<?php echo get_user_meta($user_ID, 'mobile_number', true); ?>" />
                                    <input type="checkbox" name="mobile_number_optin" value="<?php echo get_user_meta($user_ID, 'mobile_number_optin', true); ?>" /> Yes, I want istilist texts!
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Email</label>
                                    <input type="text" name="email_address" value="<?php echo get_the_author_meta('reporting', $user_ID); ?>" />
                                    <div class="divnote">Separate multiple email addresses with a comma.</div>
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>City</label>
                                    <input type="text" name="city" value="<?php echo get_user_meta($user_ID, 'city', true); ?>" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>State</label>
                                    <input type="text" name="state" value="<?php echo get_user_meta($user_ID, 'state', true); ?>" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Zip Code</label>
                                    <input type="text" name="zipcode" value="<?php echo get_user_meta($user_ID, 'zipcode', true); ?>" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Website</label>
                                    <input type="text" name="website" value="<?php echo get_user_meta($user_ID, 'website', true); ?>" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Password</label>
                                    <input type="password" name="pwd" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Security question</label>
                                    <select name="security_questions">
                                        <option value="What was your childhood nickname?">What was your childhood nickname?</option>
                                        <option value="What is the name of your favorite childhood friend?">What is the name of your favorite childhood friend?</option>
                                        <option value="What street did you live on in third grade?">What street did you live on in third grade?</option>
                                        <option value="What school did you attend for sixth grade?">What school did you attend for sixth grade?</option>
                                        <option value="In what city or town was your first job?">In what city or town was your first job?</option>
                                    </select>
                                </div>
                            </div>
                            <div class="section group">
                                
                                <div class="col span_6_of_12">
                                    <label>Your answer</label>
                                    <input type="text" name="security_answer" value="<?php echo get_user_meta($user_ID, 'security_answer', true); ?>" />
                                </div>
                                <div class="col span_6_of_12">
                                <label>Your Timezone</label>
                                <select name="selecttimezone">
                                    <option value="">Select Timezone</option>
                                    <?php $timezones = array(
                                                '(UTC-11:00) Midway Island' => 'Pacific/Midway',
                                                '(UTC-11:00) Samoa' => 'Pacific/Samoa',
                                                '(UTC-10:00) Hawaii' => 'Pacific/Honolulu',
                                                '(UTC-09:00) Alaska' => 'US/Alaska',
                                                '(UTC-08:00) Pacific Time (US &amp; Canada)' => 'America/Los_Angeles',
                                                '(UTC-08:00) Tijuana' => 'America/Tijuana',
                                                '(UTC-07:00) Arizona' => 'US/Arizona',
                                                '(UTC-07:00) Chihuahua' => 'America/Chihuahua',
                                                '(UTC-07:00) La Paz' => 'America/Chihuahua',
                                                '(UTC-07:00) Mazatlan' => 'America/Mazatlan',
                                                '(UTC-07:00) Mountain Time (US &amp; Canada)' => 'US/Mountain',
                                                '(UTC-06:00) Central America' => 'America/Managua',
                                                '(UTC-06:00) Central Time (US &amp; Canada)' => 'US/Central',
                                                '(UTC-06:00) Guadalajara' => 'America/Mexico_City',
                                                '(UTC-06:00) Mexico City' => 'America/Mexico_City',
                                                '(UTC-06:00) Monterrey' => 'America/Monterrey',
                                                '(UTC-06:00) Saskatchewan' => 'Canada/Saskatchewan',
                                                '(UTC-05:00) Bogota' => 'America/Bogota',
                                                '(UTC-05:00) Eastern Time (US &amp; Canada)' => 'US/Eastern',
                                                '(UTC-05:00) Indiana (East)' => 'US/East-Indiana',
                                                '(UTC-05:00) Lima' => 'America/Lima',
                                                '(UTC-05:00) Quito' => 'America/Bogota',
                                                '(UTC-04:00) Atlantic Time (Canada)' => 'Canada/Atlantic',
                                                '(UTC-04:30) Caracas' => 'America/Caracas',
                                                '(UTC-04:00) La Paz' => 'America/La_Paz',
                                                '(UTC-04:00) Santiago' => 'America/Santiago',
                                                '(UTC-03:30) Newfoundland' => 'Canada/Newfoundland',
                                                '(UTC-03:00) Brasilia' => 'America/Sao_Paulo',
                                                '(UTC-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
                                                '(UTC-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
                                                '(UTC-03:00) Greenland' => 'America/Godthab',
                                                '(UTC-02:00) Mid-Atlantic' => 'America/Noronha',
                                                //'(UTC-01:00) Azores' => 'Atlantic/Azores',
                                                //'(UTC-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
                                                //'(UTC+00:00) Casablanca' => 'Africa/Casablanca',
                                                //'(UTC+00:00) Edinburgh' => 'Europe/London',
                                                //'(UTC+00:00) Greenwich Mean Time : Dublin' => 'Etc/Greenwich',
                                                //'(UTC+00:00) Lisbon' => 'Europe/Lisbon',
                                                //'(UTC+00:00) London' => 'Europe/London',
                                               // '(UTC+00:00) Monrovia' => 'Africa/Monrovia',
                                                '(UTC+00:00) UTC' => 'UTC',
                                                //'(UTC+01:00) Amsterdam' => 'Europe/Amsterdam',
                                                //'(UTC+01:00) Belgrade' => 'Europe/Belgrade',
                                                //'(UTC+01:00) Berlin' => 'Europe/Berlin',
                                                //'(UTC+01:00) Bern' => 'Europe/Berlin',
                                                //'(UTC+01:00) Bratislava' => 'Europe/Bratislava',
                                                //'(UTC+01:00) Brussels' => 'Europe/Brussels',
                                                //'(UTC+01:00) Budapest' => 'Europe/Budapest',
                                                //'(UTC+01:00) Copenhagen' => 'Europe/Copenhagen',
                                                //'(UTC+01:00) Ljubljana' => 'Europe/Ljubljana',
                                                //'(UTC+01:00) Madrid' => 'Europe/Madrid',
                                                //'(UTC+01:00) Paris' => 'Europe/Paris',
                                                //'(UTC+01:00) Prague' => 'Europe/Prague',
                                                //'(UTC+01:00) Rome' => 'Europe/Rome',
                                                //'(UTC+01:00) Sarajevo' => 'Europe/Sarajevo',
                                                //'(UTC+01:00) Skopje' => 'Europe/Skopje',
                                                //'(UTC+01:00) Stockholm' => 'Europe/Stockholm',
                                                //'(UTC+01:00) Vienna' => 'Europe/Vienna',
                                                //'(UTC+01:00) Warsaw' => 'Europe/Warsaw',
                                                //'(UTC+01:00) West Central Africa' => 'Africa/Lagos',
                                               // '(UTC+01:00) Zagreb' => 'Europe/Zagreb',
                                                //'(UTC+02:00) Athens' => 'Europe/Athens',
                                                //'(UTC+02:00) Bucharest' => 'Europe/Bucharest',
                                                //'(UTC+02:00) Cairo' => 'Africa/Cairo',
                                                //'(UTC+02:00) Harare' => 'Africa/Harare',
                                                //'(UTC+02:00) Helsinki' => 'Europe/Helsinki',
                                               // '(UTC+02:00) Istanbul' => 'Europe/Istanbul',
                                                //'(UTC+02:00) Jerusalem' => 'Asia/Jerusalem',
                                                //'(UTC+02:00) Kyiv' => 'Europe/Helsinki',
                                                //'(UTC+02:00) Pretoria' => 'Africa/Johannesburg',
                                                //'(UTC+02:00) Riga' => 'Europe/Riga',
                                                //'(UTC+02:00) Sofia' => 'Europe/Sofia',
                                                //'(UTC+02:00) Tallinn' => 'Europe/Tallinn',
                                               // '(UTC+02:00) Vilnius' => 'Europe/Vilnius',
                                                //'(UTC+03:00) Baghdad' => 'Asia/Baghdad',
                                               // '(UTC+03:00) Kuwait' => 'Asia/Kuwait',
                                                //'(UTC+03:00) Minsk' => 'Europe/Minsk',
                                                //'(UTC+03:00) Nairobi' => 'Africa/Nairobi',
                                                //'(UTC+03:00) Riyadh' => 'Asia/Riyadh',
                                                //'(UTC+03:00) Volgograd' => 'Europe/Volgograd',
                                                //'(UTC+03:30) Tehran' => 'Asia/Tehran',
                                                //'(UTC+04:00) Abu Dhabi' => 'Asia/Muscat',
                                                //'(UTC+04:00) Baku' => 'Asia/Baku',
                                                //'(UTC+04:00) Moscow' => 'Europe/Moscow',
                                                //'(UTC+04:00) Muscat' => 'Asia/Muscat',
                                               // '(UTC+04:00) St. Petersburg' => 'Europe/Moscow',
                                                //'(UTC+04:00) Tbilisi' => 'Asia/Tbilisi',
                                                //'(UTC+04:00) Yerevan' => 'Asia/Yerevan',
                                                //'(UTC+04:30) Kabul' => 'Asia/Kabul',
                                                //'(UTC+05:00) Islamabad' => 'Asia/Karachi',
                                                //'(UTC+05:00) Karachi' => 'Asia/Karachi',
                                                //'(UTC+05:00) Tashkent' => 'Asia/Tashkent',
                                                //'(UTC+05:30) Chennai' => 'Asia/Calcutta',
                                                //'(UTC+05:30) Kolkata' => 'Asia/Kolkata',
                                                //'(UTC+05:30) Mumbai' => 'Asia/Calcutta',
                                                //'(UTC+05:30) New Delhi' => 'Asia/Calcutta',
                                                //'(UTC+05:30) Sri Jayawardenepura' => 'Asia/Calcutta',
                                                //'(UTC+05:45) Kathmandu' => 'Asia/Katmandu',
                                                //'(UTC+06:00) Almaty' => 'Asia/Almaty',
                                                //'(UTC+06:00) Astana' => 'Asia/Dhaka',
                                                //'(UTC+06:00) Dhaka' => 'Asia/Dhaka',
                                                //'(UTC+06:00) Ekaterinburg' => 'Asia/Yekaterinburg',
                                                //'(UTC+06:30) Rangoon' => 'Asia/Rangoon',
                                                //'(UTC+07:00) Bangkok' => 'Asia/Bangkok',
                                                //'(UTC+07:00) Hanoi' => 'Asia/Bangkok',
                                                //'(UTC+07:00) Jakarta' => 'Asia/Jakarta',
                                                //'(UTC+07:00) Novosibirsk' => 'Asia/Novosibirsk',
                                                //'(UTC+08:00) Beijing' => 'Asia/Hong_Kong',
                                                //'(UTC+08:00) Chongqing' => 'Asia/Chongqing',
                                                //'(UTC+08:00) Hong Kong' => 'Asia/Hong_Kong',
                                                //'(UTC+08:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
                                                //'(UTC+08:00) Kuala Lumpur' => 'Asia/Kuala_Lumpur',
                                                //'(UTC+08:00) Perth' => 'Australia/Perth',
                                                //'(UTC+08:00) Singapore' => 'Asia/Singapore',
                                               // '(UTC+08:00) Taipei' => 'Asia/Taipei',
                                                //'(UTC+08:00) Ulaan Bataar' => 'Asia/Ulan_Bator',
                                                //'(UTC+08:00) Urumqi' => 'Asia/Urumqi',
                                                //'(UTC+09:00) Irkutsk' => 'Asia/Irkutsk',
                                                //'(UTC+09:00) Osaka' => 'Asia/Tokyo',
                                                //'(UTC+09:00) Sapporo' => 'Asia/Tokyo',
                                               // '(UTC+09:00) Seoul' => 'Asia/Seoul',
                                                //'(UTC+09:00) Tokyo' => 'Asia/Tokyo',
                                                //'(UTC+09:30) Adelaide' => 'Australia/Adelaide',
                                                ///'(UTC+09:30) Darwin' => 'Australia/Darwin',
                                                //'(UTC+10:00) Brisbane' => 'Australia/Brisbane',
                                                //'(UTC+10:00) Canberra' => 'Australia/Canberra',
                                                //'(UTC+10:00) Guam' => 'Pacific/Guam',
                                                //'(UTC+10:00) Hobart' => 'Australia/Hobart',
                                                //'(UTC+10:00) Melbourne' => 'Australia/Melbourne',
                                                //'(UTC+10:00) Port Moresby' => 'Pacific/Port_Moresby',
                                                //'(UTC+10:00) Sydney' => 'Australia/Sydney',
                                                //'(UTC+10:00) Yakutsk' => 'Asia/Yakutsk',
                                                //'(UTC+11:00) Vladivostok' => 'Asia/Vladivostok',
                                                //'(UTC+12:00) Auckland' => 'Pacific/Auckland',
                                                //'(UTC+12:00) Fiji' => 'Pacific/Fiji',
                                                //'(UTC+12:00) International Date Line West' => 'Pacific/Kwajalein',
                                                //'(UTC+12:00) Kamchatka' => 'Asia/Kamchatka',
                                                //'(UTC+12:00) Magadan' => 'Asia/Magadan',
                                                //'(UTC+12:00) Marshall Is.' => 'Pacific/Fiji',
                                                //'(UTC+12:00) New Caledonia' => 'Asia/Magadan',
                                                //'(UTC+12:00) Solomon Is.' => 'Asia/Magadan',
                                                //'(UTC+12:00) Wellington' => 'Pacific/Auckland',
                                                //'(UTC+13:00) Nuku\'alofa' => 'Pacific/Tongatapu'
                                            ); ?>
                                    <?php foreach ($timezones as $key=>$t) {
                                                ?>
                                    <option value="<?php echo $t; ?>" <?php if ($t== get_user_meta($user_ID, 'selecttimezone', true)) {
                                                    echo 'selected="selected"';
                                                } ?>><?php echo $key; ?></option>
                                    <?php
                                            } ?>
                                </select>
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Email ID to Shoppers</label>
                                    <input type="text" name="email_to_shopper" value="<?php echo get_user_meta($user_ID, 'email_to_shopper', true); ?>" />
                                </div>
                                
                            </div>
                            
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Profile Picture ON/OFF</label>
                                    <input type="radio" name="profile_pic_on_off" value="1" <?php if (get_user_meta($user_ID, 'profile_pic_on_off', true) == 1) {
                                                echo 'checked="checked"';
                                            } ?> /> ON
                                    <input type="radio" name="profile_pic_on_off" value="0" <?php if (get_user_meta($user_ID, 'profile_pic_on_off', true) == 0) {
                                                echo 'checked="checked"';
                                            } ?> /> OFF
                                </div>
                                <div class="col span_6_of_12 matchheight">
                                <?php if (get_user_meta($user_ID, 'profile_pic_on_off', true) == 1) {
                                                ?>
                                    <label>Profile Picture</label>
                                    <input type="file" name="profile_pic" />
                                    <?php //echo get_store_img($user_ID);?>
                                <?php
                                            } ?>
                                </div>
                            </div>
                            <div class="section group">
                            	<div class="col span_6_of_12">
                            		<label>Check box to show shoppers in reverse order</label>
                            		<input type="checkbox" name="reverse_order" <?php if (!empty($user_reverse_order) && $user_reverse_order=='on') {
                                                echo 'checked="checked"';
                                            } ?>/>
                            	</div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                            	    <label>Type in a promo message that you want to be sent approximately 20 minutes after a shopper is registered. Type 'NA' if you do not want a daily text promo.</label>
                                    <textarea form="forms" name="daily_promo_text" id="daily_promo_text" maxlength="160" ><?php $daily_promo_text = get_user_meta($user_ID, 'daily_promo_text', true);
    if (!empty($daily_promo_text)) {
        echo $daily_promo_text;
    } else {
        echo 'NA';
    } ?></textarea>
                                    <div id="textarea_feedback"></div>
                                    <a href="http://istilist.com/test-promo-text" class="custom_button">Send Test</a>
                            	</div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12"></div>
                                <div class="col span_6_of_12">
                                    <div style="text-align: right;">
                                        <input type="submit" name="update_store_profile" value="Update" />
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
<script>
jQuery(document).ready(function(){
    jQuery('#forms').validate({
        rules: {
            store_name: {
                required: true
            },
            contact_name: {
                required: true
            },
            phone_number: {
                required: true
            },
            email_address: {
                required: true,
                multiemail: true
            },
            security_answer: {
                required: true
            }
        },
        messages: {
            
        }
    })
    var text_max = 160;
    jQuery('#textarea_feedback').html(text_max + ' characters remaining');

    jQuery('#daily_promo_text').keyup(function() {
        var text_length = jQuery('#daily_promo_text').val().length;
        var text_remaining = text_max - text_length;

        jQuery('#textarea_feedback').html(text_remaining + ' characters remaining');
    });
});
</script>
<?php
} else {
        header('Location: '.get_bloginfo('url').'/login');
    } ?>