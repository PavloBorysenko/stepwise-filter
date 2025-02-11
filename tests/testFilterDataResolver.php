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

	static $entety_resolver;

	public static function wpSetUpBeforeClass( $factory ) {
		self::$entety_resolver = new FilterDataResolver();
	}

	/**
	 * TestFilterDataResolver.
	 */
	public function test_get_object_special() {
		$slug            = 'Submit';
		$args            = array();
		$item            = self::$entety_resolver->get_object( $slug, $args );
		$this->assertInstanceOf( Submit::class, $item );
	}

	public function test_get_object_does_not_exist() {
		$slug            = 'not_existing';
		$args            = array();
		$item            = self::$entety_resolver->get_object( $slug, $args );
		$this->assertInstanceOf( Error::class, $item );
	}

	public function test_get_object_taxonomy() {
		$term            = $this->factory->category->create_and_get( array( 'name' => 'Accessories' ) );
		$args            = array();
		$item            = self::$entety_resolver->get_object( $term->taxonomy, $args );
		$this->assertInstanceOf( TaxFilter::class, $item );
	}
}
