<?php

class Controller_Index extends Controller
{
    public function action_index()
    {
        $this->redirect('booker/index/b=1');
        $this->view->show('index');
    }
}
