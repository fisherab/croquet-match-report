<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 */

do_action( 'croquet-match-report-reports-list-before' );

foreach ( $items->posts as $item ) {

	include croquet_match_report_get_template( $args['view-single'] );

} // foreach

do_action( 'croquet-match-report-reports-list-after' );
