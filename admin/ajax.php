<?php
session_start();
include_once("../classes/allinclude.php");

//Get Post Variables. The name is the same as 
//what was in the object that was sent in the jQuery
/*if (isset($_POST['sendValue'])){
    $value = $_POST['sendValue'];   
	$module = $_POST["module"];
}else{
    $value = "";
}*/
if (isset($_GET["sendValue"]) && strlen($_GET["sendValue"])>=2){
	if (isset($_GET["module"])){
 	$value = $_GET["sendValue"];
	$module = $_GET["module"];
	}
}	

if ($module=="customers"){
	$q = "SELECT id, CONCAT(vrc_first_name, ' ', vrc_last_name) as vrc_title_gr
		  FROM tbl_$module 
		  WHERE vrc_first_name LIKE '%$value%' 
		  OR vrc_last_name LIKE '%$value%'
		  ";
}else{
	$q = "SELECT * 
		  FROM tbl_$module 
		  WHERE vrc_title_gr LIKE '%$value%'";
	
}	  
$rs = mysql_query($q);
while ($row = mysql_fetch_array($rs)){
	$id = $row["id"];
	$title = $row["vrc_title_gr"];
	$results .= "<a style='text-decoration:none; font-weight:bold; color:#544E4E!important; width:300px;' href='data.php?module=$module&mode=edit&pid=$id'>$title</a><br>";
}

//Because we want to use json, we have to place things in an array and encode it for json.
//This will give us a nice javascript object on the front side.
echo json_encode(array("returnValue"=>"<div>".$results."</div>"));  
//echo $results;
?>