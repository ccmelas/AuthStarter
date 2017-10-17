<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/15/17
 * Time: 8:57 PM
 */
require_once 'bootstrap/init.php';
$user = new User();
$user->logout();
Redirect::to('index.php');