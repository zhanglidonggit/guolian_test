#!/usr/bin/php  
<?php
if (isset($_SERVER['REMOTE_ADDR'])) {  
    die('Command Line Only!');  
}  

set_time_limit(0);  

$_SERVER['PATH_INFO'] = $_SERVER['REQUEST_URI'] = $argv[1];
$_SERVER['SERVER_NAME'] = 'api.zld.com';
$_SERVER['HTTP_HOST'] = 'api.zld.com';  

require_once  realpath(dirname(dirname(__FILE__))).'/index.php';