<?php
global $wpdb;
global $user_ID;
$store_id = get_user_meta($user_ID, 'store_id', true);
$table_name1 = $wpdb->prefix.'shoppers';

if (!empty($_GET['ar'])) {
    $active_section = decripted($_GET['ar']);
} else {
    $active_section = 'tf-today';
}

if (!empty($_GET['sp'])) {
    $sp_active_section = decripted($_GET['sp']);
} else {
    $sp_active_section = 'sp-today';
}

if (!empty($_GET['st'])) {
    $st_active_section = decripted($_GET['st']);
} else {
    $st_active_section = 'st-today';
}

if (!empty($_GET['cl'])) {
    $cl_active_section = decripted($_GET['cl']);
} else {
    $cl_active_section = 'cl-today';
}

if (!empty($_GET['sz'])) {
    $sz_active_section = decripted($_GET['sz']);
} else {
    $sz_active_section = 'sz-today';
}

?>
<div class="timeframe">
    <ul>
        <li class="<?php if ($active_section == 'tf-year') {
    echo 'activeSection';
} ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?php echo encripted('tf-year'); ?>">Year</a></li>
        <li class="<?php if ($active_section == 'tf-lastmonth') {
    echo 'activeSection';
} ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?php echo encripted('tf-lastmonth'); ?>">Last Month</a></li>
        <li class="<?php if ($active_section == 'tf-thismonth') {
    echo 'activeSection';
} ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?php echo encripted('tf-thismonth'); ?>">This Month</a></li>
        <li class="<?php if ($active_section == 'tf-7days') {
    echo 'activeSection';
} ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?php echo encripted('tf-7days'); ?>">Last 7 Days</a></li>
        <li class="<?php if ($active_section == 'tf-today') {
    echo 'activeSection';
} ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?php echo encripted('tf-today'); ?>">Today</a></li>
        <li class="<?php if ($active_section == 'tf-custom') {
    echo 'activeSection';
} ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?php echo encripted('tf-custom'); ?>">Custom</a></li>
    </ul>
</div>

