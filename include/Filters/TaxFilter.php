<?php
/**
 * TaxFilter.
 *
 * @package NaGora\StepwiseFilter\Filters
 */

namespace NaGora\StepwiseFilter\Filters;

use NaGora\StepwiseFilter\Filters\Filter;

/**
 * TaxFilter.
 */
class TaxFilter implements Filter {

	/**
	 * Name of the filter.
	 *
	 * @var string
	 */
	private string $name = '';

	/**
	 * Terms of the filter.
	 *
	 * @var array|null
	 */
	private array|null $options = null;

	/**
	 * Taxonomy.
	 *
	 * @var \WP_Taxonomy|false
	 */
	private \WP_Taxonomy|false $taxonomy = false;

	/**
	 * TaxFilter constructor.
	 *
	 * @param string $slug Taxonomy Filter slug.
	 * @param array  $args Arguments.
	 */
	public function __construct( private string $slug, private array $args ) {
		$this->set_taxonomy_data();
	}

	/**
	 * Set taxonomy data.
	 */
	private function set_taxonomy_data() {
		$this->taxonomy = get_taxonomy( $this->slug );
	}

	/**
	 * Get slug.
	 *
	 * @return string
	 */
	public function get_slug(): string {
		return $this->slug;
	}

	/**
	 * Get name.
	 *
	 * @return string
	 */
	public function get_name(): string {

		if ( empty( $this->name ) ) {
			$this->set_name();
		}
		return $this->name;
	}

	/**
	 * Get options. Returns an array with keys name, slag, id, count, parent, content.
	 *
	 * @return array
	 */
	public function get_options(): array {
		if ( is_null( $this->options ) ) {
			$this->fill_options();
		}
		return $this->options;
	}

	/**
	 * Fill options. Function for late filling of options.
	 */
	private function fill_options() {

		$this->options = array();

		if ( ! $this->taxonomy ) {
			return;
		}

		$terms = $this->get_terms();

		foreach ( $terms as $term ) {
			$this->options[ $term->slug ] = array(
				'id'      => $term->term_id,
				'name'    => $term->name,
				'slug'    => $term->slug,
				'count'   => $term->count,
				'parent'  => $term->parent,
				'content' => $term->description,
			);
		}
	}

	/**
	 * Set name.
	 *
	 * Sets the name based on the taxonomy or the slug.
	 */
	private function set_name() {
		$this->name = ( $this->taxonomy ) ? $this->taxonomy->labels->singular_name : $this->slug;
	}

	/**
	 * Get terms.
	 *
	 * @return array
	 */
	private function get_terms(): array {

		$terms = get_terms(
			array(
				'taxonomy'   => $this->slug,
				'hide_empty' => isset( $this->args['hide_empty'] ) ? $this->args['hide_empty'] : false,
				'include'    => isset( $this->args['include'] ) ? $this->args['include'] : array(),
				'exclude'    => isset( $this->args['exclude'] ) ? $this->args['exclude'] : array(),
				'child_of'   => isset( $this->args['child_of'] ) ? $this->args['child_of'] : 0,
			)
		);

		return is_array( $terms ) ? $terms : array();
	}
}
