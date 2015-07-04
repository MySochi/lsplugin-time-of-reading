<?php

$config = array();

// Activated functions
$config['function'] = array(
    'read_time' => true, // calc. time to reading
    'watch_time' => true, // calc. time to watch
);

// Hooks to show a info (without 'template_')
$config['hooks'] = array(
    'topic_show_info',
);

// Full deinstall: drop the column `time_of_reading` in table `prefix_topic`
$config['full_deinstall'] = false;

// Calculate when plugin first time is activate
$config['calculate_when_activate'] = false;

/*
 * Reading settings
 */

// Speed (char/min.)
$config['speed'] = 1200; // Default: 1200

// If use_delta: n*speed + delta == n*speed + speed
$config['use_delta'] = true; // Default: true
$config['delta'] = 200; // Default: 200

// how many seconds is "instantaneous reading"
$config['read_instantly_limit'] = 30;

// how many seconds is "nothing reading"
$config['read_nothing_limit'] = 15;

/*
 * Watch settings
 */

// how many seconds is "instantaneous watching"
$config['watch_instantly_limit'] = 45;

// how many seconds is "nothing watching"
$config['watch_nothing_limit'] = 20;

$config['video_api'] = array(
    'youtube' => 'AIzaSyDuwNRZvuGXr0o5eSiRLUUt8h16a8Uwjgc', // YouTube API Key
);

return $config;
