<?php
/**
 * EntityResolver.
 *
 * @package NaGora\StepwiseFilter\SlugResolver
 */

namespace NaGora\StepwiseFilter\SlugResolver;

/**
 * EntityResolver.
 */
interface EntityResolver {

	/**
	 * Get type.
	 *
	 * @param string $slug Slug.
	 *
	 * @return string
	 */
	public function get_type( string $slug ): string;

	/**
	 * Get object.
	 *
	 * @param string $slug Slug.
	 * @param array  $args Arguments.
	 */
	public function get_object( string $slug, array $args );
}
