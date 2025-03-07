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
		$args1     = array();
		$args2     = array(
			'relation' => 'AND',
			'field' => 'slug'
		);
		$query    = array(
			'post_type'      => 'product',
			'posts_per_page' => 1,
		);
		$tax_query1 = array(
			'taxonomy' => 'test',
			'terms'    => array( 1, 2, 3 ),
			'field'    => 'id',
			'relation' => 'IN',
		);
		$tax_query2 = array(
			'taxonomy' => 'test',
			'terms'    => array( 'test1', 'test2', 'test3' ),
			'field'    => 'slug',
			'relation' => 'AND',
		);

		$modifier = new TaxSearchModifier( $tax_query1['taxonomy'] );
		$modifier->set_search_terms( $tax_query1['terms'], $args1 );

		$result_query = $modifier->modify( $query );

		$this->assert_query_structure( $result_query );
		$this->assertCount( 2, $result_query['tax_query'] );


		$query['tax_query'] = array(
			'relation' => 'AND',
			$tax_query1
		);
		$this->assertEquals( $query, $result_query);
		$this->assertEquals( $query['tax_query'], $result_query['tax_query']);


		$modifier->set_search_terms( $tax_query2['terms'], $args2 );
		$result_query = $modifier->modify( $result_query);

		$this->assert_query_structure( $result_query );
		$this->assertCount( 3, $result_query['tax_query'] );


		$query['tax_query'] = array(
			'relation' => 'AND',
			$tax_query1,
			$tax_query2
		);
		$this->assertEquals( $query['tax_query'], $result_query['tax_query']);


	}

	private function assert_query_structure(array $query): void {
		$this->assertArrayHasKey('tax_query', $query);
		$this->assertIsArray($query['tax_query']);
		$this->assertArrayHasKey('relation', $query['tax_query']);
		$this->assertArrayHasKey('post_type', $query);
		$this->assertArrayHasKey('posts_per_page', $query);
	}

}
