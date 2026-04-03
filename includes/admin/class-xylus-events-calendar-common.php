<?php
/**
 * Common functions class for Xylus Events Calendar
 *
 * @link       http://xylusthemes.com/
 * @since      1.0.0
 *
 * @package    Xylus_Events_Calendar
 * @subpackage Xylus_Events_Calendar/includes
 * @author     Rajat Patel <prajat21@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xylus_Events_Calendar_Common {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
	}

	/**
	 * Get a random placeholder image URL
	 *
	 * @since 1.2.0
	 * @return string
	 */
	public function xylusec_get_random_placeholder() {
		$placeholders = array(
			XYLUSEC_PLUGIN_URL . 'assets/images/event-placeholder.png',
			XYLUSEC_PLUGIN_URL . 'assets/images/event-placeholder-1.png',
			XYLUSEC_PLUGIN_URL . 'assets/images/event-placeholder-2.png',
			XYLUSEC_PLUGIN_URL . 'assets/images/event-placeholder-3.png',
			XYLUSEC_PLUGIN_URL . 'assets/images/event-placeholder-4.png',
			XYLUSEC_PLUGIN_URL . 'assets/images/event-placeholder-5.png',
			XYLUSEC_PLUGIN_URL . 'assets/images/event-placeholder-6.png',
		);
		return $placeholders[ array_rand( $placeholders ) ];
	}

    /**
     * Render Page header Section
     *
     * @since 1.1
     * @return void
     */
    public function xylusec_render_common_header( $page_title  ){
        ?>
        <div class="xylusec-header" >
            <div class="xylusec-container" >
                <div class="xylusec-header-content" >
                    <span style="font-size:18px;"><?php esc_html_e('Dashboard','xylus-events-calendar'); ?></span>
                    <span class="spacer"></span>
                    <span class="page-name"><?php echo esc_attr( $page_title ); ?></span></span>
                    <div class="header-actions" >
                        <span class="round">
                            <a href="<?php echo esc_url( 'https://docs.xylusthemes.com/docs/xylus-events-calendar/' ); ?>" target="_blank">
                                <svg viewBox="0 0 20 20" fill="#2c3e50" height="20px" xmlns="http://www.w3.org/2000/svg" class="xylusec-circle-question-mark">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.6665 10.0001C1.6665 5.40008 5.39984 1.66675 9.99984 1.66675C14.5998 1.66675 18.3332 5.40008 18.3332 10.0001C18.3332 14.6001 14.5998 18.3334 9.99984 18.3334C5.39984 18.3334 1.6665 14.6001 1.6665 10.0001ZM10.8332 13.3334V15.0001H9.1665V13.3334H10.8332ZM9.99984 16.6667C6.32484 16.6667 3.33317 13.6751 3.33317 10.0001C3.33317 6.32508 6.32484 3.33341 9.99984 3.33341C13.6748 3.33341 16.6665 6.32508 16.6665 10.0001C16.6665 13.6751 13.6748 16.6667 9.99984 16.6667ZM6.6665 8.33341C6.6665 6.49175 8.15817 5.00008 9.99984 5.00008C11.8415 5.00008 13.3332 6.49175 13.3332 8.33341C13.3332 9.40251 12.6748 9.97785 12.0338 10.538C11.4257 11.0695 10.8332 11.5873 10.8332 12.5001H9.1665C9.1665 10.9824 9.9516 10.3806 10.6419 9.85148C11.1834 9.43642 11.6665 9.06609 11.6665 8.33341C11.6665 7.41675 10.9165 6.66675 9.99984 6.66675C9.08317 6.66675 8.33317 7.41675 8.33317 8.33341H6.6665Z" fill="currentColor"></path>
                                </svg>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php
        
    }

    /**
     * Render Page Footer Section
     *
     * @since 1.1
     * @return void
     */
    public function xylusec_render_common_footer(){
        ?>
            <div id="xylusec-footer-links" >
                <div class="xylusec-footer">
                    <div><?php esc_attr_e( 'Made with ♥ by the Xylus Themes','xylus-events-calendar'); ?></div>
                    <div class="xylusec-links" >
                        <a href="<?php echo esc_url( 'https://xylusthemes.com/support/' ); ?>" target="_blank" ><?php esc_attr_e( 'Support','xylus-events-calendar'); ?></a>
                        <span>/</span>
                        <a href="<?php echo esc_url( 'https://docs.xylusthemes.com/docs/xylus-events-calendar/' ); ?>" target="_blank" ><?php esc_attr_e( 'Docs','xylus-events-calendar'); ?></a>
                        <span>/</span>
                        <a href="<?php echo esc_url( admin_url( 'plugin-install.php?s=xylus&tab=search&type=term' ) ); ?>" ><?php esc_attr_e( 'Free Plugins','xylus-events-calendar'); ?></a>
                    </div>
                    <div class="xylusec-social-links">
                        <a href="<?php echo esc_url( 'https://www.facebook.com/xylusinfo/' ); ?>" target="_blank" >
                            <svg class="xylusec-facebook">
                                <path fill="currentColor" d="M16 8.05A8.02 8.02 0 0 0 8 0C3.58 0 0 3.6 0 8.05A8 8 0 0 0 6.74 16v-5.61H4.71V8.05h2.03V6.3c0-2.02 1.2-3.15 3-3.15.9 0 1.8.16 1.8.16v1.98h-1c-1 0-1.31.62-1.31 1.27v1.49h2.22l-.35 2.34H9.23V16A8.02 8.02 0 0 0 16 8.05Z"></path>
                            </svg>
                        </a>
                        <a href="<?php echo esc_url( 'https://www.linkedin.com/company/xylus-consultancy-service-xcs-/' ); ?>" target="_blank" >
                            <svg class="xylusec-linkedin">
                                <path fill="currentColor" d="M14 1H1.97C1.44 1 1 1.47 1 2.03V14c0 .56.44 1 .97 1H14a1 1 0 0 0 1-1V2.03C15 1.47 14.53 1 14 1ZM5.22 13H3.16V6.34h2.06V13ZM4.19 5.4a1.2 1.2 0 0 1-1.22-1.18C2.97 3.56 3.5 3 4.19 3c.65 0 1.18.56 1.18 1.22 0 .66-.53 1.19-1.18 1.19ZM13 13h-2.1V9.75C10.9 9 10.9 8 9.85 8c-1.1 0-1.25.84-1.25 1.72V13H6.53V6.34H8.5v.91h.03a2.2 2.2 0 0 1 1.97-1.1c2.1 0 2.5 1.41 2.5 3.2V13Z"></path>
                            </svg>
                        </a>
                        <a href="<?php echo esc_url( 'https://x.com/XylusThemes" target="_blank' ); ?>" target="_blank" >
                            <svg class="xylusec-twitter" width="24" height="24" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="12" fill="currentColor"></circle>
                                <g>
                                    <path d="M13.129 11.076L17.588 6H16.5315L12.658 10.4065L9.5665 6H6L10.676 12.664L6 17.9865H7.0565L11.1445 13.332L14.41 17.9865H17.9765L13.129 11.076ZM11.6815 12.7225L11.207 12.0585L7.4375 6.78H9.0605L12.1035 11.0415L12.576 11.7055L16.531 17.2445H14.908L11.6815 12.7225Z" fill="white"></path>
                                </g>
                            </svg>
                        </a>
                        <a href="<?php echo esc_url( 'https://www.youtube.com/@xylussupport7784' ); ?>" target="_blank" >
                            <svg class="xylusec-youtube">
                                <path fill="currentColor" d="M16.63 3.9a2.12 2.12 0 0 0-1.5-1.52C13.8 2 8.53 2 8.53 2s-5.32 0-6.66.38c-.71.18-1.3.78-1.49 1.53C0 5.2 0 8.03 0 8.03s0 2.78.37 4.13c.19.75.78 1.3 1.5 1.5C3.2 14 8.51 14 8.51 14s5.28 0 6.62-.34c.71-.2 1.3-.75 1.49-1.5.37-1.35.37-4.13.37-4.13s0-2.81-.37-4.12Zm-9.85 6.66V5.5l4.4 2.53-4.4 2.53Z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        <?php   
    }

    /**
     * Get Plugin array
     *
     * @since 1.1.0
     * @return array
     */
    public function xylusec_get_xyuls_themes_plugins(){
        return array(
            'wp-bulk-delete' => array( 'plugin_name' => esc_html__( 'WP Bulk Delete', 'xylus-events-calendar' ), 'description' => 'Delete posts, pages, comments, users, taxonomy terms and meta fields in bulk with different powerful filters and conditions.' ),
            'import-eventbrite-events' => array( 'plugin_name' => esc_html__( 'Import Eventbrite Events', 'xylus-events-calendar' ), 'description' => 'Import Eventbrite Events into WordPress website and/or Event Calendar. Nice Display with shortcode & Event widget.' ),
            'import-facebook-events' => array( 'plugin_name' => esc_html__( 'Import Social Events', 'xylus-events-calendar' ), 'description' => 'Import Facebook events into your WordPress website and/or Event Calendar. Nice Display with shortcode & Event widget.' ),
            'import-meetup-events' => array( 'plugin_name' => esc_html__( 'Import Meetup Events', 'xylus-events-calendar' ), 'description' => 'Import Meetup Events allows you to import Meetup (meetup.com) events into your WordPress site effortlessly.' ),
            'wp-event-aggregator' => array( 'plugin_name' => esc_html__( 'WP Event Aggregator', 'xylus-events-calendar' ), 'description' => 'WP Event Aggregator: Easy way to import Facebook Events, Eventbrite events, MeetUp events into your WordPress Event Calendar.' ),
            'event-schema' => array( 'plugin_name' => esc_html__( 'Event Schema / Structured Data', 'xylus-events-calendar' ), 'description' => 'Automatically Google Event Rich Snippet Schema Generator. This plug-in generates complete JSON-LD based schema (structured data for Rich Snippet) for events.' ),
            'wp-smart-import' => array( 'plugin_name' => esc_html__( 'WP Smart Import : Import any XML File to WordPress', 'xylus-events-calendar' ), 'description' => 'The most powerful solution for importing any CSV files to WordPress. Create Posts and Pages any Custom Posttype with content from any CSV file.' ),
            'xt-feed-for-linkedin' => array( 'plugin_name' => esc_html__( 'XT Feed for LinkedIn', 'xylus-events-calendar' ), 'description' => 'XT Feed for LinkedIn auto-shares WordPress posts to LinkedIn with one click, making content distribution easy and boosting your reach effortlessly.' ),
        );
    }

    /**
     * Display Admin Notices
     *
     * @since 1.0
     * @param array $notice_result Status array
     * @return void
     */
    public function xylusec_display_admin_notice( $notice_result = array() ) {

        if ( ! empty( $notice_result ) && $notice_result['status'] == 1 ){
            if( !empty( $notice_result['messages'] ) ){
                foreach ( $notice_result['messages'] as $smessages ) {
                    ?>
                    <div class="notice notice-success xylusec-notice is-dismissible">
                        <p><strong><?php echo esc_attr( $smessages ); ?></strong></p>
                    </div>
                    <?php
                }
            }  
        } elseif ( ! empty( $notice_result ) && $notice_result['status'] == 0 ){

            if( !empty( $notice_result['messages'] ) ){
                foreach ( $notice_result['messages'] as $emessages ) {
                    ?>
                    <div class="notice notice-error xylusec-notice is-dismissible">
                        <p><strong><?php echo esc_attr( $emessages ); ?></strong></p>
                    </div>
                    <?php
                }
            }
        }
    }


    /**
     * Display Admin Common Notices
     *
     * @since 1.0
     * @return void
     */
    public function xylusec_render_common_notice(){
         // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if( isset( $_GET['message'] ) && !empty( $_GET['message'] ) ){
            $status         = isset( $_GET['status'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['status'] ) ) ) : 1;  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $get_message    = sanitize_text_field( wp_unslash( $_GET['message'] ) );  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $notice_message = array( 'status' => $status, 'messages' => array( esc_attr( $get_message ) ) );
            $this->xylusec_display_admin_notice( $notice_message );
        }
    }

    /**
     * Get upcoming events
     *
     * @since 1.0.0
     * @param int $paged Page number.
     * @param int $per_page Number of events per page.
     * @return WP_Query
     */
    public function xylusec_get_upcoming_events( $post_type = '', $paged = 1, $keyword = '', $per_page = 12, $shortcode_atts = array(), $past = false ) {
        if ( empty( $post_type ) ) {
            return new WP_Query(); // Return empty query
        }

        // Decode shortcode attributes if they are passed as a JSON string
        $atts = $shortcode_atts;
        if ( is_string( $shortcode_atts ) ) {
            $atts = json_decode( $shortcode_atts, true );
        }

        $category  = isset( $atts['category'] ) ? sanitize_text_field( $atts['category'] ) : '';
        $venue     = isset( $atts['venue'] ) ? sanitize_text_field( $atts['venue'] ) : '';
        $organizer = isset( $atts['organizer'] ) ? sanitize_text_field( $atts['organizer'] ) : '';

        $current_time_mysql = current_time( 'mysql' );
        $current_time_ts    = current_time( 'timestamp' );
        $get_options        = get_option( XYLUSEC_OPTIONS );
		$selected_plugin    = isset( $get_options['xylusec_event_source'] ) ? $get_options['xylusec_event_source'] : 'eec_events';
        $selected_taxonomy  = $this->get_selected_post_type_category( $selected_plugin );

        if( $past ){
            $condition = '<';
        }else{
            $condition = '>';
        }

		if( $selected_plugin == 'ajde_events' ){
			$start_key    = 'evcal_srow';
			$end_key      = 'evcal_erow';
			$type         = 'NUMERIC'; 
			$compare_time = $current_time_ts;
		}elseif( $selected_plugin == 'event' ){
			$start_key    = '_event_start';
			$end_key      = '_event_end';
			$type         = 'DATETIME';
			$compare_time = $current_time_mysql;
		}else{
			$start_key    = 'start_ts';
			$end_key      = 'end_ts';
			$type         = 'NUMERIC';
			$compare_time = $current_time_ts;
		}
        
        // If it's our plugin's event post type, we use a custom query to support recurring instances
        if ( $post_type === 'eec_events' ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'eec_event_instances';
            $offset = ( $paged - 1 ) * $per_page;
            
            // Base query parts
            $from   = " FROM $wpdb->posts p";
            $join   = " JOIN $table_name i ON p.ID = i.event_id";
            
            // Build WHERE clauses
            $where = $wpdb->prepare( 
                " WHERE p.post_type = %s AND p.post_status = 'publish' AND i.end_date $condition %s", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                $post_type,
                $current_time_mysql
            );

            if ( ! empty( $keyword ) ) {
                $where .= $wpdb->prepare( " AND (p.post_title LIKE %s OR p.post_content LIKE %s)", '%' . $wpdb->esc_like( $keyword ) . '%', '%' . $wpdb->esc_like( $keyword ) . '%' );
            }

            // Apply Taxonomy Filters (Category, Venue, Organizer)
            $tax_filters = array(
                'eec_category'  => $category,
                'eec_venue'     => $venue,
                'eec_organizer' => $organizer,
            );

            foreach ( $tax_filters as $tax => $slug ) {
                if ( ! empty( $slug ) ) {
                    $slugs = array_map( 'trim', explode( ',', $slug ) );
                    $terms = get_terms( array(
                        'taxonomy' => $tax,
                        'slug'     => $slugs,
                        'fields'   => 'all',
                    ) );
                    if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                        $tt_ids = wp_list_pluck( $terms, 'term_taxonomy_id' );
                        $where .= " AND p.ID IN (SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id IN (" . implode( ',', array_map( 'intval', $tt_ids ) ) . "))";
                    }
                }
            }

            // Get total count using a reliable COUNT(DISTINCT p.ID) query
            $count_query = "SELECT COUNT(DISTINCT p.ID)" . $from . $join . $where;
            $total_posts = $wpdb->get_var( $count_query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

            // Final query construction for posts
            $select   = "SELECT p.*, MIN(i.start_date) as instance_start, MIN(i.end_date) as instance_end";
            $group_by = " GROUP BY p.ID";
            $order    = $past ? 'DESC' : 'ASC';
            $order_by = " ORDER BY i.start_date $order";
            $limit    = $wpdb->prepare( " LIMIT %d, %d", $offset, $per_page );

            $query_str = $select . $from . $join . $where . $group_by . $order_by . $limit;

            $posts = $wpdb->get_results( $query_str ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, PluginCheck.Security.DirectDB.UnescapedDBParameter

            // Create a mock WP_Query object to maintain compatibility with existing templates
            $query = new WP_Query();
            $query->parse_query( array(
                'post_type'      => $post_type,
                'fields'         => 'all',
                'paged'          => $paged,
                'posts_per_page' => $per_page,
            ) );
            $query->posts = $posts;
            $query->post_count = count( $posts );
            $query->found_posts = intval( $total_posts );
            $query->max_num_pages = ceil( $total_posts / $per_page );
            $query->is_archive = true;
            
            return $query;
        }

        $args = [
            'post_type'      => $post_type,
            'posts_per_page' => $per_page,
            'post_status'    => array('publish'),
            'paged'          => max( 1, intval( $paged ) ),
            'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                [
                    'key'     => $end_key,
                    'value'   => $compare_time,
                    'compare' => $condition,
                    'type'    => $type,
                ],
            ],
            'meta_key'       => $start_key, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
            'orderby'        => 'meta_value_num',
            'order'          => 'ASC',
            's'              => sanitize_text_field( $keyword ),
        ];

        // Apply tax_query
        $tax_query = array( 'relation' => 'AND' );
        if ( ! empty( $category ) ) {
			$tax_query[] = array(
                'taxonomy' => $selected_taxonomy,
                'field'    => 'slug',
                'terms'    => array_map( 'trim', explode( ',', $category ) )
            );
		}

        if ( ! empty( $venue ) ) {
			$tax_query[] = array(
                'taxonomy' => 'eec_venue',
                'field'    => 'slug',
                'terms'    => array_map( 'trim', explode( ',', $venue ) )
            );
		}

        if ( ! empty( $organizer ) ) {
			$tax_query[] = array(
                'taxonomy' => 'eec_organizer',
                'field'    => 'slug',
                'terms'    => array_map( 'trim', explode( ',', $organizer ) )
            );
		}

        if ( count( $tax_query ) > 1 ) {
            $args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
        }

        $event_query = $this->xylusec_get_uc_events( $args );
        return $event_query;
    }

    /**
     * Get events with custom search
     *
     * @since 1.0.0
     * @param array $args Query arguments.
     * @return WP_Query
     */
    public function xylusec_get_uc_events($args) {
        // Add the filter BEFORE WP_Query
        add_filter( 'posts_search', array( $this, 'xylusec_title_only_search' ), 10, 2 );
        
        $query = new WP_Query( $args );

        // Remove filter AFTER WP_Query
        remove_filter( 'posts_search', array( $this, 'xylusec_title_only_search' ), 10, 2 );

        return $query;
    }

    /**
     * Get the next upcoming instance for an event
     *
     * @since 1.0.4
     * @param int $event_id Event ID.
     * @return object|null Upcoming instance row or null.
     */
    public function xylusec_get_next_event_instance( $event_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'eec_event_instances';
        $current_time = current_time( 'mysql' ); // Correct MySQL format for comparison

        //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $instance = $wpdb->get_row( $wpdb->prepare( //phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            "SELECT * FROM $table_name WHERE event_id = %d AND start_date >= %s ORDER BY start_date ASC  LIMIT 1", //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
            $event_id,
            $current_time
        ) );

        if ( ! $instance ) {
            // Fallback: Get the last instance if no upcoming ones
            $instance = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE event_id = %d ORDER BY start_date DESC LIMIT 1", $event_id ) ); //phpcs:ignore PluginCheck.Security.DirectDB.UnescapedDBParameter, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        }

        return $instance;
    }

    /**
     * Custom search filter to search only in post titles
     *
     * @since 1.0.0
     * @param string $search Search SQL.
     * @param WP_Query $wp_query WP Query object.
     * @return string Modified search SQL.
     */
    function xylusec_title_only_search( $search, $wp_query ) {
        global $wpdb;

        // If no search term, just return the normal query
        if ( empty( $wp_query->query_vars['s'] ) ) {
            return $search;
        }

        // Restrict for specific post_type (optional)
        if ( isset( $wp_query->query_vars['post_type'] ) && $wp_query->query_vars['post_type'] !== 'event' ) {
            return $search;
        }

        // Escape and prepare search
        $q = '%' . $wpdb->esc_like( $wp_query->query_vars['s'] ) . '%';

        // Return the full WHERE clause for search
        $search = $wpdb->prepare( " AND ({$wpdb->posts}.post_title LIKE %s) ", $q );

        return $search;
    }

    /**
     * Get View Details button HTML
     *
     * @since 1.0.0
     * @param array $xylusec_options Options array.
     * @return string HTML for the button.
     */
    public function xylusec_get_view_details_button( $xylusec_options, $event_id, $width = '100' ) {
        // Get colors or use defaults
        $button_bg_color   = $xylusec_options['xylusec_button_color'] ?? '#2c3e50';
        $button_text_color = $xylusec_options['xylusec_text_color'] ?? '#FFFFFF';

        // Button label
        $label = $xylusec_options['xylusec_view_details_label'] ?? 'View Details';
        $event_permalink = get_permalink( $event_id );


        // Inline CSS for background and text color
        $style = "background: {$button_bg_color}; color: {$button_text_color}; width: {$width}%";

        // Build the anchor tag
        $button = '<a href="' . esc_url( $event_permalink ) . '" class="xylusec-event-button" style="' . esc_attr( $style ) . '">' . esc_html( $label ) . '</a>';

        return $button;
    }

    /**
     * Get Post Type taxonomy
     *
     * @since 1.0.3
     */
    public function get_selected_post_type_category( $post_type ) {
         $category = '';
        if ( empty( $post_type ) ) {
            return $category;
        }
        if( $post_type == 'wp_events' ){
            $category = 'event_category';
        }elseif( $post_type == 'eventbrite_events' ){
            $category = 'eventbrite_category';
        }elseif( $post_type == 'facebook_events' ){
            $category = 'facebook_category';
        }elseif( $post_type == 'meetup_events' ){
            $category = 'meetup_category';
        }elseif( $post_type == 'ajde_events' ){
            $category = 'event_type';
        }elseif( $post_type == 'event' ){
            $category = 'event-categories';
        }elseif( $post_type == 'eec_events' ) {
            $category = 'eec_category';
        }
        return $category;
    }

    /**
     * Get Load More button HTML
     *
     * @since 1.0.0
     * @param array $xylusec_options Options array.
     * @param string $id Button ID.
     * @return string HTML for the button.
     */
    public function xylusec_get_xylusec_load_more_button( $xylusec_options, $id = 'load-more-button' ) {
        // Get colors or fallback defaults
        $button_bg_color   = $xylusec_options['xylusec_button_color'] ?? '#2c3e50';
        $button_text_color = $xylusec_options['xylusec_text_color'] ?? '#FFFFFF';

        // Button label
        $label = $xylusec_options['xylusec_load_more_label'] ?? 'Load More Events';

        // Inline styles
        $style = "background: {$button_bg_color}; color: {$button_text_color};";

        // Build the button HTML
        $button = '<button id="' . esc_attr( $id ) . '" class="xylusec_load_more_button" style="' . esc_attr( $style ) . '">';
        $button .= esc_html( $label );
        $button .= '</button>';

        return $button;
    }

    /**
     * Get event tags
     *
     * @since 1.0.0
     * @param int $event_id Event ID.
     * @return array Event tags.
     */
    public function xylusec_get_event_tags( $event_id = 0 ) {

        if ( empty( $event_id ) ) {
            return array();
        }

        $terms = get_the_terms( $event_id, 'eec_tag' );

        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return array();
        }

        $tags = array();

        foreach ( $terms as $tag ) {
            $tags[] = array(
                'name' => $tag->name,
                'slug' => $tag->slug,
            );
        }

        return $tags;
    }

    /**
     * Get event categories
     *
     * @since 1.0.0
     * @param int $event_id Event ID.
     * @return array Event tags.
     */
    public function xylusec_get_event_categories( $event_id = 0 ) {

        if ( empty( $event_id ) ) {
            return array();
        }

        $terms = get_the_terms( $event_id, 'eec_category' );

        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return array();
        }

        $categories = array();

        foreach ( $terms as $category ) {
            $categories[] = array(
                'name' => $category->name,
                'slug' => $category->slug,
            );
        }

        return $categories;
    }

    /**
     * Get event venues
     *
     * @since 1.0.0
     * @param int $event_id Event ID.
     * @return array Event tags.
     */
    public function xylusec_get_event_venues( $event_id = 0 ) {

        // Standard return structure
        $venue_data = array(
            'name'         => '',
            'slug'         => '',
            'description'  => '',
            'full_address' => '',
            'address1'     => '',
            'city'         => '',
            'state'        => '',
            'country'      => '',
            'zip'          => '',
            'latitude'     => '',
            'longitude'    => '',
        );

        if ( empty( $event_id ) ) {
            return $venue_data;
        }

        $terms = get_the_terms( $event_id, 'eec_venue' );

        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return $venue_data;
        }

        // Single venue
        $venue    = $terms[0];
        $venue_id = (int) $venue->term_id;

        if ( ! $venue_id ) {
            return $venue_data;
        }

        // Fill data
        $venue_data = array(
            'name'         => $venue->name,
            'slug'         => $venue->slug,
            'description'  => $venue->description,
            'full_address' => get_term_meta( $venue_id, 'venue_full_address', true ),
            'address1'     => get_term_meta( $venue_id, 'venue_address1', true ),
            'city'         => get_term_meta( $venue_id, 'venue_city', true ),
            'state'        => get_term_meta( $venue_id, 'venue_state', true ),
            'country'      => get_term_meta( $venue_id, 'venue_country', true ),
            'zip'          => get_term_meta( $venue_id, 'venue_zip', true ),
            'latitude'     => get_term_meta( $venue_id, 'venue_latitude', true ),
            'longitude'    => get_term_meta( $venue_id, 'venue_longitude', true ),
        );

        return $venue_data;
    }

    /**
     * Get event organizers
     *
     * @since 1.0.0
     * @param int $event_id Event ID.
     * @return array Event tags.
     */
    public function xylusec_get_event_organizers( $event_id = 0 ) {

        $empty_organizer = array(
            'name'        => '',
            'description' => '',
            'slug'        => '',
            'email'       => '',
            'phone'       => '',
        );

        if ( empty( $event_id ) ) {
            return array();
        }

        $terms = get_the_terms( $event_id, 'eec_organizer' );

        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return array();
        }

        $organizers = array();

        foreach ( $terms as $organizer ) {

            $term_id = (int) $organizer->term_id;

            $organizers[] = array(
                'name'        => $organizer->name,
                'description' => $organizer->description,
                'slug'        => $organizer->slug,
                'email'       => get_term_meta( $term_id, 'organizer_email', true ),
                'phone'       => get_term_meta( $term_id, 'organizer_phone', true ),
            );
        }

        return $organizers;
    }
}