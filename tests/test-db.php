<?php
/**
 * Class DB_Test
 */

use Shinobi_Works\WP\DB;

/**
 * DB test case.
 */
class DB_Test extends WP_UnitTestCase {

	const TABLE_NAME = 'my_awesome_table';

	/**
	 * Test creat_table()
	 */
	public function test_create_table() {
		/**
		 * To check the status of the database, I temporaray remove the "_create_temporary_tables" filter as you can see.
		 * Of course, when I run phpunit, I have to refresh the database.
		 *
		 * @link https://wordpress.stackexchange.com/questions/94954/plugin-development-with-unit-tests
		 */
		remove_filter( 'query', [ $this, '_create_temporary_tables' ] );

		$version = '1.0.0';
		$sql     = '
		ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		user_name varchar(255) NOT NULL,
		comment varchar(1000) NOT NULL,
		PRIMARY KEY  id (ID)
		';
		$result  = DB::create_table( $version, self::TABLE_NAME, $sql );
		$this->assertTrue( $result );
	}

	/**
	 * Test is_table_exists()
	 */
	public function test_is_table_exists() {
		$result = DB::is_table_exists( self::TABLE_NAME );
		$this->assertTrue( $result );
		// To check list of test database table, use below command.
		// global $wpdb;
		// var_dump( $wpdb->get_results( 'SHOW TABLES' ) );
		// $this->assertTrue( true );
	}

	/**
	 * Test to get from insert
	 */
	public function test_to_get_from_insert() {
		$user_name = 'Shinobi Works';
		$comment   = 'My First Comment';
		$data      = [
			'user_name' => $user_name,
			'comment'   => $comment,
		];
		/**
		 * Insert
		 */
		$this->assertTrue( DB::insert( self::TABLE_NAME, $data ) );
		/**
		 * Get results
		 */
		$get_results = DB::get_results( self::TABLE_NAME )[0];
		$this->assertSame(
			$user_name,
			$get_results->user_name
		);
		/**
		 * Get row of database by id
		 */
		for ( $i = 1; $i < 100; $i++ ) {
			$get_row_by_id = DB::get_row_by_id( self::TABLE_NAME, $i );
			if ( $get_row_by_id ) {
				$this->assertSame(
					$comment,
					$get_row_by_id->comment
				);
				break;
			}
		}
	}

	/**
	 * Test get_results()
	 */
	public function test_get_results() {
		/**
		 * Object
		 */
		$results = DB::get_results( 'users' )[0];
		// ID
		$expected = '1';
		$actual   = $results->ID;
		$this->assertSame( $expected, $actual );
		// Email
		$expected = 'admin@example.org';
		$actual   = $results->user_email;
		$this->assertSame( $expected, $actual );
		/**
		 * Array of usermeta table
		 */
		$results = DB::get_results( 'usermeta', ARRAY_A )[0];
		// Meta key
		$expected = 'nickname';
		$actual   = $results['meta_key'];
		$this->assertSame( $expected, $actual );
		// Meta value
		$expected = 'admin';
		$actual   = $results['meta_value'];
		$this->assertSame( $expected, $actual );
	}

	public function test_get_row() {
		/**
		 * Case.1 by "users table"
		 */
		$user_nicename  = 'admin';
		$results_case_1 = DB::get_row(
			'users',
			[
				'user_nicename' => $user_nicename,
			]
		);
		$this->assertSame( $user_nicename, $results_case_1->user_nicename );
		/**
		 * Case.2 by "options table"
		 */
		$results_case_2 = DB::get_row(
			'options',
			[
				'option_name' => 'admin_email',
			]
		);
		$this->assertSame( $results_case_2->option_value, 'admin@example.org' );
	}

}
