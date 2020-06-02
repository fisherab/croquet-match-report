<?php

/**
 * Display the set of players and their handicaps
 */
$code = $this->get_league_slug($post->ID);
$meta = get_post_meta($post->ID);
if (array_key_exists('sp_team', $meta) AND array_key_exists('sp_player', $meta)) {
    $team_ids = $meta['sp_team'];
    if ($team_ids[0] > 0 AND $team_ids[1] > 0) {
        $player_ids = $meta['sp_player']; 
        echo "<table>";
        $teamnum = 0;
        foreach ($player_ids as $player_id) {
            if (0 == $player_id) {
                if ($teamnum != 0) {
                    echo "<tr><th colspan=2></th></tr>";
                }
                $team_id = $team_ids[$teamnum++];
                $team_name = get_post($team_id)->post_title;
                echo "<tr><th colspan=2>" . $team_name . "</th></tr>";
                echo "<tr><th>Player</th><th>Hcap</th></tr>";
            } else {
                $player_info = $this->get_player_info($player_id, $code);
                echo "<tr><td>" . $player_info['name'] . "</td><td>" . $player_info['hcap'] . "</td></tr>"; 
            }
        }
        echo "</table>";
    }
}


