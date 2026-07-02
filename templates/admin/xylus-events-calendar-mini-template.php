<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

global $xylusec_events_calendar;
$xylusec_options = get_option(XYLUSEC_OPTIONS, true);
$xylusec_title_color = isset($xylusec_options['xylusec_event_title_color']) ? esc_attr($xylusec_options['xylusec_event_title_color']) : '#60606e';
$xylusec_arrowbg_color = isset($xylusec_options['xylusec_button_color']) ? esc_attr($xylusec_options['xylusec_button_color']) : '#000';
?>
<div id="xylusec-mini-calendar-container" class="xylusec-mini-calendar-wrapper">
    <div class="xylusec-mini-calendar-layout">
        <div class="xylusec-mini-calendar-left">
            <div id="xylusec-mini-calendar-widget"></div>
        </div>
        <div class="xylusec-mini-calendar-right">
            <div class="xylusec-mini-events-list">
                <div class="xylusec-mini-event-row-container">
                    <!-- Events will be loaded here dynamically on load and date click -->
                    <div class="xylusec-mini-placeholder-message"><?php esc_html_e('Select a date on the calendar to see events.', 'xylus-events-calendar'); ?></div>
                </div>
                <div class="xylusec-mini-spinner-main" style="display: none;">
                    <span class="xylusec-load-spinner xylusec-spinner"></span>
                </div>
                <div class="xylusec-mini-no-events" style="display: none;">
                    <?php echo esc_html('No events scheduled for this day.', 'xylus-events-calendar'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
