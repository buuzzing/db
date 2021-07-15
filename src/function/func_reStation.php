<?php
// filename: function/func_reStation.php
include "function.php";
checkIfSetCookie();

setcookie('station', $_POST['station'], time() + 3600, '/');
echo "<script>url='../pages/Re_station.php'; window.location.href=url;</script>";
