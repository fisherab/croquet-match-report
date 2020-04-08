<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

class Croquet_Match_Report_Activator {

	public static function activate() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-croquet-match-report-admin.php';

                Croquet_Match_Report_Admin::new_cpt_report();
                // Now_Hiring_Admin::new_taxonomy_type();

                flush_rewrite_rules();

                // $opts           = array();
                // $options        = Now_Hiring_Admin::get_options_list();

                //foreach ( $options as $option ) {
                  //      $opts[ $option[0] ] = $option[2];

                //}

                //update_option( 'now-hiring-options', $opts );

                //Now_Hiring_Admin::add_admin_notices();

	}

}
