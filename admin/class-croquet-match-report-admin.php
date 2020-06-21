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
    private $options;

    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->set_options();
    }

    /**
     * Creates the help page
     */
    public function page_help() {
        include( plugin_dir_path( __FILE__ ) . 'partials/croquet-match-report-admin-page-help.php' );
    }

    /**
     * Add menus
     */
    public function add_menu() {
        /**
         * Add help menu
         */
        add_menu_page( // For help
            apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'Croquet Match Report Help', 'croquet-match-report' ) ),
            apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Croquet Match Report Help', 'croquet-match-report' ) ),
            'manage_options',
            $this->plugin_name . '-help',
            array( $this, 'page_help' ),
            'dashicons-editor-help',
            25    
        );

        /**
         * Add option submenu
         */
        add_submenu_page(
            'options-general.php',
            apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'Croquet Match Report Settings', 'croquet-match-report' ) ),
            apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Croquet Match Report', 'croquet-match-report' ) ),
            'manage_options',
            $this->plugin_name . '-settings',
            array( $this, 'page_options' )
        );
    }

    /**
     * Creates the options page
     */
    public function page_options() {
        include( plugin_dir_path( __FILE__ ) . 'partials/croquet-match-report-admin-page-settings.php' );
    }

    /**
     * Sets the class variable $options
     */
    private function set_options() {
        $this->options = get_option( $this->plugin_name . '-options' );
    }

    /**
     * Registers settings - only one in this case
     */
    public function register_settings() {
        register_setting(
            $this->plugin_name . '-options',
            $this->plugin_name . '-options',
            ['sanitize_callback' => [$this, 'validate_options']]
        );
    }

    public function validate_options($input) {

        $key = "webmaster-address";
        $email = $input[$key];
        if (! Croquet_Match_Report_Validator::check("email",$email)) {
            add_settings_error($this->plugin_name . '-options', $this->plugin_name . '-options' . '1', "Webmaster's address is not a valid email address: " . sanitize_text_field($email), 'error'); 
            $input[$key] = $this->options[$key];
        }

        $key = "webmaster-name";
        $name = $input[$key];
        if (sanitize_text_field($name) !== $name) {
            add_settings_error($this->plugin_name . '-options', $this->plugin_name . '-options' . '1', "Webmaster's name has illegal content: " . sanitize_text_field($name), 'error'); 
            $input[$key] = $this->options[$key];
        }

        foreach ([["ac-league-managers","AC League Managers"],["gc-league-managers","GC League Managers"]] as $row) {
            $key = $row[0];
            $value = explode(",",$input[$key]);
            $fail = false;
            foreach ($value as $email) {
                if (! get_user_by("email", $email)) {
                    add_settings_error($this->plugin_name . '-options', $this->plugin_name . '-options' . '1', "No user registered as " . sanitize_text_field($email), 'error'); 
                    $fail = true;
                }
            }
            if ($fail) {
                $input[$key] = $this->options[$key];
            }
        }

        $key = "final-owner";
        $email = $input[$key];
        write_log($email);
        if (! get_user_by("email", $email)) {
            add_settings_error($this->plugin_name . '-options', $this->plugin_name . '-options' . '1', "Final owner's email address not registered: " . sanitize_text_field($email), 'error'); 
            $input[$key] = $this->options[$key];
        }



        return $input;
    }

    /**
     * Registers sections within settings - currently just one
     */
    public function register_sections() {
        add_settings_section(
            $this->plugin_name . '-messages',
            apply_filters( $this->plugin_name . 'section-title-messages', esc_html__( 'Messages', 'croquet-match-report' ) ),
            array( $this, 'section_messages' ),
            $this->plugin_name
        );
    }

    /**
     * Creates a settings section
     */
    public function section_messages( $params ) {
        include( plugin_dir_path( __FILE__ ) . 'partials/croquet-match-report-admin-section-messages.php' );
    }

    /**
     * Register fields in sections
     */
    public function register_fields() {
        add_settings_field(
            'webmaster-address',
            esc_html__( 'Webmaster Address', 'croquet-match-report' ),
            array( $this, 'field_text'),
            $this->plugin_name,
            $this->plugin_name . '-messages',
            array(
                'description' 	=> 'The email address of the webmaster - with a domain the same as that of the server',
                'id' => 'webmaster-address',
            )
        );

        add_settings_field(
            'webmaster-name',
            esc_html__( 'Webmaster Name', 'croquet-match-report' ),
            array( $this, 'field_text'),
            $this->plugin_name,
            $this->plugin_name . '-messages',
            array(
                'description' 	=> 'Used as the signature on emails sent by the webmaster',
                'id' => 'webmaster-name',
            )
        );

        add_settings_field(
            'ac-league-managers',
            esc_html__('AC League Managers', 'croquet-match-report'),
            array( $this, 'field_text'),
            $this->plugin_name,
            $this->plugin_name . '-messages',
            array(
                'description' 	=> 'A comma separated list of email addresses',
                'id' => 'ac-league-managers',
            )
        );

        add_settings_field(
            'gc-league-managers',
            esc_html__('GC League Managers', 'croquet-match-report'),
            array( $this, 'field_text'),
            $this->plugin_name,
            $this->plugin_name . '-messages',
            array(
                'description' 	=> 'A comma separated list of email addresses',
                'id' => 'gc-league-managers',
            )
        );  
  
        add_settings_field(
            'final-owner',
            esc_html__('Final owner', 'croquet-match-report'),
            array( $this, 'field_text'),
            $this->plugin_name,
            $this->plugin_name . '-messages',
            array(
                'description' 	=> 'Email address of user to own completed reports',
                'id' => 'final-owner',
            )
        );
 
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
