<?php
function easy_events_calendar_elementor_shortcode( $atts ) {
    global $xylusec_events_calendar;

    $atts = shortcode_atts( array(
        'style' => 'style1',
        'limit' => 5,
    ), $atts, 'easy_events_calendar_elementor' );

    $style = sanitize_text_field( $atts['style'] );
    $limit = absint( $atts['limit'] );

    $selected_post_type = get_option( XYLUSEC_OPTIONS ) ? get_option( XYLUSEC_OPTIONS )['xylusec_event_source'] ?? '' : '';
    $current_time       = current_time( 'timestamp' );
    if( $selected_post_type == 'ajde_events' ){
        $start_key = 'evcal_srow';
        $end_key   = 'evcal_erow';
        $type      = 'NUMERIC'; 
    }elseif( $selected_post_type == 'event' ){
        $start_key = '_event_start';
        $end_key   = '_event_end';
        $type      = 'DATETIME';
        $current_time = gmdate( 'Y-m-d H:i:s', $current_time );
    }else{
        $start_key = 'start_ts';
        $end_key   = 'end_ts';
        $type      = 'NUMERIC';
    }

    
    if ( isset( $xylusec_events_calendar ) && isset( $xylusec_events_calendar->common ) ) {
        $events_query = $xylusec_events_calendar->common->xylusec_get_upcoming_events( $selected_post_type, 1, '', $limit,  array() );
    } else {
        $pt = $selected_post_type ? $selected_post_type : 'post';
        $args = array(
            'post_type'      => $pt,
            'posts_per_page' => $limit,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'ASC',
            'meta_query'     => [ //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                [
                    'key'     => $end_key,
                    'value'   => $current_time,
                    'compare' => '>',
                    'type'    => $type,
                ],
            ],
            'meta_key'       => $start_key,
        );
        
        $events_query = new WP_Query( $args );
    }

    ob_start();

    switch ( $style ) {
        case 'style2':
            $xylusec_events_calendar->widgets->xylusec_render_style_2( $events_query, $selected_post_type );
            break;
        case 'style3':
            $xylusec_events_calendar->widgets->xylusec_render_style_3( $events_query, $selected_post_type );
            break;
        case 'style4':
            $xylusec_events_calendar->widgets->xylusec_render_style_4( $events_query, $selected_post_type );
            break;
        case 'style5':
            $xylusec_events_calendar->widgets->xylusec_render_style_5( $events_query, $selected_post_type );
            break;
        case 'style6':
            $xylusec_events_calendar->widgets->xylusec_render_style_6( $events_query, $selected_post_type );
            break;
        case 'style7':
            $xylusec_events_calendar->widgets->xylusec_render_style_7( $events_query, $selected_post_type );
            break;
        case 'style8':
            $xylusec_events_calendar->widgets->xylusec_render_style_8( $events_query, $selected_post_type );
            break;
        case 'style9':
            $xylusec_events_calendar->widgets->xylusec_render_style_9( $events_query, $selected_post_type );
            break;
        case 'style10':
            $xylusec_events_calendar->widgets->xylusec_render_style_10( $events_query, $selected_post_type );
            break;
        case 'style1':
        default:
            $xylusec_events_calendar->widgets->xylusec_render_style_1( $events_query, $selected_post_type );
            break;
    }

    if ( ! empty( $events_query ) ) {
        wp_reset_postdata();
    }

    return ob_get_clean();
}
add_shortcode( 'easy_events_calendar_elementor', 'easy_events_calendar_elementor_shortcode' );
