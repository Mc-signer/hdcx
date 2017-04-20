<?php
/**
* 
*/
class Member
{
	private $id;
	private $teamId;
	private $name;
	private $gender;
	private $contact;
	private $insititute;
	private $class;
	private $stunum;
	private $idcard;
	private $email;
	function __construct($name='',$gender='',$contact='',$insititute='',$class='',$stunum='',$idcard='',$email='',$teamId='',$id='')
	{
		$this->name=$name;
		$this->gender=$gender;
		$this->contact=$contact;
		$this->insititute=$insititute;
		$this->class=$class;
		$this->stunum=$stunum;
		$this->idcard=$idcard;
		$this->email=$email;
		$this->teamId=$teamId;
		$this->id=$id;
	}
	function addMember(){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$stmt=$mysqli->prepare("insert into members(team_id,name,gender,contact,insititute,class,stunum,idcard,email) values(?,?,?,?,?,?,?,?,?)");
		$stmt->bind_param("issssssss",$this->teamId,$this->name,$this->gender,$this->contact,$this->insititute,$this->class,$this->stunum,$this->idcard,$this->email);
		$stmt->execute(); 
		$mysqli->close();
		return true;
	}
	function check(){
		if(empty($this->teamId)||empty($this->name)||empty($this->gender)||empty($this->contact)||empty($this->insititute)||empty($this->class)||empty($this->stunum)||empty($this->idcard)||empty($this->email)){
			return "用户信息不能为空";
		}
		if(preg_match("/^\*$/",$this->name)||preg_match("/^\*$/",$this->gender)||preg_match("/^\*$/",$this->contact)||preg_match("/^\*$/",$this->insititute)||preg_match("/^\*$/",$this->class)||preg_match("/^\*$/",$this->stunum)||preg_match("/^\*$/",$this->idcard)||preg_match("/^\*$/",$this->email)){
			return "请填写所有信息";
		}
		return 1;
	}
	function delByTeamId($teamId){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$mysqli->query("delete from members where team_id = '$teamId'");
		$mysqli->close();
		return true;
	}
}