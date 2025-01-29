<?php
/**
 * TaxFilter.
 *
 * @package NaGora\StepwiseFilter\Filters
 */

namespace NaGora\StepwiseFilter\Filters;

/**
 * TaxFilter.
 */
class TaxFilter {

	/**
	 * TaxFilter constructor.
	 *
	 * @param string $slug Taxonomy Filter slug.
	 * @param array  $args Arguments.
	 */
	public function __construct( private string $slug, private array $args ) {
	}

	/**
	 * Get slug.
	 *
	 * @return string
	 */
	public function get_slug(): string {
		return $this->slug;
	}
}
