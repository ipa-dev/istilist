<?php
if(isset($_POST['all_retailer'])){
    ob_get_clean();ob_start();
    $output_filename = 'export_retailer_' . strftime( '%Y-%m-%d-%H-%M-%S' )  . '.csv';
    $output_handle = @fopen( 'php://output', "a" );
    //$output_handle = @fopen( $output_filename, "w" );
    
    //if ($output_handle && $retailers) {
        $csv_header = array(
            'retailer_name',
            'retailer_email',
            'registration_date'
        );
        
        fputcsv( $output_handle, $csv_header );
        
        $user_query = new WP_User_Query( array( 'role' => 'storeowner', 'orderby' => 'display_name') );
        $retailers = $user_query->get_results();
        foreach ( $retailers as $retailer ) {
            $output = array(
                $retailer->display_name,
                $retailer->user_email,
                $retailer->user_registered
            );
        	// Add row to file
        	fputcsv( $output_handle, $output );
        }
        //$string = ob_get_clean();
        
        
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
<div class="wrap">
    <h2>All Retailers
        <span>
            <form method="post" action="">
                <input class="button button-primary" type="submit" name="all_retailer" value="Export to CSV" />
            </form>
        </span>
    </h2>
    <table class="wp-list-table widefat fixed pages">
        <thead>
            <tr>
                <th>Retailer Name</th>
                <th>Email Address</th>
                <th>Registration Date</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Retailer Name</th>
                <th>Email Address</th>
                <th>Registration Date</th>
            </tr>
        </tfoot>
        <tbody>
            <?php
                $user_query = new WP_User_Query( array( 'role' => 'storeowner', 'orderby' => 'display_name' ) );
                $retailers = $user_query->get_results();
            ?>
            <?php foreach($retailers as $retailer){ ?>
            <tr>
                <th><a class="row-title" href="<?php bloginfo('home'); ?>/wp-admin/admin.php?page=retailers-data&id=<?php echo $retailer->id; ?>"><?php echo $retailer->display_name; ?></a></th>
                <th><?php echo $retailer->user_email; ?></th>
                <th><?php echo date('jS F, Y', strtotime($retailer->user_registered)); ?></th>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>