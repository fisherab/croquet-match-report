<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 */
class Croquet_Match_Report_i18n {

	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'plugin-name',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

    public function set_domain( $domain ) {
        $this->domain = $domain;
    }
}
