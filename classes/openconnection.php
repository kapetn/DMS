<?php
include_once("configuration.php");
/*
echo $dbhost."<br>";
echo $dbuser;
echo $dbpassword;
*/
$link = mysql_connect($dbhost, $dbuser, $dbpassword);
if (!$link) {
    die('Could not connect: ' . mysql_error());
}else{
	//echo 'Connected successfully';
	//mysql_select_db($link, $dbschema);
	$db_selected = mysql_select_db($dbschema, $link);
	mysql_query("SET NAMES UTF8");	
	
	
	if (!$db_selected) {
		die ('Can\'t use foo : ' . mysql_error());
	}
	
}
//mysql_close($link);
?>