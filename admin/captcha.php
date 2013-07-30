<?php
//Generate CAPTCHA image for the login page
require_once("../classes/class.captcha.php");
//require_once("../include_files/class.randomchar.php");
$random_char = $_GET["random_char"];
$captcha = new captchacreate();
//$randchar = new randchar();
//$random_char = $randchar->rand_char();
$captcha->create_image($random_char);
exit();

?>