<?php
class dbdatabase{
	public function sql_select_all_one_row($dbtable, $WHERE=""){
		$query = "SELECT * FROM $dbtable  $WHERE";
		//echo $query;
		$sql = mysql_query($query);
		$rs = mysql_fetch_array($sql);
		return $rs;	
	}	
	
	public function sql_select_columns($dbtable, $COLUMNS="*", $WHERE=""){
		$query = "SELECT $COLUMNS FROM $dbtable  $WHERE";
		//echo $query;
		$sql = mysql_query($query);
		$rs = mysql_fetch_array($sql);
		return $rs;	
	}
	
	public function sql_update_one_row($dbtable, $COLUMNS, $WHERE){
		$query = "UPDATE $dbtable SET $COLUMNS $WHERE";
		$sql = mysql_query($query);
	}
	
	public function sql_select_all($dbtable,  $WHERE="", $ORDERBY=""){
		$query = "SELECT * FROM $dbtable "." ".$WHERE." ".$ORDERBY;
		$sql = mysql_query($query);
		return $sql;
	}
	
	public function sql_insert_one($dbtable, $COLUMNS, $VALUES){
		$q = "INSERT INTO `$dbtable` ($COLUMNS) VALUES ($VALUES)";
		$sql = mysql_query($q);
	}
	
	public function sql_num_rows($dbtable, $WHERE){
		$q = "SELECT COUNT(*) AS countnum FROM `$dbtable` $WHERE";
		//echo $q;
		$rs = mysql_fetch_array(mysql_query($q));
		$countnum = $rs["countnum"];
		return $countnum;
	}
	
	public function sql_delete_one_row($dbtable, $WHERE){
		$q = "DELETE FROM `$dbtable` $WHERE ";
		$rs = mysql_query($q);
	}
	
	
	public function gettotalamount($pid, $module){
		if ($module=="purchase"){
			$qpurdet = "SELECT * FROM tbl_purchase_det WHERE int_purchases = '$pid'";
		}elseif ($module=="sales"){
			$qpurdet = "SELECT * FROM tbl_sales_det WHERE int_sales = '$pid'";			
		}
				$rspurdet = mysql_query($qpurdet);
				while ($rowpurdet = mysql_fetch_array($rspurdet)) {
					$did = $rowpurdet["id"];
					
					$vrc_qty = $rowpurdet["vrc_qty"];
					$dec_price = $rowpurdet["dec_price"];
					$int_tax = $rowpurdet["int_tax"];	
					$qtax = "SELECT * FROM tbl_tax WHERE id = $int_tax";
					$rspr = mysql_fetch_array(mysql_query($qtax));
					$tax = $rspr["vrc_title_gr"];
								
					$rowtotal = $vrc_qty * $dec_price;	
					$rowfpa = round(($tax * $dec_price)/100, 2);
					$rowalltotal = $rowtotal + $rowfpa;
					
					//Final Totals
					$total += round($rowtotal,2);
					$fpatotal += round($rowfpa,2);
					$alltotal += round($rowalltotal,2); 
				}
		return $alltotal;		
	}
	
	public function getpurchaseqty($pid){
		$sql = "SELECT SUM(vrc_qty) AS sumqty FROM tbl_purchase_det, tbl_purchases WHERE int_products = $pid AND chk_visible!=2";
		$rs = mysql_fetch_array(mysql_query($sql));

		return $rs["sumqty"];	
	}
	
	public function getsalesqty($pid){
		$sql = "SELECT SUM(vrc_qty) AS sumqty FROM tbl_sales_det, tbl_sales WHERE int_products = $pid AND chk_visible!=2";
		$rs = mysql_fetch_array(mysql_query($sql));

		return $rs["sumqty"];	
	}
	
