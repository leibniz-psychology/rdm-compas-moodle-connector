<?php
/**
 * Formatting FUnctions.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    RDM Compas Moodle Connector
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if ( ! function_exists( 'wdm_edwiser_bridge_wp_clean' ) ) {
	/**
	 * Clean variables.
	 *
	 * @param string $var var.
	 *
	 * @return string
	 */
	function wdm_edwiser_bridge_wp_clean( $var ) {
		return sanitize_text_field( $var );
	}
}
