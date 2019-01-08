<?php /* Template Name: Process Analytics */ ?>
<?php
global $wpdb;
global $user_ID;
$store_id = get_user_meta($user_ID, 'store_id', true);
$table_name1 = $wpdb->prefix.'shoppers';
if (isset($_POST['activeSection'])) {
    $active_section = $_POST['activeSection'];
    $return_array['dataArray'] = array();
    if ( $active_section == 'tf-year' ) {
        $return_array['columnTitle'] = 'Month';
        $return_array['hAxisTitle'] = 'Months';
        $return_array['hAxisMaxValue'] = '12';
        $hour = 0;
        $year = date('Y');
        $month = 0;
        while ($month++ <= 12) {
            $monthName = date('F', mktime(0, 0, 0, $month, 10));

            $arg1 = array(
                        'post_type' => 'shopper',
                        'post_status' => 'publish',
                        'meta_key' => 'store_id',
                        'meta_value' => $store_id,
                        'monthnum' => $month,
                        'year' => $year,
                    );

            $the_query1 = new WP_Query($arg1);
            $user_count = $the_query1->found_posts;

            $arg2 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_query' => array(
                      array(
                        'key' => 'store_id',
                        'value' => $store_id,
                        'compare' => '=',
                      ),
                      array(
                        'key' => 'complete_purchase',
                        'value' => 1,
                        'compare' => '=',
                      ),
                      'relation' => 'AND',
                    ),
                    'monthnum' => $month,
                    'year' => $year,
                );
            $the_query2 = new WP_Query($arg2);
            $purchase_count = $the_query2->found_posts;
            $return_array['dataArray'].push_back(array($monthName, $user_count, $purchase_count));
        }
    }
    elseif ( $active_section == 'tf-lastmonth' ) {
        $return_array['columnTitle'] = 'Days';
        $return_array['hAxisTitle'] = 'Days';
        $return_array['hAxisMaxValue'] = '';

        $hour = 0;
        $year = date('Y');
        $month = date('m')-1;
        $day = 0;
        $number_of_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        while ($day++ < $number_of_days) {
            $timetoprint = date('m/d/Y', mktime($hour, 0, 0, $month, $day, $year));

            $arg1 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_key' => 'store_id',
                    'meta_value' => $store_id,
                    'year' => $year,
                    'monthnum' => $month,
                    'day' => $day,
                );

            $the_query1 = new WP_Query($arg1);
            $user_count = $the_query1->found_posts;

            $arg2 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_query' => array(
                    array(
                        'key' => 'store_id',
                        'value' => $store_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'complete_purchase',
                        'value' => 1,
                        'compare' => '=',
                    ),
                    'relation' => 'AND',
                    ),
                    'monthnum' => $month,
                    'year' => $year,
                    'day' => $day,
                );

            $the_query2 = new WP_Query($arg2);
            $purchase_count = $the_query2->found_posts;
            $return_array['dataArray'].push_back(array($timetoprint, $user_count, $purchase_count));
        }
    }
    elseif ($active_section == 'tf-thismonth') {
        $return_array['columnTitle'] = 'Days';
        $return_array['hAxisTitle'] = 'Days';
        $return_array['hAxisMaxValue'] = '';

        $hour = 0;
        $year = date('Y');
        $month = date('m');
        $day = 0;
        $number_of_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        while ($day++ < $number_of_days) {
            $timetoprint = date('m/d/Y', mktime($hour, 0, 0, $month, $day, $year));

            $arg1 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_key' => 'store_id',
                    'meta_value' => $store_id,
                    'year' => $year,
                    'monthnum' => $month,
                    'day' => $day,
                );

            $the_query1 = new WP_Query($arg1);
            $user_count = $the_query1->found_posts;
            $arg2 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_query' => array(
                      array(
                        'key' => 'store_id',
                        'value' => $store_id,
                        'compare' => '=',
                      ),
                      array(
                        'key' => 'complete_purchase',
                        'value' => 1,
                        'compare' => '=',
                      ),
                      'relation' => 'AND',
                    ),
                    'monthnum' => $month,
                    'year' => $year,
                    'day' => $day,
                );

            $the_query2 = new WP_Query($arg2);
            $purchase_count = $the_query2->found_posts;
            echo "['$timetoprint', $user_count, $purchase_count],";
        }
    }
    echo json_encode($return_array);
}
else {
    //SEND BACK ERROR RESPONSE
}
?>