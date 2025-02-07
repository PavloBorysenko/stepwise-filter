<?php
/**
 * Item.
 *
 * @package NaGora\StepwiseFilter\FilterItems
 */

namespace NaGora\StepwiseFilter\FilterItems;

/**
 * Item.
 */
interface Item {

	/**
	 * Get slug. This is the main key to distinguish between filters.
	 *
	 * @return string
	 */
	public function get_slug(): string;

	/**
	 * Get name. A title for the filter.
	 *
	 * @return string
	 */
	public function get_name(): string;


	/**
	 * Get options. Filter elements by which the search is performed.
	 *
	 * @return array
	 */
	public function get_options(): array;
}
