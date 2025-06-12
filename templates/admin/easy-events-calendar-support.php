<?php
/**
 * Admin Support & help page
 *
 * @package     Easy_Events_Calendar
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
    <div class="xtec-container">
        <div class="xtec-wrap">
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="postbox-container-2" class="postbox-container">
                        <div class="support_well">
                            <div class="xtec-support-features">
                                <div class="xtec-support-features-card">
                                    <div class="xtec-support-features-img">
                                        <?php // phpcs:disable PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage  ?>
                                        <img class="xtec-support-features-icon" src="<?php echo esc_url( XTEC_PLUGIN_URL.'assets/images/document.svg' ); ?>" alt="<?php esc_attr_e( 'Looking for Something?', 'easy-events-calendar' ); ?>">
                                    </div>
                                    <div class="xtec-support-features-text">
                                        <h3 class="xtec-support-features-title"><?php esc_attr_e( 'Looking for Something?', 'easy-events-calendar' ); ?></h3>
                                        <p><?php esc_attr_e( 'We have documentation of how to delete data in bulk.', 'easy-events-calendar' ); ?></p>
                                        <a target="_blank" class="button button-primary" href="<?php echo esc_url( 'http://docs.xylusthemes.com/docs/easy-events-calendar/' ); ?>"><?php esc_attr_e( 'Plugin Documentation', 'easy-events-calendar' ); ?></a>
                                    </div>
                                </div>
                                <div class="xtec-support-features-card">
                                    <div class="xtec-support-features-img">
                                        <?php // phpcs:disable PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage  ?>
                                        <img class="xtec-support-features-icon" src="<?php echo esc_url( XTEC_PLUGIN_URL.'assets/images/call-center.svg' ); ?>" alt="<?php esc_attr_e( 'Need Any Assistance?', 'easy-events-calendar' ); ?>">
                                    </div>
                                    <div class="xtec-support-features-text">
                                        <h3 class="xtec-support-features-title"><?php esc_attr_e( 'Need Any Assistance?', 'easy-events-calendar' ); ?></h3>
                                        <p><?php esc_attr_e( 'Our EXPERT Support Team is always ready to help you out.', 'easy-events-calendar' ); ?></p>
                                        <a target="_blank" class="button button-primary" href="<?php echo esc_url( 'https://xylusthemes.com/support/' ); ?>"><?php esc_attr_e( 'Contact Support', 'easy-events-calendar' ); ?></a>
                                    </div>
                                </div>
                                <div class="xtec-support-features-card">
                                    <div class="xtec-support-features-img">
                                        <?php // phpcs:disable PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage  ?>
                                        <img class="xtec-support-features-icon"  src="<?php echo esc_url( XTEC_PLUGIN_URL.'assets/images/bug.svg' ); ?>" alt="<?php esc_attr_e( 'Found Any Bugs?', 'easy-events-calendar' ); ?>" />
                                    </div>
                                    <div class="xtec-support-features-text">
                                        <h3 class="xtec-support-features-title"><?php esc_attr_e( 'Found Any Bugs?', 'easy-events-calendar' ); ?></h3>
                                        <p><?php esc_attr_e( 'Report any Bug that you Discovered, and get Instant Solutions.', 'easy-events-calendar' ); ?></p>
                                        <a target="_blank" class="button button-primary" href="<?php echo esc_url( 'https://github.com/xylusthemes/easy-events-calendar' ); ?>"><?php esc_attr_e( 'Report to GitHub', 'easy-events-calendar' ); ?></a>
                                    </div>
                                </div>
                                <div class="xtec-support-features-card">
                                    <div class="xtec-support-features-img">
                                        <?php // phpcs:disable PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage  ?>
                                        <img class="xtec-support-features-icon" src="<?php echo esc_url( XTEC_PLUGIN_URL.'assets/images/tools.svg' ); ?>" alt="<?php esc_attr_e( 'Require Customization?', 'easy-events-calendar' ); ?>" />
                                    </div>
                                    <div class="xtec-support-features-text">
                                        <h3 class="xtec-support-features-title"><?php esc_attr_e( 'Require Customization?', 'easy-events-calendar' ); ?></h3>
                                        <p><?php esc_attr_e( 'We would love to hear your Integration and Customization Ideas.', 'easy-events-calendar' ); ?></p>
                                        <a target="_blank" class="button button-primary" href="<?php echo esc_url( 'https://xylusthemes.com/what-we-do/' ); ?>"><?php esc_attr_e( 'Connect Our Service', 'easy-events-calendar' ); ?></a>
                                    </div>
                                </div>
                                <div class="xtec-support-features-card">
                                    <div class="xtec-support-features-img">
                                        <?php // phpcs:disable PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage  ?>
                                        <img class="xtec-support-features-icon" src="<?php echo esc_url( XTEC_PLUGIN_URL.'assets/images/like.svg' ); ?>" alt="<?php esc_attr_e( 'Like The Plugin?', 'easy-events-calendar' ); ?>" />
                                    </div>
                                    <div class="xtec-support-features-text">
                                        <h3 class="xtec-support-features-title"><?php esc_attr_e( 'Like The Plugin?', 'easy-events-calendar' ); ?></h3>
                                        <p><?php esc_attr_e( 'Your Review is very important to us as it helps us to grow more.', 'easy-events-calendar' ); ?></p>
                                        <a target="_blank" class="button button-primary" href="<?php echo esc_url( 'https://wordpress.org/support/plugin/easy-events-calendar/reviews/?rate=5#new-post' ); ?>"><?php esc_attr_e( 'Review Us on WP.org', 'easy-events-calendar' ); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php 
							global $xt_events_calendar;
                            $plugin_list = array();
                            $plugin_list = $xt_events_calendar->common->xtec_get_xyuls_themes_plugins();
                        ?>
                        <div class="" style="margin-top: 20px;">
                            <h3 class="setting_bar"><?php esc_html_e( 'Plugins you should try','easy-events-calendar' ); ?></h3>
                            <div class="xtec-about-us-plugins">
                                <!-- <div class="xtec-row"> -->
                                <div class="xtec-support-features2">
                                
                                    <?php 
                                        if( !empty( $plugin_list ) ){
                                            foreach ( $plugin_list as $key => $plugin ) {

                                                $plugin_slug = ucwords( str_replace( '-', ' ', $key ) );
                                                $plugin_name =  $plugin['plugin_name'];
                                                $plugin_description =  $plugin['description'];
                                                if( $key == 'wp-event-aggregator' ){
                                                    $plugin_icon = 'https://ps.w.org/'.$key.'/assets/icon-256x256.jpg';
                                                } elseif( $key == 'xt-feed-for-linkedin' ) {
                                                    $plugin_icon = 'https://ps.w.org/'.$key.'/assets/icon-256x256.gif';
                                                } else {
                                                    $plugin_icon = 'https://ps.w.org/'.$key.'/assets/icon-256x256.png';
                                                }

                                                // Check if the plugin is installed
                                                $plugin_installed = false;
                                                $plugin_active = false;
                                                include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                                                $all_plugins = get_plugins();
                                                $plugin_path = $key . '/' . $key . '.php';

                                                if ( isset( $all_plugins[$plugin_path] ) ) {
                                                    $plugin_installed = true;
                                                    $plugin_active = is_plugin_active( $plugin_path );
                                                }

                                                // Determine the status text
                                                $status_text = 'Not Installed';
                                                if ( $plugin_installed ) {
                                                    $status_text = $plugin_active ? 'Active' : 'Installed (Inactive)';
                                                }
                                                
                                                ?>
                                                <div class="xtec-support-features-card2 xtec-plugin">
                                                    <div class="xtec-plugin-main">
                                                        <div>
                                                            <img alt="<?php esc_attr( $plugin_slug . ' Image' ); ?>" src="<?php echo esc_url( $plugin_icon ); ?>">
                                                        </div>
                                                        <div>
                                                            <div class="xtec-main-name"><?php echo esc_attr( $plugin_slug ); ?></div>
                                                            <div><?php echo esc_attr( $plugin_description ); ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="xtec-plugin-footer">
                                                        <div class="xtec-footer-status">
                                                            <div class="xtec-footer-status-label"><?php esc_html_e( 'Status : ', 'easy-events-calendar' ); ?></div>
                                                            <div class="xtec-footer-status xtec-footer-status-<?php echo esc_attr( strtolower( str_replace(' ', '-', $status_text ) ) ); ?>">
                                                                <span <?php echo ( $status_text == 'Active' ) ? 'style="color:green;"' : ''; ?>>
                                                                    <?php echo esc_attr( $status_text ); ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="xtec-footer-action">
                                                            <?php if ( !$plugin_installed ): ?>
                                                                <a href="<?php echo esc_url( admin_url( 'plugin-install.php?s=xylus&tab=search&type=term' ) ); ?>" type="button" class="button button-primary"><?php esc_attr_e( 'Install Free Plugin', 'easy-events-calendar' ); ?></a>
                                                            <?php elseif ( !$plugin_active ): ?>
                                                                <?php 
                                                                    $activate_nonce = wp_create_nonce('activate_plugin_' . $plugin_slug); 
                                                                    $activation_url = add_query_arg(array( 'action' => 'activate_plugin', 'plugin_slug' => $plugin_slug, 'nonce' => $activate_nonce, ), admin_url('admin.php?page=delete_all_actions&tab=by_support_help'));
                                                                ?>
                                                                <a href="<?php echo esc_url( admin_url( 'plugins.php?s='. $plugin_name ) ); ?>" class="button button-primary"><?php esc_attr_e( 'Activate Plugin', 'easy-events-calendar' ); ?></a>
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