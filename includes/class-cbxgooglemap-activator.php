<?php

/**
 * Fired during plugin activation
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXGoogleMap
 * @subpackage CBXGoogleMap/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    CBXGoogleMap
 * @subpackage CBXGoogleMap/includes
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXGoogleMap_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		CBXGooglemapHelper::create_googlemap_post_type();

		add_option( 'cbxgooglemap_flush_rewrite_rules', 'true' );
		set_transient( 'cbxgooglemap_activated_notice', 1 );


		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		if (defined( 'CBXGOOGLEMAPPRO_PLUGIN_NAME' ) || in_array( 'cbxgooglemappro/cbxgooglemappro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) {
			//plugin is activated

			$plugin_version = CBXGOOGLEMAPPRO_PLUGIN_VERSION;

			if ( version_compare( $plugin_version, '1.0.5', '<=' ) ) {
				deactivate_plugins( 'cbxgooglemappro/cbxgooglemappro.php' );
				set_transient( 'cbxgooglemappro_deactivated_notice', 1 );
			}
		}

	}//end activate
}//end class CBXGoogleMap_Activator
