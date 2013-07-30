<script>
function toggleChecked(status) {
	$(".checkbox").each( function() {
		$(this).attr("checked",status);
	})
}
</script>
<div class="sidebarBox supportPlanTable">
<div id="tabs">
<ul>
	<li><a href="#tabs-0"><?=$voc_array["var_records"]?></a></li>
	<li><a href="#tabs-1"><?=$voc_array["var_search_form"]?></a></li>
</ul>
<div id="tabs-0">
<form name="frmbatchdelete" id="frmbatchdelete" action="datahandler.php" method="POST">
<input type="hidden" value="batchdelete" name="mode" />
<input type="hidden" value="<?=$module?>" name="module" />
<table id="supportPlans" width="100%">
<?php
$q = "SELECT * FROM tbl_tables_repository WHERE vrc_tablename='tbl_$module' AND chk_grid=1 ORDER BY ord_orderid";  
//echo $q;
$rs = mysql_query($q);
$numrowsf = mysql_num_rows($rs); 
echo "<thead><tr>";
//echo 1;

echo "<th width='20' align='left'>";
echo "<input title='Select all' type=\"checkbox\" onclick=\"toggleChecked(this.checked)\">"; 
echo "</th>";


$i=1;
while ($row1 = mysql_fetch_array($rs)){
	$label_gr = $row1["vrc_label_$lang"];	
	$fieldname = $row1["vrc_fieldname"];	
	$fields .= "$fieldname";
	if ($i<$numrowsf){
		$fields .= ",";
	}	
	
	$fields_array[] = $fieldname;	
	
	if (!isset($pg))$pg=1;
	
	echo "<th align='left' class=\"name\">$label_gr &nbsp;";
	echo "<a style='color:#fff; width:10px; font-size:18px; font-weight:bold;' href='data.php?module=$module&mode=all&orderby=$fieldname&order=asc&pg=$pg'>&uarr;</a>";
	echo "<a style='color:#fff; width:10px; font-size:18px; font-weight:bold;' href='data.php?module=$module&mode=all&orderby=$fieldname&order=desc&pg=$pg'>&darr;</a>";
	echo "</th>";
	if ($module=="orders" && $i==$numrowsf){
		echo "<th align='left' class=\"name\">".$voc_array["var_total"]."</th>";
	}
	$i++;
}

if ($module=="products"){
	echo "<th>Ποσότητα Αγορών</th>";	
	echo "<th>Ποσότητα Πωλήσεων</th>";	
}

echo "<th align='left'>".$voc_array["var_management"]."</th>";



echo "</tr></thead>";

//echo 2;

echo "<tbody>";

if ($squery=="1" && $_SESSION["session_query"] != ""){
	$q = $_SESSION["session_query"];
	$Limit = $sLimit;
}else{
	$get_orderby = $_GET["orderby"];
	$get_order = $_GET["order"];
	if ($get_orderby!="" && $get_order!=""){
		$ORDERBY = " ORDER BY $get_orderby $get_order";
	}else{
		if ($module=="crm" || $module=="crm3"){
			$ORDERBY = " ORDER BY dat_end_date, chk_status, int_crm_types, dat_start_date DESC ";
		}else{
			$ORDERBY = "";
		}
	}
	
	if ($module=="crm" || $module=="crm3"){
		if ($day=="today"){
			$today_date = date("Y-m-d");
			//$q = "SELECT $fields FROM tbl_$module WHERE chk_status = 0 AND chk_visible!='2' AND dat_start_date LIKE '%$today_date%' $ORDERBY";
			$q = "SELECT $fields FROM tbl_$module WHERE chk_visible!='2' AND dat_start_date LIKE '%$today_date%' $ORDERBY";
		}elseif($_GET["cntid"]!="" && $mode=="all"){
			$q = "SELECT $fields 
				  FROM tbl_$module 
				  WHERE chk_visible!='2' 
				  AND int_contacts3 = '$cntid' 
				  ORDER BY id DESC
				  ";
		}else{
			//$q = "SELECT $fields FROM tbl_$module WHERE chk_status = 0 AND chk_visible!='2' $ORDERBY";
			$q = "SELECT $fields FROM tbl_$module WHERE chk_visible!='2' $ORDERBY";
		}
		
	}elseif($module=="projects" && $cntid!=""){
		$q = "SELECT * FROM tbl_$module WHERE chk_visible!='2' AND int_contacts3 = $cntid $ORDERBY";	
	}else{
		$q = "SELECT $fields FROM tbl_$module WHERE chk_visible!='2' $ORDERBY";
	}	
}
//echo $q;
//echo 3;

