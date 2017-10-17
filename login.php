<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/15/17
 * Time: 7:46 PM
 */
require_once 'bootstrap/init.php';

if (Input::exists())
{
    if(Token::check(Input::get('token'))){
       $validtor = new Validator();
       $validation = $validtor->validate($_POST, [
           'username' => 'required',
           'password' => 'required'
       ]);

       if ($validation->passed()) {
            $user = new User();

            $remember = (Input::get('remember') === 'on') ? true : false;
            $login = $user->login(Input::get('username'), Input::get('password'), $remember);
            if($login){
                Session::flash('success', 'You have successfully logged in');
                Redirect::to('index.php');
            } else {
                echo 'An error occured. Please try again';
            }
       } else {
           foreach ($validation->errors() as $error) {
               echo $error, '<br>';
           }
       }
    }
}
?>
<form action="" method="post">
    <div class="field">
        <label for="username">Username</label>
        <input name="username" value="<?php echo sanitize(Input::get('username')); ?>" id="username" autocomplete="off">
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" value="" id="password" autocomplete="off">
    </div>
    <div class="field">
        <label for="remember">
            <input type="checkbox" name="remember" id="remember"> Remember me
        </label>
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <div class="field">
        <input type="submit" name="register" value="login">
    </div>
</form>
