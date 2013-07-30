<?php
$module = $_GET["module"];
$mode = $_GET["mode"];
$pid = $_GET["pid"];
$cmpid = $_GET["cmpid"];//Company ID
$cntid = $_GET["cntid"];//Contact ID
?> 	
	<form id="datafrm" action="datahandler.php" method="POST" enctype="multipart/form-data">		
	<input type="hidden" name="module" value="<?=$module?>" />
	<input type="hidden" name="mode" value="<?=$mode?>" />
<?php
	if ($mode=="edit"){
		$id = $_GET["id"];
		
		
		/*Record locking code*/
		//First check if the current record is already locked by the specific user
		$qlock = "SELECT COUNT(*) AS countlock, int_users FROM tbl_record_lock WHERE vrc_table='tbl_$module' AND vrc_record_id='$pid' AND chk_visible='1'";
		//echo $qlock."<br>";
		$rslock = mysql_fetch_array(mysql_query($qlock));
		$countlock = $rslock["countlock"];
		$lock_int_users = $rslock["int_users"];
		
		if ($countlock==0){
			$qinslock = "INSERT INTO tbl_record_lock 
						 (vrc_table,
						  vrc_record_id,
						  dat_creation,
						  chk_visible,
						  int_users
						 ) 
						 VALUES 
						 ('tbl_$module',
						  '$pid',
						  now(),
						  '1',
						  '$session_int_users'
						 )";
			//echo $qinslock;		
			$rslock = mysql_query($qinslock);	
		}elseif($countlock>0 && $_SESSION["session_int_user"]!=$lock_int_users){
			$quser = "SELECT * FROM tbl_users WHERE id='$lock_int_users'";
			$rsuser = mysql_fetch_array(mysql_query($quser));
			$lock_user_fullname = $rsuser["vrc_first_name"]." ".$rsuser["vrc_last_name"];
		
			echo "<span style='color:red; font-size:14px; font-weight:bold;'>Αυτή η εγγραφή είναι κλειδωμένη από τον χρήστη $lock_user_fullname.</span>";
			exit();
		}
		/*End of record locking code*/
?>
	<input type="hidden" name="pid" value="<?=$pid?>" />
<?php		
	}
?>
		<div id="tabs">
			<ul>
<?php
$qtabs = "SELECT distinct vrc_tabname FROM `tbl_tables_repository` WHERE vrc_tablename = 'tbl_$module' AND chk_visible='1'";
$rstabs = mysql_query($qtabs);
$itab = 0;
//$rowtt = mysql_fetch_array($rstabs);
while ($rowt = mysql_fetch_array($rstabs)){
	$tabname = $rowt["vrc_tabname"];
	$tabarray[] = $tabname;
	echo "<li><a href=\"#tabs-$itab\">$tabname</a></li>";
	$itab++;
}//end while for Tabs	

	//Extra tabs for Orders (Order details for Rooms, Orders extra features)
	if ($module=="orders"){
		echo "<li><a href=\"#tabs-50\">".$voc_array["var_room_reservations"]."</a></li>";
		echo "<li><a href=\"#tabs-51\">".$voc_array["var_room_features"]."</a></li>";
	}
	
	if ( ($module == "purchases" || $module == "sales" ) && $mode == "edit"){
		echo "<li><a href=\"#tabs-50\">Είδη Παραστατικού</a></li>";
		echo "<li><a href=\"#tabs-51\">Γραμμές Παραστατικού</a></li>";
	}
	
	//Extra tabs for CMS Users Rights
	/*if ($module=="users"){
		echo "<li><a href=\"#tabs-50\">".$voc_array["var_users_rights"]."</a></li>";
	}*/
?>	
			
			</ul>
