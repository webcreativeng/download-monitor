<?php

/**
 * Class DLM_Test_WP_DB_Helper
 *
 * This class should ONLY be used in unit tests!!
 */
class DLM_Test_WP_DB_Helper {

	/**
	 * Flush table
	 *
	 * @param string $table
	 */
	public static function truncate( $table ) {
		global $wpdb;
		$table = esc_sql( $table );
		$wpdb->query( "TRUNCATE TABLE {$table};" );
	}

}