<?php /* Template Name: Analytics & Reporting */ ?>
<?php ob_start(); ?>
<?php
global $user_ID;
global $wpdb;
$store_id = get_user_meta($user_ID, 'store_id', true);
if (isset($_POST['download_csv'])) {
    $output_filename = 'export_' . strftime('%Y-%m-%d-%H-%M-%S')  . '.csv';
    $output_handle = @fopen('php://output', "a");

    $csv_header = array();
    array_push($csv_header, 'registered_timestamp');

    if (check_is_active('customer_fname') == 1) {
        array_push($csv_header, 'customer_fname');
    }
    if (check_is_active('customer_lname') == 1) {
        array_push($csv_header, 'customer_lname');
    }
    if (check_is_active('school_event') == 1) {
        array_push($csv_header, 'school_event');
    }
    if (check_is_active('graduation_year') == 1) {
        array_push($csv_header, 'graduation_year');
    }
    if (check_is_active('customer_email') == 1) {
        array_push($csv_header, 'customer_email');
    }
    if (check_is_active('customer_phone') == 1) {
        array_push($csv_header, 'customer_phone');
        array_push($csv_header, 'received_yes_or_no');
    }
    if (check_is_active('customer_address') == 1) {
        array_push($csv_header, 'customer_address');
    }
    if (check_is_active('customer_city') == 1) {
        array_push($csv_header, 'customer_city');
    }
    if (check_is_active('customer_state') == 1) {
        array_push($csv_header, 'customer_state');
    }
    if (check_is_active('customer_zip') == 1) {
        array_push($csv_header, 'customer_zip');
    }

    if (check_is_active('design_preferences') == 1) {
        array_push($csv_header, 'design_preferences');
    }
    if (check_is_active('style_preferences') == 1) {
        array_push($csv_header, 'style_preferences');
    }
    if (check_is_active('color_preferences') == 1) {
        array_push($csv_header, 'color_preferences');
    }
    if (check_is_active('customer_size') == 1) {
        array_push($csv_header, 'customer_size');
    }
    array_push($csv_header, 'purchased');
    array_push($csv_header, 'purchased_date');
    $table_name2 = $wpdb->prefix.'dynamic_form';
    $sql2 = "SELECT * FROM $table_name2 WHERE store_owner_id = $store_id AND is_custom = 1 ORDER BY id";
    $results2 = $wpdb->get_results($sql2);
    foreach ($results2 as $r2) {
        if (check_is_active($r2->form_slug) == 1) {
            array_push($csv_header, $r2->form_slug);
        }
    }


    fputcsv($output_handle, $csv_header);

    $post_args1 = array(
        'post_type' => 'shopper',
        'post_status' => 'publish',
        'meta_key' => 'store_id',
        'meta_value' => $store_id,
        'posts_per_page' => -1
    );

    $the_query1 = new WP_Query($post_args1);
    $posts = $the_query1->posts;

    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename=' . $output_filename);
    header('Expires: 0');
    header('Pragma: public');

    foreach ($posts as $post) {
        $output = array();
        array_push($output, get_the_date("m-d-Y H:i:s", $post->ID));
        if (check_is_active('customer_fname') == 1) {
            array_push($output, get_post_meta($post->ID, 'customer_fname', true));
        }
        if (check_is_active('customer_lname') == 1) {
            array_push($output, get_post_meta($post->ID, 'customer_lname', true));
        }
        if (check_is_active('school_event') == 1) {
            array_push($output, get_post_meta($post->ID, 'school_event', true));
        }
        if (check_is_active('graduation_year') == 1) {
            array_push($output, get_post_meta($post->ID, 'graduation_year', true));
        }
        if (check_is_active('customer_email') == 1) {
            array_push($output, get_post_meta($post->ID, 'customer_email', true));
        }
        if (check_is_active('customer_phone') == 1) {
            array_push($output, get_post_meta($post->ID, 'customer_phone', true));
            array_push($output, get_post_meta($post->ID, 'promo_list_timestamp', true));
        }
        if (check_is_active('customer_address') == 1) {
            array_push($output, get_post_meta($post->ID, 'customer_address', true));
        }
        if (check_is_active('customer_city') == 1) {
            array_push($output, get_post_meta($post->ID, 'customer_city', true));
        }
        if (check_is_active('customer_state') == 1) {
            array_push($output, get_post_meta($post->ID, 'customer_state', true));
        }
        if (check_is_active('customer_zip') == 1) {
            array_push($output, get_post_meta($post->ID, 'customer_zip', true));
        }

        if (check_is_active('design_preferences') == 1) {
            array_push($output, get_post_meta($post->ID, 'design_preferences', true));
        }
        if (check_is_active('style_preferences') == 1) {
            array_push($output, get_post_meta($post->ID, 'style_preferences', true));
        }
        if (check_is_active('color_preferences') == 1) {
            array_push($output, get_post_meta($post->ID, 'color_preferences', true));
        }
        if (check_is_active('customer_size') == 1) {
            array_push($output, get_post_meta($post->ID, 'customer_size', true));
        }
        if (get_post_meta($post->ID, 'complete_purchase', true) == 1) {
            array_push($output, 'yes');
        } else {
            array_push($output, 'no');
        }
        array_push($output, get_the_modified_date("m-d-Y H:i:s", $post->ID));
        $table_name2 = $wpdb->prefix.'dynamic_form';
        $sql2 = "SELECT * FROM $table_name2 WHERE store_owner_id = $store_id AND is_custom = 1 ORDER BY id";
        $results2 = $wpdb->get_results($sql2);
        foreach ($results2 as $r2) {
            if (check_is_active($r2->form_slug) == 1) {
                array_push($output, get_post_meta($post->ID, $r2->form_slug, true));
            }
        }

        // Add row to file
        fputcsv($output_handle, $output);
    }
    fclose($output_handle);
    exit;
}
?>
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
                    <h1><?php the_title(); ?> <span class="h1inlinelink"><a href="javascript:void(0)" id="emailthisreport">Email this report</a></span></h1>
                    <div class="reportBox" id="demo1">
                        <h3 class="toggleHeading" id="toggleHeading1">Traffic Flow</h3>
                        <div class="toggleSection"><?php get_template_part('trafficflow'); ?></div>
                    </div>
                    <div class="reportBox" id="toggleHeading2">
                        <h3 class="toggleHeading">Stylist/Employee Conversion Rates</h3>
                        <div class="toggleSection"><?php get_template_part('employeeconversion'); ?></div>
                    </div>
                    <div class="reportBox" id="toggleHeading3">
                        <h3 class="toggleHeading">Designer Preferences</h3>
                        <div class="toggleSection"><?php get_template_part('designer_preff'); ?></div>
                    </div>
                    <div class="reportBox" id="toggleHeading4">
                        <h3 class="toggleHeading">Style Preferences</h3>
                        <div class="toggleSection"><?php get_template_part('style_preff'); ?></div>
                    </div>
                    <div class="reportBox" id="toggleHeading5">
                        <h3 class="toggleHeading">Color Preferences</h3>
                        <div class="toggleSection"><?php get_template_part('color_preff'); ?></div>
                    </div>
                    <div class="reportBox" id="toggleHeading6">
                        <h3 class="toggleHeading">Size Preferences</h3>
                        <div class="toggleSection"><?php get_template_part('size_preff'); ?></div>
                    </div>
                    <div class="reportBox">
                        <h3>Export all registered shoppers</h3>
                        <span class="h1inlinelink">
                            <form method="post" id="download_form" action="">
                                <input type="submit" name="download_csv" class="download_csv" value="&#xf019; Export to CSV" />
                            </form>
                        </span>
                        <div style="clear: both;"></div>
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
