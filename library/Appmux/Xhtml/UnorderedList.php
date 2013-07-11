<?php

namespace Appmux\Xhtml;

use Appmux\Exception\Exception;

/**
 * Tag <ul></ul>
 *
 * @package Appmux\Xhtml
 */
class UnorderedList extends  AbstractTag
{
	const TAG = 'ul';

	protected $items = array();

	public function __construct($name = null, $id = null, array $attributes = array())
	{
		parent::__construct(null, $attributes);

		$this->name($name);
		$this->id($id);
	}

	public static function create($name = null, $id = null, array $attributes = array())
	{
		$class = get_called_class();

		return new $class($name, $id, $attributes);
	}

	public function getItems()
	{
		return $this->items;
	}

	public function setItems(array $items = array())
	{
		$this->items = array();
		$this->addItems($items);

		return $this;
	}

	public function addItem($item)
	{
		$this->items[] = $item;

		return $this;
	}

	public function addItems(array $items, $top = false) {
		foreach ($items as $option) {
			$this->addItem($option, null, $top);
		}

		return $this;
	}

	public function render()
	{
		foreach ($this->items as $item) {
			if (!$item instanceof Tag) {
				$item = Tag::li($item);
			}

			$this->content[] = $item;
		}

		return parent::render();
	}
}
