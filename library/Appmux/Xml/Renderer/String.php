<?php

namespace Appmux\Xml\Renderer;

use Appmux\Xml;

class String extends AbstractRenderer
{
	/**
	 * EOL character
	 */
	const EOL = PHP_EOL;

	// If content has newline put content on the new line
	// If content is short, less than XX chars put in the same line

	public function __invoke($tag, $content = '', $attributes = null, $indent = 0, $indentCloser = null)
	{
		$output = '';
		$indentCloser = !isset($indentCloser) ? $indent : $indentCloser;

		if ($content instanceof Xml\Tag) {
			$content = array($content);
		}

		// TODO Should use interface here that would require toString, toArray, toXml functions.
		if ($tag instanceof Xml\Tag) {
			return $tag->render();
		} else if (is_array($tag)) {
			foreach ($tag as $item) {
				$output .= $this->indent($item, 1) . PHP_EOL;
			}
		} else if (is_string($tag)) {
			$output .= $this->indent('<' . $tag, $indent);

			if (is_array($attributes)) {
				$output .= $this->htmlAttribs($attributes);
			}

			if ($content === null) {
				return $output . '/>';
			} elseif (is_array($content)) {
				$contentOutput = '';

				foreach ($content as $item) {
					if (is_string($item)) {
						$contentOutput .= $this->indent($item, 1) . PHP_EOL;
					} else {
						$contentOutput .= $this->indent($this($item), 1) . PHP_EOL;
					}
				}

				if (!empty($contentOutput)) {
					$content = PHP_EOL . $contentOutput;
				}
			}

			$output .= '>' . $content . $this->indent('</' . $tag . '>', $indentCloser);
		}

		return $output;
	}

}
