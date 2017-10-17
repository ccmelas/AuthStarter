<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/15/17
 * Time: 6:55 PM
 */

class Redirect
{
    public static function to($location = null)
    {
        if($location) {
            if(is_numeric($location)) {
                switch ($location) {
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        include 'includes/errors/404.php';
                        exit();
                        break;
                }
            }
            header('Location: ' . $location);
            exit();
        }
    }
}