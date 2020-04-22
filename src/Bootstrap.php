<?php

namespace Shinobi_Works\WP;

class Bootstrap extends DB {

	public static function init() {
		add_action( 'init', [ __CLASS__, 'create_options_table' ] );
	}

	/**
	 * Create "shinobi_options" table
	 *
	 * @return void
	 */
	public static function create_options_table() {
		$version = '1.0.1';
		$table   = parent::OPTIONS_TABLE;
		$sql     = '
		ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		option_name varchar(191) NOT NULL,
		option_value longtext NOT NULL,
		PRIMARY KEY  id (ID)
		';
		parent::create_table( $version, $table, $sql );
	}

}

Bootstrap::init();
