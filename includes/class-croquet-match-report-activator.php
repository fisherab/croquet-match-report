<?php
/**
 * Fired during plugin activation
 */

class Croquet_Match_Report_Activator {

    public static function activate() {
        global $wp_roles;

        if ( class_exists( 'WP_Roles' ) ){
            if ( ! isset( $wp_roles ) ){
                $wp_roles = new WP_Roles();
            }
        }

        if ( is_object( $wp_roles ) ){
            remove_role('cmr_report_manager');
            add_role('cmr_report_manager', __( 'Report Manager', 'croquet-match-report'),
                array(
                    'read'                          => true,
                    "edit_posts"                    => true, # TODO seems like a sportspress bug as 
                                                             # should not be needed for adding 
                                                             # leagues and seasons - taxonomies
                    'upload_files'                  => true,

                    'edit_sp_player'                => true,
                    'read_sp_player'                => true,
                    'delete_sp_player'              => true,
                    'edit_sp_players'               => true,
                    'publish_sp_players'            => true,
                    'delete_sp_players'             => true,
                    'delete_published_sp_players'   => true,
                    'edit_published_sp_players'     => true,

                    'edit_sp_event'                 => true,
                    'read_sp_event'                 => true,
                    'delete_sp_event'               => true,
                    'edit_sp_events'                => true,
                    'publish_sp_events'             => true,
                    'delete_sp_events'              => true,
                    'delete_published_sp_events'    => true,
                    'edit_published_sp_events'      => true,

                    'edit_sp_team'                  => true,
                    'read_sp_team'                  => true,
                    'edit_sp_teams'                 => true,
                    'delete_sp_teams'               => true,
                    'delete_sp_team'                => true,
                    'delete_published_sp_teams'     => true,
                    'publish_sp_teams'              => true,
                    'edit_published_sp_teams'       => true,
                ));
        }
    }
}
