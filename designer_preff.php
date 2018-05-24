<?php
global $wpdb;
global $user_ID;
$store_id = get_user_meta($user_ID, 'store_id', true);
$table_name1 = $wpdb->prefix.'shoppers';

if(!empty($_GET['ar'])){
    $active_section = decripted($_GET['ar']);
} else {
    $active_section = 'tf-today';
}

if(!empty($_GET['sp'])){
    $sp_active_section = decripted($_GET['sp']);
} else {
    $sp_active_section = 'sp-today';
}

if(!empty($_GET['st'])){
    $st_active_section = decripted($_GET['st']);
} else {
    $st_active_section = 'st-today';
}

if(!empty($_GET['cl'])){
    $cl_active_section = decripted($_GET['cl']);
} else {
    $cl_active_section = 'cl-today';
}

if(!empty($_GET['sz'])){
    $sz_active_section = decripted($_GET['sz']);
} else {
    $sz_active_section = 'sz-today';
}
?>
<div class="timeframe">
    <ul>
        <li class="<?php if($sp_active_section == 'sp-year'){ echo 'activeSection'; } ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?sp=<?php echo encripted('sp-year'); ?>">Year</a></li>
        <li class="<?php if($sp_active_section == 'sp-lastmonth'){ echo 'activeSection'; } ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?sp=<?php echo encripted('sp-lastmonth'); ?>">Last Month</a></li>
        <li class="<?php if($sp_active_section == 'sp-thismonth'){ echo 'activeSection'; } ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?sp=<?php echo encripted('sp-thismonth'); ?>">This Month</a></li>
        <li class="<?php if($sp_active_section == 'sp-7days'){ echo 'activeSection'; } ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?sp=<?php echo encripted('sp-7days'); ?>">Last 7 Days</a></li>
        <li class="<?php if($sp_active_section == 'sp-today'){ echo 'activeSection'; } ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?sp=<?php echo encripted('sp-today'); ?>">Today</a></li>
        <li class="<?php if($sp_active_section == 'sp-custom'){ echo 'activeSection'; } ?>"><a href="<?php bloginfo('url'); ?>/analytics-reporting/?sp=<?php echo encripted('sp-custom'); ?>">Custom</a></li>
    </ul>
</div>

<!-- Generate Report -->
<?php if($sp_active_section == 'sp-year'){ ?>
<script>
// code for year
google.setOnLoadCallback(designer_preff_year);
function designer_preff_year(){
    var data = new google.visualization.DataTable();
    
    data.addColumn('string', 'Designers');
    data.addColumn('number', 'No. of Shoppers');
    
    data.addRows([
        <?php
        $year = date('Y');
        $designer_pref_args = array(
            'post_type' => 'designer_pref',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_key' => 'store_id',
            'meta_value' => $store_id,
            'orderby' => 'title',
            'order' => 'ASC'
        );
        $designer_pref = new WP_Query($designer_pref_args);
        if ( $designer_pref->have_posts() ) {
            while ( $designer_pref->have_posts() ) : $designer_pref->the_post();
                $designer_name = get_the_title();
                $arg1 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_key' => 'store_id',
                    'meta_value' => $store_id,
                    'meta_query' => array(
                        array(
                            'key' => 'design_preferences',
                            'value' => serialize(strval($designer_name)),
                            'compare' => 'LIKE',
                        ),
                    ),
                    'year' => $year,
                );
                
                $the_query1 = new WP_Query( $arg1 );
                $user_count = $the_query1->found_posts;
                echo "['$designer_name', $user_count],";
            endwhile;
            wp_reset_postdata();
        }
        ?>
    ]);
    
    var options = {
        hAxis: {
            title: 'Number of Shoppers'
        },
        vAxis: {
            title: 'Designers'
        },
        legend: {position: 'top'},
        bar: {groupWidth: "10%"},
        colors: ['#14b9d6', '#097138'],
        width: '100%',
        height: 350
    };
      
    var chart = new google.visualization.BarChart(document.getElementById('designer_preff_year'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_designer_preff_year = document.getElementById('chart_to_png_designer_preff_year');
        chart_to_png_designer_preff_year.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', designer_preff_year);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', designer_preff_year);
}
else {
    window.resize = designer_preff_year;
}
</script>
<h4>Designer Preferences in <?php echo date('Y'); ?></h4>
<div id="designer_preff_year"></div><br /><br />

<!-- Chart to PNG -->
<div id="chart_to_png_designer_preff_year" style="display: none;"></div>
<?php } ?>
<!--  -->


