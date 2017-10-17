<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/15/17
 * Time: 4:42 PM
 */
require_once 'bootstrap/init.php';

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validator = new Validator();
        $validation = $validator->validate($_POST, array(
            'username' => 'required|min:6|max:20|unique:users',
            'name' => 'required|min:6',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|matches:password'
        ));

        if($validation->passed()) {
            $user = new User();
            try{
                $salt = Hash::salt(30);
                $user->create(array(
                    'username' => sanitize(Input::get('username')),
                    'name' => sanitize(Input::get('name')),
                    'email' => sanitize(Input::get('email')),
                    'password' => Hash::make(sanitize(Input::get('password')), $salt),
                    'salt' => $salt,
                    'created_at' => date('Y-m-d H:i:s')
                ));
                Session::flash('success', 'You registered successfully');
                header('Location: index.php');
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    } else {
        echo 'Session timed out. Please try again';
    }
}
?>
<form action="" method="post">
    <div class="field">
        <label for="username">Username</label>
        <input name="username" value="<?php echo sanitize(Input::get('username')); ?>" id="username" autocomplete="off">
    </div>
    <div class="field">
        <label for="name">Name</label>
        <input name="name" value="<?php echo sanitize(Input::get('name')); ?>" id="name" autocomplete="off">
    </div>
    <div class="field">
        <label for="email">Email</label>
        <input type="email" name="email" value="<?php echo sanitize(Input::get('email')); ?>" id="email" autocomplete="off">
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" value="" id="password" autocomplete="off">
    </div>
    <div class="field">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation" value="" id="password_confirmation" autocomplete="off">
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <div class="field">
        <input type="submit" name="register" value="register">
    </div>
</form>
