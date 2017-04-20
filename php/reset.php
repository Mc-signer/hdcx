<?php
require_once("config/init.php");
$id=stripslashes(trim($_GET['id']));
$token = stripslashes(trim($_GET['token'])); 
$user=new user();
if($user->checkToken($id,$token)){
	$_SESSION['resetId']=$id;
	header("Location:http://".PATH."resetPassword.html");
}else{
	header("Location:http://".PATH."login.html");
}