<?php
include_once("configuration.php");
include_once("openconnection.php");
//$q = "SELECT * FROM knowledgebase.tbl_users WHERE chk_visible = '1' ";
$q = "SELECT * FROM tbl_users WHERE chk_visible = '1' ";
//echo $q;
$rs = mysql_query($q);
$numrows = mysql_num_rows($rs);
echo $numrows;
$fetch = mysql_fetch_array($rs);
//$fetch = mysql_fetch_assoc($rs);
echo "Count:".$fetch[0];
?>