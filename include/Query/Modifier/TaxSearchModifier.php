<?php
/**
 * TaxSearchModifier.
 *
 * @package NaGora\StepwiseFilter\Query
 */

namespace NaGora\StepwiseFilter\Query\Modifier;

use NaGora\StepwiseFilter\Query\Modifier\SearchModifier;

/**
 * TaxSearchModifier.
 */
class TaxSearchModifier extends SearchModifier {

	/**
	 * Search terms.
	 *
	 * @var array
	 */
	private array $search_terms = array();

	/**
	 * TaxSearchModifier constructor.
	 *
	 * @param string $slug Filter slug.
	 * @param array  $args Args like array('relation' => 'AND').
	 */
	public function __construct( private string $slug, private array $args ) {
	}

	/**
	 * Set search terms for hot swapping search terms.
	 *
	 * @param array $search_terms Search terms like taxonomy slugs.
	 */
	public function set_search_terms( array $search_terms ): void {
		$this->search_terms = $search_terms;
	}

	/**
	 * Modify query args.
	 *
	 * @param array $query_args Query args.
	 * @return array Modified query args.
	 */
	public function modify( $query_args ): array {

		$query_args['tax_query'] = array(  // phpcs:ignore
			array(
				'taxonomy' => $this->slug,
				'relation' => 'AND',
				'terms'    => $this->search_terms,
			),
		);

		return $query_args;
	}
}
