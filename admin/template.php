<?php 
session_start(); 
$session_id = session_id();

include_once("../classes/allinclude.php");
//GET variables

//Variables
$testmode = $_GET["testmode"];
if ($testmode==1){
	echo "GET superblobal array <br>";
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
	
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>HOTEL RMS</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js"></script>
<script>
$(document).ready(function(){

	$("ul.subnav").parent().append("<span></span>"); //Only shows drop down trigger when js is enabled (Adds empty span tag after ul.subnav*)

	$("ul.topnav li span").click(function() { //When trigger is clicked...

		//Following events are applied to the subnav itself (moving subnav up and down)
		$(this).parent().find("ul.subnav").slideDown('fast').show(); //Drop down the subnav on click

		$(this).parent().hover(function() {
		}, function(){
			$(this).parent().find("ul.subnav").slideUp('slow'); //When the mouse hovers out of the subnav, move it back up
		});

		//Following events are applied to the trigger (Hover events for the trigger)
		}).hover(function() {
			$(this).addClass("subhover"); //On hover over, add class "subhover"
		}, function(){	//On Hover Out
			$(this).removeClass("subhover"); //On hover out, remove class "subhover"
	});

});

</script>
<link rel="stylesheet" type="text/css" href="../css/admin.css" />

</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#D9D9D9;" >
<table cellspacing="0" cellpadding="0" width="99%" height="100%">
<tr>
	<td class="cms_header_row" valign="top" align="left" width="100%">
		<h1 style="color:#b9b9b9; font-size:38px;">HOTEL RMS</h1>
	</td>
</tr>
<tr>
	<td class="cms_menu_row" valign="middle" align="left" width="99%" height="25">
			<div id="cmsmenu">
			<ul class="topnav">
			    <li><a href="main.php">Home</a></li>
			    <li>
			        <a href="#">Hotels</a>
			        <ul class="subnav">
			            <li><a href="#">Create Hotel</a></li>
			            <li><a href="#">View All</a></li>
			        </ul>
			    </li>
			    <li>
			        <a href="#">Rooms</a>
			        <ul class="subnav">
			            <li><a href="#">Create Room</a></li>
			        </ul>
			    </li>
				<li>
			        <a href="#">CMS User Types</a>
			        <ul class="subnav">
			            <li><a href="#">Create Type</a></li>
			        </ul>
			    </li>
				<li>
			        <a href="#">CMS Users</a>
			        <ul class="subnav">
			            <li><a href="#">Create User</a></li>
			        </ul>
			    </li>
			     <li>
			        <a href="#">Settings</a>
			        <ul class="subnav">
			            <li><a href="settings.php?mode=all">View All</a></li>
			        </ul>
			    </li>
			    <li><a href="logout.php">Log out</a></li>
			</ul>
		</div>
	</td>
</tr>
</table>	
</body>
</html>