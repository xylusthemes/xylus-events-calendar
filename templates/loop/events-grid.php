<?php
global $xylusec_events_calendar;
/**
 * Shared template for rendering the event grid loop.
 * Used by archive and taxonomy templates, and also for AJAX responses.
 *
 * @package Xylus_Events_Calendar
 * @since 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $query->have_posts() ) : ?>

	<div class="eec-archive-grid">

		<?php while ( $query->have_posts() ) : $query->the_post();

			$xylusec_event_id        = get_the_ID();
			$xylusec_current_post    = $query->post;
			
			// Use instance dates if available (from custom recurring query)
			if ( isset( $xylusec_current_post->instance_start ) ) {
				$xylusec_start_ts = strtotime( $xylusec_current_post->instance_start );
				$xylusec_end_ts   = isset( $xylusec_current_post->instance_end ) ? strtotime( $xylusec_current_post->instance_end ) : '';
			} else {
				$xylusec_start_ts = get_post_meta( $xylusec_event_id, 'start_ts', true );
				$xylusec_end_ts   = get_post_meta( $xylusec_event_id, 'end_ts', true );
			}

			$xylusec_real_time       = current_time( 'timestamp' );
			$xylusec_thumbnail       = get_the_post_thumbnail_url( $xylusec_event_id, 'medium' );
			$xylusec_event_permalink = get_permalink( $xylusec_event_id );

			// Date badge parts.
			$xylusec_month = $xylusec_start_ts ? strtoupper( date_i18n( 'M', $xylusec_start_ts ) ) : '';
			$xylusec_day   = $xylusec_start_ts ? date_i18n( 'd', $xylusec_start_ts ) : '';

			// Event status.
			$xylusec_status = ( $xylusec_end_ts && $xylusec_real_time > $xylusec_end_ts ) ? 'past' : 'upcoming';
		?>

			<div class="eec-archive-card <?php echo esc_attr( 'eec-status-' . $xylusec_status ); ?>">
				<div class="event-upcoming-image-wrapper">
					<a href="<?php echo esc_url( $xylusec_event_permalink ); ?>" aria-label="<?php the_title_attribute(); ?>">
						<?php if ( $xylusec_thumbnail ) : ?>
							<img src="<?php echo esc_url( $xylusec_thumbnail ); ?>"
								 alt="<?php the_title_attribute(); ?>"
								 class="event-upcoming-image"
								 loading="lazy" />
						<?php else : ?>
							<img src="<?php echo esc_url( $xylusec_events_calendar->common->xylusec_get_random_placeholder() ); ?>"
								 alt="<?php the_title_attribute(); ?>"
								 class="event-upcoming-image"
								 loading="lazy" />
						<?php endif; ?>
					</a>
					<?php if ( $xylusec_start_ts ) : ?>
						<div class="event-date-badge">
							<div class="event-date-month"><?php echo esc_html( $xylusec_month ); ?></div>
							<div class="event-date-day"><?php echo esc_html( $xylusec_day ); ?></div>
						</div>
					<?php endif; ?>
				</div>

				<div class="event-upcoming-content">
					<h2 class="event-upcoming-card-title">
						<a href="<?php echo esc_url( $xylusec_event_permalink ); ?>"><?php the_title(); ?></a>
					</h2>

					<?php if ( $xylusec_start_ts ) : ?>
						<div class="eec-archive-meta">
							<span class="eec-archive-date">
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
								</svg>
								<?php 
								$xylusec_time_format = get_option( 'time_format' );
								$xylusec_date_format = 'M j, Y';
								
								// Get formatted parts
								$xylusec_s_date = date_i18n( $xylusec_date_format, $xylusec_start_ts );
								$xylusec_s_time = date_i18n( $xylusec_time_format, $xylusec_start_ts );
								$xylusec_e_date = $xylusec_end_ts ? date_i18n( $xylusec_date_format, $xylusec_end_ts ) : '';
								$xylusec_e_time = $xylusec_end_ts ? date_i18n( $xylusec_time_format, $xylusec_end_ts ) : '';

								if ( $xylusec_end_ts ) {
									if ( gmdate( 'Ymd', $xylusec_start_ts ) === gmdate( 'Ymd', $xylusec_end_ts ) ) {
										// Same day: Date, Time - Time
										echo esc_html( $xylusec_s_date . ', ' . $xylusec_s_time . ' - ' . $xylusec_e_time );
									} else {
										// Different days: Date Time - Date Time
										echo esc_html( date_i18n( 'M j', $xylusec_start_ts ) . ', ' . $xylusec_s_time . ' - ' . date_i18n( 'M j, Y', $xylusec_end_ts ) . ', ' . $xylusec_e_time );
									}
								} else {
									// No end date/time
									echo esc_html( $xylusec_s_date . ', ' . $xylusec_s_time );
								}
								?>
							</span>
							<?php 
							$xylusec_venues = get_the_terms( $xylusec_event_id, 'eec_venue' );
							if ( ! empty( $xylusec_venues ) && ! is_wp_error( $xylusec_venues ) ) : 
								$xylusec_venue = array_shift( $xylusec_venues );
							?>
								<span class="eec-archive-location">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
									</svg>
									<?php echo esc_html( $xylusec_venue->name ); ?>
								</span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="event-upcoming-description">
						<?php
						$xylusec_excerpt = get_the_excerpt();
						if ( empty( $xylusec_excerpt ) ) {
							$xylusec_excerpt = wp_trim_words( get_the_content(), 12 );
						} else {
							$xylusec_excerpt = wp_trim_words( $xylusec_excerpt, 12 );
						}
						echo wp_kses_post( $xylusec_excerpt );
						?>
					</div>
				</div>
			</div>

		<?php endwhile; wp_reset_postdata(); ?>

	</div>

	<div class="eec-archive-pagination">
		<?php
		$xylusec_big = 999999999; // need an unlikely integer
		echo paginate_links( array( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'base'      => str_replace( $xylusec_big, '%#%', esc_url( get_pagenum_link( $xylusec_big ) ) ),
			'format'    => '?paged=%#%',
			'current'   => max( 1, $query->get( 'paged' ) ),
			'total'     => $query->max_num_pages,
			'mid_size'  => 2,
			'prev_text' => '&larr; ' . esc_html__( 'Previous', 'xylus-events-calendar' ),
			'next_text' => esc_html__( 'Next', 'xylus-events-calendar' ) . ' &rarr;',
		) );
		?>
	</div>

<?php else : ?>

	<div class="eec-no-events">
		<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="64" height="64">
			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
		</svg>
		<h2><?php esc_html_e( 'No Events Found', 'xylus-events-calendar' ); ?></h2>
		<p><?php esc_html_e( 'Try refining your search or filters to find what you\'re looking for.', 'xylus-events-calendar' ); ?></p>
	</div>

<?php endif; ?>
