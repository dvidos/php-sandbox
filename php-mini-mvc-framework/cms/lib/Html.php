<?php

class Html
{
	static function encode($string)
	{
		return htmlspecialchars($string);
	}
	
	static function openTag($tag, $attributes = array(), $selfClose = false)
	{
		$html = '<' . $tag;
		
		foreach ($attributes as $name => $value)
			$html .= ' ' . htmlspecialchars($name) . '="' . htmlspecialchars($value) . '"';
		
		if ($selfClose)
			$html .= ' /';
		
		$html .= '>';
		
		return $html;
	}

	static function closeTag($tag)
	{
		return '</' . $tag . '>';
	}
	
	static function tag($tag, $attributes = array(), $content = false, $selfClose = false)
	{
		if ($content !== false)
		{
			return self::openTag($tag, $attributes) . $content . self::closeTag($tag);
		}
		else
		{
			// could be "<img src="x" />"
			if ($selfClose)
				return self::openTag($tag, $attributes, true);
			else
				return self::openTag($tag, $attributes) . self::closeTag($tag);
		}
	}
	

	static function link($text, $url = array(), $attributes = array())
	{
		$params = array_merge(array('href' => app()->create_url($url)), $attributes);
		return html::tag('a', $params, $text);
	}
	
	
	/*
	 * create pagination links.
	 * one div of class "paginator". 
	 * inside there are links or spans with classes: "first", "prev", "page", "next", "last". 
	 * current page also has class "current".
	 * TODO: move lang strings to lib/locale/xx_xx.php or something.
	 */
	static function pagination($url = array(), $curr_page, $pages)
	{
		$multilingual_words = array(
			'el_gr'=>array('Πρώτη', 'Προηγούμενη', 'Επόμενη', 'Τελευταία'),
			'en_us'=>array('First', 'Prev', 'Next', 'Last'),
			'en_gb'=>array('First', 'Prev', 'Next', 'Last'),
		);
		
		$words = $multilingual_words[app()->locale];
		if (empty($words))
			$words = $multilingual_words[array_shift(array_keys($multilingual_words))];
		
		
		$html = '';
		
		
		// first and previous page
		if ($curr_page == 1)
		{
			//$html .= self::tag('span', array('class'=>'first'), $words[0]);
			$html .= self::tag('span', array('class'=>'prev'), $words[1]);
		}
		else
		{
			//$html .= self::link($words[0], array_merge($url, array('page'=>1)), array('class'=>'first'));
			$html .= self::link($words[1], array_merge($url, array('page'=>$curr_page - 1)), array('class'=>'prev'));
		}
		
		// all pages
		for ($page = 1; $page <= $pages; $page++)
		{
			if ($page == $curr_page)
			{
				$html .= self::tag('span', array('class'=>'page current'), $page);
			}
			else
			{
				$html .= self::link($page, array_merge($url, array('page'=>$page)), array('class'=>'page'));
			}
		}
		
		// next and last page
		if ($curr_page >= $pages)
		{
			$html .= self::tag('span', array('class'=>'next'), $words[2]);
			//$html .= self::tag('span', array('class'=>'last'), $words[3]);
		}
		else
		{
			$html .= self::link($words[2], array_merge($url, array('page'=>$curr_page + 1)), array('class'=>'next'));
			//$html .= self::link($words[3], array_merge($url, array('page'=>$pages)), array('class'=>'last'));
		}
		
		
		// overall div
		$html = self::tag('div', array('class'=>'pagination'), $html);
		
		return $html;
	}
	
	/**
	 * Create a (possibly multilevel) <ul> menu
	 * Each item can have caption, url (array or string), items (children)
	 * If url matches current request, a 'selected' or 'default' class is appended to the item
	 */
	static function menu($items, $menuOptions = array())
	{
		$activeClass = 'active';
		$requestedUrl = $_SERVER['REQUEST_URI'];
		$html = '';
		
		foreach ($items as $item)
		{
			// see if we want to hide or skip this.
			if (array_key_exists('visible', $item))
			{
				$visible = $item['visible'];
				try
				{
					if (!eval('return (' . $visible . ');'))
						continue;
				}
				catch (Exception $e)
				{
					app()->log_error('Error in eval() of menu visibility condition \"' . $visible . '"');
				}
			}
			
			// so we shall show it.
			$caption = array_key_exists('caption', $item) ? $item['caption'] : '';
			
			// if no url given, we are rendered as span
			$url = array_key_exists('url', $item) ? $item['url'] : null;
			$active = false;
			
			if ($url != null)
			{
				// see if we are active: if the item url is the same with current url
				$finalUrl = app()->create_rul($url);
				log_debug('menu(): comparing item url \"' . $finalUrl . '\" to requested url \"' . $requestedUrl . '\"');
				$active = ($requestedUrl == $finalUrl);
			}
			
			// options for the <li> item
			$itemOptions = array_key_exists('itemOptions', $item) ? $item['itemOptions'] : array();
			
			// options for the <a> link
			$linkOptions = array_key_exists('linkOptions', $item) ? $item['linkOptions'] : array();
			
			// submenu options for the nested <ul> tag
			$submenuOptions = array_key_exists('submenuOptions', $item) ? $item['submenuOptions'] : array();
			
			
			// set or merge the active class on this item (the item, not the link)
			if ($active)
			{
				if (array_key_exists('class', $itemOptions))
					$itemOptions['class'] .= ' ' . $activeClass;
				else
					$itemOptions['class'] = $activeClass;
			}
			
			
			// prepare the innerHtml of this item.
			$innerHtml = '';
			if ($url == null)
				$innerHtml .= self::tag('span', $caption, $linkOptions);
			else
				$innerHtml .= self::link($caption, $url, $linkOptions);
			
			if (array_key_exists('items', $item) && is_array($item['items']) && count($item['items']) > 0)
			{
				$innerHtml .= self::menu($item['items'], $submenuOptions);
			}
			
			$html .= CHtml::tag('li', $itemOptions, $innerHtml);
		}
		
		return self::tag('ul', $menuOptions, $html);
	}

	
	public static function tableRow()
	{
		return self::specificTableRow('td', func_get_args());
	}
	
	public static function tableHeadingRow()
	{
		return self::specificTableRow('th', func_get_args());
	}
	
	protected static function specificTableRow($cellTag, $cellContents)
	{
		$html = '';
		foreach ($cellContents as $cell)
		{
			if ($cell === '')
				$cell = '&nbsp;';
			else
				$cell = self::encode($cell);
			
			$html .= self::tag($cellTag, array(), $cell);
		}
		
		return self::tag('tr', array(), $html);
	}
}