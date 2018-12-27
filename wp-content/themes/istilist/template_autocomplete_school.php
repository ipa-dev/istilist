<?php /* Template Name: Autocomplete School/Event */ ?>
<?php
$q=$_GET['q'];
$my_data=mysql_real_escape_string($q);
global $wpdb;
global $user_ID;
$table_name = $wpdb->prefix.'dress_registration';
$sql="SELECT school_event FROM $table_name WHERE registered_by = $user_ID AND school_event LIKE '%$my_data%' ORDER BY school_event";
$results = $wpdb->get_results($sql);
//print_r($results);
if ($results) {
    foreach ($results as $r) {
        echo ucwords($r->school_event)."\n";
    }
}
