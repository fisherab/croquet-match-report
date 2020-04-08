<?php
/**
 * Plugin Name: Croquet Match Report
 * Description:       Allow the user to input and validate league match results
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author             Steve Fisher
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       my-basics-plugin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function write_log($log) {
	if (is_array($log) || is_object($log)){
		error_log(print_r($log,true));
	} else {
		error_log($log);
	}
}

write_log ("Starting main .php");

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CROQUET_MATCH_REPORT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-croquet-match-report-activator.php
 */
function activate_croquet_match_report() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-croquet-match-report-activator.php';
	Croquet_Match_Report_Activator::activate();
	write_log ("activated");
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_croquet_match_report() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-croquet-match-report-deactivator.php';
	Croquet_Match_Report_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_croquet_match_report' );
register_deactivation_hook( __FILE__, 'deactivate_croquet_match_report' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-croquet-match-report.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_croquet_match_report() {

	$plugin = new Croquet_Match_Report();
	$plugin->run();
	write_log("should be running");

}
run_croquet_match_report();
