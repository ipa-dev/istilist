<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="retailer_text_stats.csv"');
header("Content-Length: " . filesize("retailer_text_stats.csv"));
readfile("retailer_text_stats.csv");
header("Location: http://istilist.com/wp-admin/admin.php?page=textstats");
