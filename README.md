
接口示例说明：
==============================
PHP 5 >= 5.4

Demo说明
==============================
- 本示例程序需要PHP5.4以上版本
- 程序结构为一个极简化的MVC框架
```
.
├── application
│   ├── controllers     控制器目录
│   └── views           视图目录
├── config
│   └── config.php      配置文件
├── kernel              框架
├── public  
│   ├── index.php       入口
│   └── resource        静态文件，js/css等
└── README.md
```

路由规则：
------------------------
例URL：http://domain/pay/call
- 控制器为`/application/controllers/Pay.php`
- 方法为：`PayController->callAction()`
- 视图文件为：`/application/views/pay/call.php`

【路由\控制器\方法\视图】规则：在控制器方法中
- 若`return array;`，则结果以`json`格式显示，并且`Content-type: application/json`；
- 若`return string;`，则只打印该字符串；
- 若返回null值（`return;`或`return null;`或无`return`），则会显示视图文件，若没有此视图文件则会出错；


虚拟主机URL改写设置：
-------------------------
Nginx:
```
location / {
    if (!-e $request_filename) {
       rewrite ^/(.+)$ /index.php/$1 last;
    }
}
```
