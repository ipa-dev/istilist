<?php /* Template Name: Add New shopper */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
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
						<?php require_once 'php_modules/template-new-shopper/new-shopper-form.php'; ?>
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