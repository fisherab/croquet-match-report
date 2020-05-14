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
        add_action('sportspress_event_performance_meta_box_table_footer', array($this, 'printt'), 10, 8);
        write_log('action added');
        global $wp_filter;
#        write_log($wp_filter);
    }

    /**
     * Creates new custom post types
     */
    public static function new_cpt_report() {

        $cap_type = 'post';
        $single = 'Report';
        $plural = 'Reports';
        $cpt_name = "report";

        $opts['can_export']								= TRUE;
        $opts['capability_type']						= $cap_type;
        $opts['description']							= '';
        $opts['exclude_from_search']					= FALSE;
        $opts['has_archive']							= TRUE;
        $opts['hierarchical']							= FALSE;
        $opts['map_meta_cap']							= TRUE;
        $opts['menu_icon']								= 'dashicons-media-default';
        $opts['menu_position']							= null;
        $opts['public']									= TRUE;
        $opts['publicly_querable']						= TRUE;
        $opts['query_var']								= TRUE;
        $opts['register_meta_box_cb']					= null;
        $opts['rewrite']								= FALSE;
        $opts['show_in_admin_bar']						= TRUE;
        $opts['show_in_menu']							= TRUE;
        $opts['show_in_nav_menu']						= TRUE;
        $opts['show_ui']								= TRUE;
        $opts['supports']								= array(); // Dont want title, featured imaged, editor etc
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
        $opts['rewrite']['slug']						= esc_html__( strtolower($plural), 'croquet-match-report' );
        $opts['rewrite']['with_front']					= FALSE;

        $opts = apply_filters( 'croquet-match-report-cpt-options', $opts ); //TODO do we need this?

        register_post_type( strtolower( $cpt_name ), $opts );
        remove_post_type_support(strtolower( $cpt_name ), 'editor');
        remove_post_type_support(strtolower( $cpt_name ), 'title');	

        $cb = function ( $defaults ) {
            write_log(['Columns', $defaults]);
            $defaults = [];
            $defaults['cb'] = '<input type="checkbox" />';
            $defaults['report-league']  = 'League';
            $defaults['report-season']  = 'Season';
            $defaults['report-venue']   = 'Venue';
            $defaults['author'] = 'Added By';
            $defaults['date'] = 'Date';
            return $defaults;
        };
        add_filter('manage_report_posts_columns', $cb);

        $cb = function ( $column_name, $post_id ) {
            if ($column_name == 'report-league') {
                echo get_post_meta( $post_id, 'report-league', true );
            }
            if ($column_name == 'report-season') {
                echo get_post_meta( $post_id, 'report-season', true );
            }

            if ($column_name == 'report-venue') {
                echo get_post_meta( $post_id, 'report-venue', true );
            }
        };
        add_action( 'manage_report_posts_custom_column', $cb, 10, 2 );

        add_action('save_post_report', [$this, 'set_post_name']);
    }

    public function set_post_name($post_id) { // This code unhooks itself and rehooks at the end to avoid infinite recursion
        write_log(['Fiddle', $post_id]);
        if ( $parent_id = wp_is_post_revision( $post_id ) ) $post_id = $parent_id;
        remove_action('save_post_report', [$this, 'set_post_name']);
        wp_update_post(['ID' => $post_id, 'post_name' => $post_id]);
        add_action('save_post_report', [$this, 'set_post_name']);
    }


    private function sanitizer( $type, $data ) {

        if ( empty( $type ) ) { return; }
        if ( empty( $data ) ) { return; }

        $return 	= '';
        $sanitizer 	= new Croquet_Match_Report_Sanitize();
        $sanitizer->set_data( $data );
        $sanitizer->set_type( $type );

        $return = $sanitizer->clean();

        unset( $sanitizer );

        return $return;

    } // sanitizer()

    /**
     * Creates the help page
     */
    public function page_help() {
        include( plugin_dir_path( __FILE__ ) . 'partials/croquet-match-report-admin-page-help.php' );
    } // page_help()

    /**
     * Add top level admin menus for help
     */
    public function add_menu() {
        add_menu_page( // For help
                apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'Croquet Match Report Help', 'croquet-match-report' ) ),
                apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Croquet Match Report Help', 'croquet-match-report' ) ),
                'manage_options',
                $this->plugin_name . '-help',
                array( $this, 'page_help' ),
                'dashicons-editor-help',
                25    
                );
    }


    public function printt ( $data = array(), $labels = array(), $team_id = 0, $positions = array(), $status = true, $sortable = true, $numbers = true, $section = -1 ) {
        write_log([$data, $labels, $team_id, $positions, $status, $sortable, $numbes, $section]);
    }


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

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/croquet-match-report-admin.css', array(), $this->version, 'all' );
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


    /**
     * Creates a checkbox field
     *
     * @param   array       $args           The arguments for the field
     * @return  string                      The HTML field
     */
    public function field_checkbox( $args ) {

        $defaults['class']          = '';
        $defaults['description']    = '';
        $defaults['label']          = '';
        $defaults['name']           = $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['value']          = 0;

        apply_filters( $this->plugin_name . '-field-checkbox-options-defaults', $defaults );

        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {

            $atts['value'] = $this->options[$atts['id']];

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-checkbox.php' );

    } // field_checkbox()

    /**
     * Creates an editor field
     *
     * NOTE: ID must only be lowercase letter, no spaces, dashes, or underscores.
     *
     * @param   array       $args           The arguments for the field
     * @return  string                      The HTML field
     */
    public function field_editor( $args ) {

        $defaults['description']    = '';
        $defaults['settings']       = array( 'textarea_name' => $this->plugin_name . '-options[' . $args['id'] . ']' );
        $defaults['value']          = '';

        apply_filters( $this->plugin_name . '-field-editor-options-defaults', $defaults );

        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {

            $atts['value'] = $this->options[$atts['id']];

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-editor.php' );

    } // field_editor()

    /**
     * Creates a set of radio buttons
     *
     * @param   array       $args           The arguments for the field
     * @return  string                      The HTML field
     */
    public function field_radios( $args ) {

        $defaults['class']          = '';
        $defaults['description']    = '';
        $defaults['label']          = '';
        $defaults['name']           = $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['value']          = 0;

        apply_filters( $this->plugin_name . '-field-radios-options-defaults', $defaults );

        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {

            $atts['value'] = $this->options[$atts['id']];

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-radios.php' );

    } // field_radios()

    public function field_repeater( $args ) {

        $defaults['class']          = 'repeater';
        $defaults['fields']         = array();
        $defaults['id']             = '';
        $defaults['label-add']      = 'Add Item';
        $defaults['label-edit']     = 'Edit Item';
        $defaults['label-header']   = 'Item Name';
        $defaults['label-remove']   = 'Remove Item';
        $defaults['title-field']    = '';

        /*
           $defaults['name']           = $this->plugin_name . '-options[' . $args['id'] . ']';
         */
        apply_filters( $this->plugin_name . '-field-repeater-options-defaults', $defaults );

        $setatts    = wp_parse_args( $args, $defaults );
        $count      = 1;
        $repeater   = array();

        if ( ! empty( $this->options[$setatts['id']] ) ) {

            $repeater = maybe_unserialize( $this->options[$setatts['id']][0] );

        }

        if ( ! empty( $repeater ) ) {

            $count = count( $repeater );

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-repeater.php' );

    } // field_repeater()

    /**
     * Creates a select field
     *
     * Note: label is blank since its created in the Settings API
     *
     * @param   array       $args           The arguments for the field
     * @return  string                      The HTML field
     */
    public function field_select( $args ) {

        $defaults['aria']           = '';
        $defaults['blank']          = '';
        $defaults['class']          = 'widefat';
        $defaults['context']        = '';
        $defaults['description']    = '';
        $defaults['label']          = '';
        $defaults['name']           = $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['selections']     = array();
        $defaults['value']          = '';

        apply_filters( $this->plugin_name . '-field-select-options-defaults', $defaults );

        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {

            $atts['value'] = $this->options[$atts['id']];

        }

        if ( empty( $atts['aria'] ) && ! empty( $atts['description'] ) ) {

            $atts['aria'] = $atts['description'];

        } elseif ( empty( $atts['aria'] ) && ! empty( $atts['label'] ) ) {

            $atts['aria'] = $atts['label'];

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-select.php' );

    } // field_select()

    /**
     * Creates a text field
     *
     * @param   array       $args           The arguments for the field
     * @return  string                      The HTML field
     */
    public function field_text( $args ) {

        $defaults['class']          = 'text widefat';
        $defaults['description']    = '';
        $defaults['label']          = '';
        $defaults['name']           = $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['placeholder']    = '';
        $defaults['type']           = 'text';
        $defaults['value']          = '';

        apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );

        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {

            $atts['value'] = $this->options[$atts['id']];

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-text.php' );

    } // field_text()

    /**
     * Creates a textarea field
     *
     * @param   array       $args           The arguments for the field
     * @return  string                      The HTML field
     */
    public function field_textarea( $args ) {
        $defaults['class']          = 'large-text';
        $defaults['cols']           = 50;
        $defaults['context']        = '';
        $defaults['description']    = '';
        $defaults['label']          = '';
        $defaults['name']           = $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['rows']           = 10;
        $defaults['value']          = '';
        apply_filters( $this->plugin_name . '-field-textarea-options-defaults', $defaults );
        $atts = wp_parse_args( $args, $defaults );
        if ( ! empty( $this->options[$atts['id']] ) ) {
            $atts['value'] = $this->options[$atts['id']];
        }
        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-textarea.php' );
    } 
}
