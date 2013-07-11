<?php

namespace Appmux\Xml;

class Tag
{
	protected $nodeName;
	protected $content;
	protected $attributes = array();

	public function __construct($name = null, $content = null, array $attributes = array())
	{
		$this->setName($name);
		$this->setContent($content);
		$this->setAttributes($attributes);
	}
	/**
	 * @param null  $name
	 * @param null  $content
	 * @param array $attributes
	 * @return Tag
	 */
	public static function create($name = null, $content = null, array $attributes = array())
	{
		$class = get_called_class();

		return new $class($name, $content, $attributes);
	}

	public function __set($property, $value)
	{
		if (property_exists($this, $property)) {
			$this->$property = $value;
		} else {
			$this->attr($property, $value);
		}

		return $this;
	}

	public function __get($property)
	{
		if (property_exists($this, $property)) {
			return $this->$property;
		} elseif (array_key_exists($property, $this->attributes)) {
			return $this->attributes[$property];
		} else {
			throw new \Appmux\Exception\Exception(sprintf('Property \'%s\' not found.', $property));
		}
	}

	public function getName()
	{
		return $this->nodeName;
	}

	public function setName($name)
	{
		// TODO Add validation and throw an error
		$this->nodeName = $name;

		return $this;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function setContent($content = null)
	{
		$this->content = $content;

		return $this;
	}

	public function getAttributes()
	{
		return $this->attributes;
	}

	public function setAttributes(array $attributes)
	{
		if (is_array($attributes)) {
			$this->attributes = $attributes;
		} else {
			$this->attributes = array();
		}

		return $this;
	}

	public static function createFromArray(array $data = array())
	{
		$name = array();
		$content = array();
		$attributes = array();

		if (array_key_exists('@attributes', $data)) {
			$attributes = $data['@attributes'];
		}

	}

	/**
	 * Shorthand function to remove an attribute
	 *
	 * @param $name
	 * @return Tag|null|string
	 */
	public function removeAttr($name)
	{
		return $this->attr($name, null);
	}

	/**
	 * @param      $name
	 * @param null $value
	 * @return Tag|string|null
	 */
	public function attr($name, $value = null)
	{
		if (func_num_args() == 1) {
			if (array_key_exists($name, $this->attributes)) {
				return $this->attributes[$name];
			} else {
				return null;
			}
		} else {
			if (is_null($value)) {
				unset($this->attributes[$name]);
			} else {
				$this->attributes[$name] = $value;
			}

			return $this;
		}
	}

	public function attrAppend($name, $value, $separator = ' ')
	{
		if (empty($value)) {
			return $this;
		}

		/** @var $v String */
		$v = $this->attr($name);

		if (empty($v)) {
			$v = '';
			$separator = '';
		}

		$this->attr($name, $v . $separator . $value);

		return $this;
	}

	public function __toString()
	{
		return $this->render();
	}

	/**
	 * @return string|mixed
	 */
	public function render()
	{
		$rend = __NAMESPACE__ . '\\Renderer\\' . 'String';
		$r = new $rend();
//		$r = new Renderer\String();
		return $r($this->nodeName, $this->content, $this->attributes);
	}

/*	public function toArray()
	{
		if (count($this->attributes) > 0) {
			$this->data['@attributes'] = $this->attributes;
		} else {
			unset($this->data['@attributes']);
		}

		return $this->data;
	}*/
}
