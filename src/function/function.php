<?php
// filename: function/function.php
date_default_timezone_set('PRC');

function getResult($sql)
{
    $servername = "localhost:3306";
    $dbname = "ticketsystem";
    $username = "root";
    $password = "12345678";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    return $result;
}

// 处理时间间隔，Tag 标记表示第几天
function calInterval($startTime, $startTimeTag, $endTime, $endTimeTag)
{
    $t1 = strtotime($startTime);
    $t1 += (int)$startTimeTag * 24 * 3600;
    $t2 = strtotime($endTime);
    $t2 += (int)$endTimeTag * 24 * 3600;
    $interval = $t2 - $t1;
    $hour = floor((int)$interval / 3600);
    $minute = floor(((int)$interval % 3600) / 60);
    if ($hour < 10) $interval = "0" . $hour;
    else $interval = (string)$hour;
    $interval .= ":";
    if ($minute < 10) $interval .= ("0" . $minute);
    else $interval .= $minute;
    return $interval;
}

// 删除过期订单
function delOrders()
{
    $sql = "
update ticketsystem.order_info
set Order_status = 3
where Departure_time < now() and Order_status = 1;
";
    getResult($sql);
}

function checkIfSetCookie()
{
    if (!isset($_COOKIE['ID']))
        echo "<script>url='../../index.php'; window.location.href=url;</script>";
}

function makeCSS($title, $icon)
{
    echo "
    <meta charset=\"utf-8\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->";
    if (isset($icon))
        echo "<link rel=\"icon\" href=\"$icon\">";
    echo "
    <title>$title</title>

    <!-- Bootstrap core CSS -->
    <link href=\"/static/css/bootstrap.min.css\" rel=\"stylesheet\">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href=\"/static/css/ie10-viewport-bug-workaround.css\" rel=\"stylesheet\">          

    <!-- Custom styles for this template -->
    <link href=\"/static/css/dashboard.css\" rel=\"stylesheet\">     
";
}

function makeHeader()
{
    echo '
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/src/pages/home.php">车站售票管理系统</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/src/pages/home.php">主页</a></li>
                    <li><a href="/src/pages/myProfile.php">我的信息</a></li>
                    <li><a href="#">帮助</a></li>
                    <li><a href="/src/function/logout.php">注销</a></li>
                </ul>
            </div>
        </div>
    </nav>';
}

function makeFooter()
{
    echo "
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src=\"/static/js/jquery.min.js\"></script>
    <script src=\"/static/js/bootstrap.min.js\"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src=\"/static/js/ie10-viewport-bug-workaround.js\"></script>
";
}

function makeNavigationBar($where)
{
    echo '
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">';
    if ($where == 1)
        echo '<li class="active"><a href="#">主页 <span class="sr-only">(current)</span></a></li>';
    else
        echo '<li><a href="/src/pages/home.php">主页 <span class="sr-only">(current)</span></a></li>';
    echo '</ul>
        <ul class="nav nav-sidebar">';
//    if ($where == 2)
//        echo '<li class="active"><a href="">站站查询</a></li>';
//    else
//        echo '<li><a href="">站站查询</a></li>';
    if ($where == 3)
        echo '<li class="active"><a href="#">车次查询</a></li>';
    else
        echo '<li><a href="/src/pages/Re_number.php">车次查询</a></li>';
    if ($where == 4)
        echo '<li class="active"><a href="#">车站查询</a></li>';
    else
        echo '<li><a href="/src/pages/Re_station.php">车站查询</a></li>';
    echo '</ul>
        <ul class="nav nav-sidebar">';
    if ($where == 5)
        echo '<li class="active"><a href="#">订车票 / 余票查询</a></li>';
    else
        echo '<li><a href="/src/pages/Re_tickets.php">订车票 / 余票查询</a></li>';
//    if ($where == 6)
//        echo '<li class="active"><a href=""></a></li>';
//    else
//        echo '<li><a href="">订车票</a></li>';
    if ($where == 7)
        echo '<li class="active"><a href="#">我的订单</a></li>';
    else
        echo '<li><a href="/src/pages/myOrders.php">我的订单</a></li>';
    if ($where == 8)
        echo '<li class="active"><a href="#">我的信息</a></li>';
    else
        echo '<li><a href="/src/pages/myProfile.php">我的信息</a></li>';
    echo '</ul>
    </div>';
}

function makeRemainTicketInfo($totSeatInfo, $usedSeatInfo, $seatType)
{
    if (array_key_exists($seatType, $totSeatInfo)) {
        if (isset($usedSeatInfo[$seatType]))
            $tmp = (int)$totSeatInfo[$seatType] - (int)$usedSeatInfo[$seatType];
        else $tmp = (int)$totSeatInfo[$seatType];
        echo "<th>$tmp</th>";
    } else echo "<th><br>-</th>";
}

function makePurchaseTicketInfo($totSeatInfo, $usedSeatInfo, $seatType, $pur_trainNumber, $pur_departureDate, $pur_departureStation, $pur_terminus)
{
    if (array_key_exists($seatType, $totSeatInfo)) {
        if (isset($usedSeatInfo[$seatType]))
            $tmp = (int)$totSeatInfo[$seatType] - (int)$usedSeatInfo[$seatType];
        else $tmp = (int)$totSeatInfo[$seatType];
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
        $Run_mileage = (int)$row['Run_mileage'];
        $row = mysqli_fetch_assoc($result);
        $Run_mileage = (int)$row['Run_mileage'] - $Run_mileage;
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
        switch ($seatType) {
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
        $price = number_format($price, 1, '.', '');
        echo "<th>$tmp<br>￥ $price<br><a href='javascript:void(0)' 
onclick=\"purchase('$seatType','$pur_trainNumber','$pur_departureDate','$pur_departureStation','$pur_terminus')\" 
class='btn btn-primary'>购买</a></th>";
    } else echo "<th>-</th>";
}

// 返回值为一查询指定车次、出发、到达站、乘车时间内的已卖出的车票座位类型和对应数目的SQL语句
function getRemainingTicketInquiry($Train_number, $departurePlace, $destination, $departureDate)
{
    return <<<"QUIRY"
select Seat_type, count(Departure_Number) as USE_Number
from ((select Number_of_stops as Re_Departure_Number
       from ticketsystem.train_timetable as B
       where Train_number = '$Train_number'
         and City = '$departurePlace') as Departure,
      (select Number_of_stops as Re_Terminus_Number
       from ticketsystem.train_timetable
       where Train_number = '$Train_number'
         and City = '$destination') as Terminus,
      (select Departure_Number, Terminus_Number, Number_of_cars, Departure_time
       from ticketsystem.order_info
       where Train_number = '$Train_number'
         and Order_status = 1) as Orders,
      (select Seat_type, Number_of_cars, Seat_number
       from ticketsystem.train_seat_info
       where Train_number = '$Train_number') as Seat)
where Orders.Number_of_cars = Seat.Number_of_cars
  and date_format(Orders.Departure_time, '%Y-%m-%d') = '$departureDate'
  and (not (Terminus_Number <= Re_Departure_Number or Departure_Number >= Re_Terminus_Number))
group by Seat_type;
QUIRY;
}
