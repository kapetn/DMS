<?php
session_start();
include_once("../classes/allinclude.php");
$lang = $_GET["lang"];
$module = $_GET["module"];
$mode = $_GET["mode"];
$pid = $_GET["pid"];
if ($lang!=""){
	$_SESSION["session_lang"] = $lang; 
}

if ($mode!="edit"){
	header("Location: $site_admin_url/data.php?mode=$mode&module=$module");
}else{
	header("Location: $site_admin_url/data.php?mode=$mode&module=$module&pid=$pid");
}
exit();
?>