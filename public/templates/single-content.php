<?php
/**
 * The template for displaying all single report posts.
 */

$meta = get_post_custom( $post->ID );

do_action( 'croquet-match-report-before-single', $meta );

?><div class="wrap-report"><?php

	do_action( 'croquet-match-report-before-single-content', $meta );

		do_action( 'croquet-match-report-single-content', $post, $meta );

	do_action( 'croquet-match-report-after-single-content', $meta );

?></div><?php

do_action( 'croquet-match-report-after-single', $meta );
