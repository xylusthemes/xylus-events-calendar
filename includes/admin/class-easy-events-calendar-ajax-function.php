<?php
/**
 * Common functions class for Easy Events Calendar
 *
 * @link       http://xylusthemes.com/
 * @since      1.0.0
 *
 * @package    Easy_Events_Calendar
 * @subpackage Easy_Events_Calendar/includes/admin
 * @author     Rajat Patel <prajat21@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Easy_Events_Calendar_Ajax {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */


	private $xtec_options;
	
	public function __construct() {

		 $this->xtec_options = get_option( XTEC_OPTIONS, true );

		// Fetch events for the calendar
        add_action('wp_ajax_xtec_get_events', array( $this, 'xtec_get_events' ) );
		add_action('wp_ajax_nopriv_xtec_get_events', array( $this, 'xtec_get_events' ) );

		// Load more events for the calendar
		add_action('wp_ajax_load_more_events', array( $this, 'load_more_events' ) );
		add_action('wp_ajax_nopriv_load_more_events', array( $this, 'load_more_events' ) );

		// Grid view
		add_action('wp_ajax_load_more_row_events', array( $this, 'load_more_row_events' ) );
		add_action('wp_ajax_nopriv_load_more_row_events', array( $this, 'load_more_row_events' ) );

		// Staggered grid view
		add_action('wp_ajax_load_more_staggered_events', array( $this, 'load_more_staggered_events' ) );
		add_action('wp_ajax_nopriv_load_more_staggered_events', array( $this, 'load_more_staggered_events' ) );
	} 

	/**
	 * Fetch events for the calendar.
	 *
	 * @return void
	 */
	public function xtec_get_events() {
		check_ajax_referer('xtec_nonce', 'nonce');
		
		$start = isset( $_GET['start'] ) ? (int)esc_attr( sanitize_text_field( wp_unslash( $_GET['start'] ) ) ) : '';
		$end   = isset( $_GET['end'] ) ? (int)esc_attr( sanitize_text_field( wp_unslash( $_GET['end'] ) ) ) : '';	
		$selected_post_type = isset( $this->xtec_options['xtec_event_source'] ) ? $this->xtec_options['xtec_event_source'] : '';
		$args  = [
			'post_type' => $selected_post_type,
			'posts_per_page' => -1,
			'meta_query' => [         //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'relation' => 'AND',
				[
					'key' => 'start_ts',
					'value' => $start,
					'compare' => '>=',
					'type' => 'NUMERIC'
				],
				[
					'key' => 'end_ts',
					'value' => $end,
					'compare' => '<=',
					'type' => 'NUMERIC'
				]
			]
		];
		
		$color_palette = [ '#bee9fa','#d1f8d1','#f8cfcf','#fff3cd','#e0d4f5','#fce5cd','#d9faff','#e6f5d0','#fddde6','#cfe2f3','#ffe6f0','#e0f7fa','#e6ffe6','#f9f1dc','#f0e5d8','#dfe7fd','#fff0f5','#e4f9f5','#f7f4ea','#f3e6ff' ];
		$text_colors   = [ '#0A4F70','#2E7031','#A94442','#8A6D3B','#5E4B8B','#B85C00','#31708F','#607D3B','#9F3858','#3A5F7F','#B03A5D','#317C80','#338055','#7A6332','#7D6F58','#5B6EBF','#A35B73','#31706B','#7E7654','#5B3B8A' ];
			
		$events = [];
		$query  = new WP_Query($args);
		
		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				$post_id = get_the_ID();
				
				// Get a color from our palette (using post ID for consistency)
			$color_index = $post_id % count($color_palette);
				$color = $color_palette[$color_index];
				$text_color = $text_colors[$color_index];

				$events[] = [
					'id' => $post_id,
					'title' => html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8'),
					'start' => gmdate('Y-m-d\TH:i:s', get_post_meta($post_id, 'start_ts', true)),
					'end' => gmdate('Y-m-d\TH:i:s', get_post_meta($post_id, 'end_ts', true)),
					'url' => esc_url( get_permalink() ),
					'description' => get_the_excerpt(),
					'image' => esc_url( get_the_post_thumbnail_url($post_id, 'medium') ),
					'color' => $color,
					'textColor' => $text_color,
					'borderColor' => 'rgba(0,0,0,0.1)',
					'formattedDate' => gmdate('M j, Y g:i a', get_post_meta($post_id, 'start_ts', true))
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
	public function load_more_events() {
		global $xt_events_calendar;
		check_ajax_referer('xtec_nonce', 'nonce');

		$paged   = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
		$keyword = isset( $_POST['keyword']) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) ) : '';
		$selected_post_type = isset( $this->xtec_options['xtec_event_source'] ) ? $this->xtec_options['xtec_event_source'] : '';
		$pagination_count   = isset( $this->xtec_options['xtec_events_per_page'] ) ? $this->xtec_options['xtec_events_per_page'] : 12;
		$title_color     = isset( $this->xtec_options['xtec_event_title_color'] ) ? $this->xtec_options['xtec_event_title_color'] : '#60606e';
		$events  = $xt_events_calendar->common->xtec_get_upcoming_events( $selected_post_type, $paged, $keyword, $pagination_count );

		if ($events->have_posts()) :
			while ($events->have_posts()) : $events->the_post();
				$event_id   = get_the_ID();    
				$vdbutton   = $xt_events_calendar->common->xtec_get_view_details_button( $this->xtec_options, $event_id, 100 );
				$start_ts   = get_post_meta( $event_id, 'start_ts', true );
				$location   = get_post_meta( $event_id, 'venue_name', true );
				$event_date = gmdate( 'D, d M Y h:i A', $start_ts );
				?>
				<div class="xtec-event-card">
					<div class="xtec-event-img">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'medium' ); ?>
							</a>
						<?php endif; ?>
					</div>
					<div class="xtec-event-info">
						<h3 class="xtec-event-title"><a style="color:<?php echo esc_attr( $title_color ); ?>;" href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_attr( get_the_title() ); ?></a></h3>
						<div class="xtec-event-meta">
							<span class="xtec-event-location"><?php echo esc_html($location); ?></span>
							<span class="xtec-event-date"><?php echo esc_html($event_date); ?></span>
						</div>
						<p class="xtec-event-excerpt"><?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 15) ); ?></p>
						<div class="xtec-event-footer">
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
	public function load_more_row_events() {
		global $xt_events_calendar;
		check_ajax_referer('xtec_nonce', 'nonce');

		$paged        = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
		$keyword      = isset( $_POST['keyword'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) ) : '';
		$selected_post_type = isset( $this->xtec_options['xtec_event_source'] ) ? $this->xtec_options['xtec_event_source'] : '';
		$pagination_count   = isset( $this->xtec_options['xtec_events_per_page'] ) ? $this->xtec_options['xtec_events_per_page'] : 12;
		$title_color     = isset( $this->xtec_options['xtec_event_title_color'] ) ? $this->xtec_options['xtec_event_title_color'] : '#60606e';
		$query        = $xt_events_calendar->common->xtec_get_upcoming_events( $selected_post_type, $paged, $keyword, $pagination_count );
		
		
		if ($query->have_posts()) :
			while ($query->have_posts()) : $query->the_post();
				$event_id   = get_the_ID();    
				$vdbutton   = $xt_events_calendar->common->xtec_get_view_details_button( $this->xtec_options, $event_id, 30 );
				$start_ts   = get_post_meta( $event_id, 'start_ts', true );
				$location   = get_post_meta( $event_id, 'venue_name', true );
				$event_date = gmdate( 'D, d M Y h:i A', $start_ts );
				?>
				<div class="xtec-event-row">
					<div class="xtec-event-row-content">
						<?php 
						if ( has_post_thumbnail( $event_id ) ) {
							$permalink = get_permalink( $event_id );
							echo '<div class="xtec-image-anchor-container" > <a href="' . esc_url( $permalink ) . '">';
							echo get_the_post_thumbnail( $event_id, 'medium', [ 'class' => 'xtec-event-image' ] );
							echo '</a></div>';
						}
						?>
						<div class="xtec-event-details">
							<h3 class="xtec-event-title"><a style="color:<?php echo esc_attr( $title_color ); ?>;" href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_attr( get_the_title() ); ?></a></h3>
							<div class="xtec-event-location"><?php echo esc_html( $location ); ?></div>
							<p class="xtec-event-excerpt"><?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 10 ) ); ?></p>
							<div class="xtec-event-meta xtec-event-meta-row" >
								<div class="xtec-event-date"><?php echo esc_html( $event_date ); ?></div>
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
	public function load_more_staggered_events() {
		global $xt_events_calendar;
		check_ajax_referer('xtec_nonce', 'nonce');

		$paged              = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
		$keyword            = isset( $_POST['keyword'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) ) : '';
		$selected_post_type = isset( $this->xtec_options['xtec_event_source'] ) ? $this->xtec_options['xtec_event_source'] : '';
		$pagination_count   = isset( $this->xtec_options['xtec_events_per_page'] ) ? $this->xtec_options['xtec_events_per_page'] : 12;
		$title_color        = isset( $this->xtec_options['xtec_event_title_color'] ) ? $this->xtec_options['xtec_event_title_color'] : '#60606e';
		$query              = $xt_events_calendar->common->xtec_get_upcoming_events( $selected_post_type, $paged, $keyword, $pagination_count );

		
		if ($query->have_posts()) :
			while ($query->have_posts()) : $query->the_post();
				$event_id   = get_the_ID();    
				$vdbutton   = $xt_events_calendar->common->xtec_get_view_details_button( $this->xtec_options, $event_id, 100 );
				$start_ts   = get_post_meta( $event_id, 'start_ts', true );
				$location   = get_post_meta( $event_id, 'venue_name', true );
				$event_date = gmdate( 'D, d M Y h:i A', $start_ts );
				
				?>
				<div class="xtec-event-card-staggered">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="xtec-staggered-image">
							<a href="<?php esc_url( the_permalink() ); ?>">
								<?php the_post_thumbnail( 'medium' ); ?>
							</a>
						</div>
					<?php endif; ?>
					<div class="xtec-staggered-details">
						<h3 class="xtec-event-title"><a style="color:<?php echo esc_attr( $title_color ); ?>;" href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_attr( get_the_title() ); ?></a></h3>
						<div class="xtec-event-location"><?php echo esc_html($location); ?></div>
						<p class="xtec-event-excerpt"><?php echo wp_kses_post(wp_trim_words(get_the_excerpt(), 20)); ?></p>
						<div class="xtec-event-meta" >
							<div class="xtec-event-date"><?php echo esc_html($event_date); ?></div>
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
}