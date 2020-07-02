<?php

/**
 * Display the set of individual games and their results
 */
$meta = get_post_meta($post->ID);
if (array_key_exists('event_games', $meta)) {
    $event_games = unserialize($meta['event_games'][0]);
} 

/*
 * Get the lists of home and of away players
 */
if (array_key_exists('sp_player', $meta)) {
    # Each team's player ids are preceded by a zero
    $player_ids = array_slice($meta['sp_player'],1);
    $zero = array_search(0, $player_ids);
    $home = array_slice($player_ids, 0, $zero);
    $away = array_slice($player_ids, $zero+1);
}

echo "<table>";
echo "<tr><th>Home team player</th><th>Score</th><th></th><th>Away team player</th><th>Score</th></tr>";
for ($i = 0; $i < $this->get_max_game_count(); $i++) {
    if (isset($event_games)) $event_game = $event_games[$i];
    echo "<tr><td>";
    $atts = array();
    $atts['id'] = 'Home-Player-' . strval($i);
    $atts['class']          = 'widefat';
    $atts['description']    = '';
    $atts['name']           = $this->plugin_name . '-event-games-home-player[' . $atts['id'] . ']';
    if (isset($event_game)) {
        $atts['value'] = $event_game[0];
    } else {
        $atts['value']          = '';
    }
    $atts['selections'] = [];
    foreach ($home as $p) $atts['selections'][] = get_post($p)->post_title;
    include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-select.php' );  
    echo "</td><td>";

    $atts = array();
    $atts['id'] = 'Home-Score-' . strval($i);
    $atts['class']          = 'widefat';
    $atts['description']    = '';
    $atts['name']           = $this->plugin_name . '-event-games-home-score[' . $atts['id'] . ']';
    if (isset($event_game)) {
        $atts['value'] = $event_game[1];
    } else {
        $atts['value']          = '';
    }
    include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );  
    echo "</td><td>vs.</td><td>";

    $atts = array();
    $atts['id'] = 'Away-Player-' . strval($i);
    $atts['class']          = 'widefat';
    $atts['description']    = '';
    $atts['name']           = $this->plugin_name . '-event-games-away-player[' . $atts['id'] . ']';
    if (isset($event_game)) {
        $atts['value'] = $event_game[2];
    } else {
        $atts['value']          = '';
    }
    $atts['selections'] = [];
    foreach ($away as $p) $atts['selections'][] = get_post($p)->post_title;
    include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-select.php' );  
    echo "</td><td>";

    $atts = array();
    $atts['id'] = 'Home-Score-' . strval($i);
    $atts['class']          = 'widefat';
    $atts['description']    = '';
    $atts['name']           = $this->plugin_name . '-event-games-away-score[' . $atts['id'] . ']';
    if (isset($event_game)) {
        $atts['value'] = $event_game[3];
    } else {
        $atts['value']          = '';
    }
    include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );  
    echo "</td></tr>";
}

echo "</table>";


