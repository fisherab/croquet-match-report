<?php
/**
 * The template for displaying all single jobs posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * Get a custom header-employee.php file, if it exists.
 * Otherwise, get default header.
 */
get_header( 'cmr_z' );

if ( have_posts() ) :

	/**
	 * croquet-match-report-single-before-loop hook
	 *
	 * @hooked 		job_single_content_wrap_start 		10
	 */
	do_action( 'croquet-match-report-single-before-loop' );

	while ( have_posts() ) : the_post();

		include croquet_match_report_get_template( 'single-content' );

	endwhile;

	/**
	 * croquet-match-report-single-after-loop hook
	 *
	 * @hooked 		job_single_content_wrap_end 		90
	 */
	do_action( 'croquet-match-report-single-after-loop' );

endif;

get_footer( 'cmr_z' );
