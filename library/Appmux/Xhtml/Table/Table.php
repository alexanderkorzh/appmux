<?php

namespace Appmux\Xhtml\Table;

use Appmux\Xhtml\Tag;

/**
 * Tag <table>
 *
 * @method static \Appmux\Xhtml\Table\Table create($content = null, array $attributes = array()) Tag <table>
 *
 * @package Appmux\Xhtml\Table
 */
class Table extends \Appmux\Xhtml\AbstractTag
{
	const TAG = 'table';

	protected $columns = array();

	public function getColumns()
	{
		return $this->columns;
	}

	public static function tr($content = null, array $attributes = array())
	{
		return Tag::create('tr', $content, $attributes);
	}

	public static function td($content = null, array $attributes = array())
	{
		return Tag::create('td', $content, $attributes);
	}
}
