<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/15/17
 * Time: 11:25 AM
 */


/**
 * @param $value
 * @return string
 * @desc Escapes string values
 */
function sanitize($value)
{
    return htmlentities($value, ENT_QUOTES, 'UTF-8');
}