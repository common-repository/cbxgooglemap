<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://codeboxr.com
 * @since             1.0.0
 * @package           Cbxgooglemap
 *
 * @wordpress-plugin
 * Plugin Name:       CBX Map for Google Map & OpenStreetMap
 * Plugin URI:        https://codeboxr.com/product/cbx-google-map-for-wordpress/
 * Description:       Easy responsive embed of google map and openstreet map
 * Version:           1.1.12
 * Author:            Codeboxr
 * Author URI:        https://codeboxr.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cbxgooglemap
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


defined( 'CBXGOOGLEMAP_PLUGIN_NAME' ) or define( 'CBXGOOGLEMAP_PLUGIN_NAME', 'cbxgooglemap' );
defined( 'CBXGOOGLEMAP_PLUGIN_VERSION' ) or define( 'CBXGOOGLEMAP_PLUGIN_VERSION', '1.1.12' );
defined( 'CBXGOOGLEMAP_BASE_NAME' ) or define( 'CBXGOOGLEMAP_BASE_NAME', plugin_basename( __FILE__ ) );
defined( 'CBXGOOGLEMAP_ROOT_PATH' ) or define( 'CBXGOOGLEMAP_ROOT_PATH', plugin_dir_path( __FILE__ ) );
defined( 'CBXGOOGLEMAP_ROOT_URL' ) or define( 'CBXGOOGLEMAP_ROOT_URL', plugin_dir_url( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cbxgooglemap-activator.php
 */
function activate_cbxgooglemap() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxgooglemap-activator.php';
	CBXGoogleMap_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cbxgooglemap-deactivator.php
 */
function deactivate_cbxgooglemap() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxgooglemap-deactivator.php';
	CBXGoogleMap_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cbxgooglemap' );
register_deactivation_hook( __FILE__, 'deactivate_cbxgooglemap' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require plugin_dir_path( __FILE__ ) . 'includes/class-cbxgooglemap.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cbxgooglemap() {
	return CBXGoogleMap::instance();
}//end method run_cbxgooglemap

$GLOBALS['cbxgooglemap'] = run_cbxgooglemap();