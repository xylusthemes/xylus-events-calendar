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
		check_ajax_referer('xylusec_nonce', 'nonce');
		
		$start              = isset( $_GET['start'] ) ? (int)esc_attr( sanitize_text_field( wp_unslash( $_GET['start'] ) ) ) : '';
		$end                = isset( $_GET['end'] ) ? (int)esc_attr( sanitize_text_field( wp_unslash( $_GET['end'] ) ) ) : '';	
		$selected_post_type = isset( $this->xylusec_options['xylusec_event_source'] ) ? $this->xylusec_options['xylusec_event_source'] : '';

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

		$args  = [
			'post_type'      => $selected_post_type,
			'posts_per_page' => -1,
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

				$events[] = [
					'id' => $post_id,
					'title' => html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8'),
					'start' => $startgm,
					'end' => $endgm,
					'url' => esc_url( get_permalink() ),
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

		$paged   = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
		$keyword = isset( $_POST['keyword']) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) ) : '';
		$selected_post_type = isset( $this->xylusec_options['xylusec_event_source'] ) ? $this->xylusec_options['xylusec_event_source'] : '';
		$pagination_count   = isset( $this->xylusec_options['xylusec_events_per_page'] ) ? $this->xylusec_options['xylusec_events_per_page'] : 12;
		$title_color     = isset( $this->xylusec_options['xylusec_event_title_color'] ) ? $this->xylusec_options['xylusec_event_title_color'] : '#60606e';
		$events  = $xylusec_events_calendar->common->xylusec_get_upcoming_events( $selected_post_type, $paged, $keyword, $pagination_count );

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
				$start_ts   = get_post_meta( $event_id, $start_key, true );
				$location   = get_post_meta( $event_id, 'venue_name', true );
				
				if( $selected_post_type == 'event' ){
					$start_ts = strtotime( $start_ts );
				}

				$event_date = gmdate( 'D, d M Y h:i A', $start_ts );
				?>
				<div class="xylusec-event-card">
					<div class="xylusec-event-img">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'medium' ); ?>
							</a>
						<?php endif; ?>
					</div>
					<div class="xylusec-event-info">
						<h3 class="xylusec-event-title"><a style="color:<?php echo esc_attr( $title_color ); ?>;" href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_attr( get_the_title() ); ?></a></h3>
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

		$paged        = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
		$keyword      = isset( $_POST['keyword'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) ) : '';
		$selected_post_type = isset( $this->xylusec_options['xylusec_event_source'] ) ? $this->xylusec_options['xylusec_event_source'] : '';
		$pagination_count   = isset( $this->xylusec_options['xylusec_events_per_page'] ) ? $this->xylusec_options['xylusec_events_per_page'] : 12;
		$title_color     = isset( $this->xylusec_options['xylusec_event_title_color'] ) ? $this->xylusec_options['xylusec_event_title_color'] : '#60606e';
		$query        = $xylusec_events_calendar->common->xylusec_get_upcoming_events( $selected_post_type, $paged, $keyword, $pagination_count );

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
				$vdbutton   = $xylusec_events_calendar->common->xylusec_get_view_details_button( $this->xylusec_options, $event_id, 30 );
				$start_ts   = get_post_meta( $event_id, $start_key, true );
				$location   = get_post_meta( $event_id, 'venue_name', true );

				if( $selected_post_type == 'event' ){
					$start_ts = strtotime( $start_ts );
				}

				$event_date = gmdate( 'D, d M Y h:i A', $start_ts );
				?>
				<div class="xylusec-event-row">
					<div class="xylusec-event-row-content">
						<?php 
						if ( has_post_thumbnail( $event_id ) ) {
							$permalink = get_permalink( $event_id );
							echo '<div class="xylusec-image-anchor-container" > <a href="' . esc_url( $permalink ) . '">';
							echo get_the_post_thumbnail( $event_id, 'medium', [ 'class' => 'xylusec-event-image' ] );
							echo '</a></div>';
						}
						?>
						<div class="xylusec-event-details">
							<h3 class="xylusec-event-title"><a style="color:<?php echo esc_attr( $title_color ); ?>;" href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_attr( get_the_title() ); ?></a></h3>
							<div class="xylusec-event-location"><?php echo esc_html( $location ); ?></div>
							<p class="xylusec-event-excerpt"><?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 10 ) ); ?></p>
							<div class="xylusec-event-meta xylusec-event-meta-row" >
								<div class="xylusec-event-date"><?php echo esc_html( $event_date ); ?></div>
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

		$paged              = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
		$keyword            = isset( $_POST['keyword'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) ) : '';
		$selected_post_type = isset( $this->xylusec_options['xylusec_event_source'] ) ? $this->xylusec_options['xylusec_event_source'] : '';
		$pagination_count   = isset( $this->xylusec_options['xylusec_events_per_page'] ) ? $this->xylusec_options['xylusec_events_per_page'] : 12;
		$title_color        = isset( $this->xylusec_options['xylusec_event_title_color'] ) ? $this->xylusec_options['xylusec_event_title_color'] : '#60606e';
		$query              = $xylusec_events_calendar->common->xylusec_get_upcoming_events( $selected_post_type, $paged, $keyword, $pagination_count );

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
				$start_ts   = get_post_meta( $event_id, $start_key, true );
				$location   = get_post_meta( $event_id, 'venue_name', true );

				if( $selected_post_type == 'event' ){
					$start_ts = strtotime( $start_ts );
				}

				$event_date = gmdate( 'D, d M Y h:i A', $start_ts );
				
				?>
				<div class="xylusec-event-card-staggered">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="xylusec-staggered-image">
							<a href="<?php esc_url( the_permalink() ); ?>">
								<?php the_post_thumbnail( 'medium' ); ?>
							</a>
						</div>
					<?php endif; ?>
					<div class="xylusec-staggered-details">
						<h3 class="xylusec-event-title"><a style="color:<?php echo esc_attr( $title_color ); ?>;" href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_attr( get_the_title() ); ?></a></h3>
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

		$paged        = isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1;
		$keyword      = isset( $_POST['keyword'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) ) : '';
		$selected_post_type = isset( $this->xylusec_options['xylusec_event_source'] ) ? $this->xylusec_options['xylusec_event_source'] : '';
		$pagination_count   = isset( $this->xylusec_options['xylusec_events_per_page'] ) ? $this->xylusec_options['xylusec_events_per_page'] : 12;
		$title_color     = isset( $this->xylusec_options['xylusec_event_title_color'] ) ? $this->xylusec_options['xylusec_event_title_color'] : '#60606e';
		$query        = $xylusec_events_calendar->common->xylusec_get_upcoming_events( $selected_post_type, $paged, $keyword, $pagination_count );

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
				$start_ts   = get_post_meta( $event_id, $start_key, true );
				$location   = get_post_meta( $event_id, 'venue_name', true );

				if( $selected_post_type == 'event' ){
					$start_ts = strtotime( $start_ts );
				}

				$event_date = gmdate( 'D, d M Y h:i A', $start_ts );
				?>
				<div class="xylusec-slider-slide">
					<div class="xylusec-slider-event-card">
					<div class="xylusec-slider-event-info">
						<h3><a style="color:<?php echo esc_attr( $title_color ); ?>;" href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_attr( get_the_title() ); ?></a></h3>
						<span class="xylusec-slider-event-meta"><strong><?php echo esc_html( $location ); ?></strong></span>
						<div class="xylusec-slider-event-meta"><span class="xylusec-slider-event-date"><strong><?php echo esc_html( $event_date ); ?></strong></span></div>
						<p class="xylusec-slider-event-desc">
							<?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
						</p>
						<?php echo wp_kses_post( $vdbutton ); ?>
					</div>
					<?php 
						if ( has_post_thumbnail( $event_id ) ) {
							$permalink = get_permalink( $event_id );
							echo '<div class="xylusec-slider-event-img" ><a href="' . esc_url( $permalink ) . '">';
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