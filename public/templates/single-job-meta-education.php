<?php
/**
 * The view for the single job metadata for education
 */

if ( ! empty( $meta['job-requirements-education'][0] ) ) {

	?><h3><?php echo esc_html( apply_filters( 'croquet-match-report-title-job-requirements-education', 'Education' ), 'croquet-match-report' ); ?></h3>
	<p class="<?php echo esc_attr( 'job-requirements-education' ); ?>"><?php echo html_entity_decode( $meta['job-requirements-education'][0] ); ?></p><?php

}
