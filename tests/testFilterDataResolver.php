<?php
/**
 * TestFilterDataResolver.
 *
 * @package NaGora\StepwiseFilter
 */

use NaGora\StepwiseFilter\SlugResolver\FilterDataResolver;
use NaGora\StepwiseFilter\FilterItems\Submit;
use NaGora\StepwiseFilter\FilterItems\Error;
use NaGora\StepwiseFilter\FilterItems\TaxFilter;

/**
 * TestFilterDataResolver.
 */
class TestFilterDataResolver extends WP_UnitTestCase {

	/**
	 * Entety resolver object.
	 *
	 * @var FilterDataResolver
	 */
	public static $entety_resolver;

	/**
	 * Set up before class.
	 *
	 * @param WP_UnitTest_Factory $factory Factory.
	 */
	public static function wpSetUpBeforeClass( $factory ) {
		self::$entety_resolver = new FilterDataResolver();
	}

	/**
	 * Test get object special.
	 */
	public function test_get_object_special() {
		$slug = 'Submit';
		$args = array();
		$item = self::$entety_resolver->get_object( $slug, $args );
		$this->assertInstanceOf( Submit::class, $item );
	}

	/**
	 * Test get object does not exist.
	 */
	public function test_get_object_does_not_exist() {
		$slug = 'not_existing';
		$args = array();
		$item = self::$entety_resolver->get_object( $slug, $args );
		$this->assertInstanceOf( Error::class, $item );
	}

	/**
	 * Test get object taxonomy.
	 */
	public function test_get_object_taxonomy() {
		$term = $this->factory->category->create_and_get( array( 'name' => 'Accessories' ) );
		$args = array();
		$item = self::$entety_resolver->get_object( $term->taxonomy, $args );
		$this->assertInstanceOf( TaxFilter::class, $item );
	}
}
