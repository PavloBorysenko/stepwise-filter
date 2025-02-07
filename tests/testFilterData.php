<?php
/**
 * TestFilterData.
 *
 * @package NaGora\StepwiseFilter
 */

use NaGora\StepwiseFilter\Data\FilterData;
use NaGora\StepwiseFilter\FilterItems\TaxFilter;
/**
 * TestFilterData.
 */
class TestFilterData extends WP_UnitTestCase {

	/**
	 * Test filter data.
	 */
	public function test_return_valid_array() {
		$term       = $this->factory->category->create_and_get( array( 'name' => 'Accessories' ) );
		$tax_slug   = $term->taxonomy;
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
