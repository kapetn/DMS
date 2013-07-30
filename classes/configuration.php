<?php
error_reporting(1);
//Configuration File
//Database Variables
$dbhost = "localhost";
//$dbuser = "knowledgebase";
$dbuser = "root";
//$dbpassword = "dopplerDop2013";
$dbpassword = "usbw";
$dbschema = "knowledgebase"; 

//Site Variables
$site_url = "http://10.0.0.182:8080/knowledgebase"; 
$site_url_demo = "http://www.safehouse.com.gr"; 
$site_admin_url = "$site_url/admin";
$dir_upload = "upload_files";
$dir_images = "$site_url/images";

$Limit = 50;//Limit for normal query pagination. Deprecated after tbl_settings addon
//$sLimit = 350;//Limit for session query search pagination. Deprecated after tbl_settings addon
//$admin_email = "kapetn@yahoo.gr";. Deprecated after tbl_settings addon
$site_name = "Doppler Knowledge Base";
$site_domain = "";
$supported_images_arr = array("jpg", "png", "gif");
$weekdays_el_arr = array("Κυριακή", "Δευτέρα", "Τρίτη", "Τετάρτη", "Πέμπτη", "Παρασκευή", "Σάββατο");
$weekdays_en_arr = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");

//Version changelog
//$version = "version 3.1.9"; /* 14/05/2012 Last 50 tasks link added in Contacts, Flags icons for Greek and English language added, direct link relating New Contact with existing Company added*/
//$version = "version 3.2.1"; /* 16/05/2012 Email via CRM, direct link to Search Form tab, bug user assigned to CRM module corrected, bug display recipient email value corrected*/
//$version = "version 3.2.2"; /* Add file attachment in Contact Messages module*/
//$version = "version 3.2.3"; /* Project module was added. Relationship between Project and Contact */
//$version = "version 3.2.4"; /* Export to MS Excel feature added. Add settings database table. */

//Email variables
$donotreply_email = "donotreply@getphpscripts.com";
$support_email = "support@getphpscripts.com";
$info_email = "info@getphpscripts.com"; 
$admin_email = "kapetn@yahoo.gr, mastrogiannos@yahoo.gr,info@safehouse.com.gr";
$html_signature = "<hr /><div>the Support Team <br />Email: support@getphpscripts.com<br /><a href='http://www.getphpscripts.com'><strong>www.getphpscripts.com</strong></a></div>";

/*
$headers = "From: GETPHPSCRIPTS.COM<$donotreply_email>" . "\n";
$headers  .= 'MIME-Version: 1.0' . "\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers.="Return-Path:<$donotreply_email>\r\n"; 
// Additional headers
$headers .= "To: <$txt_newsletter>" . "\r\n";
*/

//Various Variables
$lowerchars_arr = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
$upperchars_arr = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

//Ecommerce Variables
$MerchantID = "2495";
$Currency = "978";
?>