<?php
/**
 * Common functions class for Xylus Events Calendar
 *
 * @link       http://xylusthemes.com/
 * @since      1.0.0
 *
 * @package    Xylus_Events_Calendar
 * @subpackage Xylus_Events_Calendar/includes
 * @author     Rajat Patel <prajat21@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class XYLUSEC_Shortcode_List_Table extends WP_List_Table {

	public function prepare_items() {

		$columns 	= $this->get_columns();
		$hidden 	= $this->get_hidden_columns();
		$sortable 	= $this->get_sortable_columns();
		$data 		= $this->table_data();

		$perPage 		= 20;
		$currentPage 	= $this->get_pagenum();
		$totalItems 	= count( $data );

		$this->set_pagination_args( array(
			'total_items' => $totalItems,
			'per_page'    => $perPage
		) );

		$data = array_slice( $data, ( ( $currentPage-1 ) * $perPage ), $perPage );

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items = $data;
	}

	/**
	 * Override the parent columns method. Defines the columns to use in your listing table
	 *
	 * @return Array
	 */
	public function get_columns() {
		$columns = array(
			'id'            => __( 'ID', 'xylus-events-calendar' ),
			'how_to_use'    => __( 'Title', 'xylus-events-calendar' ),
			'shortcode'     => __( 'Shortcode', 'xylus-events-calendar' ),
			'action'    	=> __( 'Action', 'xylus-events-calendar' ),
		);

		return $columns;
	}

	/**
	 * Define which columns are hidden
	 *
	 * @return Array
	 */
	public function get_hidden_columns() {
		return array();
	}

	/**
	 * Get the table data
	 *
	 * @return Array
	 */
	private function table_data() {
		$data = array();

		$data[] = array(
					'id'            => 1,
					'how_to_use'    => 'Explore upcoming events in various layouts, including a calendar, grid, list, and more',
					'shortcode'     => '<p class="xylusec_short_code">[xylus_events_calendar]</p>',
					'action'     	=> '<button class="xylusec-btn-copy-shortcode button-primary"  data-value="[xylus_events_calendar]">Copy</button>',
					);
		return $data;
	}
	
	/**
	 * Define what data to show on each column of the table
	 *
	 * @param Array  $item Data
	 * @param String $column_name - Current column name
	 */
	public function column_default( $item, $column_name ){
		switch( $column_name ){
			case 'id':
			case 'how_to_use':
			case 'shortcode':
			case 'action':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
		}
	}
}