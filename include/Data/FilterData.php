<?php
/**
 * ResponseData.
 *
 * @package NaGora\StepwiseFilter\Data
 */

namespace NaGora\StepwiseFilter\Data;

use NaGora\StepwiseFilter\Filters\TaxFilter;

/**
 * Class ResponseData.
 */
class FilterData implements ResponseData {

	/**
	 * FilterData constructor.
	 *
	 * @param \NaGora\StepwiseFilter\Filters\TaxFilter $filter Filter.
	 */
	public function __construct( private TaxFilter $filter ) {
	}

	/**
	 * Get response data as an array.
	 *
	 * @return array
	 */
	public function to_array(): array {
		$data = array(
			'slug'    => $this->filter->get_slug(),
			'name'    => $this->filter->get_name(),
			'id'      => $this->filter->get_id(),
			'options' => $this->filter->get_options(),
		);
		return $data;
	}
}
