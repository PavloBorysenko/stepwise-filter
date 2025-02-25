<?php
/**
 * TestWCFilterQuery.
 *
 * @package NaGora\StepwiseFilter
 */

use NaGora\StepwiseFilter\Query\WCFilterQuery;
use NaGora\StepwiseFilter\Util\Cache\WPCache;

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
	 * Test get query.
	 */
	public function test_get_query_initial_args() {
		$filter_query_obj = new WCFilterQuery( new WPCache( 'swf_test_count_' ) );
		$query_args       = $filter_query_obj->get_query_args();
		$this->assertArrayHasKey( 'post_type', $query_args );
		$this->assertArrayHasKey( 'wc_query', $query_args );
		$this->assertArrayHasKey( 'stepwise_filter', $query_args );
	}

	/**
	 * Test set initial query args.
	 */
	public function test_set_initial_query_args() {

		$filter_query_obj       = new WCFilterQuery( new WPCache( 'swf_test_count_' ) );
		$new_initial_query_args = array(
			'post__in'  => '1,2,3', // set new arg.
			'post_type' => 'product,variation', // change existing arg.
		);

		$query_args = $filter_query_obj->get_query_args();
		$this->assertArrayNotHasKey( 'post__in', $query_args );
		$this->assertEquals( $query_args['post_type'], 'product' );

		$filter_query_obj->set_initial_query_args( $new_initial_query_args );
		$query_args = $filter_query_obj->get_query_args();

		$this->assertArrayHasKey( 'post__in', $query_args );
		$this->assertEquals( $new_initial_query_args['post__in'], $query_args['post__in'] );
		$this->assertEquals( $new_initial_query_args['post_type'], $query_args['post_type'] );
	}

	/**
	 * Test get ids and cache.
	 */
	public function test_get_ids() {
		$filter_query_obj = new WCFilterQuery( new WPCache( 'swf_test_count_', 10 ) );

		$mock_post_ids = array( 301, 302, 303 );

		$reflection_filter_query = new ReflectionClass( $filter_query_obj );
		$query_property          = $reflection_filter_query->getProperty( 'query' );
		$query_property->setAccessible( true );

		$wp_query_mock = $this->getMockBuilder( \WP_Query::class )
			->onlyMethods( array( 'get_posts' ) )
			->getMock();

		$wp_query_mock->expects( $this->once() )
			->method( 'get_posts' )
			->willReturn( $mock_post_ids );

		$query_property->setValue( $filter_query_obj, $wp_query_mock );

		$result = $filter_query_obj->get_ids();
		$this->assertSame( $mock_post_ids, $result );

		// Does class cache work.
		$result = $filter_query_obj->get_ids();
		$this->assertSame( $mock_post_ids, $result );
	}
}
