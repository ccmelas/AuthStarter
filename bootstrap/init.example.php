<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/15/17
 * Time: 11:21 AM
 */

session_start();

/**
 * stores config values
 */
$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => 'database host',
        'username' => 'database username',
        'password' => '*****',
        'db' => 'database name'
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    )
);

/** autoloads classes */
spl_autoload_register(function ($class) {
   require_once 'classes/' . $class . '.php';
});

/** loads the sanitize.php file */
require_once 'helpers/sanitize.php';

/** Check Login - Remember me */
if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    $hash = Cookie::get('remember/cookie_name');
    $hashCheck = DB::getInstance()->get('user_sessions', array('hash', '=', $hash));
    if ($hashCheck->count()) {
        //log user in

    }
}
