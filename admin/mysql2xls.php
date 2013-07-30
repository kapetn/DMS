<?php
session_start(); 
$session_id = session_id();
include_once("sessioncontrol.php");
include_once("../classes/allinclude.php");

//GET variables
$module = $_GET["module"]; 

$q = "SELECT * FROM tbl_tables_repository WHERE vrc_tablename='tbl_$module' AND chk_grid=1 ORDER BY ord_orderid";  
$rs = mysql_query($q);
$numrowsf = mysql_num_rows($rs);
$i=1;
while ($row1 = mysql_fetch_array($rs)){
	$label_gr = $row1["vrc_label_$lang"];	
	$fieldname = $row1["vrc_fieldname"];	
	$fields .= "$fieldname";
	if ($i<$numrowsf){
		$fields .= ",";
	}	
	$fields_array[] = $fieldname;
	
	$q = "SELECT $fields FROM tbl_$module WHERE chk_visible!='2' ORDER BY id";	
	
	$i++;
}


$select = $q; 
mysql_query("SET NAMES 'utf8_unicode_ci'"); 
mysql_query("SET CHARACTER SET 'utf8_unicode_ci'"); 
$export = mysql_query($select); 
$fields = mysql_num_fields($export); 

for ($i = 0; $i < $fields; $i++) { 
  $csv_output .= mysql_field_name($export, $i) . "\t"; 
} 

while($row = mysql_fetch_row($export)) { 
    $line = ''; 
    foreach($row as $value) { 
        if ((!isset($value)) OR ($value == "")) { 
            $value = "\t"; 
        } else { 
            $value = str_replace('"', '""', $value); 
            $value = '"' . $value . '"' . "\t"; 
        } 
        $line .= $value; 
    } 
    $data .= trim($line)."\n"; 
} 
$data = str_replace("\r","",$data); 

header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=$module.xls"); 
header("Content-Type: application/vnd.ms-excel;  charset=utf-8;");
header("Pragma: no-cache"); 
header("Expires: 0"); 
print $csv_output."\n".$data; 
exit; 

?>