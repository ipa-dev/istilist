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
        <li class="<?php if ($active_section == 'cp-year') {
    echo 'activeSection';
} ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?php echo encripted('cp-year'); ?>">Year</a></li>
        <li class="<?php if ($active_section == 'cp-lastmonth') {
    echo 'activeSection';
} ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?php echo encripted('cp-lastmonth'); ?>">Last Month</a></li>
        <li class="<?php if ($active_section == 'cp-thismonth') {
    echo 'activeSection';
} ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?php echo encripted('cp-thismonth'); ?>">This Month</a></li>
        <li class="<?php if ($active_section == 'cp-7days') {
    echo 'activeSection';
} ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?php echo encripted('cp-7days'); ?>">Last 7 Days</a></li>
        <li class="<?php if ($active_section == 'cp-today') {
    echo 'activeSection';
} ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?php echo encripted('cp-today'); ?>">Today</a></li>
        <li class="<?php if ($active_section == 'cp-custom') {
    echo 'activeSection';
} ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?php echo encripted('cp-custom'); ?>">Custom</a></li>
    </ul>
</div>

<!-- Generate Report -->
<?php if ($active_section == 'cp-year') {
    ?>
<script>

google.setOnLoadCallback(customerpurchaseyear);
function customerpurchaseyear(){
    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Month');
    data.addColumn('number', 'No. of Shoppers');

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

        $the_query1 = new WP_Query($arg1);
        $user_count = $the_query1->found_posts;
        console.log("TEST");
        echo "['$monthName', $user_count],";
    } ?>
    ]);

    var options = {
        hAxis: {
            title: 'Months',
            maxValue: 12
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
        height: 350
    };

    var chart = new google.visualization.LineChart(document.getElementById('customerpurchaseyear'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_customerpurchaseyear = document.getElementById('chart_to_png_customerpurchaseyear');
        chart_to_png_customerpurchaseyear.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', customerpurchaseyear);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', customerpurchaseyear);
}
else {
    window.resize = customerpurchaseyear;
}
</script>
<h4>Purchases <?php echo date('Y'); ?></h4>
<div id="customerpurchaseyear"></div><br /><br />
<?php
} ?>
<!--  -->

<?php if ($active_section == 'tf-lastmonth') {
        ?>
<script>
// code for last month
google.setOnLoadCallback(customerpurchaselastmonth);
function customerpurchaselastmonth(){
    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Days');
    data.addColumn('number', 'No. of Shoppers');

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
                    'year' => $year,
                    'monthnum' => $month,
                    'day' => $day,
                );

            $the_query1 = new WP_Query($arg1);
            $user_count = $the_query1->found_posts;
            echo "['$timetoprint', $user_count],";
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

    var chart = new google.visualization.LineChart(document.getElementById('customerpurchaselastmonth'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_customerpurchaselastmonth = document.getElementById('chart_to_png_customerpurchaselastmonth');
        chart_to_png_customerpurchaselastmonth.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', customerpurchaselastmonth);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', customerpurchaselastmonth);
}
else {
    window.resize = customerpurchaselastmonth;
}
</script>
<h4>Purchases Last Month</h4>
<div id="customerpurchaselastmonth"></div><br /><br />
<?php
    } ?>
<!--  -->

<?php if ($active_section == 'cp-thismonth') {
        ?>
<script>
// code for This month
google.setOnLoadCallback(customerpurchasethismonth);
function customerpurchasethismonth(){
    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Days');
    data.addColumn('number', 'No. of Shoppers');

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
                    'year' => $year,
                    'monthnum' => $month,
                    'day' => $day,
                );

            $the_query1 = new WP_Query($arg1);
            $user_count = $the_query1->found_posts;
            echo "['$timetoprint', $user_count],";
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

    var chart = new google.visualization.LineChart(document.getElementById('customerpurchasethismonth'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_customerpurchasethismonth = document.getElementById('chart_to_png_customerpurchasethismonth');
        chart_to_png_customerpurchasethismonth.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', customerpurchasethismonth);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', customerpurchasethismonth);
}
else {
    window.resize = customerpurchasethismonth;
}
</script>
<h4>Purchases This Month</h4>
<div id="customerpurchasethismonth"></div>
<?php
    } ?>
