<?php

$config = array();

// Full deinstall: drop the column `time_of_reading` in table `prefix_topic`
$config['full_deinstall'] = false;

// Calculate when plugin first time is activate
$config['calculate_when_activate'] = false;

//
$config['table']['time_of_reading'] = 'topic_time_of_reading';

// Speed (char/min.)
$config['speed'] = 1200; // Default: 1200

// If use_delta: n*speed + delta == n*speed + speed
$config['use_delta'] = true; // Default: true
$config['delta'] = 200; // Default: 200

return $config;
