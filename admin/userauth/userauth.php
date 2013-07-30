<?php
ob_start();
session_start();
include_once("../../classes/allinclude.php");
include_once("../../classes/functions.php");

//var_dump($_POST);
//POST variables
$post_username = $_POST["username"];
$post_password = $_POST["password"];
$enc_post_password = sha1($post_password);
$post_lang = $_POST["lang"];
//echo $enc_post_password;
$post_session_id = $_POST["session_id"];
$post_captcha = $_POST["captcha_code"];

//SESSION variables
$session_id = $_SESSION["session_id"];
$session_captcha = $_SESSION["rand_char"];

//var_dump($_SESSION);

if ($_POST && $session_id = $post_session_id && $post_username!="" && $post_password!=""){
	//echo "1";
	if ($session_captcha == $post_captcha){
		//echo "2";
		//Successfull client validation
		//Now do the User and password - database validation
		$qauth = "SELECT COUNT(*) AS vcount FROM tbl_users WHERE vrc_username_email = '$post_username' AND chk_visible = '1' LIMIT 1";
		
		$qauth2 = "SELECT * FROM tbl_users WHERE vrc_username_email = '$post_username' AND chk_visible = '1' LIMIT 1";
		//echo $qauth;
		$resauth = mysql_query($qauth2);
		$rsauth = mysql_fetch_array(mysql_query($qauth));
		
		$rs = mysql_query($qauth);
		$rsauth1 = mysql_fetch_array($rs);
				
		//echo 3;
		//echo $qauth;
		//$numrows = mysql_num_rows($resauth);
		$numrows = $rsauth1["vcount"];
		//echo "Num Rows:". $numrows;
		//exit();
		
		if ($numrows==1){
		
		//echo 4;
		//exit();
		
			$fetch = mysql_fetch_array($resauth);
			$fetch_password = $fetch["vrc_password"];	
			/*
			echo "Fetch pass:".$fetch_password;
			exit();
			*/
			if ($fetch_password == $enc_post_password){
				/*
				echo "User is authenticated!!!";
				exit();
				*/
				$_SESSION["session_auth_user"] = $post_username;
				$_SESSION['LAST_ACTIVITY'] = time();
				$_SESSION["session_lang"] = $post_lang;
				$_SESSION["session_int_user"] = $fetch["id"];
				//$ip=$_SERVER['REMOTE_ADDR'];
				$ip = getRealIpAddr();
				$ins = fns_audit("login", $post_username, "all", "$post_username logged in from $ip", 0);
				//var_dump($_SESSION);
				
				
				//System settings array
				$qset = "SELECT * FROM tbl_settings WHERE chk_visible = '1'";
				$rsset = mysql_query($qset);
				$set_array = array();
				while($row = mysql_fetch_array($rsset)){
					$vrc_variable = $row["vrc_var_name"];
					$vrc_value = $row["vrc_var_value"];
					
					$set_array[$vrc_variable] = $vrc_value;
				}
				$vv_splash_page = $set_array["vv_splash_page"];
				if (isset($vv_splash_page)){
					header("Location: $site_admin_url/$vv_splash_page");
				}else{
					header("Location: $site_admin_url/data.php?module=crm3&mode=all");
				}
				
			}else{
				header("Location: $site_admin_url/login.php?msg=1");
			}
		}
	}else{
		header("Location: $site_admin_url/login.php?msg=2");
	}
}else{
	header("Location: $site_admin_url/login.php?msg=1");
}
include_once("../../classes/closeconnection.php");
exit();
?>