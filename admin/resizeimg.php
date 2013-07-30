<?php
set_time_limit(10000);
error_reporting(E_ALL ^ E_NOTICE);
//include_once("config/mainconfig.php");

class Resize_Image {

var $image_to_resize;
var $new_width;
var $new_height;
var $ratio;
var $new_image_name;
var $save_folder;

function resize()
{
if(!file_exists($this->image_to_resize))
{
  exit("File ".$this->image_to_resize." does not exist.");
}

$info = GetImageSize($this->image_to_resize);

if(empty($info))
{
  exit("The file ".$this->image_to_resize." doesn't seem to be an image.");
}

$width = $info[0];
$height = $info[1];
$mime = $info['mime'];

/*
Keep Aspect Ratio?

Improved, thanks to Larry
*/

if($this->ratio)
{
// if preserving the ratio, only new width or new height
// is used in the computation. if both
// are set, use width

if (isset($this->new_width))
{
$factor = (float)$this->new_width / (float)$width;
$this->new_height = $factor * $height;
}
else if (isset($this->new_height))
{
$factor = (float)$this->new_height / (float)$height;
$this->new_width = $factor * $width;
}
else
exit("neither new height or new width has been set");
}

// What sort of image?

$type = substr(strrchr($mime, '/'), 1);

switch ($type)
{
case 'jpeg':
    $image_create_func = 'ImageCreateFromJPEG';
    $image_save_func = 'ImageJPEG';
	$new_image_ext = 'jpg';
    break;

case 'png':
    $image_create_func = 'ImageCreateFromPNG';
    $image_save_func = 'ImagePNG';
	$new_image_ext = 'png';
    break;

case 'bmp':
    $image_create_func = 'ImageCreateFromBMP';
    $image_save_func = 'ImageBMP';
	$new_image_ext = 'bmp';
    break;

case 'gif':
    $image_create_func = 'ImageCreateFromGIF';
    $image_save_func = 'ImageGIF';
	$new_image_ext = 'gif';
    break;

case 'vnd.wap.wbmp':
    $image_create_func = 'ImageCreateFromWBMP';
    $image_save_func = 'ImageWBMP';
	$new_image_ext = 'bmp';
    break;

case 'xbm':
    $image_create_func = 'ImageCreateFromXBM';
    $image_save_func = 'ImageXBM';
	$new_image_ext = 'xbm';
    break;

default:
	$image_create_func = 'ImageCreateFromJPEG';
    $image_save_func = 'ImageJPEG';
	$new_image_ext = 'jpg';
}

	// New Image
	$image_c = ImageCreateTrueColor($this->new_width, $this->new_height);

	$new_image = $image_create_func($this->image_to_resize);

	ImageCopyResampled($image_c, $new_image, 0, 0, 0, 0, $this->new_width, $this->new_height, $width, $height);

        if($this->save_folder)
		{
	       if($this->new_image_name)
	       {
	       $new_name = $this->new_image_name.'.'.$new_image_ext;
	       }
	       else
	       {
	       $new_name = $this->new_thumb_name( basename($this->image_to_resize) ).'_resized.'.$new_image_ext;
	       }

		$save_path = $this->save_folder.$new_name;
		}
		else
		{
		/* Show the image without saving it to a folder */
		   header("Content-Type: ".$mime);

	       $image_save_func($image_c);

		   $save_path = '';
		}

	    $process = $image_save_func($image_c, $save_path);

		return array('result' => $process, 'new_file_path' => $save_path);

	}

	function new_thumb_name($filename)
	{
	$string = trim($filename);
	$string = strtolower($string);
	$string = trim(ereg_replace("[^ A-Za-z0-9_]", " ", $string));
	$string = ereg_replace("[ tnr]+", "_", $string);
	$string = str_replace(" ", '_', $string);
	$string = ereg_replace("[ _]+", "_", $string);

	return $string;
	}
}

//$resize_image = new Resize_Image;
$resize_image = new Resize_Image();

// Folder where the (original) images are located with trailing slash at the end
//$images_dir = 'images/';
$images_dir = '';

// Image to resize
$image = $_GET['image'];

/* Some validation */
if(!@file_exists($images_dir.$image))
{
exit('The requested image does not exist.');
}

// Get the new with & height
$new_width = (int)$_GET['new_width'];
$new_height = (int)$_GET['new_height'];

$resize_image->new_width = $new_width;
$resize_image->new_height = $new_height;

$resize_image->image_to_resize = $images_dir.$image; // Full Path to the file

$resize_image->ratio = true; // Keep aspect ratio

$process = $resize_image->resize(); // Output image
?>