<?php
/**
 * Admin Shortcode page
 *
 * @package     Xylus_Events_Calendar
 * @subpackage  Admin/Pages
 * @copyright   Copyright (c) 2025, Rajat Patel
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>
<div class="form-table">
    <!-- Primary Calendar Shortcodes -->
    <div class="xylusec-card mt-2" style="border-radius: 8px; overflow: hidden; border-color: #e2e8f0; margin-bottom: 24px;">
        <div class="header" style="background-color: #f8fafc; border-bottom-color: #e2e8f0;">
            <div class="text">
                <div class="header-icon" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%2216%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%230f172a%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><rect x=%223%22 y=%224%22 width=%2218%22 height=%2218%22 rx=%222%22 ry=%222%22></rect><line x1=%2216%22 y1=%222%22 x2=%2216%22 y2=%226%22></line><line x1=%228%22 y1=%222%22 x2=%228%22 y2=%226%22></line><line x1=%223%22 y1=%2210%22 x2=%2221%22 y2=%2210%22></line></svg>');"></div>
                <div class="header-title">
                    <span style="font-weight: 700; color: #0f172a; font-size: 15px;"><?php esc_html_e( 'Primary Calendar Shortcodes', 'xylus-events-calendar' ); ?></span>
                </div>
            </div>
        </div>
        <div class="content" style="padding: 28px;">
            <div class="xylusec-settings-wrapper">

                <!-- Row 1 -->
                <div class="xylusec-setting-row">
                    <div class="xylusec-inner-section-1">
                        <label><?php esc_html_e( 'Default Event Calendar', 'xylus-events-calendar' ); ?></label>
                        <span class="row-desc"><?php esc_html_e( 'Explore upcoming events in various layouts, including a calendar, grid, list, and more.', 'xylus-events-calendar' ); ?></span>
                    </div>
                    <div class="xylusec-inner-section-2">
                        <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                            <input type="text" readonly value="[easy_events_calendar]" class="xylusec-shortcode-input" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1;" />
                            <button class="xylusec-btn-copy-shortcode button-primary" data-value="[easy_events_calendar]" style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px;">Copy</button>
                        </div>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="xylusec-setting-row">
                    <div class="xylusec-inner-section-1">
                        <label><?php esc_html_e( 'Filter Events by Category', 'xylus-events-calendar' ); ?></label>
                        <span class="row-desc"><?php esc_html_e( 'Display events belonging to a specific category.', 'xylus-events-calendar' ); ?></span>
                    </div>
                    <div class="xylusec-inner-section-2">
                        <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                            <input type="text" readonly value='[easy_events_calendar category="your-category-slug"]' class="xylusec-shortcode-input" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1;" />
                            <button class="xylusec-btn-copy-shortcode button-primary" data-value='[easy_events_calendar category="your-category-slug"]' style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px;">Copy</button>
                        </div>
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="xylusec-setting-row">
                    <div class="xylusec-inner-section-1">
                        <label><?php esc_html_e( 'Filter Events by Collection', 'xylus-events-calendar' ); ?></label>
                        <span class="row-desc"><?php esc_html_e( 'Display events belonging to a specific collection.', 'xylus-events-calendar' ); ?></span>
                    </div>
                    <div class="xylusec-inner-section-2">
                        <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                            <input type="text" readonly value='[easy_events_calendar collection="collection-slug"]' class="xylusec-shortcode-input" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1;" />
                            <button class="xylusec-btn-copy-shortcode button-primary" data-value='[easy_events_calendar collection="collection-slug"]' style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px;">Copy</button>
                        </div>
                    </div>
                </div>

                <!-- Row 4 -->
                <div class="xylusec-setting-row">
                    <div class="xylusec-inner-section-1">
                        <label><?php esc_html_e( 'Event Discovery Archive', 'xylus-events-calendar' ); ?></label>
                        <span class="row-desc"><?php esc_html_e( 'Display the events discovery/archive layout with filters and search options.', 'xylus-events-calendar' ); ?></span>
                    </div>
                    <div class="xylusec-inner-section-2">
                        <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                            <input type="text" readonly value="[eec_events_discovery]" class="xylusec-shortcode-input" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1;" />
                            <button class="xylusec-btn-copy-shortcode button-primary" data-value="[eec_events_discovery]" style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px;">Copy</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Mini Calendar Shortcodes -->
    <div class="xylusec-card mt-2" style="border-radius: 8px; overflow: hidden; border-color: #e2e8f0; margin-bottom: 24px;">
        <div class="header" style="background-color: #f8fafc; border-bottom-color: #e2e8f0;">
            <div class="text">
                <div class="header-icon" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%2216%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%230f172a%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><rect x=%223%22 y=%224%22 width=%2218%22 height=%2218%22 rx=%222%22 ry=%222%22></rect><line x1=%2216%22 y1=%222%22 x2=%2216%22 y2=%226%22></line><line x1=%228%22 y1=%222%22 x2=%228%22 y2=%226%22></line><line x1=%223%22 y1=%2210%22 x2=%2221%22 y2=%2210%22></line></svg>');"></div>
                <div class="header-title">
                    <span style="font-weight: 700; color: #0f172a; font-size: 15px;"><?php esc_html_e( 'Mini Calendar Shortcodes', 'xylus-events-calendar' ); ?></span>
                </div>
            </div>
        </div>
        <div class="content" style="padding: 28px;">
            <div class="xylusec-settings-wrapper">

                <!-- Row 1 -->
                <div class="xylusec-setting-row">
                    <div class="xylusec-inner-section-1">
                        <label><?php esc_html_e( 'Mini Calendar (Default)', 'xylus-events-calendar' ); ?></label>
                        <span class="row-desc"><?php esc_html_e( 'Display the premium mini calendar with event list sidebar using default settings.', 'xylus-events-calendar' ); ?></span>
                    </div>
                    <div class="xylusec-inner-section-2">
                        <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                            <input type="text" readonly value="[easy_event_calendar_mini]" class="xylusec-shortcode-input" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1;" />
                            <button class="xylusec-btn-copy-shortcode button-primary" data-value="[easy_event_calendar_mini]" style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px;">Copy</button>
                        </div>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="xylusec-setting-row">
                    <div class="xylusec-inner-section-1">
                        <label><?php esc_html_e( 'Mini Calendar Category Filter', 'xylus-events-calendar' ); ?></label>
                        <span class="row-desc"><?php esc_html_e( 'Limit the mini calendar and sidebar to events under a specific category.', 'xylus-events-calendar' ); ?></span>
                    </div>
                    <div class="xylusec-inner-section-2">
                        <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                            <input type="text" readonly value='[easy_event_calendar_mini category="your-category-slug"]' class="xylusec-shortcode-input" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1;" />
                            <button class="xylusec-btn-copy-shortcode button-primary" data-value='[easy_event_calendar_mini category="your-category-slug"]' style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px;">Copy</button>
                        </div>
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="xylusec-setting-row">
                    <div class="xylusec-inner-section-1">
                        <label><?php esc_html_e( 'Mini Calendar without Images', 'xylus-events-calendar' ); ?></label>
                        <span class="row-desc"><?php esc_html_e( 'Display the mini calendar and sidebar list with event images hidden.', 'xylus-events-calendar' ); ?></span>
                    </div>
                    <div class="xylusec-inner-section-2">
                        <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                            <input type="text" readonly value='[easy_event_calendar_mini show_image="false"]' class="xylusec-shortcode-input" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1;" />
                            <button class="xylusec-btn-copy-shortcode button-primary" data-value='[easy_event_calendar_mini show_image="false"]' style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px;">Copy</button>
                        </div>
                    </div>
                </div>

                <!-- Row 4 -->
                <div class="xylusec-setting-row">
                    <div class="xylusec-inner-section-1">
                        <label><?php esc_html_e( 'Mini Calendar without Locations', 'xylus-events-calendar' ); ?></label>
                        <span class="row-desc"><?php esc_html_e( 'Hide venue/location names on all event cards in the sidebar.', 'xylus-events-calendar' ); ?></span>
                    </div>
                    <div class="xylusec-inner-section-2">
                        <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                            <input type="text" readonly value='[easy_event_calendar_mini show_location="false"]' class="xylusec-shortcode-input" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1;" />
                            <button class="xylusec-btn-copy-shortcode button-primary" data-value='[easy_event_calendar_mini show_location="false"]' style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px;">Copy</button>
                        </div>
                    </div>
                </div>

                <!-- Row 5 -->
                <div class="xylusec-setting-row">
                    <div class="xylusec-inner-section-1">
                        <label><?php esc_html_e( 'Mini Calendar without Dates', 'xylus-events-calendar' ); ?></label>
                        <span class="row-desc"><?php esc_html_e( 'Hide event start dates and times in the sidebar list.', 'xylus-events-calendar' ); ?></span>
                    </div>
                    <div class="xylusec-inner-section-2">
                        <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                            <input type="text" readonly value='[easy_event_calendar_mini show_date="false"]' class="xylusec-shortcode-input" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1;" />
                            <button class="xylusec-btn-copy-shortcode button-primary" data-value='[easy_event_calendar_mini show_date="false"]' style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px;">Copy</button>
                        </div>
                    </div>
                </div>

                <!-- Row 6 -->
                <div class="xylusec-setting-row">
                    <div class="xylusec-inner-section-1">
                        <label><?php esc_html_e( 'Mini Calendar with Organizers', 'xylus-events-calendar' ); ?></label>
                        <span class="row-desc"><?php esc_html_e( 'Display the event organizer details on each card in the sidebar list.', 'xylus-events-calendar' ); ?></span>
                    </div>
                    <div class="xylusec-inner-section-2">
                        <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                            <input type="text" readonly value='[easy_event_calendar_mini show_organizer="true"]' class="xylusec-shortcode-input" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1;" />
                            <button class="xylusec-btn-copy-shortcode button-primary" data-value='[easy_event_calendar_mini show_organizer="true"]' style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px;">Copy</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>