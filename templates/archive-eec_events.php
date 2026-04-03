<?php
/**
 * Archive template for Easy Events Calendar (eec_events).
 *
 * Displays a list of events in a card-based grid layout,
 * complete with date badges, thumbnails, excerpts, and pagination.
 *
 * Includes a filter bar for searching and filtering by taxonomies.
 *
 * Override: Copy to {theme}/xylus-events-calendar/archive-eec_events.php
 *
 * @package    Xylus_Events_Calendar
 * @since      1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$xylusec_eec_view = get_query_var( 'eec_view' );
$xylusec_archive_title = __( 'All Events', 'xylus-events-calendar' );

if ( $xylusec_eec_view === 'venue_root' ) {
	$xylusec_archive_title = __( 'Events by Venue', 'xylus-events-calendar' );
} elseif ( $xylusec_eec_view === 'organizer_root' ) {
	$xylusec_archive_title = __( 'Events by Organizer', 'xylus-events-calendar' );
} elseif ( $xylusec_eec_view === 'category_root' ) {
	$xylusec_archive_title = __( 'Events by Category', 'xylus-events-calendar' );
} elseif ( $xylusec_eec_view === 'tag_root' ) {
	$xylusec_archive_title = __( 'Events by Tag', 'xylus-events-calendar' );
}
?>

<div class="eec-archive-wrap">

	<div class="eec-archive-header">
		<h1 class="eec-archive-title"><?php echo esc_html( $xylusec_archive_title ); ?></h1>
	</div>

	<?php
	// Filter bar data.
	$xylusec_all_categories = get_terms( array( 'taxonomy' => 'eec_category', 'hide_empty' => true ) );
	$xylusec_all_venues     = get_terms( array( 'taxonomy' => 'eec_venue', 'hide_empty' => true ) );
	$xylusec_all_organizers = get_terms( array( 'taxonomy' => 'eec_organizer', 'hide_empty' => true ) );

	// Current filter values.
	$xylusec_filter_search   = isset( $_GET['eec_search'] ) ? sanitize_text_field( wp_unslash( $_GET['eec_search'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$xylusec_filter_category = isset( $_GET['eec_category'] ) ? sanitize_text_field( wp_unslash( $_GET['eec_category'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$xylusec_filter_venue    = isset( $_GET['eec_venue'] ) ? sanitize_text_field( wp_unslash( $_GET['eec_venue'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$xylusec_filter_organizer = isset( $_GET['eec_organizer'] ) ? sanitize_text_field( wp_unslash( $_GET['eec_organizer'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	?>

	<div class="eec-filter-bar">
		<form method="get" class="eec-filter-form" id="eec-filter-ajax-form">
			<div class="eec-filter-search">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
				</svg>
				<input type="text" name="eec_search" placeholder="<?php esc_attr_e( 'Search events...', 'xylus-events-calendar' ); ?>" value="<?php echo esc_attr( $xylusec_filter_search ); ?>" class="eec-filter-input" />
			</div>

			<div class="eec-filter-select-wrap">
				<select name="eec_category" class="eec-filter-select">
					<option value=""><?php esc_html_e( 'All Categories', 'xylus-events-calendar' ); ?></option>
					<?php foreach ( $xylusec_all_categories as $cat ) : ?>
						<option value="<?php echo esc_attr( $cat->slug ); ?>" <?php selected( $xylusec_filter_category, $cat->slug ); ?>><?php echo esc_html( $cat->name ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="eec-filter-select-wrap">
				<select name="eec_venue" class="eec-filter-select">
					<option value=""><?php esc_html_e( 'All Venues', 'xylus-events-calendar' ); ?></option>
					<?php foreach ( $xylusec_all_venues as $xylusec_venue ) : ?>
						<option value="<?php echo esc_attr( $xylusec_venue->slug ); ?>" <?php selected( $xylusec_filter_venue, $xylusec_venue->slug ); ?>><?php echo esc_html( $xylusec_venue->name ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="eec-filter-select-wrap">
				<select name="eec_organizer" class="eec-filter-select">
					<option value=""><?php esc_html_e( 'All Organizers', 'xylus-events-calendar' ); ?></option>
					<?php foreach ( $xylusec_all_organizers as $xylusec_org ) : ?>
						<option value="<?php echo esc_attr( $xylusec_org->slug ); ?>" <?php selected( $xylusec_filter_organizer, $xylusec_org->slug ); ?>><?php echo esc_html( $xylusec_org->name ); ?></option>
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

			<?php if ( $xylusec_filter_search || $xylusec_filter_category || $xylusec_filter_venue || $xylusec_filter_organizer ) : ?>
				<?php 
				$xylusec_reset_url = get_post_type_archive_link( 'eec_events' );
				if ( $xylusec_eec_view === 'venue_root' ) {
					$xylusec_reset_url = home_url( '/eec-venue/' );
				} elseif ( $xylusec_eec_view === 'organizer_root' ) {
					$xylusec_reset_url = home_url( '/eec-organizer/' );
				} elseif ( $xylusec_eec_view === 'category_root' ) {
					$xylusec_reset_url = home_url( '/eec-category/' );
				} elseif ( $xylusec_eec_view === 'tag_root' ) {
					$xylusec_reset_url = home_url( '/eec-tag/' );
				}
				?>
				<a href="<?php echo esc_url( $xylusec_reset_url ); ?>" class="eec-filter-reset"><?php esc_html_e( 'Reset', 'xylus-events-calendar' ); ?></a>
			<?php endif; ?>
		</form>
	</div>

	<div class="eec-results-info">
		<?php
		global $wp_query;
		$xylusec_found = $wp_query->found_posts;
		/* translators: %d: number of events found */
		printf( esc_html( _n( '%d event found', '%d events found', $xylusec_found, 'xylus-events-calendar' ) ), intval( $xylusec_found ) );
		?>
	</div>

	<div class="eec-ajax-results-container" style="position: relative; min-height: 200px;">
		<div class="eec-ajax-loader" style="display:none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); z-index: 10; align-items: center; justify-content: center;">
			<div class="eec-spinner"></div>
		</div>
		<div id="eec-events-grid" data-taxonomy="" data-term="">
			<?php 
			$query = $wp_query; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
			include XYLUSEC_PLUGIN_DIR . 'templates/loop/events-grid.php'; 
			?>
		</div>
	</div>

</div>

<?php
get_footer();
