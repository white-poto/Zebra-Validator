<?php
/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 14-10-27
 * Time: 上午9:41
 */

namespace Jenner\Zebra\Validator;


class Message {

    protected $message;

    protected static $message_array;

    public function __contruct(){
        $this->message = '';
    }

    public function setMessage($key, $rule, $message_array=null){
        if(isset($message_array[$key . '.' . $rule]) && !empty($message_array[$key . '.' . $rule])) {
            $this->message[$key][$rule] = $message_array[$key . '.' . $rule];
            return true;
        }

        $message_array = self::initMessage();
        if(!isset($message_array[$rule]) || empty($message_array[$rule])){
            throw new \Exception('default validate message not found.please check the validate.php file. rule:' . $rule);
        }
        $message = $message_array[$rule];
        $message = str_replace(':attribute', $key, $message);
        $this->message[$key][$rule] = $message;
    }

    public function failed(){
        return !$this->pass();
    }

    public function pass(){
        return empty($this->message);
    }

    public function messages(){
        return $this->message;
    }

    public function first($key){
        return current($this->message[$key]);
    }

    protected static function initMessage(){
        if(empty(self::$message_array) || !is_array(self::$message_array)){
            self::$message_array = require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'validate.php';
        }
        return self::$message_array;
    }
} 