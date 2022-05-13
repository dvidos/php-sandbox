<?php
/* functions:
 * - find_files($folder_path, $order_by, $order_asc)
 *
 */

class File
{
	var $path = ''; // the relative path for links and/or manipulation (ie "files/images/photos/photo1.jpg")
	var $filename = ''; // the filename and extension (ie "photo1.jpg")
	var $name = ''; // the file name without extension (ie "photo1")
	var $extension = ''; // the file extension (ie "jpg")
	var $size; // in bytes
	var $ctime = 0; // the file creation timestamp
	var $mtime = 0; // the file modification timestamp
}

