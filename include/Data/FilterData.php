<?php
/**
 * ResponseData.
 *
 * @package NaGora\StepwiseFilter\Data
 */

namespace NaGora\StepwiseFilter\Data;

use NaGora\StepwiseFilter\Filters\Filter;

/**
 * Class ResponseData.
 */
class FilterData implements ResponseData {

	/**
	 * FilterData constructor.
	 *
	 * @param \NaGora\StepwiseFilter\Filters\Filter $filter Filter.
	 */
	public function __construct( private Filter $filter ) {
	}

	/**
	 * Get response data as an array.
	 *
	 * @return array
	 */
	public function to_array(): array {
		return array(
			'slug'    => $this->filter->get_slug(),
			'name'    => $this->filter->get_name(),
			'options' => $this->filter->get_options(),
		);
	}
}
