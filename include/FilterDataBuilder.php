<?php
/**
 * FilterDataBuilder.
 *
 * @package stepwise-filter
 */

namespace NaGora\StepwiseFilter;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use NaGora\StepwiseFilter\Data\ResponseData;
use NaGora\StepwiseFilter\Data\FilterData;

/**
 * FilterDataBuilder.
 */
class FilterDataBuilder {
	/**
	 * Retrieves a filter data object.
	 *
	 * @param string $slug Filter slug.
	 * @param array  $args Arguments.
	 *
	 * @return ResponseData
	 */
	public function get_filter_data( string $slug, array $args ): ResponseData {
		return new FilterData();
	}
}
