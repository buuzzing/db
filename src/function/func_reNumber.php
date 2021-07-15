<?php
// filename: function/func_reNumber.php
include "function.php";
checkIfSetCookie();

setcookie('number', $_POST['number'], time() + 3600, '/');
echo "<script>url='../pages/Re_number.php'; window.location.href=url;</script>";
