<?php
/**
 * Template Loader for Easy Events Calendar.
 *
 * Handles loading front-end templates for the eec_events post type
 * and its taxonomies (eec_category, eec_tag). Supports theme overrides
 * by checking the active theme first, then falling back to plugin defaults.
 *
 * Theme Override Path: {theme}/xylus-events-calendar/{template-name}.php
 * Plugin Fallback:     {plugin}/templates/{template-name}.php
 *
 * @link       http://xylusthemes.com/
 * @since      1.1.0
 *
 * @package    Xylus_Events_Calendar
 * @subpackage Xylus_Events_Calendar/includes
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xylus_Events_Calendar_Template_Loader {

	/**
	 * Theme subfolder for template overrides.
	 *
	 * @var string
	 */
	private $theme_template_dir = 'xylus-events-calendar';

	/**
	 * Event post type slug.
	 *
	 * @var string
	 */
	private $event_posttype = 'eec_events';

	/**
	 * Event category taxonomy.
	 *
	 * @var string
	 */
	private $event_category = 'eec_category';

	/**
	 * Event tag taxonomy.
	 *
	 * @var string
	 */
	private $event_tag = 'eec_tag';

	/**
	 * Event venue taxonomy.
	 *
	 * @var string
	 */
	private $event_venue = 'eec_venue';

	/**
	 * Event organizer taxonomy.
	 *
	 * @var string
	 */
	private $event_organizer = 'eec_organizer';

	/**
	 * Initialize the class and register hooks.
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		add_filter( 'template_include', array( $this, 'xylusec_template_loader' ), 99 );
		add_action( 'pre_get_posts', array( $this, 'xylusec_handle_archive_filters' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'eec_enqueue_scripts' ) );

		// AJAX handlers.
		add_action( 'wp_ajax_eec_ajax_filter', array( $this, 'eec_ajax_filter_events' ) );
		add_action( 'wp_ajax_nopriv_eec_ajax_filter', array( $this, 'eec_ajax_filter_events' ) );
	}

	/**
	 * Enqueue front-end scripts and styles.
	 *
	 * @since 1.2.0
	 */
	public function eec_enqueue_scripts() {
		wp_enqueue_script( 'eec-ajax-filter', XYLUSEC_PLUGIN_URL . 'assets/js/xylus-events-calendar-ajax.js', array( 'jquery' ), XYLUSEC_VERSION, true );

		wp_localize_script( 'eec-ajax-filter', 'eec_ajax_obj', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'eec_ajax_nonce' ),
		) );
	}

	/**
	 * Handle custom filters for event archives and taxonomy pages.
	 * 
	 * Supports: eec_search, eec_category, eec_venue, eec_organizer
	 *
	 * @since 1.1.0
	 * @param WP_Query $query The query object.
	 */
	public function xylusec_handle_archive_filters( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		$eec_view = get_query_var( 'eec_view' );

		// Only target event archives, custom taxonomies, or our root views.
		if ( ! is_post_type_archive( $this->event_posttype ) && 
			 ! is_tax( array( $this->event_category, $this->event_tag, $this->event_venue, $this->event_organizer ) ) &&
			 empty( $eec_view ) ) {
			return;
		}

		$tax_query = array( 'relation' => 'AND' );

		// 1. Search Filter (Targets Title and Content).
		if ( ! empty( $_GET['eec_search'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$query->set( 's', sanitize_text_field( wp_unslash( $_GET['eec_search'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		// 2. Category Filter.
		if ( ! empty( $_GET['eec_category'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$tax_query[] = array(
				'taxonomy' => $this->event_category,
				'field'    => 'slug',
				'terms'    => sanitize_text_field( wp_unslash( $_GET['eec_category'] ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			);
		}

		// 3. Venue Filter.
		if ( ! empty( $_GET['eec_venue'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$tax_query[] = array(
				'taxonomy' => $this->event_venue,
				'field'    => 'slug',
				'terms'    => sanitize_text_field( wp_unslash( $_GET['eec_venue'] ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			);
		}

		// 4. Organizer Filter.
		if ( ! empty( $_GET['eec_organizer'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$tax_query[] = array(
				'taxonomy' => $this->event_organizer,
				'field'    => 'slug',
				'terms'    => sanitize_text_field( wp_unslash( $_GET['eec_organizer'] ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			);
		}

		if ( count( $tax_query ) > 1 ) {
			$query->set( 'tax_query', $tax_query );
		}

		// 5. Default to Upcoming Only.
		$meta_query = $query->get( 'meta_query' ) ?: array();
		$meta_query[] = array(
			'key'     => 'end_ts',
			'value'   => current_time( 'timestamp' ),
			'compare' => '>=',
			'type'    => 'NUMERIC',
		);
		$query->set( 'meta_query', $meta_query );
		$query->set( 'orderby', 'meta_value_num' );
		$query->set( 'meta_key', 'start_ts' );
		$query->set( 'order', 'ASC' );
	}

	/**
	 * Load the appropriate template for eec_events post type and taxonomies.
	 *
	 * Priority: theme override → plugin fallback → WordPress default.
	 *
	 * @since  1.1.0
	 * @param  string $template The current template path.
	 * @return string Modified template path.
	 */
	public function xylusec_template_loader( $template ) {

		// Single event page.
		if ( is_singular( $this->event_posttype ) ) {
			$located = $this->xylusec_locate_template( 'single-eec_events.php' );
			if ( $located ) {
				return $located;
			}
		}

		$eec_view = get_query_var( 'eec_view' );

		// Venue Root Archive.
		if ( $eec_view === 'venue_root' ) {
			$located = $this->xylusec_locate_template( 'archive-eec_events.php' );
			if ( $located ) {
				return $located;
			}
		}

		// Organizer Root Archive.
		if ( $eec_view === 'organizer_root' ) {
			$located = $this->xylusec_locate_template( 'archive-eec_events.php' );
			if ( $located ) {
				return $located;
			}
		}

		// Category Root Archive.
		if ( $eec_view === 'category_root' ) {
			$located = $this->xylusec_locate_template( 'archive-eec_events.php' );
			if ( $located ) {
				return $located;
			}
		}

		// Tag Root Archive.
		if ( $eec_view === 'tag_root' ) {
			$located = $this->xylusec_locate_template( 'archive-eec_events.php' );
			if ( $located ) {
				return $located;
			}
		}

		// Event archive page.
		if ( is_post_type_archive( $this->event_posttype ) ) {
			$located = $this->xylusec_locate_template( 'archive-eec_events.php' );
			if ( $located ) {
				return $located;
			}
		}

		// Event category taxonomy archive.
		if ( is_tax( $this->event_category ) ) {
			$located = $this->xylusec_locate_template( 'taxonomy-eec_category.php' );
			if ( $located ) {
				return $located;
			}
		}

		// Event tag taxonomy archive.
		if ( is_tax( $this->event_tag ) ) {
			$located = $this->xylusec_locate_template( 'taxonomy-eec_tag.php' );
			if ( $located ) {
				return $located;
			}
		}

		// Event venue taxonomy archive.
		if ( is_tax( $this->event_venue ) ) {
			$located = $this->xylusec_locate_template( 'taxonomy-eec_venue.php' );
			if ( $located ) {
				return $located;
			}
		}

		// Event organizer taxonomy archive.
		if ( is_tax( $this->event_organizer ) ) {
			$located = $this->xylusec_locate_template( 'taxonomy-eec_organizer.php' );
			if ( $located ) {
				return $located;
			}
		}

		return $template;
	}

	/**
	 * Locate a template file.
	 *
	 * Checks the active theme first (in the xylus-events-calendar subfolder),
	 * then falls back to the plugin's templates directory.
	 *
	 * @since  1.1.0
	 * @param  string $template_name Template filename (e.g. 'single-eec_events.php').
	 * @return string|false Full path to the template file, or false if not found.
	 */
	public function xylusec_locate_template( $template_name ) {

		// 1. Check theme / child-theme override.
		$theme_template = locate_template(
			array(
				trailingslashit( $this->theme_template_dir ) . $template_name,
			)
		);

		if ( $theme_template ) {
			return $theme_template;
		}

		// 2. Fall back to plugin template.
		$plugin_template = XYLUSEC_PLUGIN_DIR . 'templates/' . $template_name;

		if ( file_exists( $plugin_template ) ) {
			return $plugin_template;
		}

		return false;
	}

	/**
	 * AJAX Handler for Filtering Events.
	 *
	 * @since 1.2.0
	 */
	/**
	 * AJAX Handler for Filtering Events.
	 *
	 * @since 1.2.0
	 */
	/**
	 * AJAX Handler for Filtering Events.
	 *
	 * @since 1.2.0
	 */
	public function eec_ajax_filter_events() {
		check_ajax_referer( 'eec_ajax_nonce', 'nonce' );

		$paged   = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
		$options = get_option( XYLUSEC_OPTIONS, [] );
		$per_page = ! empty( $options['xylusec_events_per_page'] ) ? intval( $options['xylusec_events_per_page'] ) : 12;
		
		$shortcode_atts = wp_json_encode( array(
			'category'  => isset( $_POST['eec_category'] ) ? sanitize_text_field( wp_unslash( $_POST['eec_category'] ) ) : '',
			'venue'     => isset( $_POST['eec_venue'] ) ? sanitize_text_field( wp_unslash( $_POST['eec_venue'] ) ) : '',
			'organizer' => isset( $_POST['eec_organizer'] ) ? sanitize_text_field( wp_unslash( $_POST['eec_organizer'] ) ) : '',
		) );

		$search = isset( $_POST['eec_search'] ) ? sanitize_text_field( wp_unslash( $_POST['eec_search'] ) ) : '';

		global $xylusec_events_calendar;
		$query = $xylusec_events_calendar->common->xylusec_get_upcoming_events( $this->event_posttype, $paged, $search, $per_page, $shortcode_atts );

		$layout        = isset( $_POST['eec_layout'] ) ? sanitize_text_field( wp_unslash( $_POST['eec_layout'] ) ) : 'grid';
		$template_file = ( $layout === 'list' ) ? 'events-list.php' : 'events-grid.php';

		ob_start();

		// Use the correct loop template based on selected layout.
		include XYLUSEC_PLUGIN_DIR . 'templates/loop/' . $template_file;

		$html = ob_get_clean();

		wp_send_json_success( array(
			'html'        => $html,
			'found_posts' => $query->found_posts,
		) );
	}
}
