<?php
//Data Handler PHP
//error_reporting(0); 
ob_start();
session_start();
include_once("../classes/allinclude.php");
include_once("../classes/functions.php");
include_once("sessioncontrol.php");//User authentication control

//SESSION variables
$logged_user = $_SESSION["session_auth_user"];
$qus = "SELECT * FROM tbl_users WHERE vrc_username_email = '$logged_user'";
//echo $qus;
//exit();
$rsus = mysql_fetch_array(mysql_query($qus));
$rsid = $rsus["id"];

//Object instantiate
$obj_db = new dbdatabase();



//POST variables
$module = $_POST["module"];
$mode = $_POST["mode"];
$id = $_POST["id"];

//deletion, cloning, visible mode
if ($_GET){
	$module = $_GET["module"];
	$mode = $_GET["mode"];
	$pid = $_GET["pid"];//Primary key for all tables (id is the name of db column)
	$did = $_GET["did"];//Primary key for detail table tbl_purchase_det
	
	if ($mode == "delete"){
		$q = "UPDATE tbl_$module SET chk_visible = 2 WHERE id = '$pid'";
		//echo $q;
		$rs = mysql_query($q);
		
		$ins = fns_audit($module, $logged_user, $mode, "$logged_user deleted $pid", $pid);
		
		header("Location: data.php?module=$module&mode=all");
		exit();
	}
	
	if ($mode == "clone"){
		$q = "SELECT * FROM tbl_$module WHERE id = '$pid'";
		$rs = mysql_fetch_assoc(mysql_query($q));
		$countnum = count($rs)-1;
		$i=0;
		//var_dump($rs);
		foreach($rs as $k=>$val){
			if ($k!="id"){
				$fields .= $k;
				if ($i<$countnum){
					$fields .= " , ";
				}
			}
		$i++;
		}	
		//echo $fields;
		
		$q = "INSERT INTO tbl_$module ($fields) SELECT $fields FROM tbl_$module WHERE id = '$pid'";
		echo $q;
		//exit();
		$rs = mysql_query($q);
		
		$ins = fns_audit($module, $logged_user, $mode, "$logged_user cloned $pid", $pid);
		
		header("Location: data.php?module=$module&mode=all");
		exit();
	}
	
	if ($mode=="visible"){
		$q = "UPDATE tbl_$module SET chk_visible = '1' WHERE id='$pid'";
		$rs = mysql_query($q);
		
		$ins = fns_audit($module, $logged_user, $mode, "$logged_user made visible $pid", $pid);
		
		header("Location: data.php?module=$module&mode=all");
		exit();
	}
	
	if ($mode=="notvisible"){
		$q = "UPDATE tbl_$module SET chk_visible = '0' WHERE id='$pid'";
		$rs = mysql_query($q);
		
		$ins = fns_audit($module, $logged_user, $mode, "$logged_user made not visible $pid", $pid);
		
		header("Location: data.php?module=$module&mode=all");
		exit();
	}
	
	
	//if ($module == "purchases" && $pid!="" && $did!=""){
	if ($module == "purchases"){
		$qdel = "DELETE FROM tbl_purchase_det WHERE id = $did";
		$rsdel = mysql_query($qdel);
		
		$tamnt = $obj_db->gettotalamount($pid, $module);
		echo $tamnt;
		//exit();
		
		$qupdm = "UPDATE tbl_purchases SET dec_total = '$tamnt' WHERE id='$pid'";
		$rsupd = mysql_query($qupdm);
		
		header("Location: data.php?module=purchases&mode=edit&pid=$pid");		
		exit();
	}
	
	
	//if ($module == "purchases" && $pid!="" && $did!=""){
	if ($module == "sales"){
		$qdel = "DELETE FROM tbl_sales_det WHERE id = $did";
		$rsdel = mysql_query($qdel);
		
		$tamnt = $obj_db->gettotalamount($pid, $module);
		echo $tamnt;
		//exit();
		
		$qupdm = "UPDATE tbl_sales SET dec_total = '$tamnt' WHERE id='$pid'";
		$rsupd = mysql_query($qupdm);
		
		header("Location: data.php?module=sales&mode=edit&pid=$pid");		
		exit();
	}
	
	if ($mode=="order"){
		$order = $_GET["order"];
		$field = $_GET["f"];
		$val = $_GET["val"];
		
		
		if ($order=="up"){
			$q = "UPDATE tbl_$module SET $field = ($val-1) WHERE id = '$pid' ";
		}elseif($order=="down")	{
			$q = "UPDATE tbl_$module SET $field = ($val+1) WHERE id = '$pid' ";
		}
		$rs = mysql_query($q);
		
		$ins = fns_audit($module, $logged_user, $mode, "$logged_user changed orderid $pid", $pid);
		
		header("Location: data.php?module=$module&mode=all");
		exit();
	}
	
}//end GET deletion, cloning, visible-not visible

