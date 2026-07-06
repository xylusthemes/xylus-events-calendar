<?php
/**
 * SEO & Schema Class
 * Handles JSON-LD event schema generation for SEO purposes.
 *
 * @package     Xylus_Events_Calendar
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xylus_Events_Calendar_SEO {

	public function __construct() {
		add_action( 'wp_head', array( $this, 'output_event_schema' ) );
	}

	/**
	 * Output JSON-LD Event Schema on single event pages.
	 */
	public function output_event_schema() {
		$options = get_option( XYLUSEC_OPTIONS, array() );
		$enable_schema = isset( $options['xylusec_enable_schema'] ) ? $options['xylusec_enable_schema'] : 'yes';
		
		if ( $enable_schema === 'no' ) {
			return;
		}

		if ( ! is_singular( 'eec_events' ) ) {
			return;
		}

		global $post, $xylusec_events_calendar;
		$event_id = $post->ID;

		// Fetch Event Dates
		$start_ts = get_post_meta( $event_id, 'start_ts', true );
		$end_ts   = get_post_meta( $event_id, 'end_ts', true );
		
		if ( ! $start_ts ) {
			return;
		}
		
		if ( ! $end_ts ) {
			$end_ts = $start_ts + 3600;
		}

		// Convert to ISO 8601 for Schema
		$tz_string = get_option( 'timezone_string' );
		if ( empty( $tz_string ) ) {
			$tz_string = 'UTC';
		}
		
		try {
			$tz = new DateTimeZone( $tz_string );
			
			$start_dt = new DateTime();
			$start_dt->setTimestamp( $start_ts );
			$start_dt->setTimezone( $tz );
			$start_iso = $start_dt->format( 'c' );
			
			$end_dt = new DateTime();
			$end_dt->setTimestamp( $end_ts );
			$end_dt->setTimezone( $tz );
			$end_iso = $end_dt->format( 'c' );
		} catch ( Exception $e ) {
			$start_iso = gmdate( 'c', $start_ts );
			$end_iso   = gmdate( 'c', $end_ts );
		}

		// Fetch Venue
		$venues = $xylusec_events_calendar->common->xylusec_get_event_venues( $event_id );
		
		// Fetch Organizer
		$organizers = $xylusec_events_calendar->common->xylusec_get_event_organizers( $event_id );
		
		// Build Schema Array
		$schema = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'Event',
			'name'        => get_the_title( $event_id ),
			'startDate'   => $start_iso,
			'endDate'     => $end_iso,
			'eventStatus' => 'https://schema.org/EventScheduled',
			'url'         => get_permalink( $event_id )
		);

		// Description
		$desc = wp_strip_all_tags( $post->post_content );
		if ( ! empty( $desc ) ) {
			$schema['description'] = wp_trim_words( $desc, 100 );
		}

		// Image
		$thumbnail_url = get_the_post_thumbnail_url( $event_id, 'full' );
		if ( $thumbnail_url ) {
			$schema['image'] = array( $thumbnail_url );
		}

		// Location
		if ( ! empty( $venues['name'] ) || ! empty( $venues['full_address'] ) ) {
			// Basic location schema
			$schema['eventAttendanceMode'] = 'https://schema.org/OfflineEventAttendanceMode';
			$schema['location'] = array(
				'@type' => 'Place',
				'name'  => ! empty( $venues['name'] ) ? $venues['name'] : get_bloginfo('name'),
				'address' => array(
					'@type' => 'PostalAddress',
				)
			);
			
			if ( ! empty( $venues['full_address'] ) ) $schema['location']['address']['streetAddress']   = $venues['full_address'];
			if ( ! empty( $venues['city'] ) )         $schema['location']['address']['addressLocality'] = $venues['city'];
			if ( ! empty( $venues['state'] ) )        $schema['location']['address']['addressRegion']   = $venues['state'];
			if ( ! empty( $venues['zip'] ) )          $schema['location']['address']['postalCode']      = $venues['zip'];
			if ( ! empty( $venues['country'] ) )      $schema['location']['address']['addressCountry']  = $venues['country'];
		} else {
			// If no physical location, could be online but let's just omit or set default.
		}

		// Organizer & Performer
		if ( ! empty( $organizers ) ) {
			$org = reset( $organizers ); // Take first organizer
			if ( ! empty( $org['name'] ) ) {
				$org_term = get_term_by( 'slug', $org['slug'], 'eec_organizer' );
				$org_url  = $org_term && ! is_wp_error( $org_term ) ? get_term_link( $org_term ) : site_url();
				
				$org_schema = array(
					'@type' => 'Organization',
					'name'  => $org['name'],
					'url'   => esc_url( $org_url )
				);
				$schema['organizer'] = $org_schema;
				$schema['performer'] = $org_schema;
			}
		} else {
			// Fallback organizer/performer to avoid Google Schema warnings
			$org_schema = array(
				'@type' => 'Organization',
				'name'  => get_bloginfo( 'name' ),
				'url'   => site_url()
			);
			$schema['organizer'] = $org_schema;
			$schema['performer'] = $org_schema;
		}

		// Registration / Website link & Offer
		$website = get_post_meta( $event_id, 'eec_event_link', true );
		$price   = get_post_meta( $event_id, 'event_price', true );
		if ( empty( $price ) ) {
			$price = '0';
		}
		
		$valid_from = get_the_date( 'c', $event_id );
		
		$schema['offers'] = array(
			'@type'         => 'Offer',
			'url'           => ! empty( $website ) ? esc_url_raw( $website ) : get_permalink( $event_id ),
			'price'         => $price,
			'priceCurrency' => 'USD',
			'availability'  => 'https://schema.org/InStock',
			'validFrom'     => $valid_from
		);

		echo "<!-- Xylus Events Calendar JSON-LD Event Schema -->\n";
		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
	}
}
