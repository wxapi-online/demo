<?php

class WithdrawController extends BaseController
{
    public function indexAction()
    {
        $this->assignLayout('menu', 'withdraw');
        $this->assignLayout('title', '单笔代付');
    }
}