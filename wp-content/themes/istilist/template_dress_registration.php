<?php /* Template Name: Dress Registraton */ ?>
<?php
ob_start();
if (isset($_POST['download_csv'])) {
    global $user_ID;
    $store_id = get_user_meta($user_ID, 'store_id', true);
    $output_filename = 'export_' . strftime('%Y-%m-%d-%H-%M-%S')  . '.csv';
    $output_handle = @fopen('php://output', "a");

    $csv_header = array(
        'id',
        'purchase_date',
        'stylist_employee',
        'school_event',
        'customer_name',
        'invoice',
        'designer',
        'style_number',
        'color',
        'size',
        'spacial_order',
        'customer_wear_date'
    );
    fputcsv($output_handle, $csv_header);

    $post_args1 = array(
        'post_type' => 'dress_reg',
        'post_status' => 'publish',
        'meta_key' => 'store_id',
        'meta_value' => $store_id,
        'posts_per_page' => -1
    );

    $the_query1 = new WP_Query($post_args1);
    $posts = $the_query1->posts;



    foreach ($posts as $post) {
        $output = array(
            $post->ID,
            get_post_meta($post->ID, 'purchase_date', true),
            get_post_meta($post->ID, 'stylist_employee', true),
            get_post_meta($post->ID, 'school_event', true),
            get_post_meta($post->ID, 'customer_name', true),
            get_post_meta($post->ID, 'invoice', true),
            get_post_meta($post->ID, 'designer', true),
            get_post_meta($post->ID, 'style_number', true),
            get_post_meta($post->ID, 'color', true),
            get_post_meta($post->ID, 'size', true),
            get_post_meta($post->ID, 'spacial_order', true),
            get_post_meta($post->ID, 'customer_wear_date', true),
        );
        // Add row to file
        fputcsv($output_handle, $output);
    }
    /*
    //rewind($output_handle);
    $attachments = stream_get_contents($output_handle);

    $from = get_option('admin_email');
    $headers = 'From: '.$from . "\r\n";
    //$headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
    //$headers .= "CC: susan@example.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $subject = "Dress Registration CSV";
    $msg = 'Dress Registration CSV';
    $emailto = 'bhulbhal981@gmail.com';
    $sent_message = wp_mail( $emailto, $subject, $msg, $headers, $attachments );
    */

    fclose($output_handle);
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename=' . $output_filename);
    header('Expires: 0');
    header('Pragma: public');
    //echo $data;
    exit;
}
?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) {
    ?>
<?php global $user_ID; ?>
<?php $store_id = get_user_meta($user_ID, 'store_id', true); ?>
<?php $store_owner_id = get_user_meta($user_ID, 'store_id', true); ?>
<?php $user_role = get_user_role($user_ID); ?>
<div id="dashboard">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <?php get_sidebar('menu'); ?>
	        <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <?php if (($user_role == 'storeowner') || ($user_role == 'storesupervisor')) {
        ?>
                    <h1><?php the_title(); ?>
                        <span class="h1inlinelink">
                            <form method="post" id="download_form" action="">
                                <input type="submit" name="download_csv" class="download_csv" value="&#xf019; Export to CSV" />
                            </form>
                        </span>
                    </h1>
                    <?php
    } ?>

                    <?php if ($user_role == 'storeemployee') {
        ?>
                    <h1><?php the_title(); ?></h1>
                    <?php
    } ?>
                    <div class="box">
                        <?php


                            if (isset($_POST['dress_registration'])) {
                                global $wpdb;
                                $post_arg = array(
                                    'post_type' => 'dress_reg',
                                    'post_title' => $_POST['purchase_date'].' - Dress Registration - '.$_POST['customer_name'],
                                    'post_content' => '',
                                    'post_author' => $user_ID,
                                    'post_date' => date('Y-m-d H:i:s'),
                                    'post_status' => 'publish'
                                );
                                $new_post_id = wp_insert_post($post_arg);

                                if ($new_post_id) {
                                    add_post_meta($new_post_id, 'purchase_date', $_POST['purchase_date']);
                                    add_post_meta($new_post_id, 'stylist_employee', $_POST['stylist_employee']);
                                    add_post_meta($new_post_id, 'school_event', $_POST['school_event']);
                                    add_post_meta($new_post_id, 'customer_name', $_POST['customer_name']);
                                    add_post_meta($new_post_id, 'invoice', $_POST['invoice']);
                                    add_post_meta($new_post_id, 'designer', $_POST['designer']);
                                    add_post_meta($new_post_id, 'style_number', $_POST['style_number']);
                                    add_post_meta($new_post_id, 'color', $_POST['color']);
                                    add_post_meta($new_post_id, 'size', $_POST['size']);
                                    add_post_meta($new_post_id, 'spacial_order', $_POST['spacial_order']);
                                    add_post_meta($new_post_id, 'customer_wear_date', $_POST['customer_wear_date']);
                                    add_post_meta($new_post_id, 'store_id', $store_id);

                                    echo '<p class="successMsg">Thank you for your valuable time and information.</p>';
                                //header("Location: ".get_bloginfo('home')."/dashboard");
                                } else {
                                    echo '<p class="errorMsg">Sorry, your information is not updated.</p>';
                                }
                            } ?>
                        <form id="forms" method="post" action="">
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Purchase Date</label>
                                    <input id="purchase_date" type="text" name="purchase_date" placeholder="MM-DD-YYYY" value="<?php echo date('m-d-Y'); ?>" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Stylist/Employee</label>
                                    <select name="stylist_employee">
                                        <option value="">Select</option>
                                        <?php
                                        $user_query = new WP_User_Query(array( 'role' => 'storeemployee', 'meta_key' => 'store_id', 'meta_value' => $user_ID ));
    if (! empty($user_query->results)) {
        foreach ($user_query->results as $user) {
            ?>
                                        <option value="<?php echo $user->display_name; ?>"><?php echo $user->display_name; ?></option>
                                        <?php
        }
    } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>School/Event</label>
                                    <input id="school_event" type="text" name="school_event" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Customer Name</label>
                                    <input type="text" name="customer_name" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Invoice/Sales ticket #</label>
                                    <input type="text" name="invoice" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Designer</label>
                                    <!--
                                    <?php
                                        /*$designer_args = array(
                                            'post_type' => 'designer_pref',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1,
                                            'meta_key' => 'store_id',
                                            'meta_value' => $store_id
                                        );
                                        $designers = new WP_Query($designer_args);*/
                                    ?>
                                    <select name="designer">
                                        <option value="">Select</option>
                                    <?php //while ( $designers->have_posts() ) : $designers->the_post();?>
                                        <option value="<?php //echo get_the_ID();?>"><?php //echo the_title();?></option>
                                    <?php //endwhile;?>
                                    </select>
                                    <?php //wp_reset_postdata();?>
                                    -->
                                    <input type="text" name="designer" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Style number</label>
                                    <!--
                                    <?php
                                        /*$style_args = array(
                                            'post_type' => 'style_pref',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1,
                                            'meta_key' => 'store_id',
                                            'meta_value' => $store_id
                                        );
                                        $styles = new WP_Query($style_args);*/
                                    ?>
                                    <select name="style_number">
                                        <option value="">Select</option>
                                    <?php //while ( $styles->have_posts() ) : $styles->the_post();?>
                                        <option value="<?php //echo get_the_ID();?>"><?php //echo the_title();?></option>
                                    <?php //endwhile;?>
                                    </select>
                                    <?php //wp_reset_postdata();?>
                                    -->
                                    <input type="text" name="style_number" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Color</label>
                                    <!--
                                    <?php
                                        /*$color_args = array(
                                            'post_type' => 'color_pref',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1,
                                            'meta_key' => 'store_id',
                                            'meta_value' => $store_id
                                        );
                                        $colors = new WP_Query($color_args);*/
                                    ?>
                                    <select name="color">
                                        <option value="">Select</option>
                                    <?php //while ( $colors->have_posts() ) : $colors->the_post();?>
                                        <option value="<?php //echo get_the_ID();?>"><?php //echo the_title();?></option>
                                    <?php //endwhile;?>
                                    </select>
                                    <?php //wp_reset_postdata();?>
                                    -->
                                    <input type="text" name="color" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Size</label>
                                    <!--
                                    <?php
                                        /*$size_args = array(
                                            'post_type' => 'size_pref',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1,
                                            'meta_key' => 'store_id',
                                            'meta_value' => $store_id
                                        );
                                        $sizes = new WP_Query($size_args);*/
                                    ?>
                                    <select name="size">
                                        <option value="">Select</option>
                                    <?php //while ( $sizes->have_posts() ) : $sizes->the_post();?>
                                        <option value="<?php //echo get_the_ID();?>"><?php //echo the_title();?></option>
                                    <?php //endwhile;?>
                                    </select>
                                    <?php //wp_reset_postdata();?>
                                    -->
                                    <input type="text" name="size" />
                                </div>
                                <div class="col span_6_of_12">
                                    <label>Stock Item/Special Order</label>
                                    <input type="text" name="spacial_order" />
                                </div>
                            </div>
                            <div class="section group">
                                <div class="col span_6_of_12">
                                    <label>Customer wear date</label>
                                    <input id="customer_wear_date" type="text" name="customer_wear_date" placeholder="MM-DD-YYYY" />
                                </div>
                                <div class="col span_6_of_12">
                                    <div style="text-align: right; margin-top: 20px;"><input type="submit" name="dress_registration" value="SUBMIT" /></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div>
                    <div class="box">
                      <form method="post" action="<?php bloginfo('url'); ?>/dress-registration">
                        <div class="searchForm section group">
                            <div class="col span_12_of_12">
                              <input placeholder="Style No." type="text" id="search_query" name="search_query" value="<?php echo $_POST['search_query']; ?>" />
                              <input type="submit" id="search_btn" name="search_btn" value="&#xf002" />
                            </div>
                        </div>
                      </form>
                    </div>
                    <?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
                    <?php
                        if (isset($_POST['search_query'])) {
                            $post_args = array(
                            'post_type' => 'dress_reg',
                            'post_status' => 'publish',
                            'meta_query' => array(
                              array(
                                'key' => 'store_id',
                                'value' => get_user_meta($user_ID, 'store_id', true),
                                'relation' => '='
                              ),
                              array(
                                'key' => 'style_number',
                                'value' => $_POST['search_query'],
                                'relation' => '='
                              ),
                              'relation' => 'AND'
                            ),
                            'paged' => $paged,
                            'posts_per_page' => 15
                          );
                        } else {
                            $post_args = array(
                            'post_type' => 'dress_reg',
                            'post_status' => 'publish',
                            'meta_key' => 'store_id',
                            'meta_value' => get_user_meta($user_ID, 'store_id', true),
                            'paged' => $paged,
                            'posts_per_page' => 15
                          );
                        }

    $the_query = new WP_Query($post_args);

    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) : $the_query->the_post();
        $reg_id = get_the_ID(); ?>
                    <div class="dressBox">
                        <div class="section group">
                            <div class="col span_11_of_12">
                                <div class="section group">
                                    <div class="col span_6_of_12">
                                        <p>Purchase Date : <?php echo get_post_meta($reg_id, 'purchase_date', true) ?></p>
                                    </div>
                                    <div class="col span_6_of_12">
                                        <p>Stylist/Employee : <?php echo get_post_meta($reg_id, 'stylist_employee', true) ?></p>
                                    </div>
                                </div>
                                <div class="section group">
                                    <div class="col span_6_of_12">
                                        <p>School/Event : <?php echo get_post_meta($reg_id, 'school_event', true) ?></p>
                                    </div>
                                    <div class="col span_6_of_12">
                                        <p>Customer Name : <?php echo get_post_meta($reg_id, 'customer_name', true) ?></p>
                                    </div>
                                </div>
                                <div class="section group">
                                    <div class="col span_6_of_12">
                                        <p>Invoice/Sales ticket # : <?php echo get_post_meta($reg_id, 'invoice', true) ?></p>
                                    </div>
                                    <div class="col span_6_of_12">
                                        <p>Designer : <?php echo get_post_meta($reg_id, 'designer', true); ?></p>
                                    </div>
                                </div>
                                <div class="section group">
                                    <div class="col span_6_of_12">
                                        <p>Style number : <?php echo get_post_meta($reg_id, 'style_number', true); ?></p>
                                    </div>
                                    <div class="col span_6_of_12">
                                        <p>Color : <?php echo get_post_meta($reg_id, 'color', true); ?></p>
                                    </div>
                                </div>
                                <div class="section group">
                                    <div class="col span_6_of_12">
                                        <p>Size : <?php echo get_post_meta($reg_id, 'size', true); ?></p>
                                    </div>
                                    <div class="col span_6_of_12">
                                        <p>Stock Item/Special Order : <?php echo get_post_meta($reg_id, 'spacial_order', true) ?></p>
                                    </div>
                                </div>
                                <div class="section group">
                                    <div class="col span_6_of_12">
                                        <p>Customer wear date : <?php echo get_post_meta($reg_id, 'customer_wear_date', true) ?></p>
                                    </div>
                                    <div class="col span_6_of_12"></div>
                                </div>
                            </div>
                            <div class="col span_1_of_12"><span class="buttonLink <?php echo $reg_id; ?>"><a href="<?php bloginfo('url'); ?>/delete-dress-registration?rid=<?php echo encripted($reg_id); ?>"><i class="fa fa-trash-o"></i></a></span></div>
                        </div>
                    </div>
                    <?php
                            endwhile;
    } else {
        ?>
                        <div class="box">
                            <p style="text-align: center; padding-bottom: 0;">No Dress Registered</p>
                        </div>
                        <?php
    } ?>
                        <?php wp_reset_postdata(); ?>
                        <div class="paginationWrapper"><?php if (function_exists('wp_pagenavi')) {
        wp_pagenavi(array( 'query' => $the_query ));
    } ?></div>
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
