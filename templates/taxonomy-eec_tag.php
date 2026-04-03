<?php
/**
 * Taxonomy archive template for Event Tags (eec_tag).
 *
 * Displays events filtered by a specific event tag.
 *
 * Override: Copy to {theme}/xylus-events-calendar/taxonomy-eec_tag.php
 *
 * @package    Xylus_Events_Calendar
 * @since      1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$term = get_queried_object();
?>

<div class="eec-archive-wrap">

	<div class="eec-archive-header">
		<div class="eec-taxonomy-badge"><?php esc_html_e( 'Tag', 'xylus-events-calendar' ); ?></div><br>
		<h1 class="eec-archive-title"><?php echo esc_html( $term->name ); ?></h1>
		<?php if ( ! empty( $term->description ) ) : ?>
			<p class="eec-archive-description"><?php echo esc_html( $term->description ); ?></p>
		<?php endif; ?>
	</div>

	<?php
	// Per user request, we hide the filter bar on specific term archive pages.
	// Filters are available on the root /eec-category/ and /eec-venue/ pages.
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
		<div class="eec-ajax-loader" style="display:none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); z-index: 10; align-items: center; justify-content: center;">
			<div class="eec-spinner"></div>
		</div>
		<div id="eec-events-grid" data-taxonomy="eec_tag" data-term="<?php echo esc_attr( $term->slug ); ?>">
			<?php 
			$query = $wp_query; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
			include XYLUSEC_PLUGIN_DIR . 'templates/loop/events-grid.php'; 
			?>
		</div>
	</div>

</div>

<?php
get_footer();