//echo $q."<br>";
//var_dump($fields_array);
$rs2 = mysql_query($q);
/*$numrows = mysql_num_rows($rs2);
echo "<br>Records Num: $numrows<br>";*/

$fields_num = count($fields_array);
//echo $fields_num;
$ii = 1;

//Limit 50 for Last 50 Tasks for specific Contact ID
if ($module=="crm3" && $cntid!="")$Limit=50;

//$query = "SELECT * FROM tbl_unqcodes WHERE catid = $cid ORDER BY unqcode";
//Additional GET parameters for pagination
$params = "mode=all";
if ($orderby!="")$params = "mode=all&orderby=$orderby";
if ($torder!="")$params .= "&order=$torder";

$pagin = pagination($q, $pg, $params, $Limit, $_GET["module"]);
$SearchResult = pagination_header($pg, $q, $Limit);

//echo $q;
//echo 3;

while ($row2 = mysql_fetch_array($SearchResult)){
if ($ii%2){
	//$extra_attr = "class=\"alt-row\"";
	$extra_attr = "";
}else{
	$extra_attr = "";
}

$id = $row2["id"];

echo "<tr $extra_attr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\" data-id=\"1\" tabindex=\"0\" >";
echo "<td><input type=\"checkbox\" name='chkbox[]' value='$id'  class='checkbox'></td>";
	
	$chk_visible = $row2["chk_visible"];
	for ($i=0;$i<$fields_num;$i++){
		
		
		
		$fieldname = $fields_array[$i];
		$field = $row2[$fieldname];
		$field_value = $field;
		if (substr($fieldname, 0,3)=="chk"){
			if ($field=="1"){
				$field = "Yes";
			}else{
				$field = "No";
			}	
		}
		
		if (substr($fieldname, 0,3)=="int"){
			$table_suffix = substr($fieldname, 4);
			if ($table_suffix=="users_assigned"){
				$table_suffix = "users";
			}
			
			//$qint = "SELECT * FROM tbl_$table_suffix WHERE id = '$field'";
			$qint = "SELECT * FROM tbl_$table_suffix WHERE id IN ($field)";
			$pieces = explode(",", $field);
			$count_p = count($pieces);
			//echo $qint;
			//$rsint = mysql_fetch_array(mysql_query($qint));
			$rint = mysql_query($qint);
			$field = "";
			$ii=1;
			while ($rsint = mysql_fetch_array($rint)){
					$fieldid = $rsint["id"];
					$field .= "<a style='color:#8E3636;' href='data.php?module=$table_suffix&mode=edit&pid=$fieldid' target='_blank'>";
					if ($table_suffix=="customers"){
						$field .= $rsint["vrc_first_name"]." ".$rsint["vrc_last_name"];
					}elseif ($table_suffix=="orders"){
						$field .= $rsint["id"];
					}elseif ($table_suffix=="members"){
						$field .= $rsint["vrc_first_name"]." ".$rsint["vrc_last_name"];
					}elseif ($table_suffix=="contacts3"){
						$field .= $rsint["vrc_title_gr"]." ".$rsint["vrc_last_name_gr"];
					}elseif ($table_suffix=="users" || $table_suffix=="users_assigned"){
						//$field .= $rsint["vrc_username_email"]; 
						$field .= $rsint["vrc_first_name"]." ".$rsint["vrc_last_name"];
					}else{
						$field .= $rsint["vrc_title_gr"];
					}	
					
					/*Display commas in multiple values*/
					if ($ii < $count_p && ($count_p > 1 && $ii>1 )){
						$field .= "</a>,<br /> ";
					}else{
						$field .= "</a> <br />";
					}
			$ii++;		
			}
		}elseif(substr($fieldname, 0,3)=="ord"){
			$field .= "&nbsp;<a title='Move up' class='link_order' href='datahandler.php?mode=order&module=$module&order=up&f=$fieldname&val=$field_value&pid=$id'><img border='0' src='../images/uparrow.png' /></a>";
			$field .= "<a title='Move down' class='link_order' href='datahandler.php?mode=order&module=$module&order=down&f=$fieldname&val=$field_value&pid=$id'><img border='0' src='../images/downarrow.png' /></a> ";
		}elseif(substr($fieldname, 0,3)=="dat"){
			$fyear = substr($field, 0,4);
			$fmonth = substr($field, 5,2);
			$fday = substr($field, 8,2);
			$field = $fday."-".$fmonth."-".$fyear;
		}
		
		echo "<td><a style='color:#544E4E;' href=\"data.php?module=$module&mode=edit&pid=$id\">$field</a></td>";
	}//end for
	
	
	//Extra code for Products - Display the Quantity for Sales and Purchases
	if ($module=="products"){
		$qty_purchase = $obj_dbdatabase->getpurchaseqty($id);
		$qty_sales = $obj_dbdatabase->getsalesqty($id);
		echo "<td>$qty_purchase</td>";
		echo "<td>$qty_sales</td>";
		//echo $id."-".$qty_purchase."<br>";		
	}

	
	//Extra code for Orders - Find the Total Amount from Order Details Summary
	if ($module=="orders"){
		$total_amount = "";
		//1. Find Total the Amount from the Order Details Records
		$qdet =  "SELECT SUM(dec_price) AS total_amount FROM tbl_orders_details WHERE int_orders = '$id' AND chk_visible=1";
		$rsdet = mysql_fetch_array(mysql_query($qdet));
		$total_amount = $rsdet["total_amount"];
		
		//2. Multiply with the number of nights
		$qmaster = "SELECT * FROM tbl_orders WHERE id='$id'";
		$rsmaster = mysql_fetch_array(mysql_query($qmaster));
		//Find the number of nights. End Date - Start Date = Number of nights
		$dat_start_date = $rsmaster["dat_start_date"];
		$dat_end_date = $rsmaster["dat_end_date"];
		$qdiff = "SELECT DATEDIFF('$dat_end_date','$dat_start_date') AS num_nights";
		$rsdiff = mysql_fetch_array(mysql_query($qdiff));
		$num_nights = $rsdiff["num_nights"];
		$total_amount = $total_amount * $num_nights;
		
		//3. Now find the extra order features amount and add it to total amount
		$qfeat = "SELECT * FROM tbl_orders_feat_details WHERE int_orders = '$id' AND chk_visible=1";
		$rsfeat = mysql_query($qfeat);
		$feat_amount = "";
		while ($rowfeat = mysql_fetch_array($rsfeat)){
			$dec_amount = $rowfeat["dec_amount"];
			$dec_qty = $rowfeat["dec_qty"];
			$feat_amount = $feat_amount + ( ($dec_amount * $dec_qty) * $num_nights );
		}
		
		$total_amount = $total_amount + $feat_amount;
		
		
		echo "<td><a style='color:#544E4E;' href=\"data.php?module=$module&mode=edit&pid=$id\">$total_amount &euro;</a></td>"; 
	}

	echo "<td>";
	
	if ($chk_insert==1){	
		if ($module=="companies"){
			echo "<a title='New Contact' target='_blank' href='data.php?module=contacts3&mode=new&cmpid=$id'><img src='../images/user.png' /></a>";
		}
	}
	
	//Extra button with new task link for Contacts
	if ($module=="contacts3"){
		echo "<a title='Last 50 Tasks' target='_blank' href='data.php?module=crm3&mode=all&cntid=$id'><img width='16' border='0' src='../images/last-50.png' /></a>";
		echo "<a title='New Task' target='_blank' href='data.php?module=crm3&mode=new&cntid=$id'><img width='16' border='0' src='../images/new-task.png' /></a>";
	}
	
	
	
	
	if ($chk_update==1){
	
		/*Extra code for Table Record locking*/
		$qlock = "SELECT COUNT(*) AS countlock, int_users FROM tbl_record_lock WHERE vrc_table='tbl_$module' AND vrc_record_id='$id' AND chk_visible='1'";
		//echo $qlock."<br>";
		$rslock = mysql_fetch_array(mysql_query($qlock));
		$countlock = $rslock["countlock"];
		$lock_int_users = $rslock["int_users"];
		
		
		if ( ($countlock>0 && $_SESSION["session_int_user"]==$lock_int_users && $lock_int_users!="")
			||
			  $countlock==0
		){
			echo "<a title='Edit ".$row2[vrc_title_gr]."' href=\"data.php?module=$module&mode=edit&pid=$id\"><img src=\"../images/edit.png\"></a>";
			
			if ($chk_visible==1){
					echo "<a title='Not visible ".$row2[vrc_title_gr]."?' href=\"datahandler.php?module=$module&mode=notvisible&pid=$id\"><img src=\"../images/publish_g.png\"></a>"; 
			}elseif($chk_visible==0){
					echo "<a title='Visible ".$row2[vrc_title_gr]."?' href=\"datahandler.php?module=$module&mode=visible&pid=$id\"><img src=\"../images/publish_x.png\"></a>";
			}
			
		}else{
			$quser = "SELECT * FROM tbl_users WHERE id='$lock_int_users'";
			$rsuser = mysql_fetch_array(mysql_query($quser));
			$lock_user_fullname = $rsuser["vrc_first_name"]." ".$rsuser["vrc_last_name"];
		
			echo "<a href='#'><img title='This record is locked by $lock_user_fullname' src=\"../images/lock-icon-16.png\"></a>";
		}
				
	}	 

	if ($chk_insert==1){	
		echo"<a onclick=\"return confirm('Do you want to clone this record ".$row2[vrc_title_gr]."? ')\" href='datahandler.php?mode=clone&module=$module&pid=$id' title='Clone ".$row2[vrc_title_gr]."'><img width='16' height='16' src='../images/clone.png' /></a>";
	}
	
	if ( ($countlock>0 && $_SESSION["session_int_user"]==$lock_int_users && $lock_int_users!="")
			||
			  $countlock==0
		){
				if ($chk_delete==1){
					echo "<a title='Delete ".$row2[vrc_title_gr]."' onclick=\"return confirm('Do you want to delete this record ".$row2[vrc_title_gr]."? ')\" href=\"datahandler.php?mode=delete&module=$module&pid=$id\"><img src=\"../images/cross.png\"></a>"; 
				}
	}	
	echo "</td>";
echo "</tr>";	
$ii++;
}

