<style>

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
        width: 7em;
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
        width: 12em;
        height: 2em;
        border: 1px solid #888;
        color: #888;
        font-size: 1.3em;
    }

    input[type=submit] {
        width: 10em;
        height: 3em;
        border: 1px solid #888;
    }

    input[type=submit]:hover {
        background: #0A7189;
        color: #ffffff;
    }

</style>

<div class="body">
    <form class="pay" action="/withdraw/send" method="post" autocomplete="off">
        <div class="head">单笔代付</div>
        <table style="width: 100%;">
            <tr style="height:3em;">
                <td></td>
            </tr>
            <tr>
                <td>金额</td>
                <td>
                    <input type="tel" name="billno" value="" placeholder="订单号" autocomplete="off">
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <input type="submit" value="查询">
                </td>
            </tr>

        </table>
    </form>
</div>
