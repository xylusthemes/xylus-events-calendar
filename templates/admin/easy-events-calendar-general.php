<?php
/**
 * Admin General page
 *
 * @package     Easy_Events_Calendar
 * @subpackage  Admin/Pages
 * @copyright   Copyright (c) 2025, Rajat Patel
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

global $xt_events_calendar;

$xtec_options     = get_option( XTEC_OPTIONS, true );

?>
<form method="post" action="">
    <div class="form-table">
        <div class="xtec-card mt-2">
            <div class="header">
                <div class="text">
                    <div class="header-icon"></div>
                    <div class="header-title">
                        <span><?php esc_attr_e( 'General Settings', 'easy-events-calendar' ); ?></span>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="xtec-settings-wrapper">

                    <!-- Event Source -->
                    <div class="xtec-setting-row">
                        <div class="xtec-inner-section-1">
                            <label for="xtec_event_source"><?php esc_attr_e( 'Select Event Source', 'easy-events-calendar' ); ?></label>
                        </div>
                        <div class="xtec-inner-section-2">
                            <select id="xtec_event_source" name="xtec_event_source">
                                <option value="" disabled selected><?php esc_attr_e( 'Choose...', 'easy-events-calendar' ); ?></option>
                                <option value="wp_events" <?php selected( $xtec_options['xtec_event_source'] ?? '', 'wp_events' ); ?>><?php esc_attr_e( 'WP Event Aggregator', 'easy-events-calendar' ); ?></option>
                                <option value="eventbrite_events" <?php selected( $xtec_options['xtec_event_source'] ?? '', 'eventbrite_events' ); ?>><?php esc_attr_e( 'Import Eventbrite Events', 'easy-events-calendar' ); ?></option>
                                <option value="facebook_events" <?php selected( $xtec_options['xtec_event_source'] ?? '', 'facebook_events' ); ?>><?php esc_attr_e( 'Import Facebook Events', 'easy-events-calendar' ); ?></option>
                                <option value="meetup_events" <?php selected( $xtec_options['xtec_event_source'] ?? '', 'meetup_events' ); ?>><?php esc_attr_e( 'Import Meetup Events', 'easy-events-calendar' ); ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Default View -->
                    <div class="xtec-setting-row">
                        <div class="xtec-inner-section-1"><label for="xtec_default_view"><?php esc_attr_e( 'Default Calendar View', 'easy-events-calendar' ); ?></label></div>
                        <div class="xtec-inner-section-2">
                            <select id="xtec_default_view" name="xtec_default_view">
                                <?php
                                $views = [ 'month', 'week', 'day', 'list', 'grid', 'row', 'staggered' ];
                                foreach ( $views as $view ) {
                                    echo '<option value="' . esc_attr( $view ) . '" ' . selected( $xtec_options['xtec_default_view'] ?? '', $view, false ) . '>' . esc_attr( ucfirst( $view ) ) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Events Per Page -->
                    <div class="xtec-setting-row">
                        <div class="xtec-inner-section-1"><label for="xtec_events_per_page"><?php esc_attr_e( 'Events Per Page', 'easy-events-calendar' ); ?></label></div>
                        <div class="xtec-inner-section-2">
                            <input type="number" id="xtec_events_per_page" name="xtec_events_per_page" value="<?php echo esc_attr( $xtec_options['xtec_events_per_page'] ?? 12 ); ?>" min="1">
                        </div>
                    </div>

                    <!-- Load More Text -->
                    <div class="xtec-setting-row">
                        <div class="xtec-inner-section-1"><label for="xtec_load_more_label"><?php esc_attr_e( 'Load More Button Text', 'easy-events-calendar' ); ?></label></div>
                        <div class="xtec-inner-section-2">
                            <input type="text" id="xtec_load_more_label" name="xtec_load_more_label" value="<?php echo esc_attr( $xtec_options['xtec_load_more_label'] ?? 'Load More Events' ); ?>">
                        </div>
                    </div>

                    <!-- View Details -->
                    <div class="xtec-setting-row">
                        <div class="xtec-inner-section-1"><label for="xtec_view_details_label"><?php esc_attr_e( 'View Details', 'easy-events-calendar' ); ?></label></div>
                        <div class="xtec-inner-section-2">
                            <input type="text" id="xtec_view_details_label" name="xtec_view_details_label" value="<?php echo esc_attr( $xtec_options['xtec_view_details_label'] ?? 'View Details' ); ?>">
                        </div>
                    </div>

                    <!-- Week Start From -->
                    <div class="xtec-setting-row">
                        <div class="xtec-inner-section-1"><label for="xtec_week_start"><?php esc_attr_e( 'Week Starts On', 'easy-events-calendar' ); ?></label></div>
                        <div class="xtec-inner-section-2">
                            <select id="xtec_week_start" name="xtec_week_start">
                                <?php
                                $days = [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ];
                                foreach ( $days as $i => $day ) {
                                    echo '<option value="' . esc_attr( $i ) . '" ' . selected( $xtec_options['xtec_week_start'] ?? 0, $i, false ) . '>' . esc_attr( $day ) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Appearance Options -->
                    <?php
                    $color_fields = [ 'xtec_button_color' => '#2c3e50', 'xtec_text_color' => '#FFFFFF', 'xtec_event_title_color' => '#60606e' ];
                    foreach ( $color_fields as $key => $default ) {
                        ?>
                        <div class="xtec-setting-row">
                            <div class="xtec-inner-section-1"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( ucwords( str_replace( '_', ' ', str_replace( 'xtec_', '', $key ) ) ) ); ?></label></div>
                            <div class="xtec-inner-section-2">
                                <input type="color" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $xtec_options[$key] ?? $default ); ?>">
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="xtec-setting-row">
        <div class="xtec-inner-section-1">
            <?php wp_nonce_field( 'xtec_so_setting_form_nonce_action', 'xtec_so_setting_form_nonce' ); ?>
            <input type="hidden" name="xtec_so_action" value="xtec_save_so_settings" />
            <input type="submit"class="xtec_button" style="display: flex;align-items: center;color: #fff;"  value="<?php esc_attr_e( 'Save Settings', 'easy-events-calendar' ); ?>" />
        </div>
    </div>
</form>