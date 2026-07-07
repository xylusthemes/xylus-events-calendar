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

$xylusec_widget_options     = get_option( XYLUSEC_WIDGET_OPTIONS, true );

?>
<form method="post" action="">
    <div class="form-table">
        <div class="xylusec-card mt-2" style="border-radius: 8px; overflow: hidden; border-color: #e2e8f0;">
            <div class="header" style="background-color: #f8fafc; border-bottom-color: #e2e8f0;">
                <div class="text">
                    <div class="header-icon"></div>
                    <div class="header-title">
                        <span style="font-weight: 700; color: #0f172a; font-size: 15px;"><?php esc_attr_e( 'Widget Appearance Settings', 'xylus-events-calendar' ); ?></span>
                    </div>
                </div>
            </div>
            <div class="content" style="padding: 28px;">
                <div class="xylusec-settings-wrapper">
                    <!-- Appearance Options -->
                    <div class="xylusec-setting-row">
                        <div class="xylusec-inner-section-1">
                            <label><?php esc_attr_e( 'Widget Colors', 'xylus-events-calendar' ); ?></label>
                            <span class="row-desc"><?php esc_attr_e( 'Customize styling colors for widgets like list views and calendar sidebars.', 'xylus-events-calendar' ); ?></span>
                        </div>
                        <div class="xylusec-inner-section-2">
                            <div class="xylusec-color-pickers-group">
                                <?php
                                $xylusec_color_fields = [
                                    'xylusec_widget_background_color'       => [ 'label' => __( 'Widget Background', 'xylus-events-calendar' ), 'default' => '#ffffff' ],
                                    'xylusec_widget_hover_background_color' => [ 'label' => __( 'Widget Hover Background', 'xylus-events-calendar' ), 'default' => '#f0f4f8' ],
                                    'xylusec_widget_title_color'            => [ 'label' => __( 'Widget Title Color', 'xylus-events-calendar' ), 'default' => '#333333' ],
                                    'xylusec_widget_title_hover_color'      => [ 'label' => __( 'Widget Title Hover Color', 'xylus-events-calendar' ), 'default' => '#ff5a5f' ],
                                    'xylusec_widget_date_color'             => [ 'label' => __( 'Widget Date Color', 'xylus-events-calendar' ), 'default' => '#888888' ],
                                    'xylusec_widget_border_color'           => [ 'label' => __( 'Widget Border Color', 'xylus-events-calendar' ), 'default' => '#f0f4f8' ] 
                                ];

                                foreach ( $xylusec_color_fields as $xylusec_key => $xylusec_meta ) {
                                    $col_val = $xylusec_widget_options[$xylusec_key] ?? $xylusec_meta['default'];
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
            <?php wp_nonce_field( 'xylusec_so_widget_setting_form_nonce_action', 'xylusec_so_widget_setting_form_nonce' ); ?>
            <input type="hidden" name="xylusec_so_widget_action" value="xylusec_so_widget_settings" />
            <input type="hidden" name="xylusec_reset_defaults" id="xylusec_reset_defaults" value="0" />
            <input type="submit" class="xylusec_button" value="<?php esc_attr_e( 'Save Settings', 'xylus-events-calendar' ); ?>" />
            <input type="button" id="xylusec_reset_widget_colors" class="xylusec_button secondary" value="<?php esc_attr_e( 'Reset to Defaults', 'xylus-events-calendar' ); ?>" />
        </div>
    </div>
</form>

<!-- Custom Confirm Modal -->
<div id="xylusec-reset-confirm-modal" class="xylusec-confirm-overlay">
    <div class="xylusec-confirm-box">
        <div class="xylusec-confirm-icon">⚠️</div>
        <div class="xylusec-confirm-title"><?php esc_html_e( 'Reset Widget Colors?', 'xylus-events-calendar' ); ?></div>
        <div class="xylusec-confirm-message"><?php esc_html_e( 'This will reset all widget color settings back to their default values. This action cannot be undone.', 'xylus-events-calendar' ); ?></div>
        <div class="xylusec-confirm-actions">
            <button type="button" id="xylusec-reset-cancel" class="xylusec_button secondary"><?php esc_html_e( 'Cancel', 'xylus-events-calendar' ); ?></button>
            <button type="button" id="xylusec-reset-confirm" class="xylusec_button danger"><?php esc_html_e( 'Yes, Reset', 'xylus-events-calendar' ); ?></button>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    // Sync color input changes to corresponding color text values
    $('.xylusec-color-input').on('input', function() {
        $(this).siblings('.xylusec-color-val').text($(this).val().toUpperCase());
    });

    // Default colors mapping for widget options
    var defaultColors = {
        'xylusec_widget_background_color': '#ffffff',
        'xylusec_widget_hover_background_color': '#f0f4f8',
        'xylusec_widget_title_color': '#333333',
        'xylusec_widget_title_hover_color': '#ff5a5f',
        'xylusec_widget_date_color': '#888888',
        'xylusec_widget_border_color': '#f0f4f8'
    };

    var $modal = $('#xylusec-reset-confirm-modal');

    // Show custom confirm modal on Reset click
    $('#xylusec_reset_widget_colors').on('click', function(e) {
        e.preventDefault();
        $modal.addClass('is-visible');
    });

    // Cancel – close the modal
    $('#xylusec-reset-cancel').on('click', function() {
        $modal.removeClass('is-visible');
    });

    // Close modal on overlay click (outside the box)
    $modal.on('click', function(e) {
        if ($(e.target).hasClass('xylusec-confirm-overlay')) {
            $modal.removeClass('is-visible');
        }
    });

    // Confirm – reset colors and submit
    $('#xylusec-reset-confirm').on('click', function() {
        $.each(defaultColors, function(key, val) {
            var $input = $('#' + key);
            $input.val(val);
            $input.siblings('.xylusec-color-val').text(val.toUpperCase());
        });
        $modal.removeClass('is-visible');
        // Set reset flag so PHP shows correct message
        $('#xylusec_reset_defaults').val('1');
        // Auto submit the form to save defaults
        $('form').first().submit();
    });
});
</script>