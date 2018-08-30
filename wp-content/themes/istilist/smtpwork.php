<?php
require("smtp.php");
require("sasl.php"); //SASL authentication
$from="noreply@istilist.com";
$smtp=new smtp_class;
//$smtp->host_name="smtp.gmail.com"; // Or IP address
$smtp->host_name="smtp.istilist.com";
$smtp->host_port=465;
$smtp->ssl=1;
$smtp->start_tls=0;
$smtp->localhost="localhost";
$smtp->direct_delivery=0;
$smtp->timeout=180;
$smtp->data_timeout=0;
$smtp->debug=1;
$smtp->html_debug=0;
$smtp->pop3_auth_host="";
//$smtp->user="coregen.testing@gmail.com"; // SMTP Username
$smtp->user="info@istilist.com";
$smtp->realm="";
//$smtp->password="coregen123"; // SMTP Password
$smtp->password="Formal!1468";
$smtp->workstation="";
$smtp->authentication_mechanism="";
