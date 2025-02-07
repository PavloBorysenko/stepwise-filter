<?php
/**
 * FilterDataResolver.
 *
 * @package NaGora\StepwiseFilter\SlugResolver
 */

namespace NaGora\StepwiseFilter\SlugResolver;

use NaGora\StepwiseFilter\SlugResolver\EntityResolver;
use NaGora\StepwiseFilter\FilterItems\TaxFilter;
use NaGora\StepwiseFilter\FilterItems\Error;
use NaGora\StepwiseFilter\FilterItems\Item as FilterItem;

/**
 * FilterDataResolver.
 */
class FilterDataResolver implements EntityResolver {

	/**
	 * Get type.
	 *
	 * @param string $slug Slug.
	 *
	 * @return string
	 */
	public function get_type( $slug ): string {
		if ( taxonomy_exists( $slug ) ) {
			return 'taxonomy';
		}
		return 'error';
	}

	/**
	 * Get object.
	 *
	 * @param string $slug Slug.
	 * @param array  $args Arguments.
	 *
	 * @return FilterItem
	 */
	public function get_object( string $slug, array $args ): FilterItem {
		switch ( $this->get_type( $slug ) ) {

			case 'taxonomy':
				return new TaxFilter( $slug, $args );

		}
		return new Error( $slug, $args );
	}
}