<?php
//var_dump($rowtt);
	for ($i=0;$i<=$itab;$i++){
		$tabname = $tabarray[$i];
		/*$tabname = $rowt[$i];
		echo $tabname;
		*/
		//var_dump($rowt);
?>			
			<div id="tabs-<?=$i?>">				
					<table cellpadding="2" cellspacing="2" width="100%">
<?php
	if ($module!=""){
		$id = $_GET["pid"];
		//echo $id;
		//echo 1;
		$qtablerep = "SELECT * FROM tbl_tables_repository 
					  WHERE vrc_tablename = 'tbl_$module' 
					  AND chk_visible=1 
					  AND vrc_tabname='$tabname' 
					  ORDER BY ord_orderid";
		$rstablerep = mysql_query($qtablerep);
		$numrows = mysql_num_rows($rstablerep);
		if ($numrows>0){
			$ifields = 0;
			while($row = mysql_fetch_array($rstablerep)){
				$label = $row["vrc_label_$lang"];
				$fieldname = $row["vrc_fieldname"];
				
				/*Column attributes (e.g. Define if form field is read only, has required validation, email validation etc)*/
				$chk_readonly = $row["chk_readonly"];
				$chk_required = $row["chk_required"];
				$chk_numeric = $row["chk_numeric"];
				$chk_email = $row["chk_email"];
				$chk_date = $row["chk_date"];
				$chk_thumbnail = $row["chk_thumbnail"];
				$chk_url = $row["chk_url"];
				$chk_calculated = $row["chk_calculated"];
				$vrc_formula = $row["vrc_formula"];
				$chk_ajax_duplicate = $row["chk_ajax_duplicate"];
				$chk_only_text = $row["chk_only_text"];
				$chk_drop_down = $row["chk_drop_down"];
				
				$qdata = "SELECT `$fieldname` FROM `tbl_$module` WHERE id = '$id'";
				//echo $qdata."<br>";
				
				$rsdata = mysql_fetch_array(mysql_query($qdata));
				$field_value = stripslashes($rsdata[$fieldname]);		
				
				//First check the prefix in fieldname (e.g. int_ stands for a select option field, vrc_ stands for a text field from varchar etc)
				$column_prefix = substr($fieldname, 0, 3);
				//echo $column_prefix."<br>";
				
				$attributes = "";
				$attrib_class = " class=' ";
				
				if ($chk_readonly == 1){
					$attributes .= " style='background-color:#efefef;' readonly='readonly' ";
				}
				
				
				if ($chk_required == 1){
					$attrib_class .= " required ";
					$label .= " * ";
				}
				
				if ($chk_email == 1){
					$attrib_class .= " email ";
				}
				
				if ($chk_url == 1){
					$attrib_class .= " url ";
				}
				
				if ($chk_date == 1){
					$attrib_class .= " date ";
				}
				
				if ($chk_numeric == 1){
					$attrib_class .= " number ";
				}
				
				
				
				$attrib_class .= " ' ";
				
				if ($fieldname!="id"){
					if ($column_prefix=="chk"){
						if ($field_value==1){
							$attributes .= " checked='checked' ";
						}
					echo "<tr>
							<td width=\"160\">
								<label>$label:</label>
							</td>
							<td style='padding-left:5px;'>
								<select name='$fieldname'>
						  ";		
					if ($field_value==1){
						echo "<option selected value='1'>Yes</option>";
						echo "<option value='0'>No</option>'>";
					}else{
					
						if ($mode=="new"){
							echo "<option selected value='1'>Yes</option>";
							echo "<option value='0'>No</option>";
						}else{
							echo "<option  value='1'>Yes</option>";
							echo "<option selected  value='0'>No</option>";
						}
					}		
						
					echo "								
								</select>
							</td>
						</tr>";	
					}
					
					
					if ($column_prefix=="int"){
						$table_suffix = substr($fieldname, 4);
						//$qdetail = "SELECT * FROM `tbl_$table_suffix` WHERE chk_visible='1' ORDER BY vrc_title_gr, id";
						if ($mode=="edit"){
							$qdetail = "SELECT * FROM `tbl_$table_suffix` WHERE chk_visible='1' AND id NOT IN ($field_value) ORDER BY vrc_title_gr, id";
						}else{
							$qdetail = "SELECT * FROM `tbl_$table_suffix` WHERE chk_visible='1' ORDER BY vrc_title_gr, id";
						}
						if ($fieldname=="int_users_assigned"){
								$qdetail = "SELECT * FROM `tbl_users` WHERE chk_visible='1' ORDER BY vrc_title_gr, id";
						}
						//echo $qdetail;
						$rsdetail = mysql_query($qdetail);
						$optionsd = "<option value='0'></option>";
						while ($rowd = mysql_fetch_array($rsdetail)){
							$did = $rowd["id"];
							if ($table_suffix=="customers"){
								$dtitle = $rowd["vrc_first_name"]." ".$rowd["vrc_last_name"] ;
							}elseif($table_suffix=="orders"){
								$dtitle = $rowd["id"];
							}elseif($table_suffix=="rooms"){
								$dtitle = $rowd["int_hotels"]." ".$rowd["vrc_title_gr"];
							}elseif($table_suffix=="members"){
								$dtitle = $rowd["vrc_first_name"]." ".$rowd["vrc_last_name"];
							}elseif($table_suffix=="users" || $table_suffix=="users_assigned"){
								//$dtitle = $rowd["vrc_username_email"];
								$dtitle = $rowd["vrc_first_name"]." ".$rowd["vrc_last_name"];
							}elseif($table_suffix=="contacts3"){
								//$dtitle = $rowd["vrc_username_email"];
								$dtitle = $rowd["vrc_title_gr"]." ".$rowd["vrc_last_name_gr"];
							}else{
								$dtitle = $rowd["vrc_title_gr"];
							}
							
							if ($fieldname=="int_users_assigned"){
								$dtitle = $rowd["vrc_first_name"]." ".$rowd["vrc_last_name"];
							}
							
							$optionsd .= "<option value='$did'>$dtitle</option>";
						}
						
						
						
						
						//Display first the selected option
						if ($mode=="edit"){
							$selected_option = "";
							
							if ($fieldname=="int_users_assigned"){
								$table_suffix = "users";	
							}
							
							
							//$qs = "SELECT * FROM `tbl_$table_suffix` WHERE id = '$field_value' AND chk_visible='1'";
							$qs = "SELECT * FROM `tbl_$table_suffix` WHERE id IN ($field_value) AND chk_visible='1'";
								
							
							/*if ($fieldname=="int_users_assigned"){
								$qs = "SELECT * FROM `tbl_users` WHERE id = '$userid' AND chk_visible='1'";
							}
							*/
							//echo $qs;
							
							//$rss = mysql_fetch_array(mysql_query($qs));
							$qss = 	mysql_query($qs);	
							while($rss = mysql_fetch_array($qss)){
								if ($table_suffix=="customers"){
									$stitle = $rss["vrc_first_name"]." ".$rss["vrc_last_name"] ;
								}elseif($table_suffix=="orders"){
									$stitle = $rss["id"];
								}elseif($table_suffix=="rooms"){
									$stitle = $rss["int_hotels"]." ".$rss["vrc_title_gr"]; 
								}elseif($table_suffix=="members"){
									$stitle = $rss["vrc_first_name"]." ".$rss["vrc_last_name"];  
								}elseif($table_suffix=="users" || $table_suffix=="users_assigned"){
									//$stitle = $rss["vrc_username_email"];
									$stitle = $rss["vrc_first_name"]." ".$rss["vrc_last_name"];  
								}elseif($table_suffix=="contacts3"){
									$stitle = $rss["vrc_title_gr"]." ".$rss["vrc_last_name_gr"];  
								}else{
									$stitle = $rss["vrc_title_gr"];
								}
								
								$sval = $rss["id"];
								//$selected_option = "<option selected='selected' value='$sval'>$stitle</option>";
								$selected_option .= "<option selected='selected' value='$sval'>$stitle</option>";
								//$selected_option .= "<option value='0'></option>";		
							}
							
							
						}else{
							if ( ($module=="contacts" || $module=="contacts3" ) && $table_suffix == "companies"){
								$qc = "SELECT * FROM tbl_$table_suffix WHERE id = $cmpid";
								$rsc = mysql_fetch_array(mysql_query($qc));
								$company = $rsc["vrc_title_gr"];								
								$selected_option = "<option selected='selected' value='$cmpid'>$company</option>";
							}elseif($module=="projects" && $table_suffix == "contacts3"){
								$qc = "SELECT * FROM tbl_$table_suffix WHERE id = $cntid";
								//echo $qc;
								$rsc = mysql_fetch_array(mysql_query($qc));
								$contact = $rsc["vrc_title_gr"]." ".$rsc["vrc_last_name_gr"];
								$selected_option = "<option selected='selected' value='$cntid'>$contact</option>";
							}elseif($module=="crm3" && $table_suffix == "contacts3"){
								$qc = "SELECT * FROM tbl_$table_suffix WHERE id = $cntid";
								$rsc = mysql_fetch_array(mysql_query($qc));
								$contact = $rsc["vrc_title_gr"]." ".$rsc["vrc_last_name_gr"];								
								$selected_option = "<option selected='selected' value='$cntid'>$contact</option>";
							}else{						
									//Special case for email messages
									if ($module=="contacts_messages"){
										if ($fieldname=="int_contacts3"){
											$qcon = "SELECT * FROM tbl_contacts3 WHERE id = $pid";
											$rscon = mysql_fetch_array(mysql_query($qcon));
											$dtitle = $rscon["vrc_title_gr"]. " " .$rscon["vrc_last_name_gr"];
											$optionsd .= "<option value='$pid' selected>$dtitle</option>";
										}elseif($fieldname == "int_users"){
											$qusr = "SELECT * FROM tbl_users WHERE id = '$userid'";
											$rsusr = mysql_fetch_array(mysql_query($qusr));
											$dtitle = $rsusr["vrc_first_name"]." ".$rsusr["vrc_last_name"];
											$optionsd .= "<option value='$userid' selected>$dtitle</option>";
										}							
									}else{
										$selected_option = "<option selected='selected' value='0'>----</option>";	
									}
								
							}
						}
					
					echo "<tr>
							<td width=\"160\">
								<label>$label:</label>
							</td>
							<td style='padding-left:5px;'>";
					//Special case for User's rights	
					/*if ($module=="users_rights"){
						$multiple = " ";
					}else{
						$multiple = " multiple='multiple' ";
					}*/
					
					
					/*Display select object as simple drop down or multiple*/
					if ($chk_drop_down==1 || $module=="users_rights"){
						$multiple = " ";
					}else{
						$multiple = " multiple='multiple' ";
					}
					
					
					
						//Display the field
						if ($chk_only_text==1){
							if ($fieldname=="int_contacts3"){
								$qcon = "SELECT * FROM tbl_contacts3 WHERE id = $field_value";
								//echo $qcon;
								$rscon = mysql_fetch_array(mysql_query($qcon));
								$dtitle = $rscon["vrc_title_gr"]. " " .$rscon["vrc_last_name_gr"];
								//$optionsd .= "<option value='$contactid' selected>$dtitle</option>";
								echo "$dtitle</td></tr>";
							}elseif($fieldname == "int_users"){
								$qusr = "SELECT * FROM tbl_users WHERE id = '$userid'";
								$rsusr = mysql_fetch_array(mysql_query($qusr));
								$dtitle = $rsusr["vrc_first_name"]." ".$rsusr["vrc_last_name"];
								//$optionsd .= "<option value='$userid' selected>$dtitle</option>";
								echo "$dtitle</td></tr>";
							}
							
						}else{
							echo "<select $attributes name='$fieldname"; echo "[]"; echo "' $multiple>
											$selected_option
											$optionsd
										</select>								
										<a href='data.php?module=$table_suffix&mode=new' target='_blank'><img title='".$voc_array["var_add_new"]." $label' border='0' src='../images/new_50.png' width='32' /></a>
										<img src='../images/info-32.png' title='Press Ctrl + left mouse click to select multiple values for $label' />
									</td>
								</tr>";	
						}	
					}	
					
					
					if ($column_prefix=="fil"){
						$viewfile = "";
						if ($field_value!=""){
							if ($chk_thumbnail==1){
								$thumb_ext = substr($field_value, -3, 3);	
								if (in_array($thumb_ext, $supported_images_arr)){
									$thumbnail_image = "<img src='resizeimg.php?image=../$dir_upload/$field_value&new_width=100&new_height=120' />";
								}
							}						
							$viewfile = "<a style='font-size:16px; color:#fff!important;' target='_blank' href='../$dir_upload/$field_value'>View file</a>
							&nbsp;&nbsp; <label>Delete File? <input type='checkbox' name='del_$fieldname' /></label>
							<br />
							File Name: <b>$field_value</b>
							<br />
							$thumbnail_image
							";
						}
					echo "<tr>
							<td valign='top' width=\"160\">
								<label>$label:</label>
							</td>
							<td style='padding-left:5px;'>
								<input $attributes type=\"file\" name=\"$fieldname\" id='$fieldname' />
								<br /><br />
								$viewfile 
								<br /><br />
							</td>
						</tr>";	
					}	
					
					if ($column_prefix=="vrc"){
						if ($chk_url == 1 && $field_value!=""){
							$url_link = "<a href='$field_value' target='_blank'><img src='../images/url-icon-32.png' title='View page $field_value' /></a>";
						}
						
						
						if ($chk_email == 1 && $mode=="edit" && $field_value!=""){ 
							$url_link = "<a title='Send email to $field_value' target='_blank' href='data.php?module=contacts_messages&pid=$id&eml=$field_value&usr=$userid&mode=new'>
								<img src='../images/email-32.png' title='Send email to $field_value' />
								</a>";
						}
						
						/*Check field for duplicate values in database table*/
						$ajax_dupli = "";
						if ($chk_ajax_duplicate == 1 && $mode=="new"){
?>
<script>
	function showHint(module, column, value)
	{
	//alert(1);	
	var xmlhttp;
	if (value.length==0)
	  { 
	  //alert(1);	
	  document.getElementById("txtHint_"+column).innerHTML="";
	  return;
	  }
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("txtHint_"+column).innerHTML=xmlhttp.responseText;
		}
	  }
	value = encodeURI(value);  
	xmlhttp.open("GET","checkduplicate.php?dbmodule="+module+"&dbcolumn="+column+"&value="+value,true);
	xmlhttp.send();
	}
</script>	
<?php						
							$onkeyup = " onkeyup=\"showHint('$module', '$fieldname', this.value)\" ";
							//$ajax_dupli = "Check for duplicate values";		
							$ajax_dupli = "<p><span style='color:red; font-weight:bold;' id=\"txtHint_$fieldname\"></span></p> ";		
						}
						
						//Special case for email messages
						if ($module=="contacts_messages"){
							if ($fieldname=="vrc_subject" && $mode=="new"){
								$field_value = "Auto Message ";
							}
							
							if ($fieldname=="vrc_email" && $mode=="new"){
								$field_value = $_GET["eml"];
							}
							
						}
						
					
						if ($chk_only_text==1){
							echo "<tr><td width=\"160\"><label>$label:</label></td><td>$field_value</td></tr>";
						}else{	
							echo "<tr>
									<td width=\"160\">
										<label>$label:</label>
									</td>
									<td>
										<input $onkeyup $attributes $attrib_class type=\"text\" value=\"$field_value\" name=\"$fieldname\" id=\"$fieldname\" />&nbsp; $url_link  
										$ajax_dupli
									</td>
								</tr>";	
								$url_link = "";
						}	
					}	
					
					
					if ($column_prefix=="dec"){
					//first check the vrc_formula for the specific table field/column
					
					if ($chk_calculated==1 && $vrc_formula!="" && ($field_value == "0.00") ){
						$field_value = "";
						$qfor = "SELECT FORMAT(($vrc_formula),2) AS cf_formula FROM tbl_$module WHERE id='$id'";
						$rsfor = mysql_fetch_array(mysql_query($qfor));
						$field_value = $rsfor["cf_formula"];
					}
					
					echo "<tr>
							<td width=\"160\">
								<label>$label:</label>
							</td>
							<td>
								<input $attributes $attrib_class type=\"text\" value=\"$field_value\" name=\"$fieldname\" />
							</td>
						</tr>";	
					}
					
					/*if ($column_prefix=="txt"){
					echo "<tr>
							<td width=\"160\">
								<label>$label:</label>
							</td>
							<td style='padding-left:5px;'>
								<textarea $attributes $attrib_class cols=45 rows=10 name='$fieldname'>$field_value</textarea>
							</td>
						</tr>";	
					}*/
					
					if ($column_prefix=="txt"){
					echo "<tr>
							<td valign='top' width=\"160\">
								<label>$label:</label>
							</td>
							<td style='padding-left:5px;'>
						";	
					if ($mode=="new"){	
						echo $obj_fckeditor->new_fck($fieldname, 600, 400);					
					}else{
						if ($chk_only_text==1){
							echo $obj_fckeditor->update_fck($fieldname, $field_value, 600, 400, true);					
							echo "<div>$field_value</div>";
						}else{
							echo $obj_fckeditor->update_fck($fieldname, $field_value, 600, 400);					
						}	
					}	
					echo "</td>
						</tr>";	
					}
					
					
					if ($column_prefix=="ord"){
						echo "<tr><td width=\"160\"><label>$label:</label></td><td>";
						if ($mode=="edit"){		
							echo "<input $attributes $attrib_class type=\"text\" value=\"$field_value\" name=\"$fieldname\" />";
						}elseif($mode=="new"){
							$qmax = "SELECT (MAX($fieldname)+1) AS maxordid FROM `tbl_$module` WHERE chk_visible!=2";
							//echo $qmax;
							$rsmax = mysql_fetch_array(mysql_query($qmax));
							$maxordid = $rsmax["maxordid"];
							echo "<input $attributes $attrib_class type=\"text\" value=\"$maxordid\" name=\"$fieldname\" />";
						}
						echo "</td></tr>";	
					}
					
					
					if ($column_prefix=="dat"){
						if ($mode=="new"){
							$year_val = date("Y");
							$month_val = date("m");
							$day_val = date("d");
						}else{
							$year_val = substr($field_value, 0, 4);
							$month_val = substr($field_value, 5,2);
							$day_val = substr($field_value, 8,2);
						}	
						$field_value = $day_val."-".$month_val."-".$year_val;
						
						if ($chk_date=="1"){
							$attrib_date = " date ";
						}else{
							$attrib_date = "";
						}
						
					echo "<tr>
							<td width=\"160\"><label>$label:</label></td>
							<td style='padding-left:2px;'><input $attributes name='$fieldname'  value='$field_value' type=\"text\" class='datepicker $attrib_date' id=\"datepicker$ifields\">	
							</td>
						</tr>";	
					}
					
				}	
				$ifields++;
			}//end while fields
		}
		
		//echo "Num rows:$numrows";
		//echo $qtablerep;
		
	}

	

