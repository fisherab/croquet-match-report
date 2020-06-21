<?php

/**
 * Provides a static validation function
 */

class Croquet_Match_Report_Validator {

    public static function check($type, $data) {
        switch ( $type ) {
        case 'integer'			: return $data === intval( $data ); 
        case 'text'				: return $data === sanitize_text_field( $data );
        case 'editor' 			: return $data === wp_kses_post( $data );
        case 'email'			: return $data === sanitize_email( $data );
        case 'file'				: return $data === sanitize_file_name( $data );
        case 'phone'			: if (empty($data)) return FALSE; 
        return preg_match( '/^[+]?([0-9]?)[(|s|-|.]?([0-9]{3})[)|s|-|.]*([0-9]{3})[s|-|.]*([0-9]{4})$/', $data );
        case 'textarea'			: return $data === esc_textarea( $data );
        case 'url'				: return $data === esc_url( $data );
        return false;
        } 
    }
}
