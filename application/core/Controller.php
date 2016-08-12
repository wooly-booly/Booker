<?php

abstract class Controller
{
    protected $view = null;
    protected $layout = 'template';
    protected $conf = null;

    public function __construct()
    {
        $this->view = new View($this->layout, get_class($this));
        $this->conf = Config::instance();

        $this->view->set('boardrooms', $this->conf->get('boardrooms_number'));
    }

    public function redirect($address)
    {
        $uri = $this->conf->get('base_uri').'/'.$address;

        header("Location:{$uri}");
        exit();
    }

    abstract public function action_index();
}
