<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>支付DEMO</title>
    <link rel="stylesheet" href="/resource/css/auto.css?1" media="all">
    <link rel="stylesheet" href="/resource/css/public.css?0" media="all">

    <style>

        form.pay {
            width: <?="{$width}"?>;
            background: #eee;
            margin: 0 auto;
        }

        td:first-child {
            width: 100px;
            text-align: center;
        }

        tr, td {
            height: 2em;
        }

        div.head {
            width: 100%;
            height: 3em;
            line-height: 3em;
            font-size: 20px;
            text-align: center;
            background: #3f4e68;
            color: #fff;
        }

        label {
            display: inline-block;
            height: 2em;
            width: 12em;
            float: left;
            margin-top: 1.2em;
        }

        img {
            width: 1.2em;
            height: 1.2em;
            margin-top: 0.5em;
        }

        input[type=radio] {
            width: 1.5em;
            height: 1.5em;
        }

        input[type=tel] {
            width: 8em;
            height: 2em;
            border: 1px solid #888;
            color: #888;
            font-size: 1.3em;
        }

        input[type=submit] {
            width: 10em;
            height: 3em;
            border: 1px solid #888;
            background: #1e9d0d;
            color: #fff;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
        }

        input[type=submit]:hover {
            background: #067201;
            color: #ffffff;
            cursor: hand;
        }

    </style>
</head>
<body>
<ul class="menu radius">
    <li><a href="/pay" class="active">支付测试</a></li>
    <li><a href="/query">订单查询</a></li>
    <li><a href="/withdraw">单笔代付</a></li>
</ul>

<div class="body">
    <form class="pay" action="/pay/call" method="post" autocomplete="off">
        <div class="head">支付DEMO</div>
        <table style="width:100%;">
            <tr style="height:0.3em;">
                <td></td>
            </tr>
            <tr>
                <td>支付金额</td>
                <td>
                    <input type="tel" name="amount" value="1" placeholder="请输入支付金额" autocomplete="off">
                </td>
            </tr>
            <tr style="background: #d0e9c6;width:100%;">
                <td>支付方式</td>
                <td>
                    <?php
                    $pay_type = array();
                    $pay_type[1011] = '支付宝扫码';
                    $pay_type[1012] = '支付宝WAP';
                    $pay_type[1021] = '微信扫码';
                    $pay_type[1022] = '微信WAP';
                    $pay_type[1031] = 'QQ钱包扫码';
                    $pay_type[1032] = 'QQ钱包WAP';
                    $pay_type[1041] = '京东扫码';
                    $pay_type[1042] = '京东WAP';
                    $pay_type[1051] = '云闪付扫码';
                    $pay_type[1052] = '中国银联快捷';
                    $pay_type[1053] = '中国银联网关';
                    $pay_type[1083] = '商业银行网关';

                    foreach ($pay_type as $id => $title) {
                        echo "<label><input type='radio' name='pay_type' value='{$id}'><span>{$id}.{$title}</span></label>";
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <td></td>
                <td style="height:5em;">
                    <input type="submit" value="立即支付">
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>