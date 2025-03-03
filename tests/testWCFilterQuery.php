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

	public function test_get_query_args() {
		$mock_post_ids = array( 301, 302, 303 );
		$reflection_filter_query = new ReflectionClass( $this->filter_query_obj );
		$cache_property          = $reflection_filter_query->getProperty( 'cache' );
		$cache_property->setAccessible( true );

		$mock_modifier = $this->getMockBuilder( SearchModifier::class )
		->disableOriginalConstructor()
		->onlyMethods( array( 'modify' ) )
		->getMock();

		$mock_modifier->method( 'modify' )
		->willReturn( array( 'mocked' => true ) );

		$query_args = $this->filter_query_obj->get_query_args();

		$this->assertArrayHasKey( 'post_type', $query_args );
		$this->assertArrayHasKey( 'wc_query', $query_args );
		$this->assertArrayHasKey( 'stepwise_filter', $query_args );
		$this->assertArrayHasKey( 'posts_per_page', $query_args );

		// Set modifier to get is_search set to true.
		$this->filter_query_obj->set_modifier( $mock_modifier );

		$query_arg_searched = $this->filter_query_obj->get_query_args();

		$this->assertCount( 2, $query_arg_searched );
		$this->assertArrayHasKey( 'mocked', $query_arg_searched );
		$this->assertArrayHasKey( 'posts_per_page', $query_arg_searched );

		$WPCache_mock = $this->getMockBuilder( WPCache::class )
			->onlyMethods( array( 'get' ) )
			->getMock();

		$WPCache_mock->method( 'get' )
			->with($this->equalTo($query_arg_searched))
			->willReturn( $mock_post_ids );

		$cache_property->setValue( $this->filter_query_obj, $WPCache_mock );

		$query_arg_optimized = $this->filter_query_obj->get_query_args();
		$query_args['post__in'] =  $mock_post_ids;
		$this->assertSame( $query_args, $query_arg_optimized);
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
	 * Test get count.
	 */
	public function test_get_count() {

		$post_count              = 777;
		$reflection_filter_query = new ReflectionClass( $this->filter_query_obj );
		$query_property          = $reflection_filter_query->getProperty( 'query' );
		$query_property->setAccessible( true );

		$wp_query_mock = $this->getMockBuilder( \WP_Query::class )
		->disableOriginalConstructor()
		->onlyMethods( array( 'query' ) )
		->getMock();

		$wp_query_mock->expects( $this->once() )
			->method( 'query' );

		$wp_query_mock->found_posts = $post_count;

		$query_property->setValue( $this->filter_query_obj, $wp_query_mock );

		$result = $this->filter_query_obj->get_count();
		$this->assertSame( $post_count, $result );

		// Check cache.
		$result = $this->filter_query_obj->get_count();

		$args = $this->filter_query_obj->add_post__in( $this->filter_query_obj->get_query_args(), array() );
		$this->filter_query_obj->set_initial_query_args( $args );

		// Check if post__in is empty.
		$result = $this->filter_query_obj->get_count();

		$this->assertSame( 0, $result );
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

	/**
	 * Test generate query args.
	 */
	public function test_generate_query_args() {

		$reflection                 = new ReflectionClass( $this->filter_query_obj );
		$method_generate_query_args = $reflection->getMethod( 'generate_query_args' );
		$method_generate_query_args->setAccessible( true );
		$query_args = $method_generate_query_args->invoke( $this->filter_query_obj );

		$this->assertArrayHasKey( 'post_type', $query_args );
		$this->assertArrayHasKey( 'wc_query', $query_args );
		$this->assertArrayHasKey( 'stepwise_filter', $query_args );

		$mock_modifier = $this->getMockBuilder( SearchModifier::class )
						->disableOriginalConstructor()
						->onlyMethods( array( 'modify' ) )
						->getMock();

		$mock_modifier->expects( $this->once() )
				->method( 'modify' )
				->willReturn( array( 'mocked' => true ) );

		$this->filter_query_obj->set_modifier( $mock_modifier );
		$query_args = $method_generate_query_args->invoke( $this->filter_query_obj );

		$this->assertCount( 1, $query_args );
		$this->assertArrayHasKey( 'mocked', $query_args );

	}

	/**
	 * Test add post__in.
	 */
	public function test_add_post__in() {
		$mock_post_ids = array( 301, 302, 303, 304, 305 );
		$query_args    = $this->filter_query_obj->get_query_args();
		// Add post__in.
		$query_args = $this->filter_query_obj->add_post__in( $query_args, $mock_post_ids );

		$this->assertArrayHasKey( 'post__in', $query_args );
		$this->assertEquals( $query_args['post__in'], $mock_post_ids );

		$mock_post_ids_1 = array( 301, 305, 402, 403, 404 );
		// If post__in is already set, it should be intersected.
		$query_args = $this->filter_query_obj->add_post__in( $query_args, $mock_post_ids_1 );
		$this->assertEquals( $query_args['post__in'], array( 301, 305 ) );

		$mock_post_ids_2 = array( 777 );
		// If ther is no intersection, post__in should be empty.
		$query_args = $this->filter_query_obj->add_post__in( $query_args, $mock_post_ids_2 );
		$this->assertCount( 0, $query_args['post__in'] );
	}
}
