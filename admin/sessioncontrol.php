<?php
//User authentication Session control
if ($_SESSION["session_auth_user"]==""){
	header("Location:logout.php");
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
    // last request was more than 30 minutes ago
    session_destroy();   // destroy session data in storage
    session_unset();     // unset $_SESSION variable for the runtime
	header("Location:logout.php");
	exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
$session_int_users = $_SESSION["session_int_user"];
?>