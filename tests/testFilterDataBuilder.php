<?php
/**
 * TestFilterDataBuilder
 *
 * @package stepwise-filter
 */

use NaGora\StepwiseFilter\FilterDataBuilder;
use NaGora\StepwiseFilter\Data\ResponseData;

/**
 * TestFilterDataBuilder.
 */
class TestFilterDataBuilder extends WP_UnitTestCase {

	/**
	 * Checks that the get_response_data method returns a ResponseData object.
	 */
	public function test_get_respose_data() {
		$filter_data_builder = new FilterDataBuilder();
		$filter_data         = $filter_data_builder->get_filter_data( 'test', array() );

		$this->assertTrue( $filter_data instanceof ResponseData );
	}
}
