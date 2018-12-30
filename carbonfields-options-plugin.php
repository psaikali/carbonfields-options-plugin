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
 * Charge notre fichier de plugin seulement si CarbonFields est activé
 *
 * @return mixed
 */
function load_plugin() {
	$carbonfields_plugin = 'carbon-fields/carbon-fields-plugin.php';
	$should_load_plugin  = in_array( $carbonfields_plugin, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );

	if ( ! apply_filters( 'msk_plugin_should_load', $should_load_plugin ) ) {
		return;
	}

	require_once plugin_dir_path( __FILE__ ) . '/includes/options.php';
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin' );
