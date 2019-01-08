<?php
$active_section = 'tf-today';
if (!empty($_GET['ar'])) {
    $active_section = decripted($_GET['ar']);
} 
?>
<input id="active_section" type="hidden" value="<?= $active_section ?>"/>
<div class="timeframe">
    <ul>
        <!-- TODO: Active section is denoted with class activeSection: append this in javascript -->
        <li id="tf-year">
            <a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?= encripted('tf-year'); ?>">Year</a>
        </li>
        <li id="tf-lastmonth">
            <a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?= encripted('tf-lastmonth'); ?>">Last Month</a>
        </li>
        <li id="tf-thismonth">
            <a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?= encripted('tf-thismonth'); ?>">This Month</a>
        </li>
        <li id="tf-7days">
            <a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?= encripted('tf-7days'); ?>">Last 7 Days</a>
        </li>
        <li id="tf-today">
            <a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?= encripted('tf-today'); ?>">Today</a>
        </li>
        <li id="tf-custom">
            <a href="<?php bloginfo('url'); ?>/analytics-reporting/?ar=<?= encripted('tf-custom'); ?>">Custom</a>
        </li>
    </ul>
</div>
<script>
    function setResizeEvent( callback ) {
        if (document.addEventListener) {
            window.addEventListener('resize', callback);
        }
        else if (document.attachEvent) {
            window.attachEvent('onresize', callback);
        }
        else {
            window.resize = callback;
        }
    }
    function googleCallback(hAxisTitle, hAxisMaxValue, columnTitle, dataArray){
        var data = new google.visualization.DataTable();

        data.addColumn('string', columnTitle);
        data.addColumn('number', 'No. of Shoppers');
        data.addColumn('number', 'No. of Purchases');

        data.addRows(dataArray);

        var options = {
            hAxis: {
                title: hAxisTitle,
                maxValue: hAxisMaxValue
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
    jQuery( document ).ready( function() {
        var activeSection = document.getElementById( 'active_section' ).value;
        jQuery.ajax({
            url: window.location.origin + '/process-analytics',
            method: 'POST',
            data: {
                activeSection: activeSection
            },
            success: function( data, textStatus, jqXHR ) {
                google.charts
                .setOnLoadCallback( googleCallback( data.hAxisTitle, data.hAxisMaxValue,
                                                   data.columnTitle, data.dataArray ) );

                setResizeEvent(googleCallback( data.hAxisTitle, data.hAxisMaxValue,
                                                   data.columnTitle, data.dataArray ) );
            }
        });
    });
</script>


<!-- Generate Report -->

<h4>Traffic Flow <?php echo date('Y'); ?></h4>
<div id="trafficflowyear"></div><br /><br />
<?php if ($active_section == 'tf-lastmonth') { ?>

<h4>Traffic Flow Last Month</h4>
<div id="trafficflowlastmonth"></div><br /><br />
<?php } if ($active_section == 'tf-thismonth') { ?>
<script>
// code for This month
google.charts.setOnLoadCallback( trafficflowthismonth );
function trafficflowthismonth(){
    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Days');
    data.addColumn('number', 'No. of Shoppers');
    data.addColumn('number', 'No. of Purchases');

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
setResizeEvent( trafficflowthismonth );
</script>
<h4>Traffic Flow This Month</h4>
<div id="trafficflowthismonth"></div>
<?php } if ($active_section == 'tf-7days') { ?>
<script>
// code for last 7 days
google.charts.setOnLoadCallback(trafficflow7days);
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
setResizeEvent( trafficflow7days );
</script>
<h4>Traffic Flow last 7 days</h4>
<div id="trafficflow7days"></div><br /><br />
<?php } if ($active_section == 'tf-today') {
        ?>
<script>
// code for today
google.charts.setOnLoadCallback(trafficflowtoday);
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
setResizeEvent( trafficflowtoday );
</script>
<h4>Traffic Flow Today</h4>
<div id="trafficflowtoday"></div><br /><br />
<?php } if ($active_section == 'tf-custom') { ?>
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
<?php if (isset($_POST['showreport'])) { ?>
<script>
// code for custom date range
google.charts.setOnLoadCallback(trafficflowcustom);
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
setResizeEvent( trafficflowcustom )
</script>
<h4>Traffic Flow Between <?php echo $_POST['fromdate']; ?> to <?php echo $_POST['todate']; ?></h4>
<div id="trafficflowcustom"></div><br /><br />
<?php
        } 
    } ?>


<!-- Chart to PNG -->
<div id="chart_to_png_trafficflowyear" style="display: none;"></div>
<div id="chart_to_png_trafficflowlastmonth" style="display: none;"></div>
<div id="chart_to_png_trafficflowthismonth" style="display: none;"></div>
<div id="chart_to_png_trafficflow7days" style="display: none;"></div>
<div id="chart_to_png_trafficflowtoday" style="display: none;"></div>
<div id="chart_to_png_trafficflowcustom" style="display: none;"></div>
