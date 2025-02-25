<?php
/**
 * Cache.
 *
 * @package NaGora\StepwiseFilter\Util\Cache
 * @version 1.0.0
 */

namespace NaGora\StepwiseFilter\Util\Cache;

/**
 * Cache.
 */
abstract class Cache {

	/**
	 * Cache constructor.
	 *
	 * @param string $prefix Prefix for cache.
	 * @param int    $expire Expire for cache.
	 */
	public function __construct( protected string $prefix = 'swf_', protected int $expire = HOUR_IN_SECONDS ) {}

	/**
	 * Get hash of key.
	 *
	 * @param array|string $arg Key.
	 *
	 * @return string
	 */
	public function get_hash( array|string $arg ): string {
		// wp_json_encode - no need in this case.
		return md5( json_encode( $arg ) ); // phpcs:ignore
	}

	/**
	 * Get cached data.
	 *
	 * @param array|string $key Key.
	 *
	 * @return mixed
	 */
	abstract public function get( array|string $key ): mixed;

	/**
	 * Set cached data.
	 *
	 * @param array|string $key Key.
	 * @param mixed        $value Value.
	 */
	abstract public function set( array|string $key, mixed $value );

	/**
	 * Delete cached data.
	 *
	 * @param array|string $key Key.
	 */
	abstract public function delete( array|string $key );
}
