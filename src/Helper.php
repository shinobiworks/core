<?php
/**
 * Plugin Name:     Wp Core
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     wp-core
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Wp_Core
 */

namespace Shinobi_Works\WP_Core;

class Helper {

	/**
	 * Trim for multi byte
	 *
	 * @param string $string text.
	 *
	 * @return string
	 */
	public static function mb_trim( $string ) {
		return preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $string );
	}

	/**
	 * Get attributes from array
	 *
	 * @param array  $new_atts new attributes.
	 * @param string $atts old attributes.
	 *
	 * @return string
	 */
	public static function get_attributes( $new_atts = [], $atts = '' ) {
		foreach ( $new_atts as $key => $value ) {
			$atts .= ' ' . $key . '="' . $value . '"';
		}
		return $atts;
	}

}
