<?php
/* Template Name: Dial Store */
require_once ABSPATH . 'vendor/autoload.php';
use Twilio\Twiml;

$response = new Twiml();
$dial = $response->dial();
$dial->number('865-475-8641');

echo $response;
