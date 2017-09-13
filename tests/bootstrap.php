<?php
/**
 * PHPUnit bootstrap file
 *
 * @package SearchPress VIP Go
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	$_sp_dir = getenv( 'SP_DIR' );
	if ( ! $_sp_dir ) {
		$_sp_dir = dirname( dirname( __DIR__ ) ) . '/searchpress';
	}

	require_once dirname( __DIR__ ) . '/searchpress-vip-go.php';
	require_once $_sp_dir . '/searchpress.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
