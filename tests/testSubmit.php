<?php
/**
 * TestSubmit.
 *
 * @package NaGora\StepwiseFilter
 */

use NaGora\StepwiseFilter\FilterItems\Submit;
use NaGora\StepwiseFilter\FilterItems\Item;

/**
 * TestSubmit.
 */
class TestSubmit extends WP_UnitTestCase {

	/**
	 * Test object data.
	 */
	public function test_object_data() {
		$submit  = new Submit( 'submit', array() );
		$options = $submit->get_options();

		$this->assertInstanceOf( Item::class, $submit );

		$this->assertEquals( 'submit', $submit->get_slug() );
		$this->assertEquals( 'Submit', $submit->get_name() );
		$this->assertArrayHasKey( 'filter', $options );
		$this->assertArrayHasKey( 'reset', $options );
	}
}
