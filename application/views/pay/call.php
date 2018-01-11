<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/resource/css/auto.css?1" media="all">
    <link rel="stylesheet" href="/resource/css/public.css?0" media="all">
    <title>DemoPay</title>
    <style>
        div.code {
            width: 300px;
            height: 300px;
            padding: 1px;
            border: 1px solid #aaa;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<div class="code">
    <img src="<?= $code_url ?>">
</div>

<script>
    var tim = setInterval(function () {
        var url = '<?="/pay/check/{$order_id}"?>';
        var obj = new XMLHttpRequest();  // XMLHttpRequest对象用于在后台与服务器交换数据
        obj.open('GET', url, true);
        obj.onreadystatechange = function () {
            if (obj.status == 200) {
                if (obj.responseText == 'ok') {
                    top.location.href = '/pay/ok';
                }
            }
        };
        obj.send();
    }, 2000);

</script>

</body>
</html>