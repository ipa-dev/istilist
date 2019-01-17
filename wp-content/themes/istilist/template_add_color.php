<?php /* Template Name: Add Color */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    ?>
<?php global $user_ID; ?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <h1><?php the_title(); ?></h1>
                    <div class="box">
                        <?php
                            if (isset($_POST['add_pref'])) {
                                $args = array(
                                    'post_title' => $_POST['preference_title'],
                                    'post_status' => 'publish',
                                    'post_type' => 'color_pref'
                                );
                                $pref_id = wp_insert_post($args);
                                add_post_meta($pref_id, 'store_id', $_POST['store_id']);
                                header('Location: '.get_bloginfo('url').'/store-preferences');
                            } ?>
                        <form id="forms" method="post" action="">
                            <div class="commonForm">
                                <div>
                                    <label>Title</label>
                                    <input type="text" name="preference_title" />
                                </div>
                                <div>
                                    <input type="hidden" name="store_id" value="<?php echo get_user_meta($user_ID, 'store_id', true); ?>" />
                                    <input type="submit" name="add_pref" value="Add" />
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
            preference_title: {
                required: true
            }
        }
    });
});
</script>
<?php } else { header('Location: '.get_bloginfo('url').'/login'); } ?>