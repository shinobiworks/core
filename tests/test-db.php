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
	 * Test creat table
	 *
	 * @return void
	 */
	public function test_create_table() {
		/**
		 * To check the status of the database, I temporaray remove the "_create_temporary_tables" filter as you can see.
		 * Of course, when I run phpunit, I have to refresh the database.
		 *
		 * @link https://wordpress.stackexchange.com/questions/94954/plugin-development-with-unit-tests
		 */
		remove_filter( 'query', array( $this, '_create_temporary_tables' ) );

		$version = '1.0.0';
		$sql     = '
		ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		option_name varchar(191) NOT NULL,
		option_value longtext NOT NULL,
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

}
