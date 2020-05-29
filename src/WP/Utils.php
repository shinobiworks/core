<?php
/**
 * Utilities Functions for WordPress
 *
 * @package    Shinobi_Works\WP
 * @author     Shinobi Works <support@shinobiworks.com>
 * @license    https://www.gnu.org/licenses/gpl-3.0.html/ GPL v3 or later
 * @link       https://shinobiworks.com/
 * @since      0.6.0
 */

namespace Shinobi_Works\WP;

/**
 * Utils Class
 */
class Utils {

	/**
	 * Escape SVG
	 *
	 * @param string $svg
	 */
	public static function esc_svg( $svg, $echo = false ) {
		$allowed_html = [
			'svg'            => [
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true,
			],
			'defs'           => true,
			'lineargradient' => [
				'id' => true,
			],
			'stop'           => [
				'offset'     => true,
				'stop-color' => true,
			],
			'g'              => [ 'fill' => true ],
			'title'          => [ 'title' => true ],
			'path'           => [
				'd'    => true,
				'fill' => true,
			],
			'circle'         => [
				'cx' => true,
				'cy' => true,
				'r'  => true,
			],
		];
		if ( $echo ) {
			echo wp_kses( $svg, $allowed_html );
		} else {
			return wp_kses( $svg, $allowed_html );
		}
	}

}