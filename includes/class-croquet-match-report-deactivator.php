<?php
/**
 * Fired during plugin deactivation
 */
class Croquet_Match_Report_Deactivator {

    public static function deactivate() {
        global $wp_roles;                                                                     
        if (class_exists('WP_Roles')) {
            if (! isset($wp_roles)){
                $wp_roles = new WP_Roles();
            }
        }                                                
        if (is_object($wp_roles)) remove_role('cmr_report_manager');
    }
}
