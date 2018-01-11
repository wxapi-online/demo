<?php

class QueryController extends BaseController
{
    public function indexAction()
    {
        $this->assignLayout('menu', 'query');
        $this->assignLayout('title', '订单查询、代付查询');
    }

    public function checkAction()
    {
        $data = array();
        $data['appid'] = $this->config['appid'];
        $data['type'] = 'order';
        $data['billno'] = isset($_POST['billno']) ? $_POST['billno'] : '';

        if (!$data['appid']) $this->error('必须输入商户应用APPID');
        if (!$data['billno']) $this->error('必须输入要查询的订单号');
        $data['sign'] = $this->sign_create($data, $this->config['token']);

        $json = $this->post($this->config['api']['query'], json_encode($data, 256));

        //TODO 通道返回的数据
        $array = json_decode($json, true);

        //基本数据格式判断
        if (!is_array($array) or !isset($array['success'])) {
            $this->error("通道方返回数据无法解析成数组：{$json}");
        }

        //TODO 检查服务器发来的数据是否合法，若发生此情况，请立即通知通道方核查原因
        if (!$this->sign_check($array, $this->config['token'])) {
            $this->error("通道方返回数据签名认证失败");
        }

        if (intval($array['error']) !== 0) {
            $this->error("通道方返回错误信息：{$array['message']}");
        }

        $this->assign('value', $array);

    }
}