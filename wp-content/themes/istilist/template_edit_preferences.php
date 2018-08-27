<?php /* Template Name: Edit Preferences */ ?>
<?php get_header(); ?>
<?php if(is_user_logged_in()){ ?>
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
                            $pref_id = decripted($_GET['id']);
                            if(isset($_POST['update_pref'])){
                                $args = array(
                                    'ID' => $_POST['pref_id'], 
                                    'post_title' => $_POST['preference_title']
                                );
                                wp_update_post( $args );
                                header('Location: '.get_bloginfo('url').'/store-preferences');
                            }
                        ?>
                        <form id="forms" method="post" action="">
                            <div class="commonForm">
                                <div>
                                    <label>Title</label>
                                    <input type="text" name="preference_title" value="<?php echo get_the_title($pref_id); ?>" />
                                </div>
                                <div>
                                    <input type="hidden" name="pref_id" value="<?php echo $pref_id; ?>" />
                                    <input type="submit" name="update_pref" value="Update" />
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