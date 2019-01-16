<?php /* Template Name: Self Registration */ ?>
<?php 
get_header(); 
require_once ABSPATH . 'vendor/autoload.php';
use Twilio\Rest\Client;
if (is_user_logged_in()) {
    global $user_ID;
    global $wpdb; 
    $store_id = get_user_meta($user_ID, 'store_id', true);
?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
          <div class="col span_2_of_12"></div>
	        <div class="col span_8_of_12 matchheight">
                <div class="dash_content">
                    <?php 
                    require_once ABSPATH . 'wp-content/themes/istilist/php_modules/template-new-shopper/new-shopper-form.php';
                    generate_new_shopper_form($wpdb, $store_id, get_bloginfo( 'url' ) . '/self-registration');
                    ?>
                </div>
          </div>
          <div class="col span_2_of_12"></div>
      </div>
  </div>
</div>
<script>
    jQuery(document).ready(function(){
        jQuery('#forms').validate({
            rules: {
                customer_fname: {
                    required: true
                },
                customer_lname: {
                    required: true
                },
                school_event: {
                    required: true
                },
                graduation_year: {
                    required: true
                },
                <?php
                $store_owner_id1 = get_user_meta($user_ID, 'store_id', true);
                $table_name21 = $wpdb->prefix.'dynamic_form';
                $sql21 = "SELECT * FROM $table_name21 WHERE store_owner_id = $store_owner_id1 AND is_custom = 1 ORDER BY id";
                $results21 = $wpdb->get_results($sql21);
                foreach ($results21 as $r21) {
                    echo $r21->form_slug.": {
                            required: true
                        },\n";
                } ?>
                    },
                    messages: {

                    }
                })
    });
</script>
<?php wp_footer(); } else { header('Location: '.get_bloginfo('url').'/login'); } ?>
