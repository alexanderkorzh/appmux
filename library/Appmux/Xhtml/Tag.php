<?php

namespace Appmux\Xhtml;

/**
 * Class Tag
 *
 * @package Appmux\Xhtml
 *
 * @method static \Appmux\Xhtml\Tag br($content = null, array $attributes = array()) Tag <br />
 * @method static \Appmux\Xhtml\Tag b($content = null, array $attributes = array()) Tag <b>
 * @method static \Appmux\Xhtml\Tag span($content = null, array $attributes = array()) Tag <span>
 * @method static \Appmux\Xhtml\Tag div($content = null, array $attributes = array()) Tag <div>
 * @method static \Appmux\Xhtml\Tag label($content = null, array $attributes = array()) Tag <label>
 * @method static \Appmux\Xhtml\Tag button($content, $attributes) Tag <button>
 * @method static \Appmux\Xhtml\Tag select($content, $attributes) Tag <select>
 * @method static \Appmux\Xhtml\Tag li($content = null, array $attributes = array()) Tag <li>
 *
 * @method static \Appmux\Xhtml\Tag h1($content, array $attributes = array()) Tag <h1>
 * @method static \Appmux\Xhtml\Tag h2($content, array $attributes = array()) Tag <h2>
 * @method static \Appmux\Xhtml\Tag h3($content, array $attributes = array()) Tag <h3>
 * @method static \Appmux\Xhtml\Tag h4($content, array $attributes = array()) Tag <h4>
 * @method static \Appmux\Xhtml\Tag h5($content, array $attributes = array()) Tag <h5>
 */

class Tag extends \Appmux\Xml\Tag
{
	protected $classes = array();

	/**
	 * Shortcut function to any simple tag.
	 *
	 * @param       $name
	 * @param array $arguments
	 *
	 * @return \Appmux\Xhtml\Tag
	 */
	public static function __callStatic($name, $arguments = array())
	{
		switch (strtolower($name)) {
			case 'select':
				array_unshift($arguments, $name);
				return forward_static_call_array(array(__CLASS__, 'create'), $arguments);
				break;
			default:
				array_unshift($arguments, $name);
				return forward_static_call_array(array(__CLASS__, 'create'), $arguments);
		}
	}

	public static function link($file, array $attributes = array())
	{
		$default = array(
			'type' => 'text/css',
			'rel' => 'stylesheet',
			'href' => $file,
		);

		$attributes = array_merge($default, $attributes);
		$arguments = array('link', null, $attributes);

		return forward_static_call_array(array(__CLASS__, 'create'), $arguments);
	}

	public static function a($content, $href = '', array $attributes = array())
	{
		// TODO Throw argument error if not a string
		// Thought, href actually can be empty if tage used as an anchor
		if (!empty($href)) {
			$attributes['href'] = $href;
		}

		$arguments = array('a', $content, $attributes);

		return forward_static_call_array(array(__CLASS__, 'create'), $arguments);
	}

	public static function inputCheckbox($id, $name, $value = '1', array $attributes = array())
	{
		$attributes['type'] = 'checkbox';
		$attributes['id'] = (string) $id;
		$attributes['name'] = (string) $name;
		$attributes['value'] = $value;

		$arguments = array('input', null, $attributes);

		return forward_static_call_array(array(__CLASS__, 'create'), $arguments);
	}

	public static function inputText($id, $name, $value = '', array $attributes = array())
	{
		$attributes['type'] = 'text';
		$attributes['id'] = (string) $id;
		$attributes['name'] = (string) $name;
		$attributes['value'] = $value;

		$arguments = array('input', null, $attributes);

		return forward_static_call_array(array(__CLASS__, 'create'), $arguments);
	}

	/**
	 * @param       $content
	 * @param null  $value
	 * @param bool  $selected
	 * @param array $attributes
	 * @return Tag
	 */
	public static function option($content, $value = null, $selected = false, array $attributes = array())
	{
		if ($value !== null) {
			$attributes['value'] = $value;
		} elseif (!array_key_exists('value', $attributes)) {
			$attributes['value'] = $content;
		}

		if ($selected === true) {
			$attributes['selected'] = 'selected';
		}

		$arguments = array('option', $content, $attributes);

		return forward_static_call_array(array(__CLASS__, 'create'), $arguments);
	}

	function hasClass($class)
	{
		return in_array($class, $this->classes);
	}

	/**
	 * Adds the specified class(es)
	 *
	 * @param $class
	 *
	 * @return \Appmux\Xhtml\Tag
	 */
	public function addClass($classes)
	{
		if (is_string($classes)) {
			$classes = explode(' ', $classes);
		}

		foreach ((array) $classes as $class) {
			if (!empty($class) && !$this->hasClass($class)) {
				$this->classes[] = $class;
			}
		}

		return $this;
	}

	public function removeClass($class)
	{
		$i = array_search($class, $this->classes);

		if ($i !== false) {
			array_splice($this->classes, $i, 1);
		}
	}

	public function onclick($value = '')
	{
		return $this->attr('onclick', $value);
	}

	// Wraps this tag into another tag
	// Accepts string name for simple tag or Xml\Tag object
	public function wrap(Tag $tag)
	{
		$tag->setContent($this);

		return $tag;
	}

	public function wrapInner(Tag $tag)
	{
		$this->content = $tag->setContent($this->content);

		return $this;
	}

	public function prepend($content)
	{
		if (!is_array($this->content)) {
			if ($this->content === null) {
				$this->content = array();
			} else {
				$this->content = array($this->content);
			}
		}

		array_unshift($this->content, $content);

		return $this;
	}

	public function append($content)
	{
		if (!is_array($this->content)) {
			if ($this->content === null) {
				$this->content = array();
			} else {
				$this->content = array($this->content);
			}
		}

		array_push($this->content, $content);

		return $this;
	}

	public function render()
	{
		if (count($this->classes) > 0) {
			$this->attr('class', implode(' ', $this->classes));
		}

		return parent::render();
	}
}
