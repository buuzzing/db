<?php
// filename: function/login.php

$ID = $_POST['inputUserID'];
$pw = $_POST['inputPassword'];
$pw = md5($pw);
include 'function.php';
$sql = "select User_name, User_password from ticketsystem.user_info where User_ID='$ID'";
$result = getResult($sql);
if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('用户不存在');</script>"
        . "<script>url='../../index.php';window.location.href=url</script>";
} else {
    $row = mysqli_fetch_assoc($result);
    if ($pw != $row["User_password"]) {
        echo "<script>alert('密码错误');</script>"
            . "<script>url='../../index.php';window.location.href=url</script>";
    } else {
        setcookie('ID', $ID, time() + 3600, '/');
        setcookie('username', $row["User_name"], time() + 3600, '/');
        echo "<script>alert('登录成功！');</script>"
            . "<script>url='../pages/home.php'; window.location.href=url</script>";
    }
}
