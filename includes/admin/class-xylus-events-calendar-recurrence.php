<?php
/**
 * Recurrence logic handler for Xylus Events Calendar
 *
 * @package    Xylus_Events_Calendar
 * @subpackage Xylus_Events_Calendar/includes/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xylus_Events_Calendar_Recurrence {

	/**
	 * Table name for event instances
	 */
	private $table_name;

	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'eec_event_instances';
		
		// Create table if it doesn't exist
		if ( is_admin() && get_option( 'xylusec_db_version' ) != XYLUSEC_VERSION ) {
			$this->create_instances_table();
			update_option( 'xylusec_db_version', XYLUSEC_VERSION );
		}
	}

	/**
	 * Create the event instances table
	 */
	public function create_instances_table() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $this->table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			event_id bigint(20) NOT NULL,
			start_date datetime NOT NULL,
			end_date datetime NOT NULL,
			is_recurrence tinyint(1) DEFAULT 0 NOT NULL,
			PRIMARY KEY  (id),
			KEY event_id (event_id),
			KEY start_date (start_date),
			KEY end_date (end_date)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Synchronize instances for a specific event
	 * 
	 * @param int $event_id
	 */
	public function sync_event_instances( $event_id ) {
		global $wpdb;

		// Clear existing instances
		$wpdb->delete( $this->table_name, array( 'event_id' => $event_id ), array( '%d' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

		$recurrence_type = get_post_meta( $event_id, 'event_recurrence_type', true );
		
		// If no recurrence, just add the base instance
		if ( empty( $recurrence_type ) || $recurrence_type === 'none' ) {
			$this->add_base_instance( $event_id );
			return;
		}

		// Generate and add recurring instances
		$instances = $this->generate_instances( $event_id );
		foreach ( $instances as $instance ) {
			$wpdb->insert(  // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$this->table_name, 
				array(
					'event_id'      => $event_id,
					'start_date'    => $instance['start'],
					'end_date'      => $instance['end'],
					'is_recurrence' => $instance['is_recurrence']
				),
				array( '%d', '%s', '%s', '%d' )
			);
		}
	}

	/**
	 * Add the base instance (non-recurring)
	 */
	private function add_base_instance( $event_id ) {
		global $wpdb;
		
		$start_date = get_post_meta( $event_id, 'event_start_date', true );
		$start_hour = get_post_meta( $event_id, 'event_start_hour', true );
		$start_min  = get_post_meta( $event_id, 'event_start_minute', true );
		$start_mer  = get_post_meta( $event_id, 'event_start_meridian', true );

		$end_date = get_post_meta( $event_id, 'event_end_date', true );
		$end_hour = get_post_meta( $event_id, 'event_end_hour', true );
		$end_min  = get_post_meta( $event_id, 'event_end_minute', true );
		$end_mer  = get_post_meta( $event_id, 'event_end_meridian', true );

		$start_dt = gmdate( 'Y-m-d H:i:s', strtotime( "$start_date $start_hour:$start_min $start_mer" ) );
		$end_dt   = gmdate( 'Y-m-d H:i:s', strtotime( "$end_date $end_hour:$end_min $end_mer" ) );

		$wpdb->insert(  // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$this->table_name, 
			array(
				'event_id'      => $event_id,
				'start_date'    => $start_dt,
				'end_date'      => $end_dt,
				'is_recurrence' => 0
			),
			array( '%d', '%s', '%s', '%d' )
		);
	}

	/**
	 * Generate instances based on recurrence rules
	 */
	private function generate_instances( $event_id ) {
		$type      = get_post_meta( $event_id, 'event_recurrence_type', true );
		$interval  = (int) get_post_meta( $event_id, 'event_recurrence_interval', true );
		$end_type  = get_post_meta( $event_id, 'event_recurrence_end_type', true );
		$end_date  = get_post_meta( $event_id, 'event_recurrence_end_date', true );
		$end_count = (int) get_post_meta( $event_id, 'event_recurrence_end_count', true );
		$weekly_days = get_post_meta( $event_id, 'event_recurrence_weekly_days', true ); // Array

		if ( $interval < 1 ) $interval = 1;

		$start_date_str = get_post_meta( $event_id, 'event_start_date', true );
		$start_hour     = get_post_meta( $event_id, 'event_start_hour', true );
		$start_min      = get_post_meta( $event_id, 'event_start_minute', true );
		$start_mer      = get_post_meta( $event_id, 'event_start_meridian', true );
		$base_start_ts  = strtotime( "$start_date_str $start_hour:$start_min $start_mer" );

		$end_date_str   = get_post_meta( $event_id, 'event_end_date', true );
		$end_hour       = get_post_meta( $event_id, 'event_end_hour', true );
		$end_min        = get_post_meta( $event_id, 'event_end_minute', true );
		$end_mer        = get_post_meta( $event_id, 'event_end_meridian', true );
		$base_end_ts    = strtotime( "$end_date_str $end_hour:$end_min $end_mer" );

		$duration = $base_end_ts - $base_start_ts;

		$instances = array();
		
		// Add first instance
		$instances[] = array(
			'start' => gmdate( 'Y-m-d H:i:s', $base_start_ts ),
			'end'   => gmdate( 'Y-m-d H:i:s', $base_end_ts ),
			'is_recurrence' => 0
		);

		$current_start = $base_start_ts;
		$count = 1;
		$max_instances = 100; // Safety limit
		$limit_date = strtotime( '+2 years' );

		if ( $end_type === 'date' && !empty( $end_date ) ) {
			$limit_date = strtotime( $end_date . ' 23:59:59' );
		}

		while ( $count < $max_instances ) {
			if ( $end_type === 'count' && $count >= $end_count ) break;
			
			switch ( $type ) {
				case 'daily':
					$current_start = strtotime( "+$interval day", $current_start );
					break;
				case 'weekly':
					if ( ! empty( $weekly_days ) && is_array( $weekly_days ) ) {
						$day_map = array(
							'SU' => 0, 'MO' => 1, 'TU' => 2, 'WE' => 3, 'TH' => 4, 'FR' => 5, 'SA' => 6
						);
						
						$selected_day_nums = array();
						foreach ( $weekly_days as $day_code ) {
							if ( isset( $day_map[$day_code] ) ) {
								$selected_day_nums[] = $day_map[$day_code];
							}
						}

						// Current week start (Sunday)
						$week_start = strtotime( "Sunday last week", $current_start + 86400 ); // +1 day handle if today is Sunday
						
						// Iterate through the week
						for ( $i = 0; $i < 7; $i++ ) {
							$day_ts = strtotime( "+$i day", $week_start );
							$day_num = (int) gmdate( 'w', $day_ts );

							if ( in_array( $day_num, $selected_day_nums ) ) {
								// Only add if it's after the base start and not already added (the base instance is index 0)
								if ( $day_ts > $base_start_ts ) {
									if ( $end_type === 'date' && $day_ts > $limit_date ) break 2;
									if ( $end_type === 'count' && $count >= $end_count ) break 2;
									
									$instances[] = array(
										'start' => gmdate( 'Y-m-d H:i:s', $day_ts ),
										'end'   => gmdate( 'Y-m-d H:i:s', $day_ts + $duration ),
										'is_recurrence' => 1
									);
									$count++;
								}
							}
						}
						// Jump to next week based on interval
						$current_start = strtotime( "+$interval week", $current_start );
					} else {
						$current_start = strtotime( "+$interval week", $current_start );
						if ( $current_start > $limit_date ) break;
						
						$instances[] = array(
							'start' => gmdate( 'Y-m-d H:i:s', $current_start ),
							'end'   => gmdate( 'Y-m-d H:i:s', $current_start + $duration ),
							'is_recurrence' => 1
						);
						$count++;
					}
					continue 2; // Jump to next iteration of the while loop
				case 'monthly':
					$current_start = strtotime( "+$interval month", $current_start );
					break;
				case 'yearly':
					$current_start = strtotime( "+$interval year", $current_start );
					break;
				default:
					return $instances;
			}

			if ( $current_start > $limit_date ) break;

			$instances[] = array(
				'start' => gmdate( 'Y-m-d H:i:s', $current_start ),
				'end'   => gmdate( 'Y-m-d H:i:s', $current_start + $duration ),
				'is_recurrence' => 1
			);
			$count++;
		}

		return $instances;
	}

	/**
	 * Delete all instances for a specific event
	 *
	 * @param int $event_id Post ID.
	 */
	public function delete_event_instances( $event_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'eec_event_instances';
		$wpdb->delete( $table_name, array( 'event_id' => $event_id ), array( '%d' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	}
}
