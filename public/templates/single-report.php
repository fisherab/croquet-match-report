<?php
/**
 * The template for displaying all single report posts.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly
write_log ("single-report.php starting");

/**
 * Get a custom header-employee.php file, if it exists.
 * Otherwise, get default header.
 */
get_header( 'report' );

if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		include croquet_match_report_get_template( 'single-content' );
	endwhile;
endif;

get_footer( 'report' );

write_log ("single-report.php completed");
