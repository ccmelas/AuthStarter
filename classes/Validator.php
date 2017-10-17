<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/15/17
 * Time: 5:03 PM
 */

class Validator
{
    private $passed = false, $errors = [], $db = null;

    /**
     * Validator constructor.
     */
    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function validate($request, $fields = array()) {
        foreach ($fields as $field => $rules) {
            $rules = explode('|', $rules);
            foreach ($rules as $rule) {
                $rule = explode(':', $rule);
                $value = trim(sanitize($request[$field]));
                if ($rule[0] === 'required' && empty($value)) {
                    $this->addError(
                        ucwords(str_replace('_', ' ', $field))
                        . " is required"
                    );
                } elseif (!empty($value)) {
                    switch ($rule[0]) {
                        case 'min':
                            if(strlen($value) < (int)$rule[1]) {
                                $this->addError(
                                    ucwords(str_replace('_', ' ', $field))
                                    . " must be a miniumum of {$rule[1]} characters"
                                );
                            }
                            break;
                        case 'max':
                            if(strlen($value) > (int)$rule[1]) {
                                $this->addError(
                                    ucwords(str_replace('_', ' ', $field))
                                    . " must be a maximum of {$rule[1]} characters"
                                );
                            }
                            break;
                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError(
                                    ucwords(str_replace('_', ' ', $field))
                                    . " must be an Email"
                                );
                            }
                            break;
                        case 'unique':
                            $check = $this->db->get($rule[1], array($field, '=', $value));
                            if($check->count()) {
                                $this->addError(
                                    ucwords(str_replace('_', ' ', $field))
                                    . " already exists");
                            }
                            break;
                        case 'matches':
                            if ($value !== $request[$rule[1]]) {
                                $this->addError(
                                    ucwords(str_replace('_', ' ', $field))
                                    . " must match " .
                                    ucwords(str_replace('_', ' ', $rule[1]))
                                );
                            }
                            break;
                    }
                }
            }
        }
        if(empty($this->errors)) {
            $this->passed = true;
        }
        return $this;
    }

    public function passed() {
        return $this->passed;
    }

    private function addError($error) {
        array_push($this->errors, $error);
    }

    public function errors() {
        return $this->errors;
    }
}