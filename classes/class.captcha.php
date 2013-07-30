<?php
/*
$captcha = new captchacreate();
$randchar = new randchar();

$random_char = $randchar->rand_char();

$captcha->create_image($random_char);
exit();
*/

class captchacreate{	

//Send a generated image to the browser	
function create_image($pass)
{

    //Set the image width and height
    $width = 200;
    $height = 50; 

    //Create the image resource 
    $image = ImageCreate($width, $height);  

    //We are making three colors, white, black and gray
    $white = ImageColorAllocate($image, 255, 255, 255);
    $black = ImageColorAllocate($image, 0, 0, 0);
    $grey = ImageColorAllocate($image, 204, 204, 204);

    //Make the background black 
    ImageFill($image, 0, 0, $black); 

    //Add randomly generated string in white to the image
    //imagestring($image,  )
    //ImageString($image, 5, 0, 0, $pass, $white); 
    $bg = imagecolorallocate($image, 255, 255, 255);
    $textcolor = imagecolorallocate($image, 255, 255, 255);
    imagestring($image, 5, 75, 20, $pass, $textcolor);

    //Throw in some lines to make it a little bit harder for any bots to break 
    ImageRectangle($image,0,0,$width-1,$height-1,$grey); 
    //imageline($image, 0, $height/2, $width, $height/2, $grey); 
    imageline($image, $width/2, 0, $width/2, $height, $grey); 
    imageline($image, $width/1.3, 0, $width/2, $height, $grey); 
    //imageline($image, $width/0.3, 0, $width/2, $height, $grey); 
    imageline($image, $width/22.3, 0, $width/2, $height, $grey); 
    imageline($image, $width/5, 0, $width/2, $height, $grey); 
 
    //Tell the browser what kind of file is come in 
    header("Content-Type: image/jpeg"); 

    //Output the newly created image in jpeg format 
    ImageJpeg($image);
   
    //Free up resources
    ImageDestroy($image);
}

}
?>