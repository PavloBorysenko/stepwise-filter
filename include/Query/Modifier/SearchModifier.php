<?php
/**
 * SearchModifier.
 *
 * @package NaGora\StepwiseFilter\Query
 */

namespace NaGora\StepwiseFilter\Query\Modifier;

/**
 * SearchModifier.
 */
abstract class SearchModifier {

	/**
	 * Next modifier.
	 *
	 * @var SearchModifier|null
	 */
	private ?SearchModifier $next_modifier = null;

	/**
	 * Add modifier.
	 *
	 * @param SearchModifier $modifier Modifier.
	 * @return SearchModifier
	 */
	public function add_midifier( SearchModifier $modifier ): SearchModifier {
		$this->next_modifier = $modifier;
		return $modifier;
	}

	/**
	 * Modify query args.
	 *
	 * @param array $query_args Query args.
	 * @return array
	 */
	public function modify( array $query_args ): array {
		if ( $this->next_modifier ) {
			return $this->next_modifier->modify( $query_args );
		}
		return $query_args;
	}
}
