<?php
/* functions:
 * - image_create_thumb($img_file_path, $thumb_file_path, $width, $height)
 *
 */


function image_create_thumb($img_file_path, $thumb_file_path, $width, $height)
{
	if (($image = _image_load($img_file_path)) === false)
		return false;
	
	$src_x = 0;
	$src_y = 0;
	$src_w = 0;
	$src_h = 0;
	
	$dst_w = 0;
	$dst_h = 0;
	
	_image_calculate_dimensions(
		imagesx($image), imagesy($image), 
		$width, $height, 
		$src_x, $src_y, $src_w, $src_h, $dst_w, $dst_h);
	
	//die('initial image width is ' . $original_width . ', requested width is ' . $width . ', new image width is ' . $new_img_width . ', src_x is ' . $src_x);
	
	$thumb_image = imageCreateTrueColor($dst_w, $dst_h);
	imageCopyResampled($thumb_image, $image, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

	return _image_save($thumb_file_path, $thumb_image);
}


function _image_calculate_dimensions(
	$original_width, $original_height, 
	$requested_width, $requested_height, 
	&$src_x, &$src_y, &$src_w, &$src_h, &$dst_w, &$dst_h)
{
	if ($requested_width == 0 && $requested_height == 0)
	{
		// no dimensions, no resizing
		$src_x = 0;
		$src_y = 0;
		$src_w = $original_width;
		$src_h = $original_height;
		$dst_w = $original_width;
		$dst_h = $original_height;
	}
	else if ($requested_height != 0 && $requested_width == 0)
	{
		// can be any width. resize based on height.
		$scale_factor = $requested_height / $original_height;
		
		$src_x = 0;
		$src_y = 0;
		$src_w = $original_width;
		$src_h = $original_height;
		$dst_w = $original_width * $scale_factor;
		$dst_h = $requested_height;
	}
	else if ($requested_width != 0 && $requested_height == 0)
	{
		// can be any height. resize based on width.
		$scale_factor = $requested_width / $original_width;
		
		$src_x = 0;
		$src_y = 0;
		$src_w = $original_width;
		$src_h = $original_height;
		$dst_w = $requested_width;
		$dst_h = $original_height * $scale_factor;
	}
	else if ($requested_width != 0 && $requested_height != 0)
	{
		// both dimensions given, scale, honouring aspect ratio. caller wants to fill said space.
		$dst_w = $requested_width;
		$dst_h = $requested_height;
		
		// first, try landscape image, as is usual, scale to width, check height to crop.
		$scale_factor = $requested_width / $original_width;
		$resulting_width = ceil($original_width * $scale_factor);
		$resulting_height = ceil($original_height * $scale_factor);
		
		if ($resulting_height > $requested_height)
		{
			// good, image is taller than requested. crop some at the top and bottom of it.
			$src_w = $original_width;
			$src_h = ceil($requested_height / $scale_factor);
			$src_x = 0;
			$src_y = ($original_height - $src_h) / 2;
		}
		else if ($resulting_height == $requested_height)
		{
			// good, aspect ratio is maintained!
			$src_w = $original_width;
			$src_h = $original_height;
			$src_x = 0;
			$src_y = 0;
		}
		else if ($resulting_height < $requested_height)
		{
			// image would not be tall enough, if we resized based on width. 
			// resize the height, check the width.
			$scale_factor = $requested_height / $original_height;
			$resulting_width = ceil($original_width * $scale_factor);
			$resulting_height = ceil($original_height * $scale_factor);
			
			// there is only one case: the image is wider than needed.
			// it cannot be the same, for if aspect ratio is maintened, we would be in the "elseif" above,
			// it cannot be narrower, for we would be in the first "if"
			$src_w = ceil($requested_width / $scale_factor);
			$src_h = $original_height;
			$src_x = ($original_width - $src_w) / 2;
			$src_y = 0;
		}
	}
}

function _image_load($image_file_path)
{
	log_trace('_image_load("' . $image_file_path . '")');
	$image = false;
	$extension = strtolower(pathinfo($image_file_path, PATHINFO_EXTENSION));
	
	if ($extension == "jpg" || $extension == "jpeg")
	{
		$image = imageCreateFromJPEG($image_file_path);
	}	
	elseif ($extension == "png")
	{
		$image = imageCreateFromPNG($image_file_path);
	}	
	elseif ($extension == "gif")
	{
		$image = imageCreateFromGIF($image_file_path);
	}
	
	if (!$image)
	{
		log_warning('_image_load(): could not load file "' . $image_file_path . '"');
		return false;
	}
	
	return $image;
}

function _image_save($image_file_path, $image)
{
	log_trace('_image_save("' . $image_file_path . '")');
	$extension = strtolower(pathinfo($image_file_path, PATHINFO_EXTENSION));
	
	if ($extension == "jpg" || $extension == "jpeg")
	{
		$done = imageJpeg($image, $image_file_path, 90);
	}	
	elseif ($extension == "png")
	{
		$done = imagePng($image, $image_file_path);
	}	
	elseif ($extension == "gif")
	{
		$done = imageGif($image, $image_file_path);
	}
	
	if (!$done)
	{
		log_warning('_image_save(): could not save file "' . $image_file_path . '"');
		return false;
	}
	
	return true;
}
