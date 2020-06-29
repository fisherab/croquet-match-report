<?php

/**
 * The metabox-specific functionality of the plugin.
 */
class Croquet_Match_Report_Admin_Metaboxes {

    /**
     * The post meta data
     */
    private $meta;

    private $plugin_name;

    private $version;

    private $options;

    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this -> options = get_option( $this->plugin_name . '-options' );
    }

    public function pre_post_update_action($post_id, $data) {

        write_log(get_post_meta($post_id)['_wporg_meta_key']);
#        write_log($_POST);

        if (array_key_exists('croquet-match-report-options', $_POST)) {
            write_log($_POST['croquet-match-report-options']);
            quet-match-report-options
            update_post_meta(
                $post_id,
                '_wporg_meta_key',
                $_POST['croquet-match-report-options']
            );
        }


        # Allow update if sufficient privileges
        if (!empty(array_intersect(wp_get_current_user()->roles, ['superadmin','administrator','sp_league_manager']))) return;

        # Allow update if no results defined yet
        if (!array_key_exists('sp_results',  $_POST)) return;
        $results = $_POST['sp_results'];
        $found = false;
        foreach ($results as $result) {
            foreach (['igs','games','hoops','tps'] as $rtype) {
                if ('' != $result[$rtype]) {
                    $found = true;
                    break;
                }
            }
        }
        if (!$found) return;

        $approval_count = get_post_meta($post_id, 'approval_count', true);
        $approval_count = "" === $approval_count ? 0 : $approval_count;

        $errors = array();
        foreach (['homecaptain','awaycaptain'] as $captain) {
            if (! array_key_exists($captain, $_POST['sp_specs'])) {
                $errors[] = new WP_Error(1, 'As the results have been set the user names of the two captains must be provided');
                break;
            }
            $name = trim($_POST['sp_specs'][$captain]);
            if ("" === $name) {
                $errors[] = new WP_Error(1, 'As the results have been set the user names of the two captains must be provided');
                break;
            }

            $user = get_user_by('login',$name);
            if (!$user) {
                $errors[] = new WP_Error(1, 'The '.$captain. ' does not have a recognised user name');
                break;
            }
            if ($user->ID == wp_get_current_user()->ID) {
                if (isset($you)) {
                    $errors[] = new WP_Error(1, 'The home and away captains must be different');
                    break;
                }
                $you = $captain;
            } else {
                $newowner = $user->ID;
            }
        }
        if (!isset($you)) {
            $errors[] = new WP_Error(1, 'You must be either the home or the away captain');
        }

        /*
         * Make sure the players have a handicap
         */
        $sp_player = $_POST['sp_player'];
        $player_ids = array_merge($sp_player[0], $sp_player[1]);
        $taxonomies = $_POST['tax_input'];
        $league = $taxonomies['sp_league'];
        if (2 != count($league)) {
            $errors[] = new WP_Error(1, 'This match must be in exactly one league');
        } else {    
            $league_id = $league[1];
            $taxonomy = get_term($league_id, 'sp_league');
            while (0 != $taxonomy->parent) {
                $taxonomy = get_term($taxonomy->parent, 'sp_league');
            }
            $code = $taxonomy->slug;
            foreach ($player_ids as $player_id) {
                if (0 != $player_id) {   
                    $player = $this->get_player_info($player_id, $code);
                    if ("" === $player['hcap']) {
                        $errors[] = new WP_Error(1, $player['name'] . ' has no ' . $code . ' handicap'); 
                    }
                }
            }
        }

        /*
         * Make sure that exactly one season is present
         */
        $season = $taxonomies['sp_season'];
        if (2 != count($season)) $errors[] = new WP_Error(1, 'This match must be in exactly one season');

        /*
         *  Now see if any errors set
         */
        if (!empty($errors)) {
            foreach ($errors as $error) add_user_meta(get_current_user_id(), 'admin_notices', $error);
            $url = admin_url( 'post.php?post=' . $post_id ) . '&action=edit';
            wp_redirect( $url );
            exit;     
        }

        /*
         *  No more errors possible so update approval count and hook the owner change
         */
        if ($approval_count == 1) $newowner = get_user_by('email', $this->options['final-owner'])->ID;
        update_post_meta($post_id, 'approval_count', $approval_count + 1);

        $args = ['newowner' => $newowner, 'post_id' => $post_id, 'league_name' => get_term($league_id)->name, 'code' => $code];
        add_action ('save_post_sp_event', function() use ($args) {$this->change_author($args);});

    }

    public function change_author ($args) {
        $title = get_post($args['post_id'])->post_title;
        $user = get_user_by('ID', $args['newowner']);
        $to = $user->user_email;
        $league_managers = $this->options[$args['code'] . "-league-managers"];
        $given_name = strtok($user->user_nicename, ' \t');
        $subject = "Please check and approve a match result";
        $from = $this->options["webmaster-address"];
        $cc = $league_managers;
        $headers = [];
        $headers["From"] = $from;
        $headers["Cc"] = $cc;
        $message = "Dear " . $given_name. ",\n\n";
        $message.= "A croquet match result is waiting for your approval.\n\n";
        $message.= "Go to " . admin_url('edit.php?post_type=sp_event') . ", set the filters for the current season and for the league, '";
        $message.= $args['league_name']. "', and click on the 'filter' button. Now find the match with the title '" . $title . "'.\n\n"; 
        $message.= "Click on the title and you may get a message saying that someone is editing it - just request 'Take Over'. ";
        $message.= "Then, check the result and confirm that all players had the indicated handicaps on the day of the event. Finally click 'Update'.\n\n";
        $message.= "If something is wrong then please contact " . $league_managers . " and explain what the problem is.\n\n";
        $message.= "After doing the update the ownership of the match is transferred away from you so you will no longer see it.\n\n";
        $message.= $this->options["webmaster-name"];
        mail($to, $subject, $message, $headers);
        global $wpdb;
        $wpdb->update($wpdb->posts,['post_author' => $args['newowner']], ['id' => $args['post_id']]);
    }

    public function get_player_info($player_id, $code) {
        $player_name = get_post($player_id)->post_title;
        $metrics = unserialize(get_post_meta($player_id)['sp_metrics'][0]);
        $hcap = (!is_null($code)) ? $metrics[$code] : '';
        return (['name' => $player_name, 'hcap' => $hcap, 'username' => $metrics['username']]); 
    }

    /*
     * return ac or gc determined by oldest ancestor of first league 
     * a match should only be associated with one league
     */
    public function get_league_slug($post_id) {
        $taxonomies = wp_get_object_terms($post_id, 'sp_league');
        if (empty($taxonomies)) return null;
        $taxonomy = $taxonomies[0];
        while (0 != $taxonomy->parent) {
            $taxonomy = get_term($taxonomy->parent, 'sp_league');
        }
        return $taxonomy->slug;
    }

    /*
     *  Display any errors
     */
    public function admin_notice_handler() {
        $user_id = get_current_user_id();
        $admin_notices = get_user_meta($user_id, 'admin_notices');
        if(!empty($admin_notices)){
            $html = '';
            delete_user_meta($user_id, 'admin_notices');
            foreach($admin_notices AS $notice){
                $msgs = $notice->get_error_messages();
                if(!empty($msgs)){
                    $msg_type = $notice->get_error_data();
                    if(!empty($notice_type)){
                        $html .= '<div class="'.$msg_type.'">';
                    } else {                    
                        $html .= '<div class="error">';
                        $html .= '<p><strong>Validation errors</strong></p>';
                    }
                    foreach($msgs as $msg){
                        $html .= '<p>- '.$msg.'</p>';
                    }                    
                    $html .= '</div>';                   
                }
            }
            echo $html;
        }
    }         


    /**
     * Registers metaboxes with WordPress
     */
    public function add_metaboxes() {

        add_meta_box(
            'croquet_match_report_players',  //Box id
            apply_filters( $this->plugin_name . '-metabox-title-requirements', esc_html__( 'Players', 'croquet-match-report' ) ), // Box title
            array( $this, 'metabox' ), // Callback
            'sp_event',    // post types to have the metabox 
            'normal',    // context - i.e. where it should go
            'high',   // priority
            array(
                'file' => 'event-players' 
            ) // optional array of args to pass to callback
        );

        add_meta_box(
            'croquet_match_report_games',  //Box id
            apply_filters( $this->plugin_name . '-metabox-title-requirements', esc_html__( 'Games', 'croquet-match-report' ) ), // Box title
            array( $this, 'metabox' ), // Callback
            'sp_event',    // post types to have the metabox 
            'normal',    // context - i.e. where it should go
            'high',   // priority
            array(
                'file' => 'event-games' 
            ) // optional array of args to pass to callback
        );

    }

    /*
     * Remove all unwanted metaboxes
     */
    public function zap_metaboxes() {
        remove_meta_box('postimagediv','sp_event','side');
        remove_meta_box('sp_videodiv','sp_event','side');
        remove_meta_box('sp_modediv','sp_event','side');
        # remove_meta_box('sp_formatdiv','sp_event','side');  # TODO if uncommented events will not be saved - probable bug in sportspress
        remove_meta_box('sp_performancediv', 'sp_event', 'normal');
        remove_meta_box('sp_shortcodediv','sp_event','side');  
        remove_meta_box('postexcerpt','sp_event','normal');
        remove_meta_box('slugdiv','sp_event','normal');
        remove_post_type_support('sp_event','editor');

        remove_meta_box('sp_staffdiv','sp_team','normal');
        remove_meta_box('sp_listsdiv','sp_team','normal');
        remove_meta_box('pageparentdiv','sp_team','side');
        remove_meta_box('postexcerpt','sp_team','normal');
        remove_meta_box('slugdiv','sp_team','normal');
        remove_meta_box('sp_tablesdiv', 'sp_team', 'normal');
        remove_post_type_support('sp_team','editor');

        remove_meta_box('postimagediv','sp_player','side');
        remove_meta_box('pageparentdiv','sp_player','side');
        remove_meta_box('sp_shortcodediv','sp_player','side');
        remove_meta_box('postexcerpt','sp_player','normal');
        remove_meta_box('slugdiv','sp_player','normal');
        remove_meta_box('sp_statisticsdiv', 'sp_player', 'normal');
        remove_post_type_support('sp_player','editor');
    }

    /**
     * Calls a metabox file specified in the add_meta_box args.
     */
    public function metabox( $post, $params ) {
        if ( ! empty( $params['args']['classes'] ) ) {
            $classes = 'repeater ' . $params['args']['classes'];
        }
        include( plugin_dir_path( __FILE__ ) . 'partials/croquet-match-report-admin-metabox-' . $params['args']['file'] . '.php' );
    }

}
