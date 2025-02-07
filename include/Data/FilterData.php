<?php
/**
 * ResponseData.
 *
 * @package NaGora\StepwiseFilter\Data
 */

namespace NaGora\StepwiseFilter\Data;

use NaGora\StepwiseFilter\FilterItems\Item;

/**
 * Class ResponseData.
 */
class FilterData implements ResponseData {

	/**
	 * FilterData constructor.
	 *
	 * @param \NaGora\StepwiseFilter\FilterItems\Item $filter_item Item.
	 */
	public function __construct( private Item $filter_item ) {
	}

	/**
	 * Get response data as an array.
	 *
	 * @return array
	 */
	public function to_array(): array {
		return array(
			'slug'    => $this->filter_item->get_slug(),
			'name'    => $this->filter_item->get_name(),
			'options' => $this->filter_item->get_options(),
		);
	}
}
