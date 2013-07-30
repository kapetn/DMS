<?php
//Logout script
ob_start();
session_start();
include_once("../classes/allinclude.php");
include_once("../classes/functions.php");

$logged_user = $_SESSION["session_auth_user"];
$pid = 0;
$ins = fns_audit("logout", $logged_user, "all", "$logged_user logged out", $pid);


// Unset all of the session variables.
$_SESSION = array();
$_SESSION["usrname"] = "";
// Finally, destroy the session.
session_destroy();
header("Location:login.php");
?>