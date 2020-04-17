<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 */

?><ul class="wrap-jobs"><?php

foreach ( $items->posts as $item ) {

	do_action( 'croquet-match-report-jobs-list-before' );

	?><li class="single-job"><?php

	include croquet_match_report_get_template( $args['view-single'] );

	?></li><?php

	do_action( 'croquet-match-report-jobs-list-after' );

} // foreach

?></ul><?php
