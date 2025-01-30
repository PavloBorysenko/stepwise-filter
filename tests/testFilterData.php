<?php
/**
 * TestFilterData.
 *
 * @package NaGora\StepwiseFilter
 */

use NaGora\StepwiseFilter\Data\FilterData;
use NaGora\StepwiseFilter\Filters\TaxFilter;
/**
 * TestFilterData.
 */
class TestFilterData extends WP_UnitTestCase {

	/**
	 * Test filter data.
	 */
	public function test_return_valid_array() {
		$tax_slug   = 'product_cat';
		$args       = array();
		$data       = new FilterData( new TaxFilter( $tax_slug, $args ) );
		$filte_data = $data->to_array();
		$this->assertIsArray( $filte_data );

		$this->assertArrayHasKey( 'slug', $filte_data );

		$this->assertArrayHasKey( 'name', $filte_data );

		$this->assertArrayHasKey( 'options', $filte_data );
		$this->assertIsArray( $filte_data['options'] );
	}
}
