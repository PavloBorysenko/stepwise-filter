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
	 * Term.
	 *
	 * @var \WP_Term $term.
	 */
	public static $term;

	/**
	 * Filter data builder.
	 *
	 * @var FilterDataBuilder $filter_data_builder.
	 */
	public static $filter_data_builder;

	/**
	 * Set up before class.
	 *
	 * @param WP_UnitTest_Factory $factory Factory.
	 */
	public static function wpSetUpBeforeClass( $factory ) {
		self::$term                = $factory->category->create_and_get( array( 'name' => 'Accessories' ) );
		self::$filter_data_builder = new FilterDataBuilder();
	}

	/**
	 * Tear down after class.
	 */
	public static function wpTearDownAfterClass() {
		wp_delete_term( self::$term->term_id, self::$term->taxonomy );
	}

	/**
	 * Test get filter data valid response.
	 *
	 * @param string $slug Slug.
	 *
	 * @dataProvider slugs_data_provider
	 */
	public function test_get_filter_data_valid_response( $slug ) {

		$filter_data_builder = new FilterDataBuilder();
		$filter_data         = $filter_data_builder->get_filter_data( $slug, array() );
		$this->assertTrue( $filter_data instanceof ResponseData );
	}

	/**
	 * Test get filter data for taxonomy.
	 */
	public function test_get_filter_data_taxonomy() {
		$response_data = self::$filter_data_builder->get_filter_data( self::$term->taxonomy, array() );
		$this->assertTrue( $response_data instanceof ResponseData );
	}

	/**
	 * Slugs data provider.
	 *
	 * @return array
	 */
	public static function slugs_data_provider(): array {
		return array(
			array( 'not_existing_slug' ),
			array( 'submit' ),
		);
	}
}
