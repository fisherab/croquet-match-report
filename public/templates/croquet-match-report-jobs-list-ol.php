<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 */

?><ol class="wrap-jobs"><?php

foreach ( $items->posts as $item ) {

	do_action( 'croquet-match-report-reports-list-before' );

	?><li class="single-report"><?php

	include croquet_match_reporting__get_template( $args['view-single'] );

	?></li><?php

	do_action( 'croquet-match-report-reports-list-after' );

} // foreach

?></ol><?php
