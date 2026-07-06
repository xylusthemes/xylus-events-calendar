<?php
/**
 * Plugin Deactivation Class
 * Collects Feedback from user about deactivation
 *
 * @package     Xylus_Events_Calendar
 * @subpackage  Xylus_Events_Calendar/admin
 * @copyright   Copyright (c) 2024, Xylus Themes
 * @since       1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The deactivation-specific functionality of the plugin.
 *
 * @package     Xylus_Events_Calendar
 * @subpackage  Xylus_Events_Calendar/admin
 * @author      Rajat Patel <prajat21@gmail.com>
 */
if ( ! class_exists( 'Xylus_Events_Calendar_Deactivation' ) ) {
    class Xylus_Events_Calendar_Deactivation {

        private $prefix = 'xylusec_';
        private $slug = 'xylus-events-calendar';
        private $plugin_version = '1.1.0';
        private $api_url = 'https://api.xylusthemes.com/api/v1/';

        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         */
        public function __construct() {
			if ( defined( 'XYLUSEC_VERSION' ) ) {
				$this->plugin_version = XYLUSEC_VERSION;
			}

            add_action( 'admin_footer', array( $this, 'deactivation_feedback_form') );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets') );
            add_action( 'wp_ajax_'.$this->prefix.'plugin_deactivation_feedback', array( $this, 'submit_plugin_deactivation_feedback') );
		}

        /**
         * Enqueue deactivation assets.
         */
        public function enqueue_admin_assets($hook) {
            if ( 'plugins.php' !== $hook ) {
                return;
            }
            wp_enqueue_style( 'xec-deactivation-css', XYLUSEC_PLUGIN_URL . 'assets/css/xylus-events-calendar-deactivation.css', false, XYLUSEC_VERSION );
        }

		public function get_deactivation_reasons() {
			return array(
				'confusing' => __('I couldn\'t understand how to make it work', 'xylus-events-calendar' ),
				'better_plugin' => __('I found a better plugin', 'xylus-events-calendar' ),
				'feature_request' => __('The plugin is great, but I need specific feature that you don\'t support', 'xylus-events-calendar' ),
				'buggy' => __('Plugin has bugs and it\'s not working', 'xylus-events-calendar' ),
				'wrong_plugin' => __('It\'s not what I was looking for', 'xylus-events-calendar' ),
				'not_working' => __('Plugin didn\'t work as expected', 'xylus-events-calendar' ),
				'temporary' => __('It\'s temporary deactivatation, for debug an issue', 'xylus-events-calendar' ),
				'other' => __('Other reasons', 'xylus-events-calendar' ),
			);
        }

        function generate_ticket(){
            $url = $this->api_url.'generateTicket';
            $user = wp_get_current_user();
            $headers = array( 'Content-Type' => 'application/json' );
            $args = array(
                'method' =>'POST',
                'body'    => json_encode(array('customer_email' => $user->user_email )),
                'blocking' => true,
                'headers' => $headers,
            );

		    $response = wp_remote_post( $url, $args );
            if ( is_wp_error( $response ) ) {
                return false;
            }

		    return wp_remote_retrieve_body($response);
        }

        function submit_plugin_deactivation_feedback(){
            if ( isset( $_REQUEST['nonce'] ) && !wp_verify_nonce( sanitize_text_field( wp_unslash ( $_REQUEST['nonce'] ) ), $this->prefix.'plugin_deactivation_feedback' ) ) {
                exit("nonce verification failed");
            }

            $url = $this->api_url.'feedback';
            $credentials = $this->generate_ticket();
            if(!$credentials){
                die();
			}

			$credentials = json_decode($credentials);
            $user = wp_get_current_user();
            $timestamp = $credentials->timestamp;
            $client_id = $credentials->client_id;
            $client_secret = $credentials->client_secret;
            $customer_email = $user->user_email;
            $customer_name = $user->user_firstname. ' '.$user->user_lastname;
            $deactivation_reason = isset( $_REQUEST['reason'] ) ? sanitize_text_field( wp_unslash ( $_REQUEST['reason'] ) ): '';
            $deactivation_reason_message = $this->get_deactivation_reasons()[$deactivation_reason];
            $customer_query = isset( $_REQUEST['customerQuery'] ) ? sanitize_text_field( wp_unslash ( $_REQUEST['customerQuery'] ) ): '';

            $data = array(
                "type" => "plugin_deactivation",
                "site_url" => get_site_url(),
                "customer_name" => $customer_name,
                "customer_email" => $customer_email,
                "plugin" => $this->slug,
                "plugin_name" => 'Easy Events Calendar',
                "plugin_version" => $this->plugin_version,
                "deactivation_reason" => $deactivation_reason,
                "deactivation_reason_message" => $deactivation_reason_message,
                "query" => $customer_query
            );

            $plain_string = $customer_email .  $timestamp . $client_secret;
            $sha512_hash  = hash("sha512", $plain_string);

            $body = json_encode($data);
            $headers = array( 'Content-Type' => 'application/json');
            $headers['Client-Id'] = $client_id;
            $headers['Timestamp'] = $timestamp;
            $headers['Authorization'] = $sha512_hash;
            $args = array(
                'method' =>'POST',
                'body' => $body,
                'blocking' => true,
                'headers' => $headers
            );
			$response = wp_remote_post( $url, $args );
            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                echo esc_attr( "Something went wrong: $error_message" );
                exit();
            }

            die(true);
        }

        public function deactivation_feedback_form() {
            $wp_screen = get_current_screen();
            $page_id = $wp_screen->id;

            // Load only for WP admin plugins page
            if($page_id !== 'plugins'){
                return;
            }
            wp_enqueue_style( 'wp-jquery-ui-dialog');
            wp_enqueue_script( 'jquery-ui-dialog');

            $deactivate_reasons = $this->get_deactivation_reasons();
        	?>

            <script>
                jQuery(document).ready(function() {
					var dataReason = jQuery('input:radio[name="<?php echo esc_attr( $this->prefix ); ?>deactivatation_reason_radio"]:checked').val();
                    
                    jQuery('a[id^="deactivate-xylus-events-calendar"]').on('click', function (e) {
                        e.preventDefault();
                        var pluginDeactivateURL = jQuery(this).attr('href');
                        jQuery('#<?php echo esc_attr( $this->slug ); ?>-deactivate-dialog' ).dialog({
                            'dialogClass'   : '<?php echo esc_attr( $this->slug ) . "-deactivate-dialog"; ?>',
                            'modal'         : true,
                            'closeOnEscape' : true,
                            width: 550,
                            'buttons'       : [
                                {
                                    text: "Submit & Deactivate",
                                    class: 'button button-primary xec-modern-btn xec-submit-btn <?php echo esc_attr( $this->prefix ) . "deactivate_button"; ?>',
                                    click: function() {
										var that = this;
										var dataQuery = jQuery('#<?php echo esc_attr( $this->prefix ); ?>customer_query').val();
										if(dataReason == 'other' && !dataQuery){
											jQuery('#<?php echo esc_attr( $this->prefix ); ?>customer_query').focus();
											return false;
										}
										jQuery('#<?php echo esc_attr( $this->prefix ); ?>deactivatation_form').hide();
										jQuery('.<?php echo esc_attr( $this->prefix ); ?>deactivatation_loading').show();
                                        jQuery('button.<?php echo esc_attr( $this->prefix ); ?>deactivate_button').prop('disabled', true);
                                        jQuery.ajax({
                                            type : "post",
                                            dataType : "json",
                                            url : "<?php echo esc_url( admin_url('admin-ajax.php?action='.$this->prefix.'plugin_deactivation_feedback&nonce='.wp_create_nonce($this->prefix.'plugin_deactivation_feedback') ) ); ?>",
                                            data : {
                                                action: "<?php echo esc_attr( $this->prefix ); ?>plugin_deactivation_feedback",
                                                reason: dataReason,
                                                customerQuery: dataQuery
                                            },
                                        }).always( function(){
											jQuery( that ).dialog( "close" );
											window.location.href=pluginDeactivateURL;
										});
                                    }
                                },
                                {
                                    text: "Skip & Deactivate",
                                    class: 'button xec-modern-btn xec-skip-btn',
                                    click: function() {
                                        jQuery( this ).dialog( "close" );
                                        window.location.href=pluginDeactivateURL;
                                    }
                                }
                            ]
                        });
                    });

                    jQuery(document).on('click', '.xec-dialog-close-btn', function() {
                        jQuery('#<?php echo esc_attr( $this->slug ); ?>-deactivate-dialog').dialog('close');
                    });

                    jQuery(document).on('click', '.xec-reason-item', function() {
                        jQuery('.xec-reason-item').removeClass('active');
                        jQuery(this).addClass('active');
                        jQuery(this).find('input:radio').prop('checked', true).trigger('change');
                    });

                    jQuery('input:radio[name="<?php echo esc_attr( $this->prefix ); ?>deactivatation_reason_radio"]').change(function () {
                        var reason = jQuery(this).val();
						dataReason = jQuery(this).val();
                        var customerQuery = jQuery('#<?php echo esc_attr( $this->prefix ); ?>customer_query');
                        customerQuery.removeAttr('required');
                        
                        var placeholder = "<?php esc_attr_e('Write your query here', 'xylus-events-calendar'); ?>";

                        if (reason === "confusing") {
                            placeholder = "<?php esc_attr_e('Finding it confusing? let us know so that we can improve the interface', 'xylus-events-calendar' ); ?>";
                        } else if (reason === "other") {
                            placeholder = "<?php esc_attr_e('Can you let us know the reason for deactivation (Required)', 'xylus-events-calendar' ); ?>";
                            customerQuery.prop('required', true);
                        } else if (reason === "buggy" || reason === 'not_working') {
                            placeholder = "<?php esc_attr_e('Can you please let us know about the bug/issue in detail?', 'xylus-events-calendar' ); ?>";
                        } else if (reason === "better_plugin") {
                            placeholder = "<?php esc_attr_e('Can you please let us know which plugin you found helpful', 'xylus-events-calendar' ); ?>";
                        } else if (reason === "feature_request") {
                            placeholder = "<?php esc_attr_e('Can you please let us know more about the feature you want', 'xylus-events-calendar' ); ?>";
                        }  else if (reason === "wrong_plugins") {
                            placeholder = "<?php esc_attr_e('Can you please let us know more about your requirement', 'xylus-events-calendar' ); ?>";
                        }

                        customerQuery.attr("placeholder", placeholder);
                        jQuery('.xec-feedback-textarea-wrap').slideDown();
                    });
                });
            </script>
            <div id="<?php echo esc_attr( $this->slug ); ?>-deactivate-dialog" style="display:none;">
                <div class="xec-dialog-header">
                    <button type="button" class="xec-dialog-close-btn" aria-label="<?php esc_attr_e('Close dialog', 'xylus-events-calendar'); ?>">&times;</button>
                    <h2><?php esc_html_e('Quick Feedback', 'xylus-events-calendar'); ?></h2>
                    <p><?php esc_html_e('Could you please share why you are deactivating Easy Events Calendar?', 'xylus-events-calendar'); ?></p>
                </div>

                <form method="post" action="" id="<?php echo esc_attr( $this->prefix ); ?>deactivatation_form">
                    <div class="xec-reasons-list">
                    <?php
                        foreach ( $deactivate_reasons as $key => $deactivate_reason ) {
                            ?>
                            <div class="xec-reason-item">
                                <input type="radio" name="<?php echo esc_attr( $this->prefix ); ?>deactivatation_reason_radio" id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $key ); ?>"> 
                                <span><?php echo esc_attr( $deactivate_reason ); ?></span>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="xec-feedback-textarea-wrap">
                        <textarea id="<?php echo esc_attr( $this->prefix ); ?>customer_query" name="<?php echo esc_attr( $this->prefix ); ?>customer_query" rows="4" placeholder="<?php esc_attr_e('Write your query here', 'xylus-events-calendar'); ?>"></textarea>
                    </div>

                    <div class="xec-footer-notice">
                        <?php echo esc_html__( '* By submitting this form, you will also be sending us your email address & website URL.', 'xylus-events-calendar' ); ?>
                    </div>
                </form>

				<div class="<?php echo esc_attr( $this->prefix ); ?>deactivatation_loading" style="padding: 40px 0; text-align: center; display:none;">
					<img src="<?php echo esc_url( admin_url('images/spinner.gif') ); ?>" style="width: 20px; height: 20px; margin-right: 10px; vertical-align: middle;" />
                    <span style="font-size: 15px; color: #646970;"><?php esc_html_e('Submitting your feedback...', 'xylus-events-calendar'); ?></span>
				</div>
            </div>
            <?php
        }
    }
}
