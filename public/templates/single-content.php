<?php
/**
 * The template for displaying all single job posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 */

$meta = get_post_custom( $post->ID );

/**
 * croquet-match-report-before-single hook
 */
do_action( 'croquet-match-report-before-single', $meta );

?><div class="wrap-job"><?php

	/**
	 * croquet-match-report-before-single-content hook
	 */
	do_action( 'croquet-match-report-before-single-content', $meta );

		/**
		 * croquet-match-report-single-content hook
		 */
		do_action( 'croquet-match-report-single-content', $meta );

	/**
	 * croquet-match-report-after-single-content hook
	 */
	do_action( 'croquet-match-report-after-single-content', $meta );

?></div><!-- .wrap-employee --><?php

/**
 * croquet-match-report-after-single hook
 */
do_action( 'croquet-match-report-after-single', $meta );
