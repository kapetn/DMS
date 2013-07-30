<?php
//Functions PHP script
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}


function sendhtmlemail($from, $to, $subject="Message", $message){ 
							//$from = "Demo CRM<$from>"; // Who the email is from
						    // To send HTML mail, the Content-type header must be set
							$headers = "From: " . $from . "\n";
							$headers .= "Reply-To: ". $from . "\n";
							$headers .= "MIME-Version: 1.0\n";
							$headers .= "Content-Type: text/html; charset=UTF-8\n";
							
							$message = '<html><body>'.$message;
							$message .= "</body></html>";
							/*$message .= "This is a multi-part message in MIME format.\n\n" .
							            "--{$mime_boundary}\n" .
							            "Content-Type:text/html; charset=\"utf8\"\n" .
							            "Content-Transfer-Encoding: 8bit\n\n" .
							$message . "\n\n";
							$message .= "--{$mime_boundary}--\n";*/
				            //Send every email
				            mail($to, $subject, $message, $headers);
		//return $mail;
}//end method emailattach  




function fns_audit($module, $username, $mode, $title, $id=""){
	$q = "INSERT INTO tbl_audit
		  (vrc_username,
		   dat_creation,
		   vrc_title_gr,
		   chk_visible,
		   record_id,
		   vrc_tablename
		  ) 
		  VALUES 
		  ('$username',
		   now(),
		   '$title',
		   '1',
		   '$id',
		   'tbl_$module'
		   )";
	//echo $q;	   
	$ins = mysql_query($q);	   
	//exit();
}

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
?>