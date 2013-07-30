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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?=$site_name?> CMS</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="../css/admin.css" />
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" >
<table width="100%" height="600">
<tr height="100%">
<td valign="middle" align="center" width="100%" height="100%">
	<div id="loginfrm">
	<form id="login_frm" name="login_frm" action="userauth/userauth.php" method="post">
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
	}
?>	
	</div>
	<br />
<?php	
}
?>		
		<label>
			Username: 
		</label> 	
		<input type="text" name="username" class="txtfield" />
		<br />
		<br />
		<label>
			Password:&nbsp;   
		</label> 	
		<input type="password" name="password"  />
		<br />
		<a href="forgotpassword.php">Forgot Password?</a>
		<br />	
		<br />	
		<label>
			Language:&nbsp;   
		</label> 
		<select style="width:305px;" name="lang">
			<option value="gr">Ελληνικά</option>
			<option value="en">English</option>
		</select>	
		<br /><br />
		<img align="center" id="captcha" src="captcha.php?random_char=<?=$rand_char?>" alt="CAPTCHA Image" />
		<br />
		<br />
		<label>
			Code:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		</label>
		<input type="text" name="captcha_code" maxlength="6" />
		<br />
		<!--<a href="#" onclick="document.getElementById('captcha').src = '../securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>-->
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