<?php
/**
 * Submit.
 *
 * @package NaGora\StepwiseFilter\FilterItems
 */

namespace NaGora\StepwiseFilter\FilterItems;

use NaGora\StepwiseFilter\FilterItems\Item;

/**
 * Submit.
 */
class Submit extends Item {

	/**
	 * Get slug.
	 *
	 * @return string
	 */
	public function get_slug(): string {
		return 'submit';
	}

	/**
	 * Get name.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return esc_html__( 'Submit', 'stepwise-filter' );
	}

	/**
	 * Get options.
	 *
	 * @return array
	 */
	public function get_options(): array {

		return array(
			'filter' => array(
				'name'    => esc_html__( 'Filter', 'stepwise-filter' ),
				'viseble' => true,
			),
			'reset'  => array(
				'name'    => esc_html__( 'Reset', 'stepwise-filter' ),
				'viseble' => true,
			),
		);
	}
}
