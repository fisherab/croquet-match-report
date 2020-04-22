<?php

/**
 * Provide the view for a report hometeam metabox
 */

wp_nonce_field( $this->plugin_name, 'report_header' );

global $wpdb;
$q = "SELECT {$wpdb->prefix}terms.name FROM {$wpdb->prefix}terms, {$wpdb->prefix}term_taxonomy WHERE taxonomy='sp_venue' and {$wpdb->prefix}terms.term_id = {$wpdb->prefix}term_taxonomy.term_id ORDER BY name";
$atts 					= array();
$atts['class'] 			= 'widefat';
$atts['description'] 	= '';
$atts['id'] 			= 'report-venue';
$atts['label'] 			= 'Venue';
$atts['name'] 			= 'report-venue';
$atts['placeholder'] 	= '';
$atts['type'] 			= 'text';
$atts['selections']     = $wpdb->get_col($q);
$atts['value'] 			= '';
$atts['aria']           = "wibble"; //TODO what should this be?

if ( ! empty( $this->meta[$atts['id']][0] ) ) {
	$atts['value'] = $this->meta[$atts['id']][0];
}
apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

?><p><?php

include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-select.php' );

?></p><?php

$atts 					= array();
$atts['class'] 			= 'widefat';
$atts['description'] 	= '';
$atts['id'] 			= 'report-season';
$atts['label'] 			= 'Season';
$atts['name'] 			= 'report-season';
$atts['placeholder'] 	= '2020';
$atts['type'] 			= 'text';
$atts['value'] 			= '';

if ( ! empty( $this->meta[$atts['id']][0] ) ) {
	$atts['value'] = $this->meta[$atts['id']][0];
}
apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

?><p><?php

include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );

?></p><?php

$atts 					= array();
$atts['class'] 			= 'widefat';
$atts['description'] 	= '';
$atts['id'] 			= 'report-hometeam';
$atts['label'] 			= 'Home Team';
$atts['name'] 			= 'report-hometeam';
$atts['placeholder'] 	= '';
$atts['type'] 			= 'text';
$atts['value'] 			= '';

if ( ! empty( $this->meta[$atts['id']][0] ) ) {
	$atts['value'] = $this->meta[$atts['id']][0];
}
apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

?><p><?php

include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );

?></p><?php

$atts 					= array();
$atts['class'] 			= 'widefat';
$atts['description'] 	= '';
$atts['id'] 			= 'report-awayteam';
$atts['label'] 			= 'Away Team';
$atts['name'] 			= 'report-awayteam';
$atts['placeholder'] 	= '';
$atts['type'] 			= 'text';
$atts['value'] 			= '';

if ( ! empty( $this->meta[$atts['id']][0] ) ) {
	$atts['value'] = $this->meta[$atts['id']][0];
}
apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

?><p><?php

include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );






?></p>

