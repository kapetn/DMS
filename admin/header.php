<?php 
session_start(); 
$session_id = session_id();

include_once("sessioncontrol.php");

include_once("../classes/allinclude.php");
//GET variables
$module = $_GET["module"]; 
$day = $_GET["day"];
$orderby = $_GET["orderby"];
$torder = $_GET["order"];

//Load the vocabulary
$lang = $_SESSION["session_lang"];

$qvoc = "SELECT * FROM tbl_vocabulary WHERE chk_visible = 1";
$rsvoc = mysql_query($qvoc);
$voc_array = array();
while($rowv = mysql_fetch_array($rsvoc)){
	$vrc_variable = $rowv["vrc_variable"];
	$vrc_title = $rowv["vrc_title_$lang"];
	
	$voc_array[$vrc_variable] = $vrc_title;
}

/*
//System settings array
*/
$qset = "SELECT * FROM tbl_settings WHERE chk_visible = '1'";
$rsset = mysql_query($qset);
$set_array = array();
while($row = mysql_fetch_array($rsset)){
	$vrc_variable = $row["vrc_var_name"];
	$vrc_value = $row["vrc_var_value"];
	
	$set_array[$vrc_variable] = $vrc_value;
}

$Limit = $set_array["vv_Limit"];
$sLimit = $set_array["vv_sLimit"];
$admin_email = $set_array["vv_admin_email"];
$site_name = $set_array["vv_site_name"];
$jquery_theme = $set_array["vv_jquery_theme"];
$system_marquee = $set_array["vv_system_marquee"];
/*
end of system settings
---------------------------------
*/



//var_dump($voc_array);
$mod_array = array();
$qmodarr = "SELECT * FROM tbl_modules WHERE chk_visible=1 ORDER BY ord_orderid";
$rsmodarr = mysql_query($qmodarr);
while ($rowmr = mysql_fetch_array($rsmodarr)){
	if ($lang=="gr"){
		$vrc_title = $rowmr["vrc_title_gr"];
	}else{
	    $vrc_title = $rowmr["vrc_title_en"];
	}

	$vrc_module = $rowmr["vrc_module"];
	$mod_array[$vrc_module] = $vrc_title;
}

$mode = $_GET["mode"];
$pg = $_GET["pg"];
$squery = $_GET["sq"];//Session query variable. Use it only for search
$cntid = $_GET["cntid"];//Contact ID for Last 50 Tasks

//Variables
$testmode = $_GET["testmode"];
if ($testmode==1){

	echo "<div style='color:#000;'>GET superblobal array <br>";
	var_dump($_GET);
	echo "<br><br>";
	echo "POST superblobal array <br>";
	var_dump($_POST);
	echo "<br><br>";
	echo "SESSION superblobal array <br>";
	var_dump($_SESSION);
	echo "<br><br>";
	echo "SERVER superblobal array <br>";
	var_dump($_SERVER);
	echo "<br /><br /></div>";
	
}

	//Find the CMS User's id
	$quser = "SELECT * FROM tbl_users WHERE vrc_username_email = '".$_SESSION["session_auth_user"]."'";
	$rsuser = mysql_fetch_array(mysql_query($quser));
	$userid = $rsuser["id"];
	$user_fullname = $rsuser["vrc_first_name"]." ".$rsuser["vrc_last_name"];


//Instantiate Objects
$obj_fckeditor = new fckeditor();
$obj_dbdatabase = new dbdatabase();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?=$site_name?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/ui-darkness/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/redmond/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/blitzer/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/cupertino/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/humanity/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/pepper-grinder/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/flick/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/<?=$jquery_theme?>/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="../css/admin2.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<!--<script type="text/javascript" src="http://jzaefferer.github.com/jquery-validation/jquery.validate.js"></script>-->
<script type="text/javascript" src="../jquery/jquery.validate.js"></script>
<script language="javascript" src="../FCKeditor/fckeditor.js"></script>  
  <script>
  $(document).ready(function() {
    $("button").button();
  });
  </script>

