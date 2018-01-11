<?php

class BaseController extends Controller
{
    protected $config = array();

    public function __construct()
    {
        $this->config = include(_ROOT . '/config/config.php');
    }


    /**
     * 生成签名
     * @param $data
     * @param $key
     * @return string
     */
    public function sign_create($data, $key)
    {
        ksort($data);
        $buff = array();
        foreach ($data as $k => $v) {
            if ($v !== '' and !is_null($v) and $k !== 'sign' and !is_array($v)) $buff[] = "{$k}={$v}";
        }
        $str = implode('&', $buff);
        return md5("{$str}&key={$key}");
    }

    /**
     * 检查签名
     * @param $data
     * @param $key
     * @return bool
     */
    public function sign_check($data, $key)
    {
        if (empty($data) or !isset($data['sign'])) return false;
        $md5 = $this->sign_create($data, $key);
        if (version_compare(PHP_VERSION, '5.6.0', '>=')) {
            return hash_equals(strtolower($md5), strtolower($data['sign']));
        } else {
            return strtolower($md5) === strtolower($data['sign']);
        }
    }

}