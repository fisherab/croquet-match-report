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
