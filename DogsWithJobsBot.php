<?php

/*
Plugin Name: Dogs With Jobs Bot
Description: Twitter Bot that pulls tweets from r/dogswithjobs subreddit
Version: 1.0.0
Author: Donny Chen
Author URI: https://donnychen.dev
 */

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// include __DIR__ . '/debugging.php';
require __DIR__ . '/DogsWithJobsBot/vendor/autoload.php';

spl_autoload_register(function ($class_name) {
    if (strpos($class_name, 'DogsWithJobsBot') == false) {
        $file_path      = str_replace("\\", "/", $class_name);
        $file_name = stream_resolve_include_path($file_path . ".php");
        if ($file_name !== false) {
            include $file_name;
        }
    }
});

