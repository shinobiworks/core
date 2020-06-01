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
			$actual   = FormParts::input( $args['atts'] );
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
				'expected'     => "<textarea data-class=\"{$data_class}\"></textarea>",
				// Default.
				'atts'         => [],
				'entered_text' => '',
			],
			[
				'expected'     => "<textarea data-class=\"{$data_class}\" id=\"uniq_id\" class=\"common-class\">Entered text...</textarea>",
				// Array has multiple attributes.
				'atts'         => [
					'id'    => 'uniq_id',
					'class' => 'common-class',
				],
				'entered_text' => 'Entered text...',
			],
		];
		foreach ( $args_arr as $args ) {
			$actual = FormParts::textarea( $args['atts'], $args['entered_text'] );
			$this->assertSame( $args['expected'], $actual );
		}
	}

	/**
	 * Test selectbox()
	 */
	public function test_selectbox() {
		$data_class = FormParts::SHINOBI_FORM_PARTS . ' shinobi-selectbox';

		$args_arr = [
			[
				'name'             => 'test_select_1',
				'atts'             => [
					[
						'value' => 'test_value',
					],
				],
				'expected_options' => '<option value="test_value">test_value</option>',
			],
			[
				'name'             => 'test_select_2',
				'atts'             => [
					[
						'value' => '',
						'label' => 'Select data...',
					],
					[
						'value' => 'test_value_2',
					],
				],
				'expected_options' => '<option value="">Select data...</option><option value="test_value_2">test_value_2</option>',
			],
		];

		foreach ( $args_arr as $args ) {
			$name             = $args['name'];
			$expected_options = $args['expected_options'];
			$expected         = "<select name=\"{$name}\" data-class=\"{$data_class}\">{$expected_options}</select>";
			$actual           = FormParts::selectbox( $name, $args['atts'] );
			$this->assertSame( $expected, $actual );
		}
	}

}
