<?php


include_once  "../vendor/autoload.php";


$cmbc = new \Owlet\CmbcSign\Cmbc();
$config = require_once "../src/config/sign.php";
$cmbc->config($config);

$result = $cmbc->request("https://api.cmbchina.com/xft/itax/tax/v2/TXQRYORG","POST",array());

var_dump($result);die();