<?php if($sp_active_section == 'sp-lastmonth'){ ?>
<script>
// code for last month
google.setOnLoadCallback(designer_preff_lastmonth);
function designer_preff_lastmonth(){
    var data = new google.visualization.DataTable();
    
    data.addColumn('string', 'Designers');
    data.addColumn('number', 'No. of Shoppers');
    
    data.addRows([
        <?php
        $year = date('Y');
        $month = date('m')-1;
        
        $designer_pref_args = array(
            'post_type' => 'designer_pref',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_key' => 'store_id',
            'meta_value' => $store_id,
            'orderby' => 'title',
            'order' => 'ASC'
        );
        $designer_pref = new WP_Query($designer_pref_args);
        if ( $designer_pref->have_posts() ) {
            while ( $designer_pref->have_posts() ) : $designer_pref->the_post();
                $designer_name = get_the_title();
                $arg1 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_key' => 'store_id',
                    'meta_value' => $store_id,
                    'meta_query' => array(
                        array(
                            'key' => 'design_preferences',
                            'value' => serialize(strval($designer_name)),
                            'compare' => 'LIKE',
                        ),
                    ),
                    'year' => $year,
                    'monthnum' => $month,
                );
                
                $the_query1 = new WP_Query( $arg1 );
                $user_count = $the_query1->found_posts;
                echo "['$designer_name', $user_count],";
            endwhile;
            wp_reset_postdata();
        }
        ?>
    ]);
    
    var options = {
        hAxis: {
            title: 'Number of Shoppers'
        },
        vAxis: {
            title: 'Designers'
        },
        legend: {position: 'top'},
        bar: {groupWidth: "10%"},
        colors: ['#14b9d6', '#097138'],
        width: '100%',
        height: 350
    };
      
    var chart = new google.visualization.BarChart(document.getElementById('designer_preff_lastmonth'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_designer_preff_lastmonth = document.getElementById('chart_to_png_designer_preff_lastmonth');
        chart_to_png_designer_preff_lastmonth.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', designer_preff_lastmonth);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', designer_preff_lastmonth);
}
else {
    window.resize = designer_preff_lastmonth;
}
</script>
<h4>Designer Preferences in Last Month</h4>
<div id="designer_preff_lastmonth"></div><br /><br />

<!-- Chart to PNG -->
<div id="chart_to_png_designer_preff_lastmonth" style="display: none;"></div>
<?php } ?>
<!--  -->


<?php if($sp_active_section == 'sp-thismonth'){ ?>
<script>
// code for this month
google.setOnLoadCallback(designer_preff_thismonth);
function designer_preff_thismonth(){
    var data = new google.visualization.DataTable();
    
    data.addColumn('string', 'Designers');
    data.addColumn('number', 'No. of Shoppers');
    
    data.addRows([
        <?php
        $year = date('Y');
        $month = date('m');
        
        $designer_pref_args = array(
            'post_type' => 'designer_pref',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_key' => 'store_id',
            'meta_value' => $store_id,
            'orderby' => 'title',
            'order' => 'ASC'
        );
        $designer_pref = new WP_Query($designer_pref_args);
        if ( $designer_pref->have_posts() ) {
            while ( $designer_pref->have_posts() ) : $designer_pref->the_post();
                $designer_name = get_the_title();
                $arg1 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_key' => 'store_id',
                    'meta_value' => $store_id,
                    'meta_query' => array(
                        array(
                            'key' => 'design_preferences',
                            'value' => serialize(strval($designer_name)),
                            'compare' => 'LIKE',
                        ),
                    ),
                    'year' => $year,
                    'monthnum' => $month,
                );
                
                $the_query1 = new WP_Query( $arg1 );
                $user_count = $the_query1->found_posts;
                echo "['$designer_name', $user_count],";
            endwhile;
            wp_reset_postdata();
        }
        ?>
    ]);
    
    var options = {
        hAxis: {
            title: 'Number of Shoppers'
        },
        vAxis: {
            title: 'Designers'
        },
        legend: {position: 'top'},
        bar: {groupWidth: "10%"},
        colors: ['#14b9d6', '#097138'],
        width: '100%',
        height: 350
    };
      
    var chart = new google.visualization.BarChart(document.getElementById('designer_preff_thismonth'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_designer_preff_thismonth = document.getElementById('chart_to_png_designer_preff_thismonth');
        chart_to_png_designer_preff_thismonth.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', designer_preff_thismonth);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', designer_preff_thismonth);
}
else {
    window.resize = designer_preff_thismonth;
}
</script>
<h4>Designer Preferences in This Month</h4>
<div id="designer_preff_thismonth"></div><br /><br />

<!-- Chart to PNG -->
<div id="chart_to_png_designer_preff_thismonth" style="display: none;"></div>
<?php } ?>
<!--  -->


