<?php
/**
 * Form Parts Test
 */

use Shinobi_Works\Generate\FormParts;
use Shinobi_Works\WP\Utils;

/**
 * Form Parts Test Class
 */
class FormPartsTest extends WP_UnitTestCase {

	/**
	 * Test input()
	 */
	public function test_input() {
		$data_class = FormParts::SHINOBI_FORM_PARTS . ' shinobi-input';

		$args_arr = [
			[
				'expected' => "<input type=\"text\" data-class=\"{$data_class}\">",
				// Default.
				'atts'     => [],
			],
			[
				'expected' => "<input type=\"text\" data-class=\"{$data_class}\" id=\"uniq_id\" class=\"common-class\">",
				// Array has multiple attributes.
				'atts'     => [
					'id'    => 'uniq_id',
					'class' => 'common-class',
				],
			],
			[
				'expected' => "<input type=\"submit\" data-class=\"{$data_class}\">",
				// Array has duplicate attribute
				'atts'     => [
					'type' => 'submit',
				],
			],
		];
		foreach ( $args_arr as $args ) {
			$actual   = Utils::esc_input( FormParts::input( $args['atts'] ) );
			$expected = $args['expected'];
			$this->assertSame( $expected, $actual );
		}
	}

	/**
	 * Test textarea()
	 */
	public function test_textarea() {
		$data_class = FormParts::SHINOBI_FORM_PARTS . ' shinobi-textarea';

		$args_arr = [
			[
				'expected' => "<textarea data-class=\"{$data_class}\"></textarea>",
				// Default.
				'atts'     => [],
			],
			[
				'expected' => "<textarea data-class=\"{$data_class}\" id=\"uniq_id\" class=\"common-class\"></textarea>",
				// Array has multiple attributes.
				'atts'     => [
					'id'    => 'uniq_id',
					'class' => 'common-class',
				],
			],
		];
		foreach ( $args_arr as $args ) {
			$actual = Utils::esc_textarea( FormParts::textarea( $args['atts'] ) );
			$this->assertSame( $args['expected'], $actual );
		}
	}

}
