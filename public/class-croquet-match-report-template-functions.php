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
	 *
	 * @var 	object 		$_this
 	 */
	private static $_this;

	/**
	 * The post meta data
	 */
	private $meta;

	private $plugin_name;

	private $version;

	public function __construct( $plugin_name, $version ) {

		self::$_this = $this;

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	} // __construct()

	/*
	 * Includes the croquet-match-report-report-title template
	 *
	 * @hooked 		croquet-match-report-loop-content 		10
	 *
	 * @param 		object 		$item 		A post object
	 * @param 		array 		$meta 		The post metadata
	 */
	public function content_job_title( $item, $meta ) {

		include croquet_match_report_get_template( 'croquet-match-report-report-title' );

	} // content_job_title()

	/**
	 * Includes the employee name template file
	 *
	 * @hooked 		croquet-match-report-loop-content 		15
	 *
	 * @param 		object 		$item 		A post object
	 * @param 		array 		$meta 		The post metadata
	 */
	public function content_job_location( $item, $meta ) {

		include croquet_match_report_get_template( 'croquet-match-report-report-location' );

	} // content_job_location()

	/**
	 * Includes the link end template file
	 *
	 * @hooked 		croquet-match-report-after-loop-content 		10
	 *
	 * @param 		object 		$item 		A post object
	 * @param 		array 		$meta 		The post metadata
	 */
	public function content_link_end( $item, $meta ) {

		include croquet_match_report_get_template( 'croquet-match-report-content-link-end' );

	} // content_link_end()

	/**
	 * Includes the link start template file
	 *
	 * @hooked 		croquet-match-report-before-loop-content 		15
	 *
	 * @param 		object 		$item 		A post object
	 * @param 		array 		$meta 		The post metadata
	 */
	public function content_link_start( $item, $meta ) {

		include croquet_match_report_get_template( 'croquet-match-report-content-link-start' );

	} // content_link_start()

	/**
	 * Includes the content wrap end template file
	 *
	 * @hooked 		croquet-match-report-after-loop-content 		90
	 *
	 * @param 		object 		$item 		A post object
	 * @param 		array 		$meta 		The post metadata
	 */
	public function content_wrap_end( $item, $meta ) {

		include croquet_match_report_get_template( 'croquet-match-report-content-wrap-end' );

	} // content_wrap_end()

	/**
	 * Includes the content wrap start template file
	 *
	 * @hooked 		croquet-match-report-before-loop-content 		10
	 */
	public function content_wrap_start() {

		include croquet_match_report_get_template( 'croquet-match-report-content-wrap-start' );

	} // content_wrap_start()

	/**
	 * Returns an array of the featured image details
	 *
	 * @param 	int 	$postID 		The post ID
	 * @return 	array 					Array of info about the featured image
	 */
	public function get_featured_images( $postID ) {

		if ( empty( $postID ) ) { return FALSE; }

		$imageID = get_post_thumbnail_id( $postID );

		if ( empty( $imageID ) ) { return FALSE; }

		return wp_prepare_attachment_for_js( $imageID );

	} // get_featured_images()

	/**
	 * Includes the list wrap end template file
	 *
	 * @hooked 		croquet-match-report-after-loop 		10
	 */
	public function list_wrap_end() {

		include croquet_match_report_get_template( 'croquet-match-report-list-wrap-end' );

	} // list_wrap_end()

	/**
	 * Includes the list wrap start template file
	 *
	 * @hooked 		croquet-match-report-before-loop 		10
	 */
	public function list_wrap_start() {

		include croquet_match_report_get_template( 'croquet-match-report-list-wrap-start' );

	} // list_wrap_start()

	/**
	 * Includes the single job post content
	 *
	 * @hooked 		croquet-match-report-single-content 	15
	 */
	public function single_post_content() {

		include croquet_match_report_get_template( 'single-report-post-content' );

	} // single_post_content()

	/**
	 * Includes the single job post metadata for education
	 *
	 * @hooked 		croquet-match-report-single-content 	30
	 *
	 * @param 		array 		$meta 		The post metadata
	 */
	public function single_post_education( $meta ) {

		include croquet_match_report_get_template( 'single-report-meta-education' );

	} // single_post_education()

	/**
	 * Includes the single job post metadata for experience
	 *
	 * @hooked 		croquet-match-report-single-content 	40
	 *
	 * @param 		array 		$meta 		The post metadata
	 */
	public function single_post_experience( $meta ) {

		include croquet_match_report_get_template( 'single-report-meta-experience' );

	} // single_post_experience()

	/**
	 * Includes the single job post metadata for the file
	 *
	 * @hooked 		croquet-match-report-single-content 	50
	 *
	 * @param 		array 		$meta 		The post metadata
	 */
	public function single_post_file( $meta ) {

		include croquet_match_report_get_template( 'single-report-meta-file' );

	} // single_post_file()

	/**
	 * Includes the single job post metadata for info
	 *
	 * @hooked 		croquet-match-report-single-content 	45
	 *
	 * @param 		array 		$meta 		The post metadata
	 */
	public function single_post_info( $meta ) {

		include croquet_match_report_get_template( 'single-report-meta-info' );

	} // single_post_info()

	/**
	 * Includes the single job post metadata for location
	 *
	 * @hooked 		croquet-match-report-single-content 	25
	 *
	 * @param 		array 		$meta 		The post metadata
	 */
	public function single_post_location( $meta ) {

		include croquet_match_report_get_template( 'single-report-meta-location' );

	} // single_post_location()

	/**
	 * Includes the single job post metadata for responsibilities
	 *
	 * @hooked 		croquet-match-report-single-content 	20
	 *
	 * @param 		array 		$meta 		The post metadata
	 */
	public function single_post_responsibilities( $meta ) {

		include croquet_match_report_get_template( 'single-report-meta-responsibilities' );

	} // single_post_responsibilities()

	/**
	 * Includes the single job post metadata for skills
	 *
	 * @hooked 		croquet-match-report-single-content 	35
	 *
	 * @param 		array 		$meta 		The post metadata
	 */
	public function single_post_skills( $meta ) {

		include croquet_match_report_get_template( 'single-report-meta-skills' );

	} // single_post_skills()

	/**
	 * Includes the single job post title
	 *
	 * @hooked 		croquet-match-report-single-content 		10
	 */
	public function single_post_title() {

		include croquet_match_report_get_template( 'single-report-post-title' );

	} // single_post_title()

	public function single_post_how_to_apply() {

		echo apply_filters( 'croquetmatchreport_howtoapply', '' );

	} // single_post_how_to_apply()

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
