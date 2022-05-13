<?php
	/* we shall prepare a thumbs gallery, with images and thumbs, using lightbox.
	 sample call:
	
	app()->load_template('images_thumbs', array(
		'baseDir'=>'assets/images/products/fx_copper/',
		'thumbPrefix'=>'',
		'thumbSuffix'=>'_th',
		'tableAttributes'=>array('class'=>'thumbs'),
		'cellAttributes'=>array('class'=>'thumbs'),
		'imageAttributes'=>array(),
		'columns'=>6,
		'rel'=>'lightbox[a]',
		'images'=> array(
			'copper01.jpg', 
		),
	));

	*/
	
	
	if (!isset($baseDir))
		$baseDir = './';
		
	if (!isset($thumbPrefix))
		$thumbPrefix = '';
		
	if (!isset($thumbSuffix))
		$thumbSuffix = '_th';
		
	if (!isset($divAttributes))
		$divAttributes = array('class'=>'thumbs_gallery');
		
	if (!isset($itemDivAttributes))
		$itemDivAttributes = array('class'=>'thumbs_item');
		
	if (!isset($linkAttributes))
		$linkAttributes = array();
		
	if (!isset($thumbAttributes))
		$thumbAttributes = array();
	
	if (!isset($lightboxRel))
		$lightboxRel = 'lightbox[a]';
	
	
	
	$html = '';
	
	$html .= html::openTag('div', $divAttributes);
	$html .= "\r\n";
	$count = 0;
	$max_count = 0;
	
	foreach ($images as $image)
	{
		$imageHref = $baseDir . $image;
		$info = pathinfo($imageHref);
		$thumbHref = $baseDir . $thumbPrefix . $info['filename'] . $thumbSuffix . '.' . $info['extension'];
		
		$thumb_tag = html::tag('img', array_merge($thumbAttributes, array('src'=>$thumbHref)), false, true);
		$link_tag = html::tag('a', array_merge($linkAttributes, array('href'=>$imageHref, 'rel'=>$lightboxRel)), $thumb_tag);
		
		$html .= html::tag('div', $itemDivAttributes, $link_tag);
		$html .= "\r\n";
	}
	
	$html .= html::tag('div', array('style'=>'clear:both;'), '');
	$html .= html::closeTag('div');
	$html .= "\r\n";
	
	//echo '<hr><pre>' . htmlspecialchars($html) . '</pre><hr>';
	echo $html;
?>