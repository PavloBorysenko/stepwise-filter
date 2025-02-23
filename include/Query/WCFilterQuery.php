<?php
/**
 * WPFilterQuery.
 *
 * @package NaGora\StepwiseFilter
 */

namespace NaGora\StepwiseFilter\Query;

use NaGora\StepwiseFilter\Query\FilterQuery;

/**
 * WPFilterQuery.
 */
class WCFilterQuery implements FilterQuery {

	/**
	 * WP Query arguments.
	 *
	 * @var array
	 */
	private array $query_args = array();

	/**
	 * WP Query.
	 *
	 * @var \WP_Query
	 */
	private \WP_Query $query;

	/**
	 * WPFilterQuery constructor.
	 */
	public function __construct( ) {

		$this->query      = new \WP_Query();

		$this->query_args = array(
			'post_type'       => 'product',
			'wc_query'        => 'product_query',
			'filds'           => 'ids',
			'stepwise_filter' => 'count',
		);
	}

	/**
	 * Set initial query args.
	 *
	 * @param array $new_query_args New query args.
	 */
	public function set_initial_query_args( array $new_query_args ): void {
		$this->query_args = array_merge( $this->query_args, $new_query_args );
	}

	/**
	 * Get query args for WP_Query.
	 *
	 * @return array
	 */
	public function get_query_args(): array {
		return $this->query_args;
	}
}
