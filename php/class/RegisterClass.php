<?php
/**
* 用于组织处理用户注册相关行为
*/
class Register extends User
{
	private $token;
	private $regtime;
	function __construct($name='',$password='',$contact='',$email=''){
		$this->name=$name;
		$this->password=sha1($password);
		$this->contact=$contact;
		$this->email=$email;
		$this->regtime=time();
		$this->token=sha1($name.$password.$this->regtime);
	}
	function checkReg($password){
		if(empty($this->name)||empty($this->password)||empty($this->contact)||empty($this->email)){
			return "用户名、密码、联系方式与邮箱均不能为空";
		}
		//检查用户名是否符合规定
		if(!preg_match("/^(?!_)(?!.*?_$)[a-zA-Z0-9_\x80-\xff]+$/",$this->name)){
			return "用户名不符合规范，请重试";
		}
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from users where name='{$this->name}'");
		if($result->num_rows){
			$mysqli->close();
			return "该用户名已存在，请重新输入";
		}

		$result=$mysqli->query("select * from t_users where name='{$this->name}'");
		if($result->num_rows){
			$mysqli->close();
			return "请勿重复注册";
		}
		$mysqli->close();
		//检查密码
		if(!preg_match("/^\w{6,18}$/",$password)){
			return "密码不符合规范，请使用由6-18位字母数字及字符组成的密码";
		}
		//检查联系方式与邮箱是否符合规定
		if(!preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/",$this->email)){
			return "邮箱格式不正确，请重试";
		}
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from users inner join t_users where users.email='{$this->email}' or t_users.email='{$this->email}'");
		if($result->num_rows){
			$mysqli->close();
			return "该邮箱已被注册，请更换邮箱";
		}
		$mysqli->close();
		if(!preg_match("/^[0-9]{11}$/",$this->contact)){
			return "联系方式格式不正确，请填写您的手机号";
		}
		return 1;
	}
	function sendEmail(){
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
		$url="http://".PATH."php/active.php?verify={$this->token}";
		$mail->Body="
		您好，感谢您注册华北电力大学（保定）大学生创新网，请点击以下链接完成验证：
		$url
		如果以上链接不能点击，请将以上链接复制粘贴到地址栏访问。
		如果您从未申请注册，对您造成的不便，敬请谅解。
		";

		//send the message, check for errors
		if (!$mail->send()) {
		    return "Mailer Error: " . $mail->ErrorInfo;
		}
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$deadline=$this->regtime+60*60*24;
		$result=$mysqli->query("insert into t_users(name,password,contact,token,token_exptime,email) values('{$this->name}','{$this->password}','{$this->contact}','{$this->token}','{$deadline}','{$this->email}')");
		if(!$result){
			$mysqli->close();
			return false;
		}
		$mysqli->close();
		return 1;
	}
	function register($verify){
		$nowtime = time(); 
		 
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select id,token_exptime from t_users where token='$verify'"); 
		$row=$result->fetch_array(MYSQLI_ASSOC);
		if($row){ 
		    if($nowtime>$row['token_exptime']){ //24hour 
		        $msg = '您的激活有效期已过，请重新申请注册。'; 
		        $mysqli->query("delete from t_users where id = '{$row['id']}'");
		    }else{
		    	//$mysqli->query("insert into users(name,password,contact,email) values('{$this->name}','{$this->password}','{$this->contact}','{$this->email}')");
		    	$mysqli->query("update t_users set status ='1' where id = '{$row['id']}'");
		        $msg = 1;
		    } 
		}else{ 
		    $msg = '该用户不存在，请重新申请注册。';
		} 
		$mysqli->close();
		return $msg; 
	}
}