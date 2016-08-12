<?php

class Config
{
    private $_conf = array();
    private static $_instance;

    private function __clone() {}
    private function __construct() {}

    public static function instance()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function set($key, $val)
    {
        $this->_conf[$key] = $val;
    }

    public function get($key)
    {
        return $this->_conf[$key];
    }
}
