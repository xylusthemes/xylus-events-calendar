<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
/**
 * Single event template for Easy Events Calendar (eec_events).
 *
 * Displays the full event detail page with featured image, event content,
 * details sidebar, venue map, and related events.
 *
 * Designed to be fully compatible with any WordPress theme — uses a
 * self-contained wrapper with namespaced CSS classes to avoid conflicts.
 *
 * Override: Copy to {theme}/xylus-events-calendar/single-eec_events.php
 *
 * @package    Xylus_Events_Calendar
 * @since      1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
global $xylusec_events_calendar;

while ( have_posts() ) : the_post();

	if ( ! isset( $xylusec_event_id ) || empty( $xylusec_event_id ) ) {
		$xylusec_event_id = get_the_ID();
	}

	/** Core */
	$xylusec_title               = get_the_title( $xylusec_event_id );
	$xylusec_content             = get_post_field( 'post_content', $xylusec_event_id );
	$xylusec_thumbnail           = get_the_post_thumbnail_url( $xylusec_event_id, 'large' );

	/** Meta */
	$xylusec_next_instance = $xylusec_events_calendar->common->xylusec_get_next_event_instance( $xylusec_event_id );

	if ( $xylusec_next_instance ) {
		$xylusec_start_date_str = strtotime( $xylusec_next_instance->start_date );
		$xylusec_end_date_str   = strtotime( $xylusec_next_instance->end_date );
	} else {
		$xylusec_start_date_str = get_post_meta( $xylusec_event_id, 'start_ts', true );
		$xylusec_end_date_str   = get_post_meta( $xylusec_event_id, 'end_ts', true );
	}

	$xylusec_start_date_formated = $xylusec_start_date_str ? date_i18n( 'F j, Y', $xylusec_start_date_str ) : '';
	$xylusec_end_date_formated   = $xylusec_end_date_str ? date_i18n( 'F j, Y', $xylusec_end_date_str ) : '';
	$xylusec_start_time_formated = $xylusec_start_date_str ? date_i18n( 'g:i A', $xylusec_start_date_str ) : '';
	$xylusec_end_time_formated   = $xylusec_end_date_str ? date_i18n( 'g:i A', $xylusec_end_date_str ) : '';
	$xylusec_website             = get_post_meta( $xylusec_event_id, 'eec_event_link', true );
	$xylusec_real_time           = current_time( 'timestamp' );

	/** Taxonomies */
	$xylusec_tags                = $xylusec_events_calendar->common->xylusec_get_event_tags( $xylusec_event_id );
	$xylusec_eec_categories      = $xylusec_events_calendar->common->xylusec_get_event_categories( $xylusec_event_id );
	$xylusec_eec_venues          = $xylusec_events_calendar->common->xylusec_get_event_venues( $xylusec_event_id );
	$xylusec_eec_organizers      = $xylusec_events_calendar->common->xylusec_get_event_organizers( $xylusec_event_id );
	$xylusec_eec_collections     = $xylusec_events_calendar->common->xylusec_get_event_collections( $xylusec_event_id );

	/** Status */
	$xylusec_status       = ( $xylusec_end_date_str && $xylusec_real_time > $xylusec_end_date_str ) ? 'past' : 'upcoming';
	$xylusec_status_label = ( $xylusec_status === 'past' ) ? __( 'Past Event', 'xylus-events-calendar' ) : __( 'Upcoming', 'xylus-events-calendar' );

	/** Venue data */
	$xylusec_venue_name      = ! empty( $xylusec_eec_venues['name'] ) ? $xylusec_eec_venues['name'] : '';
	$xylusec_venue_address   = ! empty( $xylusec_eec_venues['full_address'] ) ? $xylusec_eec_venues['full_address'] : '';
	$xylusec_venue_city      = ! empty( $xylusec_eec_venues['city'] ) ? $xylusec_eec_venues['city'] : '';
	$xylusec_venue_state     = ! empty( $xylusec_eec_venues['state'] ) ? $xylusec_eec_venues['state'] : '';
	$xylusec_venue_country   = ! empty( $xylusec_eec_venues['country'] ) ? $xylusec_eec_venues['country'] : '';
	$xylusec_venue_zip       = ! empty( $xylusec_eec_venues['zip'] ) ? $xylusec_eec_venues['zip'] : '';
	$xylusec_venue_latitude  = ! empty( $xylusec_eec_venues['latitude'] ) ? $xylusec_eec_venues['latitude'] : '';
	$xylusec_venue_longitude = ! empty( $xylusec_eec_venues['longitude'] ) ? $xylusec_eec_venues['longitude'] : '';

	/** Date badge parts */
	$xylusec_badge_month = $xylusec_start_date_str ? strtoupper( date_i18n( 'M', $xylusec_start_date_str ) ) : '';
	$xylusec_badge_day   = $xylusec_start_date_str ? date_i18n( 'd', $xylusec_start_date_str ) : '';

	/** Map Data */
	$xylusec_map_url = '';
	if ( ! empty( $xylusec_venue_name ) ) {
		$xylusec_q = '';
		if ( ! empty( $xylusec_venue_latitude ) && ! empty( $xylusec_venue_longitude ) ) {
			$xylusec_q = $xylusec_venue_latitude . ',' . $xylusec_venue_longitude;
		}
		
		if ( ! empty( $xylusec_venue_address ) ) {
			$xylusec_q = $xylusec_venue_address;
		}
		
		if ( ! empty( $xylusec_venue_name ) && ! empty( $xylusec_venue_address ) ) {
			$xylusec_q = $xylusec_venue_name . ', ' . $xylusec_venue_address;
		}

		if ( ! empty( $xylusec_q ) ) {
			// Construct a more comprehensive address string for Google Maps search
			$xylusec_addr_parts = array_filter( array( $xylusec_venue_address, $xylusec_venue_city, $xylusec_venue_state, $xylusec_venue_country, $xylusec_venue_zip ) );
			$xylusec_full_q     = implode( ', ', $xylusec_addr_parts );
			
			if ( ! empty( $xylusec_venue_name ) ) {
				$xylusec_full_q .= ' (' . $xylusec_venue_name . ')';
			}

			if ( empty( $xylusec_full_q ) ) {
				$xylusec_full_q = $xylusec_q;
			}

			$xylusec_map_url = "https://maps.google.com/maps?q=" . urlencode( $xylusec_full_q ) . "&hl=en&z=14&output=embed";
		}
	}
	?>
	<div class="eec-single-main" >
		<div class="eec-single-wrap">

			<!-- ===== Header: Title + Status Badge ===== -->
			<div class="eec-single-header">
				<div class="eec-single-header-meta">
					<?php if ( ! empty( $xylusec_eec_categories ) ) : ?>
						<div class="eec-single-categories-inline">
							<?php foreach ( $xylusec_eec_categories as $cat ) : ?>
								<span class="eec-single-cat-badge"><?php echo esc_html( $cat['name'] ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<span class="eec-single-status eec-single-status--<?php echo esc_attr( $xylusec_status ); ?>">
						<?php echo esc_html( $xylusec_status_label ); ?>
					</span>
				</div>
				<h1 class="eec-single-title"><?php echo esc_html( $xylusec_title ); ?></h1>

				<!-- Quick meta bar -->
				<?php if ( $xylusec_start_date_formated || ! empty( $xylusec_venue_name ) ) : ?>
					<div class="eec-single-quick-meta">
						<?php if ( $xylusec_start_date_formated ) : ?>
							<span class="eec-single-quick-item">
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
								</svg>
								<?php echo esc_html( $xylusec_start_date_formated ); ?>
								<?php if ( $xylusec_start_time_formated ) : ?>
									<span class="eec-single-time-sep">·</span> <?php echo esc_html( $xylusec_start_time_formated ); ?>
								<?php endif; ?>
							</span>
						<?php endif; ?>

						<?php if ( ! empty( $xylusec_venue_name ) ) : ?>
							<span class="eec-single-quick-item">
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
								</svg>
								<?php
								echo esc_html( $xylusec_venue_name );
								if ( ! empty( $xylusec_venue_city ) ) {
									echo ', ' . esc_html( $xylusec_venue_city );
								}
								?>
							</span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<!-- ===== Main 2-Column Layout ===== -->
			<div class="eec-single-body">

				<!-- Left Column: Image + Content -->
				<div class="eec-single-main">

					<?php if ( $xylusec_thumbnail ) : ?>
						<div class="eec-single-image-wrap">
							<img src="<?php echo esc_url( $xylusec_thumbnail ); ?>" alt="<?php echo esc_attr( $xylusec_title ); ?>" class="eec-single-image" />
							<?php if ( $xylusec_badge_month && $xylusec_badge_day ) : ?>
								<div class="event-date-badge">
									<div class="event-date-month"><?php echo esc_html( $xylusec_badge_month ); ?></div>
									<div class="event-date-day"><?php echo esc_html( $xylusec_badge_day ); ?></div>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="eec-single-content">
						<h2 class="event-section-title"><?php esc_html_e( 'About This Event', 'xylus-events-calendar' ); ?></h2>
						<div class="eec-single-description">
							<?php echo wp_kses_post( $xylusec_content ); ?>
						</div>
						<?php do_action( 'eec_after_event_description_inner', $xylusec_event_id ); ?>
					</div>

					<?php if ( ! empty( $xylusec_tags ) ) : ?>
						<div class="eec-single-tags">
							<h3 class="eec-single-tags-title"><?php esc_html_e( 'Tags', 'xylus-events-calendar' ); ?></h3>
							<div class="event-tags-container">
								<?php foreach ( $xylusec_tags as $xylusec_tag ) : 
									$xylusec_term = get_term_by( 'slug', $xylusec_tag['slug'], 'eec_tag' );
									if ( $xylusec_term ) :
										$xylusec_url = get_term_link( $xylusec_term );
								?>
									<a href="<?php echo esc_url( $xylusec_url ); ?>" class="event-tag">#<?php echo esc_html( $xylusec_tag['name'] ); ?></a>
								<?php endif; endforeach; ?>
							</div>
						</div>
						<br>
					<?php endif; ?>
				</div>

				<!-- Right Column: Details Sidebar -->
				<aside class="eec-single-sidebar">
					<div class="event-details-card">
						<h3 class="event-card-title"><?php esc_html_e( 'Event Details', 'xylus-events-calendar' ); ?></h3>
						<div class="event-divider"></div>

						<!-- Start Date -->
						<div class="event-detail-row">
							<div class="event-detail-icon">
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
								</svg>
							</div>
							<div class="event-detail-content">
								<div class="event-detail-label"><?php esc_html_e( 'Start Date', 'xylus-events-calendar' ); ?></div>
								<div class="event-detail-value">
									<?php
									echo esc_html( $xylusec_start_date_formated );
									if ( $xylusec_start_time_formated ) {
										echo ' · ' . esc_html( $xylusec_start_time_formated );
									}
									?>
								</div>
							</div>
						</div>

						<!-- End Date -->
						<?php if ( $xylusec_end_date_str ) : ?>
							<div class="event-detail-row">
								<div class="event-detail-icon">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
									</svg>
								</div>
								<div class="event-detail-content">
									<div class="event-detail-label"><?php esc_html_e( 'End Date', 'xylus-events-calendar' ); ?></div>
									<div class="event-detail-value">
										<?php
										echo esc_html( $xylusec_end_date_formated );
										if ( $xylusec_end_time_formated ) {
											echo ' · ' . esc_html( $xylusec_end_time_formated );
										}
										?>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<!-- Category -->
						<?php if ( ! empty( $xylusec_eec_categories ) ) : ?>
							<div class="event-detail-row">
								<div class="event-detail-icon">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
									</svg>
								</div>
								<div class="event-detail-content">
									<div class="event-detail-label"><?php esc_html_e( 'Category', 'xylus-events-calendar' ); ?></div>
									<div class="event-detail-value">
										<?php
										$xylusec_cat_links = array();
										foreach ( $xylusec_eec_categories as $cat ) {
											$xylusec_term = get_term_by( 'slug', $cat['slug'], 'eec_category' );
											if ( $xylusec_term ) {
												$xylusec_url = get_term_link( $xylusec_term );
												$xylusec_cat_links[] = sprintf( '<a href="%s">%s</a>', esc_url( $xylusec_url ), esc_html( $cat['name'] ) );
											}
										}
										echo wp_kses_post( implode( ', ', $xylusec_cat_links ) );
										?>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<!-- Collection -->
						<?php if ( ! empty( $xylusec_eec_collections ) ) : ?>
							<div class="event-detail-row">
								<div class="event-detail-icon">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
									</svg>
								</div>
								<div class="event-detail-content">
									<div class="event-detail-label"><?php esc_html_e( 'Collection', 'xylus-events-calendar' ); ?></div>
									<div class="event-detail-value">
										<?php
										$xylusec_col_links = array();
										foreach ( $xylusec_eec_collections as $col ) {
											$xylusec_term = get_term_by( 'slug', $col['slug'], 'eec_collection' );
											if ( $xylusec_term ) {
												$xylusec_url = get_term_link( $xylusec_term );
												$xylusec_col_links[] = sprintf( '<a href="%s">%s</a>', esc_url( $xylusec_url ), esc_html( $col['name'] ) );
											}
										}
										echo wp_kses_post( implode( ', ', $xylusec_col_links ) );
										?>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<!-- Organizer -->
						<?php if ( ! empty( $xylusec_eec_organizers ) ) : ?>
							<div class="event-detail-row">
								<div class="event-detail-icon">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
									</svg>
								</div>
								<div class="event-detail-content">
									<div class="event-detail-label"><?php esc_html_e( 'Organizer', 'xylus-events-calendar' ); ?></div>
									<div class="event-detail-value">
										<?php
										$xylusec_organizer_links = array();
										foreach ( $xylusec_eec_organizers as $xylusec_organizer ) {
											if ( empty( $xylusec_organizer['slug'] ) ) {
												continue;
											}
											$xylusec_term = get_term_by( 'slug', $xylusec_organizer['slug'], 'eec_organizer' );
											if ( ! $xylusec_term || is_wp_error( $xylusec_term ) ) {
												continue;
											}
											$xylusec_url = get_term_link( $xylusec_term );
											if ( ! is_wp_error( $xylusec_url ) ) {
												$xylusec_organizer_links[] = sprintf(
													'<a href="%s">%s</a>',
													esc_url( $xylusec_url ),
													esc_html( $xylusec_organizer['name'] )
												);
											}
										}
										echo wp_kses_post( implode( ', ', $xylusec_organizer_links ) );
										?>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<!-- Tags -->
						<?php if ( ! empty( $xylusec_tags ) ) : ?>
							<div class="event-detail-row">
								<div class="event-detail-icon">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
									</svg>
								</div>
								<div class="event-detail-content">
									<div class="event-detail-label"><?php esc_html_e( 'Tags', 'xylus-events-calendar' ); ?></div>
									<div class="event-detail-value">
										<?php
										$xylusec_tag_links = array();
										foreach ( $xylusec_tags as $xylusec_tag ) {
											if ( empty( $xylusec_tag['slug'] ) ) continue;
											$xylusec_term = get_term_by( 'slug', $xylusec_tag['slug'], 'eec_tag' );
											if ( $xylusec_term ) {
												$xylusec_url = get_term_link( $xylusec_term );
												$xylusec_tag_links[] = sprintf( '<a href="%s">#%s</a>', esc_url( $xylusec_url ), esc_html( $xylusec_tag['name'] ) );
											}
										}
										echo wp_kses_post( implode( ', ', $xylusec_tag_links ) );
										?>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<!-- Venue -->
						<?php if ( ! empty( $xylusec_venue_name ) ) : ?>
							<div class="event-detail-row">
								<div class="event-detail-icon">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
									</svg>
								</div>
								<div class="event-detail-content">
									<div class="event-detail-label"><?php esc_html_e( 'Venue', 'xylus-events-calendar' ); ?></div>
									<div class="event-detail-value">
										<?php
										$xylusec_venue_term = get_term_by( 'slug', $xylusec_eec_venues['slug'], 'eec_venue' );
										if ( $xylusec_venue_term ) {
											echo sprintf( '<a href="%s">%s</a>', esc_url( get_term_link( $xylusec_venue_term ) ), esc_html( $xylusec_venue_name ) );
										} else {
											echo esc_html( $xylusec_venue_name );
										}
										
										if ( ! empty( $xylusec_venue_address ) ) {
											echo '<br><small class="eec-sidebar-address">' . esc_html( $xylusec_venue_address ) . '</small>';
										}
										?>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<!-- Registration -->
						<?php if ( $xylusec_website ) : ?>
							<div class="event-divider"></div>
							<a href="<?php echo esc_url( $xylusec_website ); ?>" target="_blank" rel="noopener noreferrer" class="eec-single-register-btn">
								<?php esc_html_e( 'Register Now', 'xylus-events-calendar' ); ?>
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
								</svg>
							</a>
						<?php endif; ?>

						<!-- Add to Calendar -->
						<?php
						$cal_urls = Xylus_Events_Calendar_iCal::get_calendar_urls( $xylusec_event_id );
						if ( ! empty( $cal_urls ) ) :
						?>
							<div class="event-divider"></div>
							<div class="eec-add-to-calendar-wrap" style="position: relative;">
								<button type="button" class="eec-single-register-btn eec-add-to-calendar-btn" onclick="this.nextElementSibling.classList.toggle('show');">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
									</svg>
									<?php esc_html_e( 'Add to Calendar', 'xylus-events-calendar' ); ?>
								</button>
								<div class="eec-add-to-calendar-dropdown">
									<a href="<?php echo esc_url( $cal_urls['google'] ); ?>" target="_blank" rel="noopener noreferrer">Google Calendar</a>
									<a href="<?php echo esc_url( $cal_urls['yahoo'] ); ?>" target="_blank" rel="noopener noreferrer">Yahoo Calendar</a>
									<a href="<?php echo esc_url( $cal_urls['ics'] ); ?>">Apple / Outlook (ICS)</a>
								</div>
							</div>
							<style>
							.eec-add-to-calendar-dropdown {
								display: none; position: absolute; top: calc(100% + 5px); left: 0; right: 0; z-index: 100;
								background: #fff; border: 1px solid #eee; border-radius: 8px;
								box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden;
							}
							.eec-add-to-calendar-dropdown a {
								display: block; padding: 12px 15px; color: #334155 !important;
								text-decoration: none !important; font-size: 14px; font-weight: 500;
								border-bottom: 1px solid #f1f5f9; outline: none !important; transition: all 0.2s ease;
							}
							.eec-add-to-calendar-dropdown a:last-child {
								border-bottom: none;
							}
							.eec-add-to-calendar-dropdown a:hover,
							.eec-add-to-calendar-dropdown a:focus {
								background: #f8fafc; color: var(--xec-primary-color, #005AE0) !important;
								outline: none !important; box-shadow: none !important;
							}
							</style>
							<script>
							document.addEventListener('click', function(e) {
								if (!e.target.closest('.eec-add-to-calendar-wrap')) {
									var dropdowns = document.querySelectorAll('.eec-add-to-calendar-dropdown');
									dropdowns.forEach(function(d) {
										d.style.display = 'none';
										d.classList.remove('show');
									});
								}
							});
							var calBtn = document.querySelector('.eec-add-to-calendar-btn');
							if (calBtn) {
								calBtn.addEventListener('click', function(e) {
									e.preventDefault();
									var dropdown = this.nextElementSibling;
									if (dropdown.style.display === 'none' || dropdown.style.display === '') {
										dropdown.style.display = 'block';
									} else {
										dropdown.style.display = 'none';
									}
								});
								// Remove the inline onclick so it doesn't conflict
								calBtn.removeAttribute('onclick');
							}
							</script>
						<?php endif; ?>

						<div class="event-divider"></div>

						<!-- Share -->
						<div class="event-share-section">
							<div class="event-share-title"><?php esc_html_e( 'Share This Event', 'xylus-events-calendar' ); ?></div>
							<div class="event-social-icons">
								<?php
								$xylusec_share_url   = rawurlencode( get_permalink( $xylusec_event_id ) );
								$xylusec_share_title = rawurlencode( $xylusec_title );
								?>
								<a href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $xylusec_share_url ); ?>" target="_blank" rel="noopener noreferrer" class="event-social-icon facebook" aria-label="<?php esc_attr_e( 'Share on Facebook', 'xylus-events-calendar' ); ?>">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
								</a>
								<a href="<?php echo esc_url( 'https://x.com/intent/tweet?url=' . $xylusec_share_url . '&text=' . $xylusec_share_title ); ?>" target="_blank" rel="noopener noreferrer" class="event-social-icon x-twitter" aria-label="<?php esc_attr_e( 'Share on X', 'xylus-events-calendar' ); ?>" style="background: #000000;">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 22.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
								</a>
								<a href="<?php echo esc_url( 'https://www.linkedin.com/sharing/share-offsite/?url=' . $xylusec_share_url ); ?>" target="_blank" rel="noopener noreferrer" class="event-social-icon linkedin" aria-label="<?php esc_attr_e( 'Share on LinkedIn', 'xylus-events-calendar' ); ?>">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
								</a>
								<a href="<?php echo esc_url( 'mailto:?subject=' . $xylusec_share_title . '&body=' . $xylusec_share_url ); ?>" class="event-social-icon email" aria-label="<?php esc_attr_e( 'Share via Email', 'xylus-events-calendar' ); ?>">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
								</a>
							</div>
						</div>
					</div>
				</aside>

			</div>

			<!-- ===== Map Section ===== -->
			<?php if ( ! empty( $xylusec_venue_latitude ) && ! empty( $xylusec_venue_longitude ) ) : ?>
				<div class="eec-single-map">
					<h2 class="event-section-title"><?php esc_html_e( 'Event Location', 'xylus-events-calendar' ); ?></h2>
							
					<div class="event-location-info">
						<h3 class="event-venue-name"><?php echo esc_html( $xylusec_venue_name ); ?></h3>
						<?php if ( $xylusec_venue_address ) : ?>
							<p class="event-venue-address"><i><?php echo esc_html( $xylusec_venue_address ); ?></i></p>
						<?php endif; ?>
						<?php 
						$xylusec_venue_parts = array_filter( array( $xylusec_venue_city, $xylusec_venue_state, $xylusec_venue_country, $xylusec_venue_zip ) );
						if ( ! empty( $xylusec_venue_parts ) ) : ?>
							<p class="event-venue-location"><i><?php echo esc_html( implode( ', ', $xylusec_venue_parts ) ); ?></i></p>
						<?php endif; ?>
					</div>
					<div class="event-map-container">
						<iframe
							src="<?php echo esc_url( 'https://www.google.com/maps?q=' . $xylusec_venue_latitude . ',' . $xylusec_venue_longitude . '&output=embed' ); ?>"
							allowfullscreen
							loading="lazy"
							referrerpolicy="no-referrer-when-downgrade"
							title="<?php echo esc_attr( $xylusec_venue_name ); ?> - <?php esc_attr_e( 'Event Location Map', 'xylus-events-calendar' ); ?>"></iframe>
					</div>
				</div>
			<?php endif; ?>

		</div>

		<?php do_action( 'eec_after_event_description', $xylusec_event_id ); ?>
		
		<div class="eec-single-wrap">
			<?php
				// Related Events Section.
				echo do_shortcode( '[eec_event_details_releted_events event_id="' . intval( $xylusec_event_id ) . '"]' );
			?>
		</div>
	</div>
	<?php

endwhile;

get_footer();
