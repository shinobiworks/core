<?php
/**
 * Utilities Functions for WordPress
 *
 * @package    Shinobi_Works\WP
 * @author     Shinobi Works <support@shinobiworks.com>
 * @license    https://www.gnu.org/licenses/gpl-3.0.html/ GPL v3 or later
 * @link       https://shinobiworks.com/
 * @since      1.0.0
 */

namespace Shinobi_Works\WP;

/**
 * Utils Class
 */
class Utils {

	/**
	 * Echo the SVG after escaping.
	 *
	 * @param string $svg
	 * @return void
	 */
	public static function esc_svg_e( $svg ) {
		echo self::esc_svg( $svg ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Escape SVG
	 *
	 * @param string $svg
	 * @return string
	 */
	public static function esc_svg( $svg ) {
		return wp_kses(
			$svg,
			[
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
			]
		);
	}

	/**
	 * Echo input tag after escaping
	 *
	 * @param string $input
	 * @return void
	 */
	public static function esc_input_e( $input ) {
		echo self::esc_input( $input ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Escape input tag
	 *
	 * @param string $input
	 * @return string
	 */
	public static function esc_input( $input ) {
		return wp_kses(
			$input,
			[
				'input' => [
					'type'             => true,
					'value'            => true,
					'autocomplete'     => true,
					'autofocus'        => true,
					'disabled'         => true,
					'form'             => true,
					'list'             => true,
					'name'             => true,
					'readonly'         => true,
					'required'         => true,
					'tabindex'         => true,
					'aria-describedby' => true,
					'aria-details'     => true,
					'aria-label'       => true,
					'aria-labelledby'  => true,
					'aria-hidden'      => true,
					'class'            => true,
					'id'               => true,
					'style'            => true,
					'title'            => true,
					'role'             => true,
					'data-*'           => true,
				],
			]
		);
	}

	/**
	 * Echo the textare after escaping
	 *
	 * @param string $textarea
	 * @return void
	 */
	public static function esc_textarea_e( $textarea ) {
		echo self::esc_textarea( $textarea ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Escape textare
	 *
	 * @param string $textarea
	 * @return string
	 */
	public static function esc_textarea( $textarea ) {
		return wp_kses(
			$textarea,
			[
				'textarea' => wp_kses_allowed_html( 'post' )['textarea'],
			]
		);
	}

}
