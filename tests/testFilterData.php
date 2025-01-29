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
	public function test_filter_data() {
		$tax_slug   = 'product_cat';
		$args       = array();
		$data       = new FilterData( new TaxFilter( $tax_slug, $args ) );
		$filte_data = $data->to_array();
		$this->assertIsArray( $filte_data );

		$this->assertArrayHasKey( 'slug', $filte_data );
		$this->assertEquals( $tax_slug, $filte_data['slug'] );
	}
}
