<?php
// filename: function/func_refundTicket.php
include "function.php";
checkIfSetCookie();

if (isset($_COOKIE['refundID'])) {
    $OrderID = $_COOKIE['refundID'];
    setcookie('refundID', '', time() - 60, '/');
    $sql="
update ticketsystem.order_info
set Order_status=2
where Order_ID = '$OrderID';
";
    getResult($sql);
    echo "<script>url='../pages/myOrders.php'; window.location.href=url;</script>";
}
