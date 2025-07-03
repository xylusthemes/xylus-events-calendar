<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package     Xylus_Events_Calendar
 * @subpackage  Xylus_Events_Calendar/admin
 * @copyright   Copyright (c) 2016, Rajat Patel
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @package     Xylus_Events_Calendar
 * @subpackage  Xylus_Events_Calendar/admin
 * @author     Rajat Patel <prajat21@gmail.com>
 */
class Xylus_Events_Calendar_Admin {

	/**
	 * Admin page URL
	 *
	 * @var string
	 */
	public $adminpage_url;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->adminpage_url  = admin_url( 'admin.php?page=xt_events_calendar' );

        add_action( 'admin_menu', array( $this, 'xylusec_add_menu_pages' ) );
		add_filter( 'submenu_file', array( $this, 'xylusec_get_selected_tab_submenu_xtfefoli' ) );
        add_action( 'admin_init', array( $this, 'xylusec_handle_so_settings_submit' ), 99 );
        add_action( 'xylusec_notice', array( $this, 'xylusec_display_notices' ) );
		add_action( 'admin_init', array( $this, 'xylusec_plugin_maybe_save_default_options' ) );
		add_shortcode('xylus_events_calendar', array( $this, 'xylusec_calendar_shortcode' ) );
	}

	public function xylusec_calendar_shortcode($atts) {
		ob_start();
		include XYLUSEC_PLUGIN_DIR . 'templates/admin/xylus-events-calendar-template.php';
		return ob_get_clean();
	}


    /**
	 * Create the Admin menu and submenu and assign their links to global varibles.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function xylusec_add_menu_pages() {
		add_menu_page( __( 'Xylus Events Calendar', 'xylus-events-calendar' ), __( 'Xylus Events Calendar', 'xylus-events-calendar' ), 'manage_options', 'xt_events_calendar', array( $this, 'xylusec_admin_page' ), 'dashicons-calendar', '30' );
		global $submenu;	
		$submenu['xt_events_calendar'][] = array( __( 'Xylus Events Calendar', 'xylus-events-calendar' ), 'manage_options', admin_url( 'admin.php?page=xt_events_calendar&tab=general' ) );
		$submenu['xt_events_calendar'][] = array( __( 'Shortcode', 'xylus-events-calendar' ), 'manage_options', admin_url( 'admin.php?page=xt_events_calendar&tab=shortcode' ) );
		$submenu['xt_events_calendar'][] = array( __( 'Support & Help', 'xylus-events-calendar' ), 'manage_options', admin_url( 'admin.php?page=xt_events_calendar&tab=support' ) );
	}

	/**
	 * Load Admin page.
	 *
	 * @since 1.0
	 * @return void
	 */

	public function xylusec_admin_page(){
        global $xt_events_calendar;
		
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $active_tab = isset( $_GET['tab'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['tab'] ) ) ) : 'general';
        $gettab     = ucwords( str_replace( '_', ' ', $active_tab ) );
        if( $active_tab == 'general' || $active_tab == 'shortcode' || $active_tab == 'support' ){
            $gettab     = ucwords( str_replace( '_', ' ', $gettab ) );
            $page_title = $gettab;
        }
        
        $posts_header_result = $xt_events_calendar->common->xylusec_render_common_header( $page_title );
        ?>
        
        <div class="xylusec-container" >
            <div class="xylusec-wrap" >
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-2">
                        <?php
                            do_action( 'xylusec_notice' ); 
                        ?>
                        <div class="ajax_xylusec_notice"></div>
                        <div id="postbox-container-2" class="postbox-container">
                            <div class="xylusec-app">
                                <div class="xylusec-tabs">
                                    <div class="tabs-scroller">
                                        <div class="var-tabs var-tabs--item-horizontal var-tabs--layout-horizontal-padding">
											<div class="var-tabs__tab-wrap var-tabs--layout-horizontal">
												<a href="<?php echo esc_url( admin_url( 'admin.php?page=xt_events_calendar&tab=general' ) ); ?>" class="var-tab <?php echo $active_tab == 'general' ? 'var-tab--active' : 'var-tab--inactive'; ?>">
													<span class="tab-label"><?php esc_attr_e( 'General', 'xylus-events-calendar' ); ?></span>
												</a>
												<a href="<?php echo esc_url( admin_url( 'admin.php?page=xt_events_calendar&tab=shortcode' ) ); ?>" class="var-tab <?php echo $active_tab == 'shortcode' ? 'var-tab--active' : 'var-tab--inactive'; ?>">
													<span class="tab-label"><?php esc_attr_e( 'Shortcode', 'xylus-events-calendar' ); ?></span>
												</a>
												<a href="<?php echo esc_url( admin_url( 'admin.php?page=xt_events_calendar&tab=support' ) ); ?>" class="var-tab <?php echo $active_tab == 'support' ? 'var-tab--active' : 'var-tab--inactive'; ?>">
													<span class="tab-label"><?php esc_attr_e( 'Support & Help', 'xylus-events-calendar' ); ?></span>
												</a>
											</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <?php
                            $valid_tabs = [ 'general', 'shortcode', 'support' ];
                            if( $active_tab == 'general' ){
                                require_once XYLUSEC_PLUGIN_DIR . '/templates/admin/xylus-events-calendar-general.php';
							}elseif( $active_tab == 'shortcode' ){
                                require_once XYLUSEC_PLUGIN_DIR . '/templates/admin/xylus-events-calendar-shortcode.php';
                            }elseif( $active_tab == 'support' ){
                                require_once XYLUSEC_PLUGIN_DIR . '/templates/admin/xylus-events-calendar-support.php';
                            }
                            ?>
                        </div>
                    </div>
                    <br class="clear">
                </div>
            </div>
        </div>
        <?php
        $posts_footer_result = $xt_events_calendar->common->xylusec_render_common_footer();
    }

	/**
	 * Tab Submenu got selected.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function xylusec_get_selected_tab_submenu_xtfefoli( $submenu_file ) {
		global $xt_events_calendar;

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $_GET['page'] ) && esc_attr( sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) == 'xt_events_calendar' ) {
			$allowed_tabs = array( 'general', 'shortcode', 'support' );

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$tab = isset( $_GET['tab'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['tab'] ) ) ) : 'general';
			if ( in_array( $tab, $allowed_tabs ) ) {
				$submenu_file = admin_url( 'admin.php?page=xt_events_calendar&tab=' . $tab );
			}
		}
		return $submenu_file;
	}
	

    /**
	 * Process Saving liknedin feedpress sharing option
	 *
	 * @since    1.0.0
	 */
	public function xylusec_handle_so_settings_submit() {
		global $xylusec_errors, $xylusec_success_msg;

		if (
			isset( $_POST['xylusec_so_action'] ) &&
			'xylusec_save_so_settings' === esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_so_action'] ) ) ) &&
			check_admin_referer( 'xylusec_so_setting_form_nonce_action', 'xylusec_so_setting_form_nonce' )
		) {
			// Sanitize and collect all form fields
			$xylusec_so_options = [
				'xylusec_event_source'        => esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_event_source'] ?? '' ) ) ),
				'xylusec_default_view'        => esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_default_view'] ?? '' ) ) ),
				'xylusec_events_per_page'     => esc_attr( sanitize_text_field( wp_unslash( absint( $_POST['xylusec_events_per_page'] ?? 10 ) ) ) ),
				'xylusec_load_more_label'     => esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_load_more_label'] ?? '' ) ) ),
				'xylusec_view_details_label'  => esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_view_details_label'] ?? '' ) ) ),
				'xylusec_week_start'          => esc_attr( sanitize_text_field( wp_unslash( absint( $_POST['xylusec_week_start'] ?? 0 ) ) ) ),
				'xylusec_button_color'        => esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_button_color'] ?? '#2c3e50' ) ) ),
				'xylusec_text_color'          => esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_text_color'] ?? '#333333' ) ) ),
				'xylusec_event_title_color'   => esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_event_title_color'] ?? '#60606e' ) ) ),
			];

			$updated = update_option( XYLUSEC_OPTIONS, $xylusec_so_options );

			if ( $updated ) {
				$xylusec_success_msg[] = __( 'Settings saved successfully.', 'xylus-events-calendar' );
			} else {
				$xylusec_errors[] = __( 'No changes made or something went wrong.', 'xylus-events-calendar' );
			}
		}
	}

    /**
	 * Display notices in admin.
	 *
	 * @since    1.0.0
	 */
	public function xylusec_display_notices() {
		global $xylusec_errors, $xylusec_success_msg, $xylusec_warnings, $xylusec_info_msg;

		if ( ! empty( $xylusec_errors ) ) {
			foreach ( $xylusec_errors as $error ) :
				?>
				<div class="notice notice-error xylusec-notice is-dismissible">
					<p><?php echo wp_kses_post( $error ) ; ?></p>
				</div>
				<?php
			endforeach;
		}

		if ( ! empty( $xylusec_success_msg ) ) {
			foreach ( $xylusec_success_msg as $success ) :
				?>
				<div class="notice notice-success xylusec-notice is-dismissible">
					<p><?php echo wp_kses_post( $success ); ?></p>
				</div>
				<?php
			endforeach;
		}

		if ( ! empty( $xylusec_warnings ) ) {
			foreach ( $xylusec_warnings as $warning ) :
				?>
				<div class="notice notice-warning xylusec-notice is-dismissible">
					<p><?php echo wp_kses_post( $warning ); ?></p>
				</div>
				<?php
			endforeach;
		}

		if ( ! empty( $xylusec_info_msg ) ) {
			foreach ( $xylusec_info_msg as $info ) :
				?>
				<div class="notice notice-info xylusec-notice is-dismissible">
					<p><?php echo wp_kses_post( $info ); ?></p>
				</div>
				<?php
			endforeach;
		}
	}

	/**
	 * Save default options on plugin activation.
	 *
	 * @since 1.0.0
	 */
	public function xylusec_plugin_maybe_save_default_options() {
		if ( get_option( 'xylusec_plugin_activated' ) ) {

			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			// Detect primary and text color from theme
			$primary_color = get_theme_mod( 'accent_color', '#2c3e50' );
			$text_color    = get_theme_mod( 'text_color', '#ffffff' );

			if ( empty( $text_color ) && function_exists( 'wp_get_global_settings' ) ) {
				$json = wp_get_global_settings();
				$text_color = $json['color']['text'] ?? '#ffffff';
			}

			// Determine active event source plugin
			$event_source = '';
			if ( is_plugin_active( 'import-meetup-events/import-meetup-events.php' ) ) {
				$event_source = 'meetup_events';
			} elseif ( is_plugin_active( 'import-eventbrite-events/import-eventbrite-events.php' ) ) {
				$event_source = 'eventbrite_events';
			} elseif ( is_plugin_active( 'facebook-events/facebook-events.php' ) ) {
				$event_source = 'facebook_events';
			} elseif ( is_plugin_active( 'wp-event-aggregator/wp-event-aggregator.php' ) ) {
				$event_source = 'wp_events';
			}

			// Save default options only if not already set
			if ( ! get_option( XYLUSEC_OPTIONS ) ) {
				$defaults = [
					'xylusec_event_source'        => $event_source,
					'xylusec_default_view'        => '',
					'xylusec_events_per_page'     => 12,
					'xylusec_load_more_label'     => 'Load More Events',
					'xylusec_view_details_label'  => 'View Details',
					'xylusec_week_start'          => 0,
					'xylusec_button_color'        => $primary_color,
					'xylusec_text_color'          => $text_color,
					'xylusec_event_title_color'   => $primary_color,
				];

				update_option( XYLUSEC_OPTIONS, $defaults );
			}

			delete_option( 'xylusec_plugin_activated' );

			// Redirect after activation (only for single plugin activation)
			if ( ! isset( $_GET['activate-multi'] ) && is_admin() ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				wp_safe_redirect( admin_url( 'admin.php?page=xt_events_calendar&tab=general' ) );
				exit;
			}
		}
	}
}