<!--  -->

<?php if ($active_section == 'cp-7days') {
        ?>
<script>
// code for last 7 days
google.setOnLoadCallback(customerpurchase7days);
function customerpurchase7days(){
    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Days');
    data.addColumn('number', 'No. of Shoppers');

    data.addRows([
        <?php
            $year = date('Y');
        $month = date('m');
        $end_date = date('d')-7;
        $today = date('d');
        do {
            $timetoprint = date('m/d/Y', mktime(0, 0, 0, $month, $today, $year));
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
                    'year' => $year,
                    'monthnum' => $month,
                    'day' => $today

                );

            $the_query2 = new WP_Query($arg2);
            $user_count = $the_query2->found_posts;

            echo "['$timetoprint', $user_count],";
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

    var chart = new google.visualization.LineChart(document.getElementById('customerpurchase7days'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_customerpurchase7days = document.getElementById('chart_to_png_customerpurchase7days');
        chart_to_png_customerpurchase7days.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', customerpurchase7days);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', customerpurchase7days);
}
else {
    window.resize = customerpurchase7days;
}
</script>
<h4>Purchases last 7 days</h4>
<div id="customerpurchase7days"></div><br /><br />
<?php
    } ?>
<!--  -->

<?php if ($active_section == 'cp-today') {
        ?>
<script>
// code for today
google.setOnLoadCallback(customerpurchasetoday);
function customerpurchasetoday(){
    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Time');
    data.addColumn('number', 'No. of Shoppers');

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
                    'year' => $year,
                    'monthnum' => $month,
                    'day' => $date,
                    'hour' => ($hour+$offset)
                );

            $the_query1 = new WP_Query($arg1);
            $user_count = $the_query1->found_posts;
            echo "['$timetoprint', $user_count],";
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

    var chart = new google.visualization.LineChart(document.getElementById('customerpurchasetoday'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_customerpurchasetoday = document.getElementById('chart_to_png_customerpurchasetoday');
        chart_to_png_customerpurchasetoday.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', customerpurchasetoday);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', customerpurchasetoday);
}
else {
    window.resize = customerpurchasetoday;
}
</script>
<h4>Purchases Today</h4>
<div id="customerpurchasetoday"></div><br /><br />
<?php
    } ?>
<!--  -->

<?php if ($active_section == 'cp-custom') {
        ?>
<div class="daterange">
    <form method="post" action="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?php echo encripted('cp-custom'); ?>">
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
google.setOnLoadCallback(customerpurchasecustom);
function customerpurchasecustom(){
    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Days');
    data.addColumn('number', 'No. of Shoppers');

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
                    'year' => $thisYear,
                    'monthnum' => $thisMonth,
                    'day' => $thisDate
                );

                $the_query2 = new WP_Query($arg2);
                $user_count = $the_query2->found_posts;

                echo "['$timetoprint', $user_count],";
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

    var chart = new google.visualization.LineChart(document.getElementById('customerpurchasecustom'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_customerpurchasecustom = document.getElementById('chart_to_png_customerpurchasecustom');
        chart_to_png_customerpurchasecustom.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', customerpurchasecustom);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', customerpurchasecustom);
}
else {
    window.resize = customerpurchasecustom;
}
</script>
<h4>Purchases Between <?php echo $_POST['fromdate']; ?> to <?php echo $_POST['todate']; ?></h4>
<div id="customerpurchasecustom"></div><br /><br />
<?php
        } ?>
<?php
    } ?>


<!-- Chart to PNG -->
<div id="chart_to_png_customerpurchaseyear" style="display: none;"></div>
<div id="chart_to_png_customerpurchaselastmonth" style="display: none;"></div>
<div id="chart_to_png_customerpurchasethismonth" style="display: none;"></div>
<div id="chart_to_png_customerpurchase7days" style="display: none;"></div>
<div id="chart_to_png_customerpurchasetoday" style="display: none;"></div>
<div id="chart_to_png_customerpurchasecustom" style="display: none;"></div>
