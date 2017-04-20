<?php
require_once('config.php');

session_start();

date_default_timezone_set('Asia/Shanghai');
function __autoload($className){
	require_once("./class/{$className}Class.php");
}