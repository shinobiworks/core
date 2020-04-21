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

	private $test_user_name = 'Shinobi Works';
	private $test_comment   = 'My First Comment';

	/**
	 * After test, delete table
	 *
	 * @return void
	 */
	public static function tearDownAfterClass(): void {
		// DB::drop_table( self::TABLE_NAME );
	}


	/**
	 * Setup
	 */
	public function setUp() {
	}

	/**
	 * Test check tables
	 *
	 * @doesNotPerformAssertions
	 */
	public function test_check_tables() {
		return;
		// To check list of test database table, use below command.
		global $wpdb;
		var_dump( $wpdb->get_results( 'SHOW TABLES' ) );
		$this->assertTrue( true );
	}

	/**
	 * Test creat_table()
	 */
	public function test_create_table() {
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
	 *
	 * @depends test_create_table
	 */
	public function test_is_table_exists() {
		$result = DB::is_table_exists( self::TABLE_NAME );
		$this->assertTrue( $result );
	}

	/**
	 * Test insert()
	 *
	 * @depends test_create_table
	 */
	public function test_insert() {
		$data = [
			'user_name' => $this->test_user_name,
			'comment'   => $this->test_comment,
		];
		$this->assertTrue( DB::insert( self::TABLE_NAME, $data ) );
	}

	/**
	 * Test get_results()
	 *
	 * @depends test_create_table
	 * @depends test_insert
	 */
	public function test_get_results() {
		/**
		 * Get results
		 */
		$get_results = DB::get_results( self::TABLE_NAME )[0];
		$this->assertSame(
			$this->test_user_name,
			$get_results->user_name
		);
	}

	/**
	 * Test get_results() for default database
	 */
	public function test_get_results_default() {
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

	/**
	 * Test get_row_by_id()
	 *
	 * @depends test_create_table
	 * @depends test_insert
	 */
	public function test_get_row_by_id() {
		$get_row_by_id = DB::get_row_by_id( self::TABLE_NAME, 1 );
		$this->assertSame(
			$this->test_comment,
			$get_row_by_id->comment
		);
	}

	/**
	 * Test get_row()
	 *
	 * @depends test_create_table
	 * @depends test_insert
	 */
	public function test_get_row() {
		$where   = [
			'user_name' => $this->test_user_name,
		];
		$results = DB::get_row( self::TABLE_NAME, $where );
		$this->assertSame( $this->test_user_name, $results->user_name );
	}


	/**
	 * Test get_row() for default database
	 */
	public function test_get_row_default() {
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

	/**
	 * Test update()
	 *
	 * @depends test_create_table
	 */
	public function test_update() {
		$this->test_user_name = 'Shinobi Works 2.0';
		$this->test_comment   = 'My Second Comment';
		$update_data          = [
			'user_name' => $this->test_user_name,
			'comment'   => $this->test_comment,
		];
		$where                = [
			'ID' => 1,
		];
		$update_result        = DB::update( self::TABLE_NAME, $update_data, $where );
		$this->assertSame( 1, $update_result );
	}

	/**
	 * Test drop_table()
	 */
	public function test_drop_table() {
		$this->assertTrue( DB::drop_table( self::TABLE_NAME ) );
	}

}
