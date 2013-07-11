<?php

namespace Appmux\Xhtml;

class Button extends \Appmux\Xhtml\AbstractTag
{
	const TAG = 'button';

	protected $attributes = array(
		'type' => 'button',
	);

	public function __construct($content = null, $id = null, array $attributes = array())
	{
		$attributes = array_merge($this->attributes, $attributes);

		parent::__construct($content, $attributes);

		$this->attr('id', $id);
	}

	/**
	 * @param null  $content
	 * @param null  $name
	 * @param null  $id
	 * @param array $attributes
	 *
	 * @return \Appmux\Xhtml\Button
	 */
	public static function create($content = null, $id = null, array $attributes = array())
	{
		$class = get_called_class();

		return new $class($content, $id, $attributes);
	}
}
