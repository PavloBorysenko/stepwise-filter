<?php
/**
 * TestWCFilterQuery.
 *
 * @package NaGora\StepwiseFilter
 */

use NaGora\StepwiseFilter\Query\WCFilterQuery;

/**
 * Class TestWCFilterQuery.
 */
class TestWCFilterQuery extends WP_UnitTestCase {

	/**
	 * Category Terms.
	 *
	 * @var array
	 */
	public static $terms = array();

	/**
	 * Set up before class.
	 *
	 * @param WP_UnitTest_Factory $factory Factory.
	 */
	public static function wpSetUpBeforeClass( $factory ) {

		$args = array(
			array(
				'name' => 'Accessories',
			),
			array(
				'name' => 'Clothing',
			),
		);

		self::$terms[] = $factory->category->create_and_get( $args[0] );
		self::$terms[] = $factory->category->create_and_get( $args[1] );
	}

	/**
	 * Tear down after class.
	 */
	public static function wpTearDownAfterClass() {
		foreach ( self::$terms as $term ) {
			wp_delete_term( $term->term_id, $term->taxonomy );
		}
	}

	/**
	 * Test set current taxonomy method. Real taxonomy, fake taxonomy, reset taxonomy.
	 */
	public function test_set_current_taxonomy() {

		$taxonomy_slug = self::$terms[0]->taxonomy;
		$term_slug     = self::$terms[0]->slug;
		$fake_taxonomy = array( 'taxonomy_does_not_exist' => 'false' );
		$filter_query  = new WCFilterQuery();

		$reflection_filter_query   = new ReflectionClass( $filter_query );
		$current_taxonomy_property = $reflection_filter_query->getProperty( 'current_taxonomy' );
		$current_taxonomy_property->setAccessible( true );

		$filter_query->set_current_taxonomy( array( $taxonomy_slug => $term_slug ) );
		$this->assertTrue( $filter_query->is_current_taxonomy(), 'Correct current taxonomy is set' );
		$current_taxonomy = $current_taxonomy_property->getValue( $filter_query );
		$this->assertEquals( self::$terms[0]->term_id, $current_taxonomy['terms'] );
		$this->assertEquals( $taxonomy_slug, $current_taxonomy['taxonomy'] );

		$filter_query->set_current_taxonomy( array() );
		$this->assertFalse( $filter_query->is_current_taxonomy(), 'Reset current taxonomy' );

		$filter_query->set_current_taxonomy( $fake_taxonomy );
		$this->assertFalse( $filter_query->is_current_taxonomy(), 'Incorrect current taxonomy is set, assert false' );
	}


}
