<?php /* Template Name: Analytics HTMLtoPDF */ ?>
<?php ob_start(); ?>
<?php
global $user_ID;
global $wpdb;
$store_id = get_user_meta($user_ID, 'store_id', true);
?>
<?php get_header('report'); ?>
<?php //if(is_user_logged_in()){ ?>
<?php global $user_ID; ?>
<?php $store_id = get_user_meta($user_ID, 'store_id', true); ?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php //get_sidebar('menu'); ?>
	        <div class="col span_12_of_12 matchheight">
                <div class="dash_content">
                    <h1>Analytics Report</h1>
                    <div class="reportBox">
                        <h3>Traffic Flow</h3>
                        <?php echo date('Y-m-d H:i:s'); ?>
                        <div><?php get_template_part('trafficflow'); ?></div>
                    </div>
                    <div class="reportBox">
                        <h3>Stylist/Employee Conversion Rates</h3>
                        <div><?php get_template_part('employeeconversion'); ?></div>
                    </div>
                </div>
                <?php get_footer(); ?>                          
	        </div>
	    </div>
	</div>
</div>
<?php //} else { header('Location: '.get_bloginfo('url').'/login'); } ?>