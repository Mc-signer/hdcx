<?php
/**
*管理员登录注册等行为
*成员：id，姓名，密码，所在院系，联系方式，级别（1为最高级管理员，2为二级管理员）
*方法：
*/
class Admin
{
	private $id;
	private $name;
	private $password;
	private $insititute;
	private $contact;
	private $degree;
	function __construct($name='',$password='',$insititute='',$contact='',$degree='1',$id='')
	{
		$this->name=$name;
		$this->password=$password;
		$this->insititute=$insititute;
		$this->contact=$contact;
		$this->degree=$degree;
		$this->id=$id;
	}
	function login(){
		if(empty($this->name)||empty($this->password)){
			return "用户名、密码不能为空";
		}
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from admins where name='{$this->name}' and password='".$this->password."'");
		if($result->num_rows){
			$row=$result->fetch_array(MYSQLI_ASSOC);
			$_SESSION['adminId']=$row['id'];
			$_SESSION['adminName']=$row['name'];
			$_SESSION['degree']=$row['degree'];
			$return=1;
		}else {
			$return="该用户不存在或密码错误";
		}
		$mysqli->close();
		return $return;
	}
	function checkReg(){
		if(empty($this->name)||empty($this->password)||empty($this->insititute)||empty($this->contact)){
			return "用户名、密码、所属与联系方式均不能为空";
		}
		//检查用户名是否符合规定
		if(!preg_match("/^(?!_)(?!.*?_$)[a-zA-Z0-9_\x80-\xff]+$/",$this->name)){
			return "用户名不符合规范，请重试";
		}
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from admins where name='{$this->name}'");
		if($result->num_rows){
			$mysqli->close();
			return "该用户名已存在，请重新输入";
		}
		$mysqli->close();
		//检查密码
		if(!preg_match("/^\w{6,18}$/",$this->password)){
			return "密码不符合规范，请使用由6-18位字母数字或字符组成的密码";
		}
		//检查联系方式与邮箱是否符合规定
		if(!preg_match("/^[0-9]{11}$/",$this->contact)){
			return "联系方式格式不正确，请重试";
		}
		return 1;
	}
	function add(){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("insert into admins(name,password,insititute,contact,degree) values('{$this->name}','{$this->password}','{$this->insititute}','{$this->contact}','2')");
		if($result){
			$mysqli->close();
			return true;
		}else{
			$mysqli->close();
			return false;
		}
	}
	function checkEdit($name,$password,$insititute,$contact){
		if(empty($name)||empty($password)||empty($insititute)||empty($contact)){
			return "用户名、密码、所属与联系方式均不能为空";
		}
		//检查用户名是否符合规定
		if(!preg_match("/^(?!_)(?!.*?_$)[a-zA-Z0-9_\x80-\xff]+$/",$name)){
			return "用户名不符合规范，请重试";
		}
		//检查密码
		if(!preg_match("/^\w{6,18}$/",$password)){
			return "密码不符合规范，请使用由6-18位字母数字或字符组成的密码";
		}
		//检查联系方式与邮箱是否符合规定
		if(!preg_match("/^[0-9]{11}$/",$contact)){
			return "联系方式格式不正确，请重试";
		}
		return 1;
	}
	function edit(){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("update admins set name='{$this->name}',password='{$this->password}',insititute='{$this->insititute}',contact='{$this->contact}' where id='{$this->id}'");
		if($result){
			$mysqli->close();
			return true;
		}else{
			$mysqli->close();
			return false;
		}
	}
	function del($id){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("delete from admins where id = '$id'");
		if($result){
			$mysqli->close();
			return true;
		}else {
			$mysqli->close();
			return false;
		}
	}
	function getAdmin(){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$result=$mysqli->query("select * from admins where id = '{$_SESSION['adminId']}'");
		$row=$result->fetch_array(MYSQLI_ASSOC);
		$mysqli->close();
		return $row;
	}
	function getRegUsers(){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select id,name,contact,email from t_users where status='1'");
		$rows=$result->fetch_all(MYSQLI_ASSOC);
		$mysqli->close();
		return $rows;
	}
	function agree($id){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		if(!$mysqli){
			return false;
		}
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select name,password,contact,email from t_users where id = '$id'");
		$row=$result->fetch_array(MYSQLI_ASSOC);
		$result=$mysqli->query("insert into users(name,password,contact,email) values('{$row['name']}','{$row['password']}','{$row['contact']}','{$row['email']}')");
		$result=$mysqli->query("delete from t_users where id = '$id'");
		$mysqli->close();
		return true;
	}
	function reject($id){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		if(!$mysqli){
			return false;
		}
		$mysqli->query("set names utf8");
		$result=$mysqli->query("update t_users set status = '-1' where id = '$id'");
		$mysqli->close();
		return true;
	}
	function getAdmins(){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		if(!$mysqli){
			return false;
		}
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from admins where degree = '2'");
		$rows=$result->fetch_all(MYSQLI_ASSOC);
		$mysqli->close();
		return $rows;
	}
	function pass($teamId,$pass){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		if(!$mysqli){
			return false;
		}
		$mysqli->query("set names utf8");
		$stmt=$mysqli->prepare("update teams set status = ? where id = ?");
		$stmt->bind_param("ii",$pass,$teamId);
		$stmt->execute();
		return true;
	}
}