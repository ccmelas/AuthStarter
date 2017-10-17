<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/15/17
 * Time: 4:50 PM
 */

class Input
{
    public static function exists($type = 'post')
    {
        switch ($type){
            case 'post':
                return (!empty($_POST));
                break;
            case 'get':
                return (!empty($_GET));
                break;
            default:
                return false;
                break;
        }
    }

    public static function get($item)
    {
        if(isset($_POST[$item])) {
            return $_POST[$item];
        } else if(isset($_GET[$item])) {
            return $_GET[$item];
        } else {
            return '';
        }
    }
}