<?php
session_start();
include_once("../classes/allinclude.php");
include_once("../classes/functions.php");

$module = $_GET["dbmodule"];
$column = $_GET["dbcolumn"];
$value = $_GET["value"];

$query = "SELECT $column FROM tbl_$module WHERE chk_visible!='2' AND $column = '$value'";
//echo $query;
$rs = mysql_query($query);
$numcount = mysql_num_rows($rs);
if ($numcount>0){
	echo "We would like to inform you that <u><i>$value</i></u> already exists!";
}
?>