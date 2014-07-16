<?php
/*
* File: SimpleImage.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 08/11/06 Ä
* Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
* 
* This program is free software; you can redistribute it and/or 
* modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation; either version 2 
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful, 
* but WITHOUT ANY WARRANTY; without even the implied warranty of 
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
* GNU General Public License for more details: 
* http://www.gnu.org/licenses/gpl.html
*
*/
class SimpleImage {
   
   var $image;
   var $image_type;
   var $image_info;

	function  SimpleImage()
	{
		$this->image_info=false;
	}
//
	function load($filename)
	{
		$this->image_info = @getimagesize($filename);
		if($this->image_info)
		{
			$this->image_type = $this->image_info[2];
			if( $this->image_type == IMAGETYPE_JPEG )
			{
				$this->image = imagecreatefromjpeg($filename);
			}
			elseif( $this->image_type == IMAGETYPE_GIF )
			{
				$this->image = imagecreatefromgif($filename);
			}
			elseif( $this->image_type == IMAGETYPE_PNG )
			{
				$this->image = imagecreatefrompng($filename);
				imagealphablending($this->image , true);
			}
		}
	}
//
	function save($filename,  $compression=85, $permissions=0755)
	{
		if($this->image_info)
		{
			if( $this->image_type == IMAGETYPE_JPEG )
			{
				imagejpeg($this->image,$filename,$compression);
			}
			elseif( $this->image_type == IMAGETYPE_GIF )
			{
				imagegif($this->image,$filename);  
			}
			elseif( $this->image_type == IMAGETYPE_PNG )
			{
				imagepng($this->image,$filename);
			}
			if( $permissions != null)
			{
				chmod($filename,$permissions);
			}
		}
	}
//
	function output($image_type=IMAGETYPE_JPEG)
	{
		if($this->image_info)
		{
			if( $image_type == IMAGETYPE_JPEG )
			{
				imagejpeg($this->image);
			}
			elseif( $image_type == IMAGETYPE_GIF )
			{
				imagegif($this->image);
			}
			elseif( $image_type == IMAGETYPE_PNG )
			{
				imagepng($this->image);
			}
		} 
	}
//
	function getWidth()
	{
		if($this->image_info)
		{
			return $this->image_info[0];
		}
	}
//
	function getHeight()
	{
		if($this->image_info)
		{
			return $this->image_info[1];
		}
	}
//
	function resizeToHeight($height)
	{
		if($this->image_info)
		{
			$ratio = $height / $this->getHeight();
			$width = $this->getWidth() * $ratio;
			$this->resize($width,$height);
		}
	}

	function resizeToWidth($width)
	{
		if($this->image_info)
		{
			$ratio = $width / $this->getWidth();
			$height = $this->getheight() * $ratio;
			$this->resize($width,$height);
		}
	}
//
	function scale($scale)
	{
		if($this->image_info)
		{
			$width = $this->getWidth() * $scale/100;
			$height = $this->getheight() * $scale/100;
			$this->resize($width,$height);
		}
	}
//
	function resize($width,$height)
	{
		if($this->image_info)
		{
			$new_image = imagecreatetruecolor($width, $height);
			imagealphablending( $new_image, false);
			imagesavealpha( $new_image, true);
			imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
			$this->image = $new_image;
		}
	}
}
?>