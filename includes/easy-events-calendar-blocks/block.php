<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function xylus_register_easy_events_calendar_block() {

    register_block_type(
        XYLUSEC_PLUGIN_DIR . 'includes/easy-events-calendar-blocks',
        array(
            'render_callback' => 'xylus_render_easy_events_calendar_block'
        )
    );
}
add_action( 'init', 'xylus_register_easy_events_calendar_block' );

function xylus_register_calendar_block_styles() {
    wp_register_style(
        'xylus-events-calendar-widget-css',
        XYLUSEC_PLUGIN_URL . 'assets/css/xylus-events-calendar-widget.css',
        array(),
        XYLUSEC_VERSION
    );
}
add_action( 'init', 'xylus_register_calendar_block_styles' );

function xylus_render_easy_events_calendar_block( $attributes ) {

    // Default attribute values
    $defaults = [
        'limit'    => '10',
        'category' => '',
        'style'    => 'style1'
    ];

    $attributes = wp_parse_args( $attributes, $defaults );

    // Build Shortcode
    $shortcode = '[easy_events_calendar_gutenberg';

    foreach ( $attributes as $key => $value ) {
        if ( ! empty( $value ) ) {
            $shortcode .= ' ' . $key . '="' . esc_attr( $value ) . '"';
        }
    }

    $shortcode .= ']';

    // Return output
    return '<div class="xylus-event-block">' . do_shortcode( $shortcode ) . '</div>';
}


function xylus_enqueue_block_editor() {
    global $xylusec_events_calendar;

    $get_options       = get_option( XYLUSEC_OPTIONS );
    $selected_plugin   = $get_options['xylusec_event_source'];
    $taxonomy          = $xylusec_events_calendar->common->get_selected_post_type_category( $selected_plugin );

    // Get taxonomy terms
    $terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => false
    ]);

    $categories = [];

    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            $categories[$term->slug] = $term->name;
        }
    }

    wp_enqueue_script(
        'xylus-easy-events-calendar-block-js',
        XYLUSEC_PLUGIN_URL . 'includes/easy-events-calendar-blocks/index.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render'],
        filemtime( XYLUSEC_PLUGIN_DIR . 'includes/easy-events-calendar-blocks/index.js' ),
        true
    );

    wp_localize_script(
        'xylus-easy-events-calendar-block-js',
        'XylusBlockData',
        [
            'categories' => $categories
        ]
    );
}
add_action( 'enqueue_block_editor_assets', 'xylus_enqueue_block_editor' );