if ($_POST){
		
		//exit();
		/*Batch deletion*/
		if ($mode=="batchdelete"){
			//echo "inside mode batchdelete";
			//var_dump($_POST);
			foreach($_POST["chkbox"] as $val){
				//echo $val."<br>";
				if (is_numeric($val)){
					$qdel = "UPDATE tbl_$module SET chk_visible='2' WHERE id='$val'";
					$rsdel = mysql_query($qdel);
					$ins = fns_audit($module, $logged_user, $mode, "$logged_user batch deleted $val", $val);
				}
			}
			header("Location: data.php?module=$module&mode=all");
			exit();						
		}		
		
		if ($mode=="search"){
			/*var_dump($_POST); 
			echo "<br><br>";*/
			$qsearch = "SELECT * FROM tbl_$module WHERE chk_visible!='2' AND 1=1 ";			
			
			//now build the WHERE clause
			foreach ($_POST as $k=>$val){
				$val = mysql_real_escape_string($val);
				if (substr($k, 0, 3)!="btn" && $val!="search" && $val!="$module" && substr($k, -3, 3)!="_to"){
				
					//if ($val!=""){
					if ($val){
						echo $k."=$val<br>";
						//Fields control
						if (substr($k, 0, 3) == "int" || substr($k, 0, 3) == "vrc" || substr($k, 0, 3) == "txt" || substr($k, 0, 3) == "dat" || substr($k, 0, 3) == "dec" || substr($k, 0, 3) == "chk" ){
							if ($val!=""){ 
								//Extra code for Date fields
								if (substr($k, 0, 3) == "dat"){
									$dyear = substr($val, 6, 4);
									$dmonth = substr($val, 3, 2);
									$dday = substr($val, 0, 2);
									$date_val = $dyear."-".$dmonth."-".$dday; 
									
									$_POST[$k] = $date_val; 
									$qwhere .= " AND $k";
								}elseif(substr($k, 0, 3) == "chk"){
									if ($val=="Yes"){
										$_POST[$k] = "1";
									}elseif($val=="No"){
										$_POST[$k] = "0";
									}
									$qwhere .= " AND $k";
								}elseif(substr($k, 0, 3) == "int"){
									//$qwhere .= " AND $k ";
									$qwhere .= " AND ";
								}else{
									$qwhere .= " AND $k";
								}	
							}
						}
						//Operators control
						//if (substr($k, 0, 3) == "sel" && $_POST[substr($k,4)]!=""){
						if (substr($k, 0, 3) == "sel" && $_POST[substr($k,4)]){
							if ($val=="%LIKE%" ){
								$qwhere .= " LIKE '%".$_POST[substr($k,4)]."%' ";
							}elseif($val=="="){
								//$qwhere .= " = '".$_POST[substr($k,4)]."' ";
								//$qwhere .= " FIND_IN_SET('b','a,b,c,d') ( ".$_POST[substr($k,4)]." ) ";
								if (substr($k, 4,3)=="int"){
									//$qwhere .= " IN ('".$_POST[substr($k,4)]."') ";
									$qwhere .= " FIND_IN_SET('".$_POST[substr($k,4)]."',".substr($k, 4).") ";
									//$qwhere .= " FIND_IN_SET('$k', '".$_POST[substr($k,4)]."') ";
								}else{
									$qwhere .= " = '".$_POST[substr($k,4)]."' ";
								}
								
							}elseif($val=="!="){
								$qwhere .= " != '".$_POST[substr($k,4)]."' ";
							}elseif($val=="%LIKE"){
								$qwhere .= " LIKE '%".$_POST[substr($k,4)]."' ";
							}elseif($val=="LIKE%"){
								$qwhere .= " LIKE '".$_POST[substr($k,4)]."%' ";
							}elseif($val=="ISNOTNULL"){
								$qwhere .= " != '' ";
							}elseif ($val=="GREATERTHANOREQUAL"){
								$qwhere .= " >= ".$_POST[substr($k,4)]." ";
							}elseif ($val=="LESSTHANOREQUAL"){
								$qwhere .= " <= ".$_POST[substr($k,4)]." ";
							}elseif($val=="BETWEEN"){
								$valto = $_POST[substr($k."_to",4)];
								$dyearto = substr($valto, 6, 4);
								$dmonthto = substr($valto, 3, 2);
								$ddayto = substr($valto, 0, 2);
								$date_valto = $dyearto."-".$dmonthto."-".$ddayto; 
								
								$qwhere .= " BETWEEN '".$_POST[substr($k,4)]."' AND '".$date_valto."'";
							}
						}
					}	
					
				}
			}			
			echo $qsearch.$qwhere;
			$_SESSION["session_query"] = $qsearch.$qwhere;
			//var_dump($_SESSION);
			//exit();
			header("Location: data.php?module=$module&mode=all&sq=1");
			exit();
		}
		
		
		if ($mode == "new"){
			//Find all columns. Columns are all form fields, except hidden fields 'module', 'mode' and 'id'
			$countnum = count($_POST)-3;//Find the array elements - the 3 hidden fields
			echo "Count array elements: $countnum <br>";
			$i=0;
			
			if ($module=="contacts_messages"){
				$columns .= "dat_sys_creation,";
				$values .= "now(),";
			}
			
			foreach ($_POST as $k=>$val){
				if ($k!="module" && $k!="mode" && $k!="pid" && $k!="btn_submit"){
					$val = mysql_real_escape_string($val);
					
					//Add extra code for password in new CMS user
					if ($k=="vrc_password"){
						$val = sha1($val);
					}
					
					//Convert Date Values
					if (substr($k, 0, 3)=="dat"){
						$year_val = substr($val, 6, 4);
						$month_val = substr($val, 3, 2); 
						$day_val = substr($val, 0, 2); 
						$val = "$year_val-$month_val-$day_val";
					}
					
					if (substr($k, 0, 3)=="int"){
						$count_opt = count($_POST[$k]);
						$ii=1;
						$valsopt = "";
						if ($count_opt>0){
							foreach ($_POST[$k] as $selopt){
								//if ($ii<$count_opt){
								if ($ii<$count_opt && ($ii>0 && $count_opt>1 )){  
									//echo $selopt.",<br>";
									$valsopt .= "$selopt,"; 
								}else{
									$valsopt .= $selopt; 
									//$selopt .= $selopt;
									//echo $selopt."<br>";
								}
							$ii++;	
							$val = $valsopt;
							}	
						}
						//echo $val."<br>";	
					}
					
					$values .= "'$val'" ;
					$columns .= "`$k`";
					if ($i<=$countnum){
						$values .= " ,";
						$columns .= " ,";
					}
				}
				$i++;
			}
			
			
			
			//Upload the Files
			$i=1;
			$countnum = count($_FILES);//Find the array elements
			foreach($_FILES as $k=>$val){
				//"<br>".var_dump($_FILES)."<br>";
				//var_dump($val)."<br><br>";
				$filename = safefilename($val["name"]);
				//echo $val["name"];
				//$filename = $k[$i];
				//var_dump($k);
				//$upload = move_uploaded_file($_FILES["$k"]["tmp_name"], $dir_upload . "/" . $_FILES["$k"]["name"]);
				$upload = move_uploaded_file($_FILES["$k"]["tmp_name"], "../" . $dir_upload . "/" . $filename);
				//echo $k." - $filename<br>";				
				
				if ($val["name"]!=""){
					$columns .= " ,";
					$values .= " ,";
					
					//$columns .= " `$k` = '$filename' ";
					$columns .= " `$k` ";
					$values .= " '$filename' ";
					/*if ($i>1 && $i<$countnum){
						$columns .= " , ";
					}*/
				}
				$i++;
			}
			
			
			
			/*echo "Values: $values <br>";
			echo "Columns: $columns";*/
			//exit();
			
			/*Special code for ticketing system
			  Send email to administrators with every new ticket
			*/
			if ($module=="tickets"){
				$columns .= " ,int_users ";
				$values .= " ,'$rsid' ";
				
				$email_subject = "$site_name new ticket notification";
				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=UTF8' . "\r\n";
				// Additional headers
				$headers .= "To: $admin_email\r\n";
				$headers .= "From: $site_name<donotreply@$site_domain>\r\n";
								
				$email_message = "<html><head></head><body>$values</body></html>";

				$ok = @mail($admin_email, $email_subject, $email_message, $headers);//Send email to admin with ticket notification 
			}
			
			$query = "INSERT INTO tbl_$module 
					  ($columns) 
					  VALUES 
					  ($values)";
			/*echo $query;
			exit();*/
			$rs = mysql_query($query);
			
			$qmax = "SELECT MAX(id) AS maxid FROM tbl_$module";
			$rsmax = mysql_fetch_array(mysql_query($qmax));
			$maxid = $rsmax["maxid"];
			$ins = fns_audit($module, $logged_user, $mode, "$logged_user created record $maxid", $maxid);
			
			if ($module=="crm" || $module=="crm3"){
				//$q = "UPDATE tbl_$module SET `int_users_assigned` = '$rsid' WHERE id='$maxid'";
				$q = "UPDATE tbl_$module SET `int_users` = '$rsid' WHERE id='$maxid'";
				$rsq = mysql_query($q);
			}
			
			//Send email to the recipient
			if ($module=="contacts_messages"){ 
				//Now send the email with the activation link
				$subjectc = $_POST["vrc_subject"];
				$messagec = $_POST["txt_message"];
				/*$sendemail = sendhtmlemail( $logged_user, $_POST["vrc_email"], $subjectc, $messagec );//send email to recipient
				$sendemail = sendhtmlemail( $logged_user, $logged_user, $subjectc, $messagec );//send copy to sender		*/
				
						$fileatt = "../upload_files/$filename"; // Path to the file 
						$file_ext = substr($filename, -3, 3);
						$fileatt_type = "application/$file_ext"; // File Type 
						$fileatt_name = "$filename"; // Filename that will be used for the file as the attachment

						$email_from = $logged_user; // Who the email is from 
						$email_subject = $subjectc; // The Subject of the email 
						$email_message = $messagec;
						$email_to = $_POST['vrc_email']; // Who the email is to

						$headers = "From: ".$email_from;

						$file = fopen($fileatt,'rb'); 
						$data = fread($file,filesize($fileatt)); 
						fclose($file);

						$semi_rand = md5(time()); 
						$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

						$headers .= "\nMIME-Version: 1.0\n" . 
						"Content-Type: multipart/mixed;\n" . 
						" boundary=\"{$mime_boundary}\"";

						$email_message .= "This is a multi-part message in MIME format.\n\n" . 
						"--{$mime_boundary}\n" . 
						"Content-Type:text/html; charset=\"UTF-8\"\n" . 
						"Content-Transfer-Encoding: 7bit\n\n" . 
						$email_message .= "\n\n";

						$data = chunk_split(base64_encode($data));

						$email_message .= "--{$mime_boundary}\n" . 
						"Content-Type: {$fileatt_type};\n" . 
						" name=\"{$fileatt_name}\"\n" . 
						//"Content-Disposition: attachment;\n" . 
						//" filename=\"{$fileatt_name}\"\n" . 
						"Content-Transfer-Encoding: base64\n\n" . 
						$data .= "\n\n" . 
						"--{$mime_boundary}--\n";

						$ok = @mail($email_to, $email_subject, $email_message, $headers);//Send email to recipient
						$ok = @mail($logged_user, $email_subject, $email_message, $headers);//Send copy email to sender

						if($ok) { 
						echo "You file has been sent
						to the email address you specified.

						Make sure to check your junk mail!

						Click here to return to mysite.com.";

						} else { 
						die("Sorry but the email could not be sent. Please go back and try again!"); 
						} 
				
				
				//exit();
				
			}
			
			header("Location: data.php?module=$module&mode=all");
			exit();
		}
		
		if ($mode == "edit"){
			$id = $_POST["pid"];
			//var_dump($_POST)."<br>";
			//var_dump($_FILES)."<br>";
			//exit();
			
			//Find all columns. Columns are all form fields, except hidden fields 'module', 'mode' and 'id'
			$countnum = count($_POST)-3;//Find the array elements - the 3 hidden fields
			//echo "Count array elements: $countnum <br>";
			$i=0;
			$val = "";
			foreach ($_POST as $k=>$val){
				if ($k!="module" && $k!="mode" && $k!="pid" && $k!="btn_submit" && substr($k, 0, 3)!="del"){
					
					//Add extra code for password in new CMS user
					if ($k=="vrc_password"){
						$val = sha1($val);
					}
					
					//Convert Date Values
					if (substr($k, 0, 3)=="dat"){
						$year_val = substr($val, 6, 4);
						$month_val = substr($val, 3, 2); 
						$day_val = substr($val, 0, 2); 
						$val = "$year_val-$month_val-$day_val";
					}
					
					if (substr($k, 0, 3)=="int"){
						$count_opt = count($_POST[$k]);
						$ii=1;
						if ($count_opt>0){
							$valsopt = "";
							foreach ($_POST[$k] as $selopt){
								if ($ii<$count_opt && ($ii>=1 && $count_opt>1)){
									//echo $selopt.",<br>";
									$valsopt .= "$selopt,"; 
								}else{
									$valsopt .= $selopt; 
									//$selopt .= $selopt;
									//echo $selopt."<br>";
								}
							$ii++;	
							$val = $valsopt;
							}	
						}
						//echo $val."<br>";	
					}
					
					$val = mysql_real_escape_string($val);
					$columns .= "`$k`='$val'";
					
					/*echo $columns;
					exit();
					*/
					
					if ($i<=$countnum){
						$columns .= " ,";
					}
				}
				$i++;
			}//end foreach POST
			
			//exit();
			
			//Upload the Files
			$i=1;
			$countnum = count($_FILES);//Find the array elements
			foreach($_FILES as $k=>$val){
				//"<br>".var_dump($_FILES)."<br>";
				//var_dump($val)."<br><br>";
				$filename = safefilename($val["name"]);
				//echo $val["name"];
				//$filename = $k[$i];
				//var_dump($k);
				//$upload = move_uploaded_file($_FILES["$k"]["tmp_name"], $dir_upload . "/" . $_FILES["$k"]["name"]);
				$upload = move_uploaded_file($_FILES["$k"]["tmp_name"], "../" . $dir_upload . "/" . $filename);
				//echo $k." - $filename<br>";				
				
				if ($val["name"]!=""){
					$columns .= " ,";
					
					$columns .= " `$k` = '$filename' ";
					/*if ($i>1 && $i<$countnum){
						$columns .= " , ";
					}*/
				}
				$i++;
			}
			
			
			//echo "<br>".$columns."<br>";
			$query = "UPDATE tbl_$module SET $columns WHERE id = '$id'";
			//echo $query;
			//exit();
			$rs = mysql_query($query);
			
			$ins = fns_audit($module, $logged_user, $mode, "$logged_user updated $id", $id);
			
			//Do the file's deletion
			foreach ($_POST as $k=>$val){
			 //echo "$k<br>";
				if (substr($k, 0, 3) == "del"){
					$column_name = substr($k,4);
					//echo "ok";
					//echo $_POST["del_$k"];
					if ($_POST["$k"]=="on"){
						$q = "UPDATE tbl_$module  SET `$column_name`='' WHERE id='$id'";
						echo $q."<br>";
						$rs = mysql_query($q);
					}
				}
			}//end foreach
			
			//exit();
			
			/*Special code for Purchases Detail table*/
			if ($module=="purchases" && $mode=="edit"){
				
				$int_products = $_POST["int_products"];
				$vrc_qty = $_POST["vrc_qty"];
				$dec_price = $_POST["dec_price"];
				$int_tax = $_POST["int_tax"];
				
				var_dump($_POST);
				//exit();
				
				
				$qdet = "INSERT INTO tbl_purchase_det 
						 (int_purchases, 
						  int_products,
						  vrc_qty,
						  dec_price,
						  int_tax
						 ) 
						 VALUES 
						 ($id,
						  $int_products,
						  $vrc_qty,
						  $dec_price,
						  $int_tax
						 )";	
				//echo $qdet;
				$rsdet = mysql_query($qdet);
				//exit();
				
				//UPDATE THE TOTAL AMOUNT COLUMN IN THE MASTER TABLE			
				$tamnt = $obj_db->gettotalamount($id, $module);
				$qupdm = "UPDATE tbl_purchases SET dec_total = '$tamnt' WHERE id='$id'";
				$rsupd = mysql_query($qupdm);
			}
			
			
			/*Special code for Purchases Detail table*/
			if ($module=="sales" && $mode=="edit"){
				
				$int_products = $_POST["int_products"];
				$vrc_qty = $_POST["vrc_qty"];
				$dec_price = $_POST["dec_price"];
				$int_tax = $_POST["int_tax"];
				
				var_dump($_POST);
				//exit();
				
				
				$qdet = "INSERT INTO tbl_sales_det 
						 (int_sales, 
						  int_products,
						  vrc_qty,
						  dec_price,
						  int_tax
						 ) 
						 VALUES 
						 ($id,
						  $int_products,
						  $vrc_qty,
						  $dec_price,
						  $int_tax
						 )";	
				//echo $qdet;
				$rsdet = mysql_query($qdet);
				//exit();
				
				//UPDATE THE TOTAL AMOUNT COLUMN IN THE MASTER TABLE			
				$tamnt = $obj_db->gettotalamount($id, $module);
				$qupdm = "UPDATE tbl_sales SET dec_total = '$tamnt' WHERE id='$id'";
				$rsupd = mysql_query($qupdm);
			}
			
			
			if ($module=="crm" || $module=="crm3"){
				//$q = "UPDATE tbl_$module SET `int_users_assigned` = '$rsid' WHERE id='$id'";
				$q = "UPDATE tbl_$module SET `int_users` = '$rsid' WHERE id='$id'";
				$rsq = mysql_query($q);
			}
			
			/*Unlock table record*/
			$qunlock = "UPDATE tbl_record_lock SET chk_visible='0' WHERE vrc_table = 'tbl_$module' AND vrc_record_id='$id'";
			$rsunlock = mysql_query($qunlock);
			
			//header("Location: data.php?module=$module&mode=edit&pid=$id");
			header("Location: data.php?module=$module&mode=all");//Changed after record locking procedure 08.06.2012
			
			exit();
			
		}//end mode=edit
		
}


function safefilename($file){
	$today = date("Ymdhis");
	$safe_filename = preg_replace(
                     array("/\s+/", "/[^-\.\w]+/"),
                     array("_", ""),
                     trim($file));
			
    $safe_filename = $today.$safe_filename; 
	//echo $safe_filename;
return $safe_filename;                     
}    

function uploadfile($file, $uploaddir){
$today = date("Ymdhis");	
	if ($_FILES[$file]["name"]!=""){
		//echo $_FILES["FL_image1"]["name"];
		//rename uploaded image					   
        $uploadfile = "../" .$uploaddir. "/" . basename(safefilename($today, $_FILES[$file]["name"]));
        //echo "Upload Dir: "$uploadfile;                    
		move_uploaded_file($_FILES[$file]['tmp_name'], $uploadfile);
                	    
	}
}
?>