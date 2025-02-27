<?php
/**
 * WPFilterQuery.
 *
 * @package NaGora\StepwiseFilter\Query
 */

namespace NaGora\StepwiseFilter\Query;

use NaGora\StepwiseFilter\Query\FilterQuery;
use NaGora\StepwiseFilter\Query\Modifier\SearchModifier;
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
	 * Search modifier. Object chine for change query args.
	 *
	 * @var SearchModifier|null
	 */
	private SearchModifier|null $search_modifier = null;

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
	 * Set search modifier.
	 *
	 * @param SearchModifier|null $modifier Search modifier.
	 */
	public function set_modifier( SearchModifier|null $modifier ): void {
		$this->search_modifier = $modifier;
	}

	/**
	 * Get query args for WP_Query.
	 *
	 * @return array
	 */
	public function get_query_args(): array {
		// TODO if ther is cache and search modifier. chenge args - add post__in.
		return $this->generate_query_args();
	}

	/**
	 * Generate query args for WP_Query.
	 *
	 * @return array
	 */
	private function generate_query_args(): array {

		if ( $this->is_search() ) {
			return $this->search_modifier->modify( $this->query_args );
		}
		return $this->query_args;
	}

	/**
	 * Get ids.
	 *
	 * @return array post ids.
	 */
	public function get_ids(): array {

		$args = $this->generate_query_args();

		$post_ids = $this->cache->get( $args );

		if ( is_array( $post_ids ) ) {
			return $post_ids;
		}

		$post_ids = $this->query->query( $args );

		$this->cache->set( $args, $post_ids );

		return $post_ids;
	}

	/**
	 * Is search.
	 *
	 * @return bool
	 */
	public function is_search(): bool {
		return null !== $this->search_modifier;
	}
}
