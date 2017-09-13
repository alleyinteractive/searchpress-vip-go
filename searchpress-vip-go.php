<?php
/**
 * Plugin Name:     SearchPress VIP Go Add-on
 * Plugin URI:      https://github.com/alleyinteractive/searchpress-vip-go
 * Description:     Improves cron indexing performance for VIP Go environments.
 * Author:          Matthew Boynes
 * Author URI:      https://www.alleyinteractive.com/
 * Text Domain:     searchpress-vip-go
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         SearchPress VIP Go
 */

namespace SearchPress\VIP_Go;

function hooks() {
	if ( function_exists( 'SP_Cron' ) ) {
		remove_action( 'sp_reindex', [ SP_Cron(), 'reindex' ] );
		add_action( 'sp_reindex', __NAMESPACE__ . '\reindex' );
	}
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\hooks', 30 );

/**
 * Reindex via cron as one job instead of 1 job per minute for n minutes.
 */
function reindex() {
	$sync_meta = SP_Sync_Meta();
	$sync_manager = SP_Sync_Manager();
	$page = 1;

	/**
	 * Add a backstop for infinite loops. Since this function's loop depends
	 * entirely on external processes, make sure that it has _some_ control. By
	 * default, this is 100,000 pages at 500 posts per page, or 50,000,000 total
	 * posts. If your site has more than 50 million posts, you should increase
	 * this value.
	 *
	 * @param int $max_pages Total number of loops allowed at 500 posts/loop.
	 */
	$max_pages = apply_filters( 'sp_vipgo_reindex_max_allowed_pages', 100000 );

	$lap = time();
	while ( $sync_meta->running && $sync_manager->do_index_loop() ) {
		if ( $page++ >= $max_pages ) {
			break;
		}

		// Ensure the script has enough time to run, increase the time limit by
		// the duration of this loop plus 5 seconds.
		@set_time_limit( time() - $lap + 5 );
		$lap = time();

		sp_contain_memory_leaks();
	}
}

/**
 * Clear some common memory leaks for bulk processors.
 */
function sp_contain_memory_leaks() {
	global $wpdb, $wp_object_cache;
	$wpdb->queries = array();
	if ( ! is_object( $wp_object_cache ) ) {
		return;
	}
	$wp_object_cache->group_ops = array();
	$wp_object_cache->stats = array();
	$wp_object_cache->memcache_debug = array();
	$wp_object_cache->cache = array();
	if ( method_exists( $wp_object_cache, '__remoteset' ) ) {
		$wp_object_cache->__remoteset();
	}
}