<?php if($sp_active_section == 'sp-7days'){ ?>
<script>
// code for last 7 days
google.setOnLoadCallback(designer_preff_7days);
function designer_preff_7days(){
    var data = new google.visualization.DataTable();
    
    data.addColumn('string', 'Designers');
    data.addColumn('number', 'No. of Shoppers');
    
    data.addRows([
        <?php
        $year = date('Y');
        $month = date('m');
        $today = date('Y-m-d');
        $seven_days_ago = date('Y-m-d', strtotime('-7 days'));
        
        $designer_pref_args = array(
            'post_type' => 'designer_pref',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_key' => 'store_id',
            'meta_value' => $store_id,
            'orderby' => 'title',
            'order' => 'ASC'
        );
        $designer_pref = new WP_Query($designer_pref_args);
        if ( $designer_pref->have_posts() ) {
            while ( $designer_pref->have_posts() ) : $designer_pref->the_post();
                $designer_name = get_the_title();
                $arg1 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_key' => 'store_id',
                    'meta_value' => $store_id,
                    'meta_query' => array(
                        array(
                            'key' => 'design_preferences',
                            'value' => serialize(strval($designer_name)),
                            'compare' => 'LIKE',
                        ),
                    ),
                    'date_query' => array(
                        array(
                            'after' => '1 week ago'
                        )
                    )
                );
                
                $the_query1 = new WP_Query( $arg1 );
                $user_count = $the_query1->found_posts;
                echo "['$designer_name', $user_count],";
            endwhile;
            wp_reset_postdata();
        }
        ?>
    ]);
    
    var options = {
        hAxis: {
            title: 'Number of Shoppers'
        },
        vAxis: {
            title: 'Designers'
        },
        legend: {position: 'top'},
        bar: {groupWidth: "10%"},
        colors: ['#14b9d6', '#097138'],
        width: '100%',
        height: 350
    };
      
    var chart = new google.visualization.BarChart(document.getElementById('designer_preff_7days'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_designer_preff_7days = document.getElementById('chart_to_png_designer_preff_7days');
        chart_to_png_designer_preff_7days.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', designer_preff_7days);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', designer_preff_7days);
}
else {
    window.resize = designer_preff_7days;
}
</script>
<h4>Designer Preferences in Last 7 Days</h4>
<div id="designer_preff_7days"></div><br /><br />

<!-- Chart to PNG -->
<div id="chart_to_png_designer_preff_7days" style="display: none;"></div>
<?php } ?>
<!--  -->


<?php if($sp_active_section == 'sp-today'){ ?>
<script>
// code for today
google.setOnLoadCallback(designer_preff_today);
function designer_preff_today(){
    var data = new google.visualization.DataTable();
    
    data.addColumn('string', 'Designers');
    data.addColumn('number', 'No. of Shoppers');
    
    data.addRows([
        <?php
        $year = date('Y');
        $month = date('m');
        $date = date('d');
        
        $designer_pref_args = array(
            'post_type' => 'designer_pref',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_key' => 'store_id',
            'meta_value' => $store_id,
            'orderby' => 'title',
            'order' => 'ASC'
        );
        $designer_pref = new WP_Query($designer_pref_args);
        if ( $designer_pref->have_posts() ) {
            while ( $designer_pref->have_posts() ) : $designer_pref->the_post();
                $designer_name = get_the_title();
                $arg1 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_key' => 'store_id',
                    'meta_value' => $store_id,
                    'meta_query' => array(
                        array(
                            'key' => 'design_preferences',
                            'value' => $designer_name,
                            'compare' => 'LIKE',
                        ),
                    ),
                    'year' => $year,
                    'monthnum' => $month,
                    'day' => $date
                );
                
                $the_query1 = new WP_Query( $arg1 );
                $user_count = $the_query1->found_posts;
                echo "['$designer_name', $user_count],";
            endwhile;
            wp_reset_postdata();
        }
        ?>
    ]);
    
    var options = {
        hAxis: {
            title: 'Number of Shoppers'
        },
        vAxis: {
            title: 'Designers'
        },
        legend: {position: 'top'},
        bar: {groupWidth: "10%"},
        colors: ['#14b9d6', '#097138'],
        width: '100%',
        height: 350
    };
      
    var chart = new google.visualization.BarChart(document.getElementById('designer_preff_today'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_designer_preff_today = document.getElementById('chart_to_png_designer_preff_today');
        chart_to_png_designer_preff_today.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', designer_preff_today);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', designer_preff_today);
}
else {
    window.resize = designer_preff_today;
}
</script>
<h4>Designer Preferences Today</h4>
<div id="designer_preff_today"></div><br /><br />

<!-- Chart to PNG -->
<div id="chart_to_png_designer_preff_today" style="display: none;"></div>
<?php } ?>
<!--  -->


