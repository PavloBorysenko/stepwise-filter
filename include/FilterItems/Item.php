<?php
/**
 * Item.
 *
 * @package NaGora\StepwiseFilter\FilterItems
 */

namespace NaGora\StepwiseFilter\FilterItems;

/**
 * Item.
 */
abstract class Item {

	/**
	 * Item constructor.
	 *
	 * @param string $slug Slug.
	 * @param array  $args Arguments .
	 */
	public function __construct( protected string $slug, protected array $args ) {}

	/**
	 * Get slug. This is the main key to distinguish between filters.
	 *
	 * @return string
	 */
	abstract public function get_slug(): string;

	/**
	 * Get name. A title for the filter.
	 *
	 * @return string
	 */
	abstract public function get_name(): string;


	/**
	 * Get options. Filter elements by which the search is performed.
	 *
	 * @return array
	 */
	abstract public function get_options(): array;

	/**
	 * Is special.
	 *
	 * @param string $slug Slug.
	 *
	 * @return bool
	 */
	public static function is_special( string $slug ): bool {
		return class_exists( __NAMESPACE__ . '\\' . $slug );
	}

	/**
	 * Is taxonomy.
	 *
	 * @param string $slug Slug.
	 *
	 * @return bool
	 */
	public static function is_taxonomy( string $slug ): bool {
		return taxonomy_exists( $slug );
	}

	/**
	 * Get tax filter.
	 *
	 * @param string $slug Slug.
	 * @param array  $args Arguments.
	 *
	 * @return Item
	 */
	public static function get_tax_filter( string $slug, array $args ): Item {
		return new TaxFilter( $slug, $args );
	}

	/**
	 * Get error.
	 *
	 * @param string $slug Slug.
	 * @param array  $args Arguments.
	 *
	 * @return Item
	 */
	public static function get_error( string $slug, array $args ): Item {
		return new Error( $slug, $args );
	}

	/**
	 * Get special.
	 *
	 * @param string $slug Slug.
	 * @param array  $args Arguments.
	 *
	 * @return Item
	 */
	public static function get_special( string $slug, array $args ): Item {
		$class_name = __NAMESPACE__ . '\\' . $slug;
		return new $class_name( $slug, $args );
	}
}
