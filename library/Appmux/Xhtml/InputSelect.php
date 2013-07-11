<?php

namespace Appmux\Xhtml;

use Appmux\Exception\Exception;

/**
 * Tag <select></select>
 *
 * @package Appmux\Xhtml
 */
class InputSelect extends  AbstractTag
{
	const TAG = 'select';

	protected $topOptions = array();
	protected $options = array();

	protected $attributes = array(
		'value' => '',
	);

	public function __construct($name = null, $id = null, array $attributes = array())
	{
		$attributes = array_merge($this->attributes, $attributes);

		parent::__construct(null, $attributes);

		$this->name($name);
		$this->id($id);
	}

	public static function create($name = null, $id = null, array $attributes = array())
	{
		$class = get_called_class();

		return new $class($name, $id, $attributes);
	}

	/**
	 * array(										// Defines field as filterable.
	 *												// Retrieves DISTINCT values for the database fields.
	 * 												// Uses 'options' array for custom filters.
	 *     'title' => getShowAllCategoriesText(),	// Sets top option in the drop down box.
	 *
	 *     'source' => 'db',						// 'db' | 'sql' | 'values' - defines a source for the drop-down list.
	 * 												// 'db' - retrieves values from the database.
	 * 												// 'sql' - retrieves values from the database using provided sql statement.
	 * 												// 'values' - uses 'values' array provided.
	 *
	 *     'count' => false,						// false (default) | true - Used with 'source' = 'db'.
	 * 												// false - simply retrieves distinct values of a field and a 'count' as null.
	 * 												// true - retrieves distinct values of a field and a 'count' as a number.
	 *
	 * Array of options
	 *
	 * <option value="option_value">Option Content</option>
	 *
	 * Content defaults to an empty string, value defaults to content.
	 *
	 *     'options' => array(
	 *        'Option Value and Title',				// A string
	 *         array(								// An associative array
	 *            'content' => 'Option Content'
	 *            'value' => 'option_value',
	 *         ),
	 *         array('content' => 'Option Content'),
	 *         array('value' => 'option_value'),
	 *     ),
	 *
	 *    'map' => array(							// Sets a translation map for option values
	 *         '0' => 'Deactivated',
	 *         'yes' => 'Active',
	 *         'no' => 'Inactive',
	 *         'E' => 'functionName',				// Custom function callback.
	 *         'D' => array('ClassName', 'method'),	// Custom static class method callback.
	 *     ),
	 * ),
	 *
	 * @param $config
	 * @return InputSelect $this
	 * @throws \Freedom\Exception\Exception
	 */
	public static function fromArray($config = array())
	{
		if (!array_key_exists('name', $config)) {
			throw new Exception('Parameter "name" has to be provided for this element');
		}

		$options = (array) $config['options'];
		$topOption = array_key_exists('topOption', $config) ? $config['topOption'] : null;
		$topOptions = array_key_exists('topOptions', $config) ? $config['topOptions'] : array();

		unset(
			$config['options'],
			$config['topOption'],
			$config['topOptions']
		);

		if ($topOption !== null) {
			array_unshift($topOptions, $topOption);
		}

		/** @var $select InputSelect */
		$select = parent::fromArray($config);
		$select->addOptions($topOptions, true);
		$select->addOptions($options);

		return $select;
	}

	public function getOptions()
	{
		return $this->options;
	}

	public function setOptions(array $options = array())
	{
		$this->options = array();
		$this->addOptions($options);

		return $this;
	}

	public function addOption($option, $value = null, $top = false)
	{
		if (!is_array($option)) {
			$option = array($option, $value);
		}

		if ($top) {
			$this->topOptions[] = $this->filterOption($option);
		} else {
			$this->options[] = $this->filterOption($option);
		}

		return $this;
	}

	public function addOptions(array $options, $top = false) {
		foreach ($options as $option) {
			$this->addOption($option, null, $top);
		}

		return $this;
	}

	public function filterOption($option)
	{
		if (is_string($option)) {
			$option = array($option);
		}

		if (is_array($option)) {
			$content = array_key_exists('content', $option) ? $option['content'] : null;
			$value = array_key_exists('value', $option) ? $option['value'] : null;

			if ($content === null && $value === null) {
				$content = array_shift($option);
				$value = array_shift($option);
			}
		} else {
			throw new Exception('Options must be a string or an array.');
		}

		$content = $content !== null ? $content : '';
		$value = $value !== null ? $value : $content;

		return array('content' => $content, 'value' => $value);
	}

	public function render()
	{
		$options = array_merge($this->topOptions, $this->options);

		foreach ($options as $option) {
			$this->content[] = Tag::option($option['content'], $option['value']);
		}

		return parent::render();
	}
}
