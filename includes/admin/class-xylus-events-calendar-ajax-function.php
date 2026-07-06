<?php
/**
 * Common functions class for Xylus Events Calendar
 *
 * @link       http://xylusthemes.com/
 * @since      1.0.0
 *
 * @package    Xylus_Events_Calendar
 * @subpackage Xylus_Events_Calendar/includes/admin
 * @author     Rajat Patel <prajat21@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xylus_Events_Calendar_Ajax_Handler {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */


	private $xylusec_options;
	
	public function __construct() {

		 $this->xylusec_options = get_option( XYLUSEC_OPTIONS, true );

		// Fetch events for the calendar
        add_action('wp_ajax_xylusec_get_events', array( $this, 'xylusec_get_events' ) );
		add_action('wp_ajax_nopriv_xylusec_get_events', array( $this, 'xylusec_get_events' ) );

		// Load more events for the calendar
		add_action('wp_ajax_xylusec_load_more_events', array( $this, 'xylusec_load_more_events' ) );
		add_action('wp_ajax_nopriv_xylusec_load_more_events', array( $this, 'xylusec_load_more_events' ) );

		// Grid view
		add_action('wp_ajax_xylusec_load_more_row_events', array( $this, 'xylusec_load_more_row_events' ) );
		add_action('wp_ajax_nopriv_xylusec_load_more_row_events', array( $this, 'xylusec_load_more_row_events' ) );

		// Staggered grid view
		add_action('wp_ajax_xylusec_load_more_staggered_events', array( $this, 'xylusec_load_more_staggered_events' ) );
		add_action('wp_ajax_nopriv_xylusec_load_more_staggered_events', array( $this, 'xylusec_load_more_staggered_events' ) );

		// Slider view
		add_action('wp_ajax_xylusec_load_more_slider_events', array( $this, 'xylusec_load_more_slider_events' ) );
		add_action('wp_ajax_nopriv_xylusec_load_more_slider_events', array( $this, 'xylusec_load_more_slider_events' ) );
	} 

	/**
	 * Fetch events for the calendar.
	 *
	 * @return void
	 */
	public function xylusec_get_events() {
		global $xylusec_events_calendar;
		check_ajax_referer('xylusec_nonce', 'nonce');
		
		$atts_json          = isset( $_GET['shortcode_atts'] ) ? $_GET['shortcode_atts'] : '{}'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
    	$atts               = json_decode( stripslashes($atts_json), true );
		$category           = isset( $_REQUEST['category'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['category'] ) ) : ( isset( $atts['category'] ) ? $atts['category'] : '' );
		$cats               = array_map( 'trim', explode( ',', $category ) );
		$collection         = isset( $_REQUEST['collection'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['collection'] ) ) : ( isset( $atts['collection'] ) ? $atts['collection'] : '' );
		$cols               = array_map( 'trim', explode( ',', $collection ) );
		$venue              = isset( $_REQUEST['venue'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['venue'] ) ) : ( isset( $atts['venue'] ) ? $atts['venue'] : '' );
		$organizer          = isset( $_REQUEST['organizer'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['organizer'] ) ) : ( isset( $atts['organizer'] ) ? $atts['organizer'] : '' );
		$tag                = isset( $_REQUEST['tag'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['tag'] ) ) : ( isset( $atts['tag'] ) ? $atts['tag'] : '' );
		$day                = isset( $_REQUEST['day'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['day'] ) ) : '';
		$time               = isset( $_REQUEST['time'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['time'] ) ) : '';
		$date_from          = isset( $_REQUEST['date_from'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['date_from'] ) ) : '';
		$date_to            = isset( $_REQUEST['date_to'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['date_to'] ) ) : '';

		$start              = isset( $_GET['start'] ) ? (int)esc_attr( sanitize_text_field( wp_unslash( $_GET['start'] ) ) ) : '';
		$end                = isset( $_GET['end'] ) ? (int)esc_attr( sanitize_text_field( wp_unslash( $_GET['end'] ) ) ) : '';	
		$selected_post_type = isset( $this->xylusec_options['xylusec_event_source'] ) ? $this->xylusec_options['xylusec_event_source'] : '';
        $selected_taxonomy  = $xylusec_events_calendar->common->get_selected_post_type_category( $selected_post_type );

		if( $selected_post_type == 'ajde_events' ){
			$start_key = 'evcal_srow';
			$end_key   = 'evcal_erow';
			$type      = 'NUMERIC'; 
		}elseif( $selected_post_type == 'event' ){
			$start_key = '_event_start';
			$end_key   = '_event_end';
			$type      = 'DATETIME';
			$start     = gmdate( 'Y-m-d H:i:s', $start );
			$end       = gmdate( 'Y-m-d H:i:s', $end );
		}else{
			$start_key = 'start_ts';
			$end_key   = 'end_ts';
			$type      = 'NUMERIC';
		}

		// If it's our plugin's event post type, use the instances table for better performance and recurrence support
		if ( $selected_post_type === 'eec_events' ) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'eec_event_instances';
			
			// Build query for instances
			$query_str = $wpdb->prepare( 
				"SELECT i.*, p.post_title, p.post_excerpt, p.post_content FROM $table_name i JOIN $wpdb->posts p ON i.event_id = p.ID WHERE p.post_status = 'publish' AND i.start_date <= %s AND i.end_date >= %s", //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				gmdate( 'Y-m-d H:i:s', $end ),
				gmdate( 'Y-m-d H:i:s', $start )
			);

			// Apply Taxonomy Filters (Category, Venue, Organizer, Collection, Tag)
			$tax_filters = array(
				$selected_taxonomy => $category,
				'eec_collection'   => $collection,
				'eec_venue'        => $venue,
				'eec_organizer'    => $organizer,
				'eec_tag'          => $tag,
			);

			foreach ( $tax_filters as $tax => $slug ) {
				if ( ! empty( $slug ) ) {
					$slugs = array_map( 'trim', explode( ',', $slug ) );
					$terms = get_terms( array(
						'taxonomy'   => $tax,
						'slug'       => $slugs,
						'fields'     => 'all',
						'hide_empty' => false,
					) );
					if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
						$tt_ids = wp_list_pluck( $terms, 'term_taxonomy_id' );
						$query_str .= " AND i.event_id IN (
							SELECT object_id FROM $wpdb->term_relationships 
							WHERE term_taxonomy_id IN (" . implode( ',', array_map( 'intval', $tt_ids ) ) . ")
						)";
					} else {
						$query_str .= " AND 1=0";
					}
				}
			}

			// Apply Day Filter
			if ( ! empty( $day ) ) {
				$day_array = array_map( 'trim', explode( ',', $day ) );
				$day_nums = array();
				$day_map = array(
					'sunday'    => 1, 'sun' => 1, 'su' => 1,
					'monday'    => 2, 'mon' => 2, 'mo' => 2,
					'tuesday'   => 3, 'tue' => 3, 'tu' => 3,
					'wednesday' => 4, 'wed' => 4, 'we' => 4,
					'thursday'  => 5, 'thu' => 5, 'th' => 5,
					'friday'    => 6, 'fri' => 6, 'fr' => 6,
					'saturday'  => 7, 'sat' => 7, 'sa' => 7,
				);
				foreach ( $day_array as $d ) {
					$d_lower = strtolower( $d );
					if ( isset( $day_map[ $d_lower ] ) ) {
						$day_nums[] = $day_map[ $d_lower ];
					} elseif ( is_numeric( $d ) ) {
						$day_nums[] = intval( $d );
					}
				}
				if ( ! empty( $day_nums ) ) {
					$query_str .= " AND DAYOFWEEK(i.start_date) IN (" . implode( ',', array_map( 'intval', $day_nums ) ) . ")";
				}
			}

			// Apply Time Filter
			if ( ! empty( $time ) ) {
				$time_array = array_map( 'trim', explode( ',', strtolower( $time ) ) );
				$time_clauses = array();
				foreach ( $time_array as $t ) {
					if ( $t === 'morning' ) {
						$time_clauses[] = "HOUR(i.start_date) BETWEEN 6 AND 11";
					} elseif ( $t === 'afternoon' ) {
						$time_clauses[] = "HOUR(i.start_date) BETWEEN 12 AND 16";
					} elseif ( $t === 'evening' ) {
						$time_clauses[] = "HOUR(i.start_date) BETWEEN 17 AND 20";
					} elseif ( $t === 'night' ) {
						$time_clauses[] = "(HOUR(i.start_date) >= 21 OR HOUR(i.start_date) < 6)";
					}
				}
				if ( ! empty( $time_clauses ) ) {
					$query_str .= " AND (" . implode( " OR ", $time_clauses ) . ")";
				}
			}

			// Apply Date From & To
			if ( ! empty( $date_from ) ) {
				$query_str .= $wpdb->prepare( " AND i.start_date >= %s", $date_from . ' 00:00:00' );
			}
			if ( ! empty( $date_to ) ) {
				$query_str .= $wpdb->prepare( " AND i.end_date <= %s", $date_to . ' 23:59:59' );
			}

			error_log( print_r( $query_str, true ) );

			$results = $wpdb->get_results( $query_str ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			
			$color_palette = [ '#bee9fa','#d1f8d1','#f8cfcf','#fff3cd','#e0d4f5','#fce5cd','#d9faff','#e6f5d0','#fddde6','#cfe2f3','#ffe6f0','#e0f7fa','#e6ffe6','#f9f1dc','#f0e5d8','#dfe7fd','#fff0f5','#e4f9f5','#f7f4ea','#f3e6ff' ];
			$text_colors   = [ '#0A4F70','#2E7031','#A94442','#8A6D3B','#5E4B8B','#B85C00','#31708F','#607D3B','#9F3858','#3A5F7F','#B03A5D','#317C80','#338055','#7A6332','#7D6F58','#5B6EBF','#A35B73','#31706B','#7E7654','#5B3B8A' ];
			
			$events = [];
			foreach ( $results as $row ) {
				$post_id = $row->event_id;
				$color_index = $post_id % count($color_palette);
				
				$event_url = get_permalink( $post_id );

				$events[] = [
					'id'            => $post_id,
					'instance_id'   => $row->id,
					'title'         => html_entity_decode( $row->post_title, ENT_QUOTES, 'UTF-8' ),
					'start'         => gmdate( 'Y-m-d\TH:i:s', strtotime( $row->start_date ) ),
					'end'           => gmdate( 'Y-m-d\TH:i:s', strtotime( $row->end_date ) ),
					'url'           => esc_url( $event_url ),
					'description'   => ! empty( $row->post_excerpt ) ? $row->post_excerpt : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $row->post_content ) ), 25, '...' ),
					'image'         => esc_url( get_the_post_thumbnail_url( $post_id, 'medium' ) ),
					'color'         => $color_palette[$color_index],
					'textColor'     => $text_colors[$color_index],
					'borderColor'   => 'rgba(0,0,0,0.1)',
					'formattedDate' => gmdate( 'M j, Y g:i a', strtotime( $row->start_date ) ),
				];
			}
			
			wp_send_json( $events );
		}

		// Fallback for other post types or if something went wrong
		$args  = [
			'post_type'      => $selected_post_type,
			'posts_per_page' => -1,
			'post_status'    => array('publish'),
			'meta_query'     => [		//phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'relation' => 'AND',
				[
					'key'     => $start_key,
					'value'   => $start,
					'compare' => '>=',
					'type'    => $type,
				],
				[
					'key'     => $end_key,
					'value'   => $end,
					'compare' => '<=',
					'type'    => $type,
				]
			]
		];

		if ( ! empty( $category ) || ! empty( $collection ) || ! empty( $venue ) || ! empty( $organizer ) || ! empty( $tag ) ) {
			$args['tax_query'] = array( 'relation' => 'AND' ); //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			if ( ! empty( $category ) ) {
				$args['tax_query'][] = [
					'taxonomy' => $selected_taxonomy,
					'field'    => 'slug',
					'terms'    => $cats
				];
			}
			if ( ! empty( $collection ) ) {
				$args['tax_query'][] = [
					'taxonomy' => 'eec_collection',
					'field'    => 'slug',
					'terms'    => $cols
				];
			}
			if ( ! empty( $venue ) ) {
				$args['tax_query'][] = [
					'taxonomy' => 'eec_venue',
					'field'    => 'slug',
					'terms'    => array_map( 'trim', explode( ',', $venue ) )
				];
			}
			if ( ! empty( $organizer ) ) {
				$args['tax_query'][] = [
					'taxonomy' => 'eec_organizer',
					'field'    => 'slug',
					'terms'    => array_map( 'trim', explode( ',', $organizer ) )
				];
			}
			if ( ! empty( $tag ) ) {
				$args['tax_query'][] = [
					'taxonomy' => 'eec_tag',
					'field'    => 'slug',
					'terms'    => array_map( 'trim', explode( ',', $tag ) )
				];
			}
		}
		
		$color_palette = [ '#bee9fa','#d1f8d1','#f8cfcf','#fff3cd','#e0d4f5','#fce5cd','#d9faff','#e6f5d0','#fddde6','#cfe2f3','#ffe6f0','#e0f7fa','#e6ffe6','#f9f1dc','#f0e5d8','#dfe7fd','#fff0f5','#e4f9f5','#f7f4ea','#f3e6ff' ];
		$text_colors   = [ '#0A4F70','#2E7031','#A94442','#8A6D3B','#5E4B8B','#B85C00','#31708F','#607D3B','#9F3858','#3A5F7F','#B03A5D','#317C80','#338055','#7A6332','#7D6F58','#5B6EBF','#A35B73','#31706B','#7E7654','#5B3B8A' ];
			
		$events = [];
		$query  = new WP_Query($args);
		
		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				$post_id = get_the_ID();

				if( $selected_post_type == 'event' ){
					$getmetas = get_post_meta( $post_id, $start_key, true );
					$getmetae = get_post_meta( $post_id, $end_key, true );
					$startgm = gmdate('Y-m-d\TH:i:s', strtotime( $getmetas ) );
					$endgm   = gmdate('Y-m-d\TH:i:s', strtotime( $getmetae ) );
					$formated_date = gmdate('M j, Y g:i a', strtotime( $getmetas ) );
				}else{
					$startgm = gmdate('Y-m-d\TH:i:s', get_post_meta( $post_id, $start_key, true ) );
					$endgm   = gmdate('Y-m-d\TH:i:s', get_post_meta( $post_id, $end_key, true ) );
					$formated_date = gmdate('M j, Y g:i a', get_post_meta( $post_id, $start_key, true ) );
				}
				
				// Get a color from our palette (using post ID for consistency)
			$color_index = $post_id % count($color_palette);
				$color = $color_palette[$color_index];
				$text_color = $text_colors[$color_index];

				$event_url = get_permalink();

				$events[] = [
					'id' => $post_id,
					'title' => html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8'),
					'start' => $startgm,
					'end' => $endgm,
					'url' => esc_url( $event_url ),
					'description' => get_the_excerpt(),
					'image' => esc_url( get_the_post_thumbnail_url($post_id, 'medium') ),
					'color' => $color,
					'textColor' => $text_color,
					'borderColor' => 'rgba(0,0,0,0.1)',
					'formattedDate' => $formated_date,
				];
			}
		}
		
		wp_reset_postdata();
		wp_send_json($events);
	}

	/**
	 * Load more events for the grid view.
	 *
	 * @return void
	 */
	public function xylusec_load_more_events() {
		global $xylusec_events_calendar;
		check_ajax_referer('xylusec_nonce', 'nonce');

		$shortcode_atts     = isset( $_POST['shortcode_atts'] ) ? $_POST['shortcode_atts'] : '{}'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$paged              = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
		$past               = isset( $_POST['past'] ) && $_POST['past'] == 1;
		$keyword            = isset( $_POST['keyword']) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) ) : '';
		$selected_post_type = isset( $this->xylusec_options['xylusec_event_source'] ) ? $this->xylusec_options['xylusec_event_source'] : '';
		$pagination_count   = isset( $this->xylusec_options['xylusec_events_per_page'] ) ? $this->xylusec_options['xylusec_events_per_page'] : 12;
		$title_color        = isset( $this->xylusec_options['xylusec_event_title_color'] ) ? $this->xylusec_options['xylusec_event_title_color'] : '#60606e';
		$events             = $xylusec_events_calendar->common->xylusec_get_upcoming_events( $selected_post_type, $paged, $keyword, $pagination_count, $shortcode_atts, $past );

		if( $selected_post_type == 'ajde_events' ){
			$start_key = 'evcal_srow';
			$end_key   = 'evcal_erow';
		}elseif( $selected_post_type == 'event' ){
			$start_key = '_event_start';
			$end_key   = '_event_end';
		}else{
			$start_key = 'start_ts';
			$end_key   = 'end_ts';
		}

		if ($events->have_posts()) :
			while ($events->have_posts()) : $events->the_post();
				$event_id   = get_the_ID();    
				$vdbutton   = $xylusec_events_calendar->common->xylusec_get_view_details_button( $this->xylusec_options, $event_id, 100 );
				$event_url = get_permalink();

				// Use instance date if available (for recurring events), fallback to meta
				$current_post = $events->post;
				if ( isset( $current_post->instance_start ) ) {
					$start_ts = strtotime( $current_post->instance_start );
				} else {
					$start_ts = get_post_meta( $event_id, $start_key, true );
					if( $selected_post_type == 'event' ){
						$start_ts = strtotime( $start_ts );
					}
				}

				$location   = get_post_meta( $event_id, 'venue_name', true );
				$event_date = gmdate( 'D, d M Y h:i A', $start_ts );
				?>
				<div class="xylusec-event-card">
					<div class="xylusec-event-img">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php echo esc_url( $event_url ); ?>" >
								<?php the_post_thumbnail( 'medium' ); ?>
							</a>
						<?php else: ?>
							<a href="<?php echo esc_url( $event_url ); ?>" >
								<img src="https://dummyimage.com/350x350/ccc/969696.png&text=<?php echo esc_attr( gmdate( 'F+d', $start_ts ) ); ?>" alt="<?php the_title(); ?>" />
							</a>
						<?php endif; ?>
					</div>
					<div class="xylusec-event-info">
						<h3 class="xylusec-event-title"><a style="color:<?php echo esc_attr( $title_color ); ?>;" href="<?php echo esc_url( $event_url ); ?>" ><?php echo esc_attr( get_the_title() ); ?></a></h3>
						<div class="xylusec-event-meta">
							<span class="xylusec-event-location"><?php echo esc_html($location); ?></span>
							<span class="xylusec-event-date"><?php echo esc_html($event_date); ?></span>
						</div>
						<p class="xylusec-event-excerpt"><?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 15) ); ?></p>
						<div class="xylusec-event-footer">
							<?php echo wp_kses_post( $vdbutton ); ?>
						</div>
					</div>
				</div>
			<?php
			endwhile;
		endif;

		wp_reset_postdata();
		die();
	}

	/**
	 * Load more events for the row view.
	 *
	 * @return void
	 */
	public function xylusec_load_more_row_events() {
		global $xylusec_events_calendar;
		check_ajax_referer('xylusec_nonce', 'nonce');
		
		$shortcode_atts     = isset( $_POST['shortcode_atts'] ) ? $_POST['shortcode_atts'] : '{}'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$paged              = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
		$keyword            = isset( $_POST['keyword'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) ) : '';
		$selected_post_type = isset( $this->xylusec_options['xylusec_event_source'] ) ? $this->xylusec_options['xylusec_event_source'] : '';
		$pagination_count   = isset( $this->xylusec_options['xylusec_events_per_page'] ) ? $this->xylusec_options['xylusec_events_per_page'] : 12;
		$title_color        = isset( $this->xylusec_options['xylusec_event_title_color'] ) ? $this->xylusec_options['xylusec_event_title_color'] : '#60606e';
		$past               = isset( $_POST['past'] ) && $_POST['past'] == 1;
		$query              = $xylusec_events_calendar->common->xylusec_get_upcoming_events( $selected_post_type, $paged, $keyword, $pagination_count, $shortcode_atts, $past );

		if( $selected_post_type == 'ajde_events' ){
			$start_key = 'evcal_srow';
			$end_key   = 'evcal_erow';
		}elseif( $selected_post_type == 'event' ){
			$start_key = '_event_start';
			$end_key   = '_event_end';
		}else{
			$start_key = 'start_ts';
			$end_key   = 'end_ts';
		}
		
		$atts = array();
		if ( is_string( $shortcode_atts ) ) {
			$atts = json_decode( stripslashes( $shortcode_atts ), true );
		} elseif ( is_array( $shortcode_atts ) ) {
			$atts = $shortcode_atts;
		}

		$show_image     = isset( $atts['show_image'] ) ? filter_var( $atts['show_image'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) ?? true : true;
		$show_location  = isset( $atts['show_location'] ) ? filter_var( $atts['show_location'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) ?? true : true;
		$show_date      = isset( $atts['show_date'] ) ? filter_var( $atts['show_date'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) ?? true : true;
		$show_organizer = isset( $atts['show_organizer'] ) ? filter_var( $atts['show_organizer'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) ?? false : false;

		if ($query->have_posts()) :
			while ($query->have_posts()) : $query->the_post();
				$event_id   = get_the_ID();    
				$vdbutton   = $xylusec_events_calendar->common->xylusec_get_view_details_button( $this->xylusec_options, $event_id, 30 );
				$event_url = get_permalink();

				// Use instance date if available (for recurring events), fallback to meta
				$current_post = $query->post;
				if ( isset( $current_post->instance_start ) ) {
					$start_ts = strtotime( $current_post->instance_start );
				} else {
					$start_ts = get_post_meta( $event_id, $start_key, true );
					if( $selected_post_type == 'event' ){
						$start_ts = strtotime( $start_ts );
					}
				}

				$location   = get_post_meta( $event_id, 'venue_name', true );
				$event_date = gmdate( 'D, d M Y h:i A', $start_ts );
				
				$organizer_name = '';
				if ( $show_organizer ) {
					$organizers = $xylusec_events_calendar->common->xylusec_get_event_organizers( $event_id );
					if ( ! empty( $organizers ) ) {
						$organizer_name = $organizers[0]['name'];
					}
				}
				?>
				<div class="xylusec-event-row">
					<div class="xylusec-event-row-content">
						<?php 
						$thumb_html = '';
						if ( $show_image ) {
							if ( has_post_thumbnail( $event_id ) ) {
								$thumb_html = get_the_post_thumbnail( $event_id, 'medium', [ 'class' => 'xylusec-event-image' ] );
							} else {
								$ext_image_url = get_post_meta( $event_id, 'iee_event_image_url', true );
								if ( $ext_image_url ) {
									$thumb_html = '<img src="' . esc_url( $ext_image_url ) . '" class="xylusec-event-image" />';
								}
							}
						}
						
						if ( $show_image && $thumb_html ) {
							echo '<div class="xylusec-image-anchor-container" > <a href="' . esc_url( $event_url ) . '" ' . $target . '>';
							echo $thumb_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo '</a></div>';
						}
						?>
						<div class="xylusec-event-details">
							<h3 class="xylusec-event-title"><a style="color:<?php echo esc_attr( $title_color ); ?>;" href="<?php echo esc_url( $event_url ); ?>" ><?php echo esc_attr( get_the_title() ); ?></a></h3>
							<?php if ( $show_location && $location ) : ?>
								<div class="xylusec-event-location"><?php echo esc_html( $location ); ?></div>
							<?php endif; ?>
							<?php if ( $show_organizer && $organizer_name ) : ?>
								<div class="xylusec-event-organizer" style="font-size: 11.5px; color: #64748b; margin-top: 2px;"><?php echo esc_html( sprintf( __( 'Organizer: %s', 'xylus-events-calendar' ), $organizer_name ) ); ?></div>
							<?php endif; ?>
							<p class="xylusec-event-excerpt"><?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 10 ) ); ?></p>
							<div class="xylusec-event-meta xylusec-event-meta-row" >
								<?php if ( $show_date ) : ?>
									<div class="xylusec-event-date"><?php echo esc_html( $event_date ); ?></div>
								<?php endif; ?>
								<?php echo wp_kses_post( $vdbutton ); ?>
							</div>
						</div>
					</div>
				</div>
				<?php
			endwhile;
		endif;
		wp_reset_postdata();
		wp_die();
	}
    
	/**
	 * Load more events for the staggered grid view.
	 *
	 * @return void
	 */
	public function xylusec_load_more_staggered_events() {
		global $xylusec_events_calendar;
		check_ajax_referer('xylusec_nonce', 'nonce');

		$shortcode_atts     = isset( $_POST['shortcode_atts'] ) ? $_POST['shortcode_atts'] : '{}'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$paged              = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
		$keyword            = isset( $_POST['keyword'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) ) : '';
		$selected_post_type = isset( $this->xylusec_options['xylusec_event_source'] ) ? $this->xylusec_options['xylusec_event_source'] : '';
		$pagination_count   = isset( $this->xylusec_options['xylusec_events_per_page'] ) ? $this->xylusec_options['xylusec_events_per_page'] : 12;
		$title_color        = isset( $this->xylusec_options['xylusec_event_title_color'] ) ? $this->xylusec_options['xylusec_event_title_color'] : '#60606e';
		$past               = isset( $_POST['past'] ) && $_POST['past'] == 1;
		$query              = $xylusec_events_calendar->common->xylusec_get_upcoming_events( $selected_post_type, $paged, $keyword, $pagination_count, $shortcode_atts, $past );

		if( $selected_post_type == 'ajde_events' ){
			$start_key = 'evcal_srow';
			$end_key   = 'evcal_erow';
		}elseif( $selected_post_type == 'event' ){
			$start_key = '_event_start';
			$end_key   = '_event_end';
		}else{
			$start_key = 'start_ts';
			$end_key   = 'end_ts';
		}

		if ($query->have_posts()) :
			while ($query->have_posts()) : $query->the_post();
				$event_id   = get_the_ID();    
				$vdbutton   = $xylusec_events_calendar->common->xylusec_get_view_details_button( $this->xylusec_options, $event_id, 100 );
				$event_url = get_permalink();

				// Use instance date if available (for recurring events), fallback to meta
				$current_post = $query->post;
				if ( isset( $current_post->instance_start ) ) {
					$start_ts = strtotime( $current_post->instance_start );
				} else {
					$start_ts = get_post_meta( $event_id, $start_key, true );
					if( $selected_post_type == 'event' ){
						$start_ts = strtotime( $start_ts );
					}
				}

				$location   = get_post_meta( $event_id, 'venue_name', true );
				$event_date = gmdate( 'D, d M Y h:i A', $start_ts );
				
				?>
				<div class="xylusec-event-card-staggered">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="xylusec-staggered-image">
							<a href="<?php echo esc_url( $event_url ); ?>" >
								<?php the_post_thumbnail( 'medium' ); ?>
							</a>
						</div>
					<?php endif; ?>
					<div class="xylusec-staggered-details">
						<h3 class="xylusec-event-title"><a style="color:<?php echo esc_attr( $title_color ); ?>;" href="<?php echo esc_url( $event_url ); ?>" ><?php echo esc_attr( get_the_title() ); ?></a></h3>
						<div class="xylusec-event-location"><?php echo esc_html($location); ?></div>
						<p class="xylusec-event-excerpt"><?php echo wp_kses_post(wp_trim_words(get_the_excerpt(), 20)); ?></p>
						<div class="xylusec-event-meta" >
							<div class="xylusec-event-date"><?php echo esc_html($event_date); ?></div>
							<?php echo wp_kses_post( $vdbutton ); ?>
						</div>
					</div>
				</div>
				<?php
			endwhile;
		endif;
		wp_reset_postdata();
		wp_die();
	}

	/**
	 * Load more events for the slider view.
	 *
	 * @return void
	 */
	public function xylusec_load_more_slider_events() {
		global $xylusec_events_calendar;
		check_ajax_referer('xylusec_nonce', 'nonce');

		$shortcode_atts     = isset( $_POST['shortcode_atts'] ) ? $_POST['shortcode_atts'] : '{}'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$paged        = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
		$keyword      = isset( $_POST['keyword'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) ) : '';
		$selected_post_type = isset( $this->xylusec_options['xylusec_event_source'] ) ? $this->xylusec_options['xylusec_event_source'] : '';
		$pagination_count   = isset( $this->xylusec_options['xylusec_events_per_page'] ) ? $this->xylusec_options['xylusec_events_per_page'] : 12;
		$past               = isset( $_POST['past'] ) && $_POST['past'] == 1;
		$title_color     = isset( $this->xylusec_options['xylusec_event_title_color'] ) ? $this->xylusec_options['xylusec_event_title_color'] : '#60606e';
		$query        = $xylusec_events_calendar->common->xylusec_get_upcoming_events( $selected_post_type, $paged, $keyword, $pagination_count, $shortcode_atts, $past );

		if( $selected_post_type == 'ajde_events' ){
			$start_key = 'evcal_srow';
			$end_key   = 'evcal_erow';
		}elseif( $selected_post_type == 'event' ){
			$start_key = '_event_start';
			$end_key   = '_event_end';
		}else{
			$start_key = 'start_ts';
			$end_key   = 'end_ts';
		}
		
		if ($query->have_posts()) :
			while ($query->have_posts()) : $query->the_post();
				$event_id   = get_the_ID();    
				$vdbutton   = $xylusec_events_calendar->common->xylusec_get_view_details_button( $this->xylusec_options, $event_id, 70 );
				
				$event_url = get_permalink();

				// Use instance date if available (for recurring events), fallback to meta
				$current_post = $query->post;
				if ( isset( $current_post->instance_start ) ) {
					$start_ts = strtotime( $current_post->instance_start );
				} else {
					$start_ts = get_post_meta( $event_id, $start_key, true );
					if( $selected_post_type == 'event' ){
						$start_ts = strtotime( $start_ts );
					}
				}

				$location   = get_post_meta( $event_id, 'venue_name', true );
				$event_date = gmdate( 'D, d M Y h:i A', $start_ts );
				?>
				<div class="xylusec-slider-slide">
					<div class="xylusec-slider-event-card">
					<div class="xylusec-slider-event-info">
						<h3><a class="xylusec-slider-event-title" style="color:<?php echo esc_attr( $title_color ); ?>;" href="<?php echo esc_url( $event_url ); ?>" ><?php echo esc_attr( get_the_title() ); ?></a></h3>
						<span class="xylusec-slider-event-meta"><strong><?php echo esc_html( $location ); ?></strong></span>
						<div class="xylusec-slider-event-meta"><span class="xylusec-slider-event-date"><strong><?php echo esc_html( $event_date ); ?></strong></span></div>
						<p class="xylusec-slider-event-desc">
							<?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
						</p>
						<?php echo wp_kses_post( $vdbutton ); ?>
					</div>
					<?php 
						if ( has_post_thumbnail( $event_id ) ) {
							echo '<div class="xylusec-slider-event-img" ><a href="' . esc_url( $event_url ) . '" ' . $target . '>';
							echo get_the_post_thumbnail( $event_id, 'full', [  ] );
							echo '</a></div>';
						}
					?>
					</div>
				</div>
				<?php
			endwhile;
		endif;
		wp_reset_postdata();
		wp_die();
	}

}