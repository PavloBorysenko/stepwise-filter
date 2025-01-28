<?php
/**
 * ResponseData.
 *
 * @package stepwise-filter
 */

namespace NaGora\StepwiseFilter\Data;

interface ResponseData {
	/**
	 * Get response data as an array.
	 *
	 * @return array
	 */
	public function to_array(): array;
}
