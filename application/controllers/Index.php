<?php

class IndexController extends BaseController
{
    public function indexAction()
    {
        $this->redirect('/pay');
    }
}