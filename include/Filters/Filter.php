<?php
/**
 * Filter.
 *
 * @package NaGora\StepwiseFilter\Filters
 */

namespace NaGora\StepwiseFilter\Filters;

/**
 * Filter.
 */
interface Filter {

	/**
	 * Get slug.
	 *
	 * @return string
	 */
	public function get_slug(): string;

	/**
	 * Get name.
	 *
	 * @return string
	 */
	public function get_name(): string;


	/**
	 * Get options.
	 *
	 * @return array
	 */
	public function get_options(): array;
}
