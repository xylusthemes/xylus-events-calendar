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

$xylusec_widget_options     = get_option( XYLUSEC_WIDGET_OPTIONS, true );

?>
<form method="post" action="">
    <div class="form-table">
        <div class="xylusec-card mt-2">
            <div class="header">
                <div class="text">
                    <div class="header-icon"></div>
                    <div class="header-title">
                        <span><?php esc_attr_e( 'Widget Settings', 'xylus-events-calendar' ); ?></span>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="xylusec-settings-wrapper">
                    <!-- Appearance Options -->
                    <?php
                        $color_fields = [
                            'xylusec_widget_background_color'       => '#ffffff',
                            'xylusec_widget_hover_background_color' => '#f0f4f8',
                            'xylusec_widget_title_color'            => '#333333',
                            'xylusec_widget_title_hover_color'      => '#ff5a5f',
                            'xylusec_widget_date_color'             => '#888888',
                            'xylusec_widget_border_color'           => '#f0f4f8' 
                        ];

                        foreach ( $color_fields as $key => $default ) {
                            ?>
                            <div class="xylusec-setting-row">
                                <div class="xylusec-inner-section-1"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( ucwords( str_replace( '_', ' ', str_replace( 'xylusec_', '', $key ) ) ) ); ?></label></div>
                                <div class="xylusec-inner-section-2">
                                    <input type="color" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $xylusec_widget_options[$key] ?? $default ); ?>">
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
            <?php wp_nonce_field( 'xylusec_so_widget_setting_form_nonce_action', 'xylusec_so_widget_setting_form_nonce' ); ?>
            <input type="hidden" name="xylusec_so_widget_action" value="xylusec_so_widget_settings" />
            <input type="submit"class="xylusec_button" style="display: flex;align-items: center;color: #fff;"  value="<?php esc_attr_e( 'Save Settings', 'xylus-events-calendar' ); ?>" />
        </div>
    </div>
</form>