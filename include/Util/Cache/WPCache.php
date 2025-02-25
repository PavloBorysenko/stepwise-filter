<?php
/**
 * WPCache.
 *
 * @package NaGora\StepwiseFilter\Util\Cache
 * @version 1.0.0
 */

namespace NaGora\StepwiseFilter\Util\Cache;

/**
 * WPCache.
 */
class WPCache extends Cache {

	/**
	 * Get cached data.
	 *
	 * @param string|array $key key for cache.
	 *
	 * @return mixed
	 */
	public function get( string|array $key ): mixed {
		$key = $this->get_hash( $key );
		return wp_cache_get( $key, $this->prefix );
	}

	/**
	 * Set cached data.
	 *
	 * @param string|array $key key for cache.
	 * @param mixed        $value data for cache.
	 * @param int|null     $expire expire for cache.
	 *
	 * @return void
	 */
	public function set( array|string $key, $value, ?int $expire = null ): void {
		$key = $this->get_hash( $key );
		wp_cache_set( $key, $value, $this->prefix, $expire ?? $this->expire );
	}

	/**
	 * Delete cached data.
	 *
	 * @param string|array $key key for cache.
	 *
	 * @return void
	 */
	public function delete( array|string $key ): void {
		$key = $this->get_hash( $key );
		wp_cache_delete( $key, $this->prefix );
	}
}
