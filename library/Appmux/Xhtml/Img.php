<?php

namespace Appmux\Xhtml;

/**
 * Tag <img />
 *
 * @package Appmux\Xhtml
 *
 * @method static \Appmux\Xhtml\Img src($path) Tag attribute
 * @method static \Appmux\Xhtml\Img alt($text) Tag attribute
 * @method static \Appmux\Xhtml\Img title($text) Tag attribute
 */
class Img extends  AbstractTag
{
	const TAG = 'img';

	public function __construct($src = null, $alt = null, $title = null, array $attributes = array())
	{
		parent::__construct(null, $attributes);

		$alt = $alt !== null ? $alt : '';
		$title = $title !== null ? $title : $alt;

		$this->attr('src', $src);
		$this->attr('alt', $alt);
		$this->attr('title', $title);
	}

	/**
	 * @param null  $src
	 * @param null  $alt
	 * @param null  $title
	 * @param array $attributes
	 *
	 * @return \Appmux\Xhtml\Img
	 */
	public static function create($src = null, $alt = null, $title = null, array $attributes = array())
	{
		$class = get_called_class();

		return new $class($src, $alt, $title, $attributes);
	}
}
