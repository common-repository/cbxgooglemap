<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXGoogleMap
 * @subpackage CBXGoogleMap/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    CBXGoogleMap
 * @subpackage CBXGoogleMap/includes
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXGoogleMap_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option( 'cbxgooglemap_flush_rewrite_rules' );
	}//end deactivate

}//end CBXGoogleMap_Deactivator
