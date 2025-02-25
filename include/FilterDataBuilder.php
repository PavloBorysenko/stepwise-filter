<?php
/**
 * FilterDataBuilder.
 *
 * @package NaGora\StepwiseFilter
 */

namespace NaGora\StepwiseFilter;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use NaGora\StepwiseFilter\Data\ResponseData;
use NaGora\StepwiseFilter\Data\FilterData;
use NaGora\StepwiseFilter\SlugResolver\FilterDataResolver;
use NaGora\StepwiseFilter\Query\FilterQuery;
use NaGora\StepwiseFilter\Query\WCFilterQuery;
use NaGora\StepwiseFilter\Util\Cache\WPCache;

/**
 * FilterDataBuilder.
 */
class FilterDataBuilder {
	/**
	 * Retrieves a filter data object.
	 *
	 * @param string $slug Filter slug.
	 * @param array  $args Arguments.
	 *
	 * @return ResponseData
	 */
	public function get_filter_data( string $slug, array $args ): ResponseData {

		$filter_data_resolver = new FilterDataResolver();
		$filter_item          = $filter_data_resolver->get_object( $slug, $args );

		return new FilterData( $filter_item );
	}

	/**
	 * Get qurent query object.
	 *
	 * @return WCFilterQuery
	 */
	public function get_qurent_query(): FilterQuery {
		$cache           = new WPCache( 'swf_count_' );
		$wp_filter_query = new WCFilterQuery( $cache );
		return $wp_filter_query;
	}
}
