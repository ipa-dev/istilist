<?php /* Template Name: Store Preferences */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    ?>
<?php global $user_ID; ?>
<?php $store_id = get_user_meta($user_ID, 'store_id', true); ?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <h1><?php the_title(); ?></h1>
                    <div class="reportBox">
                        <h3>Style Preferences</h3>
                        <div class="buttonLink"><a href="<?php bloginfo('url'); ?>/add-style">Add Style</a></div>
                        <div>
                            <?php
                                $style_args = array(
                                    'post_type' => 'style_pref',
                                    'post_status' => 'publish',
                                    'posts_per_page' => -1,
                                    'meta_key' => 'store_id',
                                    'meta_value' => $store_id,
                                    'orderby' => 'title',
                                    'order' => 'ASC'
                                );
    $styles = new WP_Query($style_args); ?>
                            <table class="footable" data-sort="false">
                                <thead>
                                    <tr>
                                        <th class="prefwidth1">Title</th>
                                        <th data-sort-ignore="true"></th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php if ($styles->have_posts()) {
        ?>
                            <?php while ($styles->have_posts()) : $styles->the_post(); ?>
                                    <tr>
                                        <td><?php the_title(); ?></td>
                                        <td><div style="text-align: right;"><span class="buttonLink"><a href="<?php bloginfo('url'); ?>/edit-preferences/?id=<?php echo encripted(get_the_ID()); ?>"><i class="fa fa-pencil-square-o"></i></a></span> <span class="buttonLink"><a href="<?php bloginfo('url'); ?>/delete-preference/?id=<?php echo encripted(get_the_ID()); ?>"><i class="fa fa-trash-o"></i></a></span></div></td>
                                    </tr>
                            <?php endwhile; ?>
                            <?php
    } else {
        ?>
                                    <tr>
                                        <td colspan="2"><div style="text-align: center;">No Preferences</div></td>
                                    </tr>
                            <?php
    } ?>
                            <?php wp_reset_postdata(); ?>
                                </tbody>
                                <tfoot>
                            		<tr>
                            			<td colspan="2">
                            				<div class="pagination pagination-centered hide-if-no-paging"></div>
                            			</td>
                            		</tr>
                            	</tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="reportBox">
                        <h3>Color Preferences</h3>
                        <div class="buttonLink"><a href="<?php bloginfo('url'); ?>/add-color">Add Color</a></div>
                        <div>
                            <?php
                                $color_args = array(
                                    'post_type' => 'color_pref',
                                    'post_status' => 'publish',
                                    'posts_per_page' => -1,
                                    'meta_key' => 'store_id',
                                    'meta_value' => $store_id,
                                    'orderby' => 'title',
                                    'order' => 'ASC'
                                );
    $colors = new WP_Query($color_args); ?>
                            <table class="footable" data-sort="false">
                                <thead>
                                    <tr>
                                        <th class="prefwidth1">Title</th>
                                        <th data-sort-ignore="true"></th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php if ($colors->have_posts()) {
        ?>
                            <?php while ($colors->have_posts()) : $colors->the_post(); ?>
                                    <tr>
                                        <td><?php the_title(); ?></td>
                                        <td><div style="text-align: right;"><span class="buttonLink"><a href="<?php bloginfo('url'); ?>/edit-preferences/?id=<?php echo encripted(get_the_ID()); ?>"><i class="fa fa-pencil-square-o"></i></a></span> <span class="buttonLink"><a href="<?php bloginfo('url'); ?>/delete-preference/?id=<?php echo encripted(get_the_ID()); ?>"><i class="fa fa-trash-o"></i></a></span></div></td>
                                    </tr>
                            <?php endwhile; ?>
                            <?php
    } else {
        ?>
                                    <tr>
                                        <td colspan="2"><div style="text-align: center;">No Preferences</div></td>
                                    </tr>
                            <?php
    } ?>
                            <?php wp_reset_postdata(); ?>
                                </tbody>
                                <tfoot>
                            		<tr>
                            			<td colspan="2">
                            				<div class="pagination pagination-centered hide-if-no-paging"></div>
                            			</td>
                            		</tr>
                            	</tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="reportBox">
                        <h3>Size Preferences</h3>
                        <div class="buttonLink"><a href="<?php bloginfo('url'); ?>/add-size">Add Size</a></div>
                        <div>
                            <?php
                                $size_args = array(
                                    'post_type' => 'size_pref',
                                    'post_status' => 'publish',
                                    'posts_per_page' => -1,
                                    'meta_key' => 'store_id',
                                    'meta_value' => $store_id,
                                    //'orderby' => 'title',
                                    //'order' => 'ASC'
                                );
    $sizes = new WP_Query($size_args); ?>
                            <table class="footable" data-sort="false">
                                <thead>
                                    <tr>
                                        <th class="prefwidth1">Title</th>
                                        <th data-sort-ignore="true"></th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php if ($sizes->have_posts()) {
        ?>
                            <?php while ($sizes->have_posts()) : $sizes->the_post(); ?>
                                    <tr>
                                        <td><?php the_title(); ?></td>
                                        <td><div style="text-align: right;"><span class="buttonLink"><a href="<?php bloginfo('url'); ?>/edit-preferences/?id=<?php echo encripted(get_the_ID()); ?>"><i class="fa fa-pencil-square-o"></i></a></span> <span class="buttonLink"><a href="<?php bloginfo('url'); ?>/delete-preference/?id=<?php echo encripted(get_the_ID()); ?>"><i class="fa fa-trash-o"></i></a></span></div></td>
                                    </tr>
                            <?php endwhile; ?>
                            <?php
    } else {
        ?>
                                    <tr>
                                        <td colspan="2"><div style="text-align: center;">No Preferences</div></td>
                                    </tr>
                            <?php
    } ?>
                            <?php wp_reset_postdata(); ?>
                                </tbody>
                                <tfoot>
                            		<tr>
                            			<td colspan="2">
                            				<div class="pagination pagination-centered hide-if-no-paging"></div>
                            			</td>
                            		</tr>
                            	</tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="reportBox">
                        <h3>Designer Preferences</h3>
                        <div class="buttonLink"><a href="<?php bloginfo('url'); ?>/add-designer">Add Designer</a></div>
                        <div>
                            <?php
                                $size_args = array(
                                    'post_type' => 'designer_pref',
                                    'post_status' => 'publish',
                                    'posts_per_page' => -1,
                                    'meta_key' => 'store_id',
                                    'meta_value' => $store_id,
                                    'orderby' => 'title',
                                    'order' => 'ASC'
                                );
    $sizes = new WP_Query($size_args); ?>
                            <table class="footable" data-sort="false">
                                <thead>
                                    <tr>
                                        <th class="prefwidth1">Title</th>
                                        <th data-sort-ignore="true"></th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php if ($sizes->have_posts()) {
        ?>
                            <?php while ($sizes->have_posts()) : $sizes->the_post(); ?>
                                    <tr>
                                        <td><?php the_title(); ?></td>
                                        <td><div style="text-align: right;"><span class="buttonLink"><a href="<?php bloginfo('url'); ?>/edit-preferences/?id=<?php echo encripted(get_the_ID()); ?>"><i class="fa fa-pencil-square-o"></i></a></span> <span class="buttonLink"><a href="<?php bloginfo('url'); ?>/delete-preference/?id=<?php echo encripted(get_the_ID()); ?>"><i class="fa fa-trash-o"></i></a></span></div></td>
                                    </tr>
                            <?php endwhile; ?>
                            <?php
    } else {
        ?>
                                    <tr>
                                        <td colspan="2"><div style="text-align: center;">No Preferences</div></td>
                                    </tr>
                            <?php
    } ?>
                            <?php wp_reset_postdata(); ?>
                                </tbody>
                                <tfoot>
                            		<tr>
                            			<td colspan="2">
                            				<div class="pagination pagination-centered hide-if-no-paging"></div>
                            			</td>
                            		</tr>
                            	</tfoot>
                            </table>
                        </div>
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