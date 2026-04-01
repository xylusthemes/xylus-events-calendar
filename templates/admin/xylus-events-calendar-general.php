<?php
/**
 * Admin General page
 *
 * @package     Xylus_Events_Calendar
 * @subpackage  Admin/Pages
 * @copyright   Copyright (c) 2025, Rajat Patel
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

global $xylusec_events_calendar;

$xylusec_options     = get_option( XYLUSEC_OPTIONS, true );

?>
<form method="post" action="">
    <div class="form-table">
        <div class="xylusec-card mt-2">
            <div class="header">
                <div class="text">
                    <div class="header-icon"></div>
                    <div class="header-title">
                        <span><?php esc_attr_e( 'General Settings', 'xylus-events-calendar' ); ?></span>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="xylusec-settings-wrapper">

                    <!-- Event Source -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1">
                            <label for="xylusec_event_source"><?php esc_attr_e( 'Select Event Source', 'xylus-events-calendar' ); ?></label>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <select id="xylusec_event_source" name="xylusec_event_source">
                                <option value="" disabled selected><?php esc_attr_e( 'Choose...', 'xylus-events-calendar' ); ?></option>
                                <option value="eec_events" <?php selected( $xylusec_options['xylusec_event_source'] ?? '', 'eec_events' ); ?>><?php esc_attr_e( 'Easy Event Calnder', 'xylus-events-calendar' ); ?></option>
                                <option value="wp_events" <?php selected( $xylusec_options['xylusec_event_source'] ?? '', 'wp_events' ); ?>><?php esc_attr_e( 'WP Event Aggregator', 'xylus-events-calendar' ); ?></option>
                                <option value="eventbrite_events" <?php selected( $xylusec_options['xylusec_event_source'] ?? '', 'eventbrite_events' ); ?>><?php esc_attr_e( 'Import Eventbrite Events', 'xylus-events-calendar' ); ?></option>
                                <option value="facebook_events" <?php selected( $xylusec_options['xylusec_event_source'] ?? '', 'facebook_events' ); ?>><?php esc_attr_e( 'Import Facebook Events', 'xylus-events-calendar' ); ?></option>
                                <option value="meetup_events" <?php selected( $xylusec_options['xylusec_event_source'] ?? '', 'meetup_events' ); ?>><?php esc_attr_e( 'Import Meetup Events', 'xylus-events-calendar' ); ?></option>
                                <option value="ajde_events" <?php selected( $xylusec_options['xylusec_event_source'] ?? '', 'ajde_events' ); ?>><?php esc_attr_e( 'EventOn', 'xylus-events-calendar' ); ?></option>
                                <option value="event" <?php selected( $xylusec_options['xylusec_event_source'] ?? '', 'event' ); ?>><?php esc_attr_e( 'Events Manager', 'xylus-events-calendar' ); ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Default View -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1"><label for="xylusec_default_view"><?php esc_attr_e( 'Default Calendar View', 'xylus-events-calendar' ); ?></label></div>
                        <div class="xylusec-inner-section-2">
                            <select id="xylusec_default_view" name="xylusec_default_view">
                                <?php
                                $xylusec_views = [ 'month', 'week', 'day', 'list', 'grid', 'row', 'staggered', 'slider' ];
                                foreach ( $xylusec_views as $xylusec_view ) {
                                    echo '<option value="' . esc_attr( $xylusec_view ) . '" ' . selected( $xylusec_options['xylusec_default_view'] ?? '', $xylusec_view, false ) . '>' . esc_attr( ucfirst( $xylusec_view ) ) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Event Source -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1">
                            <label for="xylusec_event_source"><?php esc_attr_e( 'Hide Header', 'xylus-events-calendar' ); ?></label>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <input type="checkbox" id="xylusec_hide_header" name="xylusec_hide_header" value="1" <?php checked( $xylusec_options['xylusec_hide_header'] ?? 'no', 'yes' ); ?>>
                            <label for="xylusec_hide_header"><?php esc_attr_e( 'Check to hide the header, including the search box and view buttons.', 'xylus-events-calendar' ); ?></label>
                        </div>
                    </div>

                    <!-- Events Per Page -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1"><label for="xylusec_events_per_page"><?php esc_attr_e( 'Events Per Page', 'xylus-events-calendar' ); ?></label></div>
                        <div class="xylusec-inner-section-2">
                            <input type="number" id="xylusec_events_per_page" name="xylusec_events_per_page" value="<?php echo esc_attr( $xylusec_options['xylusec_events_per_page'] ?? 12 ); ?>" min="1">
                        </div>
                    </div>

                    <!-- Load More Text -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1"><label for="xylusec_load_more_label"><?php esc_attr_e( 'Load More Button Text', 'xylus-events-calendar' ); ?></label></div>
                        <div class="xylusec-inner-section-2">
                            <input type="text" id="xylusec_load_more_label" name="xylusec_load_more_label" value="<?php echo esc_attr( $xylusec_options['xylusec_load_more_label'] ?? 'Load More Events' ); ?>">
                        </div>
                    </div>

                    <!-- View Details -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1"><label for="xylusec_view_details_label"><?php esc_attr_e( 'View Details', 'xylus-events-calendar' ); ?></label></div>
                        <div class="xylusec-inner-section-2">
                            <input type="text" id="xylusec_view_details_label" name="xylusec_view_details_label" value="<?php echo esc_attr( $xylusec_options['xylusec_view_details_label'] ?? 'View Details' ); ?>">
                        </div>
                    </div>

                    <!-- Week Start From -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1"><label for="xylusec_week_start"><?php esc_attr_e( 'Week Starts On', 'xylus-events-calendar' ); ?></label></div>
                        <div class="xylusec-inner-section-2">
                            <select id="xylusec_week_start" name="xylusec_week_start">
                                <?php
                                $xylusec_days = [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ];
                                foreach ( $xylusec_days as $xylusec_i => $xylusec_day ) {
                                    echo '<option value="' . esc_attr( $xylusec_i ) . '" ' . selected( $xylusec_options['xylusec_week_start'] ?? 0, $xylusec_i, false ) . '>' . esc_attr( $xylusec_day ) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Appearance Options -->
                    <?php
                    $xylusec_color_fields = [ 'xylusec_button_color' => '#2c3e50', 'xylusec_text_color' => '#FFFFFF', 'xylusec_event_title_color' => '#60606e' ];
                    foreach ( $xylusec_color_fields as $xylusec_key => $xylusec_default ) {
                        ?>
                        <div class="xylusec-setting-row">
                            <div class="xylusec-inner-section-1"><label for="<?php echo esc_attr( $xylusec_key ); ?>"><?php echo esc_attr( ucwords( str_replace( '_', ' ', str_replace( 'xylusec_', '', $xylusec_key ) ) ) ); ?></label></div>
                            <div class="xylusec-inner-section-2">
                                <input type="color" id="<?php echo esc_attr( $xylusec_key ); ?>" name="<?php echo esc_attr( $xylusec_key ); ?>" value="<?php echo esc_attr( $xylusec_options[$xylusec_key] ?? $xylusec_default ); ?>">
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="xylusec-setting-row">
        <div class="xylusec-inner-section-1">
            <?php wp_nonce_field( 'xylusec_so_setting_form_nonce_action', 'xylusec_so_setting_form_nonce' ); ?>
            <input type="hidden" name="xylusec_so_action" value="xylusec_save_so_settings" />
            <input type="submit"class="xylusec_button" style="display: flex;align-items: center;color: #fff;"  value="<?php esc_attr_e( 'Save Settings', 'xylus-events-calendar' ); ?>" />
        </div>
    </div>
</form>