if ($module=="orders"){
	//Find the total amount for Reservations (orders)
	//1. Find the Pending summary
	$qsump = "SELECT SUM(dec_amount) as sum_pending FROM tbl_orders WHERE chk_status = 0 AND chk_visible = 1";
	//echo $qsump;
	$rssump = mysql_fetch_array(mysql_query($qsump));
	$sum_pending = $rssump["sum_pending"];
	//2. Find the Confirmed summary
	$qsump = "SELECT SUM(dec_amount) as sum_confirmed FROM tbl_orders WHERE chk_status = 1 AND chk_visible = 1";
	$rssump = mysql_fetch_array(mysql_query($qsump));
	$sum_confirmed = $rssump["sum_confirmed"];
	echo "<tr><td align='right' style='font-weight:bold;' colspan='50'>Pending Total: $sum_pending &euro;</td></tr>";
	echo "<tr><td align='right' style='font-weight:bold;' colspan='50'>Confirmed Total: $sum_confirmed &euro;</td></tr>";
}

if ($chk_delete==1){
?>
<tr>
	<td colspan="30">
		<input class="ui-button ui-widget ui-state-default ui-corner-all" type="submit" value="Batch delete" name="btn_submit" role="button">
	</td>
</tr>
<?php
}
?>
</table>						
</form>
</div>
<div id="tabs-1">		
	<form name="frmsearch" id="frmsearch" action="datahandler.php" method="POST">
		<input type="hidden" name="mode" value="search" />
		<input type="hidden" name="module" value="<?=$module?>" />
		<table width="100%">
			<tr>
				<th align="center">
					<?=$voc_array["var_field_name"]?>
				</th>
				<th align="center">
					<?=$voc_array["var_field_value"]?>
				</th align="center">
				<th align="left">
					<?=$voc_array["var_operator"]?>
				</th>
			</tr>
			<!--<tr><td colspan="20"><hr /></td></tr>-->
