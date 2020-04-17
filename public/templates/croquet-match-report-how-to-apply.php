<?php

/**
 * Provide a public-facing view for the How to Apply plugin option
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?><div class="how-to-apply">
	<h2><?php esc_html_e( 'How to Apply', 'croquet-match-report' ); ?></h2><?php

	echo $this->options['howtoapply'];

?></div>
