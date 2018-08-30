<?php /* Template Name: Autocomplete Designer */ ?>
<?php
$q=$_GET['q'];
$my_data=mysql_real_escape_string($q);
global $wpdb;
global $user_ID;
$table_name = $wpdb->prefix.'dress_registration';
$sql="SELECT designer FROM $table_name WHERE registered_by = $user_ID AND designer LIKE '%$my_data%' ORDER BY designer";
$results = $wpdb->get_results($sql);
//print_r($results);
if ($results) {
    foreach ($results as $r) {
        echo ucwords($r->designer)."\n";
    }
}
