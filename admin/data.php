<?php
include_once("header.php");
include_once("header_add.php");
include_once("userrightscontrol.php");
if ($mode=="all"){
	include_once("pagination.php");
}else{
	include_once("main.php");
}
include_once("footer.php");
?>