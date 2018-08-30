<?php
global $wpdb;
$table_name1 = $wpdb->prefix.'shoppers';
global $user_ID;
$store_id = get_user_meta($user_ID, 'store_id', true);
$week_num = date("W");
$month_num = date("n");
?>

<script>
// code for last 7 days
google.setOnLoadCallback(trafficflowweekly);
function trafficflowweekly(){    
    var data = google.visualization.arrayToDataTable([
        ['Employee', 'Shoppers Conversion'],
        <?php
            $user_query = new WP_User_Query(array( 'role' => 'storeemployee', 'meta_key' => 'store_id', 'meta_value' => $store_id ));
            
            foreach ($user_query->results as $user) {
                $day = date('d')+1;
                $last_7_day = date('d')-6;
                $year = date('Y');
                $month = date('m');
                $employee_id = $user->ID;
                $employee_name = get_the_author_meta('display_name', $user->ID);
                
                //$current_date_time1 = date('Y-m-d',mktime(0,0,0,$month,$day,$year)).' 00:00:00';
                //$current_date_time2 = date('Y-m-d',mktime(0,0,0,$month,$last_7_day,$year)).' 00:00:00';
                //$sql2 = "SELECT count(id) as user_count FROM $table_name1 WHERE entry_date BETWEEN '$current_date_time2' AND '$current_date_time1'  AND stylist_id = $employee_id AND complete_purchase = 1";
                //$result = $wpdb->get_results($sql2);
                //$user_count = $result[0]->user_count;
                $arg1 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_query' => array(
            array(
                'key'     => 'store_id',
                'value'   => $store_id,
                'compare' => '=',
            ),
            array(
                'key' => 'stylist_id',
                'value' => $employee_id,
                'compare' => '='
            ),
            'relation' => 'AND'
            ),
                    //'author' => $employee_id,
                    'date_query' =>  array(
                        array(
                            'week' => $week_num,
                            'year' => $year
                        ),
                        'column' => 'post_modified'
                    ),
                );
                
                $the_query1 = new WP_Query($arg1);
                $total_shopper_count = $the_query1->found_posts;

                $arg2 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_query' => array(
            array(
                'key'     => 'store_id',
                'value'   => $store_id,
                'compare' => '=',
            ),
            array(
                'key' => 'stylist_id',
                'value' => $employee_id,
                'compare' => '='
            ),
            array(
                'key' => 'complete_purchase',
                'value' => 1,
                'compare' => '=',
            ),
            'relation' => 'AND'
            ),
                    'date_query' =>  array(
                        array(
                            'week' => $week_num,
                            'year' => $year
                        ),
                        'column' => 'post_modified'
                    ),
                );
                
                $the_query2 = new WP_Query($arg2);
                $sold_shopper_count = $the_query2->found_posts;

                if ($total_shopper_count != 0) {
                    echo "['$employee_name', ".$sold_shopper_count/$total_shopper_count."],";
                } else {
                    echo "['$employee_name', 0],";
                }
            }
        ?>
    ]);
    
    var options = {
        hAxis: {
            title: 'No. of Shoppers this week',
            logScale: false
        },
        vAxis: {
            title: 'Employee',
            logScale: false
        },
        colors: ['#14b9d6', '#097138']
    };
      
    var chart = new google.visualization.BarChart(document.getElementById('trafficflowweekly1'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_trafficflowweekly1 = document.getElementById('chart_to_png_trafficflowweekly1');
        chart_to_png_trafficflowweekly1.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
</script>
<script>
// code for this month
google.setOnLoadCallback(trafficflowmonthly);
function trafficflowmonthly(){
    var data = google.visualization.arrayToDataTable([
        ['Employee', 'Shoppers Conversion'],
        <?php
            $user_query = new WP_User_Query(array( 'role' => 'storeemployee', 'meta_key' => 'store_id', 'meta_value' => $user_ID ));
            foreach ($user_query->results as $user) {
                //$day = date('d')+1;
                //$last_7_day = date('d')-6;
                $year = date('Y');
                $month = date('n');
                
                $employee_id = $user->ID;
                $employee_name = get_the_author_meta('display_name', $user->ID);
                
                //$current_date_time1 = date('Y-m-d',mktime(0,0,0,1,1,$year)).' 00:00:00';
                //$current_date_time2 = date('Y-m-d',mktime(0,0,0,12,31,$year)).' 00:00:00';
                $arg3 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_query' => array(
            array(
                'key'     => 'store_id',
                'value'   => $store_id,
                'compare' => '=',
            ),
            array(
                'key' => 'stylist_id',
                'value' => $employee_id,
                'compare' => '='
            ),
            'relation' => 'AND'
            ),
                    //'author' => $employee_id,
                    'date_query' => array(
                    array(
                        'month' => $month,
                        'year' => $year
                    ),
                        'column' => 'post_modified'
                    ),
                );
                
                $the_query3 = new WP_Query($arg3);
                
                $total_shopper_count = $the_query3->found_posts;

                $arg4 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_query' => array(
            array(
                'key'     => 'store_id',
                'value'   => $store_id,
                'compare' => '=',
            ),
            array(
                'key' => 'stylist_id',
                'value' => $employee_id,
                'compare' => '='
            ),
            array(
                'key' => 'complete_purchase',
                'value' => 1,
                'compare' => '=',
            ),
            'relation' => 'AND'
            ),
                    'date_query' => array(
                    array(
                        'month' => $month,
                        'year' => $year
                    ),
                        'column' => 'post_modified'
                    ),
                );
                
                $the_query4 = new WP_Query($arg4);
                $sold_shopper_count = $the_query4->found_posts;

                
                
                if ($total_shopper_count != 0) {
                    echo "['$employee_name', ".$sold_shopper_count/$total_shopper_count."],";
                } else {
                    echo "['$employee_name', 0],";
                }
            }
        ?>
    ]);
    
    var options = {
        hAxis: {
            title: 'No. of Shoppers (Year '+<?php echo $year; ?>+')',
            //logScale: true,
            minValue: 0
        },
        vAxis: {
            title: 'Employee',
            //logScale: true
        },
        colors: ['#14b9d6', '#097138']
    };
      
    var chart = new google.visualization.BarChart(document.getElementById('trafficflowmonthly1'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_trafficflowmonthly1 = document.getElementById('chart_to_png_trafficflowmonthly1');
        chart_to_png_trafficflowmonthly1.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
</script>
<h4>Stylist/Employee Conversion Rates Weekly</h4>
<div id="trafficflowweekly1"></div><br /><br />
<h4>Stylist/Employee Conversion Rates This Month</h4>
<div id="trafficflowmonthly1"></div>

<!-- Chart to PNG -->
<div id="chart_to_png_trafficflowweekly1" style="display: none;"></div>
<div id="chart_to_png_trafficflowmonthly1" style="display: none;"></div>