<?php
	$qfields = "SELECT * 
				FROM tbl_tables_repository 
				WHERE vrc_tablename='tbl_$module' 
				AND vrc_fieldname!='id' 
				AND SUBSTRING(vrc_fieldname,1,3) NOT IN ('fil', 'ord') 
				AND chk_visible=1 
				ORDER BY ord_orderid";
	//echo $qfields;
	$rsfields = mysql_query($qfields);
	$ifields = 0;
	while ($rfield = mysql_fetch_array($rsfields)){
		$label = $rfield["vrc_label_$lang"];
		$fieldname = $rfield["vrc_fieldname"];
		echo "<tr><td width='150'><label>$label</label></td>";
		if (substr($fieldname, 0, 3)=="dat"){
			$dtpk = $ifields + 50;
			echo "<td  width='350'>
					<input type='text' name='$fieldname' class='datepicker $attrib_date' id=\"datepicker$ifields\" />
					<input type='text' name='$fieldname"."_to"."' class='datepicker $attrib_date' id=\"datepicker$dtpk\" />
			  	  </td>";
		}elseif(substr($fieldname, 0, 3)=="chk"){
			echo "<td  width='350' style='padding-left:5px;'>
					<select name='$fieldname'>
						<option selected value=''></option>
						<option value='1'>Yes</option>
						<option value='0'>No</option>
					</select>
				  </td>";
		}elseif(substr($fieldname, 0, 3)=="int"){
			if ($fieldname == "int_users_assigned"){
				$tablename = "tbl_users";
			}else{
				$tablename = "tbl_".substr($fieldname, 4);
			}	
			
			//echo $tablename;
			$qint = "SELECT * FROM $tablename WHERE chk_visible!=2 ORDER BY vrc_title_gr";
			//echo $qint."<br />";
			$int_options = "";
			$rsint = mysql_query($qint);
			while($rowint = mysql_fetch_array($rsint)){
				if ($tablename=="tbl_users"){
					//$int_title = $rowint["vrc_username_email"];
					$int_title = $rowint["vrc_first_name"]." ".$rowint["vrc_last_name"];
				}else{
					$int_title = $rowint["vrc_title_gr"];
				}	
				$int_id = $rowint["id"];				
				$int_options .= "<option value='$int_id'>$int_title</option>";
			}
			
			echo "<td  width='350' style='padding-left:5px;'>
					<select name='$fieldname'>
						<option selected value=''></option>
						$int_options
					</select>
				  </td>";
		}else{
			echo "<td  width='200'><input type='text' name='$fieldname' /></td>";
		}	
		echo "<td><select name='sel_$fieldname'>
				<option value='' selected></option>
			 ";
				
		if (substr($fieldname, 0, 3)=="dat"){
			echo "<option value='BETWEEN'>".$voc_array["var_between"]."</option>";
		}
		
		if (substr($fieldname, 0, 3)=="dec"){
			echo "<option value='GREATERTHANOREQUAL'>μεγαλύτερο ή ίσο με</option>";
			echo "<option value='LESSTHANOREQUAL'>μικρότερο ή ίσο με</option>";
		}
		
		
		echo "<option value='='>".$voc_array["var_equal_to"]."</option>
				<option value='!='>".$voc_array["var_not_equal_to"]."</option>
				<option value='%LIKE%'>".$voc_array["var_contains"]."</option>
				<!--<option value='ISNOTNULL'>IS NOT NULL (EMPTY)</option>-->
			   </select></td>";
		echo "</tr>";
		$ifields++;
	}

