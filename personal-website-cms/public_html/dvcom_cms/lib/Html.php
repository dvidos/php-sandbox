<?php

class Html
{
	static $elements_id_counter = 0;
	
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
	

	static function link($text, $url = [], $attributes = [])
	{
		if (is_string($url))
		{
			$route = $url;
			$args = [];
		}
		else
		{
			$route = array_shift($url);
			$args = $url;
		}
		
		$params = array_merge(array('href' => cms()->create_url($route, $args)), $attributes);
		return Html::tag('a', $params, $text);
	}
	
	
	/*
	 * create pagination links.
	 * one div of class "paginator". 
	 * inside there are links or spans with classes: "first", "prev", "page", "next", "last". 
	 * current page also has class "current".
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
		if ($curr_page == $pages)
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
	

	static function label($caption, $target_name)
	{
		$html = self::tag('label', ['for' => $target_name], $caption);
		return $html;
	}
	
	static function textField($name, $value = '', $attributes = [])
	{
		$attributes['type'] = 'text';
		$attributes['name'] = $name;
		$attributes['id'] = $name;
		$attributes['value'] = $value;
		
		$html = self::tag('input', $attributes, false, true);
		return $html;
	}
	
	static function passwordField($name, $value = '', $attributes = [])
	{
		$attributes['type'] = 'password';
		$attributes['name'] = $name;
		$attributes['id'] = $name;
		$attributes['value'] = $value;
		
		$html = self::tag('input', $attributes, false, true);
		return $html;
	}
	
	static function textArea($name, $value = '', $attributes = [])
	{
		$attributes['id'] = $name;
		$attributes['name'] = $name;
		
		$html = self::openTag('textarea', $attributes) . htmlentities($value) . self::closeTag('textarea');
		return $html;
	}
	
	static function checkBox($name, $value, $attributes = [])
	{
		$attributes['type'] = 'checkbox';
		$attributes['name'] = $name;
		$attributes['id'] = $name;
		$attributes['value'] = '1'; // to have the checkbox return this if checked

		if ($value)
			$attributes['checked'] = 'checked';
		else
			unset($attributes['checked']);
		
		$html = self::tag('input', $attributes);
		return $html;
	}
	
	static function submit($caption, $attributes = [])
	{
		$attributes['type'] = 'submit';
		$attributes['id'] = 'el' . self::$elements_id_counter;
		$attributes['value'] = $caption;
		
		$html = self::tag('input', $attributes, false, true);
		return $html;
	}
}