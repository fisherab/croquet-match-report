<?php

/**
 * The metabox-specific functionality of the plugin.
 . TODO needs checking
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
		$this->set_meta();
	}

	/**
	 * Registers metaboxes with WordPress
	 */
	public function add_metaboxes() {

		add_meta_box(
			'croquet_match_report_report_header',  //Box id
        	apply_filters( $this->plugin_name . '-metabox-title-requirements', esc_html__( 'Header', 'croquet-match-report' ) ), // Box title
			array( $this, 'metabox' ), // Callback
            array("cmr_ac_a_level","cmr_ac_b_level","cmr_ac_handicap"), // post types to add the metabox
        	'normal',    // context - i.e. where it should go
			'default',   // priority
			array(
				'file' => 'report-header' 
			) // optional array of args to pass to callback
		);

		add_meta_box(
			'croquet_match_report_report_hometeam',  //Box id
        	apply_filters( $this->plugin_name . '-metabox-title-requirements', esc_html__( 'Home Team (name, handicap and x if seen)', 'croquet-match-report' ) ), // Box title
			array( $this, 'metabox' ), // Callback
            array("cmr_ac_a_level","cmr_ac_b_level","cmr_ac_handicap"), // post types to add the metabox
        	'normal',    // context - i.e. where it should go
			'default',   // priority
			array(
				'file' => 'report-hometeam' 
			) // optional array of args to pass to callback
		);

		add_meta_box(
			'croquet_match_report_report_awayteam',  //Box id
        	apply_filters( $this->plugin_name . '-metabox-title-requirements', esc_html__( 'Away Team (name, handicap and x is seen', 'croquet-match-report' ) ), // Box title
			array( $this, 'metabox' ), // Callback
            array("cmr_ac_a_level","cmr_ac_b_level","cmr_ac_handicap"), // post types to add the metabox
        	'normal',    // context - i.e. where it should go
			'default',   // priority
			array(
				'file' => 'report-awayteam' 
			) // optional array of args to pass to callback
		);

		write_log("Added the metaboxes");
	}

	/**
	 * Check each nonce. If any don't verify, $nonce_check is increased.
	 * If all nonces verify, returns 0.
	 */
	private function check_nonces( $posted ) {
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

	} // check_nonces()

	/**
	 * Returns an array of the all the metabox fields and their respective types
	 */
	private function get_metabox_fields() {
		$fields = array();
		$fields[] = array( 'report-venue', 'text' );
		$fields[] = array( 'report-season', 'text' );
 		$fields[] = array( 'report-hometeam', 'text' );
 		$fields[] = array( 'report-awayteam', 'text' );
        $fields[] = array( 'report-home1', 'text' );
        $fields[] = array( 'report-home2', 'text' );
        $fields[] = array( 'report-home3', 'text' );
        $fields[] = array( 'report-home4', 'text' );
        $fields[] = array( 'report-away1', 'text' );
        $fields[] = array( 'report-away2', 'text' );
        $fields[] = array( 'report-away3', 'text' );
        $fields[] = array( 'report-away4', 'text' );
   
		// $fields[] = array( 'job-responsibilities', 'textarea' );
		// $fields[] = array( 'file-repeater', 'repeater', array( array( 'label-file', 'text' ), array( 'url-file', 'url' ) ) );
		return $fields;
	} 

	/**
	 * Calls a metabox file specified in the add_meta_box args.
	 */
	public function metabox( $post, $params ) {
		if ( ! is_admin() ) { return; }
		if ( ! empty( $params['args']['classes'] ) ) {
			$classes = 'repeater ' . $params['args']['classes'];
		}
		include( plugin_dir_path( __FILE__ ) . 'partials/croquet-match-report-admin-metabox-' . $params['args']['file'] . '.php' );
	} // metabox()

	private function sanitizer( $type, $data ) {
		if ( empty( $type ) ) { return; }
		if ( empty( $data ) ) { return; }
		$return 	= '';
		$sanitizer 	= new Croquet_Match_Report_Sanitize();
		$sanitizer->set_data( $data );
		$sanitizer->set_type( $type );
		$return = $sanitizer->clean();
		unset( $sanitizer );
		return $return;
	} // sanitizer()

	/**
	 * Saves button order when buttons are sorted.
	 */
	public function save_files_order() {

		check_ajax_referer( 'croquet-match-report-file-order-nonce', 'fileordernonce' );

		$order 						= $this->meta['file-order'];
		$new_order 					= implode( ',', $_POST['file-order'] );
		$this->meta['file-order'] 	= $new_order;
		$update 					= update_post_meta( 'file-order', $new_order );

		esc_html_e( 'File order saved.', 'croquet-match-report' );

		die();

	} // save_files_order()

	/**
	 * Sets the class variable $options
	 */
	public function set_meta() {
		global $post;
		write_log("Entered set_meta");
        if (empty($post)) return;
 		if (strpos($post->post_type,"crm_") != 0 ) return;
        write_log("Post type and id " . $post->post_type);
        write_log($post->ID);
		$this->meta = get_post_custom( $post->ID );
        write_log($this->meta);
	} // set_meta()

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
	public function validate_meta( $post_id, $object ) {

		write_log("Validate_meta for");
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
		if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id; 
		if ( strpos($object->post_type,"cmr_") != 0 ) return $post_id;

        write_log("Check those nonces");
		$nonce_check = $this->check_nonces( $_POST );
        write_log($nonce_check);
		if ( 0 < $nonce_check ) { return $post_id; }

		$metas = $this->get_metabox_fields();
		write_log($metas);
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
	} // validate_meta()

} // class
