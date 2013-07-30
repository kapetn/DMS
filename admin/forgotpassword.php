<?php 
session_start(); 
$session_id = session_id();
include_once("../securimage/securimage.php");
$securimage = new Securimage();

include_once("../classes/allinclude.php");
$random_obj = new randchar();
$rand_char = $random_obj->rand_char();
$_SESSION["rand_char"] = $rand_char;
//GET variables
$msg = $_GET["msg"];


//POST variables
$p_username = $_POST["username"];
$p_email = $_POST["email"];
$captcha_code = $_POST["captcha_code"];
$p_rand_char = $_POST["rand_char"];
$p_session_id = $_POST["session_id"]; 

if ($_POST && $p_session_id!=""){
	if ($p_username!="" && $p_email!="" && $captcha_code==$p_rand_char){
		//Check if username and email exist
		$q = "SELECT vrc_username_email 
			  FROM tbl_users 
			  WHERE vrc_username_email = '$p_username' 
			  AND vrc_email = '$p_email'";	
		$rs = mysql_query($q);
	    $numrows = mysql_num_rows($rs);
		if ($numrows>0){
			//Change the password and send email
			$new_password = $random_obj->rand_char();
			$qupd = "UPDATE tbl_users SET vrc_password = sha1('$new_password')  
					 WHERE vrc_username_email = '$p_username' 
					 AND vrc_email = '$p_email'";		
			$rsupd = mysql_query($qupd);		 
			
			//Mail the new password
			mail($p_email, "$site_name Password changed", "Your new password is: $new_password");
			
			header("Location:forgotpassword.php?msg=3");
			exit();	
		}else{
			header("Location:forgotpassword.php?msg=1");
			exit();
		}		
	}else{
		header("Location:forgotpassword.php?msg=1");
		exit();
	}	
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?=$site_name?> CMS - Forgot Password?</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="../css/admin.css" />
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" >
<table width="100%" height="600">
<tr height="100%">
<td valign="middle" align="center" width="100%" height="100%">
	<div id="loginfrm">
	<form id="login_frm" name="login_frm" action="forgotpassword.php" method="post">
	<input type="hidden" value="<?=$session_id?>" name="session_id" />
	<input type="hidden" value="<?=$rand_char?>" name="rand_char" />
		<h1><?=$site_name?></h1>
<?php
if ($msg!=""){
?>
	<div id="errordiv">	
<?php
	if ($msg=="1"){
?>
		Please type the correct Username, Password and Code
<?php	
	}elseif($msg=="2"){
?>	
		Please type the correct Code
<?php
	}elseif($msg=="3"){
?>
		Your password has changed. The new password was sent to your email. Thank you.
<?php	
	}
?>		
	</div>
	<br />
<?php	
}
?>		<div>
			<h1><a href="login.php" style="font-weight:bold;">Login</a></h1>
		</div>
		<div>Please type your Username, Email and Code.
			<br />
			A message with your new password will be sent to your email. 
			<br />
			Thank you. 
		</div>
		<label>
			Username: 
		</label> 	
		<input type="text" name="username" class="txtfield" />
		<br />
		<br />
		<label>
			Email:&nbsp; &nbsp; &nbsp; &nbsp;   
		</label> 	
		<input type="text" name="email" class="txtfield"  />
		<br /><br />
		<img align="center" id="captcha" src="captcha.php?random_char=<?=$rand_char?>" alt="CAPTCHA Image" />
		<br />
		<br />
		<label>
			Code:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		</label>
		<input type="text" name="captcha_code" maxlength="6" />
		<br />
		<br /><br />
		<input type="submit" name="submit_btn" value="Submit" />
		<input type="reset" name="reset_btn" value="Reset" />
	</form>	
	</div>
</td>
</tr>
</table>	
</body>
</html>