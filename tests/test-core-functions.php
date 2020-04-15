<?php
/**
 * Class CoreFunctionsTest
 *
 * @package Wp_Core
 */

use Shinobi_Works\WP_Core\Helper;

/**
 * Core functions test case.
 */
class CoreFunctionsTest extends WP_UnitTestCase {

	/**
	 * Trim multi byte.
	 */
	public function test_mb_trim() {
		// Expected string.
		$expected = 'こんにちは！';
		// Case1. Single byte space
		$actual = Helper::mb_trim( '    こんにちは！ ' );
		$this->assertSame( $expected, $actual );
		// Case2. Multi byte space
		$actual = Helper::mb_trim( '　こんにちは！　' );
		$this->assertSame( $expected, $actual );
	}

	/**
	 * For test get attributes test data.
	 *
	 * @return array
	 */
	public function for_test_get_attributes() {
		return [
			// Case1. Without old attributes.
			[
				// Actual.
				Helper::get_attributes(
					[
						'id'    => 'my_id',
						'class' => 'my_class',
					]
				),
				// Expected
				' id="my_id" class="my_class"',
			],
			// Case2. With old attributes.
			[
				// Actual.
				Helper::get_attributes(
					[
						'id'    => 'my_id',
						'class' => 'my_class',
					],
					'data-value="default"'
				),
				// Expected.
				'data-value="default" id="my_id" class="my_class"',
			],
		];
	}

	/**
	 * Get attributes from array.
	 *
	 * @dataProvider for_test_get_attributes
	 *
	 * @return void
	 */
	public function test_get_attributes( $value, $expected ) {
		$this->assertSame( $expected, $value );
	}

}
