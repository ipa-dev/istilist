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
<?php } else { header('Location: '.get_bloginfo('url').'/login'); } ?>