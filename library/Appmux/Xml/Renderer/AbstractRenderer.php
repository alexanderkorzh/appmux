<?php

namespace Appmux\Xml\Renderer;

class AbstractRenderer
{

	protected $indent = 2;

	public function indent($string, $times = 0)
	{
		return str_repeat(' ', $this->indent * $times) . $string;
	}
	/**
	 * Converts an associative array to a string of tag attributes.
	 *
	 * @access public
	 *
	 * @param array $attribs From this array, each key-value pair is
	 * converted to an attribute name and value.
	 *
	 * @return string The XHTML for the attributes.
	 */
	protected function htmlAttribs($attribs)
	{
		$xhtml   = '';
//		$escaper = $this->view->plugin('escapehtml');
		$escaper = new \Zend\View\Helper\EscapeHtml();
		foreach ((array) $attribs as $key => $val) {
			$key = $escaper($key);

			if (('on' == substr($key, 0, 2)) || ('constraints' == $key)) {
				// Don't escape event attributes; _do_ substitute double quotes with singles
				if (!is_scalar($val)) {
					// non-scalar data should be cast to JSON first
					$val = \Zend\Json\Json::encode($val);
				}
				// Escape single quotes inside event attribute values.
				// This will create html, where the attribute value has
				// single quotes around it, and escaped single quotes or
				// non-escaped double quotes inside of it
				$val = str_replace('\'', '&#39;', $val);
			} else {
				if (is_array($val)) {
					$val = implode(' ', $val);
				}
				$val = $escaper($val);
			}

			if ('id' == $key) {
				$val = $this->normalizeId($val);
			}

			if (strpos($val, '"') !== false) {
				$xhtml .= " $key='$val'";
			} else {
				$xhtml .= " $key=\"$val\"";
			}

		}
		return $xhtml;
	}

	/**
	 * Normalize an ID
	 *
	 * @param  string $value
	 * @return string
	 */
	protected function normalizeId($value)
	{
		if (strstr($value, '[')) {
			if ('[]' == substr($value, -2)) {
				$value = substr($value, 0, strlen($value) - 2);
			}
			$value = trim($value, ']');
			$value = str_replace('][', '-', $value);
			$value = str_replace('[', '-', $value);
		}
		return $value;
	}
}
