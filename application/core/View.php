<?php

class View
{
    private $template;
    private $controller;
    private $layout;
    private $vars = array();

    public function __construct($layout, $controllerName)
    {
        $this->layout = $layout;
        $arr = explode('_', $controllerName);
        $this->controller = strtolower($arr[1]);
    }

    public function set($varname, $value)
    {
        if (isset($this->vars[$varname])) {
            $this->erroorHappen('Unable to set var `'.$varname
                .'`. Already set, and overwrite not allowed.');

            return false;
        }

        $this->vars[$varname] = $value;

        return true;
    }

    public function show($name)
    {
        $layout = APP_PATH.'views'.DS.'layouts'.DS
                    .$this->layout.'.php';
        $template = APP_PATH.'views'.DS.$this->controller.DS
                    .$name.'.php';

        if (!file_exists($layout)) {
            $this->erroorHappen('Layout `'.$this->layout.'` does not exist.');

            return false;
        }

        if (!file_exists($template)) {
            $this->erroorHappen('Template `'.$name.'` does not exist.');

            return false;
        }

        foreach ($this->vars as $key => $value) {
            $$key = $value;
        }

        require_once $layout;
    }

    public function erroorHappen($msg)
    {
        throw new Exception($msg);
    }
}
