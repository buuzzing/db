<?php
// filename: function/func_reOrders.php
include "function.php";
checkIfSetCookie();

if (isset($_POST['selectInfo'])) {
    setcookie('selectMethod', $_POST['selectMethod'], time() + 3600, '/');
    setcookie('selectInfo', $_POST['selectInfo'], time() + 3600, '/');
}
echo "<script>url='../pages/myOrders.php'; window.location.href=url;</script>";
