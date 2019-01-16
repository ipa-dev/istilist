<?php /* Template Name: Add New shopper */ ?>
<?php 
get_header(); 
if (is_user_logged_in()) {
	global $user_ID;
	$store_owner_id = get_user_meta($user_ID, 'store_id', true);
	$store_id = get_user_meta($user_ID, 'store_id', true);
	$user_role = get_user_role($user_ID); 
?>
<div id="dashboard">
	<div class="maincontent noPadding">
		<div class="section group">
			<?php get_sidebar('menu'); ?>
			<div class="col span_9_of_12 matchheight">
				<div class="dash_content">
					<h1> 
						<?php // Page Title
							echo get_the_title();
							if (('storeowner' == $user_role) || ('storesupervisor' == $user_role)) {
								echo '<span class="h1inlinelink">
										<a href="' . get_bloginfo('url') . '/edit-shoppers-form">Edit this Form</a>
									  </span>';
							}
						?>
					</h1>
					<div class="box addnewshoppers">
						<?php 
							require_once 'php_modules/template-new-shopper/new-shopper-form.php';
							generate_new_shopper_form($wpdb, $store_id, get_bloginfo('url') . '/dashboard');
						?>
					</div>
				</div>
				<?php get_footer(); ?>
			</div>
		</div>
	</div>
</div>
<script>
jQuery(document).ready(function(){

	var apiUrl = window.location.origin + '/wp-json/istilist/v2/shoppers/' + document.getElementById( 'store_id' );
	axios.post( apiUrl, {
		customer_fname: document.getElementById( 'customer_fname' ).value,
		customer_lname: document.getElementById( 'customer_lname' ).value,
		school_event: document.getElementById( 'school_event' ).value,
		graduation_year: document.getElementById( 'graduation_year' ).value,
		customer_email: document.getElementById( 'customer_email' ).value,
		customer_phone: document.getElementById( 'customer_phone' ).value,
		design_preferences: document.getElementById( 'design_preferences' ).value,
		style_preferences: document.getElementById( 'style_preferences' ).value,
		color_preferences: document.getElementById( 'color_preferences' ).value,
		customer_size: document.getElementById( 'customer_size' ).value,
		customer_address: document.getElementById( 'customer_address' ).value,
		customer_city: document.getElementById( 'customer_city' ).value,
		customer_state: document.getElementById( 'customer_state' ),
		customer_zip: document.getElementById( 'customer_zip' ),
		sms_agreement: document.getElementById( 'sms_agreement' ),
		callback_url: document.getElementById( 'callback_url' ),
	});

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
<?php } else { header('Location: '.get_bloginfo('url').'/login'); } ?>