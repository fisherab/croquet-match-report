<?php

/**
 * Display the set of players and their handicaps
 */
$code = $this->get_league_slug($post->ID);
$meta = get_post_meta($post->ID);

echo "<table>";
echo "<tr><th>Home team player</th><th>Score</th><th></th><th>Away team player</th><th>Score</th></tr>";
for ($i = 0; $i < 4; $i++) {
    echo "<tr><td>";
    $atts = array();


    $atts['id'] = 'Home-Player-' . strval($i);
    $atts['blank']          = '';
    $atts['class']          = 'widefat';
    $atts['context']        = '';
    $atts['description']    = '';
    $atts['name']           = $this->plugin_name . '-options[' . $atts['id'] . ']';
    $atts['value']          = '';







    $atts['selections'] = ["A","B","C"];
    write_log($atts);
    include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-select.php' );  
    echo "</td></tr>";
}

echo "</table>";


