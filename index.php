<?php
/*
 * Plugin Name: Document CPT
 * Version: 1.0.0
 * Author: Prometheus
 * License: MIT
 * Text Domain: doc-cpt
 * Domain Path: /lang
 */

use RusAggression\DocumentCPT\Plugin;

if ( defined( 'ABSPATH' ) ) {
	$autoload_path = 'vendor/autoload.php';
	
	if ( file_exists( __DIR__ . '/' . $autoload_path ) ) {
		require_once __DIR__ . '/' . $autoload_path;
	} elseif ( file_exists( ABSPATH . $autoload_path ) ) {
		require_once ABSPATH . $autoload_path;
	}

	Plugin::get_instance();
}
