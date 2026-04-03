<?php
/**
 * Class for Register and manage Events.
 *
 * @link       http://xylusthemes.com/
 * @since      1.0.0
 *
 * @package    Xylus_Events_Calendar
 * @subpackage Xylus_Events_Calendar/includes
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xylus_Events_Calendar_CPT {

	// The Events Calendar Event Taxonomy
	public $event_slug;

	// Event post type.
	protected $event_posttype;

	// Event Category Texonomy.
	protected $event_category;

	// Event Tag Texonomy.
	protected $event_tag;

	// Event Vanue Texonomy.
	protected $event_vanue;

	// Event Organizer Texonomy.
	protected $event_organizer;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->event_slug      = 'eec-event';
		$this->event_posttype  = 'eec_events';
		$this->event_category  = 'eec_category';
		$this->event_tag       = 'eec_tag';
		$this->event_vanue     = 'eec_venue';
		$this->event_organizer = 'eec_organizer';

        add_action( 'init', array( $this, 'register_event_post_type' ) );
        add_action( 'init', array( $this, 'register_event_taxonomy' ) );
        add_action( 'init', array( $this, 'register_venue_meta_hooks' ) );
        add_action( 'init', array( $this, 'register_organizer_meta_hooks' ) );
        add_action( 'init', array( $this, 'eec_add_custom_rewrite_rules' ) );
        add_filter( 'query_vars', array( $this, 'eec_add_query_vars' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_event_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_event_meta_boxes' ), 10, 2 );
        
        
	}

    /**
     * Add custom rewrite rules for root taxonomy archives
     *
     * @since 1.1.0
     */
    public function eec_add_custom_rewrite_rules() {
        // Venue
        add_rewrite_rule( '^eec-venue/?$', 'index.php?post_type=eec_events&eec_view=venue_root', 'top' );
        add_rewrite_rule( '^eec_venue/?$', 'index.php?post_type=eec_events&eec_view=venue_root', 'top' );
        
        // Organizer
        add_rewrite_rule( '^eec-organizer/?$', 'index.php?post_type=eec_events&eec_view=organizer_root', 'top' );
        add_rewrite_rule( '^eec_organizer/?$', 'index.php?post_type=eec_events&eec_view=organizer_root', 'top' );
        
        // Category
        add_rewrite_rule( '^eec-category/?$', 'index.php?post_type=eec_events&eec_view=category_root', 'top' );
        add_rewrite_rule( '^eec_category/?$', 'index.php?post_type=eec_events&eec_view=category_root', 'top' );
        
        // Tag
        add_rewrite_rule( '^eec-tag/?$', 'index.php?post_type=eec_events&eec_view=tag_root', 'top' );
        add_rewrite_rule( '^eec_tag/?$', 'index.php?post_type=eec_events&eec_view=tag_root', 'top' );
    }

    /**
     * Register custom query variables
     *
     * @since 1.1.0
     */
    public function eec_add_query_vars( $vars ) {
        $vars[] = 'eec_view';
        return $vars;
    }

	/**
	 * get Events Post type
	 *
	 * @since    1.0.0
	 */
	public function get_event_posttype() {
		return $this->event_posttype;
	}

	/**
	 * get events category taxonomy
	 *
	 * @since    1.0.0
	 */
	public function get_event_categroy_taxonomy() {
		return $this->event_category;
	}

	/**
	 * get events tag taxonomy
	 *
	 * @since    1.0.0
	 */
	public function get_event_tag_taxonomy() {
		return $this->event_tag;
	}

	/**
	 * get events vanue taxonomy
	 *
	 * @since    1.0.0
	 */
	public function get_event_vanue_taxonomy() {
		return $this->event_vanue;
	}

	/**
	 * get events organizer taxonomy
	 *
	 * @since    1.0.0
	 */
	public function get_event_organizer_taxonomy() {
		return $this->event_organizer;
	}

	/**
	 * Register Events Post type
	 *
	 * @since    1.0.0
	 */
	public function register_event_post_type() {

		/*
		 * Event labels
		 */
		$event_labels   = array(
			'name'                  => _x( 'Easy Events Calendar Events', 'Post Type General Name', 'xylus-events-calendar' ),
			'singular_name'         => _x( 'Easy Events Calendar Event', 'Post Type Singular Name', 'xylus-events-calendar' ),
			'menu_name'             => __( 'Easy Events Calendar Events', 'xylus-events-calendar' ),
			'name_admin_bar'        => __( 'Easy Events Calendar Event', 'xylus-events-calendar' ),
			'archives'              => __( 'Event Archives', 'xylus-events-calendar' ),
			'parent_item_colon'     => __( 'Parent Event:', 'xylus-events-calendar' ),
			'all_items'             => __( 'Easy Events Calendar Events', 'xylus-events-calendar' ),
			'add_new_item'          => __( 'Add New Event', 'xylus-events-calendar' ),
			'add_new'               => __( 'Add New', 'xylus-events-calendar' ),
			'new_item'              => __( 'New Event', 'xylus-events-calendar' ),
			'edit_item'             => __( 'Edit Event', 'xylus-events-calendar' ),
			'update_item'           => __( 'Update Event', 'xylus-events-calendar' ),
			'view_item'             => __( 'View Event', 'xylus-events-calendar' ),
			'search_items'          => __( 'Search Event', 'xylus-events-calendar' ),
			'not_found'             => __( 'Not found', 'xylus-events-calendar' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'xylus-events-calendar' ),
			'featured_image'        => __( 'Featured Image', 'xylus-events-calendar' ),
			'set_featured_image'    => __( 'Set featured image', 'xylus-events-calendar' ),
			'remove_featured_image' => __( 'Remove featured image', 'xylus-events-calendar' ),
			'use_featured_image'    => __( 'Use as featured image', 'xylus-events-calendar' ),
			'insert_into_item'      => __( 'Insert into Event', 'xylus-events-calendar' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Event', 'xylus-events-calendar' ),
			'items_list'            => __( 'Event Items list', 'xylus-events-calendar' ),
			'items_list_navigation' => __( 'Event Items list navigation', 'xylus-events-calendar' ),
			'filter_items_list'     => __( 'Filter Event items list', 'xylus-events-calendar' ),
		);
		$rewrite        = array(
			'slug'       => $this->event_slug,
			'with_front' => false,
			'pages'      => true,
			'feeds'      => true,
			'ep_mask'    => EP_NONE,
		);
		$event_cpt_args = array(
			'label'               => __( 'Easy Events Calendar Event', 'xylus-events-calendar' ),
			'description'         => __( 'Post type for Events', 'xylus-events-calendar' ),
			'labels'              => $event_labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions' ),
			'taxonomies'          => array( $this->event_category, $this->event_tag ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-calendar',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
            'show_in_rest'        => true,
			'rewrite'             => $rewrite,
		);
		register_post_type( $this->event_posttype, $event_cpt_args );
	}


	/**
	 * Register Event tag taxonomy
	 *
	 * @since    1.0.0
	 */
	public function register_event_taxonomy() {

		/* Register the Event Category taxonomy. */
		register_taxonomy(
			$this->event_category, array( $this->event_posttype ), array(
				'labels'            => array(
					'name'           => __( 'Event Categories', 'xylus-events-calendar' ),
					'singular_name'  => __( 'Event Category', 'xylus-events-calendar' ),
					'menu_name'      => __( 'Event Categories', 'xylus-events-calendar' ),
					'name_admin_bar' => __( 'Event Category', 'xylus-events-calendar' ),
					'search_items'   => __( 'Search Categories', 'xylus-events-calendar' ),
					'popular_items'  => __( 'Popular Categories', 'xylus-events-calendar' ),
					'all_items'      => __( 'All Categories', 'xylus-events-calendar' ),
					'edit_item'      => __( 'Edit Category', 'xylus-events-calendar' ),
					'view_item'      => __( 'View Category', 'xylus-events-calendar' ),
					'update_item'    => __( 'Update Category', 'xylus-events-calendar' ),
					'add_new_item'   => __( 'Add New Category', 'xylus-events-calendar' ),
					'new_item_name'  => __( 'New Category Name', 'xylus-events-calendar' ),
				),
				'public'            => true,
				'show_ui'           => true,
				'show_in_nav_menus' => true,
				'show_admin_column' => true,
				'hierarchical'      => true,
				'query_var'         => true,
				'show_in_rest'      => true, // Gutenberg
				'rewrite'           => array( 'slug' => 'eec-category' ),
			)
		);

		/* Register the event Tag taxonomy. */
		register_taxonomy(
			$this->event_tag,
			array( $this->event_posttype ),
			array(
				'public'            => true,
				'show_ui'           => true,
				'show_in_nav_menus' => true,
				'show_tagcloud'     => true,
				'show_admin_column' => true,
				'hierarchical'      => false,
				'show_in_rest'      => true, // Gutenberg
				'query_var'         => $this->event_tag,
				'rewrite'           => array( 'slug' => 'eec-tag' ),
				/* Labels used when displaying taxonomy and terms. */
				'labels'            => array(
					'name'                       => __( 'Event Tags', 'xylus-events-calendar' ),
					'singular_name'              => __( 'Event Tag', 'xylus-events-calendar' ),
					'menu_name'                  => __( 'Event Tags', 'xylus-events-calendar' ),
					'name_admin_bar'             => __( 'Event Tag', 'xylus-events-calendar' ),
					'search_items'               => __( 'Search Tags', 'xylus-events-calendar' ),
					'popular_items'              => __( 'Popular Tags', 'xylus-events-calendar' ),
					'all_items'                  => __( 'All Tags', 'xylus-events-calendar' ),
					'edit_item'                  => __( 'Edit Tag', 'xylus-events-calendar' ),
					'view_item'                  => __( 'View Tag', 'xylus-events-calendar' ),
					'update_item'                => __( 'Update Tag', 'xylus-events-calendar' ),
					'add_new_item'               => __( 'Add New Tag', 'xylus-events-calendar' ),
					'new_item_name'              => __( 'New Tag Name', 'xylus-events-calendar' ),
					'separate_items_with_commas' => __( 'Separate tags with commas', 'xylus-events-calendar' ),
					'add_or_remove_items'        => __( 'Add or remove tags', 'xylus-events-calendar' ),
					'choose_from_most_used'      => __( 'Choose from the most used tags', 'xylus-events-calendar' ),
					'not_found'                  => __( 'No tags found', 'xylus-events-calendar' ),
					'parent_item'                => null,
					'parent_item_colon'          => null,
				),
			)
		);

        /**
         * Register Event Venue (Location) taxonomy
         *
         * @since 1.0.0
         */
        register_taxonomy(
            $this->event_vanue,
            array( $this->event_posttype ),
            array(
                'public'            => true,
                'show_ui'           => true,
                'show_in_nav_menus' => true,
                'show_admin_column' => true,
                'hierarchical'      => true,
                'query_var'         => true,
                'show_in_rest'      => true, // Gutenberg
                'rewrite'           => array( 'slug' => 'eec-venue' ),
                'labels'            => array(
                    'name'              => __( 'Event Venues', 'xylus-events-calendar' ),
                    'singular_name'     => __( 'Event Venue', 'xylus-events-calendar' ),
                    'menu_name'         => __( 'Event Venues', 'xylus-events-calendar' ),
                    'name_admin_bar'    => __( 'Event Venue', 'xylus-events-calendar' ),
                    'search_items'      => __( 'Search Event Venues', 'xylus-events-calendar' ),
                    'all_items'         => __( 'All Venues', 'xylus-events-calendar' ),
                    'parent_item'       => __( 'Parent Venue', 'xylus-events-calendar' ),
                    'parent_item_colon' => __( 'Parent Venue:', 'xylus-events-calendar' ),
                    'edit_item'         => __( 'Edit Venue', 'xylus-events-calendar' ),
                    'view_item'         => __( 'View Venue', 'xylus-events-calendar' ),
                    'update_item'       => __( 'Update Venue', 'xylus-events-calendar' ),
                    'add_new_item'      => __( 'Add New Venue', 'xylus-events-calendar' ),
                    'new_item_name'     => __( 'New Venue Name', 'xylus-events-calendar' ),
                ),
            )
        );

        /**
         * Register Event Organizer (Host) taxonomy
         *
         * @since 1.0.0
         */
        register_taxonomy(
            $this->event_organizer,
            array( $this->event_posttype ),
            array(
                'public'            => true,
                'show_ui'           => true,
                'show_in_nav_menus' => true,
                'show_admin_column' => true,
                'hierarchical'      => true,
                'query_var'         => true,
                'show_in_rest'      => true, // Gutenberg
                'rewrite'           => array( 'slug' => 'eec-organizer' ),
                'labels'            => array(
                    'name'                       => __( 'Event Organizers', 'xylus-events-calendar' ),
                    'singular_name'              => __( 'Event Organizer', 'xylus-events-calendar' ),
                    'menu_name'                  => __( 'Event Organizers', 'xylus-events-calendar' ),
                    'name_admin_bar'             => __( 'Event Organizer', 'xylus-events-calendar' ),
                    'search_items'               => __( 'Search Event Organizers', 'xylus-events-calendar' ),
                    'all_items'                  => __( 'All Organizers', 'xylus-events-calendar' ),
                    'edit_item'                  => __( 'Edit Organizer', 'xylus-events-calendar' ),
                    'view_item'                  => __( 'View Organizer', 'xylus-events-calendar' ),
                    'update_item'                => __( 'Update Organizer', 'xylus-events-calendar' ),
                    'add_new_item'               => __( 'Add New Organizer', 'xylus-events-calendar' ),
                    'new_item_name'              => __( 'New Organizer Name', 'xylus-events-calendar' ),
                    'separate_items_with_commas' => __( 'Separate organizers with commas', 'xylus-events-calendar' ),
                    'add_or_remove_items'        => __( 'Add or remove organizers', 'xylus-events-calendar' ),
                    'choose_from_most_used'      => __( 'Choose from the most used organizers', 'xylus-events-calendar' ),
                    'not_found'                  => __( 'No organizers found', 'xylus-events-calendar' ),
                ),
            )
        );

	}

    /*
     *  Add Meta box for team link meta box.
     */
	public function add_event_meta_boxes() {
		add_meta_box(
			'eec_events_metabox',
			__( 'Events Details', 'xylus-events-calendar' ),
			array( $this, 'render_event_meta_boxes' ),
			array( $this->event_posttype ),
			'side',
			'default'
		);
	}

    /*
     * Event meta box render
     */
	public function render_event_meta_boxes( $post ) {

		// Use nonce for verification
		wp_nonce_field( XYLUSEC_PLUGIN_DIR, 'eec_event_metabox_nonce' );

		$start_hour     = get_post_meta( $post->ID, 'event_start_hour', true );
		$start_minute   = get_post_meta( $post->ID, 'event_start_minute', true );
		$start_meridian = get_post_meta( $post->ID, 'event_start_meridian', true );

		$end_hour       = get_post_meta( $post->ID, 'event_end_hour', true );
		$end_minute     = get_post_meta( $post->ID, 'event_end_minute', true );
		$end_meridian   = get_post_meta( $post->ID, 'event_end_meridian', true );

		$start_date     = get_post_meta( $post->ID, 'event_start_date', true );
		$end_date       = get_post_meta( $post->ID, 'event_end_date', true );
		$today          = current_time( 'Y-m-d' );

		// Set today's date as default if empty
		if ( empty( $start_date ) ) {
			$start_date = $today;
		}
		if ( empty( $end_date ) ) {
			$end_date = $today;
		}

		$recurrence_type     = get_post_meta( $post->ID, 'event_recurrence_type', true );
		$recurrence_interval = get_post_meta( $post->ID, 'event_recurrence_interval', true );
		$recurrence_end_type = get_post_meta( $post->ID, 'event_recurrence_end_type', true );
		$recurrence_end_date = get_post_meta( $post->ID, 'event_recurrence_end_date', true );
		$recurrence_end_count = get_post_meta( $post->ID, 'event_recurrence_end_count', true );
		$weekly_days         = get_post_meta( $post->ID, 'event_recurrence_weekly_days', true );
		if ( ! is_array( $weekly_days ) ) $weekly_days = array();

		$fields = [
			'eec_event_link' => __( 'Source Link', 'xylus-events-calendar' ),
		];

		?>
		<div class="eec_form_section">
			<h3><?php esc_attr_e( 'Time & Date', 'xylus-events-calendar' ); ?></h3>
			<hr>
			<div class="eec_form_row">
				<label for="event_start_date"><?php esc_attr_e( 'Start Date & Time', 'xylus-events-calendar' ); ?>:</label>
				<div class="eec_form_input_group">
					<input type="text" name="event_start_date" class="xt_datepicker" id="event_start_date" value="<?php echo esc_attr( $start_date ); ?>" /> @ 
					<?php
						$this->generate_dropdown( 'event_start', 'hour', $start_hour );
						$this->generate_dropdown( 'event_start', 'minute', $start_minute );
						$this->generate_dropdown( 'event_start', 'meridian', $start_meridian );
					?>
				</div>
			</div>

			<div class="eec_form_row">
				<label for="event_end_date"><?php esc_attr_e( 'End Date & Time', 'xylus-events-calendar' ); ?>:</label>
				<div class="eec_form_input_group">
					<input type="text" name="event_end_date" class="xt_datepicker" id="event_end_date" value="<?php echo esc_attr( $end_date ); ?>" /> @ 
					<?php
						$this->generate_dropdown( 'event_end', 'hour', $end_hour );
						$this->generate_dropdown( 'event_end', 'minute', $end_minute );
						$this->generate_dropdown( 'event_end', 'meridian', $end_meridian );
					?>
				</div>
			</div>
		</div>

		<div class="eec_form_section">
			<h3><?php esc_attr_e( 'Recurrence', 'xylus-events-calendar' ); ?></h3>
			<hr>
			<div class="eec_recurrence_ui">
				<div class="eec_form_row">
					<label for="event_recurrence_type"><?php esc_attr_e( 'Repeat Frequency', 'xylus-events-calendar' ); ?></label>
					<div class="eec_form_input_group">
						<select name="event_recurrence_type" id="event_recurrence_type" style="width: 100%; max-width: 300px;">
							<option value="none" <?php selected( $recurrence_type, 'none' ); ?>><?php esc_attr_e( 'None (One-time event)', 'xylus-events-calendar' ); ?></option>
							<option value="daily" <?php selected( $recurrence_type, 'daily' ); ?>><?php esc_attr_e( 'Daily', 'xylus-events-calendar' ); ?></option>
							<option value="weekly" <?php selected( $recurrence_type, 'weekly' ); ?>><?php esc_attr_e( 'Weekly', 'xylus-events-calendar' ); ?></option>
							<option value="monthly" <?php selected( $recurrence_type, 'monthly' ); ?>><?php esc_attr_e( 'Monthly', 'xylus-events-calendar' ); ?></option>
							<option value="yearly" <?php selected( $recurrence_type, 'yearly' ); ?>><?php esc_attr_e( 'Yearly', 'xylus-events-calendar' ); ?></option>
						</select>
					</div>
				</div>

				<div class="eec_recurrence_fields" style="<?php echo ($recurrence_type && $recurrence_type !== 'none') ? '' : 'display:none;'; ?>">
					<div class="eec_form_row">
						<label for="event_recurrence_interval"><?php esc_attr_e( 'Repeat Every', 'xylus-events-calendar' ); ?></label>
						<div class="eec-input-group">
							<input type="number" name="event_recurrence_interval" id="event_recurrence_interval" min="1" value="<?php echo esc_attr( $recurrence_interval ? $recurrence_interval : 1 ); ?>" style="width:70px;" />
							<span class="interval_label" style="font-weight: 500; color: #646970;"></span>
						</div>
					</div>

					<div class="eec_form_row weekly_days_row" style="<?php echo ($recurrence_type === 'weekly') ? '' : 'display:none;'; ?>">
						<label><?php esc_attr_e( 'Repeat On', 'xylus-events-calendar' ); ?></label>
						<div class="eec-day-picker">
							<?php
							$days = array(
								'SU' => __('S', 'xylus-events-calendar'),
								'MO' => __('M', 'xylus-events-calendar'),
								'TU' => __('T', 'xylus-events-calendar'),
								'WE' => __('W', 'xylus-events-calendar'),
								'TH' => __('T', 'xylus-events-calendar'),
								'FR' => __('F', 'xylus-events-calendar'),
								'SA' => __('S', 'xylus-events-calendar'),
							);
							$day_full_names = array(
								'SU' => __('Sunday', 'xylus-events-calendar'),
								'MO' => __('Monday', 'xylus-events-calendar'),
								'TU' => __('Tuesday', 'xylus-events-calendar'),
								'WE' => __('Wednesday', 'xylus-events-calendar'),
								'TH' => __('Thursday', 'xylus-events-calendar'),
								'FR' => __('Friday', 'xylus-events-calendar'),
								'SA' => __('Saturday', 'xylus-events-calendar'),
							);
							foreach ($days as $key => $label) {
								$is_active = in_array($key, $weekly_days);
								echo '<div class="eec-day-button ' . ($is_active ? 'active' : '') . '" data-day="' . esc_attr($key) . '" title="' . esc_attr($day_full_names[$key]) . '">' . esc_html($label) . '</div>';
								echo '<input type="checkbox" name="event_recurrence_weekly_days[]" value="' . esc_attr($key) . '" ' . checked($is_active, true, false) . ' style="display:none;" />';
							}
							?>
						</div>
					</div>

					<div class="eec_form_row">
						<label><?php esc_attr_e( 'End Condition', 'xylus-events-calendar' ); ?></label>
						<div class="eec-radio-stack">
							<div class="radio-item">
								<input type="radio" id="end_never" name="event_recurrence_end_type" value="never" <?php checked( $recurrence_end_type !== 'date' && $recurrence_end_type !== 'count', true ); ?> />
								<label for="end_never"><?php esc_attr_e( 'Never ends', 'xylus-events-calendar' ); ?></label>
							</div>
							<div class="radio-item">
								<input type="radio" id="end_date" name="event_recurrence_end_type" value="date" <?php checked( $recurrence_end_type, 'date' ); ?> />
								<label for="end_date"><?php esc_attr_e( 'Ends on', 'xylus-events-calendar' ); ?></label>
								<div class="eec-input-group date-input-wrap" style="<?php echo ($recurrence_end_type === 'date') ? '' : 'display:none;'; ?>">
									<input type="text" name="event_recurrence_end_date" class="xt_datepicker" value="<?php echo esc_attr( $recurrence_end_date ); ?>" placeholder="YYYY-MM-DD" />
								</div>
							</div>
							<div class="radio-item">
								<input type="radio" id="end_count" name="event_recurrence_end_type" value="count" <?php checked( $recurrence_end_type, 'count' ); ?> />
								<label for="end_count"><?php esc_attr_e( 'Ends after', 'xylus-events-calendar' ); ?></label>
								<div class="eec-input-group count-input-wrap" style="<?php echo ($recurrence_end_type === 'count') ? '' : 'display:none;'; ?>">
									<input type="number" name="event_recurrence_end_count" min="1" value="<?php echo esc_attr( $recurrence_end_count ? $recurrence_end_count : 1 ); ?>" style="width:70px;" />
									<span style="color: #646970;"><?php esc_attr_e( 'occurrences', 'xylus-events-calendar' ); ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="eec_form_section">
			<h3><?php esc_attr_e( 'Event Source Link', 'xylus-events-calendar' ); ?></h3>
			<hr>
			<div class="eec_form_row">
				<label for="event_source_link"><?php echo esc_html( $fields['eec_event_link'] ); ?>:</label>
				<div class="eec_form_input_group">
					<input type="text" name="event_source_link" id="event_source_link" value="<?php echo esc_url( get_post_meta( $post->ID, 'eec_event_link', true ) ); ?>" />
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * generate dropdowns for event time.
	 */
	function generate_dropdown( $start_end, $type, $selected = '' ) {
		if ( $start_end == '' || $type == '' ) {
			return;
		}
		$select_name = $start_end . '_' . $type;
		if ( $type == 'hour' ) {
			?>
			<select name="<?php echo esc_attr( $select_name ); ?>">
				<option value="01" <?php selected( $selected, '01' ); ?>>01</option>
				<option value="02" <?php selected( $selected, '02' ); ?>>02</option>
				<option value="03" <?php selected( $selected, '03' ); ?>>03</option>
				<option value="04" <?php selected( $selected, '04' ); ?>>04</option>
				<option value="05" <?php selected( $selected, '05' ); ?>>05</option>
				<option value="06" <?php selected( $selected, '06' ); ?>>06</option>
				<option value="07" <?php selected( $selected, '07' ); ?>>07</option>
				<option value="08" <?php selected( $selected, '08' ); ?>>08</option>
				<option value="09" <?php selected( $selected, '09' ); ?>>09</option>
				<option value="10" <?php selected( $selected, '10' ); ?>>10</option>
				<option value="11" <?php selected( $selected, '11' ); ?>>11</option>
				<option value="12" <?php selected( $selected, '12' ); ?>>12</option>
			</select>
			<?php
		} elseif ( $type == 'minute' ) {
			?>
			<select name="<?php echo esc_attr( $select_name ); ?>">
				<option value="00" <?php selected( $selected, '00' ); ?>>00</option>
				<option value="05" <?php selected( $selected, '05' ); ?>>05</option>
				<option value="10" <?php selected( $selected, '10' ); ?>>10</option>
				<option value="15" <?php selected( $selected, '15' ); ?>>15</option>
				<option value="20" <?php selected( $selected, '20' ); ?>>20</option>
				<option value="25" <?php selected( $selected, '25' ); ?>>25</option>
				<option value="30" <?php selected( $selected, '30' ); ?>>30</option>
				<option value="35" <?php selected( $selected, '35' ); ?>>35</option>
				<option value="40" <?php selected( $selected, '40' ); ?>>40</option>
				<option value="45" <?php selected( $selected, '45' ); ?>>45</option>
				<option value="50" <?php selected( $selected, '50' ); ?>>50</option>
				<option value="55" <?php selected( $selected, '55' ); ?>>55</option>
			</select>
			<?php
		} elseif ( $type == 'meridian' ) {
			?>
			<select name="<?php echo esc_attr( $select_name ); ?>">
				<option value="am" <?php selected( $selected, 'am' ); ?>>am</option>
				<option value="pm" <?php selected( $selected, 'pm' ); ?>>pm</option>
			</select>
			<?php
		}
	}

	/**
	 * Save Testimonial meta box Options
	 */
	public function save_event_meta_boxes( $post_id, $post ) {
		// Verify the nonce before proceeding.
		if ( ! isset( $_POST['eec_event_metabox_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['eec_event_metabox_nonce'] ) ), XYLUSEC_PLUGIN_DIR ) ) {
			return $post_id;
		}

		// check user capability to edit post
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		// can't save if auto save
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// check if team then save it.
		if ( $post->post_type != $this->event_posttype ) {
			return $post_id;
		}

		// Event Date & time Details
		$event_start_date     = isset( $_POST['event_start_date'] ) ? sanitize_text_field( wp_unslash( $_POST['event_start_date'] ) ) : '';
		$event_end_date       = isset( $_POST['event_end_date'] ) ? sanitize_text_field( wp_unslash( $_POST['event_end_date'] ) ) : '';

		// Fallback to today's date if empty on save
		$today = current_time( 'Y-m-d' );
		if ( empty( $event_start_date ) ) {
			$event_start_date = $today;
		}
		if ( empty( $event_end_date ) ) {
			$event_end_date = $today;
		}
		$event_start_hour     = isset( $_POST['event_start_hour'] ) ? sanitize_text_field( wp_unslash( $_POST['event_start_hour'] ) ) : '';
		$event_start_minute   = isset( $_POST['event_start_minute'] ) ? sanitize_text_field( wp_unslash( $_POST['event_start_minute'] ) ) : '';
		$event_start_meridian = isset( $_POST['event_start_meridian'] ) ? sanitize_text_field( wp_unslash( $_POST['event_start_meridian'] ) ) : '';
		$event_end_hour       = isset( $_POST['event_end_hour'] ) ? sanitize_text_field( wp_unslash( $_POST['event_end_hour'] ) ) : '';
		$event_end_minute     = isset( $_POST['event_end_minute'] ) ? sanitize_text_field( wp_unslash( $_POST['event_end_minute'] ) ) : '';
		$event_end_meridian   = isset( $_POST['event_end_meridian'] ) ? sanitize_text_field( wp_unslash( $_POST['event_end_meridian'] ) ) : '';

		$start_time = $event_start_date . ' ' . $event_start_hour . ':' . $event_start_minute . ' ' . $event_start_meridian;
		$end_time   = $event_end_date . ' ' . $event_end_hour . ':' . $event_end_minute . ' ' . $event_end_meridian;
		$start_ts   = strtotime( $start_time );
		$end_ts     = strtotime( $end_time );
		
		// Event Recurrence Details
		$event_recurrence_type     = isset( $_POST['event_recurrence_type'] ) ? sanitize_text_field( wp_unslash( $_POST['event_recurrence_type'] ) ) : 'none';
		$event_recurrence_interval = isset( $_POST['event_recurrence_interval'] ) ? absint( $_POST['event_recurrence_interval'] ) : 1;
		if ( $event_recurrence_interval < 1 ) $event_recurrence_interval = 1;
		$event_recurrence_end_type = isset( $_POST['event_recurrence_end_type'] ) ? sanitize_text_field( wp_unslash( $_POST['event_recurrence_end_type'] ) ) : 'never';
		$event_recurrence_end_date = isset( $_POST['event_recurrence_end_date'] ) ? sanitize_text_field( wp_unslash( $_POST['event_recurrence_end_date'] ) ) : '';
		$event_recurrence_end_count = isset( $_POST['event_recurrence_end_count'] ) ? absint( $_POST['event_recurrence_end_count'] ) : 1;
		$event_recurrence_weekly_days = isset( $_POST['event_recurrence_weekly_days'] ) ? array_map( 'sanitize_text_field', (array) $_POST['event_recurrence_weekly_days'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		
		// Event Source Link
		$event_source_link   = isset( $_POST['event_source_link'] ) ?  esc_url( wp_unslash( $_POST['event_source_link'] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash

		// Save Event Data
		// Date & Time
		update_post_meta( $post_id, 'event_start_date', $event_start_date );
		update_post_meta( $post_id, 'event_start_hour', $event_start_hour );
		update_post_meta( $post_id, 'event_start_minute', $event_start_minute );
		update_post_meta( $post_id, 'event_start_meridian', $event_start_meridian );
		update_post_meta( $post_id, 'event_end_date', $event_end_date );
		update_post_meta( $post_id, 'event_end_hour', $event_end_hour );
		update_post_meta( $post_id, 'event_end_minute', $event_end_minute );
		update_post_meta( $post_id, 'event_end_meridian', $event_end_meridian );
		update_post_meta( $post_id, 'start_ts', $start_ts );
		update_post_meta( $post_id, 'end_ts', $end_ts );
		
		// Event Source Link
		update_post_meta( $post_id, 'eec_event_link', $event_source_link );

		// Save Recurrence Data
		update_post_meta( $post_id, 'event_recurrence_type', $event_recurrence_type );
		update_post_meta( $post_id, 'event_recurrence_interval', $event_recurrence_interval );
		update_post_meta( $post_id, 'event_recurrence_end_type', $event_recurrence_end_type );
		update_post_meta( $post_id, 'event_recurrence_end_date', $event_recurrence_end_date );
		update_post_meta( $post_id, 'event_recurrence_end_count', $event_recurrence_end_count );
		update_post_meta( $post_id, 'event_recurrence_weekly_days', $event_recurrence_weekly_days );

		// Sync Instances
		if ( function_exists( 'xylusec_xt_events_calendar' ) ) {
			xylusec_xt_events_calendar()->recurrence->sync_event_instances( $post_id );
		}
	}

    public function register_venue_meta_hooks() {
        add_action( 'eec_venue_add_form_fields', array( $this, 'add_venue_fields' ) );
        add_action( 'eec_venue_edit_form_fields', array( $this, 'edit_venue_fields' ) );
        add_action( 'created_eec_venue', array( $this, 'save_venue_meta' ) );
        add_action( 'edited_eec_venue', array( $this, 'save_venue_meta' ) );
    }

    public function register_organizer_meta_hooks() {
        add_action( 'eec_organizer_add_form_fields', array( $this, 'add_organizer_fields' ) );
        add_action( 'eec_organizer_edit_form_fields', array( $this, 'edit_organizer_fields' ) );
        add_action( 'created_eec_organizer', array( $this, 'save_organizer_meta' ) );
        add_action( 'edited_eec_organizer', array( $this, 'save_organizer_meta' ) );
    }


    public function add_venue_fields() {
        ?>
        <div class="form-field">
            <label><?php echo esc_attr( 'Full Address' ); ?></label>
            <textarea name="venue_full_address" rows="3"></textarea>
        </div>

        <div class="form-field">
            <label><?php echo esc_attr( 'Address Line 1' ); ?></label>
            <input type="text" name="venue_address1">
        </div>

        <div class="form-field">
            <label><?php echo esc_attr( 'City' ); ?></label>
            <input type="text" name="venue_city">
        </div>

        <div class="form-field">
            <label><?php echo esc_attr( 'State' ); ?></label>
            <input type="text" name="venue_state">
        </div>

        <div class="form-field">
            <label><?php echo esc_attr( 'Country' ); ?></label>
            <input type="text" name="venue_country">
        </div>

        <div class="form-field">
            <label><?php echo esc_attr( 'Zip / Postal Code' ); ?></label>
            <input type="text" name="venue_zip">
        </div>

        <div class="form-field">
            <label><?php echo esc_attr( 'Latitude' ); ?></label>
            <input type="text" name="venue_latitude">
        </div>

        <div class="form-field">
            <label><?php echo esc_attr( 'Longitude' ); ?></label>
            <input type="text" name="venue_longitude">
        </div>
        <?php
    }


    public function add_organizer_fields() {
        ?>

        <div class="form-field">
            <label><?php echo esc_attr( 'Email' ); ?></label>
            <input type="email" name="organizer_email">
        </div>

        <div class="form-field">
            <label><?php echo esc_attr( 'Phone' ); ?></label>
            <input type="text" name="organizer_phone">
        </div>
        <?php
    }


    public function edit_venue_fields( $term ) {

        $full_address = get_term_meta( $term->term_id, 'venue_full_address', true );
        $address1     = get_term_meta( $term->term_id, 'venue_address1', true );
        $city         = get_term_meta( $term->term_id, 'venue_city', true );
        $state        = get_term_meta( $term->term_id, 'venue_state', true );
        $country      = get_term_meta( $term->term_id, 'venue_country', true );
        $zip          = get_term_meta( $term->term_id, 'venue_zip', true );
        $latitude     = get_term_meta( $term->term_id, 'venue_latitude', true );
        $longitude    = get_term_meta( $term->term_id, 'venue_longitude', true );
        ?>
        <tr class="form-field">
            <th><?php echo esc_attr( 'Full Address' ); ?></th>
            <td><textarea name="venue_full_address" rows="3"><?php echo esc_textarea( $full_address ); ?></textarea></td>
        </tr>

        <tr class="form-field">
            <th><?php echo esc_attr( 'Address Line 1' ); ?></th>
            <td><input type="text" name="venue_address1" value="<?php echo esc_attr( $address1 ); ?>"></td>
        </tr>

        <tr class="form-field">
            <th><?php echo esc_attr( 'City' ); ?></th>
            <td><input type="text" name="venue_city" value="<?php echo esc_attr( $city ); ?>"></td>
        </tr>

        <tr class="form-field">
            <th><?php echo esc_attr( 'State' ); ?></th>
            <td><input type="text" name="venue_state" value="<?php echo esc_attr( $state ); ?>"></td>
        </tr>

        <tr class="form-field">
            <th><?php echo esc_attr( 'Country' ); ?></th>
            <td><input type="text" name="venue_country" value="<?php echo esc_attr( $country ); ?>"></td>
        </tr>

        <tr class="form-field">
            <th><?php echo esc_attr( 'Zip / Postal Code' ); ?></th>
            <td><input type="text" name="venue_zip" value="<?php echo esc_attr( $zip ); ?>"></td>
        </tr>

        <tr class="form-field">
            <th><?php echo esc_attr( 'Latitude' ); ?></th>
            <td><input type="text" name="venue_latitude" value="<?php echo esc_attr( $latitude ); ?>"></td>
        </tr>

        <tr class="form-field">
            <th><?php echo esc_attr( 'Longitude' ); ?></th>
            <td><input type="text" name="venue_longitude" value="<?php echo esc_attr( $longitude ); ?>"></td>
        </tr>
        <?php
    }

    public function edit_organizer_fields( $term ) {

        $email = get_term_meta( $term->term_id, 'organizer_email', true );
        $phone = get_term_meta( $term->term_id, 'organizer_phone', true );
        ?>

        <tr class="form-field">
            <th>Email</th>
            <td>
                <input type="email" name="organizer_email" value="<?php echo esc_attr( $email ); ?>">
            </td>
        </tr>

        <tr class="form-field">
            <th>Phone</th>
            <td>
                <input type="text" name="organizer_phone" value="<?php echo esc_attr( $phone ); ?>">
            </td>
        </tr>
        <?php
    }


    public function save_venue_meta( $term_id ) {

        $fields = array(
            'venue_full_address' => 'sanitize_textarea_field',
            'venue_address1'     => 'sanitize_text_field',
            'venue_city'         => 'sanitize_text_field',
            'venue_state'        => 'sanitize_text_field',
            'venue_country'      => 'sanitize_text_field',
            'venue_zip'          => 'sanitize_text_field',
            'venue_latitude'     => 'sanitize_text_field',
            'venue_longitude'    => 'sanitize_text_field',
        );

        foreach ( $fields as $key => $sanitize_callback ) {
            if ( isset( $_POST[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
                update_term_meta( $term_id, $key, call_user_func( $sanitize_callback, $_POST[ $key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
            }
        }
    }

    public function save_organizer_meta( $term_id ) {

        if ( isset( $_POST['organizer_email'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
            update_term_meta( $term_id, 'organizer_email', sanitize_email( $_POST['organizer_email'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
        }

        if ( isset( $_POST['organizer_phone'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
            update_term_meta( $term_id, 'organizer_phone', sanitize_text_field( $_POST['organizer_phone'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
        }
    }
}