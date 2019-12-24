<?php
/* Template Name: Dial Store */
require_once "/home3/istilist/public_html/vendor/autoload.php";
use Twilio\Twiml;

$response = new Twiml();
$dial = $response->dial();
$dial->number('865-475-8641');

echo $response;
