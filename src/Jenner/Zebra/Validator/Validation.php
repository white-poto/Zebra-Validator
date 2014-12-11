<?php
/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 14-10-25
 * Time: 下午5:40
 */

namespace Jenner\Zebra\Validator;


class Validation {

    protected static $rule_model;

    protected $validate_message = [];

    protected $success;

    protected $message;

    public static function setRuleModel($model){
         self::$rule_model = $model;
    }

    protected function __construct(){
        $this->success = false;
    }

    public static function make($data, $rules, $message_array=null, $custom_attributes=null){
        $rule_model = self::initRuleModel();
        $message_model = new Message();

        foreach($rules as $key=>$rule){
            $rule_array = explode('|', $rule);
            if(in_array('required', $rule_array)){
                $val_result = call_user_func_array([$rule_model, 'required_var'], [$data, $key]);
                if($val_result === false){
                    $message_model->setMessage($key, 'required', $message_array, $custom_attributes);
                    continue;
                }
            }

            foreach($rule_array as $ro){
                $params = [$data, $key];
                $method = $ro;
                if(strstr($ro, ':')){
                    $method = substr($ro, 0, strpos($ro, ':'));
                    $rule_params = explode(',', substr($ro, strpos($ro, ':')+1));
                    foreach($rule_params as $po) $params[] = $po;
                }
                if(empty($data[$key])) continue;
                $val_result = call_user_func_array([$rule_model, $method . '_var'], $params);
                if($val_result === false){
                    $message_model->setMessage($key, $method, $message_array, $custom_attributes);
                }
            }
        }
        return $message_model;
    }

    public static function initRuleModel(){
        if(empty(self::$rule_model)|| !is_object(self::$rule_model)){
            self::$rule_model = new Rule();
        }
        return self::$rule_model;
    }
}


