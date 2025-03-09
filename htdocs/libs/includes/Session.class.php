<?php

use MongoDB\Driver\Session as DriverSession;
//TODO: Checkout Why Session is not been deleted or always active
class Session
{
    public static $isError = false;
    public static $user = null;
    public static $usersession = null;
    public static $auth_id = null;
    public static function start()
    {
        session_start();
    }

    public static function unset()
    {
        session_unset();
    }
    public static function destroy()
    {
        session_destroy();
        /*
        If UserSession is active, set it to inactive.
        */
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function delete($key)
    {
        unset($_SESSION[$key]);
    }

    public static function isset($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function get($key, $default=false)
    {
        if (Session::isset($key)) {
            return $_SESSION[$key];
        } else {
            return $default;
        }
    }

    public static function getUser()
    {
        return Session::$user;
    }
    // public static function getauthid()
    // {
    //     return Session::$auth_id;
    // }

    public static function getUserSession(){
        return Session::$usersession;
    }


    public static function loadTemplate($name)
    {
        $script = $_SERVER['DOCUMENT_ROOT'] . get_config('base_path'). "_templates/$name.php";
        if (is_file($script)) {
            include $script;
        } else {
            Session::loadTemplate('_error');
        }
    }

    public static function renderPage()
    {
        Session::loadTemplate('_master');
    }

    public static function currentScript()
    {
        return basename($_SERVER['SCRIPT_NAME'], '.php');
    }

    public static function isAuthenticated()
    {
        //TODO: Is it a correct implementation?
        if(is_object(Session::getUserSession())){
            return Session::getUserSession()->isValid();
        } 
        return false;
    }

    public static function ensureLogin(){
        if(!Session::isAuthenticated()){
            Session::set('_redirect', $_SERVER['REQUEST_URI']);
            header("Location: /login");
            die();
        }
    }
}
