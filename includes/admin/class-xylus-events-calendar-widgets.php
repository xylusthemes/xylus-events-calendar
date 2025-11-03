<?php
/**
 * Widget functions class for Xylus Events Calendar
 *
 * @link       http://xylusthemes.com/
 * @since      1.0.0
 *
 * @package    Xylus_Events_Calendar
 * @subpackage Xylus_Events_Calendar/includes
 * @author     Rajat Patel <prajat21@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Easy_Events_Calendar_Widgets extends WP_Widget {

    private $xylusec_options;

    function __construct() {
        $this->xylusec_options = get_option( XYLUSEC_OPTIONS, true );

        parent::__construct(
            'easy_events_calendar_widget',
            __( 'Easy Events Calendar – Upcoming Events', 'easy-events-calendar' ),
            array( 'description' => __( 'Display upcoming events from Easy Events Calendar.', 'easy-events-calendar' ) )
        );
    }

    // Widget frontend output
    public function widget( $args, $instance ) {
        global $xylusec_events_calendar;

        echo $args['before_widget'];

        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Upcoming Events', 'easy-events-calendar' );
        $limit = ! empty( $instance['limit'] ) ? absint( $instance['limit'] ) : 5;
        $style = ! empty( $instance['style'] ) ? $instance['style'] : 'style1';

        if ( ! empty( $title ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
        }

        // fetch events
        $selected_post_type = isset( $this->xylusec_options['xylusec_event_source'] ) ? $this->xylusec_options['xylusec_event_source'] : '';
        $events_query = $xylusec_events_calendar->common->xylusec_get_upcoming_events( $selected_post_type, 1, '', $limit );

        if ( $events_query->have_posts() ) {
            if ( $style === 'style2' ) {
                $this->xylusec_render_style_2( $events_query, $selected_post_type );
            } elseif ( $style === 'style3' ) {
                $this->xylusec_render_style_3( $events_query, $selected_post_type );
            } elseif ( $style === 'style4' ) {
                $this->xylusec_render_style_4( $events_query, $selected_post_type );
            } elseif ( $style === 'style5' ) {
                $this->xylusec_render_style_5( $events_query, $selected_post_type );
            } elseif ( $style === 'style6' ) {
                $this->xylusec_render_style_6( $events_query, $selected_post_type );
            } elseif ( $style === 'style7' ) {
                $this->xylusec_render_style_7( $events_query, $selected_post_type );
            } elseif ( $style === 'style8' ) {
                $this->xylusec_render_style_8( $events_query, $selected_post_type );
            } elseif ( $style === 'style9' ) {
                $this->xylusec_render_style_9( $events_query, $selected_post_type );
            } elseif ( $style === 'style10' ) {
                $this->xylusec_render_style_10( $events_query, $selected_post_type );
            } else {
                $this->xylusec_render_style_1( $events_query, $selected_post_type );
            }
        } else {
            echo '<p>' . __( 'No upcoming events found.', 'easy-events-calendar' ) . '</p>';
        }

        echo $args['after_widget'];
    }

    // ========== Style 1 ==========
    private function xylusec_render_style_1( $events_query, $selected_post_type ) {
        echo '<ul class="easy-events-widget-list-style1">';
        while ( $events_query->have_posts() ) {
            $events_query->the_post();
            $event_url   = get_permalink();
            $event_title = get_the_title();

            if( $selected_post_type == 'ajde_events' ){
                $start_key = 'evcal_srow';
            }elseif( $selected_post_type == 'event' ){
                $start_key = '_event_start';
            }else{
                $start_key = 'start_ts';
            }
            $event_date  = get_post_meta( get_the_ID(), $start_key, true );
            if ( ! ctype_digit( $event_date ) ) {
                $event_date = strtotime( $event_date );
            }

            echo '<li>';
            echo '<a class="event-title-style1" href="' . esc_url( $event_url ) . '">' . esc_html( $event_title ) . '</a>';
            if ( $event_date ) {
                echo '<span class="event-date-style1"> - ' . gmdate( 'M d, Y', $event_date ) . '</span>';
            }
            echo '</li>';
        }
        echo '</ul>';
        wp_reset_postdata();
    }

    // ========== Style 2 ==========
    private function xylusec_render_style_2( $events_query, $selected_post_type ) {
        echo '<div class="easy-events-widget-grid style-2">';
        while ( $events_query->have_posts() ) {
            $events_query->the_post();
            $event_url   = get_permalink();
            $event_title = get_the_title();
            if( $selected_post_type == 'ajde_events' ){
                $start_key = 'evcal_srow';
            }elseif( $selected_post_type == 'event' ){
                $start_key = '_event_start';
            }else{
                $start_key = 'start_ts';
            }
            $event_date  = get_post_meta( get_the_ID(), $start_key, true );
            if ( ! ctype_digit( $event_date ) ) {
                $event_date = strtotime( $event_date );
            }

            echo '<div class="event-card">';
                echo '<div class="event-card-header">';
                    if ( $event_date ) {
                        echo '<span class="event-date">' . date( 'M d, Y', $event_date ) . '</span>';
                    }
                echo '</div>';
                echo '<div class="event-card-body">';
                    echo '<a class="event-title" href="' . esc_url( $event_url ) . '">' . esc_html( $event_title ) . '</a>';
                echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        wp_reset_postdata();
    }

    // ========== Style 3 ==========
    private function xylusec_render_style_3( $events_query, $selected_post_type ) {
        echo '<div class="easy-events-widget-grid style-3">';
        while ( $events_query->have_posts() ) {
            $events_query->the_post();
            $event_url   = get_permalink();
            $event_title = get_the_title();

            // meta se event date nikalna
            if( $selected_post_type == 'ajde_events' ){
                $start_key = 'evcal_srow';
            }elseif( $selected_post_type == 'event' ){
                $start_key = '_event_start';
            }else{
                $start_key = 'start_ts';
            }
            $event_date  = get_post_meta( get_the_ID(), $start_key, true );
            if ( ! ctype_digit( $event_date ) ) {
                $event_date = strtotime( $event_date );
            }

            echo '<div class="event-card">';
                // Top border + Date (optional badge bhi bana sakte)
                if ( $event_date ) {
                    echo '<span class="event-date">' . date( 'M d, Y', $event_date ) . '</span>';
                }

                // Event title
                echo '<a class="event-title" href="' . esc_url( $event_url ) . '">';
                    echo esc_html( $event_title );
                echo '</a>';
            echo '</div>';
        }
        echo '</div>';
        wp_reset_postdata();
    }

    // ========== Style 4 ==========
    private function xylusec_render_style_4( $events_query, $selected_post_type ) {
        echo '<div class="easy-events-widget-badge style-4">';
        while ( $events_query->have_posts() ) {
            $events_query->the_post();
            $event_url   = get_permalink();
            $event_title = get_the_title();

            // Event date
            if( $selected_post_type == 'ajde_events' ){
                $start_key = 'evcal_srow';
            }elseif( $selected_post_type == 'event' ){
                $start_key = '_event_start';
            }else{
                $start_key = 'start_ts';
            }
            $event_date  = get_post_meta( get_the_ID(), $start_key, true );
            if ( ! ctype_digit( $event_date ) ) {
                $event_date = strtotime( $event_date );
            }

            $day   = $event_date ? date( 'd', $event_date ) : '';
            $month = $event_date ? date( 'M', $event_date ) : '';
            $time  = $event_date ? date( 'h:i A', $event_date ) : '';

            echo '<div class="event-item">';
                // Left: circular badge
                if ( $event_date ) {
                    echo '<div class="event-date-circle">';
                        echo '<span class="day">' . esc_html( $day ) . '</span>';
                        echo '<span class="month">' . esc_html( $month ) . '</span>';
                    echo '</div>';
                }

                // Right: details
                echo '<div class="event-info">';
                    echo '<a class="event-title" href="' . esc_url( $event_url ) . '">' . esc_html( $event_title ) . '</a>';
                    if ( $time ) {
                        echo '<div class="event-time">' . esc_html( $time ) . '</div>';
                    }
                echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        wp_reset_postdata();
    }

    // ========== Style 5 ==========
    private function xylusec_render_style_5( $events_query, $selected_post_type ) {
        echo '<div class="easy-events-widget-horizontal style-5">';
        while ( $events_query->have_posts() ) {
            $events_query->the_post();
            $event_url   = get_permalink();
            $event_title = get_the_title();

            // Event date
            if( $selected_post_type == 'ajde_events' ){
                $start_key = 'evcal_srow';
            }elseif( $selected_post_type == 'event' ){
                $start_key = '_event_start';
            }else{
                $start_key = 'start_ts';
            }
            $event_date  = get_post_meta( get_the_ID(), $start_key, true );
            if ( ! ctype_digit( $event_date ) ) {
                $event_date = strtotime( $event_date );
            }

            $date_display = $event_date ? date( 'M d, Y', $event_date ) : '';
            $time_display = $event_date ? date( 'h:i A', $event_date ) : '';

            // Featured image
            if ( '' !== get_the_post_thumbnail() ) {
                $thumb = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
            }else{
                $image_date  = date_i18n( 'D', $event_date );
                $thumb       = 'https://dummyimage.com/200x200/ccc/969696.png&text=' . $image_date;
            }

            echo '<div class="event-card">';
                // Left: Image
                echo '<div class="event-thumb">';
                    echo '<a href="' . esc_url( $event_url ) . '"><img src="' . esc_url( $thumb ) . '" alt="' . esc_attr( $event_title ) . '"></a>';
                echo '</div>';

                // Right: Details
                echo '<div class="event-details">';
                    echo '<a class="event-title" href="' . esc_url( $event_url ) . '">' . esc_html( $event_title ) . '</a>';
                    if ( $date_display ) {
                        echo '<div class="event-date">' . esc_html( $date_display ) . '</div>';
                    }
                    if ( $time_display ) {
                        echo '<div class="event-time">' . esc_html( $time_display ) . '</div>';
                    }
                echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        wp_reset_postdata();
    }

    // ========== Style 6 ==========
    private function xylusec_render_style_6( $events_query, $selected_post_type ) {
        echo '<div class="easy-events-widget-masonry style-6">';
        while ( $events_query->have_posts() ) {
            $events_query->the_post();
            $event_url   = get_permalink();
            $event_title = get_the_title();

            // Event date
            if( $selected_post_type == 'ajde_events' ){
                $start_key = 'evcal_srow';
            }elseif( $selected_post_type == 'event' ){
                $start_key = '_event_start';
            }else{
                $start_key = 'start_ts';
            }
            $event_date  = get_post_meta( get_the_ID(), $start_key, true );
            if ( ! ctype_digit( $event_date ) ) {
                $event_date = strtotime( $event_date );
            }

            $date_display = $event_date ? date( 'M d, Y', $event_date ) : '';

            // Thumbnail
            if ( '' !== get_the_post_thumbnail() ) {
                $thumb = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
            }else{
                $image_date  = date_i18n( 'F+d', $event_date );
                $thumb       = 'https://dummyimage.com/200x200/ccc/969696.png&text=' . $image_date;
            }

            echo '<div class="event-card">';
                echo '<div class="event-thumb">';
                    echo '<a href="' . esc_url( $event_url ) . '"><img src="' . esc_url( $thumb ) . '" alt="' . esc_attr( $event_title ) . '"></a>';
                    if ( $date_display ) {
                        echo '<span class="event-date">' . esc_html( $date_display ) . '</span>';
                    }
                echo '</div>';
                echo '<div class="event-details">';
                    echo '<a class="event-title" href="' . esc_url( $event_url ) . '">' . esc_html( $event_title ) . '</a>';
                echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        wp_reset_postdata();
    }

    // ========== Style 7 ==========
    private function xylusec_render_style_7( $events_query, $selected_post_type ) {
        echo '<div class="easy-events-widget-timeline style-7">';
        while ( $events_query->have_posts() ) {
            $events_query->the_post();
            $event_url   = get_permalink();
            $event_title = get_the_title();

            // Event date
            if( $selected_post_type == 'ajde_events' ){
                $start_key = 'evcal_srow';
            }elseif( $selected_post_type == 'event' ){
                $start_key = '_event_start';
            }else{
                $start_key = 'start_ts';
            }
            $event_date  = get_post_meta( get_the_ID(), $start_key, true );
            if ( ! ctype_digit( $event_date ) ) {
                $event_date = strtotime( $event_date );
            }

            $date_display = $event_date ? date( 'M d, Y', $event_date ) : '';

            echo '<div class="timeline-item">';
                echo '<div class="timeline-marker"></div>';
                echo '<div class="timeline-content">';
                    if ( $date_display ) {
                        echo '<span class="event-date">' . esc_html( $date_display ) . '</span>';
                    }
                    echo '<a class="event-title" href="' . esc_url( $event_url ) . '">' . esc_html( $event_title ) . '</a>';
                echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        wp_reset_postdata();
    }

    // ========== Style 8 ==========
    private function xylusec_render_style_8( $events_query, $selected_post_type ) {
        echo '<div class="easy-events-widget-grid style-8">';
        while ( $events_query->have_posts() ) {
            $events_query->the_post();
            $event_url   = get_permalink();
            $event_title = get_the_title();

            // Event date
            if( $selected_post_type == 'ajde_events' ){
                $start_key = 'evcal_srow';
            }elseif( $selected_post_type == 'event' ){
                $start_key = '_event_start';
            }else{
                $start_key = 'start_ts';
            }
            $event_date  = get_post_meta( get_the_ID(), $start_key, true );
            if ( ! ctype_digit( $event_date ) ) {
                $event_date = strtotime( $event_date );
            }
            $date_display = $event_date ? date( 'M d, Y', $event_date ) : '';

            // Background image
            if ( '' !== get_the_post_thumbnail() ) {
                $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
            }else{
                $image_date  = date_i18n( 'F+d', $event_date );
                $thumb_url       = 'https://dummyimage.com/250x250/ccc/969696.png&text=' . $image_date;
            }

            echo '<div class="overlay-card" style="background-image:url(' . esc_url( $thumb_url ) . ');background-position: bottom;">';
                echo '<a href="' . esc_url( $event_url ) . '" class="overlay-link">';
                    echo '<div class="overlay-gradient">';
                        if ( $date_display ) {
                            echo '<span class="event-date">' . esc_html( $date_display ) . '</span>';
                        }
                        echo '<h4 class="event-title">' . esc_html( $event_title ) . '</h4>';
                    echo '</div>';
                echo '</a>';
            echo '</div>';
        }
        echo '</div>';
        wp_reset_postdata();
    }

    // ========== Style 9 ==========
    private function xylusec_render_style_9( $events_query, $selected_post_type ) {
        echo '<div class="easy-events-widget-badge style-9">';
        while ( $events_query->have_posts() ) {
            $events_query->the_post();
            $event_url   = get_permalink();
            $event_title = get_the_title();

            // Event date
            if( $selected_post_type == 'ajde_events' ){
                $start_key = 'evcal_srow';
            }elseif( $selected_post_type == 'event' ){
                $start_key = '_event_start';
            }else{
                $start_key = 'start_ts';
            }
            $event_date  = get_post_meta( get_the_ID(), $start_key, true );
            if ( ! ctype_digit( $event_date ) ) {
                $event_date = strtotime( $event_date );
            }
            $date_display = $event_date ? date( 'M d', $event_date ) : '';

            echo '<div class="event-badge">';
                if ( $date_display ) {
                    echo '<span class="event-date">' . esc_html( $date_display ) . '</span>';
                }
                echo '<a class="event-title" href="' . esc_url( $event_url ) . '">' . esc_html( $event_title ) . '</a>';
            echo '</div>';
        }
        echo '</div>';
        wp_reset_postdata();
    }

    // ========== Style 10 ==========
    private function xylusec_render_style_10( $events_query, $selected_post_type ) {
        echo '<div class="easy-events-widget-overlay style-10">';
        while ( $events_query->have_posts() ) {
            $events_query->the_post();
            $event_url   = get_permalink();
            $event_title = get_the_title();

            // Event date
            if( $selected_post_type == 'ajde_events' ){
                $start_key = 'evcal_srow';
            }elseif( $selected_post_type == 'event' ){
                $start_key = '_event_start';
            }else{
                $start_key = 'start_ts';
            }
            $event_date  = get_post_meta( get_the_ID(), $start_key, true );
            if ( ! ctype_digit( $event_date ) ) {
                $event_date = strtotime( $event_date );
            }
            $date_display = $event_date ? date( 'M d, Y', $event_date ) : '';

            // Thumbnail (fallback to default color block if no image)
            if ( '' !== get_the_post_thumbnail() ) {
                $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
            }else{
                $image_date  = date_i18n( 'F+d', $event_date );
                $thumb_url   = 'https://dummyimage.com/250x250/ccc/969696.png&text=' . $image_date;
            }

            echo '<div class="event-overlay-card" style="background-image: url(' . esc_url( $thumb_url ) . ');background-position: center;">';
                if ( $date_display ) {
                    echo '<span class="event-date-badge">' . esc_html( $date_display ) . '</span>';
                }
                echo '<div class="event-overlay-content">';
                    echo '<a class="event-title" href="' . esc_url( $event_url ) . '">' . esc_html( $event_title ) . '</a>';
                echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        wp_reset_postdata();
    }

    // Widget backend form
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Upcoming Events', 'easy-events-calendar' );
        $limit = ! empty( $instance['limit'] ) ? absint( $instance['limit'] ) : 5;
        $style = ! empty( $instance['style'] ) ? $instance['style'] : 'style1';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php _e( 'Number of events to show:' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="number" step="1" min="1"
                value="<?php echo esc_attr( $limit ); ?>" size="3">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php _e( 'Select Style:' ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
                <option value="style1" <?php selected( $style, 'style1' ); ?>><?php _e( 'Style 1 (List)', 'easy-events-calendar' ); ?></option>
                <option value="style2" <?php selected( $style, 'style2' ); ?>><?php _e( 'Style 2 (Card Grid)', 'easy-events-calendar' ); ?></option>
                <option value="style3" <?php selected( $style, 'style3' ); ?>><?php _e( 'Style 3 Modern card with top border + hover shadow', 'easy-events-calendar' ); ?></option>
                <option value="style4" <?php selected( $style, 'style4' ); ?>><?php _e( 'Style 4 Date badge + title list', 'easy-events-calendar' ); ?></option>
                <option value="style5" <?php selected( $style, 'style5' ); ?>><?php _e( 'Style 5 Horizontal card with thumbnail', 'easy-events-calendar' ); ?></option>
                <option value="style6" <?php selected( $style, 'style6' ); ?>><?php _e( 'Style 6 Grid/Masonry cards', 'easy-events-calendar' ); ?></option>
                <option value="style7" <?php selected( $style, 'style7' ); ?>><?php _e( 'Style 7 Timeline view', 'easy-events-calendar' ); ?></option>
                <option value="style8" <?php selected( $style, 'style8' ); ?>><?php _e( 'Style 8 Modern Bar', 'easy-events-calendar' ); ?></option>
                <option value="style9" <?php selected( $style, 'style9' ); ?>><?php _e( 'Style 9 Vertical Timeline', 'easy-events-calendar' ); ?></option>
                <option value="style10" <?php selected( $style, 'style10' ); ?>><?php _e( 'Style 10 Image Overlay', 'easy-events-calendar' ); ?></option>
            </select>
        </p>
        <?php
    }

    // Save widget form values
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['limit'] = absint( $new_instance['limit'] );
        $instance['style'] = in_array( $new_instance['style'], array( 'style1', 'style2', 'style3', 'style4', 'style5', 'style6', 'style7', 'style8', 'style9', 'style10' ) ) ? $new_instance['style'] : 'style1';
        return $instance;
    }
}


// Register widget
function easy_events_calendar_register_widget() {
    register_widget( 'Easy_Events_Calendar_Widgets' );
}
add_action( 'widgets_init', 'easy_events_calendar_register_widget' );
