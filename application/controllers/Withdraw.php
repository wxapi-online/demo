<?php

class WithdrawController extends BaseController
{
    public function indexAction()
    {
        $this->assign('width', $this->is_wap() ? '99%' : '1000px');

    }
}