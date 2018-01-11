<?php

abstract class Controller
{
    protected $_data = array();

    public function error($html)
    {
        echo '<!DOCTYPE html><html lang="zh-cn"><head>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
        echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>';
        exit("</htad><body><div>{$html}</div></body></html>");
    }

    /**
     * 向通道发送数据
     * @param $url
     * @param $data
     * @return mixed|string
     */
    public function post($url, $data)
    {
        if (($cURL = curl_init()) === false) $this->error('Create Protocol Object Error');
        if (is_array($data)) $data = json_encode($data, 256);

        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_POST, true);
        curl_setopt($cURL, CURLOPT_HEADER, false);
        curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($cURL, CURLOPT_DNS_CACHE_TIMEOUT, 120);
        curl_setopt($cURL, CURLOPT_TIMEOUT, CURLOPT_TIMEVALUE);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        if (strtoupper(substr($url, 0, 5)) === "HTTPS") {
            curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($cURL, CURLOPT_SSL_VERIFYHOST, 2);
        } else {
            curl_setopt($cURL, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_NONE);
        }
        curl_setopt($cURL, CURLOPT_HTTPHEADER, array("X-HTTP-Method-Override: POST"));//设置HTTP头信息
        curl_setopt($cURL, CURLOPT_POSTFIELDS, $data);
        if (($value = curl_exec($cURL)) === false) return curl_error($cURL);
        $err = curl_error($cURL);
        if (!empty($err)) return $err;
        curl_close($cURL);
        return $value;
    }

    /**
     * 是否手机访问
     * 0=PC端
     * 1=手机
     * @return bool
     */
    public function is_wap()
    {
        $browser = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if (empty($browser)) return 1;

        $uaKey = ['MicroMessenger', 'android', 'mobile', 'iphone', 'ipad', 'ipod', 'opera mini', 'windows ce', 'windows mobile', 'symbianos', 'ucweb', 'netfront'];
        foreach ($uaKey as $i => $k) if (stripos($browser, $k)) return 1;

        $mobKey = ['Noki', 'Eric', 'WapI', 'MC21', 'AUR ', 'R380', 'UP.B', 'WinW', 'UPG1', 'upsi', 'QWAP', 'Jigs', 'Java', 'Alca', 'MITS', 'MOT-', 'My S', 'WAPJ', 'fetc', 'ALAV', 'Wapa', 'Oper'];
        if (in_array(substr($browser, 0, 4), $mobKey)) return 1;

        $isWap = ['HTTP_X_WAP_PROFILE', 'HTTP_UA_OS', 'HTTP_VIA', 'HTTP_X_NOKIA_CONNECTION_MODE', 'HTTP_X_UP_CALLING_LINE_ID'];
        foreach ($isWap as $i => $k) if (isset($_SERVER[$k])) return 1;

        if (isset($_SERVER['HTTP_ACCEPT']) and stripos($_SERVER['HTTP_ACCEPT'], 'vnd.wap')) return 1;

        return 0;
    }

    /**
     * 客户端IP
     * @return string
     */
    public function ip()
    {
        $keys = ['X-REAL-IP', 'X-FORWARDED-FOR', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP', 'REMOTE_ADDR'];
        $ip = null;
        foreach ($keys as $header) {
            if (isset($_SERVER[$header]) && !empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                break;
            }
        }
        if (is_null($ip)) $ip = '127.0.0.1';
        if (strpos($ip, ',')) $ip = explode(',', $ip)[0];
        return $ip;
    }

    /**
     * 跳转
     * @param $url
     */
    public function redirect($url)
    {
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() - 100) . ' GMT');
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        header("Location: {$url}");
        exit;
    }


    public function assign($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->_data[$k] = $v;
            }
        } else {
            $this->_data[$key] = $value;
        }
    }

    public function __set($key, $value = null)
    {
        $this->assign($key, $value);
    }

    public function __get($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : null;
    }

    public function data()
    {
        return $this->_data;
    }

}
