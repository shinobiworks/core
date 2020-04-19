<?php
/**
 * Database Functions
 *
 * @category   Functions for WordPress
 * @package    Shinobi_Works\WP
 * @author     Shinobi Works <support@shinobiworks.com>
 * @license    https://www.gnu.org/licenses/gpl-3.0.html/ GPL v3 or later
 * @link       https://shinobiworks.com
 * @since      0.1.1
 */

namespace Shinobi_Works\WP;

class DB {

	/**
	 * Check Table Exists
	 *
	 * @param string $name table name.
	 * @return boolean
	 */
	public static function is_table_exists( $name ) {
		$flag = true;
		global $wpdb;
		$table = $wpdb->get_var(
			$wpdb->prepare(
				'SHOW TABLES LIKE %s',
				$name
			)
		);
		if ( is_null( $table ) ) {
			$flag = false;
		}
		return $flag;
	}

	/**
	 * Create DB Table
	 *
	 * @param string $version current table version.
	 * @param string $name table name.
	 * @param string $sql sql.
	 * @return boolean
	 */
	public static function create_table( $version = '1.0.0', $name = '', $sql = '' ) {
		if ( '' === $name || '' === $sql ) {
			return false;
		}
		$_installed_ver = \get_option( "{$name}_table_ver" );
		$_is_table      = self::is_table_exists( $name );
		if ( $_is_table && $version === $_installed_ver ) {
			return true; // This table already exists and same version.
		}
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		include_once \ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( "CREATE TABLE $name ( $sql ) $charset_collate;" );
		\update_option( $name, $version );
		return true;
	}

	/**
	 * Get Results
	 *
	 * @param string $table  table name.
	 * @param int    $format database format.
	 *
	 * @return array
	 */
	function get_all_results( $table, $format = OBJECT ) {
		global $wpdb;
		$_transient = "{$table}_" . mb_strtolower( $format );
		$_record    = get_transient( $_transient );
		if ( false === $_record ) {
			$_record = $wpdb->get_results(
			// @codingStandardsIgnoreStart
			"SELECT * FROM $table",
			// @codingStandardsIgnoreEnd
				$format
			);
			if ( ! $_record ) {
				return false;
			}
			set_transient( $_transient, $_record );
		}
		return $_record;
	}

	/**
	 * Get Row
	 *
	 * @param string $table is table name.
	 * @param int    $id    is id.
	 *
	 * @return object
	 */
	function get_row_by_id( $table, $id ) {
		$_all_results = get_all_results( $table );
		if ( false === $_all_results ) {
			return false;
		}
		foreach ( $_all_results as $index => $result ) {
			if ( (int) $id === (int) $result->ID ) {
				return $result;
			}
		}
		return false;
	}

	/**
	 * Get row by array from wpdb
	 *
	 * @param string $table is table name.
	 * @param array  $where is array.
	 * @return void|boolean
	 */
	function get_row( $table, $where = array() ) {
		if ( ! $where || ! is_array( $where ) ) {
			return false;
		}
		$_all_results = get_all_results( $table );
		if ( false === $_all_results ) {
			return false;
		}
		$_key   = array_keys( $where )[0];
		$_value = array_values( $where )[0];
		foreach ( $_all_results as $index => $row ) {
			if ( isset( $row->$_key ) && $row->$_key === $_value ) {
				return $row;
			}
		}
		return false;
	}

	/**
	 * Insert
	 *
	 * @param string $table  is table name.
	 * @param array  $data   is array.
	 * @param string $format is format style.
	 *
	 * @return boolean
	 */
	function insert( $table, $data, $format = null ) {
		global $wpdb;
		$wpdb->hide_errors();
		$_result = $wpdb->insert( $table, $data, $format );
		delete_shinobi_transient( $table );
		if ( 1 === $_result ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Update
	 *
	 * @param string $table        is table name.
	 * @param array  $data         is array.
	 * @param string $where        is string.
	 * @param string $format       is format style.
	 * @param string $where_format is where format style.
	 *
	 * @return void
	 */
	function update( $table, $data, $where, $format = null, $where_format = array( '%d' ) ) {
		global $wpdb;
		$_result = $wpdb->update( $table, $data, $where, $format, $where_format );
		delete_shinobi_transient( $table );
		return $_result;
	}

	/**
	 * Delete
	 *
	 * @param string $table        is table name.
	 * @param string $where        is string.
	 * @param string $where_format is where format style.
	 *
	 * @return void
	 */
	function delete( $table, $where, $where_format = null ) {
		global $wpdb;
		$wpdb->delete( $table, $where, $where_format );
		delete_shinobi_transient( $table );
	}

	/**
	 * Delete Transient
	 *
	 * @param string $table is table name.
	 * @return void
	 */
	function delete_shinobi_transient( $table ) {
		$_format_arr = array(
			'array_a',
			'object',
		);
		foreach ( $_format_arr as $format ) {
			\delete_transient( "{$table}_{$format}" );
		}
	}

	/**
	 * Create options table in wpdb
	 *
	 * @return void
	 */
	add_action(
		'init',
		function() {
			$_sql = '
		ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		option_name varchar(191) NOT NULL,
		option_value longtext NOT NULL,
		PRIMARY KEY  id (ID)
            ';
			create_table( '1.0.0', SHINOBI_OPTIONS_TABLE, $_sql );
		}
	);

	/**
	 * Get option value from shinobi options table
	 *
	 * @param string $option_name option name.
	 * @param string $default_value default option value.
	 * @return boolean|string
	 */
	function get_option( $option_name, $default_value = false ) {
		$_transient = "shinobi_reviews_{$option_name}";
		$_record    = \get_transient( $_transient );
		if ( false === $_record ) {
			$_flag        = true;
			$_options_arr = get_all_results( SHINOBI_OPTIONS_TABLE );
			if ( ! $_options_arr || ! is_array( $_options_arr ) ) {
				return false;
			}
			foreach ( $_options_arr as $index => $options ) {
				if ( $option_name === $options->option_name ) {
					$_record = $options->option_value;
					$_flag   = true;
					break;
				} else {
					$_flag = false;
				}
			}
			if ( $_flag ) {
				set_transient( $_transient, $_record );
			} else {
				return $default_value;
			}
		}
		return is_serialized( $_record ) ? unserialize( $_record ) : $_record;
	}

	/**
	 * Update option value in shinobi options table
	 *
	 * @param string $option_name is option name.
	 * @param string $option_value is option value.
	 */
	function update_option( $option_name, $option_value ) {
		if ( $option_value && is_array( $option_value ) ) {
			$option_value = serialize( $option_value );
		}
		$option_name = trim( $option_name );
		$_row        = get_row( SHINOBI_OPTIONS_TABLE, array( 'option_name' => $option_name ) );
		if ( $_row ) {
			update(
				SHINOBI_OPTIONS_TABLE,
				array( 'option_value' => $option_value ),
				array( 'ID' => $_row->ID )
			);
		} else {
			insert(
				SHINOBI_OPTIONS_TABLE,
				array(
					'option_name'  => $option_name,
					'option_value' => $option_value,
				)
			);
		}
		\delete_transient( "shinobi_reviews_{$option_name}" );
	}

}
