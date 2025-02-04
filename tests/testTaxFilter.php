<?php
/**
 * TestTaxFilter.
 *
 * @package NaGora\StepwiseFilter
 */

use NaGora\StepwiseFilter\Filters\TaxFilter;

/**
 * Class TestTaxFilter.
 */
class TestTaxFilter extends WP_UnitTestCase {

	/**
	 * Test data with standard taxonomy.
	 */
	public function test_data_with_standard_taxonomy() {
		$taxonomies = $this->create_taxonomy();
		$args       = array();

		$tax_filter = new TaxFilter( $taxonomies[0]->taxonomy, $args );

		$taxonomy      = get_taxonomy( $taxonomies[0]->taxonomy );
		$taxonomy_name = ( $taxonomy ) ? $taxonomy->labels->singular_name : '';
		$options       = $tax_filter->get_options();

		$this->assertEquals( $taxonomy_name, $tax_filter->get_name() );
		$this->assertEquals( $taxonomies[0]->taxonomy, $tax_filter->get_slug() );
		$this->assertIsArray( $options );

		foreach ( $taxonomies as $term ) {
			$this->assertTrue( isset( $options[ $term->slug ] ) );
			$this->assertIsArray( $options[ $term->slug ] );

			$this->assertArrayHasKey( 'id', $options[ $term->slug ] );
			$this->assertEquals( $term->term_id, $options[ $term->slug ]['id'] );

			$this->assertArrayHasKey( 'name', $options[ $term->slug ] );
			$this->assertEquals( $term->name, $options[ $term->slug ]['name'] );

			$this->assertArrayHasKey( 'slug', $options[ $term->slug ] );
			$this->assertEquals( $term->slug, $options[ $term->slug ]['slug'] );

			// It doesn't matter what is being transmitted in this test.
			$this->assertArrayHasKey( 'count', $options[ $term->slug ] );
			$this->assertArrayHasKey( 'parent', $options[ $term->slug ] );
			$this->assertArrayHasKey( 'content', $options[ $term->slug ] );

		}

		$this->delete_taxonomy( $taxonomies );
	}

	/**
	 * Test if taxonomy does not exist.
	 */
	public function test_if_taxonomy_does_not_exist() {
		$slug       = 'non-existent-taxonomy';
		$tax_filter = new TaxFilter( $slug, array() );
		$this->assertEmpty( $tax_filter->get_options(), 'The options array should be empty.' );
		$this->assertEquals( $slug, $tax_filter->get_slug() );
		$this->assertEquals( $slug, $tax_filter->get_name() );
	}

	/**
	 *
	 * Util function to create 3 categories.
	 *
	 * @return array
	 */
	public function create_taxonomy() {
		$args = array(
			array(
				'name' => 'Accessories',
			),
			array(
				'name' => 'Clothing',
			),
		);

		$categories[] = $this->factory->category->create_and_get( $args[0] );
		$categories[] = $this->factory->category->create_and_get( $args[1] );

		$child_arg = array(
			'parent' => $categories[0]->term_id,
			'name'   => 'Caps',
		);

		$categories[] = $this->factory->category->create_and_get( $child_arg );

		return $categories;
	}

	/**
	 * Util function to delete all created categories.
	 *
	 * @param array $taxonomies array of WP_Term.
	 */
	public function delete_taxonomy( $taxonomies ) {
		foreach ( $taxonomies as $taxonomy ) {
			wp_delete_term( $taxonomy->term_id, $taxonomy->taxonomy );
		}
	}
}
