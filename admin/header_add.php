<td class="right_col" width="100%" valign="top" align="left">
<noscript><b>Your browser does not support JavaScript! Please enable JavaScript or use a browser which supports JavaScript.</b></noscript>    		
		<h3>
		<!--<?=$voc_array["var_welcome"]?> <strong><?=$_SESSION["session_auth_user"]?> 
		- -->
		<!--<a style='color:#fff;' href="data.php?module=crm&mode=all&day=today"><?=$voc_array["var_today_crm"]?></a>-->
		<!--<a style='color:#fff;' href="data.php?module=crm3&mode=all&day=today"><?=$voc_array["var_today_crm"]?> (updated)</a>-->
		<!--<?=$voc_array["var_select_language"]?>:-->
<?php
	$pid = $_GET["pid"];
	if ($pid!=""){
?>
		<a style='color:#fff; font-size:16px;' href="selectlang.php?lang=gr&module=<?=$module?>&mode=<?=$mode?>&pid=<?=$pid?>"><img title="Ελληνικά" border="0" src="../images/flag_gr.png" /></a> 
		<a style='color:#fff;  font-size:16px;' href="selectlang.php?lang=en&module=<?=$module?>&mode=<?=$mode?>&pid=<?=$pid?>"><img title="English" border="0" src="../images/flag_en.png" /></a>
<?php	
	}else{
?>		
		<a style='color:#fff; font-size:16px;' href="selectlang.php?lang=gr&module=<?=$module?>&mode=<?=$mode?>"><img title="Ελληνικά" border="0" src="../images/flag_gr.png" /></a> 
		<a style='color:#fff;  font-size:16px;' href="selectlang.php?lang=en&module=<?=$module?>&mode=<?=$mode?>"><img title="English" border="0" src="../images/flag_en.png" /></a> 
<?php
	}
?>		
	<a href="javascript:history.back()" name="Back" value="Back" style="width:40px; margin-left:0px;" ><img title="Back" height="23" src="../images/back-icon-32.png" border="0" /></a>
</strong></h3> 
		
		<!--<a target="_blank" href="../../usermanual/user_manual.pdf"><img width="32" border="0" src="../images/pdf-icon.png" title="View User Manual (pdf format)" /></a>
		<a target="_blank" href="../../usermanual/user_manual.doc"><img width="32" border="0" src="../images/word-icon.png" title="View User Manual (doc format)" /></a>-->
		
		<br />
<?php
	if ($system_marquee==1){
?>		
		<!-- HTML Marquee -->
		<marquee behavior="scroll" direction="left" style='color:red; font-size:14px; font-weight:bold;' onmouseover="this.stop();" onmouseout="this.start();"> 
<?php
		$qsn = "SELECT * FROM tbl_system_news WHERE chk_visible='1' ORDER BY id";
		$rssn = mysql_query($qsn);
		while ($rowsn = mysql_fetch_array($rssn)){
			$marquee .= $rowsn["vrc_title_gr"]. " *** ";
		}
		echo $marquee;
?>			
		</marquee>
<?php
	}
?>		
		<br />	
		<div style="width:100%;">
		<!--<?=$voc_array["var_you_are_here"]?>:-->
		<a style="font-size:22px; color:#fff;" href="data.php?module=<?=$module?>&mode=all"><?=$mod_array[$module]?></a> 
		<!-- - <?=$voc_array["var_mode"]?>: <?=$mode?> - -->
		<a title="New record for <?=$module?>" style="color:#fff;" href="data.php?module=<?=$module?>&mode=new"><img width="32"  src="../images/new_50.png" border="0" /></a>
<?php
	if ($set_array["vv_export_2excel"]==1){
?>		
		<a title='Export all records to Microsoft Excel file' href='mysql2xls.php?module=<?=$module?>'><img width='32' src='../images/ms-excel.png' border='0' /></a>
<?php
	}
?>		
		
<?php		
		if ($module=="companies" && $mode=="edit"){
			echo "<a title='New Contact' target='_blank' href='data.php?module=contacts3&mode=new&cmpid=$pid'><img width='32' border='0' src='../images/user_50.png' /></a>";
		}
		
		if ($module=="contacts3" && $mode=="edit"){
			echo "<a target='_blank' title='New Task' href='data.php?module=crm3&mode=new&cntid=$pid'><img width='32'  src='../images/new-task-50.png' border='0' /></a>";
			echo "<a target='_blank' title='Last 50 Tasks' href='data.php?module=crm3&mode=all&cntid=$pid'><img width='32'  src='../images/last-50-50.png' border='0' /></a>"; 
			echo "&nbsp;<a target='_blank' title='New Project' href='data.php?module=projects&mode=new&cntid=$pid'><img width='32'  src='../images/project-50.png' border='0' /></a>"; 
			echo "&nbsp;<a target='_blank' title='View All Projects' href='data.php?module=projects&mode=all&cntid=$pid'><img src='../images/projects-50.png' border='0' /></a>"; 
		}
		
?>		
		</div>	
		<!--<label for="txtValue">Search: </label>
		<input type="text" name="txtValue" value="" id="txtValue" />
		<input type="hidden" id="module" name="module" value="<?=$module?>" />
		<div style="position:absolute; z-index:9000; color#fff; border:0px solid #efefef; background-color:#fff; margin-left:57px; width:300px;" id="display"></div>
		
		<br />
		<br />
		-->
<script type="text/javascript" charset="utf-8">

        $(document).ready(function(){
            $('#txtValue').keyup(function(){
                sendValue($(this).val());   
            }); 
            
        });
        function sendValue(str){
			$.get("ajax.php?module=<?=$module?>&sendValue="+str, 
				function(data){
					$('#display').html(data.returnValue);}
				,"json"); 
        }
        
    </script>

	