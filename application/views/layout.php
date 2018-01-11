<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?= (isset($title) ? $title : '') ?>-DEMO</title>
    <link rel="stylesheet" href="/resource/css/auto.css?1" media="all">
    <link rel="stylesheet" href="/resource/css/public.css?01" media="all">
</head>
<body>

<ul class="menu radius">
    <li><a href="/?c=pay" <?= (isset($menu) and $menu === 'pay') ? ' class="active"' : '' ?>>支付测试</a></li>
    <li><a href="/?c=query" <?= (isset($menu) and $menu === 'query') ? ' class="active"' : '' ?>>订单查询</a></li>
    <li><a href="/?c=withdraw" <?= (isset($menu) and $menu === 'withdraw') ? ' class="active"' : '' ?>>单笔代付</a></li>
</ul>

<?= $_view_html; ?>
</body>
</html>
