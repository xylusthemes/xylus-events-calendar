<?php
/**
 * Plugin Name:       Easy Events Calendar
 * Plugin URI:        https://xylusthemes.com/plugins/easy-events-calendar/
 * Description:       Display events from multiple sources in a unified calendar view. Easy Events Calendar supports events imported from Meetup, Eventbrite, Facebook, and WP Event Aggregator. Includes multiple calendar views, filtering, and responsive layouts.
 * Version:           1.0.0
 * Author:            Xylus Themes
 * Author URI:        https://xylusthemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       easy-events-calendar
 *
 *
 * @link       http://xylusthemes.com/
 * @since      1.0.0
 * @package    Easy_Events_Calendar
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Easy_Events_Calendar' ) ) :

	/**
	 * Main Easy Events Calendar class
	 */
	class Easy_Events_Calendar {

		/** Singleton *************************************************************/
		/**
		 * Easy_Events_Calendar The one true Easy_Events_Calendar.
		 */
		private static $instance;
		public $common, $xt_events_calendar, $admin, $ajax;

		/**
		 * Main Easy Events Calendar Instance.
		 *
		 * Insure that only one instance of Easy_Events_Calendar exists in memory at any one time.
		 * Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0.0
		 * @static object $instance
		 * @uses Easy_Events_Calendar::setup_constants() Setup the constants needed.
		 * @uses Easy_Events_Calendar::includes() Include the required files.
		 * @uses Easy_Events_Calendar::laod_textdomain() load the language files.
		 * @see xtec_xt_events_calendar()
		 * @return object| Easy Events Calendar the one true Easy Events Calendar.
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Easy_Events_Calendar ) ) {
				self::$instance = new Easy_Events_Calendar();
				self::$instance->setup_constants();

				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
				add_action( 'wp_enqueue_scripts', array( self::$instance, 'xtec_enqueue_style' ) );
				add_action( 'wp_enqueue_scripts', array( self::$instance, 'xtec_enqueue_script' ) );
				add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( self::$instance, 'xtec_setting_doc_links' ) );
				register_activation_hook( __FILE__, array( self::$instance, 'xtec_plugin_set_activation_flag' ) );

				self::$instance->includes();
				self::$instance->common     = new Easy_Events_Calendar_Common();
				self::$instance->admin      = new Easy_Events_Calendar_Admin();
				self::$instance->ajax       = new Easy_Events_Calendar_Ajax();

			}
			return self::$instance;
		}

		/** Magic Methods *********************************************************/

		/**
		 * A dummy constructor to prevent Easy_Events_Calendar from being loaded more than once.
		 *
		 * @since 1.0.0
		 * @see Easy_Events_Calendar::instance()
		 * @see xtec_xt_events_calendar()
		 */
		private function __construct() {
			/* Do nothing here */
		}

		/**
		 * A dummy magic method to prevent Easy_Events_Calendar from being cloned.
		 *
		 * @since 1.0.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'easy-events-calendar' ), '1.0.0' );
		}

		/**
		 * A dummy magic method to prevent Easy_Events_Calendar from being unserialized.
		 *
		 * @since 1.0.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'easy-events-calendar' ), '1.0.0' );
		}


		public function xtec_plugin_set_activation_flag() {
			update_option( 'xtec_plugin_activated', true );
		}


		/**
		 * Setup plugins constants.
		 *
		 * @access private
		 * @since 1.0.0
		 * @return void
		 */
		private function setup_constants() {

			// Plugin version.
			if ( ! defined( 'XTEC_VERSION' ) ) {
				define( 'XTEC_VERSION', '1.0.0' );
			}

			// Minimum Pro plugin version.
			if ( ! defined( 'XTEC_MIN_PRO_VERSION' ) ) {
				define( 'XTEC_MIN_PRO_VERSION', '1.7.2' );
			}

			// Plugin folder Path.
			if ( ! defined( 'XTEC_PLUGIN_DIR' ) ) {
				define( 'XTEC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin folder URL.
			if ( ! defined( 'XTEC_PLUGIN_URL' ) ) {
				define( 'XTEC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin root file.
			if ( ! defined( 'XTEC_PLUGIN_FILE' ) ) {
				define( 'XTEC_PLUGIN_FILE', __FILE__ );
			}

			// Options.
			if ( ! defined( 'XTEC_OPTIONS' ) ) {
				define( 'XTEC_OPTIONS', 'xtec_xt_event_calendar_options' );
			}

			// Pro plugin Buy now Link.
			if ( ! defined( 'XTEC_PLUGIN_BUY_NOW_URL' ) ) {
				define( 'XTEC_PLUGIN_BUY_NOW_URL', 'http://xylusthemes.com/plugins/easy-events-calendar/?utm_source=insideplugin&utm_medium=web&utm_content=sidebar&utm_campaign=freeplugin' );
			}
		}

		/**
		 * Include required files.
		 *
		 * @access private
		 * @since 1.0.0
		 * @return void
		 */
		private function includes() {
			require_once XTEC_PLUGIN_DIR . 'includes/easy-events-calendar-scripts.php';
			require_once XTEC_PLUGIN_DIR . 'includes/admin/class-easy-events-calendar-common.php';
			require_once XTEC_PLUGIN_DIR . 'includes/admin/class-easy-events-calendar-admin.php';
			require_once XTEC_PLUGIN_DIR . 'includes/admin/class-easy-events-calendar-ajax-function.php';
			require_once XTEC_PLUGIN_DIR . 'includes/admin/class-easy-events-calendar-list-table.php';
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function load_textdomain() {

			load_plugin_textdomain(
				'easy-events-calendar',
				false,
				basename( dirname( __FILE__ ) ) . '/languages'
			);

		}

		/**
		 * LF setting And docs link add in plugin page.
		 *
		 * @since 1.0
		 * @return void
		 */
		public function xtec_setting_doc_links( $links ) {
			$xtec_setting_doc_link = array(
				'lf-event-setting' => sprintf(
					'<a href="%s">%s</a>',
					esc_url( admin_url( 'admin.php?page=xt_events_calendar' ) ),
					esc_html__( 'Setting', 'easy-events-calendar' )
				),
				'lf-event-docs' => sprintf(
					'<a target="_blank" href="%s">%s</a>',
					esc_url( 'https://docs.xylusthemes.com/docs/easy-events-calendar/' ),
					esc_html__( 'Docs', 'easy-events-calendar' )
				),
			);
			return array_merge( $links, $xtec_setting_doc_link );
		}

		/**
		 * Enqueue style front-end
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function xtec_enqueue_style() {

			$css_dir = XTEC_PLUGIN_URL . 'assets/css/';
			wp_enqueue_style('easy-events-calendar-css', $css_dir . 'easy-events-calendar.css', false, XTEC_VERSION );
		}

		/**
		 * Enqueue script front-end
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function xtec_enqueue_script() {

			$js_dir = XTEC_PLUGIN_URL . 'assets/js/';
			
			wp_enqueue_script( 'moment' );
			wp_enqueue_script( 'fullcalendar-js', plugins_url( 'assets/js/easy-events-calendar-fullcalendar.global.min.js', __FILE__ ), array( 'jquery', 'moment' ), '6.1.17', true );
			wp_register_script(  'easy-events-calendar',  $js_dir . 'easy-events-calendar.js',  array( 'jquery', 'jquery-ui-core', 'fullcalendar-js' ),  XTEC_VERSION,  true  );
			wp_enqueue_script( 'easy-events-calendar' );

			wp_localize_script( 'easy-events-calendar', 'xtec_ajax', array(
				'ajaxurl'      => admin_url( 'admin-ajax.php' ),
				'nonce'        => wp_create_nonce( 'xtec_nonce' ),
				'xtec_options' => get_option( XTEC_OPTIONS, [] )
			) );
		}

	}

endif; // End If class exists check.

/**
 * The main function for that returns Easy_Events_Calendar
 *
 * The main function responsible for returning the one true Easy_Events_Calendar
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $xt_events_calendar = xtec_xt_events_calendar(); ?>
 *
 * @since 1.0.0
 * @return object|Easy_Events_Calendar The one true Easy_Events_Calendar Instance.
 */
function xtec_xt_events_calendar() {
	return Easy_Events_Calendar::instance();
}

/**
 * Get Import events setting options
 *
 * @since 1.0
 * @param string $type Option type.
 * @return array|bool Options.
 */
function xtec_get_options() {
	$xtec_options = get_option( XTEC_OPTIONS );
	return $xtec_options;
}

// Get Easy_Events_Calendar Running.
global $xtec_errors, $xtec_success_msg, $xtec_warnings, $xtec_info_msg;
$xt_events_calendar = xtec_xt_events_calendar();
$xtec_errors = $xtec_warnings = $xtec_success_msg = $xtec_info_msg = array();