?>
		</table>
	<br />
	<input class="ui-button ui-widget ui-state-default ui-corner-all" type="submit" value="<?=$voc_array["var_search"]?>" name="btn_submit" role="button">
	<input class="ui-button ui-widget ui-state-default ui-corner-all" type="reset" value="<?=$voc_array["var_reset"]?>" name="btn_reset" role="button">
	</form>
</div>	
	
</div>		
<?php

function pagination($query, $pg, $param="mode=all", $Limit=1, $module=""){
		//echo $Limit;
		//$Limit = 10; //Number of results per page
		$pg=$_GET["pg"]; //Get the page number to show
		//echo $pg;
		If($pg == "") $pg=1; //If no page number is set, the default page is 1
		//Get the number of results
		//echo $query;
		$SearchResult=@mysql_query($query) or die(mysql_error());
		$NumberOfResults=@mysql_num_rows($SearchResult);
		//Get the number of pages
		$NumberOfPages=ceil($NumberOfResults/$Limit);
		$query .= " LIMIT " . ($pg-1)*$Limit . ",$Limit";
		$SearchResult=@mysql_query($query) or die(mysql_error());
		//echo $query;
		
		$previous = "Previous";
		$next = "Next";
		
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//START OF TOP PAGINATION
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
		$Nav = "<div>";
		$stylesheet = 'font-family:Trebuchet, Arial, Verdana; font-size:12px; color:red; background-color:#ffffff;
						text-decoration:none;';
		$style_next = 'font-family:Trebuchet, Arial, Verdana; font-size:12px; color:#cccccc; background-color:#ffffff;
						text-decoration:none;';
		
		if ( $NumberOfPages == '1'  )
		{
		  echo "";
		}
		else
		{
		 $Nav.="";
		 If($_GET[pg] > 1)
		 {
		 		$pg = $pg - 1 ;
		 		if ($pg!=0){
		 			$Nav .= "<a style='$style_next' href=\"$PHP_SELF?".$param."&module=".$module."&pg=" . ($pg) . "\"><< $previous </a>";	
		 		}
		 		
		 }
		
		 For($i = 1 ; $i <= $NumberOfPages ; $i++)
		 {
			   If($i == $_GET[pg])
			   {
			   		if ($pg==1){
			   			$Nav .= "<B><span style='font-size:15px;color:#ffffff; background-color:#FF008C;'>$i</span></B>";
			   		}else{
			   			$Nav .= "<B><span style='font-size:15px;color:#ffffff; background-color:#FF008C;'>$i</span></B>";
			   		}	
			   }
			   Else
			   {
			   			//$num = $i;
			   			$Nav .= "<a style='$stylesheet' href=\"$PHP_SELF?".$param."&module=".$module."&pg=" . ($i) . "\"> $i </a>";	
			   		
			   }
		 }
		
		 If($_GET["pg"] < $NumberOfPages)
		 {
		  //$_GET["pg"] = $_GET["pg"] + 1;
		  		if ($_GET["pg"] < $NumberOfPages){
		 			$pg = $_GET["pg"];
					//echo $pg;
					$pg = $pg + 1;
		 			$Nav .= "<a style='$style_next' href=\"$PHP_SELF?".$param."&module=".$module."&pg=" . ($pg) . "\"> $next>> </a>";	
		  		}else{
		  			$Nav.="";		  			
		  		}	
		 }
		}
		$Nav.= "</div>";	
		$Nav.="<div style='$stylesheet'><b>Records:</b> $NumberOfResults</div>";
		echo $Nav;
}



function pagination_header($pg, $query, $Limit){
	
	$pg=$_GET["pg"]; //Get the page number to show
	If($pg == "") $pg=1; //If no page number is set, the default page is 1
	//Get the number of results
	$SearchResult=@mysql_query($query) or die(mysql_error());
	$NumberOfResults=@mysql_num_rows($SearchResult);
	//Get the number of pages
	$NumberOfPages=ceil($NumberOfResults/$Limit);
	$query .= " LIMIT " . ($pg-1)*$Limit . ",$Limit";
	$SearchResult=@mysql_query($query) or die(mysql_error());
	
	return $SearchResult;
}

?>					