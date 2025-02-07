<?php
/**
 * Error.
 *
 * @package NaGora\StepwiseFilter\FilterItems
 */

namespace NaGora\StepwiseFilter\FilterItems;

use NaGora\StepwiseFilter\FilterItems\Item;

/**
 * Error.
 */
class Error implements Item {

	/**
	 * Error constructor.
	 *
	 * @param string $slug Slug.
	 * @param array  $args Arguments.
	 */
	public function __construct( private string $slug, private array $args ) {}

	/**
	 * Get slug.
	 *
	 * @return string
	 */
	public function get_slug(): string {
		return 'error';
	}

	/**
	 * Get name.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return esc_html__( 'Error', 'stepwise-filter' );
	}

	/**
	 * Get options.
	 *
	 * @return array
	 */
	public function get_options(): array {

		$message = isset( $this->args['message'] )
		? $this->args['message']
		: esc_html__( 'Such filter element does not exist', 'stepwise-filter' );

		return array(
			'slug'    => $this->slug,
			'message' => $message,
			'args'    => $this->args,
		);
	}
}
