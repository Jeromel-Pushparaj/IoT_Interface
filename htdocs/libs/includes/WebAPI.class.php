<?php
// include_once 'Database.class.php';
class WebAPI
{
    public function __construct()
    {

        if (php_sapi_name() == "cli") {
            global $__site_config;
            $__site_config_path = "/var/www/html/first_app/projects/photogramconfig.json";
            $__site_config = file_get_contents($__site_config_path);
        } elseif(php_sapi_name() == "apache2handler") {
            global $__site_config;
            // $__site_config_path = dirname(is_link($_SERVER['DOCUMENT_ROOT']) ? readlink($_SERVER['DOCUMENT_ROOT']) : $_SERVER['DOCUMENT_ROOT']).'/../projects/photogramconfig.json';
            $__site_config_path = __DIR__."/../../../projects/photogramconfig.json";
            $__site_config = file_get_contents($__site_config_path);

        }
        Database::getConnection();
    }

    public function initiateSession()
    {
        Session::start();
        if (Session::isset("session_token")) {
            try {
                Session::$usersession = UserSession::authorize(Session::get('session_token')); 
            } 
            catch (Exception $e){
                //TODO: Handle error
            }
        }

        // $__base_path = get_config('base_path');
    }
}
