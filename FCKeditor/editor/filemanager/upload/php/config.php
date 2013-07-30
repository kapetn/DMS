<?php 
/*
 * 
 * "Support Open Source software. What about a donation today?"
 * 
 * File Name: config.php
 * 	Configuration file for the PHP File Uploader.
 * 
 * File Authors:
*
 */

global $Config ;

// SECURITY: You must explicitelly enable this "uploader". 
$Config['Enabled'] = true ;

// Path to uploaded files relative to the document root.
//$Config['UserFilesPath'] = '/EBEIE/Files/' ;
//$Config['UserFilesPath'] = '../../../../pics/' ;
//$Config['UserFilesPath'] = '../../browser/default/connectors/php/upload/' ;
$Config['UserFilesPath'] = '../../browser/default/connectors/upload/Image/' ;
//$Config['UserFilesPath'] = '../../../../../uploaded_images/' ;
//$Config['UserFilesPath'] = '../../browser/default/connectors/upload/Image/';
//$Config['UserFilesPath'] = '../upload/';

//$Config['UserFilesAbsolutePath'] = = '../../browser/default/connectors/php/upload/' ;
//$Config['UserFilesAbsolutePath'] = '../../../../../../upload_images/' ;
//$Config['UserFilesAbsolutePath'] = '../upload_files/' ;

$Config['AllowedExtensions']['File']	= array('pdf', 'doc', 'docx', 'txt', 'zip', 'rar') ;
$Config['DeniedExtensions']['File']		= array('php','php3','php5','phtml','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi') ;

$Config['AllowedExtensions']['Image']	= array('jpg','gif','jpeg','png') ;
$Config['DeniedExtensions']['Image']	= array() ;

$Config['AllowedExtensions']['Flash']	= array('swf','fla') ;
$Config['DeniedExtensions']['Flash']	= array() ;

?>
