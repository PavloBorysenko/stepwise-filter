<?php
/**
 * FilterQuery.
 *
 * @package NaGora\StepwiseFilter\Query
 */

namespace NaGora\StepwiseFilter\Query;

/**
 * FilterQuery.
 */
interface FilterQuery {

	/**
	 * Get query args for WP_Query.
	 *
	 * @return array
	 */
	public function get_query_args(): array;

	/**
	 * Set initial query args.
	 *
	 * @param array $new_query_args New query args.
	 */
	public function set_initial_query_args( array $new_query_args ): void;
}
