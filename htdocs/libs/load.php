<?php
//TODO: Implement autoload of class files
include_once 'includes/Session.class.php';
include_once 'includes/User.class.php';
include_once 'includes/Database.class.php';
include_once 'includes/UserSession.class.php';
include_once 'includes/WebAPI.class.php';
include_once 'app/Post.class.php';
include_once 'includes/Device.class.php';
include_once 'includes/ApiKey.class.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

global $__site_config;
/*
Note: Location of configuration
in lab : /home/user/phtogramconfig.json
in server: /var/www/photogramconfig.json
*/


$wapi = new WebAPI();
$wapi->initiateSession();

function get_config($key, $default=null)
{
    global $__site_config;
    $array = json_decode($__site_config, true);
    if (isset($array[$key])) {
        return $array[$key];
    } else {
        return $default;
    }
}

function load_template($name)
{
    include $_SERVER['DOCUMENT_ROOT'] . get_config('base_path'). "_templates/$name.php"; //not consistant.
}

