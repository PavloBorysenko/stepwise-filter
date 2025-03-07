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
		// Query optimization.
		$search_args = $this->change_search_to_post_in( $this->generate_query_for_ids() );

		return $search_args;
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
	 * Generate query args for count.
	 *
	 * @return array
	 */
	private function generate_query_for_count(): array {
		$args                    = $this->generate_query_args();
		$args['posts_per_page']  = 1;
		$args['stepwise_filter'] = 'count';
		return $args;
	}

	/**
	 * Generate query args for ids.
	 *
	 * @return array
	 */
	private function generate_query_for_ids(): array {
		$args                    = $this->generate_query_args();
		$args['posts_per_page']  = -1;
		$args['stepwise_filter'] = 'ids';
		return $args;
	}

	/**
	 * Add post__in.
	 *
	 * @param array $query_args Query args.
	 * @param array $post_ids Post ids.
	 * @return array
	 */
	public function add_post__in( array $query_args, array $post_ids ): array {

		if ( empty( $query_args['post__in'] ) ) {
			$query_args['post__in'] = $post_ids;
		} else {
			$query_args['post__in'] = array_values( array_intersect( $query_args['post__in'], $post_ids ) );
		}

		return $query_args;
	}

	/**
	 * Change search to post_in to optimization wp query.
	 *
	 * @param array $query_args Query args.
	 * @return array
	 */
	private function change_search_to_post_in( array $query_args ): array {

		if ( $this->is_search() ) {
			$cached_ids = $this->cache->get( $query_args );
			if ( is_array( $cached_ids ) && 200 > count($cached_ids) ) {
				$search_args                   = $this->add_post__in( $this->query_args, $cached_ids );
				$search_args['posts_per_page'] = isset( $query_args['posts_per_page'] ) ? $query_args['posts_per_page'] : -1;
				return $search_args;
			}
		}

		return $query_args;
	}

	/**
	 * Get ids.
	 *
	 * @return array post ids.
	 */
	public function get_ids(): array {

		$args = $this->generate_query_for_ids();

		if ( $this->check_is_empty_result( $args ) ) {
			return array();
		}

		$post_ids = $this->cache->get( $args );

		if ( is_array( $post_ids ) ) {
			return $post_ids;
		}

		$post_ids = $this->query->query( $args );

		$this->cache->set( $args, $post_ids );

		return $post_ids;
	}

	/**
	 * Get count.
	 *
	 * @return int
	 */
	public function get_count(): int {

		$args = $this->generate_query_for_count();

		if ( $this->check_is_empty_result( $args ) ) {
			return 0;
		}

		$count = $this->cache->get( $args );
		if ( is_int( $count ) ) {
			return $count;
		}

		$this->query->query( $args );
		$count = $this->query->found_posts;

		$this->cache->set( $args, $count );

		return $count;
	}

	/**
	 * Is search.
	 *
	 * @return bool
	 */
	public function is_search(): bool {
		return null !== $this->search_modifier;
	}

	/**
	 * Check is empty result.
	 *
	 * @param array $args Query args.
	 * @return bool
	 */
	private function check_is_empty_result( $args ): bool {
		return isset( $args['post__in'] ) && empty( $args['post__in'] );
	}
}
