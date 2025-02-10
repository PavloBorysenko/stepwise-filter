<?php
/**
 * TestFilterDataResolver.
 *
 * @package NaGora\StepwiseFilter
 */

use NaGora\StepwiseFilter\SlugResolver\FilterDataResolver;
use NaGora\StepwiseFilter\FilterItems\Submit;

/**
 * TestFilterDataResolver.
 */
class TestFilterDataResolver extends WP_UnitTestCase {

	/**
	 * TestFilterDataResolver.
	 */
	public function testFilterDataResolver() {
		$slug            = 'Submit';
		$args            = array();
		$entity_resolver = new FilterDataResolver();
		$item            = $entity_resolver->get_object( $slug, $args );
		$this->assertInstanceOf( Submit::class, $item );
	}
}
