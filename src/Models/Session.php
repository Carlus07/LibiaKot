<?php

namespace Models;

class Session {

    public static function get($key) {
        if (static::exist($key)) {
            return $_SESSION[$key];
        }
        return false;
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    public static function push($key,$value){
        $trimDir = trim(static::get($key));
        if(!empty($trimDir)) $_SESSION[$key] = [];
        array_push($_SESSION[$key],$value);
    }

    public static function remove($key) {
        unset($_SESSION[$key]);
    }

    public static function exist($key) {
        if (isset($_SESSION[$key])) {
            return true;
        }
        return false;
    }
}
