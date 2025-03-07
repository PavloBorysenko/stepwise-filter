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

	private array $search_args = array();

	/**
	 * TaxSearchModifier constructor.
	 *
	 * @param string $slug Filter slug.
	 * @param array  $args Args like array('relation' => 'AND').
	 */
	public function __construct( private string $slug ) {
	}

	/**
	 * Set search terms for hot swapping search terms.
	 *
	 * @param array $search_terms Search terms like taxonomy slugs.
	 */
	public function set_search_terms( array $search_terms, array $args ): void {
		$this->search_terms = $search_terms;
		$this->search_args  = $args;
	}

	/**
	 * Modify query args.
	 *
	 * @param array $query_args Query args.
	 * @return array Modified query args.
	 */
	public function modify( array $query_args ): array {

		if ( !isset($query_args['tax_query']) ) {
			$query_args['tax_query'] = array(
				'relation' => 'AND',
			);
		}
		$query_args['tax_query'][] = array(  //phpcs:ignore
				'taxonomy' => $this->slug,
				'relation' => isset($this->search_args['relation']) ? $this->search_args['relation'] : 'IN',
				'field'    => isset($this->search_args['field']) ? $this->search_args['field'] : 'id',
				'terms'    => $this->search_terms,
			);

		return $query_args;
	}
}
