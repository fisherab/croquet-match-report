<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the archive loop.
 */

/**
 * croquet-match-report-before-loop hook
 *
 * @hooked 		list_wrap_start 		10
 */
do_action( 'croquet-match-report-before-loop' );

foreach ( $items as $item ) {

	$meta = get_post_custom( $item->ID );

	/**
	 * croquet-match-report-before-loop-content hook
	 *
	 * @param 		object  	$item 		The post object
	 *
	 * @hooked 		content_wrap_start 		10
	 */
	do_action( 'croquet-match-report-before-loop-content', $item, $meta );

		/**
		 * croquet-match-report-loop-content hook
		 *
		 * @param 		object  	$item 		The post object
		 *
		 * @hooked 		content_job_title 		10
		 * @hooked 		content_job_location 	15
		 */
		do_action( 'croquet-match-report-loop-content', $item, $meta );

	/**
	 * croquet-match-report-after-loop-content hook
	 *
	 * @param 		object  	$item 		The post object
	 *
	 * @hooked 		content_link_end 		10
	 * @hooked 		content_wrap_end 		90
	 */
	do_action( 'croquet-match-report-after-loop-content', $item, $meta );

} // foreach

/**
 * croquet-match-report-after-loop hook
 *
 * @hooked 		list_wrap_end 			10
 */
do_action( 'croquet-match-report-after-loop' );
