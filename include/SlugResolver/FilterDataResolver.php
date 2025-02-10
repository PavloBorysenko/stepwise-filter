<?php
/**
 * FilterDataResolver.
 *
 * @package NaGora\StepwiseFilter\SlugResolver
 */

namespace NaGora\StepwiseFilter\SlugResolver;

use NaGora\StepwiseFilter\SlugResolver\EntityResolver;
use NaGora\StepwiseFilter\FilterItems\Item as FilterItem;

/**
 * FilterDataResolver.
 */
class FilterDataResolver implements EntityResolver {

	/**
	 * Get object.
	 *
	 * @param string $slug Slug.
	 * @param array  $args Arguments.
	 *
	 * @return FilterItem
	 */
	public function get_object( string $slug, array $args ): FilterItem {

		if ( FilterItem::is_special( $slug ) ) {
			return FilterItem::get_special( $slug, $args );
		}

		if ( FilterItem::is_taxonomy( $slug ) ) {
			return FilterItem::get_tax_filter( $slug, $args );
		}

		// FilterItem::is_meta( $slug ) TODO.

		return FilterItem::get_error( $slug, $args );
	}
}