<?php if($sp_active_section == 'sp-custom'){ ?>
<div class="daterange">
    <form method="post" action="<?php bloginfo('url'); ?>/analytics-reporting/?sp=<?php echo encripted('sp-custom'); ?>">
        <table>
            <tr>
                <td>Date Range : </td>
                <td><input id="fromdate" type="text" name="designer_preff_startdate" placeholder="mm/dd/yy" /></td>
                <td style="text-align: center;"> TO </td>
                <td><input id="todate" type="text" name="designer_preff_enddate" placeholder="mm/dd/yy" /></td>
                <td><input type="submit" name="designer_preff_showreport" value="GO" /></td>
            </tr>
        </table>
    </form>
</div>
<?php if(isset($_POST['designer_preff_showreport'])){ ?>
<script>
// code for custom
google.setOnLoadCallback(designer_preff_custom);
function designer_preff_custom(){
    var data = new google.visualization.DataTable();
    
    data.addColumn('string', 'Designers');
    data.addColumn('number', 'No. of Shoppers');
    
    data.addRows([
        <?php
        $date1 = explode('-', $_POST['designer_preff_startdate']);
            
        $startDate_month = $date1[0];
        $startDate_year = $date1[2];
        $startDate_date = $date1[1];
        
        $startDate = strtotime($startDate_year.'-'.$startDate_month.'-'.$startDate_date); 
        
        $date2 = explode('-', $_POST['designer_preff_enddate']);
        
        $endDate_month = $date2[0];
        $endDate_year = $date2[2];
        $endDate_date = $date2[1];
        
        $endDate = strtotime($endDate_year.'-'.$endDate_month.'-'.$endDate_date);
        
        $startDate1 = date('d-m-Y 00:01:00', $startDate);
        $endDate1 = date('d-m-Y 23:59:59', $endDate);
        
        $designer_pref_args = array(
            'post_type' => 'designer_pref',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_key' => 'store_id',
            'meta_value' => $store_id,
            'orderby' => 'title',
            'order' => 'ASC'
        );
        $designer_pref = new WP_Query($designer_pref_args);
        if ( $designer_pref->have_posts() ) {
            while ( $designer_pref->have_posts() ) : $designer_pref->the_post();
                $designer_name = get_the_title();
                $arg1 = array(
                    'post_type' => 'shopper',
                    'post_status' => 'publish',
                    'meta_key' => 'store_id',
                    'meta_value' => $store_id,
                    'meta_query' => array(
                        array(
                            'key' => 'design_preferences',
                            'value' => serialize(strval($designer_name)),
                            'compare' => 'LIKE',
                        ),
                    ),
                    'date_query' => array(
                        array(
                            'after' => "$startDate1",
                            'before' => "$endDate1",
                			'inclusive' => true,
                        )
                    )
                );
                
                $the_query1 = new WP_Query( $arg1 );
                $user_count = $the_query1->found_posts;
                echo "['$designer_name', $user_count],";
            endwhile;
            wp_reset_postdata();
        }
        ?>
    ]);
    
    var options = {
        hAxis: {
            title: 'Number of Shoppers'
        },
        vAxis: {
            title: 'Designers'
        },
        legend: {position: 'top'},
        bar: {groupWidth: "10%"},
        colors: ['#14b9d6', '#097138'],
        width: '100%',
        height: 350
    };
      
    var chart = new google.visualization.BarChart(document.getElementById('designer_preff_custom'));
    google.visualization.events.addListener(chart, 'ready', function () {
        var chart_to_png_designer_preff_custom = document.getElementById('chart_to_png_designer_preff_custom');
        chart_to_png_designer_preff_custom.innerHTML = chart.getImageURI();
    });
    chart.draw(data, options);
}
if (document.addEventListener) {
    window.addEventListener('resize', designer_preff_custom);
}
else if (document.attachEvent) {
    window.attachEvent('onresize', designer_preff_custom);
}
else {
    window.resize = designer_preff_custom;
}
</script>
<h4>Designer Preferences Between <?php echo $_POST['designer_preff_startdate']; ?> to <?php echo $_POST['designer_preff_enddate']; ?></h4>
<div id="designer_preff_custom"></div><br /><br />

<!-- Chart to PNG -->
<div id="chart_to_png_designer_preff_custom" style="display: none;"></div>
<?php } ?>
<?php } ?>
<!--  -->