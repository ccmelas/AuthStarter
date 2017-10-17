<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/15/17
 * Time: 6:34 PM
 */

class User
{
    private $db;

    private $table = 'users', $user_sessions_table = 'user_sessions',
        $data, $sessionName, $cookieName, $isLoggedIn = false;

    public function __construct($user = null)
    {
        $this->db = DB::getInstance();
        $this->sessionName = Config::get('session/session_name');
        $this->cookieName = Config::get('remember/cookie_name');
        if(!$user) {
            if (Session::exists($this->sessionName)) {
                $user = Session::get($this->sessionName);
                if($this->find($user)) {
                    $this->isLoggedIn = true;
                } else {
                    //process logout
                }
            }
        } else {
            $this->find($user);
        }
    }

    public function create(array $fields = array())
    {
        if(!$this->db->insert($this->table, $fields)) {
            throw new Exception('There was a problem creating your account');
        }
    }

    public function find($user = null)
    {
        if($user) {
            $field = (is_numeric($user)) ? 'id': 'username';
            $data = $this->db->get($this->table, array($field, '=', $user));

            if ($data->count()) {
                $this->data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function login($username = null, $password = null, $remember = false)
    {
        $user = $this->find($username);
        if($user) {
            if($this->data()->password === Hash::make($password, $this->data()->salt)) {
                Session::put($this->sessionName, $this->data()->id);
                if($remember) {
                    $hash = Hash::unique();
                    $hashCheck = $this->db->get($this->user_sessions_table, array(
                       'user_id', '=', $this->data()->id
                    ));

                    if(!$hashCheck->count()) {
                        $this->db->insert($this->user_sessions_table, array(
                           'user_id' => $this->data()->id,
                           'hash' => $hash
                        ));
                    } else {
                        $hash = $hashCheck->first()->hash;
                    }

                    Cookie::put($this->cookieName, $hash, Config::get('remember/cookie_expiry'));
                }
                return true;
            }
        }
        return false;
    }

    public function logout()
    {
        Session::delete($this->sessionName);
    }

    public function data()
    {
        return $this->data;
    }

    public function isLoggedIn()
    {
        return $this->isLoggedIn;
    }
}