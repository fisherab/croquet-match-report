<?php

/**
 * Fired during plugin activation
 */

class Croquet_Match_Report_Activator {

	public static function activate() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-croquet-match-report-admin.php';

                Croquet_Match_Report_Admin::new_cpt_report();
                // Croquet_Match_Report_Admin::new_taxonomy_type();

                flush_rewrite_rules();

                // $opts           = array();
                // $options        = Croquet_Match_Report_Admin::get_options_list();

                //foreach ( $options as $option ) {
                  //      $opts[ $option[0] ] = $option[2];

                //}

                //update_option( 'croquet-match-report-options', $opts );

                //Croquet_Match_Report_Admin::add_admin_notices();

	}

}
