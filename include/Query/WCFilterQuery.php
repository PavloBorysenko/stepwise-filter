<?php
/**
 * WPFilterQuery.
 *
 * @package NaGora\StepwiseFilter
 */

namespace NaGora\StepwiseFilter\Query;

use NaGora\StepwiseFilter\Query\FilterQuery;
use NaGora\StepwiseFilter\Util\Cache\Cache;

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
	 *
	 * @param \NaGora\StepwiseFilter\Util\Cache\Cache $cache Cache.
	 */
	public function __construct( private Cache $cache ) {

		$this->query = new \WP_Query();

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

	/**
	 * Get ids.
	 *
	 * @return array post ids.
	 */
	public function get_ids(): array {

		$args = $this->get_query_args();

		$post_ids = $this->cache->get( $args );

		if ( is_array( $post_ids ) ) {
			return $post_ids;
		}

		$post_ids = $this->query->query( $this->get_query_args() );

		$this->cache->set( $args, $post_ids );

		return $post_ids;
	}
}
