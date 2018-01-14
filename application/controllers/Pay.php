<?php

class PayController extends BaseController
{
    public function indexAction()
    {
        $this->assignLayout('title', '统一下单支付');
        $this->assignLayout('menu', 'pay');
    }

    public function callAction()
    {
        $data = array();
        $data['appid'] = $this->config['appid'];
        $data['client_ip'] = ip();
        $data['client_mobile'] = is_wap();
        $data['client_ua'] = getenv('HTTP_USER_AGENT');

        //支付方式:
        $data['trade_type'] = isset($_POST['trade_type']) ? $_POST['trade_type'] : '';

        //商户网站自己的订单ID，请根据自己站的实际情况生成唯一订单号
        $data['out_trade_no'] = date('ymdHis') . mt_rand(1000, 9999) . mt_rand(1000, 9999);

        //支付金额，单位：人民币 分
        $data['amount'] = isset($_POST['amount']) ? intval(strval(floatval($_POST['amount']) * 100)) : 0;

        if (!$data['amount']) return $this->error('必须输入支付金额');
        if (!$data['out_trade_no']) return $this->error('必须输入商户订单唯一ID');
        if (!$data['trade_type']) return $this->error('必须指定支付方式');

        //回调URL
        $data['notify_url'] = 'http://' . getenv('HTTP_HOST') . '?c=accept&a=pay_notify';
        $data['notice_url'] = 'http://' . getenv('HTTP_HOST') . '?c=accept&a=page_notice';

        //最后组织签名
        $data['sign'] = $this->sign_create($data, $this->config['token']);

        //保存数据
        $datModel = new Database();
        $datModel->insert($data['out_trade_no'], $data + array('pay' => 0));

        //请求接口
        $json = $this->post($this->config['api']['pay'], json_encode($data, 256));

        //通道返回的数据，json格式
        $array = json_decode($json, true);

        //基本数据格式判断
        if (!is_array($array) or !isset($array['success'])) {
            return $this->error("请求得到的数据无法解析为数组：{$json}");
        }

        //检查是否有错误信息
        $error = intval($array['error']);
        if ($error !== 0) {
            return $this->error("[{$array['error']}]{$array['message']}");
        }

        //TODO 检查服务器发来的数据是否合法，若发生此情况，请立即通知通道方核查原因
        if (isset($array['sign']) and !$this->sign_check($array, $this->config['token'])) {
            return $this->error('通道返回数据，签名认证失败，请联系通道方技术员');
        }

        //若出错，则显示错误
        if (!$array['success']) {
            return $this->error(json_encode($array, 256));
        }

        //若收到数据中含有url，则直接跳入即可
        if (isset($array['url'])) redirect($array['url']);

        $this->assign($array);
    }

    public function checkAction($order)
    {
        $data = new Database();
        $value = $data->get($order);
        if (!is_array($value)) exit;

        //支付成功，请用自己的业务逻辑方式判断
        if (isset($value['pay']) and $value['pay']) {
            $val = array(
                'success' => 1,
                'jump' => '/pay/ok/' . $value['amount'],//支付成功后的跳转目标
                'time' => time(),
            );
        } else {
            $val = array('success' => 0);
        }

        return $val;
    }


    public function okAction($amount)
    {
        $this->assign('amount', intval($amount) / 100);
    }

}