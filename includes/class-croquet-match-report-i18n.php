<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 */
class Croquet_Match_Report_i18n {

    public function hook_text_translations() {
        add_filter( 'gettext', array( $this, 'gettext' ), 20, 3 );
    }

    public function gettext( $translated_text, $untranslated_text, $domain ) {
        if ( $domain == 'sportspress' ) {
            switch ( $untranslated_text ) {
                case 'Events':
                    $translated_text = __( 'Matches', 'croquet-match-report' );
                    break;
                case 'Event':
                    $translated_text = __( 'Match', 'croquet-match-report' );
                    break;
                case 'Add New Event':
                    $translated_text = __( 'Add New Match', 'croquet-match-report' );
                    break;
                case 'Edit Event':
                    $translated_text = __( 'Edit Match', 'croquet-match-report' );
                    break;
                case 'View Event':
                    $translated_text = __( 'View Match', 'croquet-match-report' );
                    break;
                case 'View all events':
                    $translated_text = __( 'View all matches', 'croquet-match-report' );
                    break;
                    // TODO remove cricket stuff
                case 'Substitute':
                case 'Substituted':
                    $translated_text = __( 'Did Not Bat', 'croquet-match-report' );
                    break;
                case 'Offense':
                    $translated_text = __( 'Batting', 'croquet-match-report' );
                    break;
                case 'Defense':
                    $translated_text = __( 'Bowling', 'croquet-match-report' );
                    break;
            }
        }
        return $translated_text;
    }

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
