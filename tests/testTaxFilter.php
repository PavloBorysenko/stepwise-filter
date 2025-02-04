<?php
/**
 * TestTaxFilter.
 *
 * @package NaGora\StepwiseFilter
 */

use NaGora\StepwiseFilter\Filters\TaxFilter;

/**
 * Class TestTaxFilter.
 */
class TestTaxFilter extends WP_UnitTestCase {

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

		$child_arg = array(
			'parent' => self::$terms[0]->term_id,
			'name'   => 'Caps',
		);

		self::$terms[] = $factory->category->create_and_get( $child_arg );
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
	 * Test data with standard taxonomy.
	 */
	public function test_data_with_standard_taxonomy() {
		$args = array();

		$tax_filter = new TaxFilter( self::$terms[0]->taxonomy, $args );

		$taxonomy      = get_taxonomy( self::$terms[0]->taxonomy );
		$taxonomy_name = ( $taxonomy ) ? $taxonomy->labels->singular_name : '';
		$options       = $tax_filter->get_options();

		$this->assertEquals( $taxonomy_name, $tax_filter->get_name() );
		$this->assertEquals( self::$terms[0]->taxonomy, $tax_filter->get_slug() );
		$this->assertIsArray( $options );

		foreach ( self::$terms as $term ) {
			$this->assertTrue( isset( $options[ $term->slug ] ) );
			$this->assertIsArray( $options[ $term->slug ] );

			$this->assertArrayHasKey( 'id', $options[ $term->slug ] );
			$this->assertEquals( $term->term_id, $options[ $term->slug ]['id'] );

			$this->assertArrayHasKey( 'name', $options[ $term->slug ] );
			$this->assertEquals( $term->name, $options[ $term->slug ]['name'] );

			$this->assertArrayHasKey( 'slug', $options[ $term->slug ] );
			$this->assertEquals( $term->slug, $options[ $term->slug ]['slug'] );

			// It doesn't matter what is being transmitted in this test.
			$this->assertArrayHasKey( 'count', $options[ $term->slug ] );
			$this->assertArrayHasKey( 'parent', $options[ $term->slug ] );
			$this->assertArrayHasKey( 'content', $options[ $term->slug ] );

		}
	}

	/**
	 * Test if taxonomy does not exist.
	 */
	public function test_if_taxonomy_does_not_exist() {
		$slug       = 'non-existent-taxonomy';
		$tax_filter = new TaxFilter( $slug, array() );
		$this->assertEmpty( $tax_filter->get_options(), 'The options array should be empty.' );
		$this->assertEquals( $slug, $tax_filter->get_slug() );
		$this->assertEquals( $slug, $tax_filter->get_name() );
	}

	/**
	 * Test get options by Term ids.
	 */
	public function test_get_options_by_ids() {
		$arguments  = array(
			'include' => array( self::$terms[0]->term_id, self::$terms[1]->term_id ),
		);
		$tax_filter = new TaxFilter( self::$terms[0]->taxonomy, $arguments );

		$options = $tax_filter->get_options();

		$this->assertIsArray( $options );
		$this->assertCount( 2, $options );

		$this->assertArrayHasKey( self::$terms[0]->slug, $options );
		$this->assertArrayHasKey( self::$terms[1]->slug, $options );
	}

	/**
	 * Test get options if id does not exist.
	 */
	public function test_get_options_if_id_does_not_exist() {
		$arguments  = array(
			'include' => array( 9999 ),
		);
		$tax_filter = new TaxFilter( self::$terms[0]->taxonomy, $arguments );

		$options = $tax_filter->get_options();

		$this->assertIsArray( $options );
		$this->assertEmpty( $options );
	}

	/**
	 * Test get option only parent by id.
	 */
	public function test_get_option_only_parent_by_id() {

		foreach ( self::$terms as $term ) {
			$children   = get_term_children( $term->term_id, $term->taxonomy );
			$arguments  = array(
				'child_of' => $term->term_id,
			);
			$tax_filter = new TaxFilter( $term->taxonomy, $arguments );

			$options = $tax_filter->get_options();

			$this->assertIsArray( $options );
			$this->assertEquals( count( $children ), count( $options ) );

		}
	}

	public function test_get_option_only_count_not_zero() {
		$arguments  = array(
			'hide_empty' => true,
		);
		$tax_filter = new TaxFilter( self::$terms[0]->taxonomy, $arguments );

		$options = $tax_filter->get_options();

		$this->assertIsArray( $options );
		$this->assertTrue(count($options) == 1);
	}
}
