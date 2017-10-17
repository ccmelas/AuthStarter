<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/15/17
 * Time: 11:20 AM
 */

require_once 'bootstrap/init.php';

//DB::getInstance()->insert('users', array(
//   'name' => 'firstname',
//    'username' => 'melas',
//    'email' => 'chiemelachinedum@ymail.com',
//    'password' => 'secret',
//    'salt' => 'secret',
//    'created_at' => date('Y-m-d H:i:s')
//));
//DB::getInstance()->update('users', 1, array(
//    'name' => 'updatedname',
//));
//if (!DB::getInstance()->error()) {
//    echo 'Success';
//} else {
//    echo 'Failure';
//}

if (Session::exists('success')) {
    echo Session::flash('success');
}
$user = new User();
if($user->isLoggedIn()) {
    ?>
    <p>Hello <a href=""><?php echo sanitize($user->data()->username); ?></a>!</p>
    <ul>
        <li><a href="logout.php">Log out</a></li>
    </ul>
<?php } else {?>
    <p>You need to <a href="login.php">Login</a> or <a href="register.php">Register</a></p>
<?php } ?>