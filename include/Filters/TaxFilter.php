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

		if (empty( $this->name ) ) {
			$this->set_name();
		}
		return $this->name;
	}

	/**
	 * Get options.
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
	 * Fill options.
	 */
	private function fill_options() {
		$this->options = array(
			'option' => 'option',
		);
	}

	/**
	 * Set name.
	 *
	 * Sets the name based on the taxonomy or the slug.
	 */
	private function set_name() {
		$this->name = ($this->taxonomy)?$this->taxonomy->labels->singular_name: $this->slug;
	}

}
