<?php
// filename: function/func_reTicket.php
include "function.php";
checkIfSetCookie();

$departureDate = $_POST['departureDate'];
setcookie('departurePlace', $_POST['departurePlace'], time() + 3600, '/');
setcookie('destination', $_POST['destination'], time() + 3600, '/');
setcookie('pur_done', '', time() - 3600, '/');
switch ($departureDate) {
    case 'option1':
        setcookie('departureDate', 0, time() + 3600, '/');
        break;
    case 'option2':
        setcookie('departureDate', 1, time() + 3600, '/');
        break;
    case 'option3':
        setcookie('departureDate', 2, time() + 3600, '/');
        break;
}
echo "<script>url='../pages/Re_tickets.php'; window.location.href=url;</script>";
