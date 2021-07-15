<?php
// filename: function/func_purchase.php
include "function.php";
checkIfSetCookie();

$passengerID = $_COOKIE['pur_id'];
$pur_seatType = $_COOKIE['pur_seatType'];
$pur_trainNumber = $_COOKIE['pur_trainNumber'];
$pur_departureDate = $_COOKIE['pur_departureDate'];
$pur_departureStation = $_COOKIE['pur_departureStation'];
$pur_terminus = $_COOKIE['pur_terminus'];
$userID = $_COOKIE['ID'];
setcookie('pur_done', '', time() - 3600, '/');
setcookie('pur_seatType', '', time() - 3600, '/');
setcookie('pur_trainNumber', '', time() - 3600, '/');
setcookie('pur_departureDate', '', time() - 3600, '/');
setcookie('pur_departureStation', '', time() - 3600, '/');
setcookie('pur_terminus', '', time() - 3600, '/');
$OrderID = date("YmdHis");

$sql = "
select Number_of_cars
from ticketsystem.train_seat_info
where Seat_type = '$pur_seatType'
  and Train_number = '$pur_trainNumber';
";
$result = getResult($sql);
$row = mysqli_fetch_assoc($result);
$Number_of_cars = $row['Number_of_cars'];

$sql = "
select max(Number_of_seat) + 1 as seatNumber
from ticketsystem.order_info
where Train_number = '$pur_trainNumber'
  and Number_of_cars = '$Number_of_cars'
  and date_format(Departure_time, '%Y-%m-%d') = '$pur_departureDate';
";
$result = getResult($sql);
$Number_of_seat = 1;
$row = mysqli_fetch_assoc($result);
if ($row['seatNumber'] != null)
    $Number_of_seat = $row['seatNumber'];

$sql = "
select Number_of_stops, Departure_time, Run_mileage
from ticketsystem.train_timetable
where Train_number = '$pur_trainNumber'
  and (Station_name = '$pur_departureStation'
    or Station_name = '$pur_terminus')
order by Number_of_stops;
";
$result = getResult($sql);
$row = mysqli_fetch_assoc($result);
$pur_departureNumber = $row['Number_of_stops'];
$Run_mileage = (int)$row['Run_mileage'];
$startTime = $row['Departure_time'];
$startTime = $pur_departureDate . " " . $startTime;
$row = mysqli_fetch_assoc($result);
$pur_arrivalNumber = $row['Number_of_stops'];
$Run_mileage = (int)$row['Run_mileage'] - $Run_mileage;

//echo "<h1>Run_mileage: $Run_mileage</h1>";

$sql = "
select Unit_price, Start_price
from ticketsystem.price_info as A,
     ticketsystem.train_info as B
where A.Train_body = B.Train_body
  and B.Train_number = '$pur_trainNumber';
";
$result = getResult($sql);
$row = mysqli_fetch_assoc($result);
$price = (double)$Run_mileage * (double)$row['Unit_price'] + (double)$row['Start_price'];

//echo "<h1>price: $price</h1>";
//echo "<h1>seatType: $pur_seatType</h1>";

$sql = "
select Passenger_type
from ticketsystem.passenger_info
where Passenger_ID = '$passengerID';
";
$result = getResult($sql);
$row = mysqli_fetch_assoc($result);
$passengerType = $row['Passenger_type'];

switch ($pur_seatType) {
    case "硬卧":
        $price *= 1.7;
        break;
    case "软卧":
        $price *= 2.4;
        break;
    case "高级软卧":
        $price *= 3;
        break;
    case "一等座":
        $price *= 2.3;
        break;
    case "商务座":
        $price *= 3.1;
        break;
}

//echo "<h1>passengerType: $passengerType</h1>";
if ($passengerType == '2')  // 学生票
    $price *= 0.8;
$price = number_format($price, 1, '.', '');

//echo "<h1>new price: $price</h1>";

$now = date("Y-m-d H:i:s");
$sql = "
insert into ticketsystem.order_info
values ('$OrderID', '$pur_trainNumber', '$passengerID', '$userID', '$Number_of_cars', '$Number_of_seat', '$startTime',
        '$pur_departureStation', '$pur_departureNumber', '$pur_terminus', '$pur_arrivalNumber', '$now', '1', '$price');
";
//echo "<h1>$sql</h1>";
getResult($sql);
echo "
<script>
    alert('您的订单');
    url='../pages/myOrders.php'; 
    window.location.href=url;
</script>";
