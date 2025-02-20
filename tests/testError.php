<?php
/**
 * TestError.
 *
 * @package NaGora\StepwiseFilter
 */

use NaGora\StepwiseFilter\FilterItems\Error;
use NaGora\StepwiseFilter\FilterItems\Item;


/**
 * Class TestError.
 */
class TestError extends WP_UnitTestCase {

	/**
	 * Test object data.
	 */
	public function test_object_data() {
		$message = 'test error message';
		$slug    = 'any_slug';

		$error   = new Error( $slug, array( 'message' => $message ) );
		$options = $error->get_options();

		$this->assertInstanceOf( Item::class, $error );
		$this->assertEquals( 'error', $error->get_slug() );
		$this->assertEquals( 'Error', $error->get_name() );

		$this->assertEquals( $slug, $options['slug'] );
		$this->assertEquals( $message, $options['message'] );
	}

	/**
	 * Test object data if empty properties.
	 */
	public function test_object_data_if_empty_properties() {
		$error   = new Error( '', array() );
		$options = $error->get_options();

		$this->assertInstanceOf( Item::class, $error );
		$this->assertEquals( 'error', $error->get_slug() );
		$this->assertEquals( 'Error', $error->get_name() );
		$this->assertEquals( '', $options['slug'] );
		$this->assertEquals( 'Such filter element does not exist', $options['message'] );
	}
}
