<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
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
        <div class="xylusec-card mt-2" style="border-radius: 8px; overflow: hidden; border-color: #e2e8f0;">
            <div class="header" style="background-color: #f8fafc; border-bottom-color: #e2e8f0;">
                <div class="text">
                    <div class="header-icon"></div>
                    <div class="header-title">
                        <span style="font-weight: 700; color: #0f172a; font-size: 15px;"><?php esc_attr_e( 'General Settings', 'xylus-events-calendar' ); ?></span>
                    </div>
                </div>
            </div>
            <div class="content" style="padding: 28px;">
                <div class="xylusec-settings-wrapper">

                    <!-- Event Source -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1">
                            <label for="xylusec_event_source"><?php esc_attr_e( 'Select Event Source', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_attr_e( 'Choose the primary source to query and display events on your calendar views.', 'xylus-events-calendar' ); ?></span>
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
                        <div class="xylusec-inner-section-1">
                            <label for="xylusec_default_view"><?php esc_attr_e( 'Default Calendar View', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_attr_e( 'Set the default view rendered when the events calendar loads on the page.', 'xylus-events-calendar' ); ?></span>
                        </div>
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

                    <!-- Hide Header -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1">
                            <label><?php esc_attr_e( 'Hide Header', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_attr_e( 'Hide the entire calendar header section, including navigation buttons and search options.', 'xylus-events-calendar' ); ?></span>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <?php $hide_header_val = $xylusec_options['xylusec_hide_header'] ?? 'no'; ?>
                            <div class="xylusec-filter-card <?php echo $hide_header_val === 'yes' ? 'is-active' : ''; ?>" style="max-width: 450px;">
                                <span class="xylusec-filter-card-label"><?php esc_html_e( 'Hide Calendar Header', 'xylus-events-calendar' ); ?></span>
                                <label class="xylusec-switch">
                                    <input type="checkbox" class="xylusec-filter-checkbox" id="xylusec_hide_header" name="xylusec_hide_header" value="1" <?php checked( $hide_header_val, 'yes' ); ?>>
                                    <span class="xylusec-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Show Filters Bar -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1">
                            <label><?php esc_attr_e( 'Show Filters Bar', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_attr_e( 'Display the multi-select dynamic filters bar above the calendar grid on the frontend.', 'xylus-events-calendar' ); ?></span>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <?php $show_filters_val = $xylusec_options['xylusec_show_filters'] ?? 'no'; ?>
                            <div class="xylusec-filter-card <?php echo $show_filters_val === 'yes' ? 'is-active' : ''; ?>" style="max-width: 450px;">
                                <span class="xylusec-filter-card-label"><?php esc_html_e( 'Show Filters Bar', 'xylus-events-calendar' ); ?></span>
                                <label class="xylusec-switch">
                                    <input type="checkbox" class="xylusec-filter-checkbox" id="xylusec_show_filters" name="xylusec_show_filters" value="1" <?php checked( $show_filters_val, 'yes' ); ?>>
                                    <span class="xylusec-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Individual Filters Sub-options -->
                    <div class="xylusec-setting-row xylusec-filters-suboptions" style="<?php echo $show_filters_val === 'yes' ? '' : 'display:none;'; ?>">
                        <div class="xylusec-inner-section-1">
                            <label><?php esc_attr_e( 'Filter Bar Fields', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_attr_e( 'Choose which filter options will be rendered in the filters bar.', 'xylus-events-calendar' ); ?></span>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <div class="xylusec-filters-grid">
                                <?php
                                $available_filters = [
                                    'category'   => __( 'Category Filter', 'xylus-events-calendar' ),
                                    'tag'        => __( 'Tag Filter', 'xylus-events-calendar' ),
                                    'venue'      => __( 'Venue Filter', 'xylus-events-calendar' ),
                                    'organizer'  => __( 'Organizer Filter', 'xylus-events-calendar' ),
                                    'collection' => __( 'Collection Filter', 'xylus-events-calendar' ),
                                    'day'        => __( 'Day Filter', 'xylus-events-calendar' ),
                                    'time'       => __( 'Time Filter', 'xylus-events-calendar' ),
                                    'date_from'  => __( 'Date From Filter', 'xylus-events-calendar' ),
                                    'date_to'    => __( 'Date To Filter', 'xylus-events-calendar' ),
                                ];
                                foreach ( $available_filters as $filter_key => $filter_label ) {
                                    $field_name = 'xylusec_filter_show_' . $filter_key;
                                    $field_val = $xylusec_options[$field_name] ?? 'yes';
                                    $is_active = $field_val === 'yes';
                                    ?>
                                    <div class="xylusec-filter-card <?php echo $is_active ? 'is-active' : ''; ?>">
                                        <span class="xylusec-filter-card-label"><?php echo esc_html( $filter_label ); ?></span>
                                        <label class="xylusec-switch">
                                            <input type="checkbox" class="xylusec-filter-checkbox" name="<?php echo esc_attr( $field_name ); ?>" value="1" <?php checked( $field_val, 'yes' ); ?>>
                                            <span class="xylusec-slider"></span>
                                        </label>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Events Per Page -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1">
                            <label for="xylusec_events_per_page"><?php esc_attr_e( 'Events Per Page', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_attr_e( 'The default number of events shown in grid, row, staggered, or list calendar formats.', 'xylus-events-calendar' ); ?></span>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <input type="number" id="xylusec_events_per_page" name="xylusec_events_per_page" value="<?php echo esc_attr( $xylusec_options['xylusec_events_per_page'] ?? 12 ); ?>" min="1">
                        </div>
                    </div>

                    <!-- Load More Text -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1">
                            <label for="xylusec_load_more_label"><?php esc_attr_e( 'Load More Button Text', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_attr_e( 'Specify the text shown on the Load More pagination button.', 'xylus-events-calendar' ); ?></span>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <input type="text" id="xylusec_load_more_label" name="xylusec_load_more_label" value="<?php echo esc_attr( $xylusec_options['xylusec_load_more_label'] ?? 'Load More Events' ); ?>">
                        </div>
                    </div>

                    <!-- View Details -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1">
                            <label for="xylusec_view_details_label"><?php esc_attr_e( 'View Details Button Text', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_attr_e( 'Specify the text shown on the individual event details button.', 'xylus-events-calendar' ); ?></span>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <input type="text" id="xylusec_view_details_label" name="xylusec_view_details_label" value="<?php echo esc_attr( $xylusec_options['xylusec_view_details_label'] ?? 'View Details' ); ?>">
                        </div>
                    </div>

                    <!-- Week Start From -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1">
                            <label for="xylusec_week_start"><?php esc_attr_e( 'Week Starts On', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_attr_e( 'Choose which day of the week is displayed in the first column of the month view.', 'xylus-events-calendar' ); ?></span>
                        </div>
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

                    <!-- Master iCal Feed -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1">
                            <label><?php esc_attr_e( 'Master iCal Feed URL', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_attr_e( 'Copy this URL to subscribe to all upcoming published events in Google Calendar, Apple Calendar, or Outlook.', 'xylus-events-calendar' ); ?></span>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                                <input type="text" readonly="readonly" value="<?php echo esc_url( site_url( '?xylusec_ical_feed=1' ) ); ?>" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1; width: 100%;" id="xylusec_master_ical_url">
                                <button type="button" class="xylusec-btn-copy-shortcode button-primary" data-value="<?php echo esc_url( site_url( '?xylusec_ical_feed=1' ) ); ?>" style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px; padding: 0 20px;"><?php esc_html_e( 'Copy', 'xylus-events-calendar' ); ?></button>
                            </div>
                        </div>
                    </div>


                    <!-- Appearance Options -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1">
                            <label><?php esc_attr_e( 'Appearance Colors', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_attr_e( 'Customize primary button background, text, and title colors across your events calendar views.', 'xylus-events-calendar' ); ?></span>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <div class="xylusec-color-pickers-group">
                                <?php
                                $xylusec_color_fields = [
                                    'xylusec_primary_color'     => [ 'label' => __( 'Primary Brand Color', 'xylus-events-calendar' ), 'default' => $xylusec_events_calendar->common->xylusec_get_theme_primary_color() ],
                                    'xylusec_button_color'      => [ 'label' => __( 'Button Background Color', 'xylus-events-calendar' ), 'default' => '#2c3e50' ],
                                    'xylusec_text_color'        => [ 'label' => __( 'Button Text Color', 'xylus-events-calendar' ), 'default' => '#FFFFFF' ],
                                    'xylusec_event_title_color' => [ 'label' => __( 'Event Title Color', 'xylus-events-calendar' ), 'default' => '#60606e' ]
                                ];
                                foreach ( $xylusec_color_fields as $xylusec_key => $xylusec_meta ) {
                                    $col_val = $xylusec_options[$xylusec_key] ?? $xylusec_meta['default'];
                                    ?>
                                    <div class="xylusec-color-picker-item">
                                        <label><?php echo esc_html( $xylusec_meta['label'] ); ?></label>
                                        <div class="xylusec-color-picker-wrap">
                                            <input type="color" class="xylusec-color-input" id="<?php echo esc_attr( $xylusec_key ); ?>" name="<?php echo esc_attr( $xylusec_key ); ?>" value="<?php echo esc_attr( $col_val ); ?>">
                                            <span class="xylusec-color-val"><?php echo esc_html( strtoupper( $col_val ) ); ?></span>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="xylusec-setting-row" style="margin-top: 24px;">
        <div class="xylusec-inner-section-2" style="display: flex; gap: 12px; align-items: center;">
            <?php wp_nonce_field( 'xylusec_so_setting_form_nonce_action', 'xylusec_so_setting_form_nonce' ); ?>
            <input type="hidden" name="xylusec_so_action" value="xylusec_save_so_settings" />
            <input type="submit" class="xylusec_button" value="<?php esc_attr_e( 'Save Settings', 'xylus-events-calendar' ); ?>" />
        </div>
    </div>
</form>

<script type="text/javascript">
jQuery(document).ready(function($) {
    // Toggle sub-options visibility when show filters is checked/unchecked
    $('#xylusec_show_filters').on('change', function() {
        if ($(this).is(':checked')) {
            $('.xylusec-filters-suboptions').slideDown();
        } else {
            $('.xylusec-filters-suboptions').slideUp();
        }
    });

    // Toggle active class on card elements when switches change state
    $('.xylusec-filter-checkbox').on('change', function() {
        var $card = $(this).closest('.xylusec-filter-card');
        if ($(this).is(':checked')) {
            $card.addClass('is-active');
        } else {
            $card.removeClass('is-active');
        }
    });

    // Make entire card clickable to toggle switch
    $(document).on('click', '.xylusec-filter-card', function(e) {
        if (!$(e.target).closest('.xylusec-switch').length) {
            var $checkbox = $(this).find('.xylusec-filter-checkbox');
            $checkbox.prop('checked', !$checkbox.prop('checked')).trigger('change');
        }
    });

    // Sync color input changes to corresponding color text values
    $('.xylusec-color-input').on('input', function() {
        $(this).siblings('.xylusec-color-val').text($(this).val().toUpperCase());
    });
});
</script>