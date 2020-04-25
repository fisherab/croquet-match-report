<?php
/**
 * The template for displaying a signle report contents
 */


write_log("single-report-post-content.php entered");
$meta = get_post_custom( $post->ID );
$league = $meta['report-league'][0];
$tit = $league . ': ' . $meta['report-hometeam'][0] . ' vs ' . $meta['report-awayteam'][0] . ' at ' . $meta['report-venue'][0] . ' on ' . date_format(new DateTime($post->post_date),'d/M/Y'); 
write_log($tit);
?><div class="wrap-report"><?php

?><h1 classs="<?php echo esc_attr( 'report-title' ); ?>"><?php echo html_entity_decode( $tit ); ?></h1><?php

?><p> <?php echo html_entity_decode("Wibble and wobble"); ?></p><?php

?></div><?php

write_log("single-report-post-content-php done"); //TODO to be continued

