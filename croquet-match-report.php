<?php
/**
 * Plugin Name: Croquet Match Report
 * Description:       Allow the user to input and validate league match results
 * Version:           2020.04.11
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author             Steve Fisher
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       my-basics-plugin
 */

// WPINC is defined by WordPress so this stops anybody invokine the file directly
if ( ! defined( 'WPINC' ) ) die;

function write_log($log) { // TODO delete when no longer needed or make it depend  on WP_DEBUG
    if (is_array($log) || is_object($log)){
        error_log(print_r($log,true));
    } else {
        error_log($log);
    }
}

function display_sp ($msg) { # TODO this is probably already no longer needed
    global $wp_filter;
    write_log($msg);
    if (array_key_exists('save_post_sp_event', $wp_filter)) {
        foreach ($wp_filter['save_post_sp_event']->callbacks as $cb) {
            write_log(count($cb));
        }
    } else {
        write_log(0);
    }
    write_log(" -- eoo");
}

function activate_croquet_match_report() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-croquet-match-report-activator.php';
    Croquet_Match_Report_Activator::activate();
    write_log ("CMR activated - in croquet-match-report.php");
}

function deactivate_croquet_match_report() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-croquet-match-report-deactivator.php';
    Croquet_Match_Report_Deactivator::deactivate();
    write_log('CMR deactivated - in croquet-match-report.php');
}

register_activation_hook( __FILE__, 'activate_croquet_match_report' );
register_deactivation_hook( __FILE__, 'deactivate_croquet_match_report' );

require plugin_dir_path( __FILE__ ) . 'includes/class-croquet-match-report.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
function run_croquet_match_report() {
    $plugin = new Croquet_Match_Report();
    $plugin->run();
}
run_croquet_match_report();
