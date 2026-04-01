<?php
/**
 * Taxonomy archive template for Event Organizers (eec_organizer).
 *
 * Displays events associated with a specific organizer.
 * Showcases organizer details at the top.
 *
 * Override: Copy to {theme}/xylus-events-calendar/taxonomy-eec_organizer.php
 *
 * @package    Xylus_Events_Calendar
 * @since      1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$xylusec_term = get_queried_object();
$xylusec_email = get_term_meta( $xylusec_term->term_id, 'organizer_email', true );
$xylusec_phone = get_term_meta( $xylusec_term->term_id, 'organizer_phone', true );
?>

<div class="eec-archive-wrap">

	<div class="eec-archive-header eec-taxonomy-header">
		<div class="eec-taxonomy-badge"><?php esc_html_e( 'Organizer', 'xylus-events-calendar' ); ?></div><br>
		<h1 class="eec-archive-title"><?php echo esc_html( $xylusec_term->name ); ?></h1>
		
		<?php if ( $xylusec_email || $xylusec_phone || ! empty( $xylusec_term->description ) ) : ?>
			<div class="eec-taxonomy-meta-box">
				<?php if ( ! empty( $xylusec_term->description ) ) : ?>
					<div class="eec-archive-description"><?php echo wp_kses_post( wpautop( $xylusec_term->description ) ); ?></div>
				<?php endif; ?>

				<?php if ( $xylusec_email || $xylusec_phone ) : ?>
					<div class="eec-taxonomy-contact">
						<?php if ( $xylusec_email ) : ?>
							<a href="mailto:<?php echo esc_attr( $xylusec_email ); ?>" class="eec-contact-link">
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
								</svg>
								<?php echo esc_html( $xylusec_email ); ?>
							</a>
						<?php endif; ?>
						<?php if ( $xylusec_phone ) : ?>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $xylusec_phone ) ); ?>" class="eec-contact-link">
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
								</svg>
								<?php echo esc_html( $xylusec_phone ); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<?php
	// Per user request, we hide the filter bar on specific term archive pages.
	// Filters are available on the root /eec-organizer/ page.
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
		<div id="eec-events-grid" data-taxonomy="eec_organizer" data-term="<?php echo esc_attr( $xylusec_term->slug ); ?>">
			<?php 
			$query = $wp_query; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
			include XYLUSEC_PLUGIN_DIR . 'templates/loop/events-grid.php'; 
			?>
		</div>
	</div>

</div>

<?php
get_footer();
