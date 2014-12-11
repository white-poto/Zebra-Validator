<?php
/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 14-10-27
 * Time: 上午9:41
 */

namespace Jenner\Zebra\Validator;


class Rule {

    protected $callback;

    public function extend($callback_name, $callback){
        $this->callback[$callback_name] = $callback;
    }

    public function required_var($data, $field){
        if(isset($data[$field]) && !is_null($data[$field]) && !empty($data[$field])){
            return true;
        }
        return false;
    }

    public function integer_var($data, $field){
        return filter_var($data[$field], FILTER_VALIDATE_INT, ['options'=>['default'=>false]]);
    }

    public function float_var($data, $field){
        return filter_var($data[$field], FILTER_VALIDATE_FLOAT, ['options'=>['default'=>false]]);
    }

    public function alpha_var($data, $field){
        return filter_var($data[$field], FILTER_VALIDATE_REGEXP, ['options'=>['default'=>false, 'regexp'=>'/^[a-zA-Z]+$/']]);
    }

    public function alpha_num_var($data, $field){
        return filter_var($data[$field], FILTER_VALIDATE_REGEXP, ['options'=>['default'=>false, 'regexp'=>'/^[a-zA-Z0-9]+$/']]);
    }

    public function array_var($data, $field){
        return is_array($data[$field]);
    }

    public function between_var($data, $field, $min, $max=false){
        $max = ($max===false) ? $max : false;

        if(is_int($data[$field]) || is_float($data[$field])){
            return $data[$field] >= $min && ($max===false ? true : $data[$field] <= $max);
        }

        $len = strlen($data[$field]);
        return $len >= $min && ($max==false ? true : $data[$field] <= $max);
    }

    public function date_var($data, $field){
        return strtotime($data[$field]);
    }

    public function date_format_var($data, $field, $format){
        $result = date_parse_from_format($format, $data[$field]);
        return count($result['errors'])===0;
    }

    //全部为数字
    public function digits_var($data, $field){
        return filter_var($data[$field], FILTER_VALIDATE_REGEXP, ['options'=>['default'=>false, 'regexp'=>'/^[0-9]+$/']]);
    }

    public function digits_between_var($data, $field, $min, $max){
        $len = strlen($data[$field]);
        return $len >= $min && $len <= $max;
    }

    public function boolean_var($data, $field){
        if(is_bool($data[$field])) return true;
        return in_array($data[$field], [0, 1, '0', '1']);
    }

    public function email_var($data, $field){
        return filter_var($data[$field], FILTER_VALIDATE_EMAIL, ['options'=>['default'=>false]]);
    }

    public function in_var($data, $filed){
        $ins = func_get_args();
        unset($ins[0], $ins[1]);
        return in_array($data[$filed], $ins);
    }

    public function max_var($data, $field, $max){
        if(is_int($data[$field]) || is_float($data[$field])){
            return $data[$field] <= $max;
        }
        return strlen($data[$field]<=$max);
    }

    public function min_var($data, $field, $min){
        if(is_int($data[$field]) || is_float($data[$field])){
            return $data[$field] <= $min;
        }
        return strlen($data[$field]<=$min);
    }

    public function same_var($data, $field, $same){
        return $data[$field] === $same;
    }

    public function url_var($data, $field){
        return filter_var($data[$field], FILTER_VALIDATE_URL, ['options'=>['default'=>false]]);
    }

    public function ip_var($data, $field){
        return filter_var($data[$field], FILTER_VALIDATE_IP, ['options'=>['default'=>false], 'flags'=>FILTER_FLAG_IPV4]);
    }

    public function regexp_var($data, $field, $pattern){
        return preg_match($pattern, $data[$field]);
    }

    public function chinese_var($data, $field){
        return preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $data[$field]);
    }

    public function chinese_alpha_var($data, $field){
        return preg_match('/^[a-zA-Z\x{4e00}-\x{9fa5}]+$/u', $data[$field]);
    }

    public function chinese_alpha_num_var($data, $field){
        return preg_match('/^[a-zA-Z0-9\x{4e00}-\x{9fa5}]+$/u', $data[$field]);
    }


} 