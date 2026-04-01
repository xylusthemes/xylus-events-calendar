<?php
/**
 * Taxonomy archive template for Event Venues (eec_venue).
 *
 * Displays events associated with a specific venue.
 * Shows venue name, address, and an embedded Google Map at the top.
 *
 * Override: Copy to {theme}/xylus-events-calendar/taxonomy-eec_venue.php
 *
 * @package    Xylus_Events_Calendar
 * @since      1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$xylusec_term      = get_queried_object();
$xylusec_address   = get_term_meta( $xylusec_term->term_id, 'venue_full_address', true );
$xylusec_latitude  = get_term_meta( $xylusec_term->term_id, 'venue_latitude', true );
$xylusec_longitude = get_term_meta( $xylusec_term->term_id, 'venue_longitude', true );
?>

<div class="eec-archive-wrap">

	<div class="eec-archive-header eec-taxonomy-header">
		<div class="eec-taxonomy-badge"><?php esc_html_e( 'Venue', 'xylus-events-calendar' ); ?></div><br>
		<h1 class="eec-archive-title"><?php echo esc_html( $xylusec_term->name ); ?></h1>
		
		<?php if ( $xylusec_address || ! empty( $xylusec_term->description ) ) : ?>
			<div class="eec-taxonomy-meta-box">
				<?php if ( ! empty( $xylusec_term->description ) ) : ?>
					<div class="eec-archive-description"><?php echo wp_kses_post( wpautop( $xylusec_term->description ) ); ?></div>
				<?php endif; ?>

				<?php if ( $xylusec_address ) : ?>
					<div class="eec-taxonomy-address">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
						</svg>
						<address><?php echo esc_html( $xylusec_address ); ?></address>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $xylusec_latitude ) && ! empty( $xylusec_longitude ) ) : ?>
			<div class="eec-taxonomy-map">
				<iframe
					src="<?php echo esc_url( 'https://www.google.com/maps?q=' . $xylusec_latitude . ',' . $xylusec_longitude . '&output=embed' ); ?>"
					allowfullscreen
					loading="lazy"
					title="<?php esc_attr_e( 'Venue Location Map', 'xylus-events-calendar' ); ?>"></iframe>
			</div>
		<?php endif; ?>
	</div>

	<?php
	// Per user request, we hide the filter bar on specific term archive pages.
	// Filters are available on the root /eec-venue/ page.
	?>

	<div class="eec-results-info">
		<?php
		global $wp_query;
		$xylusec_found = $wp_query->found_posts;
		/* translators: %d: number of events found */
		printf( esc_html( _n( '%d event found', '%d events found', $xylusec_found, 'xylus-events-calendar' ) ), intval( $xylusec_found ) );
		?>
	</div>

	<div class="eec-ajax-results-container" style="position: relative; min-height: 200px;">
		<div id="eec-events-grid" data-taxonomy="eec_venue" data-term="<?php echo esc_attr( $xylusec_term->slug ); ?>">
			<?php 
			$query = $wp_query; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
			include XYLUSEC_PLUGIN_DIR . 'templates/loop/events-grid.php'; 
			?>
		</div>
	</div>

</div>

<?php
get_footer();
