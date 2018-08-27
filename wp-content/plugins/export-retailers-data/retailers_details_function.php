<?php
$retailer_id = $_GET['id'];
if(isset($_POST['shoppers_data'])){
    global $wpdb;
    ob_get_clean();ob_start();
    $output_filename = 'export_retailers_shoppers_' . strftime( '%Y-%m-%d-%H-%M-%S' )  . '.csv';
    $output_handle = @fopen( 'php://output', "a" );
    //$output_handle = @fopen( $output_filename, "w" );
    
    //if ($output_handle && $retailers) {
        $csv_header = array(
            'time_stamp',
            'shoppers_name',
            'school_event',
            'graduation_year',
            'shopper_email',
            'phone',
            'received_yes_or_no',
            'design_preferences',
            'style_preferences',
            'color_preferences',
            'size_preferences',
            'address',
            'customer_city',
            'customer_state',
            'customer_zip',
            'purchased'
        );
        
        fputcsv( $output_handle, $csv_header );
        
        $args = array(
            'author' => $retailer_id,
            'post_type' => 'shopper',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );
        $the_query = new WP_Query( $args );
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) : $the_query->the_post();
                $shoppers_id = get_the_ID();
                $design_preferences = get_post_meta($shoppers_id, 'design_preferences', true);
                if(!empty($design_preferences) && is_array($design_preferences)){
                    $design_preferences_1 = implode(',',$design_preferences);
                }
                
                $style_preferences = get_post_meta($shoppers_id, 'style_preferences', true);
                if(!empty($style_preferences) && is_array($style_preferences)){
                    $style_preferences_1 = implode(',',$style_preferences);
                }
                
                $color_preferences = get_post_meta($shoppers_id, 'color_preferences', true);
                if(!empty($color_preferences) && is_array($color_preferences)){
                    $color_preferences_1 = implode(',',$color_preferences);
                }
                
                $purchased = '';
                $purchased1 = get_post_meta($shoppers_id, 'reason_not_purchased', true);
                if ($purchased1) {
                     $purchased = 'no';
                }
                $purchased2 = get_post_meta($shoppers_id, 'complete_purchase', true);
                if (!empty($purchased2) && $purchased2 == '1') {
                     $purchased = 'yes';
                }

                $output = array(
                    get_the_time('F j, Y \a\t g:ia', $shoppers_id),
                    get_the_title($shoppers_id),
                    get_post_meta($shoppers_id, 'school_event', true),
                    get_post_meta($shoppers_id, 'graduation_year', true),
                    get_post_meta($shoppers_id, 'customer_email', true),
                    get_post_meta($shoppers_id, 'customer_phone', true),
                    get_post_meta($shoppers_id, 'promo_list_timestamp', TRUE),
                    $design_preferences_1,
                    $style_preferences_1,
                    $color_preferences_1,
                    get_post_meta($shoppers_id, 'customer_size', true),
                    get_post_meta($shoppers_id, 'customer_address', true),
                    get_post_meta($shoppers_id, 'customer_city', true),
                    get_post_meta($shoppers_id, 'customer_state', true),
                    get_post_meta($shoppers_id, 'customer_zip', true),
                    $purchased
                );
                fputcsv( $output_handle, $output );
            endwhile;
        }
        
        fclose( $output_handle );
        //header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
        header( 'Content-Description: File Transfer' );
        header( 'Content-type: text/csv' );
        header( 'Content-Disposition: attachment; filename=' . $output_filename );
        header( 'Expires: 0' );
        header('Pragma: no-cache');
        //echo $data;
        exit();
}
?>
<?php
if(isset($_POST['dress_registration_data'])){
    global $wpdb;
    ob_get_clean();ob_start();
    $output_filename = 'export_dress_registration_' . strftime( '%Y-%m-%d-%H-%M-%S' )  . '.csv';
    $output_handle = @fopen( 'php://output', "a" );
    
    $csv_header = array(
        'id',
        'time_stamp',
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
        
        fputcsv( $output_handle, $csv_header );
        
        $args = array(
            'post_type' => 'dress_reg',
            'post_status' => 'publish',
            'meta_key' => 'store_id',
            'meta_value' => $retailer_id,
            'posts_per_page' => -1
        );
        $the_query = new WP_Query( $args );
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) : $the_query->the_post();
                $shoppers_id = get_the_ID();
                
                $output = array(
                    $shoppers_id,
                    get_the_time('F j, Y \a\t g:ia', $shoppers_id),
                    get_post_meta($shoppers_id, 'purchase_date', true),
                    get_post_meta($shoppers_id, 'stylist_employee', true),
                    get_post_meta($shoppers_id, 'school_event', true),
                    get_post_meta($shoppers_id, 'customer_name', true),
                    get_post_meta($shoppers_id, 'invoice', true),
                    get_post_meta($shoppers_id, 'designer', true),
                    get_post_meta($shoppers_id, 'style_number', true),
                    get_post_meta($shoppers_id, 'color', true),
                    get_post_meta($shoppers_id, 'size', true),
                    get_post_meta($shoppers_id, 'spacial_order', true),
                    get_post_meta($shoppers_id, 'customer_wear_date', true),
                );
                fputcsv( $output_handle, $output );
            endwhile;
        }
        
        fclose( $output_handle );
        header( 'Content-Description: File Transfer' );
        header( 'Content-type: text/csv' );
        header( 'Content-Disposition: attachment; filename=' . $output_filename );
        header( 'Expires: 0' );
        header('Pragma: no-cache');
        exit();
}
?>

