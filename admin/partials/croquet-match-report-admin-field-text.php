<?php

/**
 * Provides the markup for any text field
 */

if ( ! empty( $atts['label'] ) ) {
	?><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'croquet-match-report' ); ?>: </label><?php

}

?><input
	class="<?php echo esc_attr( $atts['class'] ); ?>"
	id="<?php echo esc_attr( $atts['id'] ); ?>"
	name="<?php echo esc_attr( $atts['name'] ); ?>"
	value="<?php echo esc_attr( $atts['value'] ); ?>" /><?php

if ( ! empty( $atts['description'] ) ) {
	?><span class="description"><?php esc_html_e( $atts['description'], 'croquet-match-report' ); ?></span><?php
}
