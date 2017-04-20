<?php
/**
*用于组织用户登录等行为
**/
class User{
	private $id;
	protected $name;
	protected $password;
	protected $contact;
	protected $email;
	function __construct($name='',$password='',$contact='',$email='',$id=''){
		$this->name=$name;
		$this->password=sha1($password);
		$this->contact=$contact;
		$this->email=$email;
		$this->id=$id;
	}
	function setUser($name){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");

		$result=$mysqli->query("select * from users where name='$name'");
		$row=$result->fetch_array(MYSQLI_ASSOC);
		$this->id=$row['id'];
		$this->name=$row['name'];
		$this->password=$row['password'];
		$this->contact=$row['contact'];
		$this->email=$row['email'];
		$mysqli->close();
	}
	function login(){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");

		$result=$mysqli->query("select * from users where name='{$this->name}'");
		if(!$result->num_rows){
			$result=$mysqli->query("select * from t_users where name='{$this->name}'");
			if(!$result->num_rows){
				$mysqli->close();
				return "用户名不存在！";
			}
			else{
				$row=$result->fetch_array(MYSQLI_ASSOC);
				if($row['status']==0)return "邮箱尚未激活，请先激活邮箱";
				else if($row['status']==1)return "请等待管理员审核";
			}
		}
		$row=$result->fetch_array(MYSQLI_ASSOC);
		if($this->password!=$row['password']){
			$mysqli->close();
			return "密码错误!";
		}
		$_SESSION['userId']=$row['id'];
		$_SESSION['userName']=$row['name'];
		$_SESSION['contact']=$row['contact'];
		$_SESSION['email']=$row['email'];
		$mysqli->close();
		return 1;
	}
	function checkEdit($name,$contact,$email){
		//检查用户是否存在
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from users where name='$name'");
		if(!$result->num_rows){
			$mysqli->close();
			return "该用户不存在";
		}
		$mysqli->close();
		//检查联系方式与邮箱是否符合规定
		if(!preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/",$email)){
			return "邮箱格式不正确，请重试";
		}
		if(!preg_match("/^[0-9]{11}$/",$contact)){
			return "联系方式格式不正确，请填写您的手机号";
		}
		return 1;
	}
	function editUser($name,$contact,$email){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("update users set contact='$contact',email='$email' where name='$name'");
		if(!$result){
			header("http://".PATH."error.html");
			$mysqli->close();
			exit();
		}
		$_SESSION['contact']=$contact;
		$_SESSION['email']=$email;
		$mysqli->close();
		return true;
	}
	function editPassword($name,$old,$new,$again){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from users where password='".sha1($old)."' and name='$name'");
		if(!$result->num_rows){
			$mysqli->close();
			return "密码错误，请重试";
		}
		if($old==$new){
			return "新密码与旧密码一致";
		}
		if($again!=$new){
			return "两次输入密码必须一致";
		}
		$result=$mysqli->query("update users set password='".sha1($new)."' where name='$name'");
		if(!$result){
			$mysqli->close();
			return "服务器错误！";
			exit();
		}
		$mysqli->close();
		return 1;
	}
	function checkNameOrEmail($value){
		$value=trim($value);
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		if(!preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/",$value)){
			$result=$mysqli->query("select * from users where name='{$value}'");
			if(!$result->num_rows){
				$mysqli->close();
				return "该用户不存在！";
			}
			$row=$result->fetch_array(MYSQLI_ASSOC);
			$this->id=$row['id'];
			$this->name=$row['name'];
			$this->contact=$row['contact'];
			$this->email=$row['email'];
		}else{
			$result=$mysqli->query("select * from users where email='{$value}'");
			if(!$result->num_rows){
				$mysqli->close();
				return "该邮箱不存在！";
			}
			$row=$result->fetch_array(MYSQLI_ASSOC);
			$this->id=$row['id'];
			$this->name=$row['name'];
			$this->contact=$row['contact'];
			$this->email=$row['email'];
		}
		$mysqli->close();	
		return 1;
	}
	function sendEmail(){
		$token=sha1($this->id.time());
		$url="http://".PATH."php/reset.php?id={$this->id}&token=$token";
		require_once("../PHPMailer-master/PHPMailerAutoload.php");

		//Create a new PHPMailer instance
		$mail = new PHPMailer;
		$mail->Charset="UTF-8";
		//Tell PHPMailer to use SMTP
		$mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;
		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';
		//Set the hostname of the mail server
		$mail->Host = "smtp.163.com";
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port = 25;
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		//Username to use for SMTP authentication
		$mail->Username = EMAIL;
		//Password to use for SMTP authentication
		$mail->Password = EMAIL_PW;
		//Set who the message is to be sent from
		$mail->setFrom(EMAIL, '华电团委');
		//Set an alternative reply-to address
		$mail->addReplyTo(EMAIL, '华电团委');
		//Set who the message is to be sent to
		$mail->addAddress($this->email, $this->name);
		//Set the subject line
		$mail->Subject = '华北电力大学（保定）大学生创新网邮箱验证';
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->IsHTML=(true);
		$mail->Body="
		您好，这里是华北电力大学（保定）大学生创新网，请点击以下链接完成密码重置：
		$url
		如果以上链接不能点击，请将以上链接复制粘贴到地址栏访问。
		如果您从未申请密码重置，对您造成的不便，敬请谅解。
	
		";

		//send the message, check for errors
		if (!$mail->send()) {
		    return "Mailer Error: " . $mail->ErrorInfo;
		}
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$tokenExptime=time()+60*60*24;
		$result=$mysqli->query("insert into resets(userId,token,token_exptime) values('{$this->id}','{$token}','{$tokenExptime}')");
		if(!$result){
			$mysqli->close();
			return false;
		}
		$mysqli->close();
		return 1;
	}
	function checkToken($id,$token){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from resets where userId='$id' and token='$token'");
		if(!$result->num_rows){
			$mysqli->close();
			return false;
		}
		$row=$result->fetch_array(MYSQLI_ASSOC);
		if(time()>$row['token_exptime']){
			$mysqli->num_rows;
			return false;
		}
		$mysqli->close();
		return true;
	}
	function reset($new,$again){
		if($new!=$again){
			return "两次输入密码不一致，请重试";
		}
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("update users set password='".sha1($new)."' where id='{$_SESSION['resetId']}'");
		if($result){
			$mysqli->query("delete from resets where userId='{$_SESSION['resetId']}'");
			$_SESSION['resetId']='';
		}
		$mysqli->close();
		return 1;
	}
	function getNewUserNum(){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select id from t_users where status='1'");
		$num=$result->num_rows;
		$mysqli->close();
		return $num;
	}
}
