<?php
/**
 * Admin Support & help page
 *
 * @package     Xylus_Events_Calendar
 * @subpackage  Admin/Pages
 * @copyright   Copyright (c) 2025, Rajat Patel
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Support & help Page
 *
 * Render the Support & help page
 *
 * @since 1.0.0
 * @return void
 */
    ?>
    <div class="xylusec-container">
        <div class="xylusec-wrap">
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="postbox-container-2" class="postbox-container">
                        <div class="support_well">
                            <div class="xylusec-support-features">
                                <div class="xylusec-support-features-card">
                                    <div class="xylusec-support-features-img">
                                        <?php // phpcs:disable PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage  ?>
                                        <img class="xylusec-support-features-icon" src="<?php echo esc_url( XYLUSEC_PLUGIN_URL.'assets/images/document.svg' ); ?>" alt="<?php esc_attr_e( 'Looking for Something?', 'xylus-events-calendar' ); ?>">
                                    </div>
                                    <div class="xylusec-support-features-text">
                                        <h3 class="xylusec-support-features-title"><?php esc_attr_e( 'Looking for Something?', 'xylus-events-calendar' ); ?></h3>
                                        <p><?php esc_attr_e( 'We have documentation of how to delete data in bulk.', 'xylus-events-calendar' ); ?></p>
                                        <a target="_blank" class="button button-primary" href="<?php echo esc_url( 'http://docs.xylusthemes.com/docs/xylus-events-calendar/' ); ?>"><?php esc_attr_e( 'Plugin Documentation', 'xylus-events-calendar' ); ?></a>
                                    </div>
                                </div>
                                <div class="xylusec-support-features-card">
                                    <div class="xylusec-support-features-img">
                                        <?php // phpcs:disable PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage  ?>
                                        <img class="xylusec-support-features-icon" src="<?php echo esc_url( XYLUSEC_PLUGIN_URL.'assets/images/call-center.svg' ); ?>" alt="<?php esc_attr_e( 'Need Any Assistance?', 'xylus-events-calendar' ); ?>">
                                    </div>
                                    <div class="xylusec-support-features-text">
                                        <h3 class="xylusec-support-features-title"><?php esc_attr_e( 'Need Any Assistance?', 'xylus-events-calendar' ); ?></h3>
                                        <p><?php esc_attr_e( 'Our EXPERT Support Team is always ready to help you out.', 'xylus-events-calendar' ); ?></p>
                                        <a target="_blank" class="button button-primary" href="<?php echo esc_url( 'https://xylusthemes.com/support/' ); ?>"><?php esc_attr_e( 'Contact Support', 'xylus-events-calendar' ); ?></a>
                                    </div>
                                </div>
                                <div class="xylusec-support-features-card">
                                    <div class="xylusec-support-features-img">
                                        <?php // phpcs:disable PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage  ?>
                                        <img class="xylusec-support-features-icon"  src="<?php echo esc_url( XYLUSEC_PLUGIN_URL.'assets/images/bug.svg' ); ?>" alt="<?php esc_attr_e( 'Found Any Bugs?', 'xylus-events-calendar' ); ?>" />
                                    </div>
                                    <div class="xylusec-support-features-text">
                                        <h3 class="xylusec-support-features-title"><?php esc_attr_e( 'Found Any Bugs?', 'xylus-events-calendar' ); ?></h3>
                                        <p><?php esc_attr_e( 'Report any Bug that you Discovered, and get Instant Solutions.', 'xylus-events-calendar' ); ?></p>
                                        <a target="_blank" class="button button-primary" href="<?php echo esc_url( 'https://github.com/xylusthemes/xylus-events-calendar' ); ?>"><?php esc_attr_e( 'Report to GitHub', 'xylus-events-calendar' ); ?></a>
                                    </div>
                                </div>
                                <div class="xylusec-support-features-card">
                                    <div class="xylusec-support-features-img">
                                        <?php // phpcs:disable PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage  ?>
                                        <img class="xylusec-support-features-icon" src="<?php echo esc_url( XYLUSEC_PLUGIN_URL.'assets/images/tools.svg' ); ?>" alt="<?php esc_attr_e( 'Require Customization?', 'xylus-events-calendar' ); ?>" />
                                    </div>
                                    <div class="xylusec-support-features-text">
                                        <h3 class="xylusec-support-features-title"><?php esc_attr_e( 'Require Customization?', 'xylus-events-calendar' ); ?></h3>
                                        <p><?php esc_attr_e( 'We would love to hear your Integration and Customization Ideas.', 'xylus-events-calendar' ); ?></p>
                                        <a target="_blank" class="button button-primary" href="<?php echo esc_url( 'https://xylusthemes.com/what-we-do/' ); ?>"><?php esc_attr_e( 'Connect Our Service', 'xylus-events-calendar' ); ?></a>
                                    </div>
                                </div>
                                <div class="xylusec-support-features-card">
                                    <div class="xylusec-support-features-img">
                                        <?php // phpcs:disable PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage  ?>
                                        <img class="xylusec-support-features-icon" src="<?php echo esc_url( XYLUSEC_PLUGIN_URL.'assets/images/like.svg' ); ?>" alt="<?php esc_attr_e( 'Like The Plugin?', 'xylus-events-calendar' ); ?>" />
                                    </div>
                                    <div class="xylusec-support-features-text">
                                        <h3 class="xylusec-support-features-title"><?php esc_attr_e( 'Like The Plugin?', 'xylus-events-calendar' ); ?></h3>
                                        <p><?php esc_attr_e( 'Your Review is very important to us as it helps us to grow more.', 'xylus-events-calendar' ); ?></p>
                                        <a target="_blank" class="button button-primary" href="<?php echo esc_url( 'https://wordpress.org/support/plugin/xylus-events-calendar/reviews/?rate=5#new-post' ); ?>"><?php esc_attr_e( 'Review Us on WP.org', 'xylus-events-calendar' ); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php 
							global $xylusec_events_calendar;
                            $xylusec_plugin_list = array();
                            $xylusec_plugin_list = $xylusec_events_calendar->common->xylusec_get_xyuls_themes_plugins();
                        ?>
                        <div class="" style="margin-top: 20px;">
                            <h3 class="setting_bar"><?php esc_html_e( 'Plugins you should try','xylus-events-calendar' ); ?></h3>
                            <div class="xylusec-about-us-plugins">
                                <!-- <div class="xylusec-row"> -->
                                <div class="xylusec-support-features2">
                                
                                    <?php 
                                        if( !empty( $xylusec_plugin_list ) ){
                                            foreach ( $xylusec_plugin_list as $xylusec_key => $xylusec_plugin ) {

                                                $xylusec_plugin_slug = ucwords( str_replace( '-', ' ', $xylusec_key ) );
                                                $xylusec_plugin_name =  $xylusec_plugin['plugin_name'];
                                                $xylusec_plugin_description =  $xylusec_plugin['description'];
                                                if( $xylusec_key == 'wp-event-aggregator' ){
                                                    $xylusec_plugin_icon = 'https://ps.w.org/'.$xylusec_key.'/assets/icon-256x256.jpg';
                                                } elseif( $xylusec_key == 'xt-feed-for-linkedin' ) {
                                                    $xylusec_plugin_icon = 'https://ps.w.org/'.$xylusec_key.'/assets/icon-256x256.gif';
                                                } else {
                                                    $xylusec_plugin_icon = 'https://ps.w.org/'.$xylusec_key.'/assets/icon-256x256.png';
                                                }

                                                // Check if the plugin is installed
                                                $xylusec_plugin_installed = false;
                                                $xylusec_plugin_active = false;
                                                include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                                                $xylusec_all_plugins = get_plugins();
                                                $xylusec_plugin_path = $xylusec_key . '/' . $xylusec_key . '.php';

                                                if ( isset( $xylusec_all_plugins[$xylusec_plugin_path] ) ) {
                                                    $xylusec_plugin_installed = true;
                                                    $xylusec_plugin_active = is_plugin_active( $xylusec_plugin_path );
                                                }

                                                // Determine the status text
                                                $xylusec_status_text = 'Not Installed';
                                                if ( $xylusec_plugin_installed ) {
                                                    $xylusec_status_text = $xylusec_plugin_active ? 'Active' : 'Installed (Inactive)';
                                                }
                                                
                                                ?>
                                                <div class="xylusec-support-features-card2 xylusec-plugin">
                                                    <div class="xylusec-plugin-main">
                                                        <div>
                                                            <img alt="<?php esc_attr( $xylusec_plugin_slug . ' Image' ); ?>" src="<?php echo esc_url( $xylusec_plugin_icon ); ?>">
                                                        </div>
                                                        <div>
                                                            <div class="xylusec-main-name"><?php echo esc_attr( $xylusec_plugin_slug ); ?></div>
                                                            <div><?php echo esc_attr( $xylusec_plugin_description ); ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="xylusec-plugin-footer">
                                                        <div class="xylusec-footer-status">
                                                            <div class="xylusec-footer-status-label"><?php esc_html_e( 'Status : ', 'xylus-events-calendar' ); ?></div>
                                                            <div class="xylusec-footer-status xylusec-footer-status-<?php echo esc_attr( strtolower( str_replace(' ', '-', $xylusec_status_text ) ) ); ?>">
                                                                <span <?php echo ( $xylusec_status_text == 'Active' ) ? 'style="color:green;"' : ''; ?>>
                                                                    <?php echo esc_attr( $xylusec_status_text ); ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="xylusec-footer-action">
                                                            <?php if ( !$xylusec_plugin_installed ): ?>
                                                                <a href="<?php echo esc_url( admin_url( 'plugin-install.php?s=xylus&tab=search&type=term' ) ); ?>" type="button" class="button button-primary"><?php esc_attr_e( 'Install Free Plugin', 'xylus-events-calendar' ); ?></a>
                                                            <?php elseif ( !$xylusec_plugin_active ): ?>
                                                                <?php 
                                                                    $xylusec_activate_nonce = wp_create_nonce('activate_plugin_' . $xylusec_plugin_slug); 
                                                                    $xylusec_activation_url = add_query_arg(array( 'action' => 'activate_plugin', 'plugin_slug' => $xylusec_plugin_slug, 'nonce' => $xylusec_activate_nonce, ), admin_url('admin.php?page=delete_all_actions&tab=by_support_help'));
                                                                ?>
                                                                <a href="<?php echo esc_url( admin_url( 'plugins.php?s='. $xylusec_plugin_name ) ); ?>" class="button button-primary"><?php esc_attr_e( 'Activate Plugin', 'xylus-events-calendar' ); ?></a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            <div style="clear: both;">
                        </div>
                    </div>
                </div>
                <br class="clear">
            </div>
        </div>
    </div>
    <?php