<?php
if(isset($_POST['employee_stylist_data'])){
    global $wpdb;
    ob_get_clean();ob_start();
    $output_filename = 'export_' . strftime( '%Y-%m-%d-%H-%M-%S' )  . '.csv';
    $output_handle = @fopen( 'php://output', "a" );
    
    $csv_header = array(
    	'time_stamp',
        'employee_name',
        'email_address',
        'phone_number',
    );
        
        fputcsv( $output_handle, $csv_header );
        
        $user_query = new WP_User_Query( array( 'role' => 'storeemployee', 'orderby' => 'display_name', 'meta_key' => 'store_id', 'meta_value' => $retailer_id) );
        $employees = $user_query->get_results();
        foreach ( $employees as $emp ) {
            $time_stamp = date('F j, Y \a\t g:ia', strtotime($emp->user_registered));
            $phone = get_user_meta($emp->id, 'phone_number', true);
            $output = array(
                $time_stamp,
                $emp->display_name,
                $emp->user_email,
                $phone
            );
        fputcsv( $output_handle, $output );
        }
                
        fclose( $output_handle );
        header( 'Content-Description: File Transfer' );
        header( 'Content-type: text/csv' );
        header( 'Content-Disposition: attachment; filename=' . $output_filename );
        header( 'Expires: 0' );
        header('Pragma: no-cache');
        exit();
}
?>
<div class="wrap">
    <h2>Retailer : <?php echo get_the_author_meta('display_name', $retailer_id); ?></h2>
    <table class="wp-list-table widefat fixed pages">
        <thead>
            <tr>
                <th>Retailer Data</th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Retailer Data</th>
                <th></th>
            </tr>
        </tfoot>
        <tbody>
            <tr>
                <th>Shoppers Data</th>
                <th>
                    <form method="post" action="">
                        <input class="button button-primary" type="submit" name="shoppers_data" value="Export to CSV" />
                    </form>
                </th>
            </tr>
            <tr>
                <th>Dress Registration Data</th>
                <th>
                    <form method="post" action="">
                        <input class="button button-primary" type="submit" name="dress_registration_data" value="Export to CSV" />
                    </form>
                </th>
            </tr>
            <tr>
                <th>Employee/Stylist Data</th>
                <th>
                    <form method="post" action="">
                        <input class="button button-primary" type="submit" name="employee_stylist_data" value="Export to CSV" />
                    </form>
                </th>
            </tr>
        </tbody>
        
    </table>
</div>