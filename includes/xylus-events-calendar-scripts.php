<?php
/**
 * Scripts
 *
 * @package     Xylus_Events_Calendar
 * @subpackage  Functions
 * @copyright   Copyright (c) 2025, Rajat Patel
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Load Admin Scripts
 *
 * Enqueues the required admin scripts.
 *
 * @since 1.0
 * @param string $hook Page hook.
 * @return void
 */
function xylusec_enqueue_admin_scripts( $hook ) {
	$page   = isset( $_GET['page'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$js_dir = XYLUSEC_PLUGIN_URL . 'assets/js/';

	if ( 'xt_events_calendar' === $page ) {
		wp_register_script( 'xylus-events-calendar-admin', $js_dir . 'xylus-events-calendar-admin.js', array( 'jquery', 'jquery-ui-core' ), XYLUSEC_VERSION, true );
		wp_enqueue_script( 'xylus-events-calendar-admin' );
	}
}




/**
 * Load Admin Styles.
 *
 * Enqueues the required admin styles.
 *
 * @since 1.0
 * @param string $hook Page hook.
 * @return void
 */
function xylusec_enqueue_admin_styles( $hook ) {

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$page    = isset( $_GET['page'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : '';
	$css_dir = XYLUSEC_PLUGIN_URL . 'assets/css/';

	if( 'xt_events_calendar' == $page ){
		wp_enqueue_style('xylus-events-calendar-admin-css', $css_dir . 'xylus-events-calendar-admin.css', false, XYLUSEC_VERSION );
	}
}

add_action( 'admin_enqueue_scripts', 'xylusec_enqueue_admin_scripts' );
add_action( 'admin_enqueue_scripts', 'xylusec_enqueue_admin_styles' );