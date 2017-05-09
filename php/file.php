<?php
header('content-type:text/html;charset=utf-8');

error_reporting(E_ALL | E_STRICT);
require('config/init.php');

$options=array(
	'upload_dir' => FILEPATH.$_SESSION['teamId'].'/',
	'upload_url' => "http://".PATH."files/".$_SESSION['teamId'].'/',
	);
/*$options=array(
	'upload_dir' => dirname(dirname(__FILE__)).'/files/4/',
	);*/
$upload_handler = new UploadHandler($options);

