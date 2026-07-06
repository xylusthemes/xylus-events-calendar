<?php
/**
 * iCal Export Class
 * Handles global and single event ICS generation and Add to Calendar links.
 *
 * @package     Xylus_Events_Calendar
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xylus_Events_Calendar_iCal {

	public function __construct() {
		add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
		add_action( 'template_redirect', array( $this, 'handle_ical_requests' ) );
	}

	/**
	 * Register query variables for iCal requests.
	 */
	public function add_query_vars( $vars ) {
		$vars[] = 'xylusec_ical_feed';
		$vars[] = 'xylusec_ical_event_id';
		return $vars;
	}

	/**
	 * Intercept requests and generate ICS if query vars are present.
	 */
	public function handle_ical_requests() {
		if ( get_query_var( 'xylusec_ical_feed' ) ) {
			$this->generate_global_feed();
			exit;
		}

		$event_id = get_query_var( 'xylusec_ical_event_id' );
		if ( $event_id ) {
			$this->generate_single_event_feed( intval( $event_id ) );
			exit;
		}
	}

	/**
	 * Generate ICS for all upcoming events (Master Feed).
	 */
	private function generate_global_feed() {
		$args = array(
			'post_type'      => 'eec_events',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'meta_key'       => 'event_start_date',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'event_start_date',
					'value'   => date( 'Y-m-d' ),
					'compare' => '>=',
					'type'    => 'DATE'
				)
			)
		);

		$events = get_posts( $args );
		
		$this->output_ics_headers( 'events-calendar.ics' );
		echo $this->build_ics_content( $events, get_bloginfo('name') . ' Events' );
	}

	/**
	 * Generate ICS for a single event.
	 */
	private function generate_single_event_feed( $event_id ) {
		$event = get_post( $event_id );
		
		if ( ! $event || $event->post_type !== 'eec_events' || $event->post_status !== 'publish' ) {
			wp_die( esc_html__( 'Event not found or not published.', 'xylus-events-calendar' ) );
		}

		$filename = sanitize_title( $event->post_title ) . '.ics';
		$this->output_ics_headers( $filename );
		echo $this->build_ics_content( array( $event ), $event->post_title );
	}

	/**
	 * Output proper HTTP headers for ICS download.
	 */
	private function output_ics_headers( $filename ) {
		header( 'Content-Type: text/calendar; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
		header( 'Cache-Control: no-cache, no-store, must-revalidate' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );
	}

	/**
	 * Build the actual ICS string format.
	 */
	private function build_ics_content( $events, $calendar_name ) {
		$eol = "\r\n";
		$ics = "BEGIN:VCALENDAR" . $eol;
		$ics .= "VERSION:2.0" . $eol;
		$ics .= "PRODID:-//Xylus Themes//Easy Events Calendar//EN" . $eol;
		$ics .= "CALSCALE:GREGORIAN" . $eol;
		$ics .= "X-WR-CALNAME:" . $this->escape_string( $calendar_name ) . $eol;
		
		$timezone_string = get_option('timezone_string');
		if ( $timezone_string ) {
			$ics .= "X-WR-TIMEZONE:" . $timezone_string . $eol;
		}

		foreach ( $events as $event ) {
			global $xylusec_events_calendar;
			
			$start_timestamp = get_post_meta( $event->ID, 'start_ts', true );
			$end_timestamp   = get_post_meta( $event->ID, 'end_ts', true );
			
			$venue = '';
			if ( isset( $xylusec_events_calendar ) ) {
				$venues = $xylusec_events_calendar->common->xylusec_get_event_venues( $event->ID );
				if ( ! empty( $venues['name'] ) ) {
					$venue = $venues['name'];
					if ( ! empty( $venues['full_address'] ) ) {
						$venue .= ', ' . $venues['full_address'];
					} elseif ( ! empty( $venues['city'] ) ) {
						$venue .= ', ' . $venues['city'];
					}
				}
			}
			
			if ( ! $start_timestamp ) continue;
			if ( ! $end_timestamp ) $end_timestamp = $start_timestamp + 3600; // default 1 hr

			// Use site timezone to convert to UTC
			$dt_start = gmdate( 'Ymd\THis\Z', $this->get_utc_timestamp( $start_timestamp ) );
			$dt_end   = gmdate( 'Ymd\THis\Z', $this->get_utc_timestamp( $end_timestamp ) );
			$dt_stamp = gmdate( 'Ymd\THis\Z' );

			$description = $event->post_content;
			$description = wp_strip_all_tags( $description );
			$description = preg_replace( "/\r\n|\n|\r/", "\\n", $description );

			$ics .= "BEGIN:VEVENT" . $eol;
			$ics .= "UID:" . md5( $event->ID . $start_timestamp ) . "@" . $_SERVER['SERVER_NAME'] . $eol;
			$ics .= "DTSTAMP:" . $dt_stamp . $eol;
			$ics .= "DTSTART:" . $dt_start . $eol;
			$ics .= "DTEND:" . $dt_end . $eol;
			$ics .= "SUMMARY:" . $this->escape_string( $event->post_title ) . $eol;
			
			$thumbnail_url = get_the_post_thumbnail_url( $event->ID, 'full' );
			if ( $thumbnail_url ) {
				$ics .= "ATTACH:" . $this->escape_string( $thumbnail_url ) . $eol;
				$ics .= "IMAGE;VALUE=URI;DISPLAY=BADGE;FMTTYPE=image/jpeg:" . $this->escape_string( $thumbnail_url ) . $eol;
			}
			
			$ics .= "DESCRIPTION:" . $this->escape_string( $description ) . $eol;
			if ( $venue ) {
				$ics .= "LOCATION:" . $this->escape_string( $venue ) . $eol;
			}
			$ics .= "URL:" . get_permalink( $event->ID ) . $eol;
			$ics .= "END:VEVENT" . $eol;
		}

		$ics .= "END:VCALENDAR" . $eol;
		return $ics;
	}

	/**
	 * Convert local timestamp to UTC timestamp based on WP timezone
	 */
	private function get_utc_timestamp( $local_timestamp ) {
		$tz_string = get_option( 'timezone_string' );
		$tz_offset = get_option( 'gmt_offset', 0 ) * HOUR_IN_SECONDS;
		
		if ( $tz_string ) {
			try {
				$timezone = new DateTimeZone( $tz_string );
				$datetime = new DateTime( '@' . $local_timestamp );
				$offset = $timezone->getOffset( $datetime );
				return $local_timestamp - $offset;
			} catch ( Exception $e ) {
				return $local_timestamp - $tz_offset;
			}
		}
		
		return $local_timestamp - $tz_offset;
	}

	/**
	 * Escape special characters for ICS string
	 */
	private function escape_string( $string ) {
		$string = str_replace( array( '\\', ',', ';' ), array( '\\\\', '\,', '\;' ), $string );
		return $string;
	}

	/**
	 * Generate Add to Calendar URLs (Google, Yahoo, ICS)
	 */
	public static function get_calendar_urls( $event_id ) {
		$event = get_post( $event_id );
		if ( ! $event ) return array();
		
		global $xylusec_events_calendar;

		$start_timestamp = get_post_meta( $event->ID, 'start_ts', true );
		$end_timestamp   = get_post_meta( $event->ID, 'end_ts', true );
		
		$venue = '';
		if ( isset( $xylusec_events_calendar ) ) {
			$venues = $xylusec_events_calendar->common->xylusec_get_event_venues( $event->ID );
			if ( ! empty( $venues['name'] ) ) {
				$venue = $venues['name'];
				if ( ! empty( $venues['full_address'] ) ) {
					$venue .= ', ' . $venues['full_address'];
				} elseif ( ! empty( $venues['city'] ) ) {
					$venue .= ', ' . $venues['city'];
				}
			}
		}

		if ( ! $start_timestamp ) return array();
		if ( ! $end_timestamp ) $end_timestamp = $start_timestamp + 3600;

		$instance = new self();
		$dt_start = gmdate( 'Ymd\THis\Z', $instance->get_utc_timestamp( $start_timestamp ) );
		$dt_end   = gmdate( 'Ymd\THis\Z', $instance->get_utc_timestamp( $end_timestamp ) );

		$title = urlencode( $event->post_title );
		
		// Google Calendar supports HTML. Pass HTML if it fits within safe URL limits.
		$raw_desc = $event->post_content;
		$thumbnail_url = get_the_post_thumbnail_url( $event->ID, 'full' );
		
		if ( $thumbnail_url ) {
			$image_html = '<img src="' . esc_url( $thumbnail_url ) . '" alt="" style="max-width:100%; height:auto;"><br><br>';
			$raw_desc = $image_html . $raw_desc;
		}

		// Modern browsers handle larger URLs well, increased limit to 3000 to accommodate image tag
		if ( strlen( $raw_desc ) <= 3000 ) {
			$desc = urlencode( $raw_desc );
		} else {
			// If too long, safely strip tags before truncating to avoid broken HTML tags
			$stripped_desc = wp_strip_all_tags( $event->post_content );
			$desc = urlencode( substr( $stripped_desc, 0, 3000 ) . '...' );
		}
		$loc   = urlencode( $venue );

		$google_url = "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$title}&dates={$dt_start}/{$dt_end}&details={$desc}&location={$loc}";
		$yahoo_url  = "https://calendar.yahoo.com/?v=60&view=d&type=20&title={$title}&st={$dt_start}&et={$dt_end}&desc={$desc}&in_loc={$loc}";
		$ics_url    = site_url( '?xylusec_ical_event_id=' . $event_id );

		return array(
			'google' => $google_url,
			'yahoo'  => $yahoo_url,
			'ics'    => $ics_url
		);
	}
}
