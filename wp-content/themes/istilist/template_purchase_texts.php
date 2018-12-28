<?php /* Template Name: Purchase Texts */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) { 
      global $user_ID; 
?>
<div id="dashboard">
	<div class="maincontent noPadding">
        <div class="section group">
            <?php get_sidebar('menu'); ?>
            <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <h1><?php the_title(); ?></h1>
                    <div class="box">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>