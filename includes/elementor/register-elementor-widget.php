<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function easy_events_calendar_register_elementor_widget( $widgets_manager ) {
    require_once XYLUSEC_PLUGIN_DIR . 'includes/elementor/class-eec-elementor-widget.php';
    $widgets_manager->register( new \Elementor_Easy_Events_Calendar_Widget() );
}
add_action( 'elementor/widgets/register', 'easy_events_calendar_register_elementor_widget' );
