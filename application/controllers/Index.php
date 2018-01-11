<?php

class IndexController extends BaseController
{
    public function indexAction()
    {
        $this->assignLayout('title', '商户接入');
    }
}