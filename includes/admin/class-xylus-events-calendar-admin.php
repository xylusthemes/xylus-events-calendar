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

	// The Events Calendar Event Taxonomy
	public $event_slug;

	// Event post type.
	protected $event_posttype;

	// Event post type.
	protected $event_category;

	// Event post type.
	protected $event_tag;



	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->adminpage_url  = admin_url( 'admin.php?page=xt_events_calendar' );
		$this->event_slug     = 'eec-event';
		$this->event_posttype = 'eec_events';
		$this->event_category = 'eec_category';
		$this->event_tag      = 'eec_tag';

        add_action( 'admin_menu', array( $this, 'xylusec_add_menu_pages' ) );
		add_filter( 'submenu_file', array( $this, 'xylusec_get_selected_tab_submenu_xtfefoli' ) );
        add_action( 'admin_init', array( $this, 'xylusec_handle_so_settings_submit' ), 99 );
        add_action( 'admin_init', array( $this, 'xylusec_handle_so_widget_settings_submit' ), 99 );
        add_action( 'xylusec_notice', array( $this, 'xylusec_display_notices' ) );
		add_action( 'admin_init', array( $this, 'xylusec_plugin_maybe_save_default_options' ) );
		add_shortcode('easy_events_calendar', array( $this, 'xylusec_calendar_shortcode' ) );
		// Template loading moved to Xylus_Events_Calendar_Template_Loader class.
		add_action( 'enqueue_block_editor_assets', array( $this, 'xylus_enqueue_block_editor_styles' ) );
		add_shortcode('eec_event_details_releted_events', array( $this, 'eec_related_events_shortcode' ) );
		add_shortcode('eec_events_discovery', array( $this, 'eec_events_discovery_shortcode' ) );
	}

	public function xylus_enqueue_block_editor_styles() {
		$css_dir = plugin_dir_url( __FILE__ ) . 'css/';
		wp_enqueue_style(
			'xylus-events-calendar-block-editor',
			$css_dir . 'xylus-events-calendar-global-admin.css',
			false,
			XYLUSEC_VERSION
		);
	}

	// eec_events_single_template() and eec_events_archive_template() removed.
	// Template loading is now handled by Xylus_Events_Calendar_Template_Loader.

	public function xylusec_calendar_shortcode($atts) {
		wp_enqueue_script( 'xylus-events-calendar' );
		$inline = 'if (window.xylusec_ajax) { xylusec_ajax.shortcode_atts = ' . wp_json_encode( $atts ) . '; }';
		wp_add_inline_script( 'xylus-events-calendar', $inline, 'before' );
		
		ob_start();
		include XYLUSEC_PLUGIN_DIR . 'templates/admin/xylus-events-calendar-template.php';
		return ob_get_clean();
	}

	/**
	 * Related Events Shortcode
	 * 
	 * @param array $atts Shortcode attributes
	 * @return string HTML output or empty string
	 */
	public function eec_related_events_shortcode($atts) {
		global $xylusec_events_calendar;
		// Parse shortcode attributes
		$atts = shortcode_atts(array(
			'event_id' => get_the_ID(),
			'limit'    => 3,
			'title'    => __('Related Events', 'xylus-events-calendar'),
		), $atts, 'eec_related_events');
		
		$event_id = absint($atts['event_id']);
		
		// Validate event ID
		if (!$event_id || 'eec_events' !== get_post_type( $event_id ) ) {
			return '';
		}
		
		// ===== GET TERMS FROM TAXONOMIES =====
		$categories = wp_get_post_terms($event_id, 'eec_category', array('fields' => 'ids'));
		$tags       = wp_get_post_terms($event_id, 'eec_tag', array('fields' => 'ids'));
		$venues     = wp_get_post_terms($event_id, 'eec_venue', array('fields' => 'ids'));
		$organizers = wp_get_post_terms($event_id, 'eec_organizer', array('fields' => 'ids'));
		
		// Build tax query
		$tax_query = array('relation' => 'OR');
		$has_terms = false;
		
		$taxonomies = array(
			'eec_category'  => $categories,
			'eec_tag'       => $tags,
			'eec_venue'     => $venues,
			'eec_organizer' => $organizers,
		);

		foreach ($taxonomies as $taxonomy => $terms) {
			if (!empty($terms) && !is_wp_error($terms)) {
				$tax_query[] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => (array) $terms,
				);
				$has_terms = true;
			}
		}
		
		$now_ts = current_time('timestamp');
		
		$args = array(
			'post_type'      => 'eec_events',
			'posts_per_page' => absint( $atts['limit'] ),
			'post__not_in'   => array( $event_id ), // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
			'post_status'    => 'publish',
			'orderby'        => 'meta_value_num',
			'meta_key'       => 'start_ts', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'order'          => 'ASC',
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => 'end_ts',
					'value'   => $now_ts,
					'compare' => '>=',
					'type'    => 'NUMERIC'
				)
			),
		);

		// Prioritize related by terms
		if ($has_terms) {
			$args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		}
		
		$query = new WP_Query($args);
		
		// Fallback 1: If no exact related matches found, try general upcoming events
		if (!$query->have_posts()) {
			unset($args['tax_query']);
			$query = new WP_Query($args);
		}

		// Fallback 2 removed to strictly show only upcoming events.

		if (!$query->have_posts()) return '';
		
		ob_start();
		?>
		<section class="eec-related-events-section">
			<h2 class="eec-related-title"><?php echo esc_html( $atts['title'] ); ?></h2>
			<div class="eec-related-grid">
				<?php while ($query->have_posts()) : $query->the_post();
					
					$rel_id        = get_the_ID();
					$next_instance = $xylusec_events_calendar->common->xylusec_get_next_event_instance( $rel_id );
					
					$month = $day = '';
					
					if ( $next_instance ) {
						$timestamp = strtotime($next_instance->start_date);
						$month = date_i18n('M', $timestamp);
						$day   = date_i18n('d', $timestamp);
					} else {
						// Fallback to meta
						$display_date = get_post_meta($rel_id, 'event_start_date', true) ?: get_post_meta($rel_id, 'event_date', true);
						if ( ! $display_date ) {
							$ts = get_post_meta($rel_id, 'start_ts', true);
							if ( $ts ) $display_date = gmdate('Y-m-d', $ts);
						}
						
						if ($display_date) {
							$timestamp = strtotime($display_date);
							$month = date_i18n('M', $timestamp);
							$day   = date_i18n('d', $timestamp);
						}
					}
					
					$image = get_the_post_thumbnail_url($rel_id, 'medium');
					$fallback_image = xylusec_xt_events_calendar()->common->xylusec_get_random_placeholder();
				?>
				<div class="eec-related-card">
					<div class="eec-related-image-wrap">
						<a href="<?php the_permalink(); ?>">
							<img src="<?php echo esc_url($image ?: $fallback_image); ?>" alt="<?php the_title_attribute(); ?>" />
						</a>
						<?php if ($month && $day) : ?>
						<div class="eec-related-date-badge">
							<span class="month"><?php echo esc_html(strtoupper($month)); ?></span>
							<span class="day"><?php echo esc_html($day); ?></span>
						</div>
						<?php endif; ?>
					</div>
					<div class="eec-related-content">
						<h3 class="eec-related-item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div class="eec-related-excerpt">
							<?php echo wp_kses_post( wp_trim_words( get_the_excerpt() ?: get_the_content(), 15 ) ); ?>
						</div>
					</div>
				</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</section>
		<?php
		return ob_get_clean();
	}

	/**
	 * Events Discovery Shortcode
	 * 
	 * @param array $atts Shortcode attributes
	 * @return string HTML output
	 */
	public function eec_events_discovery_shortcode( $atts ) {
		global $xylusec_events_calendar;
		wp_enqueue_script( 'xylus-events-calendar' );
		wp_enqueue_style( 'xylus-events-calendar-template' );
		
		// Get terms for filters
		$all_categories = get_terms( array( 'taxonomy' => 'eec_category', 'hide_empty' => true ) );
		$all_venues     = get_terms( array( 'taxonomy' => 'eec_venue', 'hide_empty' => true ) );
		$all_organizers = get_terms( array( 'taxonomy' => 'eec_organizer', 'hide_empty' => true ) );

		// Current filter values
		$filter_search    = isset( $_GET['eec_search'] ) ? sanitize_text_field( wp_unslash( $_GET['eec_search'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$filter_category  = isset( $_GET['eec_category'] ) ? sanitize_text_field( wp_unslash( $_GET['eec_category'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$filter_venue     = isset( $_GET['eec_venue'] ) ? sanitize_text_field( wp_unslash( $_GET['eec_venue'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$filter_organizer = isset( $_GET['eec_organizer'] ) ? sanitize_text_field( wp_unslash( $_GET['eec_organizer'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		// Get per_page option to match AJAX logic
		$options  = get_option( XYLUSEC_OPTIONS, [] );
		$per_page = ! empty( $options['xylusec_events_per_page'] ) ? intval( $options['xylusec_events_per_page'] ) : 12;

		ob_start();
		?>
		<div class="eec-archive-wrap eec-discovery-shortcode">
			<div class="eec-filter-bar">
				<form method="get" class="eec-filter-form" id="eec-filter-ajax-form">
					<div class="eec-filter-search">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
						</svg>
						<input type="text" name="eec_search" placeholder="<?php esc_attr_e( 'Search events...', 'xylus-events-calendar' ); ?>" value="<?php echo esc_attr( $filter_search ); ?>" class="eec-filter-input" />
					</div>

					<div class="eec-filter-select-wrap">
						<select name="eec_category" class="eec-filter-select">
							<option value=""><?php esc_html_e( 'All Categories', 'xylus-events-calendar' ); ?></option>
							<?php foreach ( $all_categories as $cat ) : ?>
								<option value="<?php echo esc_attr( $cat->slug ); ?>" <?php selected( $filter_category, $cat->slug ); ?>><?php echo esc_html( $cat->name ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="eec-filter-select-wrap">
						<select name="eec_venue" class="eec-filter-select">
							<option value=""><?php esc_html_e( 'All Venues', 'xylus-events-calendar' ); ?></option>
							<?php foreach ( $all_venues as $venue ) : ?>
								<option value="<?php echo esc_attr( $venue->slug ); ?>" <?php selected( $filter_venue, $venue->slug ); ?>><?php echo esc_html( $venue->name ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="eec-filter-select-wrap">
						<select name="eec_organizer" class="eec-filter-select">
							<option value=""><?php esc_html_e( 'All Organizers', 'xylus-events-calendar' ); ?></option>
							<?php foreach ( $all_organizers as $org ) : ?>
								<option value="<?php echo esc_attr( $org->slug ); ?>" <?php selected( $filter_organizer, $org->slug ); ?>><?php echo esc_html( $org->name ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<button type="submit" class="eec-filter-btn"><?php esc_html_e( 'Filter', 'xylus-events-calendar' ); ?></button>

					<div class="eec-layout-toggle" id="eec-layout-toggle-group">
						<input type="hidden" name="eec_layout" id="eec-layout-input" value="grid" />
						<button type="button" class="eec-layout-btn active" data-layout="grid" title="<?php esc_attr_e( 'Grid View', 'xylus-events-calendar' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
							</svg>
						</button>
						<button type="button" class="eec-layout-btn" data-layout="list" title="<?php esc_attr_e( 'List View', 'xylus-events-calendar' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
							</svg>
						</button>
					</div>

					<?php if ( $filter_search || $filter_category || $filter_venue || $filter_organizer ) : ?>
						<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="eec-filter-reset"><?php esc_html_e( 'Reset', 'xylus-events-calendar' ); ?></a>
					<?php endif; ?>
				</form>
			</div>

			<div class="eec-results-info">
				<?php
				$category    = isset( $_GET['eec_category'] ) ? sanitize_text_field( wp_unslash( $_GET['eec_category'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$search      = isset( $_GET['eec_search'] ) ? sanitize_text_field( wp_unslash( $_GET['eec_search'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$venue       = isset( $_GET['eec_venue'] ) ? sanitize_text_field( wp_unslash( $_GET['eec_venue'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$organizer   = isset( $_GET['eec_organizer'] ) ? sanitize_text_field( wp_unslash( $_GET['eec_organizer'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				
				// Build attributes for the unified query
				$shortcode_atts = wp_json_encode( array(
					'category'  => $category,
					'venue'     => $venue,
					'organizer' => $organizer,
				) );

				$query = $xylusec_events_calendar->common->xylusec_get_upcoming_events( 'eec_events', 1, $search, $per_page, $shortcode_atts );
				/* translators: %d: number of events found */
				printf( esc_html( _n( '%d event found', '%d events found', $query->found_posts, 'xylus-events-calendar' ) ), intval( $query->found_posts ) );
				?>
			</div>

			<div class="eec-ajax-results-container" style="position: relative; min-height: 200px;">
				<div class="eec-ajax-loader" style="display:none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); z-index: 10; align-items: center; justify-content: center;">
					<div class="eec-spinner"></div>
				</div>
				<div id="eec-events-grid" data-taxonomy="" data-term="">
					<?php 
					include XYLUSEC_PLUGIN_DIR . 'templates/loop/events-grid.php'; 
					?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}


    /**
	 * Create the Admin menu and submenu and assign their links to global varibles.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function xylusec_add_menu_pages() {
		add_menu_page( __( 'Easy Events Calendar', 'xylus-events-calendar' ), __( 'Easy Events Calendar', 'xylus-events-calendar' ), 'manage_options', 'xt_events_calendar', array( $this, 'xylusec_admin_page' ), 'dashicons-calendar', '30' );
		global $submenu;	
		$submenu['xt_events_calendar'][] = array( __( 'Easy Events Calendar', 'xylus-events-calendar' ), 'manage_options', admin_url( 'admin.php?page=xt_events_calendar&tab=general' ) );
		$submenu['xt_events_calendar'][] = array( __( 'Widget Appearance', 'xylus-events-calendar' ), 'manage_options', admin_url( 'admin.php?page=xt_events_calendar&tab=widget' ) );
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
        global $xylusec_events_calendar;
		
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $active_tab = isset( $_GET['tab'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['tab'] ) ) ) : 'general';
        $gettab     = ucwords( str_replace( '_', ' ', $active_tab ) );
        if( $active_tab == 'general' || $active_tab == 'widget' || $active_tab == 'shortcode' || $active_tab == 'support'  ){
            $gettab     = ucwords( str_replace( '_', ' ', $gettab ) );
            $page_title = $gettab;
        }
        
        $posts_header_result = $xylusec_events_calendar->common->xylusec_render_common_header( $page_title );
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
												<a href="<?php echo esc_url( admin_url( 'admin.php?page=xt_events_calendar&tab=widget' ) ); ?>" class="var-tab <?php echo $active_tab == 'widget' ? 'var-tab--active' : 'var-tab--inactive'; ?>">
													<span class="tab-label"><?php esc_attr_e( 'Widget Appearance', 'xylus-events-calendar' ); ?></span>
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
							}elseif( $active_tab == 'widget' ){
                                require_once XYLUSEC_PLUGIN_DIR . '/templates/admin/xylus-events-calendar-widget.php';
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
        $posts_footer_result = $xylusec_events_calendar->common->xylusec_render_common_footer();
    }

	/**
	 * Tab Submenu got selected.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function xylusec_get_selected_tab_submenu_xtfefoli( $submenu_file ) {
		global $xylusec_events_calendar;

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
			$hide_header = isset( $_POST['xylusec_hide_header'] ) && esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_hide_header'] ) ) ) === '1' ? 'yes' : 'no';
			$xylusec_so_options = [
				'xylusec_event_source'        => esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_event_source'] ?? '' ) ) ),
				'xylusec_default_view'        => esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_default_view'] ?? '' ) ) ),
				'xylusec_hide_header'         => $hide_header,
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
	 * Process Saving liknedin feedpress sharing option
	 *
	 * @since    1.0.0
	 */
	public function xylusec_handle_so_widget_settings_submit() {
		global $xylusec_errors, $xylusec_success_msg;

		if (
			isset( $_POST['xylusec_so_widget_action'] ) &&
			'xylusec_so_widget_settings' === esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_so_widget_action'] ) ) ) &&
			check_admin_referer( 'xylusec_so_widget_setting_form_nonce_action', 'xylusec_so_widget_setting_form_nonce' )
		) {
			// Sanitize and collect all form fields
			$xylusec_so_widget_options = [
				'xylusec_widget_background_color'         => esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_widget_background_color'] ?? '' ) ) ),
				'xylusec_widget_hover_background_color'   => esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_widget_hover_background_color'] ?? '' ) ) ),
				'xylusec_widget_title_color'              => esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_widget_title_color'] ?? 10 ) ) ),
				'xylusec_widget_title_hover_color'        => esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_widget_title_hover_color'] ?? '' ) ) ),
				'xylusec_widget_date_color'               => esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_widget_date_color'] ?? '' ) ) ),
				'xylusec_widget_border_color'             => esc_attr( sanitize_text_field( wp_unslash( $_POST['xylusec_widget_border_color'] ?? 0 ) ) ),
			];

			$updated = update_option( XYLUSEC_WIDGET_OPTIONS, $xylusec_so_widget_options );

			if ( $updated ) {
				$xylusec_success_msg[] = __( 'Widget Settings saved successfully.', 'xylus-events-calendar' );
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
