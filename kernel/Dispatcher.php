<?php

class Dispatcher
{
    public function __construct()
    {
        if (!defined('_ROOT')) exit('网站入口处须定义 _ROOT 项，指向系统根目录');
        chdir(_ROOT);
    }

    /**
     * 框架核心
     */
    public function run()
    {
        list($controller, $action, $params) = $this->route();

        $c = $this->load('/kernel/Controller.php');
        if (!$c) exit('控制器文件/kernel/Controller.php不存在');
        $this->load(_ROOT . "/application/controllers/Base.php");

        $controller = ucfirst($controller);
        $action = strtolower($action);
        $file = (_ROOT . "/application/controllers/{$controller}.php");
        if (!$this->load($file)) exit("控制器文件{$file}不存在");

        $controlName = "{$controller}Controller";
        $actionName = "{$action}Action";
        $control = new $controlName();
        if (!($control instanceof Controller)) {
            exit("{$controlName} 须继承自 \\core\\Controller");
        }

        if (!method_exists($control, $actionName) or !is_callable([$control, $actionName])) {
            exit("控制器方法{$controlName}->{$actionName}()不存在或不可运行");
        }

        include 'Database.php';
        include 'library.php';

        //执行控制器方法
        $val = call_user_func_array([$control, $actionName], $params);

        //无返回内容，则显示视图
        if (is_null($val)) {
            $view = $control->getView();
            if ($view === true) $view = _ROOT . '/application/views/' . strtolower($controller) . '/' . $action . '.php';
            if (!is_readable($view)) exit("视图文件{$view}不存在");
            $response = $this->fetch($view, $control->data());

            $layout = $control->getLayout();
            if ($layout === true) $layout = _ROOT . '/application/views/layout.php';
            if (is_readable($layout)) {
                $data = $control->getLayoutData();
                echo $this->fetch($layout, array('_view_html' => $response) + $data);
            } else {
                echo $response;
            }

            //返回的是数组，则以json显示
        } else if (is_array($val)) {
            header('Content-type: application/json', true, 200);
            echo json_encode($val, 256);


            //返回的是字符串，则直接打印
        } else if (is_string($val)) {
            header('Content-type: text/html', true, 200);
            echo $val;
        }
        if (version_compare(PHP_VERSION, '5.3.3', '>')) @fastcgi_finish_request();
    }


    /**
     * 简化的路由匹配
     * @return array
     */
    private function route_1()
    {
        $uri = getenv('REQUEST_URI');
        $uri = substr($uri, 0, strpos($uri, '?'));
        $path = explode('/', $uri);
        $controller = $path[1] ?: 'index';
        $action = 'index';
        $params = array();
        if (isset($path[2])) $action = $path[2] ?: 'index';
        if (count($path) > 3) $params = array_slice($path, 3);
        return array($controller, $action, $params);
    }

    private function route()
    {
        $uri = substr(getenv('REQUEST_URI'), 2);
        parse_str($uri, $path);
        $controller = isset($path['c']) ? $path['c'] : 'index';
        $action = isset($path['a']) ? $path['a'] : 'index';
        $params = array();
        return array($controller, $action, $params);
    }

    /**
     * 自动加载控制器中加载的类
     * @param $path
     */
    public function autoload($path)
    {
        if (!is_array($path)) $path = array($path);
        spl_autoload_register(function ($class) use ($path) {
            $class = ucfirst($class);
            foreach ($path as $p) {
                if (include_once(_ROOT . "/{$p}/{$class}.php")) return;
            }
            die("Class {$class} is not exists!");
        });
    }

    public function load($file)
    {
        if (stripos($file, _ROOT) !== 0) $file = _ROOT . '/' . ltrim($file, '/');
        if (!$file or !is_readable($file)) return false;
        static $recode = array();
        $md5 = md5($file);
        if (isset($recode[$md5])) return $recode[$md5];
        $recode[$md5] = include $file;
        return $recode[$md5];
    }

    private function fetch($__file__, $__value__)
    {
        ob_start();
        extract($__value__);
        include $__file__;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }


}