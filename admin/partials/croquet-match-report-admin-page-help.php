<?php

/**
 * Provide an admin area view for the plugin
 * This file is used to markup the admin-facing aspects of the plugin.
 */

?><h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
<h2><?php esc_html_e( 'Shortcode', 'croquet-match-report' ); ?></h2>
<p><?php esc_html_e( 'The simplest version of the shortcode is:', 'croquet-match-report' ); ?></p>
<pre><code>[croquetmatchreport]</code></pre>

<p><?php esc_html_e( 'Enter that in the Editor on any page or post to display all the job opening posts.', 'croquet-match-report' ); ?></p>
<p><?php esc_html_e( 'This is an example with all the default attributes used:', 'croquet-match-report' ); ?></p>
<pre><code>[croquetmatchreport order="rand" quantity="10" location="Decatur"]</code></pre>

<h3><?php esc_html_e( 'Shortcode Attributes', 'croquet-match-report' ); ?></h3>
<p><?php esc_html_e( 'There are currently three attributes that can be added to the shortcode to filter job opening posts:', 'croquet-match-report' ); ?></p>
<ol>
	<li><?php esc_html_e( 'order', 'croquet-match-report' ); ?></li>
	<li><?php esc_html_e( 'quantity', 'croquet-match-report' ); ?></li>
	<li><?php esc_html_e( 'location', 'croquet-match-report' ); ?></li>
</ol>
<h4><?php esc_html_e( 'order', 'croquet-match-report' ); ?></h4>
<p><?php printf( wp_kses( __( 'Changes the display order of the job opening posts. Default value is "date", but can use any of <a href="%1$s">the "orderby" parameters for WP_Query</a>.', 'croquet-match-report' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( 'https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters' ) ); ?></p>
<p><?php esc_html_e( 'Examples of the order attribute:', 'croquet-match-report' ); ?></p>
<ul>
	<li><?php esc_html_e( 'order="title" (order by post title)', 'croquet-match-report' ); ?></li>
	<li><?php esc_html_e( 'order="name" (order by post slug)', 'croquet-match-report' ); ?></li>
	<li><?php esc_html_e( 'order="rand" (random order)', 'croquet-match-report' ); ?></li>
</ul>

<h4><?php esc_html_e( 'quantity', 'croquet-match-report' ); ?></h4>
<p><?php esc_html_e( 'Determines how many job opening posts are displayed. The default value is 100. Must be a positive value. To display all, use a high number.', 'croquet-match-report' ); ?></p>
<p><?php esc_html_e( 'Examples of the quantity attribute:', 'croquet-match-report' ); ?></p>
<ul>
	<li><?php esc_html_e( 'quantity="3" (only show 3 openings)', 'croquet-match-report' ); ?></li>
	<li><?php esc_html_e( 'quantity="125" (only show 125 openings)', 'croquet-match-report' ); ?></li>
	<li><?php esc_html_e( 'quantity="999" (large number to display to all openings)', 'croquet-match-report' ); ?></li>
</ul>

<h4><?php esc_html_e( 'location', 'croquet-match-report' ); ?></h4>
<p><?php esc_html_e( 'Filters job openings based on the value of the job location metabox field. The value should be the ', 'croquet-match-report' ); ?></p>
<p><?php esc_html_e( 'Examples of the location attribute:', 'croquet-match-report' ); ?></p>
<ul>
	<li><?php esc_html_e( 'location="St Louis"', 'croquet-match-report' ); ?></li>
	<li><?php esc_html_e( 'location="Decatur"', 'croquet-match-report' ); ?></li>
	<li><?php esc_html_e( 'location="Chicago"', 'croquet-match-report' ); ?></li>
</ul>

<h4><?php esc_html_e( 'WP_Query', 'croquet-match-report' ); ?></h4>
<p><?php printf( wp_kses( __( 'The shortcode will also accept any of <a href="%1$s">the parameters for WP_Query</a>.', 'croquet-match-report' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( 'https://codex.wordpress.org/Class_Reference/WP_Query' ) ); ?></p>