	public function productsmenu(){
		$dbtable = "tbl_pr_categories";
		$ORDERBY = " ORDER BY ord_orderid, vrc_title_gr ";
		$sqlcat = $this->sql_select_all($dbtable, " WHERE chk_visible=1 AND int_pr_categories=0 ", $ORDERBY);
		while ($cat = mysql_fetch_array($sqlcat)) {
			$ctitle = $cat["vrc_title_gr"];
			$cid = $cat["id"];	
			
			//$cats .= "<a href='$site_url/products/$cid/'>$ctitle</a><br />";
			$cats .= "$ctitle<br />";
			
			/*if ($this->subcatincat($cid)>0){
				$cats .= "<a href='$site_url/products/$cid/'>$ctitle</a><br />";
			}elseif ($this->productsincat($cid)>0){
				$cats .= "<a href='$site_url/products/$cid/'>$ctitle</a><br />";
			}*/	
			
			//Display subcategories
			$sqlscat = $this->sql_select_all($dbtable, " WHERE chk_visible=1 AND int_pr_categories=$cid ", $ORDERBY);
			while ($scat = mysql_fetch_array($sqlscat)) {
				$sid = $scat["id"];
				$stitle = $scat["vrc_title_gr"];
				
				$cats .= "&nbsp;&nbsp;<a href='$site_url/products/$cid/$sid'>-$stitle</a><br />";
				
				/*if ($this->productsincat($sid)>0){
					$cats .= "&nbsp;&nbsp;<a href='$site_url/products/$cid/$sid'>-$stitle</a><br />";
				}*/
			}
			
		}
		echo $cats;
	}
	
	//Display the subcategory only if it contains visible products
	public function productsincat($cid){
		$q = "SELECT COUNT(*) AS countnum FROM tbl_products WHERE chk_visible=1 AND int_pr_categories=$cid";
		$rs = mysql_fetch_array(mysql_query($q));
		$countnum = $rs["countnum"];
		
		return $countnum;
	}
	
	//Display the Main Category only if it contains visible subcategories and the subcategory visible products
	public function subcatincat($cid){
		$q = "SELECT COUNT(*) AS countnum 
			  FROM tbl_pr_categories 
			  WHERE chk_visible=1 
			  AND int_pr_categories=$cid 
			  ";
		//echo $q;
		$rs = mysql_fetch_array(mysql_query($q));
		$countnum = $rs["countnum"];
		
		return $countnum;
	}
	
	public function productfeatures($pid){
		$pf = "";
		$hiddenfeatures = "";
		$qpf = "SELECT * FROM tbl_pr_features WHERE int_products=$pid AND chk_visible=1";
		//echo $qpf;
		$rspf = mysql_query($qpf);
		while ($rowpf = mysql_fetch_array($rspf)){
			$decpricepf = $rowpf["dec_price"];
			$titlepf = $rowpf["vrc_title_gr"];					
			$id = $rowpf["id"];
			
			$hiddenfeatures .= "$titlepf  Τιμή: $decpricepf <br>";
			
			$ppf .= "<tr><td>$titlepf</td><td><strong>$decpricepf &euro;</strong></td><td><input class='pfeat' type='checkbox' id='pfeat' name='productfeat[$id]' title='$titlepf $decpricepf' value='$decpricepf' /></td></tr>";
		}
		
		//$ppf .= "<tr><td><span style='display:none;' id='hiddenfeatures'>$hiddenfeatures</span></td></tr>";
		return $ppf;
	}
	
	//Calculate total cart items
	public function cartitems($sessionid){
		$dbtable = "tbl_orders_details";
		$q = "SELECT COUNT(*) AS countnum FROM $dbtable WHERE vrc_sessionid = '$sessionid' AND chk_visible=1";
		$rs = mysql_fetch_array(mysql_query($q));
		$cartitems = $rs["countnum"];
		if ($cartitems==0){
			$cartitems = "KANENA ";
		}
		return $cartitems;
	}
	
	
	//Calculate total cart amount
	public function cartamount($sessionid){
		$dbtable = "tbl_orders_details";
		$q = "SELECT SUM(dec_price) AS sumtot FROM $dbtable WHERE vrc_sessionid = '$sessionid' AND chk_visible=1";
		$rs = mysql_fetch_array(mysql_query($q));
		$sumtot = $rs["sumtot"];
		if ($sumtot=="")$sumtot="0.00";
		return $sumtot;
	}
}//end class
?>