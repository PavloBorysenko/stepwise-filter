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
	 * Current taxonomy - is tax_query arguments.
	 *
	 * @see https://developer.wordpress.org/reference/classes/wp_query/#taxonomy-parameters
	 *
	 * @var array
	 */
	private array $current_taxonomy = array();

	/**
	 * Post type.
	 *
	 * @var string
	 */
	private string $post_type = 'product';

	/**
	 * WPFilterQuery constructor.
	 */
	public function __construct() {
	}

	/**
	 * Set current taxonomy.
	 *
	 * @param array $current_taxonomy Current taxonomy.
	 */
	public function set_current_taxonomy( array $current_taxonomy ): void {
		$this->current_taxonomy = ! empty( $current_taxonomy ) ? $this->generate_current_taxonomy_query( $current_taxonomy ) : array();
	}

	/**
	 * Generate current taxonomy query.
	 *
	 * @param array $current_taxonomy Current taxonomy.
	 *
	 * @return array tax_query arguments for wp_query.
	 */
	private function generate_current_taxonomy_query( array $current_taxonomy ): array {
		$taxonomy  = array_key_first( $current_taxonomy );
		$term_slug = $current_taxonomy[ $taxonomy ];
		$tax_query = array();

		$term = get_term_by( 'slug', $term_slug, $taxonomy );
		if ( $term ) {
			$tax_query = array(
				'taxonomy' => $term->taxonomy,
				'field'    => 'id',
				'terms'    => $term->term_id,
			);
		}
		return $tax_query;
	}

	/**
	 * If isset current taxonomy in query.
	 *
	 * @return bool
	 */
	public function is_current_taxonomy(): bool {
		return ! empty( $this->current_taxonomy );
	}
}
