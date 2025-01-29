<?php
/**
 * ResponseData.
 *
 * @package NaGora\StepwiseFilter\Data
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
