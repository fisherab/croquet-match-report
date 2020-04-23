<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the methods for creating the templates.
 *
 */
class Croquet_Match_Report_Template_Functions {

	/**
	 * Private static reference to this class
	 * Useful for removing actions declared here.
 	 */
	private static $_this;

	private $meta;
	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		self::$_this = $this;
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Includes the single report post content
	 */
	public function single_report_post_content($post, $meta) {
		include croquet_match_report_get_template( 'single-report-post-content' );
	}

	/**
	 * Returns a reference to this class. Used for removing
	 * actions and/or filters declared using an object of this class.
	 */
	static function this() {
		return self::$_this;
	}

}
