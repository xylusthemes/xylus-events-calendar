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
            <div style="display: flex; gap: 40px; flex-wrap: wrap;">
                <div class="xylusec-settings-wrapper" style="flex: 1; min-width: 300px;">

                    <!-- Calendar Layout -->
                    <div class="xylusec-setting-row" onmouseover="changePrimaryPreview('calendar');">
                        <div class="xylusec-inner-section-1">
                            <label><?php esc_html_e( 'Calendar Month View', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_html_e( 'Display a traditional full-month calendar view.', 'xylus-events-calendar' ); ?></span>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                                <input type="text" readonly value='[easy_events_calendar layout="calendar"]' class="xylusec-shortcode-input" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1;" />
                                <button class="xylusec-btn-copy-shortcode button-primary" data-value='[easy_events_calendar layout="calendar"]' style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px;">Copy</button>
                            </div>
                        </div>
                    </div>

                    <!-- Grid Layout (Default) -->
                    <div class="xylusec-setting-row" onmouseover="changePrimaryPreview('grid');">
                        <div class="xylusec-inner-section-1">
                            <label><?php esc_html_e( 'Grid Layout', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_html_e( 'Display events in a clean grid layout (default).', 'xylus-events-calendar' ); ?></span>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                                <input type="text" readonly value="[easy_events_calendar]" class="xylusec-shortcode-input" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1;" />
                                <button class="xylusec-btn-copy-shortcode button-primary" data-value="[easy_events_calendar]" style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px;">Copy</button>
                            </div>
                        </div>
                    </div>

                    <!-- Row Layout -->
                    <div class="xylusec-setting-row" onmouseover="changePrimaryPreview('row');">
                        <div class="xylusec-inner-section-1">
                            <label><?php esc_html_e( 'Row Layout', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_html_e( 'Display events in a stacked row (list) layout.', 'xylus-events-calendar' ); ?></span>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                                <input type="text" readonly value='[easy_events_calendar layout="row"]' class="xylusec-shortcode-input" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1;" />
                                <button class="xylusec-btn-copy-shortcode button-primary" data-value='[easy_events_calendar layout="row"]' style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px;">Copy</button>
                            </div>
                        </div>
                    </div>

                    <!-- Staggered Layout -->
                    <div class="xylusec-setting-row" onmouseover="changePrimaryPreview('staggered');">
                        <div class="xylusec-inner-section-1">
                            <label><?php esc_html_e( 'Staggered Layout', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_html_e( 'Display events in a dynamic masonry/staggered layout.', 'xylus-events-calendar' ); ?></span>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                                <input type="text" readonly value='[easy_events_calendar layout="staggered"]' class="xylusec-shortcode-input" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1;" />
                                <button class="xylusec-btn-copy-shortcode button-primary" data-value='[easy_events_calendar layout="staggered"]' style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px;">Copy</button>
                            </div>
                        </div>
                    </div>

                    <!-- Slider Layout -->
                    <div class="xylusec-setting-row" onmouseover="changePrimaryPreview('slider');">
                        <div class="xylusec-inner-section-1">
                            <label><?php esc_html_e( 'Slider / Carousel View', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_html_e( 'Display events inside an interactive slider.', 'xylus-events-calendar' ); ?></span>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                                <input type="text" readonly value='[easy_events_calendar layout="slider"]' class="xylusec-shortcode-input" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1;" />
                                <button class="xylusec-btn-copy-shortcode button-primary" data-value='[easy_events_calendar layout="slider"]' style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px;">Copy</button>
                            </div>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="xylusec-setting-row" onmouseover="changePrimaryPreview('grid');">
                        <div class="xylusec-inner-section-1">
                            <label><?php esc_html_e( 'Filter by Category', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_html_e( 'Display events belonging to a specific category.', 'xylus-events-calendar' ); ?></span>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                                <input type="text" readonly value='[easy_events_calendar category="your-category-slug"]' class="xylusec-shortcode-input" style="background: #f1f5f9; border: 1px solid #cbd5e1; font-family: monospace; font-size: 13px; font-weight: 600; color: #334155; padding: 10px 14px; border-radius: 6px; flex-grow: 1;" />
                                <button class="xylusec-btn-copy-shortcode button-primary" data-value='[easy_events_calendar category="your-category-slug"]' style="background-color: var(--xec-primary-color, #005AE0); border: none; border-radius: 6px; font-weight: 600; color: #ffffff; cursor: pointer; height: 38px;">Copy</button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Preview Column -->
                <div style="flex: 0 0 350px;">
                    <div style="position: sticky; top: 40px; border: 1px solid #e2e8f0; border-radius: 8px; background: #ffffff; padding: 20px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);">
                        <h3 id="preview-primary-title" style="margin-top: 0; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; margin-bottom: 20px;">Grid Layout Preview</h3>
                        <div id="preview-primary-content" style="background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; padding: 16px;">
                            <!-- Filter Bar Mockup -->
                            <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                                <div style="width: 45%; height: 16px; background: #cbd5e1; border-radius: 4px;"></div>
                                <div style="width: 25%; height: 16px; background: var(--xec-primary-color, #005AE0); border-radius: 4px; opacity: 0.8;"></div>
                            </div>
                            <!-- Grid Layout Mockup -->
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                                <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow:hidden; border: 1px solid #f1f5f9;">
                                    <div style="height: 70px; background: #e2e8f0;"></div>
                                    <div style="padding: 10px;">
                                        <div style="width: 80%; height: 8px; background: #94a3b8; border-radius: 4px; margin-bottom: 8px;"></div>
                                        <div style="width: 50%; height: 6px; background: #cbd5e1; border-radius: 4px;"></div>
                                    </div>
                                </div>
                                <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow:hidden; border: 1px solid #f1f5f9;">
                                    <div style="height: 70px; background: #e2e8f0;"></div>
                                    <div style="padding: 10px;">
                                        <div style="width: 70%; height: 8px; background: #94a3b8; border-radius: 4px; margin-bottom: 8px;"></div>
                                        <div style="width: 60%; height: 6px; background: #cbd5e1; border-radius: 4px;"></div>
                                    </div>
                                </div>
                                <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow:hidden; border: 1px solid #f1f5f9;">
                                    <div style="height: 70px; background: #e2e8f0;"></div>
                                    <div style="padding: 10px;">
                                        <div style="width: 90%; height: 8px; background: #94a3b8; border-radius: 4px; margin-bottom: 8px;"></div>
                                        <div style="width: 40%; height: 6px; background: #cbd5e1; border-radius: 4px;"></div>
                                    </div>
                                </div>
                                <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow:hidden; border: 1px solid #f1f5f9;">
                                    <div style="height: 70px; background: #e2e8f0;"></div>
                                    <div style="padding: 10px;">
                                        <div style="width: 75%; height: 8px; background: #94a3b8; border-radius: 4px; margin-bottom: 8px;"></div>
                                        <div style="width: 55%; height: 6px; background: #cbd5e1; border-radius: 4px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p style="font-size: 11px; color: #94a3b8; text-align: center; margin-top: 16px; font-style: italic;">Hover over any row to visualize the layout.</p>
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
            <div style="display: flex; gap: 40px; flex-wrap: wrap;">
                <div class="xylusec-settings-wrapper" style="flex: 1; min-width: 300px;">

                    <!-- Row 1 -->
                    <div class="xylusec-setting-row" onmouseover="document.getElementById('preview-mini-title').innerText='Mini Calendar Preview';">
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
                    <div class="xylusec-setting-row" onmouseover="document.getElementById('preview-mini-title').innerText='Mini Category Filter';">
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
                    <div class="xylusec-setting-row" onmouseover="document.getElementById('preview-mini-title').innerText='Mini Without Images';">
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
                    <div class="xylusec-setting-row" onmouseover="document.getElementById('preview-mini-title').innerText='Mini Without Locations';">
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
                    <div class="xylusec-setting-row" onmouseover="document.getElementById('preview-mini-title').innerText='Mini Without Dates';">
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
                    <div class="xylusec-setting-row" onmouseover="document.getElementById('preview-mini-title').innerText='Mini With Organizers';">
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

                <!-- Preview Column -->
                <div style="flex: 0 0 350px;">
                    <div style="position: sticky; top: 40px; border: 1px solid #e2e8f0; border-radius: 8px; background: #ffffff; padding: 20px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);">
                        <h3 id="preview-mini-title" style="margin-top: 0; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; margin-bottom: 20px;">Mini Calendar Preview</h3>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <!-- Mini Calendar Top Mockup -->
                            <div style="background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; padding: 12px;">
                                <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
                                    <div style="width: 15%; height: 12px; background: #cbd5e1; border-radius: 4px;"></div>
                                    <div style="width: 30%; height: 12px; background: #94a3b8; border-radius: 4px;"></div>
                                    <div style="width: 15%; height: 12px; background: #cbd5e1; border-radius: 4px;"></div>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px;">
                                    <!-- Days -->
                                    <?php 
                                    $current_day = date('j');
                                    for($i=0; $i<35; $i++): 
                                        $date_num = ($i-2 > 0 && $i-2 <= 31) ? ($i-2) : '';
                                        // Let's set 13th as selected to avoid it constantly overlapping with dynamic 'today'
                                        $is_selected = ($date_num === 13);
                                        $is_today = ($date_num != '' && $date_num == $current_day);
                                        
                                        // Event dots on random dates
                                        $has_event = in_array($date_num, [5, 13, 20, 28]);
                                        
                                        $bg = $is_selected ? 'var(--xec-primary-color, #005AE0)' : 'transparent';
                                        $color = $is_selected ? '#ffffff' : '#64748b';
                                        $border = $is_today ? '1px solid var(--xec-primary-color, #005AE0)' : 'none';
                                        $opacity = ($i<3 || $i>33) ? '0.4' : '1';
                                        ?>
                                        <div style="aspect-ratio: 1; border-radius: 50%; background: <?php echo $bg; ?>; border: <?php echo $border; ?>; opacity: <?php echo $opacity; ?>; font-size: 9px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: <?php echo $color; ?>; font-weight: 600; position: relative;">
                                            <span><?php echo $date_num; ?></span>
                                            <?php if($has_event && $date_num && !$is_selected): ?>
                                                <div style="width: 3px; height: 3px; border-radius: 50%; background: var(--xec-primary-color, #005AE0); position: absolute; bottom: 2px;"></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <!-- Mini Event List Mockup -->
                            <div style="background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; padding: 12px; display: flex; flex-direction: column; gap: 8px;">
                                <div style="display: flex; gap: 10px; align-items: center; background: #fff; padding: 8px; border-radius: 6px; border: 1px solid #f1f5f9;">
                                    <div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 6px;"></div>
                                    <div style="flex: 1;">
                                        <div style="width: 80%; height: 6px; background: #94a3b8; border-radius: 4px; margin-bottom: 6px;"></div>
                                        <div style="width: 50%; height: 5px; background: #cbd5e1; border-radius: 4px;"></div>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 10px; align-items: center; background: #fff; padding: 8px; border-radius: 6px; border: 1px solid #f1f5f9;">
                                    <div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 6px;"></div>
                                    <div style="flex: 1;">
                                        <div style="width: 70%; height: 6px; background: #94a3b8; border-radius: 4px; margin-bottom: 6px;"></div>
                                        <div style="width: 60%; height: 5px; background: #cbd5e1; border-radius: 4px;"></div>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 10px; align-items: center; background: #fff; padding: 8px; border-radius: 6px; border: 1px solid #f1f5f9;">
                                    <div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 6px;"></div>
                                    <div style="flex: 1;">
                                        <div style="width: 90%; height: 6px; background: #94a3b8; border-radius: 4px; margin-bottom: 6px;"></div>
                                        <div style="width: 45%; height: 5px; background: #cbd5e1; border-radius: 4px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changePrimaryPreview(layout) {
    let title = 'Default Grid Layout Preview';
    let html = '';
    
    const filterMockup = `<div style="display:flex; justify-content:space-between; margin-bottom:16px;">
        <div style="width: 45%; height: 16px; background: #cbd5e1; border-radius: 4px;"></div>
        <div style="width: 25%; height: 16px; background: var(--xec-primary-color, #005AE0); border-radius: 4px; opacity: 0.8;"></div>
    </div>`;

    if (layout === 'grid') {
        title = 'Grid Layout Preview';
        html = filterMockup + `<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
            <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow:hidden; border: 1px solid #f1f5f9;">
                <div style="height: 70px; background: #e2e8f0;"></div>
                <div style="padding: 10px;">
                    <div style="width: 80%; height: 8px; background: #94a3b8; border-radius: 4px; margin-bottom: 8px;"></div>
                    <div style="width: 50%; height: 6px; background: #cbd5e1; border-radius: 4px;"></div>
                </div>
            </div>
            <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow:hidden; border: 1px solid #f1f5f9;">
                <div style="height: 70px; background: #e2e8f0;"></div>
                <div style="padding: 10px;">
                    <div style="width: 70%; height: 8px; background: #94a3b8; border-radius: 4px; margin-bottom: 8px;"></div>
                    <div style="width: 60%; height: 6px; background: #cbd5e1; border-radius: 4px;"></div>
                </div>
            </div>
            <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow:hidden; border: 1px solid #f1f5f9;">
                <div style="height: 70px; background: #e2e8f0;"></div>
                <div style="padding: 10px;">
                    <div style="width: 90%; height: 8px; background: #94a3b8; border-radius: 4px; margin-bottom: 8px;"></div>
                    <div style="width: 40%; height: 6px; background: #cbd5e1; border-radius: 4px;"></div>
                </div>
            </div>
            <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow:hidden; border: 1px solid #f1f5f9;">
                <div style="height: 70px; background: #e2e8f0;"></div>
                <div style="padding: 10px;">
                    <div style="width: 75%; height: 8px; background: #94a3b8; border-radius: 4px; margin-bottom: 8px;"></div>
                    <div style="width: 55%; height: 6px; background: #cbd5e1; border-radius: 4px;"></div>
                </div>
            </div>
        </div>`;
    } else if (layout === 'row') {
        title = 'Row Layout Preview';
        html = filterMockup + `<div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; background: #fff; border-radius: 8px; border: 1px solid #f1f5f9; padding: 10px; gap: 12px; align-items: center;">
                <div style="width: 60px; height: 60px; background: #e2e8f0; border-radius: 6px;"></div>
                <div style="flex: 1;">
                    <div style="width: 70%; height: 8px; background: #94a3b8; border-radius: 4px; margin-bottom: 8px;"></div>
                    <div style="width: 40%; height: 6px; background: #cbd5e1; border-radius: 4px;"></div>
                </div>
            </div>
            <div style="display: flex; background: #fff; border-radius: 8px; border: 1px solid #f1f5f9; padding: 10px; gap: 12px; align-items: center;">
                <div style="width: 60px; height: 60px; background: #e2e8f0; border-radius: 6px;"></div>
                <div style="flex: 1;">
                    <div style="width: 80%; height: 8px; background: #94a3b8; border-radius: 4px; margin-bottom: 8px;"></div>
                    <div style="width: 50%; height: 6px; background: #cbd5e1; border-radius: 4px;"></div>
                </div>
            </div>
            <div style="display: flex; background: #fff; border-radius: 8px; border: 1px solid #f1f5f9; padding: 10px; gap: 12px; align-items: center;">
                <div style="width: 60px; height: 60px; background: #e2e8f0; border-radius: 6px;"></div>
                <div style="flex: 1;">
                    <div style="width: 60%; height: 8px; background: #94a3b8; border-radius: 4px; margin-bottom: 8px;"></div>
                    <div style="width: 30%; height: 6px; background: #cbd5e1; border-radius: 4px;"></div>
                </div>
            </div>
        </div>`;
    } else if (layout === 'slider') {
        title = 'Slider Layout Preview';
        html = filterMockup + `<div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
            <div style="width: 24px; height: 24px; border-radius: 50%; background: #e2e8f0; display: flex; justify-content: center; align-items: center; color: #94a3b8; font-weight: bold; font-size: 10px;">&lt;</div>
            <div style="flex: 1; background: #fff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); overflow:hidden; border: 1px solid #f1f5f9;">
                <div style="height: 120px; background: #e2e8f0;"></div>
                <div style="padding: 16px;">
                    <div style="width: 80%; height: 10px; background: #94a3b8; border-radius: 4px; margin-bottom: 12px;"></div>
                    <div style="width: 50%; height: 8px; background: #cbd5e1; border-radius: 4px; margin-bottom: 6px;"></div>
                    <div style="width: 60%; height: 8px; background: #cbd5e1; border-radius: 4px;"></div>
                </div>
            </div>
            <div style="width: 24px; height: 24px; border-radius: 50%; background: #e2e8f0; display: flex; justify-content: center; align-items: center; color: #94a3b8; font-weight: bold; font-size: 10px;">&gt;</div>
        </div>`;
    } else if (layout === 'calendar') {
        title = 'Calendar Month View Preview';
        let calGrid = '';
        let currentDay = <?php echo date('j'); ?>;
        for(let i=0; i<35; i++) {
            let op = (i<3||i>33) ? '0.4' : '1';
            let txt = (i-2>0 && i-2<=31) ? (i-2) : '';
            let isToday = (txt !== '' && txt == currentDay);
            let boxBg = isToday ? 'background: rgba(255,220,40,.15);' : 'background: #fff;';
            
            // Random events logic for mockup
            let eventsHtml = '';
            if (i === 4) {
                eventsHtml = `<div style="height: 3px; background: #8b5cf6; border-radius: 2px; width: 60%;"></div>`;
            } else if (i === 10) {
                eventsHtml = `<div style="height: 3px; background: #3b82f6; border-radius: 2px; margin-bottom: 2px; width: 90%;"></div>
                              <div style="height: 3px; background: #ef4444; border-radius: 2px; width: 70%;"></div>`;
            } else if (i === 12) {
                eventsHtml = `<div style="height: 3px; background: #14b8a6; border-radius: 2px; width: 85%;"></div>`;
            } else if (i === 15) {
                eventsHtml = `<div style="height: 3px; background: #10b981; border-radius: 2px; margin-bottom: 2px; width: 80%;"></div>
                              <div style="height: 3px; background: #f43f5e; border-radius: 2px; width: 50%;"></div>`;
            } else if (i === 17) {
                eventsHtml = `<div style="height: 3px; background: #0ea5e9; border-radius: 2px; width: 100%;"></div>`;
            } else if (i === 22) {
                eventsHtml = `<div style="height: 3px; background: #f59e0b; border-radius: 2px; margin-bottom: 2px; width: 100%;"></div>
                              <div style="height: 3px; background: #8b5cf6; border-radius: 2px; width: 60%;"></div>`;
            } else if (i === 26) {
                eventsHtml = `<div style="height: 3px; background: #84cc16; border-radius: 2px; width: 75%;"></div>`;
            } else if (i === 28) {
                eventsHtml = `<div style="height: 3px; background: #ec4899; border-radius: 2px; width: 85%;"></div>`;
            } else if (i === 30) {
                eventsHtml = `<div style="height: 3px; background: #64748b; border-radius: 2px; margin-bottom: 2px; width: 60%;"></div>
                              <div style="height: 3px; background: #eab308; border-radius: 2px; width: 90%;"></div>`;
            }
            
            calGrid += `<div style="aspect-ratio: 1; border-radius: 6px; display:flex; flex-direction:column; padding: 3px; ${boxBg} box-shadow: 0 1px 2px rgba(0,0,0,0.02); border: 1px solid #f1f5f9; opacity: ${op};">
                <div style="font-size: 7px; font-weight: 700; display: flex; justify-content: flex-start; align-items: center; color: #64748b; margin-bottom: 3px; margin-left: 2px; margin-top: 2px;">${txt}</div>
                <div style="display: flex; flex-direction: column; width: 100%; align-items: flex-start; padding-left: 2px;">${eventsHtml}</div>
            </div>`;
        }
        
        html = filterMockup + `<div style="background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; padding: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <div style="width: 20px; height: 12px; background: #cbd5e1; border-radius: 3px;"></div>
                <div style="width: 90px; height: 14px; background: #94a3b8; border-radius: 4px;"></div>
                <div style="width: 20px; height: 12px; background: #cbd5e1; border-radius: 3px;"></div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; margin-bottom: 6px;">
                <div style="font-size: 7px; color: #94a3b8; text-align: center; font-weight: bold;">SUN</div>
                <div style="font-size: 7px; color: #94a3b8; text-align: center; font-weight: bold;">MON</div>
                <div style="font-size: 7px; color: #94a3b8; text-align: center; font-weight: bold;">TUE</div>
                <div style="font-size: 7px; color: #94a3b8; text-align: center; font-weight: bold;">WED</div>
                <div style="font-size: 7px; color: #94a3b8; text-align: center; font-weight: bold;">THU</div>
                <div style="font-size: 7px; color: #94a3b8; text-align: center; font-weight: bold;">FRI</div>
                <div style="font-size: 7px; color: #94a3b8; text-align: center; font-weight: bold;">SAT</div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px;">
                ${calGrid}
            </div>
        </div>`;
    } else if (layout === 'staggered') {
        title = 'Staggered (Masonry) Preview';
        html = filterMockup + `<div style="display: flex; gap: 12px;">
            <div style="flex: 1; display: flex; flex-direction: column; gap: 12px;">
                <div style="background: #fff; border-radius: 8px; border: 1px solid #f1f5f9; padding: 0; height: 160px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                    <div style="height: 90px; background: #e2e8f0;"></div>
                    <div style="padding: 10px;">
                        <div style="width: 80%; height: 8px; background: #94a3b8; border-radius: 4px; margin-bottom: 8px;"></div>
                        <div style="width: 50%; height: 6px; background: #cbd5e1; border-radius: 4px;"></div>
                    </div>
                </div>
                <div style="background: #fff; border-radius: 8px; border: 1px solid #f1f5f9; padding: 0; height: 110px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                    <div style="height: 60px; background: #e2e8f0;"></div>
                    <div style="padding: 10px;">
                        <div style="width: 70%; height: 8px; background: #94a3b8; border-radius: 4px;"></div>
                    </div>
                </div>
            </div>
            <div style="flex: 1; display: flex; flex-direction: column; gap: 12px;">
                <div style="background: #fff; border-radius: 8px; border: 1px solid #f1f5f9; padding: 0; height: 130px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                    <div style="height: 70px; background: #e2e8f0;"></div>
                    <div style="padding: 10px;">
                        <div style="width: 90%; height: 8px; background: #94a3b8; border-radius: 4px; margin-bottom: 8px;"></div>
                        <div style="width: 60%; height: 6px; background: #cbd5e1; border-radius: 4px;"></div>
                    </div>
                </div>
                <div style="background: #fff; border-radius: 8px; border: 1px solid #f1f5f9; padding: 0; height: 140px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                    <div style="height: 80px; background: #e2e8f0;"></div>
                    <div style="padding: 10px;">
                        <div style="width: 80%; height: 8px; background: #94a3b8; border-radius: 4px; margin-bottom: 8px;"></div>
                        <div style="width: 40%; height: 6px; background: #cbd5e1; border-radius: 4px;"></div>
                    </div>
                </div>
            </div>
        </div>`;
    }
    
    document.getElementById('preview-primary-title').innerText = title;
    document.getElementById('preview-primary-content').innerHTML = html;
}
</script>