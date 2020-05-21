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

    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        #       $this->set_meta();
    }

    public function pre_post_update_action($post_id, $data) {
        $md = get_post_meta($post_id);
        write_log(['ppua',$post_id, $data['post_title'],$md['sp_minutes']]);
        write_log(["current",get_post($post_id)]);

        $errors = array();

        if(trim($_POST['post_title']) !== ''){
            $errors[] = new WP_Error(42, 'Are you mad?');
        }

        if (!empty($errors)) {
            add_user_meta(get_current_user_id(), 'admin_notices', $errors, true);
            $url = admin_url( 'post.php?post=' . $post_id ) . '&action=edit';
            wp_redirect( $url );
            exit;     
        }

    }


    /*
     *  Display any errors
     */
    public function admin_notice_handler() {
        $user_id = get_current_user_id();
        $admin_notices = get_user_meta($user_id, 'admin_notices', true);
        write_log($admin_notices);
        if(!empty($admin_notices)){
            $html = '';

            if(is_wp_error($admin_notices[0])){

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
            'default',   // priority
            array(
                'file' => 'event-players' 
            ) // optional array of args to pass to callback
        );

    }

    public function zap_metaboxes() {
        #        remove_meta_box('postimagediv','sp_event','side');
        #        remove_meta_box('sp_videodiv','sp_event','side');
        #        remove_meta_box('sp_modediv','sp_event','side');
        #        remove_meta_box('sp_formatdiv','sp_event','side');
        #        remove_meta_box('sp_shortcodediv','sp_event','side');
        #        remove_meta_box('postexcerpt','sp_event','normal');
        #        remove_meta_box('slugdiv','sp_event','normal');
        #        remove_post_type_support('sp_event','editor');

        remove_meta_box('postimagediv','sp_player','side');
        remove_meta_box('pageparentdiv','sp_player','side');
        remove_meta_box('sp_shortcodediv','sp_player','side');
        remove_meta_box('postexcerpt','sp_player','normal');
        remove_meta_box('slugdiv','sp_player','normal');
        remove_post_type_support('sp_player','editor');

        remove_meta_box('sp_staffdiv','sp_team','normal');
        remove_meta_box('sp_listsdiv','sp_team','normal');
        remove_meta_box('pageparentdiv','sp_team','side');
        remove_meta_box('postexcerpt','sp_team','normal');
        remove_meta_box('slugdiv','sp_team','normal');
        remove_post_type_support('sp_team','editor');
    }

    /**
     * Check each nonce. If any don't verify, $nonce_check is increased.
     * If all nonces verify, returns 0.
     */
    private function check_nonces( $posted ) {
        write_log ("Should not be here 1");
        $nonces 		= array();
        $nonce_check 	= 0;
        $nonces[] 		= 'report_header';
        $nonces[] 		= 'report_hometeam'; // TODO add the rest
        $nonces[] 		= 'report_awayteam';
        foreach ( $nonces as $nonce ) {
            if ( ! isset( $posted[$nonce] ) ) { $nonce_check++; }
            if ( isset( $posted[$nonce] ) && ! wp_verify_nonce( $posted[$nonce], $this->plugin_name ) ) { $nonce_check++; }
        }
        return $nonce_check;

    }

    /**
     * Calls a metabox file specified in the add_meta_box args.
     */
    public function metabox( $post, $params ) {
        write_log (["admin/class-croquet-match-report-admin-metaboxes", $post->ID, $post->post_type, $params]);
        if ( ! is_admin() ) { return; } // TODO - replace by better line
        if ( ! empty( $params['args']['classes'] ) ) {
            $classes = 'repeater ' . $params['args']['classes'];
        }
        include( plugin_dir_path( __FILE__ ) . 'partials/croquet-match-report-admin-metabox-' . $params['args']['file'] . '.php' );
    } // metabox()

    private function sanitizer( $type, $data ) {
        write_log ("Should not be here 4");
        if ( empty( $type ) ) { return; }
        if ( empty( $data ) ) { return; }
        $return 	= '';
        $sanitizer 	= new Croquet_Match_Report_Sanitize();
        $sanitizer->set_data( $data );
        $sanitizer->set_type( $type );
        $return = $sanitizer->clean();
        unset( $sanitizer );
        return $return;
    }

    /**
     * Saves metabox data
     *
     * Repeater section works like this:
     *  	Loops through meta fields
     *  		Loops through submitted data
     *  		Sanitizes each field into $clean array
     *   	Gets max of $clean to use in FOR loop
     *   	FOR loops through $clean, adding each value to $new_value as an array
     */
    public function validate_meta_report($post_id, $object) {
        write_log ("Should not be here 7");
        global $post;
        write_log(["Validate_meta for admin/class-croquet-match-report-admin-metaboxes", $post]);
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
        if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id; 
        if ( strpos($object->post_type,"cmr_") != 0 ) return $post_id;

        $nonce_check = $this->check_nonces( $_POST );
        write_log(['Nonce check - admin/class-croquet-match-report-admin-metaboxes', $nonce_check]);
        if ( 0 < $nonce_check ) { return $post_id; }

        $metas = $this->get_metabox_fields();
        write_log(['Metabox fields', $metas]);
        foreach ( $metas as $meta ) {
            $name = $meta[0];
            $type = $meta[1];
            if ( 'repeater' === $type && is_array( $meta[2] ) ) {
                $clean = array();
                foreach ( $meta[2] as $field ) {
                    foreach ( $_POST[$field[0]] as $data ) {
                        if ( empty( $data ) ) { continue; }
                        $clean[$field[0]][] = $this->sanitizer( $field[1], $data );
                    }
                } 
                $count 		= croquet_match_report_get_max( $clean );
                $new_value 	= array();
                for ( $i = 0; $i < $count; $i++ ) {

                    foreach ( $clean as $field_name => $field ) {

                        $new_value[$i][$field_name] = $field[$i];

                    } // foreach $clean
                }
            } else {
                write_log("Not a repeater - type, name and post are:");
                write_log($type);
                write_log($name);
                write_log($_POST);
                write_log($_POST[$name]);
                $new_value = $this->sanitizer( $type, $_POST[$name] );
            } 
            write_log(["About to update", $post_id, $name, $new_value]);
            update_post_meta( $post_id, $name, $new_value );
        }
    }
}
