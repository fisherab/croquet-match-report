<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 */
class Croquet_Match_Report_Admin {

	private $plugin_name;

	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	/**
	 * Creates a new custom post type
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_post_type()
	 */
	public static function new_cpt_report() {

		$cap_type 	= 'post';
		$plural 	= 'Reports';
		$single 	= 'Report';
		$cpt_name 	= 'report';

		$opts['can_export']								= TRUE;
		$opts['capability_type']						= $cap_type;
		$opts['description']							= '';
		$opts['exclude_from_search']					= FALSE;
		$opts['has_archive']							= FALSE;
		$opts['hierarchical']							= FALSE;
		$opts['map_meta_cap']							= TRUE;
		$opts['menu_icon']								= 'dashicons-media-default';
		$opts['menu_position']							= 25;
		$opts['public']									= TRUE;
		$opts['publicly_querable']						= TRUE;
		$opts['query_var']								= TRUE;
		$opts['register_meta_box_cb']					= '';
		$opts['rewrite']								= FALSE;
		$opts['show_in_admin_bar']						= TRUE;
		$opts['show_in_menu']							= TRUE;
		$opts['show_in_nav_menu']						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['supports']								= array( 'title', 'editor', 'thumbnail' );
		$opts['taxonomies']								= array();

		$opts['capabilities']['delete_others_posts']	= "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']			= "delete_{$cap_type}";
		$opts['capabilities']['delete_posts']			= "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']	= "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts']	= "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']		= "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']				= "edit_{$cap_type}";
		$opts['capabilities']['edit_posts']				= "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']		= "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']	= "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']			= "publish_{$cap_type}s";
		$opts['capabilities']['read_post']				= "read_{$cap_type}";
		$opts['capabilities']['read_private_posts']		= "read_private_{$cap_type}s";

		$opts['labels']['add_new']						= esc_html__( "Add New {$single}", 'croquet-match-report' );
		$opts['labels']['add_new_item']					= esc_html__( "Add New {$single}", 'croquet-match-report' );
		$opts['labels']['all_items']					= esc_html__( $plural, 'croquet-match-report' );
		$opts['labels']['edit_item']					= esc_html__( "Edit {$single}" , 'croquet-match-report' );
		$opts['labels']['menu_name']					= esc_html__( $plural, 'croquet-match-report' );
		$opts['labels']['name']							= esc_html__( $plural, 'croquet-match-report' );
		$opts['labels']['name_admin_bar']				= esc_html__( $single, 'croquet-match-report' );
		$opts['labels']['new_item']						= esc_html__( "New {$single}", 'croquet-match-report' );
		$opts['labels']['not_found']					= esc_html__( "No {$plural} Found", 'croquet-match-report' );
		$opts['labels']['not_found_in_trash']			= esc_html__( "No {$plural} Found in Trash", 'croquet-match-report' );
		$opts['labels']['parent_item_colon']			= esc_html__( "Parent {$plural} :", 'croquet-match-report' );
		$opts['labels']['search_items']					= esc_html__( "Search {$plural}", 'croquet-match-report' );
		$opts['labels']['singular_name']				= esc_html__( $single, 'croquet-match-report' );
		$opts['labels']['view_item']					= esc_html__( "View {$single}", 'croquet-match-report' );

		$opts['rewrite']['ep_mask']						= EP_PERMALINK;
		$opts['rewrite']['feeds']						= FALSE;
		$opts['rewrite']['pages']						= TRUE;
		$opts['rewrite']['slug']						= esc_html__( strtolower( $plural ), 'croquet-match-report' );
		$opts['rewrite']['with_front']					= FALSE;

		$opts = apply_filters( 'croquet-match-report-cpt-options', $opts );

		register_post_type( strtolower( $cpt_name ), $opts );

	} // new_cpt_report()


	/**
	 * Registers settings fields with WordPress
	 */
	public function register_fields() {
/*
		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );

		add_settings_field(
			'message-no-openings',
			apply_filters( $this->plugin_name . 'label-message-no-openings', esc_html__( 'No Openings Message', 'now-hiring' ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '-messages',
			array(
				'description' 	=> 'This message displays on the page if no job postings are found.',
				'id' 			=> 'message-no-openings',
				'value' 		=> 'Thank you for your interest! There are no job openings at this time.',
			)
		);

		add_settings_field(
			'how-to-apply',
			apply_filters( $this->plugin_name . 'label-how-to-apply', esc_html__( 'How to Apply', 'now-hiring' ) ),
			array( $this, 'field_editor' ),
			$this->plugin_name,
			$this->plugin_name . '-messages',
			array(
				'description' 	=> 'Instructions for applying (contact email, phone, fax, address, etc).',
				'id' 			=> 'howtoapply'
			)
		);

		add_settings_field(
			'repeater-test',
			apply_filters( $this->plugin_name . 'label-repeater-test', esc_html__( 'Repeater Test', 'now-hiring' ) ),
			array( $this, 'field_repeater' ),
			$this->plugin_name,
			$this->plugin_name . '-messages',
			array(
				'description' 	=> 'Instructions for applying (contact email, phone, fax, address, etc).',
				'fields' 		=> array(
					array(
						'text' => array(
							'class' 		=> '',
							'description' 	=> '',
							'id' 			=> 'test1',
							'label' 		=> '',
							'name' 			=> $this->plugin_name . '-options[test1]',
							'placeholder' 	=> 'Test 1',
							'type' 			=> 'text',
							'value' 		=> ''
						),
					),
					array(
						'text' => array(
							'class' 		=> '',
							'description' 	=> '',
							'id' 			=> 'test2',
							'label' 		=> '',
							'name' 			=> $this->plugin_name . '-options[test2]',
							'placeholder' 	=> 'Test 2',
							'type' 			=> 'text',
							'value' 		=> ''
						),
					),
					array(
						'text' => array(
							'class' 		=> '',
							'description' 	=> '',
							'id' 			=> 'test3',
							'label' 		=> '',
							'name' 			=> $this->plugin_name . '-options[test3]',
							'placeholder' 	=> 'Test 3',
							'type' 			=> 'text',
							'value' 		=> ''
						),
					),
				),
				'id' 			=> 'repeater-test',
				'label-add' 	=> 'Add Test',
				'label-edit' 	=> 'Edit Test',
				'label-header' 	=> 'TEST',
				'label-remove' 	=> 'Remove Test',
				'title-field' 	=> 'test1'

			)
		);
*/
	} // register_fields()

	/**
	 * Registers settings sections with WordPress
	 */
	public function register_sections() {

/*

		// add_settings_section( $id, $title, $callback, $menu_slug );

		add_settings_section(
			$this->plugin_name . '-messages',
			apply_filters( $this->plugin_name . 'section-title-messages', esc_html__( 'Messages', 'now-hiring' ) ),
			array( $this, 'section_messages' ),
			$this->plugin_name
		);
*/

	} // register_sections()

	/**
	 * Registers plugin settings
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function register_settings() {
/*

		// register_setting( $option_group, $option_name, $sanitize_callback );

		register_setting(
			$this->plugin_name . '-options',
			$this->plugin_name . '-options',
			array( $this, 'validate_options' )
		);
*/

	} // register_settings()

	private function sanitizer( $type, $data ) {

		if ( empty( $type ) ) { return; }
		if ( empty( $data ) ) { return; }

		$return 	= '';
		$sanitizer 	= new Now_Hiring_Sanitize();

		$sanitizer->set_data( $data );
		$sanitizer->set_type( $type );

		$return = $sanitizer->clean();

		unset( $sanitizer );

		return $return;

	} // sanitizer()



	/**
	 * Creates the help page
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function page_help() {

		include( plugin_dir_path( __FILE__ ) . 'partials/croquet-match-report-admin-page-help.php' );

	} // page_help()


	/**
	 * Adds links to the plugin links row
	 */
	public function link_row( $links, $file ) {

		if ( NOW_HIRING_FILE === $file ) {
			$links[] = '<a href="http://twitter.com/slushman">Twitter</a>';
		}

		return $links;

	} // link_row()


	/**
	 * Add admin submenus below "Reports"
	 */
	public function add_menu() {

/*
		add_submenu_page(
			'edit.php?post_type=job',
			apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'Now Hiring Settings', 'now-hiring' ) ),
			apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Settings', 'now-hiring' ) ),
			'manage_options',
			$this->plugin_name . '-settings',
			array( $this, 'page_options' )
		);

*/

		write_log("Adding help menu");
		add_submenu_page( // For help
			'edit.php?post_type=report',
			apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'Croquet Match Report Help', 'croquet-match-report' ) ),
			apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Help', 'croquet-match-report' ) ),
			'manage_options',
			$this->plugin_name . '-help',
			array( $this, 'page_help' )
		);

	} // add_menu()

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );

	}

}
