<?php
class sendemail{
	
	public function sendhtmlemail($fromtxt="", $from, $to, $subject="Message", $message){
		  			
							$from = "$fromtxt<$from>"; // Who the email is from
						    //$fileatt_type = "image/jpeg"; // File Type
							// subject
							// To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

							// Additional headers
							$headers .= "To: <$txt_newsletter>" . "\r\n";
							$headers .= "From: getphpscripts.com <$donotreply_email>" . "\r\n";	
							            
							$message = $message;
							$message .= "This is a multi-part message in MIME format.\n\n" .
							            "--{$mime_boundary}\n" .
							            "Content-Type:text/html; charset=\"utf-8\"\n" .
							            "Content-Transfer-Encoding: 8bit\n\n" .
							$message . "\n\n";
							$message .= "--{$mime_boundary}--\n";
				        
				            //Send every email
				            mail($to, $subject, $message, $headers);
			
		//return $mail;
		  	
	}//end method emailattach  
	
}