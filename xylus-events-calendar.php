<?php
/**
 * Plugin Name:       Easy Events Calendar : All-in-One Events Calendar with Social Event, Eventbrite, Meetup, Google & iCal Import Support
 * Plugin URI:        https://xylusthemes.com/plugins/xylus-events-calendar/
 * Description:       Display events from multiple sources in a unified calendar view. Easy Events Calendar supports events imported from Meetup, Eventbrite, Facebook, and WP Event Aggregator. Includes multiple calendar views, filtering, and responsive layouts.
 * Version:           1.1.1
 * Author:            Xylus Themes
 * Author URI:        https://xylusthemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       xylus-events-calendar
 *
 *
 * @link       http://xylusthemes.com/
 * @since      1.0.0
 * @package    Xylus_Events_Calendar
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Xylus_Events_Calendar' ) ) :

	/**
	 * Main Xylus Events Calendar class
	 */
	class Xylus_Events_Calendar {

		/** Singleton *************************************************************/
		/**
		 * Xylus_Events_Calendar The one true Xylus_Events_Calendar.
		 */
		private static $instance;
		public $common, $xylusec_events_calendar, $admin, $ajax_handler, $widgets, $cpt, $template_loader, $recurrence, $events_list, $deactivation, $ical;

		/**
		 * Main Xylus Events Calendar Instance.
		 *
		 * Insure that only one instance of Xylus_Events_Calendar exists in memory at any one time.
		 * Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0.0
		 * @static object $instance
		 * @uses Xylus_Events_Calendar::setup_constants() Setup the constants needed.
		 * @uses Xylus_Events_Calendar::includes() Include the required files.
		 * @uses Xylus_Events_Calendar::laod_textdomain() load the language files.
		 * @see xylusec_xt_events_calendar()
		 * @return object| Xylus Events Calendar the one true Xylus Events Calendar.
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Xylus_Events_Calendar ) ) {
				self::$instance = new Xylus_Events_Calendar();
				self::$instance->setup_constants();

				add_action( 'wp_enqueue_scripts', array( self::$instance, 'xylusec_enqueue_style' ) );
				add_action( 'wp_enqueue_scripts', array( self::$instance, 'xylusec_enqueue_script' ) );
				add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( self::$instance, 'xylusec_setting_doc_links' ) );

				self::$instance->includes();
				self::$instance->common          = new Xylus_Events_Calendar_Common();
				self::$instance->cpt             = new Xylus_Events_Calendar_CPT();
				self::$instance->admin           = new Xylus_Events_Calendar_Admin();
				self::$instance->ajax_handler    = new Xylus_Events_Calendar_Ajax_Handler();
				self::$instance->widgets         = new Easy_Events_Calendar_Widgets();
				self::$instance->template_loader = new Xylus_Events_Calendar_Template_Loader();
				self::$instance->recurrence      = new Xylus_Events_Calendar_Recurrence();
				self::$instance->events_list     = new Xylus_Events_Calendar_Events_List();
				self::$instance->deactivation  = new Xylus_Events_Calendar_Deactivation();
				self::$instance->ical          = new Xylus_Events_Calendar_iCal();

			}
			return self::$instance;
		}

		/** Magic Methods *********************************************************/

		/**
		 * A dummy constructor to prevent Xylus_Events_Calendar from being loaded more than once.
		 *
		 * @since 1.0.0
		 * @see Xylus_Events_Calendar::instance()
		 * @see xylusec_xt_events_calendar()
		 */
		private function __construct() {
			/* Do nothing here */
		}

		/**
		 * A dummy magic method to prevent Xylus_Events_Calendar from being cloned.
		 *
		 * @since 1.0.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'xylus-events-calendar' ), '1.1.1' );
		}

		/**
		 * A dummy magic method to prevent Xylus_Events_Calendar from being unserialized.
		 *
		 * @since 1.0.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'xylus-events-calendar' ), '1.1.1' );
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
			if ( ! defined( 'XYLUSEC_VERSION' ) ) {
				define( 'XYLUSEC_VERSION', '1.1.1' );
			}

			// Plugin folder Path.
			if ( ! defined( 'XYLUSEC_PLUGIN_DIR' ) ) {
				define( 'XYLUSEC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin folder URL.
			if ( ! defined( 'XYLUSEC_PLUGIN_URL' ) ) {
				define( 'XYLUSEC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin root file.
			if ( ! defined( 'XYLUSEC_PLUGIN_FILE' ) ) {
				define( 'XYLUSEC_PLUGIN_FILE', __FILE__ );
			}

			// General Options.
			if ( ! defined( 'XYLUSEC_OPTIONS' ) ) {
				define( 'XYLUSEC_OPTIONS', 'xylusec_xt_event_calendar_options' );
			}

			// Widget Options.
			if ( ! defined( 'XYLUSEC_WIDGET_OPTIONS' ) ) {
				define( 'XYLUSEC_WIDGET_OPTIONS', 'xylusec_xtec_widget_options' );
			}

			// Pro plugin Buy now Link.
			if ( ! defined( 'XYLUSEC_PLUGIN_BUY_NOW_URL' ) ) {
				define( 'XYLUSEC_PLUGIN_BUY_NOW_URL', 'http://xylusthemes.com/plugins/xylus-events-calendar/?utm_source=insideplugin&utm_medium=web&utm_content=sidebar&utm_campaign=freeplugin' );
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
			require_once XYLUSEC_PLUGIN_DIR . 'includes/xylus-events-calendar-scripts.php';
			require_once XYLUSEC_PLUGIN_DIR . 'includes/admin/class-xylus-events-calendar-common.php';
			require_once XYLUSEC_PLUGIN_DIR . 'includes/admin/class-xylus-events-calendar-cpt.php';
			require_once XYLUSEC_PLUGIN_DIR . 'includes/admin/class-xylus-events-calendar-admin.php';
			require_once XYLUSEC_PLUGIN_DIR . 'includes/admin/class-xylus-events-calendar-ajax-function.php';
			require_once XYLUSEC_PLUGIN_DIR . 'includes/admin/class-xylus-events-calendar-list-table.php';
			require_once XYLUSEC_PLUGIN_DIR . 'includes/admin/class-xylus-events-calendar-events-list.php';
			require_once XYLUSEC_PLUGIN_DIR . 'includes/admin/class-xylus-events-calendar-widgets.php';
			require_once XYLUSEC_PLUGIN_DIR . 'includes/admin/class-xylus-events-calendar-template-loader.php';
			require_once XYLUSEC_PLUGIN_DIR . 'includes/admin/class-xylus-events-calendar-recurrence.php';
			require_once XYLUSEC_PLUGIN_DIR . 'includes/easy-events-calendar-shortcode.php';
			require_once XYLUSEC_PLUGIN_DIR . 'includes/elementor/register-elementor-widget.php';
			require_once XYLUSEC_PLUGIN_DIR . 'includes/easy-events-calendar-blocks/block.php';
			require_once XYLUSEC_PLUGIN_DIR . 'includes/easy-events-calendar-blocks/easy-events-calendar-gutenberg-shortcode.php';
			require_once XYLUSEC_PLUGIN_DIR . 'includes/admin/class-xylus-events-calendar-deactivation.php';
			require_once XYLUSEC_PLUGIN_DIR . 'includes/class-xylus-events-calendar-ical.php';
		}

		/**
		 * LF setting And docs link add in plugin page.
		 *
		 * @since 1.0
		 * @return void
		 */
		public function xylusec_setting_doc_links( $links ) {
			$xylusec_setting_doc_link = array(
				'lf-event-setting' => sprintf(
					'<a href="%s">%s</a>',
					esc_url( admin_url( 'admin.php?page=xt_events_calendar' ) ),
					esc_html__( 'Setting', 'xylus-events-calendar' )
				),
				'lf-event-docs' => sprintf(
					'<a target="_blank" href="%s">%s</a>',
					esc_url( 'https://docs.xylusthemes.com/docs/xylus-events-calendar/' ),
					esc_html__( 'Docs', 'xylus-events-calendar' )
				),
			);
			return array_merge( $links, $xylusec_setting_doc_link );
		}

		/**
		 * Enqueue style front-end
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function xylusec_enqueue_style() {

			$css_dir = XYLUSEC_PLUGIN_URL . 'assets/css/';
			wp_enqueue_style('xylus-events-calendar-css', $css_dir . 'xylus-events-calendar.css', false, XYLUSEC_VERSION );
			wp_enqueue_style('xylus-events-calendar-widget-css', $css_dir . 'xylus-events-calendar-widget.css', false, XYLUSEC_VERSION );
			wp_enqueue_style('xylus-events-calendar-template-css', $css_dir . 'xylus-events-calendar-template.css', false, XYLUSEC_VERSION );
			wp_enqueue_style('xylus-events-calendar-past-events-css', $css_dir . 'xylus-events-calendar-past-events.css', false, XYLUSEC_VERSION );
			
			$xylusec_options = get_option( XYLUSEC_WIDGET_OPTIONS, [] );
			$xylusec_general_options = get_option( XYLUSEC_OPTIONS, [] );
			$primary_color = $xylusec_general_options['xylusec_primary_color'] ?? $this->common->xylusec_get_theme_primary_color();
			$button_color = $xylusec_general_options['xylusec_button_color'] ?? '#2c3e50';
			$text_color = $xylusec_general_options['xylusec_text_color'] ?? '#333333';
			$event_title_color = $xylusec_general_options['xylusec_event_title_color'] ?? '#60606e';

			$custom_css = ":root {";
			$custom_css .= "--xec-primary-color: {$primary_color};";
			$custom_css .= "--xec-button-color: {$button_color};";
			$custom_css .= "--xec-text-color: {$text_color};";
			$custom_css .= "--xec-event-title-color: {$event_title_color};";

			if( $xylusec_options ){
				foreach ( $xylusec_options as $key => $default ) {
					$value = !empty($xylusec_options[$key]) ? $xylusec_options[$key] : $default;
					if ( $value ) {
						$var_name = str_replace('xylusec_', '--xec-', $key);
						$var_name = str_replace('_', '-', $var_name);
						$custom_css .= "$var_name: $value;";
					}
				}
			}
			$custom_css .= "}";
			wp_add_inline_style('xylus-events-calendar-widget-css', $custom_css);


			if ( ! empty( $primary_color ) ) {
				list($r, $g, $b) = sscanf($primary_color, "#%02x%02x%02x");
				$primary_rgba_30 = "rgba($r, $g, $b, 0.3)";
				$primary_rgba_40 = "rgba($r, $g, $b, 0.4)";

				$primary_css = "
				.eec-single-quick-item svg, .event-detail-value a, .event-detail-link, .eec-archive-card .event-upcoming-card-title a:hover,
				.eec-archive-date svg, .eec-archive-location svg, .eec-venue-meta-item svg, .eec-contact-link svg { color: {$primary_color} !important; }
				.eec-single-register-btn, .event-date-badge { background: linear-gradient(135deg, {$primary_color}, {$primary_color}) !important; }
				.eec-single-register-btn { box-shadow: 0 4px 15px {$primary_rgba_30} !important; }
				.eec-single-register-btn:hover { box-shadow: 0 6px 20px {$primary_rgba_40} !important; }
				.event-date-badge { box-shadow: 0 4px 15px {$primary_rgba_40} !important; }
				.event-section-title::after, .eec-archive-title::after { background: linear-gradient(90deg, {$primary_color}, {$primary_color}) !important; }
				.eec-results-info { border-left-color: {$primary_color} !important; }
				.eec-spinner { border-top-color: {$primary_color} !important; }
				";
				wp_add_inline_style('xylus-events-calendar-template-css', $primary_css);
			}
		}

		/**
		 * Enqueue script front-end
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function xylusec_enqueue_script() {

			$js_dir = XYLUSEC_PLUGIN_URL . 'assets/js/';
			
			wp_enqueue_script( 'moment' );
			wp_enqueue_script( 'masonry' );
			wp_enqueue_script( 'fullcalendar-js', plugins_url( 'assets/js/xylus-events-calendar-fullcalendar.global.min.js', __FILE__ ), array( 'jquery', 'moment' ), '6.1.17', true );
			wp_register_script( 'xylus-events-calendar',  $js_dir . 'xylus-events-calendar.js',  array( 'jquery', 'jquery-ui-core', 'fullcalendar-js' ),  XYLUSEC_VERSION,  true  );
			wp_enqueue_script( 'xylus-events-calendar' );

			wp_localize_script( 'xylus-events-calendar', 'xylusec_ajax', array(
				'ajaxurl'      => admin_url( 'admin-ajax.php' ),
				'nonce'        => wp_create_nonce( 'xylusec_nonce' ),
				'xylusec_options' => get_option( XYLUSEC_OPTIONS, [] )
			) );
		}

	}

endif; // End If class exists check.

/**
 * The main function for that returns Xylus_Events_Calendar
 *
 * The main function responsible for returning the one true Xylus_Events_Calendar
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $xylusec_events_calendar = xylusec_xt_events_calendar(); ?>
 *
 * @since 1.0.0
 * @return object|Xylus_Events_Calendar The one true Xylus_Events_Calendar Instance.
 */
function xylusec_xt_events_calendar() {
	return Xylus_Events_Calendar::instance();
}

/**
 * Get Import events setting options
 *
 * @since 1.0
 * @param string $type Option type.
 * @return array|bool Options.
 */
function xylusec_get_options() {
	$xylusec_options = get_option( XYLUSEC_OPTIONS );
	return $xylusec_options;
}

// Get Xylus_Events_Calendar Running.
global $xylusec_errors, $xylusec_success_msg, $xylusec_warnings, $xylusec_info_msg;
$xylusec_events_calendar = xylusec_xt_events_calendar();
$xylusec_errors = $xylusec_warnings = $xylusec_success_msg = $xylusec_info_msg = array();


/**
 * The code that runs during plugin activation.
 *
 * @since 1.1.0
 */
function xylusec_plugin_activate() {
    $plugin = xylusec_xt_events_calendar();

    if ( isset( $plugin->cpt ) ) {
        $plugin->cpt->register_event_post_type();
        $plugin->cpt->register_event_taxonomy();
    
        if ( method_exists( $plugin->cpt, 'eec_add_custom_rewrite_rules' ) ) {
            $plugin->cpt->eec_add_custom_rewrite_rules();
        }
    }
    flush_rewrite_rules();
    
    update_option( 'xylusec_plugin_activated', true );
}
register_activation_hook( __FILE__, 'xylusec_plugin_activate' );


function xylusec_plugin_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'xylusec_plugin_deactivate' );