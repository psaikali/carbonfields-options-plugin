<?php
/**
 * Plugin Name: Plugin avec page d'options CarbonFields
 * Description: Tutoriel sur https://mosaika.fr/options-plugin-wordpress-carbonfields.
 * Author: Pierre Saïkali
 * Author URI: https://mosaika.fr
 * Version: 1.0.0
 */

namespace Mosaika\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Charge notre dépendance Carbon Fields via Composer
 */
function load_carbonfields() {
	require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';
	\Carbon_Fields\Carbon_Fields::boot();
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\load_carbonfields' );

/**
 * Charge notre fichier de plugin
 *
 * @return mixed
 */
function load_plugin() {
	require_once plugin_dir_path( __FILE__ ) . '/includes/options.php';
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin' );
