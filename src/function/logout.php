<?php
// filename: function/logout.php
include "function.php";
checkIfSetCookie();

//setcookie('ID', '', time() - 3600, '/');
//setcookie('username', '', time() - 3600, '/');
//
//setcookie('departurePlace', '', time() - 3600, '/');
//setcookie('destination', '', time() - 3600, '/');
//setcookie('departureDate', '', time() - 3600, '/');
//
//setcookie('number', '', time() - 3600, '/');
//setcookie('station', '', time() - 3600, '/');
//
//setcookie('selectMethod', '', time() - 3600, '/');
//setcookie('selectInfo', '', time() - 3600, '/');
//
//setCookie('pur_trainNumber', '', time() - 3600, '/');
//setCookie('pur_departureDate', '', time() - 3600, '/');
//setCookie('pur_departureStation', '', time() - 3600, '/');
//setCookie('pur_terminus', '', time() - 3600, '/');
//setCookie('put_done', '', time() - 3600, '/');

foreach ($_COOKIE as $key => $value) {
    setcookie($key, '', time() - 3600, '/');
}

echo "<script>url='../../index.php'; window.location.href=url;</script>";
