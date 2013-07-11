<?php

namespace Appmux\Xhtml;

class InputText extends  AbstractTag
{
	const TAG = 'input';

	protected $attributes = array(
		'type' => 'text',
		'value' => '',
	);

	public function __construct($name = null, $id = null, array $attributes = array())
	{
		$attributes = array_merge($this->attributes, $attributes);

		parent::__construct(null, $attributes);

		$this->name($name);
		$this->id($id);
	}

	/**
	 * @param null  $name
	 * @param null  $id
	 * @param array $attributes
	 *
	 * @return \Appmux\Xhtml\InputText
	 */
	public static function create($name = null, $id = null, array $attributes = array())
	{
		$class = get_called_class();

		return new $class($name, $id, $attributes);
	}
}
