<?php get_header(); ?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <div class="container">
                        <?php while (have_posts()) : the_post(); ?>
                        <h1><?php the_title(); ?></h1>
                        <div><?php the_content(); ?></div>
                        <?php endwhile; ?>  
                    </div>
                </div>
                <?php get_footer(); ?>                          
	        </div>
	    </div>
	</div>
</div>