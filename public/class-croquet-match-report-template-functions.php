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
	private static $_this; //TODO get rid of this class

	private $meta;
	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		self::$_this = $this;
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	} // __construct()

//	/**
//	 * Includes the single job post content
//	 *
//	 * @hooked 		croquet-match-report-single-content 	15
//	 */
//	public function single_post_content() {
//
//		include croquet_match_report_get_template( 'single-report-post-content' );
//
//	} // single_post_content()
//
//	/**
//	 * Includes the single job post title
//	 *
//	 * @hooked 		croquet-match-report-single-content 		10
//	 */
//	public function single_post_title() {
//		include croquet_match_report_get_template( 'single-report-post-title' );
//	}
//
	/**
	 * Returns a reference to this class. Used for removing
	 * actions and/or filters declared using an object of this class.
	 *
	 * @see  	http://hardcorewp.com/2012/enabling-action-and-filter-hook-removal-from-class-based-wordpress-plugins/
	 * @return 	object 		This class
	 */
	static function this() {

		return self::$_this;

	} // this()

} // class
