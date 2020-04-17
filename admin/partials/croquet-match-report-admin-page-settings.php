<?php

/**
 * Provide a admin area view for the plugin
 * This file is used to markup the admin-facing aspects of the plugin.
 */

?><h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
<form method="post" action="options.php"><?php

settings_fields( $this->croquet_match_report . '-options' );


do_settings_sections( $this->croquet_match_report );

submit_button( 'Save Settings' );

?></form>
