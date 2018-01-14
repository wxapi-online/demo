<?php

/**
 * 是否手机访问
 * 0=PC端
 * 1=手机
 * @return bool
 */
function is_wap()
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
function ip()
{
    foreach (['X-REAL-IP', 'X-FORWARDED-FOR', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP', 'REMOTE_ADDR'] as $k) {
        if (isset($_SERVER[$k]) && !empty($ip = $_SERVER[$k])) {
            if (strpos($ip, ',')) $ip = trim(explode(',', $ip)[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) break;
        }
    }
    return isset($ip) ? $ip : '127.0.0.1';
}

/**
 * 跳转
 * @param $url
 */
function redirect($url)
{
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() - 100) . ' GMT');
    header("Cache-Control: no-cache");
    header("Pragma: no-cache");
    header("Location: {$url}");
    exit;
}
