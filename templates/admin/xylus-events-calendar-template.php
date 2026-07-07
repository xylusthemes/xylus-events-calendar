<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

global $xylusec_events_calendar;
$xylusec_options = get_option(XYLUSEC_OPTIONS, true);

// Slider arrows
$xylusec_arrowbg_color = isset($xylusec_options['xylusec_button_color']) ? esc_attr($xylusec_options['xylusec_button_color']) : '#000';
$xylusec_text_color = isset($xylusec_options['xylusec_text_color']) ? esc_attr($xylusec_options['xylusec_text_color']) : '#fff';
$xylusec_title_color = isset($xylusec_options['xylusec_event_title_color']) ? esc_attr($xylusec_options['xylusec_event_title_color']) : '#60606e';
$xylusec_is_header_hide = isset($xylusec_options['xylusec_hide_header']) ? $xylusec_options['xylusec_hide_header'] : 'no';
?>
<div id="xylusec-calendar-container">
    <div class="xylusec-custom-buttons-container" <?php echo esc_attr($xylusec_is_header_hide === 'yes' ? 'style=display:none;' : ''); ?>>
        <div class="xylusec-custom-buttons-container-first-child">
            <input id="xylusec-search" type="search" placeholder="Search Events..."
                style="padding: 7px;border:1px solid #ccc;border-radius:5px;width: 100%;">
            <button id="xylusec-search-events" type="button" class="xylusec_load_more_button"
                style="padding: 2px 10px;"><?php echo esc_attr('Search', 'xylus-events-calendar'); ?></button>
        </div>
        <div style="display: flex;gap: 0;border-radius: 5px;">
            <button type="button" title="Month View"
                class="fc-button fc-button-primary fc-button-month fc-active xylusec-c-button"><svg
                    xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 20 20" height="18px"
                    viewBox="0 0 20 20" width="18px" fill="#fff">
                    <g>
                        <rect fill="none" height="20" width="20" x="0"></rect>
                    </g>
                    <g>
                        <path
                            d="M15.5,4H14V2h-1.5v2h-5V2H6v2H4.5C3.67,4,3,4.68,3,5.5v11C3,17.32,3.67,18,4.5,18h11c0.83,0,1.5-0.68,1.5-1.5v-11 C17,4.68,16.33,4,15.5,4z M15.5,16.5h-11V9h11V16.5z M15.5,7.5h-11v-2h11V7.5z M7.5,12H6v-1.5h1.5V12z M10.75,12h-1.5v-1.5h1.5V12z M14,12h-1.5v-1.5H14V12z M7.5,15H6v-1.5h1.5V15z M10.75,15h-1.5v-1.5h1.5V15z M14,15h-1.5v-1.5H14V15z">
                        </path>
                    </g>
                </svg></button>
            <button type="button" title="Grid View"
                class="fc-button fc-button-primary fc-button-grid xylusec-c-button "><svg
                    xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="18px"
                    viewBox="0 0 24 24" width="18px" fill="#fff">
                    <g>
                        <rect fill="none" height="24" width="24"></rect>
                    </g>
                    <g>
                        <g>
                            <g>
                                <path
                                    d="M3,3v8h8V3H3z M9,9H5V5h4V9z M3,13v8h8v-8H3z M9,19H5v-4h4V19z M13,3v8h8V3H13z M19,9h-4V5h4V9z M13,13v8h8v-8H13z M19,19h-4v-4h4V19z">
                                </path>
                            </g>
                        </g>
                    </g>
                </svg></button>
            <button type="button" title="Row View"
                class="fc-button fc-button-primary fc-button-row xylusec-c-button"><svg
                    xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 20 20" height="18px"
                    viewBox="0 0 20 20" width="18px" fill="#fff">
                    <g>
                        <rect fill="none" height="20" width="20" y="0"></rect>
                    </g>
                    <g>
                        <g>
                            <path
                                d="M15.5,3h-11C3.67,3,3,3.67,3,4.5v3C3,8.33,3.67,9,4.5,9h11C16.33,9,17,8.33,17,7.5v-3C17,3.67,16.33,3,15.5,3z M15.5,7.5 h-11v-3h11V7.5z">
                            </path>
                            <path
                                d="M15.5,11h-11C3.67,11,3,11.67,3,12.5v3C3,16.33,3.67,17,4.5,17h11c0.83,0,1.5-0.67,1.5-1.5v-3C17,11.67,16.33,11,15.5,11z M15.5,15.5h-11v-3h11V15.5z">
                            </path>
                        </g>
                    </g>
                </svg></button>
            <button type="button" title="Staggered View"
                class="fc-button fc-button-primary fc-button-staggered xylusec-c-button"><svg
                    xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px" fill="#fff">
                    <path d="M0 0h24v24H0V0z" fill="none"></path>
                    <path
                        d="M19 5v2h-4V5h4M9 5v6H5V5h4m10 8v6h-4v-6h4M9 17v2H5v-2h4M21 3h-8v6h8V3zM11 3H3v10h8V3zm10 8h-8v10h8V11zm-10 4H3v6h8v-6z">
                    </path>
                </svg></button>
            <button type="button" title="Slider View"
                class="fc-button fc-button-primary fc-button-slider xylusec-c-button"><svg
                    xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px" fill="#000000">
                    <path d="M0 0h24v24H0V0z" fill="none"></path>
                    <path
                        d="M20 6.54v10.91c-2.6-.77-5.28-1.16-8-1.16s-5.4.39-8 1.16V6.54c2.6.77 5.28 1.16 8 1.16 2.72.01 5.4-.38 8-1.16M21.43 4c-.1 0-.2.02-.31.06C18.18 5.16 15.09 5.7 12 5.7s-6.18-.55-9.12-1.64C2.77 4.02 2.66 4 2.57 4c-.34 0-.57.23-.57.63v14.75c0 .39.23.62.57.62.1 0 .2-.02.31-.06 2.94-1.1 6.03-1.64 9.12-1.64s6.18.55 9.12 1.64c.11.04.21.06.31.06.33 0 .57-.23.57-.63V4.63c0-.4-.24-.63-.57-.63z">
                    </path>
                </svg></button>
        </div>
    </div>
    <?php
    $xylusec_show_filters = isset($xylusec_options['xylusec_show_filters']) ? $xylusec_options['xylusec_show_filters'] : 'no';
    if ($xylusec_show_filters === 'yes'):
        $show_cat = ($xylusec_options['xylusec_filter_show_category'] ?? 'yes') === 'yes';
        $show_tag = ($xylusec_options['xylusec_filter_show_tag'] ?? 'yes') === 'yes';
        $show_ven = ($xylusec_options['xylusec_filter_show_venue'] ?? 'yes') === 'yes';
        $show_org = ($xylusec_options['xylusec_filter_show_organizer'] ?? 'yes') === 'yes';
        $show_col = ($xylusec_options['xylusec_filter_show_collection'] ?? 'yes') === 'yes';
        $show_day = ($xylusec_options['xylusec_filter_show_day'] ?? 'yes') === 'yes';
        $show_time = ($xylusec_options['xylusec_filter_show_time'] ?? 'yes') === 'yes';
        $show_df = ($xylusec_options['xylusec_filter_show_date_from'] ?? 'yes') === 'yes';
        $show_dt = ($xylusec_options['xylusec_filter_show_date_to'] ?? 'yes') === 'yes';
        ?>
        <div class="xylusec-filters-container">
            <div class="xylusec-filters-pills">
                <?php
                // 1. Event Category
                if ($show_cat && taxonomy_exists('eec_category')) {
                    $categories = get_terms(array('taxonomy' => 'eec_category', 'hide_empty' => true));
                    if (!is_wp_error($categories) && !empty($categories)) {
                        ?>
                        <div class="xylusec-filter-pill-wrap" id="xylusec-filter-category">
                            <button type="button" class="xylusec-filter-pill-btn">
                                <span class="pill-label"><?php esc_html_e('Event Category', 'xylus-events-calendar'); ?></span>
                                <svg class="pill-arrow" viewBox="0 0 24 24" width="16" height="16">
                                    <path d="M7 10l5 5 5-5H7z" />
                                </svg>
                            </button>
                            <div class="xylusec-filter-dropdown" style="display:none;">
                                <div class="xylusec-dropdown-search-wrap">
                                    <input type="text"
                                        placeholder="<?php esc_attr_e('Search Category...', 'xylus-events-calendar'); ?>"
                                        class="xylusec-dropdown-search">
                                </div>
                                <div class="xylusec-dropdown-options-list">
                                    <?php foreach ($categories as $cat): ?>
                                        <label class="xylusec-dropdown-option"><input type="checkbox" name="category"
                                                value="<?php echo esc_attr($cat->slug); ?>"
                                                data-label="<?php echo esc_attr($cat->name); ?>">
                                            <span><?php echo esc_html($cat->name); ?></span></label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }

                // 2. Tag
                if ($show_tag && taxonomy_exists('eec_tag')) {
                    $tags = get_terms(array('taxonomy' => 'eec_tag', 'hide_empty' => true));
                    if (!is_wp_error($tags) && !empty($tags)) {
                        ?>
                        <div class="xylusec-filter-pill-wrap" id="xylusec-filter-tag">
                            <button type="button" class="xylusec-filter-pill-btn">
                                <span class="pill-label"><?php esc_html_e('Tag', 'xylus-events-calendar'); ?></span>
                                <svg class="pill-arrow" viewBox="0 0 24 24" width="16" height="16">
                                    <path d="M7 10l5 5 5-5H7z" />
                                </svg>
                            </button>
                            <div class="xylusec-filter-dropdown" style="display:none;">
                                <div class="xylusec-dropdown-search-wrap">
                                    <input type="text" placeholder="<?php esc_attr_e('Search Tag...', 'xylus-events-calendar'); ?>"
                                        class="xylusec-dropdown-search">
                                </div>
                                <div class="xylusec-dropdown-options-list">
                                    <?php foreach ($tags as $tag_term): ?>
                                        <label class="xylusec-dropdown-option"><input type="checkbox" name="tag"
                                                value="<?php echo esc_attr($tag_term->slug); ?>"
                                                data-label="<?php echo esc_attr($tag_term->name); ?>">
                                            <span><?php echo esc_html($tag_term->name); ?></span></label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }

                // 3. Venue
                if ($show_ven && taxonomy_exists('eec_venue')) {
                    $venues = get_terms(array('taxonomy' => 'eec_venue', 'hide_empty' => true));
                    if (!is_wp_error($venues) && !empty($venues)) {
                        ?>
                        <div class="xylusec-filter-pill-wrap" id="xylusec-filter-venue">
                            <button type="button" class="xylusec-filter-pill-btn">
                                <span class="pill-label"><?php esc_html_e('Venue', 'xylus-events-calendar'); ?></span>
                                <svg class="pill-arrow" viewBox="0 0 24 24" width="16" height="16">
                                    <path d="M7 10l5 5 5-5H7z" />
                                </svg>
                            </button>
                            <div class="xylusec-filter-dropdown" style="display:none;">
                                <div class="xylusec-dropdown-search-wrap">
                                    <input type="text"
                                        placeholder="<?php esc_attr_e('Search Venue...', 'xylus-events-calendar'); ?>"
                                        class="xylusec-dropdown-search">
                                </div>
                                <div class="xylusec-dropdown-options-list">
                                    <?php foreach ($venues as $ven): ?>
                                        <label class="xylusec-dropdown-option"><input type="checkbox" name="venue"
                                                value="<?php echo esc_attr($ven->slug); ?>"
                                                data-label="<?php echo esc_attr($ven->name); ?>">
                                            <span><?php echo esc_html($ven->name); ?></span></label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }

                // 4. Organizer
                if ($show_org && taxonomy_exists('eec_organizer')) {
                    $organizers = get_terms(array('taxonomy' => 'eec_organizer', 'hide_empty' => true));
                    if (!is_wp_error($organizers) && !empty($organizers)) {
                        ?>
                        <div class="xylusec-filter-pill-wrap" id="xylusec-filter-organizer">
                            <button type="button" class="xylusec-filter-pill-btn">
                                <span class="pill-label"><?php esc_html_e('Organizer', 'xylus-events-calendar'); ?></span>
                                <svg class="pill-arrow" viewBox="0 0 24 24" width="16" height="16">
                                    <path d="M7 10l5 5 5-5H7z" />
                                </svg>
                            </button>
                            <div class="xylusec-filter-dropdown" style="display:none;">
                                <div class="xylusec-dropdown-search-wrap">
                                    <input type="text"
                                        placeholder="<?php esc_attr_e('Search Organizer...', 'xylus-events-calendar'); ?>"
                                        class="xylusec-dropdown-search">
                                </div>
                                <div class="xylusec-dropdown-options-list">
                                    <?php foreach ($organizers as $org): ?>
                                        <label class="xylusec-dropdown-option"><input type="checkbox" name="organizer"
                                                value="<?php echo esc_attr($org->slug); ?>"
                                                data-label="<?php echo esc_attr($org->name); ?>">
                                            <span><?php echo esc_html($org->name); ?></span></label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }

                // 5. Collection
                if ($show_col && taxonomy_exists('eec_collection')) {
                    $collections = get_terms(array('taxonomy' => 'eec_collection', 'hide_empty' => true));
                    if (!is_wp_error($collections) && !empty($collections)) {
                        ?>
                        <div class="xylusec-filter-pill-wrap" id="xylusec-filter-collection">
                            <button type="button" class="xylusec-filter-pill-btn">
                                <span class="pill-label"><?php esc_html_e('Collection', 'xylus-events-calendar'); ?></span>
                                <svg class="pill-arrow" viewBox="0 0 24 24" width="16" height="16">
                                    <path d="M7 10l5 5 5-5H7z" />
                                </svg>
                            </button>
                            <div class="xylusec-filter-dropdown" style="display:none;">
                                <div class="xylusec-dropdown-search-wrap">
                                    <input type="text"
                                        placeholder="<?php esc_attr_e('Search Collection...', 'xylus-events-calendar'); ?>"
                                        class="xylusec-dropdown-search">
                                </div>
                                <div class="xylusec-dropdown-options-list">
                                    <?php foreach ($collections as $col): ?>
                                        <label class="xylusec-dropdown-option"><input type="checkbox" name="collection"
                                                value="<?php echo esc_attr($col->slug); ?>"
                                                data-label="<?php echo esc_attr($col->name); ?>">
                                            <span><?php echo esc_html($col->name); ?></span></label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>

                <!-- Day Dropdown Pill -->
                <?php if ($show_day): ?>
                    <div class="xylusec-filter-pill-wrap" id="xylusec-filter-day">
                        <button type="button" class="xylusec-filter-pill-btn">
                            <span class="pill-label"><?php esc_html_e('Day', 'xylus-events-calendar'); ?></span>
                            <svg class="pill-arrow" viewBox="0 0 24 24" width="16" height="16">
                                <path d="M7 10l5 5 5-5H7z" />
                            </svg>
                        </button>
                        <div class="xylusec-filter-dropdown" style="display:none;">
                            <div class="xylusec-dropdown-options-list">
                                <label class="xylusec-dropdown-option"><input type="checkbox" name="day" value="monday"
                                        data-label="<?php esc_attr_e('Monday', 'xylus-events-calendar'); ?>">
                                    <span><?php esc_html_e('Monday', 'xylus-events-calendar'); ?></span></label>
                                <label class="xylusec-dropdown-option"><input type="checkbox" name="day" value="tuesday"
                                        data-label="<?php esc_attr_e('Tuesday', 'xylus-events-calendar'); ?>">
                                    <span><?php esc_html_e('Tuesday', 'xylus-events-calendar'); ?></span></label>
                                <label class="xylusec-dropdown-option"><input type="checkbox" name="day" value="wednesday"
                                        data-label="<?php esc_attr_e('Wednesday', 'xylus-events-calendar'); ?>">
                                    <span><?php esc_html_e('Wednesday', 'xylus-events-calendar'); ?></span></label>
                                <label class="xylusec-dropdown-option"><input type="checkbox" name="day" value="thursday"
                                        data-label="<?php esc_attr_e('Thursday', 'xylus-events-calendar'); ?>">
                                    <span><?php esc_html_e('Thursday', 'xylus-events-calendar'); ?></span></label>
                                <label class="xylusec-dropdown-option"><input type="checkbox" name="day" value="friday"
                                        data-label="<?php esc_attr_e('Friday', 'xylus-events-calendar'); ?>">
                                    <span><?php esc_html_e('Friday', 'xylus-events-calendar'); ?></span></label>
                                <label class="xylusec-dropdown-option"><input type="checkbox" name="day" value="saturday"
                                        data-label="<?php esc_attr_e('Saturday', 'xylus-events-calendar'); ?>">
                                    <span><?php esc_html_e('Saturday', 'xylus-events-calendar'); ?></span></label>
                                <label class="xylusec-dropdown-option"><input type="checkbox" name="day" value="sunday"
                                        data-label="<?php esc_attr_e('Sunday', 'xylus-events-calendar'); ?>">
                                    <span><?php esc_html_e('Sunday', 'xylus-events-calendar'); ?></span></label>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Time Dropdown Pill -->
                <?php if ($show_time): ?>
                    <div class="xylusec-filter-pill-wrap" id="xylusec-filter-time">
                        <button type="button" class="xylusec-filter-pill-btn">
                            <span class="pill-label"><?php esc_html_e('Time', 'xylus-events-calendar'); ?></span>
                            <svg class="pill-arrow" viewBox="0 0 24 24" width="16" height="16">
                                <path d="M7 10l5 5 5-5H7z" />
                            </svg>
                        </button>
                        <div class="xylusec-filter-dropdown" style="display:none;">
                            <div class="xylusec-dropdown-options-list">
                                <label class="xylusec-dropdown-option"><input type="checkbox" name="time" value="morning"
                                        data-label="<?php esc_attr_e('Morning', 'xylus-events-calendar'); ?>">
                                    <span><?php esc_html_e('Morning (6am - 12pm)', 'xylus-events-calendar'); ?></span></label>
                                <label class="xylusec-dropdown-option"><input type="checkbox" name="time" value="afternoon"
                                        data-label="<?php esc_attr_e('Afternoon', 'xylus-events-calendar'); ?>">
                                    <span><?php esc_html_e('Afternoon (12pm - 5pm)', 'xylus-events-calendar'); ?></span></label>
                                <label class="xylusec-dropdown-option"><input type="checkbox" name="time" value="evening"
                                        data-label="<?php esc_attr_e('Evening', 'xylus-events-calendar'); ?>">
                                    <span><?php esc_html_e('Evening (5pm - 9pm)', 'xylus-events-calendar'); ?></span></label>
                                <label class="xylusec-dropdown-option"><input type="checkbox" name="time" value="night"
                                        data-label="<?php esc_attr_e('Night', 'xylus-events-calendar'); ?>">
                                    <span><?php esc_html_e('Night (9pm - 6am)', 'xylus-events-calendar'); ?></span></label>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Date From Pill -->
                <?php if ($show_df): ?>
                    <div class="xylusec-filter-pill-wrap" id="xylusec-filter-date-from">
                        <button type="button" class="xylusec-filter-pill-btn">
                            <span class="pill-label"><?php esc_html_e('Date From', 'xylus-events-calendar'); ?></span>
                            <svg class="pill-arrow" viewBox="0 0 24 24" width="16" height="16">
                                <path d="M7 10l5 5 5-5H7z" />
                            </svg>
                        </button>
                        <div class="xylusec-filter-dropdown" style="display:none; padding: 12px; width: 220px;">
                            <input type="date" name="date_from" class="xylusec-date-input"
                                style="width: 100%; padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Date To Pill -->
                <?php if ($show_dt): ?>
                    <div class="xylusec-filter-pill-wrap" id="xylusec-filter-date-to">
                        <button type="button" class="xylusec-filter-pill-btn">
                            <span class="pill-label"><?php esc_html_e('Date To', 'xylus-events-calendar'); ?></span>
                            <svg class="pill-arrow" viewBox="0 0 24 24" width="16" height="16">
                                <path d="M7 10l5 5 5-5H7z" />
                            </svg>
                        </button>
                        <div class="xylusec-filter-dropdown" style="display:none; padding: 12px; width: 220px;">
                            <input type="date" name="date_to" class="xylusec-date-input"
                                style="width: 100%; padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Clear Button -->
                <button type="button"
                    class="xylusec-filter-clear-btn"><?php esc_html_e('CLEAR', 'xylus-events-calendar'); ?></button>
            </div>
        </div>
    <?php endif; ?>
    <div id="xylusec-calendar"></div>
    <div id="xylusec-grid-view-container" class="custom-grid-view" style="display: none;">
        <?php echo wp_kses_post( $xylusec_events_calendar->common->xylusec_get_past_header_html() ); ?>
        <div class="xylusec-inner-main-container">
            <div class="xylusec-event-grid-container"></div>
            <div class="xylusec-load-more-wrap">
                <?php echo wp_kses_post($xylusec_events_calendar->common->xylusec_get_xylusec_load_more_button($xylusec_options, 'load-more-events')); ?>
                <div class="xylusec-spinner-main">
                    <span class="xylusec-load-spinner xylusec-spinner" style="display:none;"></span>
                </div>
            </div>
        </div>
        <div class="xylusec-no-events"
            style="display: none;padding:15px;text-align:center;color:<?php echo esc_attr($xylusec_title_color); ?>;border:1px solid <?php echo esc_attr($xylusec_arrowbg_color); ?>;border-radius:5px;margin-top:15px;">
            <?php echo esc_attr('Uh-oh! No events found nearby. Change the filters or swing by later to see what’s new!', 'xylus-events-calendar'); ?>
        </div>
    </div>

    <div id="xylusec-row-view-container" class="custom-row-view" style="display: none;">
        <?php echo wp_kses_post( $xylusec_events_calendar->common->xylusec_get_past_header_html() ); ?>
        <div class="xylusec-inner-main-container">
            <div class="xylusec-event-row-container"></div>
            <div class="xylusec-load-more-wrap">
                <?php echo wp_kses_post($xylusec_events_calendar->common->xylusec_get_xylusec_load_more_button($xylusec_options, 'load-more-row-events')); ?>
                <div class="xylusec-spinner-main">
                    <span class="xylusec-load-spinner xylusec-spinner" style="display:none;"></span>
                </div>
            </div>
        </div>
        <div class="xylusec-no-events"
            style="display: none;padding:15px;text-align:center;color:<?php echo esc_attr($xylusec_title_color); ?>;border:1px solid <?php echo esc_attr($xylusec_arrowbg_color); ?>;border-radius:5px;margin-top:15px;">
            <?php echo esc_attr('Uh-oh! No events found nearby. Change the filters or swing by later to see what’s new!', 'xylus-events-calendar'); ?>
        </div>
    </div>

    <div id="xylusec-grid-staggered-view-container" class="xylusec-custom-grid-staggered-view" style="display: none;">
        <?php echo wp_kses_post( $xylusec_events_calendar->common->xylusec_get_past_header_html() ); ?>
        <div class="xylusec-inner-main-container">
            <div class="xylusec-event-grid-staggered-container"></div>
            <div class="xylusec-load-more-wrap">
                <?php echo wp_kses_post($xylusec_events_calendar->common->xylusec_get_xylusec_load_more_button($xylusec_options, 'load-more-grid-staggered-events')); ?>
                <div class="xylusec-spinner-main">
                    <span class="xylusec-load-spinner xylusec-spinner" style="display:none;"></span>
                </div>
            </div>
        </div>
        <div class="xylusec-no-events"
            style="display: none;padding:15px;text-align:center;color:<?php echo esc_attr($xylusec_title_color); ?>;border:1px solid <?php echo esc_attr($xylusec_arrowbg_color); ?>;border-radius:5px;margin-top:15px;">
            <?php echo esc_attr('Uh-oh! No events found nearby. Change the filters or swing by later to see what’s new!', 'xylus-events-calendar'); ?>
        </div>
    </div>

    <div id="xylusec-slider-past-header" style="display: none;">
        <?php echo wp_kses_post( $xylusec_events_calendar->common->xylusec_get_past_header_html() ); ?>
    </div>
    <div id="xylusec-slider-view-container" class="xylusec-slider-view" style="display: none;">
        <div class="xylusec-inner-main-container">
            <div class="xylusec-event-slider-container xylusec-slider-slider" style="display: block;"></div>
            <div class="xylusec-load-more-wrap">
                <div class="xylusec-spinner-main">
                    <span class="xylusec-load-spinner xylusec-spinner" style="display:none;"></span>
                </div>
            </div>
            <style>
                .xylusec-slider-arrow {
                    background:
                        <?php echo esc_attr($xylusec_arrowbg_color); ?>
                    ;
                    color:
                        <?php echo esc_attr($xylusec_text_color); ?>
                    ;
                }
            </style>
        </div>
        <div class="xylusec-no-events"
            style="display: none;padding:15px;text-align:center;color:<?php echo esc_attr($xylusec_title_color); ?>;border:1px solid <?php echo esc_attr($xylusec_arrowbg_color); ?>;border-radius:5px;margin-top:15px;">
            <?php echo esc_attr('Uh-oh! No events found nearby. Change the filters or swing by later to see what’s new!', 'xylus-events-calendar'); ?>
        </div>
    </div>
</div>