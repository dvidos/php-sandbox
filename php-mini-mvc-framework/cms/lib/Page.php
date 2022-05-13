<?php
/* functions:
 * - page_exists($p)
 * - load_page($p)
 * - load_pages($folder_path, $order_by, $order_asc)
 *
 */

class Page
{
	var $title = ''; // the page title
	var $meta_keywords = ''; // if not present, the $config['default_meta_keywords'] will be used.
	var $meta_description = ''; // if not present, the $config['default_meta_desription'] will be used.
	
	var $image = ''; // an image of the page
	var $text = ''; // the content!
	
	var $path = ''; // used for generating links.
	var $mtime = 0; // the file modification timestamp
	var $template = ''; // the main template to use. 
	var $layout = ''; // for single or double column layouts, sidebars, etc, within the template
}


