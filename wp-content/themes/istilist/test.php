<!DOCTYPE html>
<html>
<head></head>
<body>
<p>TEST</p>
</body>
</html>

<?php
global $wpdb;
$wpdb->show_errors = true;
$sql2 = "SELECT * FROM shoppers_folloup_messages WHERE message_type = 'thankyou' and store_id = 169";
$result2 = $wpdb->get_row($sql2);
if ($wpdb->last_error) {
    die('error=' . var_dump($wpdb->last_query) . ',' . var_dump($wpdb->error));
}
echo $result2->body;
?>