?>					
								
					</table>
					
			</div>
<?php
}//end for loop Tabs

			//Extra code for CMS Users Rights
			/*if ($module=="users"){
?>				
				<div id="tabs-50">
					<table width="100%">
						<tr>
							<th align="center">
								Ενότητα
							</th>
							<th align="center">
								Εμφάνιση
							</th>
							<th align="center">
								Εισαγωγή
							</th>
							<th align="center">
								Ενημέρωση
							</th>	
							<th align="center">
								Διαγραφή
							</th>
						</tr>
<?php
			

?>	
	
					</table>
	
				</div>		
<?php				
			}*/

			//Extra code for Purchases
			if ( ($module == "purchases" || $module=="sales" ) && $mode == "edit"){
?>				
				<div id="tabs-50">
					<table width="100%">
						<tr>
							<th align="center">
								Είδος
							</th>
							<th align="center">
								Ποσότητα
							</th>
							<th align="center">
								Αξία
							</th>	
							<th align="center">
								Αξία ΦΠΑ
							</th>
							<th align="center">
								Σύνολο Αξίας
							</th>
							<th align="center">
								Σύνολο ΦΠΑ
							</th>
							<th align="center">
								Σύνολο 
							</th>
							<th align="center">
								Διαχείριση
							</th>
						</tr>	
					
<?php			
				if ($module=="purchases"){
					$qpurdet = "SELECT * FROM tbl_purchase_det WHERE int_purchases = '$id'";
				}elseif ($module=="sales"){
					$qpurdet = "SELECT * FROM tbl_sales_det WHERE int_sales = '$id'";					
				}
				$rspurdet = mysql_query($qpurdet);
				while ($rowpurdet = mysql_fetch_array($rspurdet)) {
					$did = $rowpurdet["id"];
					$int_products = $rowpurdet[int_products];
					$qpr = "SELECT * FROM tbl_products WHERE id = $int_products";
					$rspr = mysql_fetch_array(mysql_query($qpr));
					$product = $rspr["vrc_title_gr"];
					echo "<td align='center'>$product</td>";	
					
					$vrc_qty = $rowpurdet["vrc_qty"];
					echo "<td align='center'>$vrc_qty</td>";	
					
					$dec_price = $rowpurdet["dec_price"];
					echo "<td align='center'>$dec_price &euro;</td>";	
					
					$int_tax = $rowpurdet["int_tax"];	
					$qtax = "SELECT * FROM tbl_tax WHERE id = $int_tax";
					$rspr = mysql_fetch_array(mysql_query($qtax));
					$tax = $rspr["vrc_title_gr"];
					echo "<td align='center'>$tax %</td>";	
								
					$rowtotal = $vrc_qty * $dec_price;	
					echo "<td align='center'>$rowtotal &euro;</td>";	
					
					$rowfpa = round(($tax * $dec_price)/100, 2);
					echo "<td align='center'>$rowfpa &euro;</td>";	
					
					$rowalltotal = $rowtotal + $rowfpa;
					echo "<td align='center'>$rowalltotal &euro;</td>";	
					
					echo "<td align='center'><a style='color:#fff;' href='datahandler.php?pid=$pid&did=$did&module=$module'>Διαγραφή</a></td>";
					
					//Final Totals
					$total += round($rowtotal,2);
					$fpatotal += round($rowfpa,2);
					$alltotal += round($rowalltotal,2); 
					
					echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">";
					echo "</tr>";			
				}
				echo "<tr><td align='right' colspan='20'>
							<h3>Σύνολο: $total &euro;</h3>
							<br />
							<h3>Σύνολο ΦΠΑ: $fpatotal &euro;</h3>
							<br />
							<h3>Γενικό Σύνολο: $alltotal &euro;</h3>
				</td></tr>";				
?>

					</table>	
				</div>
				
				<div id="tabs-51">
					<table width="100%">
						<tr>
							<td align="center">
								Προϊόν
							</td>
							<td align="left">
							<select name="int_products">
<?php
	$qpri = "SELECT * FROM tbl_products WHERE chk_visible = 1 ORDER BY vrc_title_gr";
	$rspri = mysql_query($qpri);
	
	while ($rowpri = mysql_fetch_array($rspri)) {
		$prid = $rowpri["id"];
		$prodname = $rowpri["vrc_title_gr"];
		
		$optpri .= "<option value='$prid'>$prodname</option>";
	}
	echo $optpri;		
?>
							</select>
							</td>
						</tr>
						<tr>
							<td align="center">
								Ποσότητα
							</td>
							<td align="left">
							<input type="text" name="vrc_qty" class="number required" style="background-color:#efefef;" />	
							</td>
						</tr>
						<tr>
							<td align="center">
								Τιμή
							</td>
							<td align="left">
							<input type="text" name="dec_price" class="number required" style="background-color:#efefef;" />	
							</td>
						</tr>	
						<tr>
							<td align="center">
								ΦΠΑ %
							</td>
							<td align="left">
							<select name="int_tax">
<?php
	$qpri = "SELECT * FROM tbl_tax WHERE chk_visible = 1 ORDER BY vrc_title_gr";
	$rspri = mysql_query($qpri);
	
	while ($rowpri = mysql_fetch_array($rspri)) {
		$tid = $rowpri["id"];
		$tname = $rowpri["vrc_title_gr"];
		
		$optt .= "<option value='$tid'>$tname</option>";
	}
	echo $optt;		
?>
							</select>
							</td>
						</tr>
					</table>	
				</div>
<?php				
	
			}
			
			
			//Extra code for Reservations(orders)
			if ($module=="orders"){
?>			
				<div id="tabs-50">
					<table width="100%">
						<tr>
							<th align="center">
								<?=$voc_array["var_room"]?>
							</th>
							<th align="center">
								<?=$voc_array["var_room_type"]?>
							</th>
							<th align="center">
								<?=$voc_array["var_price_night"]?>
							</th>	
							<th align="center">
								<?=$voc_array["var_management"]?>
							</th>
						</tr>
<?php
		$qordet = "SELECT * FROM tbl_orders_details WHERE int_orders = '$id'";
		//echo $qordet;
		$rsordet = mysql_query($qordet);
		while($rowdet = mysql_fetch_array($rsordet)){
			echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">";
			$did = $rowdet["id"];
			$roomid = $rowdet["int_rooms"];
			//get room info			
			$qroom = "SELECT * FROM tbl_rooms WHERE id='$roomid'";
			$rsroom = mysql_fetch_array(mysql_query($qroom));
			$room = $rsroom["vrc_title_gr"];
			//get room type
			$roomtype = $rsroom["int_room_types"];
			$qroomt = "SELECT * FROM tbl_room_types WHERE id='$roomtype'";
			$rsroomt = mysql_fetch_array(mysql_query($qroomt));
			$roomt = $rsroomt["vrc_title_gr"];		
			
			echo "<td align='center'>$room</td>";	
			echo "<td align='center'>$roomt</td>";	
			
			$dec_price = $rowdet["dec_price"];		
			echo "<td align='center'>$dec_price &euro;</td>";				
			//echo "<td align='center'><a target='_blank' style='color:#3383BB;' href='data.php?module=orders_details&mode=edit&pid=$did'>Επεξεργασία</a></td>";				
			echo "<td align='center'>
					<a target='_blank' style='color:#3383BB;' href='data.php?module=orders_details&mode=edit&pid=$did' title=\"Edit\">
						<img src=\"../images/edit.png\">
					</a>
				  </td>";

			echo "</tr>";			
		}
?>						
					</table>
				</div>
				<div id="tabs-51">
					<table width="100%">
						<tr>
							<th align="center">
								<?=$voc_array["var_service"]?>
							</th>
							<th align="center">
								<?=$voc_array["var_quantity"]?>
							</th>
							<th align="center">
								<?=$voc_array["var_price_night"]?>
							</th>
							<th align="center">
								<?=$voc_array["var_sub_total"]?>
							</th>
							<th align="center">
								<?=$voc_array["var_management"]?>
							</th>							
						</tr>
<?php
		$qordet = "SELECT * FROM tbl_orders_feat_details WHERE int_orders = '$id'";
		//echo $qordet;
		$rsordet = mysql_query($qordet);
		while($rowdet = mysql_fetch_array($rsordet)){
			echo "<tr onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='normal'\">";
			$did = $rowdet["id"];
			$roomf = $rowdet["int_orders_features"];
			//get room info			
			$qroomf = "SELECT * FROM tbl_orders_features WHERE id='$roomf'";
			$rsroomf = mysql_fetch_array(mysql_query($qroomf));
			$roomftitle = $rsroomf["vrc_title_gr"];
			echo "<td align='center'>$roomftitle</td>";	
			
			$qty = $rowdet["dec_qty"];
			echo "<td align='center'>$qty</td>";	
			
			$dec_price = $rowdet["dec_amount"];		
			echo "<td align='center'>$dec_price &euro;</td>";				
			
			$sub_total = (float)($qty * $dec_price);
			echo "<td align='center'>$sub_total &euro;</td>";
			
			echo "<td align='center'>
					<a target='_blank' style='color:#3383BB;' href='data.php?module=orders_feat_details&mode=edit&pid=$did' title=\"Edit\">
						<img src=\"../images/edit.png\">
					</a>
				  </td>";				
				  
			echo "</tr>";	  
		}
?>					
					</table>
				</div>
<?php				
			}
?>		
<br />
   &nbsp;&nbsp;<label>Fields with an asterisk (*) are required</label>
<br /><br />
					<input class="ui-button ui-widget ui-state-default ui-corner-all" role="button" type="submit" name="btn_submit" value="<?=$voc_array["var_save"]?>" />	
					<input class="ui-button ui-widget ui-state-default ui-corner-all" role="button" type="reset" name="btn_reset" value="<?=$voc_array["var_reset"]?>" />	
				
		</div>
		</form>	
	</td>
</tr>