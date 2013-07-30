<?php
error_reporting(0);
/*$dbhandle = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Unable to connect!");
$dbselected = mysql_select_db($dbschema, $dbhandle) or die("Could not select $dbschema");
 */

//get databases
$qdb = "SHOW DATABASES";
$resdb = mysql_query($qdb);


$idb = 0;
while ($rowdb = mysql_fetch_row($resdb)) {
	$dbs[] = $rowdb[$idb];
}


foreach ($dbs as $dbvals){
	//$optionsdb.="<option value='$dbvals'>$dbvals</option>";
}

//return all available tables
$query =  "SHOW TABLES FROM $dbschema";
//echo $query;



//get tables
$res = mysql_query($query);
$i=0;
while ($row = mysql_fetch_row($res)) {
	$rows[] = $row[$i];
}
$options="";
//var_dump($rows);
//insert db tables in option tags
foreach ($rows as $rowval){
	//echo $rowval."<br>";
	$options.="<option value='$rowval'>$rowval</option>";
}

//Insert Metadata into dbtablemetadata
if ($_POST){
	//$metadatabase = $_POST["database"];
	$metadatabase = $dbschema;
	$metadatatable = "tbl_tables_repository";
	$table_name = $_POST["dbtable"];
	//find db table's fiels-columns and insert as a record in the dbtablemetadata
	$result = mysql_query("SELECT * FROM $table_name");
	$num_fields = mysql_num_fields($result);
	//echo "Number of fields for table $table_name are: ".$num_fields;
	
	if ($_POST["delete_all"]=="1"){
		//first delete all records related to the POST db table
		$delquery = "DELETE FROM $metadatabase.$metadatatable WHERE tablename='$table_name'";
		echo $delquery;
		mysql_query($delquery);
	}
	
	//exit();
	
		
	$i = 0;
	while ($i < $num_fields) {
	    $ii = $i+1;
	    
	    //WE CAN INSERT EVERY AVAILABLE DB TABLE COLUMN ATTRIBUTE HERE
	    $meta = mysql_fetch_field($result, $i);
	    $val_tablename = $table_name;
	    $val_field = $meta->name;
	    $maxlength = $meta->max_length;
	    $primary_key = $meta->primary_key;
	    $data_type = $meta->type;
	    $numeric = $meta->numeric;
	    
	    
		//First Check if the field already exists in the db table repository
		$qexists = "SELECT * FROM $metadatabase.$metadatatable WHERE fieldname = '$val_field' AND tablename='$val_tablename'";
		$rsexists = mysql_query($qexists);
		$numrowexist = mysql_num_rows($rsexists);
	    
		if ($numrowexist==0){
				$insquery = "INSERT INTO $metadatabase.$metadatatable 
									(vrc_tablename, 
									 vrc_fieldname, 
									 vrc_tabname, 
									 vrc_label_gr, 
									 vrc_label_en,
									 ord_orderid,
									 chk_visible,
									 chk_grid
									 ) 
									 VALUES 
									 ('$val_tablename',
									  '$val_field',
									  'Information',
									  '$val_field',
									  '$val_field',
									  '$ii',
									  '1',
									  '0'
									 );
									";
				echo $insquery."<br>";
				$ins = mysql_query($insquery);
				if (!$ins){
					mysql_error();
					
				}else{
					//echo "Data inserted in metadata table<br>";
				}
		}
		
		//exit();
		
		/*echo "Information for column $i:<br />\n";
	    $meta = mysql_fetch_field($result, $i);
	    if (!$meta) {
	        echo "No information available<br />\n";
	    }
		echo "<pre>
			blob:         $meta->blob
			max_length:   $meta->max_length
			multiple_key: $meta->multiple_key
			name:         $meta->name
			not_null:     $meta->not_null
			numeric:      $meta->numeric
			primary_key:  $meta->primary_key
			table:        $meta->table
			type:         $meta->type
			default:      $meta->def
			unique_key:   $meta->unique_key
			unsigned:     $meta->unsigned
			zerofill:     $meta->zerofill
			</pre>";*/
	    
		$i++;
	}
	mysql_free_result($result);

	
}

//echo $options; 
//var_dump($tables);
?>
<td class="right_col" width="100%" valign="top" align="left" valign="top">
	<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
	<table width="100%">
		<tr>
			<td width="30%">Choose Database:</td>
			<td>
				<select name="database">
					<option value="getphpsc_site">getphpsc_site</option>
				</select>
			</td>
		</tr>
	
		<tr>
			<td width="30%">Delete All Records for this table:</td>
			<td>
				<select name="delete_all">
					<option value="1">Yes</option>
					<option value="2">No</option>
				</select>
			</td>
		</tr>
	
		<tr>
			<td width="30%">Choose Database Table to insert metadata:</td>
			<td>
				<select name="dbtable">
					<option value="">----</option>
					<?php echo $options; ?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
			<input class="ui-button ui-widget ui-state-default ui-corner-all" role="button" type="submit" name="btn_submit" value="Save" />
			</td>
		</tr>
		
	</table>
	</form>
</td>	
</tr>