<?php

namespace Appmux\Xhtml;

/**
 * Abstract tag
 *
 * @package Appmux\Xhtml
 */
class AbstractTag extends \Appmux\Xhtml\Tag
{
	const TAG = 'tag';

	public function __construct($content = null, array $attributes = array())
	{
		parent::__construct($this::TAG, $content, $attributes);
	}

	/**
	 * @param null  $content
	 * @param array $attributes
	 *
	 * @return \Appmux\Xhtml\AbstractTag
	 */
	public static function create($content = null, array $attributes = array())
	{
		$class = get_called_class();

		return new $class($content, $attributes);
	}

	public static function fromArray($config = array())
	{
		$class = get_called_class();
		$tag = new $class();

		foreach ($config as $property => $value) {
			$tag->$property = $value;
		}

		return $tag;
	}

	public function toArray()
	{
		$config = array();

		foreach (get_object_vars($this) as $property => $value) {
			$config[$property] = $value;
		}

		return $config;
	}

	public function id($value = '')
	{
		return $this->attr('id', $value);
	}

	public function name($value = '')
	{
		return $this->attr('name', $value);
	}

	public function value($value = '')
	{
		return $this->attr('value', $value);
	}
}
