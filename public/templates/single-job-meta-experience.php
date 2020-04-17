<?php
/**
 * The view for the single job metadata for the experience
 */

if ( ! empty( $meta['job-requirements-experience'][0] ) ) {

	?><h3><?php echo esc_html( apply_filters( 'croquet-match-report-title-job-requirements-experience', 'Experience' ), 'croquet-match-report' ); ?></h3>
	<p class="<?php echo esc_attr( 'job-requirements-experience' ); ?>"><?php echo html_entity_decode( $meta['job-requirements-experience'][0] ); ?></p><?php

}
