<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    cbxgooglemap
 * @subpackage cbxgooglemap/templates/admin
 */

echo '<span id="cbxgooglemapshortcode-' . intval( $post_id ) . '" class="cbxgooglemapshortcode cbxgooglemapshortcode-' . intval( $post_id ) . '">[cbxgooglemap id="' . intval( $post_id ) . '"]</span>';
//echo '<span class="cbxgooglemapshortcodetrigger" data-clipboard-text=\'[cbxgooglemap id="'.intval($post_id).'"]\' title="' . esc_html__("Copy to clipboard", 'cbxgooglemap') . '">
//	 </span>';
echo '<span class="cbxgooglemapshortcodecopytrigger" data-clipboard-text=\'[cbxgooglemap id="' . $post_id . '"]\' data-success="' . __( 'Copied', 'cbxgooglemap' ) . '" title="' . __( 'Click to copy', 'cbxgooglemap' ) . '"><img class="cbxgooglemapshortcode-copy-image" src="' . CBXGOOGLEMAP_ROOT_URL . 'assets/img/copy.svg" alt="' . __( 'CBX GoogleMaps Shortcode Copy', 'cbxgooglemap' ) . '"/></span>';
echo '<div class="cbxgooglemap_clear"></div>';