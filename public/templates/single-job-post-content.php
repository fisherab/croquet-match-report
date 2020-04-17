<?php
/**
 * The view for the post content used on the single post
 */

?><div class="content-job" itemtype="description">
	<h2><?php echo esc_html( apply_filters( 'croquet-match-report-title-job-label', 'Job Description' ), 'croquet-match-report' ); ?></h2><?php

	the_content();

?></div>
