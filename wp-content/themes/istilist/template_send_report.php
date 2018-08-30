<?php /* Template Name: Send Report */ ?>
<?php
require('phpToPDF.php');

$chart1 = $_POST['chart1'];
$img1 = str_replace('data:image/png;base64,', '', $chart1);
$filename1 = dirname(__FILE__).'/reports/sampleimage1.png';
$ifp1 = fopen($filename1, "wb");
fwrite($ifp1, base64_decode($img1));
fclose($ifp1);

$chart2 = $_POST['chart2'];
$img2 = str_replace('data:image/png;base64,', '', $chart2);
$filename2 = dirname(__FILE__).'/reports/sampleimage2.png';
$ifp2 = fopen($filename2, "wb");
fwrite($ifp2, base64_decode($img2));
fclose($ifp2);

$chart3 = $_POST['chart3'];
$img3 = str_replace('data:image/png;base64,', '', $chart3);
$filename3 = dirname(__FILE__).'/reports/sampleimage3.png';
$ifp3 = fopen($filename3, "wb");
fwrite($ifp3, base64_decode($img3));
fclose($ifp3);

$chart4 = $_POST['chart4'];
$img4 = str_replace('data:image/png;base64,', '', $chart4);
$filename4 = dirname(__FILE__).'/reports/sampleimage4.png';
$ifp4 = fopen($filename4, "wb");
fwrite($ifp4, base64_decode($img4));
fclose($ifp4);

$chart5 = $_POST['chart5'];
$img5 = str_replace('data:image/png;base64,', '', $chart5);
$filename5 = dirname(__FILE__).'/reports/sampleimage5.png';
$ifp5 = fopen($filename5, "wb");
fwrite($ifp5, base64_decode($img5));
fclose($ifp5);

//$my_html = $chart1_png."<br /><br />".$chart2."<br /><br />".$chart3."<br /><br />".$chart4."<br /><br />".$chart5."<br />";

$my_html = $imgsrc;

//Set Your Options -- see documentation for all options
$pdf_options = array(
      "source_type" => 'html',
      "source" => $my_html,
      "action" => 'save',
      "save_directory" => dirname(__FILE__).'/reports',
      "file_name" => 'analytics_report.pdf'
);

//Code to generate PDF file from options above
phptopdf($pdf_options);

$emails = explode(',', get_user_meta($user_ID, 'reporting', true));
print_r($emails);
foreach ($emails as $to) {
    $admin_name = get_bloginfo('name');
    $to = $to;
    $from = get_option('admin_email');
    $headers = 'From: '.$from . "\r\n";
    $headers .= "Reply-To: ".get_option('admin_email')."\r\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $subject = "Analytics Report";

    $msg = '<strong>Traffic Flow Today</strong><img src="'.get_bloginfo('template_directory').'/reports/sampleimage1.png" /><br /><br /><strong>Traffic Flow Weekly</strong><img src="'.get_bloginfo('template_directory').'/reports/sampleimage2.png" /><br /><br /><strong>Traffic Flow This Month</strong><img src="'.get_bloginfo('template_directory').'/reports/sampleimage3.png" /><br /><br /><strong>Stylist/Employee Conversion Rates Weekly</strong><img src="'.get_bloginfo('template_directory').'/reports/sampleimage4.png" /><br /><br /><strong>Stylist/Employee Conversion Rates This Month</strong><img src="'.get_bloginfo('template_directory').'/reports/sampleimage5.png" />';
    wp_mail($to, $subject, $msg, $headers);
}
?>