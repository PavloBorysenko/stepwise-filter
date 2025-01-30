<?php

use NaGora\StepwiseFilter\Filters\TaxFilter;

class TestTaxFilter extends WP_UnitTestCase {
	public function test_generate_correct_data() {
        $taxonomies = $this->create_taxonomy();
		$args       = array();

		$tax_filter = new TaxFilter( $taxonomies[0]->taxonomy, $args );

		$taxonomy = get_taxonomy($taxonomies[0]->taxonomy);
		$taxonomy_name = ($taxonomy)?$taxonomy->labels->singular_name: '';


		$this->assertEquals($taxonomy_name , $tax_filter->get_name() );
		$this->assertIsArray( $tax_filter->get_options() );
		$this->assertCount( 3, $tax_filter->get_options() );
	}

	public function create_taxonomy() {
		$args = array(
			array(
				'name'        => 'Accessories',
			),
			array(
				'name'        => 'Clothing',
			),
		);

		$categories[] = $this->factory->category->create_and_get($args[0]);

		$categories[] = $this->factory->category->create_and_get($args[1]);

		$child_arg = array(
			'parent' => $categories[0]->term_id,
			'name'        => 'Caps'
		);

		$categories[] = $this->factory->category->create_and_get($child_arg);

		return $categories;
	}
	public function get_taxonomy_name($slug) {
		$taxonomy = get_taxonomy($slug);
		return ($taxonomy)?$taxonomy->labels->singular_name: '';
	}
}
