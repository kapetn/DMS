<?php
include_once("../classes/allinclude.php");
//echo $_SESSION["session_auth_user"];
//Find module id
$qmod = "SELECT * FROM tbl_modules WHERE vrc_module = '".$_GET["module"]."'";
$rsmod = mysql_fetch_array(mysql_query($qmod));
$mod_id = $rsmod["id"];

$qusercontrol = "SELECT * FROM tbl_users_rights WHERE int_users = '$userid' AND int_modules = '$mod_id' AND chk_visible=1 LIMIT 1";
//echo $qusercontrol;
$rsusercontrol = mysql_fetch_array(mysql_query($qusercontrol));
$chk_select = $rsusercontrol["chk_select"];
$chk_insert = $rsusercontrol["chk_insert"];
$chk_update = $rsusercontrol["chk_update"];
$chk_delete = $rsusercontrol["chk_delete"];

if ($chk_select != "1" && $mode == "all"){
	$msg_txt = "<span style='color:red; font-size:14px; font-weight:bold;'>".$voc_array["var_user_right_view"]."</span>";
?>
	<div><?=$msg_txt?></div>
<?php	
	exit();
}

if ($chk_insert != "1" && $mode == "new"){
	$msg_txt = "<span style='color:red; font-size:14px; font-weight:bold;'>".$voc_array["var_user_right_insert"]."</span>";
?>
	<div><?=$msg_txt?></div>
<?php	
	exit();
}

if ($chk_update != "1" && $mode == "edit"){
	$msg_txt = "<span style='color:red; font-size:14px; font-weight:bold;'>".$voc_array["var_user_right_update"]."</span>";
?>
	<div><?=$msg_txt?></div>
<?php	
	exit();
}

?>