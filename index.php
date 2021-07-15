<?php
// filename: index.php
include "src/function/function.php";
if (isset($_COOKIE['ID'])) {
    echo "<script>url='src/pages/home.php'; window.location.href=url</script>";
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <?php makeCSS("车站售票管理系统", "/static/icon/train.png"); ?>
    <!-- Custom styles for this template -->
    <link href="/static/css/signin.css"
          rel="stylesheet">
</head>
<body>
<div class="container">
    <form class="form-signin" method="post" action="src/function/login.php">
        <div class="text-center" style="margin-bottom: 20px; color: purple">
            <h1 class="form-signin-heading">请先登录</h1>
        </div>
        <label for="inputUserID" class="sr-only">用户名</label>
        <input type="text" id="inputUserID" name="inputUserID" class="form-control" placeholder="用户名" required
               autofocus style="margin-bottom: 5px">
        <label for="inputPassword" class="sr-only">密码</label>
        <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="密码"
               required style="margin-bottom: 7px">
        <button class="btn btn-lg btn-primary btn-block" type="submit">登录</button>
    </form>
</div> <!-- /container -->

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="/static/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
