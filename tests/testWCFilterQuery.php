<?php
/**
 * TestWCFilterQuery.
 *
 * @package NaGora\StepwiseFilter
 */

use NaGora\StepwiseFilter\Query\WCFilterQuery;
use NaGora\StepwiseFilter\Util\Cache\WPCache;
use NaGora\StepwiseFilter\Query\Modifier\SearchModifier;

/**
 * Class TestWCFilterQuery.
 */
class TestWCFilterQuery extends WP_UnitTestCase {

	/**
	 * Filter query object.
	 *
	 * @var WCFilterQuery
	 */
	public $filter_query_obj;

	/**
	 * Set up.
	 */
	public function setUp(): void {
		parent::setUp();
		$this->filter_query_obj = new WCFilterQuery( new WPCache( 'swf_test_count_' ) );
	}

	/**
	 * Tear down.
	 */
	public function tearDown(): void {
		parent::tearDown();
		$this->filter_query_obj = null;
	}
	/**
	 * Test get query.
	 */
	public function test_get_query_initial_args() {
		$query_args = $this->filter_query_obj->get_query_args();
		$this->assertArrayHasKey( 'post_type', $query_args );
		$this->assertArrayHasKey( 'wc_query', $query_args );
		$this->assertArrayHasKey( 'stepwise_filter', $query_args );
	}

	/**
	 * Test set initial query args.
	 */
	public function test_set_initial_query_args() {

		$new_initial_query_args = array(
			'post__in'  => '1,2,3', // set new arg.
			'post_type' => 'product,variation', // change existing arg.
		);

		$query_args = $this->filter_query_obj->get_query_args();
		$this->assertArrayNotHasKey( 'post__in', $query_args );
		$this->assertEquals( $query_args['post_type'], 'product' );

		$this->filter_query_obj->set_initial_query_args( $new_initial_query_args );
		$query_args = $this->filter_query_obj->get_query_args();

		$this->assertArrayHasKey( 'post__in', $query_args );
		$this->assertEquals( $new_initial_query_args['post__in'], $query_args['post__in'] );
		$this->assertEquals( $new_initial_query_args['post_type'], $query_args['post_type'] );
	}

	/**
	 * Test get ids and cache.
	 */
	public function test_get_ids() {

		$mock_post_ids = array( 301, 302, 303 );

		$reflection_filter_query = new ReflectionClass( $this->filter_query_obj );
		$query_property          = $reflection_filter_query->getProperty( 'query' );
		$query_property->setAccessible( true );

		$wp_query_mock = $this->getMockBuilder( \WP_Query::class )
			->onlyMethods( array( 'get_posts' ) )
			->getMock();

		$wp_query_mock->expects( $this->once() )
			->method( 'get_posts' )
			->willReturn( $mock_post_ids );

		$query_property->setValue( $this->filter_query_obj, $wp_query_mock );

		$result = $this->filter_query_obj->get_ids();
		$this->assertSame( $mock_post_ids, $result );

		// Does class cache work.
		$result = $this->filter_query_obj->get_ids();
		$this->assertSame( $mock_post_ids, $result );
	}

	/**
	 * Test set modifier and is_search.
	 */
	public function test_set_modifier_and_is_search() {

		$this->assertFalse( $this->filter_query_obj->is_search(), 'is_search should be false, modifier was not set' );

		$mock_modifier = $this->getMockForAbstractClass( SearchModifier::class );
		$this->filter_query_obj->set_modifier( $mock_modifier );

		$reflection_filter_query = new ReflectionClass( $this->filter_query_obj );
		$modifier_property       = $reflection_filter_query->getProperty( 'search_modifier' );
		$modifier_property->setAccessible( true );

		$modifier_obj = $modifier_property->getValue( $this->filter_query_obj );

		$this->assertInstanceOf( SearchModifier::class, $modifier_obj );
		$this->assertTrue( $this->filter_query_obj->is_search(), 'is_search should be true, modifier was set' );
	}
}
