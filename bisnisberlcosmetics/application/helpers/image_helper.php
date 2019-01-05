<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Convert image to jpeg.
 * @param string  $originalImage  Original image.
 * @param string  $outputImage    Output image.
 * @param int     $quality        Image quailty (0 worst - 100 best).
 */
function img_to_jpeg($originalImage, $outputImage, $quality)
{
  // jpg, png, gif or bmp?
  $exploded = explode('.',$originalImage);
  $ext = $exploded[count($exploded) - 1]; 

  if (preg_match('/jpg|jpeg/i',$ext))
    $imageTmp=imagecreatefromjpeg($originalImage);
  else if (preg_match('/png/i',$ext))
    $imageTmp=imagecreatefrompng($originalImage);
  else if (preg_match('/gif/i',$ext))
    $imageTmp=imagecreatefromgif($originalImage);
  else if (preg_match('/bmp/i',$ext))
    $imageTmp=imagecreatefrombmp($originalImage);
  else
    return FALSE;

  // quality is a value from 0 (worst) to 100 (best)
  imagejpeg($imageTmp, $outputImage, $quality);
  imagedestroy($imageTmp);

  return TRUE;
}

?>