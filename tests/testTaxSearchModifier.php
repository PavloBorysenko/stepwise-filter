<?php
/**
 * TestTaxSearchModifier.
 *
 * @package NaGora\StepwiseFilter\Query
 */

use NaGora\StepwiseFilter\Query\Modifier\TaxSearchModifier;

/**
 * Class TestTaxSearchModifier.
 */
class TestTaxSearchModifier extends WP_UnitTestCase {

	/**
	 * Test modify.
	 */
	public function test_modify() {

		$slug     = 'test';
		$args     = array();
		$terms    = array( 1, 2, 3 );
		$query    = array(
			'post_type'      => 'product',
			'posts_per_page' => 1,
		);
		$modifier = new TaxSearchModifier( $slug, $args );
		$modifier->set_search_terms( $terms );

		$result_query = $modifier->modify( $query );

		$this->assertArrayHasKey( 'tax_query', $result_query );
		$this->assertIsArray( $result_query['tax_query'] );
		$this->assertCount( 1, $result_query['tax_query'] );
		$this->assertArrayHasKey( 'taxonomy', $result_query['tax_query'][0] );
		$this->assertArrayHasKey( 'relation', $result_query['tax_query'][0] );
		$this->assertArrayHasKey( 'terms', $result_query['tax_query'][0] );
		$this->assertEquals( $terms, $result_query['tax_query'][0]['terms'] );
		$this->assertEquals( $slug, $result_query['tax_query'][0]['taxonomy'] );
		$this->assertArrayHasKey( 'posts_per_page', $result_query );
		$this->assertEquals( $query['posts_per_page'], $result_query['posts_per_page'] );
		$this->assertArrayHasKey( 'post_type', $result_query );
		$this->assertEquals( $query['post_type'], $result_query['post_type'] );
	}
}
