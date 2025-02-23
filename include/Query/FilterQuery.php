<?php
/**
 * FilterQuery.
 *
 * @package NaGora\StepwiseFilter\Query
 */

namespace NaGora\StepwiseFilter\Query;

/**
 * FilterQuery.
 */
interface FilterQuery {

	/**
	 * Set current taxonomy.
	 *
	 * @param array $current_taxonomy Current taxonomy.
	 */
	public function set_current_taxonomy( array $current_taxonomy ): void;

	/**
	 * If isset current taxonomy in query.
	 *
	 * @return bool
	 */
	public function is_current_taxonomy(): bool;
}
