<?php

class AcceptController extends BaseController
{


    /**
     * 侦听支付异步回调通知
     * 数据处理完成后打印success
     * 若订单已处理过，也要打印success
     */
    public function pay_notifyAction()
    {
        $post = file_get_contents("php://input");
        if (empty($post)) exit;
        $array = json_decode($post, true);
        if (!is_array($array)) exit('data_fail');

        //签名检查
        if (!$this->sign_check($array, $this->config['token'])) exit('sign_error');

        //系统出错
        if (intval($array['error']) !== 0) exit('error_success');

        //支付失败
        if (intval($array['state']) !== 1) exit('fail_success');


        //存入数据库
        $file = dirname(__FILE__) . "/data/{$array['order_id']}.log";
        $data = file_get_contents($file);
        if (!$data) exit('order_fail');//没有此订单
        $order = unserialize($data);

        //已收到通知，直接停止
        if ($order['pay'] === 1) exit('success');


        //支付成功，并写入已入款标识
        $order['pay'] = 1;
        file_put_contents($file, serialize($order));

        //TODO 最后必须打印success
        exit('success');
    }

    /**
     * 受理支付成功后的页面跳转
     * 只需要做基本判断并显示支付成功或失败即可
     */
    public function page_noticeAction()
    {
        $value = $_GET;
        if (empty($value)) exit;

        if (intval($value['error']) !== 0) $this->error("通道方返回的错误信息:" . $value['message']);

        $amount = isset($_GET['amount']) ? $_GET['amount'] : '0';
        $amount = intval($amount) / 100;

        $this->assign('amount', $amount);

    }


}