<!-- Generate Report -->
<?php if ($active_section == 'tf-year') {
    ?>
<script>

google.setOnLoadCallback(trafficflowyear);
function trafficflowyear(){
    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Month');
    data.addColumn('number', 'No. of Shoppers');
    data.addColumn('number', 'No. of Purchases');

    data.addRows([
        <?php
            $hour = 0;
    $year = date('Y');
    $month = 0;
    //$date = date('d');
    while ($month++ <= 12) {
        //$timetoprint = date('m',mktime(0,0,0,$month,0,$year));
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
        echo "['$monthName', $user_count, $purchase_count],";
    } ?>
    ]);

    var options = {
        hAxis: {
            title: 'Months',
            maxValue: 12
        },
        vAxis: {
            viewWindowMode: 'explicit',
            viewWindow: {
              min: 0,
            },
            title: 'Number of Shoppers'
        },
        legend: {position: 'top', maxLines: 3},
        colors: ['#14b9d6', '#097138'],
        width: '100%',
        height: 350
    };

    var chart = new google.visualization.LineChart(document.getElementById('trafficflowyear'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_trafficflowyear = document.getElementById('chart_to_png_trafficflowyear');
        chart_to_png_trafficflowyear.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', trafficflowyear);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', trafficflowyear);
}
else {
    window.resize = trafficflowyear;
}
</script>
<h4>Traffic Flow <?php echo date('Y'); ?></h4>
<div id="trafficflowyear"></div><br /><br />
<?php
} ?>
<!--  -->

<?php if ($active_section == 'tf-lastmonth') {
        ?>
<script>
// code for last month
google.setOnLoadCallback(trafficflowlastmonth);
function trafficflowlastmonth(){
    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Days');
    data.addColumn('number', 'No. of Shoppers');
    data.addColumn('number', 'No. of Purchases');

    data.addRows([
        <?php
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
            echo "['$timetoprint', $user_count, $purchase_count],";
        } ?>
    ]);

    var options = {
        hAxis: {
            title: 'Days'
        },
        vAxis: {
            title: 'Number of Shoppers',
            viewWindowMode: 'explicit',
            viewWindow: {
              min: 0,
            },
        },
        legend: {position: 'top', maxLines: 3},
        colors: ['#14b9d6', '#097138'],
        width: '100%',
        height: 380
    };

    var chart = new google.visualization.LineChart(document.getElementById('trafficflowlastmonth'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_trafficflowlastmonth = document.getElementById('chart_to_png_trafficflowlastmonth');
        chart_to_png_trafficflowlastmonth.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', trafficflowlastmonth);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', trafficflowlastmonth);
}
else {
    window.resize = trafficflowlastmonth;
}
</script>
<h4>Traffic Flow Last Month</h4>
<div id="trafficflowlastmonth"></div><br /><br />
<?php
    } ?>
<!--  -->

<?php if ($active_section == 'tf-thismonth') {
        ?>
<script>
// code for This month
google.setOnLoadCallback(trafficflowthismonth);
function trafficflowthismonth(){
    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Days');
    data.addColumn('number', 'No. of Shoppers');
    data.addColumn('number', 'No. of Purchases');

    data.addRows([
        <?php
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
        } ?>
    ]);

    var options = {
        hAxis: {
            title: 'Days'
        },
        vAxis: {
            title: 'Number of Shoppers',
            viewWindowMode: 'explicit',
            viewWindow: {
              min: 0,
            },
        },
        legend: {position: 'top', maxLines: 3},
        colors: ['#14b9d6', '#097138'],
        width: '100%',
        height: 380
    };

    var chart = new google.visualization.LineChart(document.getElementById('trafficflowthismonth'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_trafficflowthismonth = document.getElementById('chart_to_png_trafficflowthismonth');
        chart_to_png_trafficflowthismonth.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', trafficflowthismonth);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', trafficflowthismonth);
}
else {
    window.resize = trafficflowthismonth;
}
</script>
<h4>Traffic Flow This Month</h4>
<div id="trafficflowthismonth"></div>
<?php
    } ?>
<!--  -->

<?php if ($active_section == 'tf-7days') {
        ?>
<script>
// code for last 7 days
google.setOnLoadCallback(trafficflow7days);
function trafficflow7days(){
    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Days');
    data.addColumn('number', 'No. of Shoppers');
    data.addColumn('number', 'No. of Purchases');

    data.addRows([
        <?php
            $year = date('Y');
        $month = date('m');
        $end_date = date('d')-7;
        $today = date('d');
        do {
            $timetoprint = date('m/d/Y', mktime(0, 0, 0, $month, $today, $year));
            $arg1 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_key' => 'store_id',
                    'meta_value' => $store_id,
                    'year' => $year,
                    'monthnum' => $month,
                    'day' => $today

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
                    'day' => $today,
                );

            $the_query2 = new WP_Query($arg2);
            $purchase_count = $the_query2->found_posts;
            echo "['$timetoprint', $user_count, $purchase_count],";
            $today--;
        } while ($today > $end_date); ?>
    ]);

    var options = {
        hAxis: {
            title: 'Days'
        },
        vAxis: {
            title: 'Number of Shoppers',
            viewWindowMode: 'explicit',
            viewWindow: {
              min: 0,
            },
        },
        legend: {position: 'top', maxLines: 3},
        colors: ['#14b9d6', '#097138'],
        width: '100%',
        height: 380
    };

    var chart = new google.visualization.LineChart(document.getElementById('trafficflow7days'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_trafficflow7days = document.getElementById('chart_to_png_trafficflow7days');
        chart_to_png_trafficflow7days.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', trafficflow7days);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', trafficflow7days);
}
else {
    window.resize = trafficflow7days;
}
</script>
<h4>Traffic Flow last 7 days</h4>
<div id="trafficflow7days"></div><br /><br />
<?php
    } ?>
<!--  -->

<?php if ($active_section == 'tf-today') {
        ?>
<script>
// code for today
google.setOnLoadCallback(trafficflowtoday);
function trafficflowtoday(){
    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Time');
    data.addColumn('number', 'No. of Shoppers');
    data.addColumn('number', 'No. of Purchases');

    data.addRows([
        <?php
            $hour = 7;
        $year = date('Y');
        $month = date('m');
        $date = date('d');
        while ($hour++ < 19) {
            $timetoprint = date('g a', mktime($hour, 0, 0, $month, $date, $year));
            //MASONS CODE FOR WRONG TIME IN ANALYTICS
            $time_zone = get_user_meta($store_id, "selecttimezone", true);
            switch ($time_zone) {
                     case "US/Eastern":
                         $offset = 4;
                         break;
                     case "US/Central":
                         $offset = 5;
                         break;
                     case "US/Mountain":
                         $offset = 6;
                         break;
                     case "America/Los_Angeles":
                         $offset = 7;
                         break;
                     default:
                         $offset = 0;
                         break;
                }
            //END MASONS CODE
            $arg1 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_key' => 'store_id',
                    'meta_value' => $store_id,
                    'year' => $year,
                    'monthnum' => $month,
                    'day' => $date,
                    'hour' => ($hour+$offset)
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
                    'day' => $date,
                    'hour' => ($hour+$offset),
                );

            $the_query2 = new WP_Query($arg2);
            $purchase_count = $the_query2->found_posts;
            echo "['$timetoprint', $user_count, $purchase_count],";
        } ?>
    ]);

    var options = {
        hAxis: {
            title: 'Time'
        },
        vAxis: {
            title: 'Number of Shoppers',
            viewWindowMode: 'explicit',
            viewWindow: {
              min: 0,
            },
        },
        legend: {position: 'top', maxLines: 3},
        colors: ['#14b9d6', '#097138'],
        width: '100%',
        height: 380
    };

    var chart = new google.visualization.LineChart(document.getElementById('trafficflowtoday'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_trafficflowtoday = document.getElementById('chart_to_png_trafficflowtoday');
        chart_to_png_trafficflowtoday.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', trafficflowtoday);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', trafficflowtoday);
}
else {
    window.resize = trafficflowtoday;
}
</script>
<h4>Traffic Flow Today</h4>
<div id="trafficflowtoday"></div><br /><br />
<?php
    } ?>
<!--  -->

<?php if ($active_section == 'tf-custom') {
        ?>
<div class="daterange">
    <form method="post" action="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?php echo encripted('tf-custom'); ?>">
        <table>
            <tr>
                <td>Date Range : </td>
                <td><input id="fromdate" type="text" name="fromdate" placeholder="mm/dd/yy" /></td>
                <td style="text-align: center;"> TO </td>
                <td><input id="todate" type="text" name="todate" placeholder="mm/dd/yy" /></td>
                <td><input type="submit" name="showreport" value="GO" /></td>
            </tr>
        </table>
    </form>
</div>
<?php if (isset($_POST['showreport'])) {
            ?>
<script>
// code for custom date range
google.setOnLoadCallback(trafficflowcustom);
function trafficflowcustom(){
    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Days');
    data.addColumn('number', 'No. of Shoppers');
    data.addColumn('number', 'No. of Purchases');

    data.addRows([
        <?php
            $date1 = explode('-', $_POST['fromdate']);

            $startDate_month = $date1[0];
            $startDate_year = $date1[2];
            $startDate_date = $date1[1];

            $startDate = strtotime($startDate_year.'-'.$startDate_month.'-'.$startDate_date.' 00:00:01');

            $date2 = explode('-', $_POST['todate']);

            $endDate_month = $date2[0];
            $endDate_year = $date2[2];
            $endDate_date = $date2[1];

            $endDate = strtotime($endDate_year.'-'.$endDate_month.'-'.$endDate_date.' 23:59:59');

            for ($i = $startDate; $i <= $endDate; $i = $i + 86400) {
                $thisYear = date('Y', $i); // 2010-05-01, 2010-05-02, etc
                $thisMonth = date('m', $i);
                $thisDate = date('d', $i);
                $timetoprint = date('m/d/Y', $i);
                $arg1 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_key' => 'store_id',
                    'meta_value' => $store_id,
                    'year' => $thisYear,
                    'monthnum' => $thisMonth,
                    'day' => $thisDate
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
                    'monthnum' => $thisMonth,
                    'year' => $thisYear,
                    'day' => $thisDate,
                );

                $the_query2 = new WP_Query($arg2);
                $purchase_count = $the_query2->found_posts;
                echo "['$timetoprint', $user_count, $purchase_count],";
            } ?>
    ]);

    var options = {
        hAxis: {
            title: 'Days'
        },
        vAxis: {
            title: 'Number of Shoppers',
            viewWindowMode: 'explicit',
            viewWindow: {
              min: 0,
            },
        },
        legend: {position: 'top', maxLines: 3},
        colors: ['#14b9d6', '#097138'],
        width: '100%',
        height: 380
    };

    var chart = new google.visualization.LineChart(document.getElementById('trafficflowcustom'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_trafficflowcustom = document.getElementById('chart_to_png_trafficflowcustom');
        chart_to_png_trafficflowcustom.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', trafficflowcustom);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', trafficflowcustom);
}
else {
    window.resize = trafficflowcustom;
}
</script>
<h4>Traffic Flow Between <?php echo $_POST['fromdate']; ?> to <?php echo $_POST['todate']; ?></h4>
<div id="trafficflowcustom"></div><br /><br />
<?php
        } ?>
<?php
    } ?>


<!-- Chart to PNG -->
<div id="chart_to_png_trafficflowyear" style="display: none;"></div>
<div id="chart_to_png_trafficflowlastmonth" style="display: none;"></div>
<div id="chart_to_png_trafficflowthismonth" style="display: none;"></div>
<div id="chart_to_png_trafficflow7days" style="display: none;"></div>
<div id="chart_to_png_trafficflowtoday" style="display: none;"></div>
<div id="chart_to_png_trafficflowcustom" style="display: none;"></div>
