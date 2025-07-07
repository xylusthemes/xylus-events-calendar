<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

global $xylusec_events_calendar;
$xylusec_options     = get_option( XYLUSEC_OPTIONS, true );
?>
<div id="xylusec-calendar-container">
    <div class="xylusec-custom-buttons-container">
        <div class="xylusec-custom-buttons-container-first-child">
            <input id="xylusec-search" type="search" placeholder="Search Events..." style="padding: 7px;border:1px solid #ccc;border-radius:5px;width: 100%;">
            <button id="xylusec-search-events" type="button" class="xylusec_load_more_button" style="padding: 2px 10px;"><?php echo esc_attr( 'Search', 'xylus-events-calendar' ); ?></button>
        </div>
        <div style="display: flex;gap: 0;border-radius: 5px;">
            <button type="button" title="Month View" class="fc-button fc-button-primary fc-button-month fc-active xylusec-c-button"><svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 20 20" height="18px" viewBox="0 0 20 20" width="18px" fill="#fff" ><g><rect fill="none" height="20" width="20" x="0"></rect></g><g><path d="M15.5,4H14V2h-1.5v2h-5V2H6v2H4.5C3.67,4,3,4.68,3,5.5v11C3,17.32,3.67,18,4.5,18h11c0.83,0,1.5-0.68,1.5-1.5v-11 C17,4.68,16.33,4,15.5,4z M15.5,16.5h-11V9h11V16.5z M15.5,7.5h-11v-2h11V7.5z M7.5,12H6v-1.5h1.5V12z M10.75,12h-1.5v-1.5h1.5V12z M14,12h-1.5v-1.5H14V12z M7.5,15H6v-1.5h1.5V15z M10.75,15h-1.5v-1.5h1.5V15z M14,15h-1.5v-1.5H14V15z"></path></g></svg></button>
            <button type="button" title="Grid View" class="fc-button fc-button-primary fc-button-grid xylusec-c-button "><svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="18px" viewBox="0 0 24 24" width="18px" fill="#fff"><g><rect fill="none" height="24" width="24"></rect></g><g><g><g><path d="M3,3v8h8V3H3z M9,9H5V5h4V9z M3,13v8h8v-8H3z M9,19H5v-4h4V19z M13,3v8h8V3H13z M19,9h-4V5h4V9z M13,13v8h8v-8H13z M19,19h-4v-4h4V19z"></path></g></g></g></svg></button>
            <button type="button" title="Row View" class="fc-button fc-button-primary fc-button-row xylusec-c-button"><svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 20 20" height="18px" viewBox="0 0 20 20" width="18px" fill="#fff" ><g><rect fill="none" height="20" width="20" y="0"></rect></g><g><g><path d="M15.5,3h-11C3.67,3,3,3.67,3,4.5v3C3,8.33,3.67,9,4.5,9h11C16.33,9,17,8.33,17,7.5v-3C17,3.67,16.33,3,15.5,3z M15.5,7.5 h-11v-3h11V7.5z"></path><path d="M15.5,11h-11C3.67,11,3,11.67,3,12.5v3C3,16.33,3.67,17,4.5,17h11c0.83,0,1.5-0.67,1.5-1.5v-3C17,11.67,16.33,11,15.5,11z M15.5,15.5h-11v-3h11V15.5z"></path></g></g></svg></button>
            <button type="button" title="Staggered View" class="fc-button fc-button-primary fc-button-staggered xylusec-c-button"><svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px" fill="#fff"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M19 5v2h-4V5h4M9 5v6H5V5h4m10 8v6h-4v-6h4M9 17v2H5v-2h4M21 3h-8v6h8V3zM11 3H3v10h8V3zm10 8h-8v10h8V11zm-10 4H3v6h8v-6z"></path></svg></button>
        </div>
    </div>
    <div id="xylusec-calendar"></div>
    <div id="xylusec-grid-view-container" class="custom-grid-view" style="display: none;">
        <div class="xylusec-event-grid-container"></div>
        <div class="xylusec-load-more-wrap">
            <?php echo wp_kses_post( $xylusec_events_calendar->common->xylusec_get_xylusec_load_more_button( $xylusec_options, 'load-more-events' ) ); ?>
            <div class="xylusec-spinner-main" >
                <span class="xylusec-load-spinner xylusec-spinner" style="display:none;"></span>
            </div>
        </div>
    </div>

    <div id="xylusec-row-view-container" class="custom-row-view" style="display: none;">
        <div class="xylusec-event-row-container"></div>
        <div class="xylusec-load-more-wrap">
            <?php echo wp_kses_post( $xylusec_events_calendar->common->xylusec_get_xylusec_load_more_button( $xylusec_options, 'load-more-row-events' ) ); ?>
            <div class="xylusec-spinner-main" >
                <span class="xylusec-load-spinner xylusec-spinner" style="display:none;"></span>
            </div>
        </div>
    </div>

    <div id="xylusec-grid-staggered-view-container" class="xylusec-custom-grid-staggered-view" style="display: none;">
        <div class="xylusec-event-grid-staggered-container"></div>
        <div class="xylusec-load-more-wrap">
            <?php echo wp_kses_post( $xylusec_events_calendar->common->xylusec_get_xylusec_load_more_button( $xylusec_options, 'load-more-grid-staggered-events' ) ); ?>
            <div class="xylusec-spinner-main" >
                <span class="xylusec-load-spinner xylusec-spinner" style="display:none;"></span>
            </div>
        </div>
    </div>
</div>