<?php
/**
 * Class for managing the events list table in WordPress admin.
 *
 * @link       http://xylusthemes.com/
 * @since      1.0.3
 *
 * @package    Xylus_Events_Calendar
 * @subpackage Xylus_Events_Calendar/includes/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xylus_Events_Calendar_Events_List {

	/**
	 * Initialize the class and set its hooks.
	 */
	public function __construct() {
		$post_type = 'eec_events';

		// Add custom columns
		add_filter( "manage_{$post_type}_posts_columns", array( $this, 'add_custom_columns' ) );
		
		// Render custom column content
		add_action( "manage_{$post_type}_posts_custom_column", array( $this, 'render_custom_column_content' ), 10, 2 );

		// Make columns sortable
		add_filter( "manage_edit-{$post_type}_sortable_columns", array( $this, 'make_columns_sortable' ) );

		// Handle custom sorting logic
		add_action( 'pre_get_posts', array( $this, 'handle_custom_sorting' ) );

		// Hide comments column by default
		add_filter( 'default_hidden_columns', array( $this, 'hide_comments_column_by_default' ), 10, 2 );
	}

	/**
	 * Add custom columns to the events list table.
	 *
	 * @param array $columns Existing columns.
	 * @return array Modified columns.
	 */
	public function add_custom_columns( $columns ) {
		$new_columns = array();
		
		foreach ( $columns as $key => $column ) {
			if ( 'date' === $key ) {
				$new_columns['event_date'] = __( 'Event Date', 'xylus-events-calendar' );
			}
			$new_columns[$key] = $column;
		}

		return $new_columns;
	}

	/**
	 * Render the content for our custom columns.
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 */
	public function render_custom_column_content( $column, $post_id ) {
		if ( 'event_date' === $column ) {
			$start_date = get_post_meta( $post_id, 'event_start_date', true );
			$end_date   = get_post_meta( $post_id, 'event_end_date', true );

			if ( $start_date ) {
				$output = date_i18n( get_option( 'date_format' ), strtotime( $start_date ) );
				if ( $end_date && $end_date !== $start_date ) {
					$output .= ' - ' . date_i18n( get_option( 'date_format' ), strtotime( $end_date ) );
				}
				echo esc_html( $output );
			} else {
				echo '<span class="na">&mdash;</span>';
			}
		}
	}

	/**
	 * Define which columns are sortable.
	 *
	 * @param array $columns Sortable columns.
	 * @return array Modified sortable columns.
	 */
	public function make_columns_sortable( $columns ) {
		$columns['event_date'] = 'event_date';
		return $columns;
	}

	/**
	 * Handle custom sorting logic for the event date column.
	 *
	 * @param WP_Query $query The current query.
	 */
	public function handle_custom_sorting( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() || 'eec_events' !== $query->get( 'post_type' ) ) {
			return;
		}

		$orderby = $query->get( 'orderby' );

		if ( 'event_date' === $orderby ) {
			$query->set( 'meta_key', 'start_ts' );
			$query->set( 'orderby', 'meta_value_num' );
		}
	}

	/**
	 * Hide the comments column by default for the events list.
	 *
	 * @param array     $hidden List of hidden columns.
	 * @param WP_Screen $screen Current screen object.
	 * @return array Modified list of hidden columns.
	 */
	public function hide_comments_column_by_default( $hidden, $screen ) {
		if ( 'edit-eec_events' === $screen->id ) {
			if ( ! in_array( 'comments', $hidden, true ) ) {
				$hidden[] = 'comments';
			}
		}
		return $hidden;
	}
}
