<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 */
class Croquet_Match_Report_Public {

	private $plugin_name;
	private $version;
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
        write_log("About to enquee css");

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/croquet-match-report-public.css', array(), $this->version, 'all' );

		write_log( plugin_dir_url( __FILE__ ) . 'css/croquet-match-report-public.css');
 		write_log("enqueued");

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/croquet-match-report-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Registers all shortcodes at once
	 *
	 * Currently none
	 */
	public function register_shortcodes() {
	} // register_shortcodes()

	/**
	 * Adds a single template for all report types
	 */
	public function single_cpt_template( $template ) {
		global $post;
		write_log("Called single_cpt_template");
		write_log($post->post_type);
	    if ( "report" == $post->post_type ) {
            write_log("Its a report!");
			return croquet_match_report_get_template( 'single-report' );
		} else {
			return $template;
	    }
	}

	/**
	 * Sets the class variable $options
	 */
	private function set_options() {
		$this->options = get_option( $this->plugin_name . '-options' );
	} // set_options()
}