<script>
  $(document).ready(function(){
    $("#datafrm").validate();
  });
</script>

<script>
	$(document).ready(function() {
		//$( "#tabs" ).tabs({ selected: 0 });
		$( "#tabs" ).tabs();
	});
</script>
<?php
$qmodacc = "SELECT a.*, 
				 b.chk_select, b.chk_insert, b.chk_update, b.chk_delete
				 FROM tbl_modules AS a, 
				 tbl_users_rights AS b 
				 WHERE a.chk_visible = 1
				 AND b.int_users = '$userid'
				 AND a.id = b.int_modules
				 AND b.chk_visible = 1
				 ORDER BY a.ord_orderid";
$rsmodacc = mysql_query($qmodacc);
$i = 0;
while ($rowc = mysql_fetch_array($rsmodacc)){
	$mod_id = $rowc["id"];
	$vrc_module = $rowc["vrc_module"];
	if ($vrc_module == $module){
		$modid = $i;
	}
$i++;	
}
//echo "Modid = $modid";
?>
<script>
  $(document).ready(function() {
    $("#accordion").accordion({active:<?=$modid?>});
  });
</script>

<script>
	$(function() {
		$( ".datepicker" ).datepicker({
			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: 'dd-mm-yy',
			yearRange: '1930:2030',
			changeMonth: true,
			changeYear: true
			//,minDate: 0
			//,beforeShowDay: $.datepicker.noWeekends
		});
	});
</script>	

<script>
	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
		$( "#dialog-modal" ).dialog({
			height: 140,
			modal: true
		});
	});
</script>

<script>

</script>
	
</head>	
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#D9D9D9;" >
<table cellspacing="0" cellpadding="0" width="100%" height="100%">
<tr>
	<td class="left_col" valign="top" align="left" height="100%">
		<!--<h1 style="color:#b9b9b9; font-size:38px;"><?=$site_name?> CMS</h1>-->
		<img src="<?=$dir_images?>/logo.jpg" border="0" /> 
		<br />
		<!--<?=$voc_array["var_signed_in"]?> <span style="color:#ffffff!important;"><strong><?=$_SESSION["session_auth_user"]?></strong></span>-->
		<?=$voc_array["var_signed_in"]?> <span style="color:#ffffff!important;"><strong><?=$user_fullname?></strong></span>
		<br />
<?php echo date("d-m-Y H:i:s"); ?>		
		<br />
		<a href="logout.php"><?=$voc_array["var_sign_out"]?></a>
		
		<br /><br />
	<div id="accordion">
<?php	
	$qmodules = "SELECT a.*, 
				 b.chk_select, b.chk_insert, b.chk_update, b.chk_delete
				 FROM tbl_modules AS a, 
				 tbl_users_rights AS b 
				 WHERE a.chk_visible = 1
				 AND b.int_users = '$userid'
				 AND a.id = b.int_modules
				 AND b.chk_visible = 1
				 ORDER BY a.ord_orderid
				 ";
	//echo $qmodules;
	$rsmod = mysql_query($qmodules);
	while ($rowm = mysql_fetch_array($rsmod)){
		$mod_id = $rowm["a.id"];
		$vrc_module = $rowm["vrc_module"];
		$vrc_title_gr = $rowm["vrc_title_$lang"];
		echo "<h1><a href=\"#\">$vrc_title_gr</a></h1><div>";
		echo "<a href=\"data.php?module=$vrc_module&mode=all#tabs-1\">".$voc_array["var_search_form"]."</a><br />";
		echo "<a href=\"data.php?module=$vrc_module&mode=new\">".$voc_array["var_add_new"]."</a><br />";
		echo "<a href=\"data.php?module=$vrc_module&mode=all\">".$voc_array["var_view_all"]."</a><br />";
		echo "</div>";
	}
?>	
</div>		
	</td>