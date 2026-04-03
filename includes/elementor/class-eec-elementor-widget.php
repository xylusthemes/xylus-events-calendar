<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Elementor_Easy_Events_Calendar_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'easy_events_calendar_elementor'; }
    public function get_title() { return __( 'Easy Events Calendar', 'xylus-events-calendar' ); }
    public function get_icon() { return 'eicon-calendar'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [ 'label' => __( 'Settings', 'xylus-events-calendar' ) ]
        );

        $this->add_control(
            'style',
            [
                'label'   => __( 'Style', 'xylus-events-calendar' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'style1',
                'options' => [
                    'style1' => 'Style 1',
                    'style2' => 'Style 2',
                    'style3' => 'Style 3',
                    'style4' => 'Style 4',
                    'style5' => 'Style 5',
                    'style6' => 'Style 6',
                    'style7' => 'Style 7',
                    'style8' => 'Style 8',
                    'style9' => 'Style 9',
                    'style10' => 'Style 10',
                ],
            ]
        );

        $this->add_control(
            'limit',
            [
                'label' => __( 'Events Limit', 'xylus-events-calendar' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'max' => 50,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        echo do_shortcode('[easy_events_calendar_elementor style="' . esc_attr($settings['style']) . '" limit="' . esc_attr($settings['limit']) . '"]');
    }
}
