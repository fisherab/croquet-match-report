<?php
/**
 * The template for displaying a signle report contents
 */


write_log("single-report-post-content.php entered");
//$post = get_post();
$pt = $post->post_type;
$league = ['cmr_ac_a_level' => 'AC A Level League', 'cmr_ac_b_level' => 'AC B Level League', 'cmr_ac_handicap' => 'AC Handicap League'][$pt];
$meta = get_post_custom( $post->ID );
$tit = $league . ': ' . $meta['report-hometeam'][0] . ' vs ' . $meta['report-awayteam'][0] . ' at ' . $meta['report-venue'][0] . ' on ' . date_format(new DateTime($post->post_date),'d/M/Y'); 
write_log($tit);
?><div class="wrap-report"><?php

?><h1 classs="<?php echo esc_attr( 'report-title' ); ?>"><?php echo html_entity_decode( $tit ); ?></h1><?php


?></div><?php

write_log("single-report-post-content-php done"); //TODO to be continued

