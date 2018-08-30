<?php /* Template Name: Stylist / Employee */ ?>
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
                        <div class="buttonLink"><a href="<?php bloginfo('url'); ?>/add-employee"><i class="fa fa-user-plus"></i> Add Employee</a></div>
                        <?php
                            if ($_POST['save_changes'] == 'Save Changes') {
                                foreach ($_POST['user_id'] as $userid) {
                                    global $wpdb;
                                    $user_status = 0;
                                    $wpdb->update($wpdb->users, array('user_status' => $user_status), array('ID' => $userid));
                                }
                                foreach ($_POST['user_element_status'] as $userid) {
                                    global $wpdb;
                                    $user_status = 2;
                                    $wpdb->update($wpdb->users, array('user_status' => $user_status), array('ID' => $userid));
                                } ?>
                                <p class="successMsg">User Status Updated.</p>
                                <?php
                            } ?>
                        <form method="post" action="">
                        <table class="footable stylist_employee" data-sort="false" data-page-size="10">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th data-sort-ignore="true">Email</th>
                                    <th data-sort-ignore="true">Phone</th>
                                    <th data-sort-ignore="true">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $user_query = new WP_User_Query(array( 'role__in' => array('storeemployee', 'storesupervisor'), 'meta_key' => 'store_id', 'meta_value' => $user_ID, 'orderby' => 'display_name', 'order' => 'ASC' ));
    if (! empty($user_query->results)) {
        foreach ($user_query->results as $user) {
            ?>
                                <tr>
                                    <td class="profilePic"><a href="<?php bloginfo('url'); ?>/edit-employee/?eid=<?php echo encripted($user->ID); ?>" class="<?php echo $user->ID; ?>"><?php echo get_profile_img($user->ID); ?><?php echo $user->display_name; ?></a></td>
                                    <td><?php echo get_the_author_meta('user_email', $user->ID); ?></td>
                                    <td><?php echo get_user_meta($user->ID, 'phone_number', true); ?></td>
                                    <td>
                                        <?php $user_status = get_the_author_meta('user_status', $user->ID); ?>
                                        <?php
                                            if ($user_status == 2) {
                                                $user_status = 1;
                                            } else {
                                                $user_status = 0;
                                            } ?>
                                        <input type="checkbox" name="user_element_status[]" value="<?php echo $user->ID; ?>" <?php if ($user_status == 1) {
                                                echo 'checked="checked"';
                                            } ?> />
                                        
                                        <?php //echo is_user_active($user->ID);?>
                                        <input type="hidden" name="user_id[]" value="<?php echo $user->ID; ?>" />
                                    </td>
                                </tr>
                            <?php
        }
    } ?>
                            </tbody>
                            <tfoot>
                        		<tr>
                        			<td colspan="4">
                        				<div class="pagination pagination-centered hide-if-no-paging"></div>
                        			</td>
                        		</tr>
                        	</tfoot>
                        </table>
                        <div class="section group">
                            <div class="col span_12_of_12">
                                <div style="text-align: right;">
                                    <input type="submit" name="save_changes" value="Save Changes" />
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
<?php
} else {
        header('Location: '.get_bloginfo('url').'/login');
    } ?>