<?php
// filename: pages/Re_tickets.php
include "../function/function.php";
checkIfSetCookie();
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <?php makeCSS("订车票 / 余票查询", "/static/icon/ticket.png"); ?>
</head>
<script>
    function setCookie(name, value, t) {
        let date = new Date();
        date.setDate(date.getDate() + t);
        document.cookie = name + '=' + value + ';expires=' + date + ';path=/';
    }

    function sendNumber(number) {
        // console.log('number');
        setCookie('number', number, 3600);
        window.location.href = 'Re_number.php';
    }

    function sendPurchaseInfo(number, date, departureStation, terminus) {
        setCookie('pur_trainNumber', number, 3600);
        setCookie('pur_departureDate', date, 3600);
        setCookie('pur_departureStation', departureStation, 3600);
        setCookie('pur_terminus', terminus, 3600);
        setCookie('pur_done', 0, 3600);
        window.location.href = 'Re_tickets.php';
    }

    function clrPurchaseInfo() {
        setCookie('pur_done', null, -3600);
        window.location.href = 'Re_tickets.php';
    }

    function purchase(seatType, pur_trainNumber, pur_departureDate, pur_departureStation, pur_terminus) {
        let u = document.getElementsByName('P_id');
        let pur_id = null;
        for (let i = 0; i < u.length; ++i)
            if (u[i].checked)
                pur_id = u[i].getAttribute('id');
        if (pur_id == null) {
            alert('请添加乘车人');
            return;
        }
        let flag = confirm('请确认您的订单信息'
            + '\n车次号：  ' + pur_trainNumber
            + '\n乘车人：  ' + pur_id
            + '\n出发站：  ' + pur_departureStation
            + '\n到达站：  ' + pur_terminus
            + '\n乘车日期：' + pur_departureDate
            + '\n座位类型：' + seatType);
        if (flag) {
            setCookie('pur_seatType', seatType, 3600);
            setCookie('pur_trainNumber', pur_trainNumber, 3600);
            setCookie('pur_departureDate', pur_departureDate, 3600);
            setCookie('pur_departureStation', pur_departureStation, 3600);
            setCookie('pur_terminus', pur_terminus, 3600);
            setCookie('pur_id', pur_id, 3600);
            window.location.href = '../function/func_purchase.php';
        }
    }
</script>
<body>
<?php makeHeader() ?>
<div class="container-fluid">
    <div class="row">
        <?php makeNavigationBar(5) ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="row">
                <div class="col-md-12 thumbnail" style="background-image: url('../../static/img/background2.jpg');">
                    <div class="text-center">
                        <form class="form-inline" style="margin: 10px 0" method="post"
                              action="../function/func_reTicket.php">
                            <div class="form-group">
                                <label for="departurePlace">出发地&nbsp</label>
                                <?php
                                echo '<input type="text" class="form-control" id="departurePlace" name="departurePlace"
                                   placeholder="出发地" ';
                                if (isset($_COOKIE['departurePlace'])) {
                                    $departurePlace = $_COOKIE['departurePlace'];
                                    echo "value=\"$departurePlace\"";
                                }
                                echo '>';
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="destination">&nbsp&nbsp&nbsp&nbsp到达地&nbsp</label>
                                <?php
                                echo '<input type="text" class="form-control" id="destination" name="destination"
                                   placeholder="到达地" ';
                                if (isset($_COOKIE['destination'])) {
                                    $destination = $_COOKIE['destination'];
                                    echo "value=\"$destination\"";
                                }
                                echo '>';
                                ?>
                            </div>
                            <label>&nbsp&nbsp&nbsp&nbsp出发日期：&nbsp</label>
                            <div class="radio">
                                <label>
                                    <?php
                                    echo '<input type="radio" name="departureDate" id="departureDate1" value="option1" ';
                                    if ((isset($_COOKIE['departureDate']) && $_COOKIE['departureDate'] == 0) || (!isset($_COOKIE['departureDate'])))
                                        echo 'checked';
                                    echo '>';
                                    echo date("Y-m-d", time())
                                    ?>
                                </label>
                            </div>
                            <span>&nbsp</span>
                            <div class="radio">
                                <label>
                                    <?php
                                    echo '<input type="radio" name="departureDate" id="departureDate2" value="option2" ';
                                    if (isset($_COOKIE['departureDate']) && $_COOKIE['departureDate'] == 1)
                                        echo 'checked';
                                    echo '>';
                                    echo date("Y-m-d", strtotime("+1 day"));
                                    ?>
                                </label>
                            </div>
                            <span>&nbsp</span>
                            <div class="radio">
                                <label>
                                    <?php
                                    echo '<input type="radio" name="departureDate" id="departureDate3" value="option3" ';
                                    if (isset($_COOKIE['departureDate']) && $_COOKIE['departureDate'] == 2)
                                        echo 'checked';
                                    echo '>';
                                    echo date("Y-m-d", strtotime("+2 day"));
                                    ?>
                                </label>
                            </div>
                            <span>&nbsp&nbsp</span>
                            <button type="submit" class="btn btn-primary" style="background-color: orange">查询</button>
                            <?php
                            if (isset($_COOKIE['pur_done'])) {
                                echo "<span>&nbsp&nbsp</span>"
                                    . "<a class='btn btn-primary' href='javascript:void(0)' onclick='clrPurchaseInfo()'"
                                    . " style='background-color: purple; color: whitesmoke'>返回</a>";
                            }
                            ?>
                        </form>
                    </div>
                </div>
                <?php
                if (!isset($_COOKIE['pur_done'])) {  // 查询余票页面
                    echo <<<"RETICKETS1"
                <div class="col-md-12 thumbnail" style="background-image: url('../../static/img/background.jpg')">
                    <table class="table table-striped" style="margin: 10px; width: 99%; height: auto;">
                        <tr>
                            <th>车次</th>
                            <th>出发站</th>
                            <th>到达站</th>
                            <th>出发时间</th>
                            <th>到达时间</th>
                            <th>历时</th>
                            <th>商务座</th>
                            <th>一等座</th>
                            <th>二等座</th>
                            <th>硬座</th>
                            <th>硬卧</th>
                            <th>软卧</th>
                            <th>高级软卧</th>
                            <th>购买</th>
                        </tr>
RETICKETS1;
                    if (isset($_COOKIE['departurePlace'])) {
                        $departurePlace = $_COOKIE['departurePlace'];
                        $destination = $_COOKIE['destination'];
                        // 余票查询
                        delOrders();    // 删除过期订单信息
                        $Ymd = date_format(date_create(), 'Y-m-d');
                        $sql = "
select A.Train_number     as Train_number,
       A.Station_name     as departureStation,
       A.Departure_time   as departureTime,
       A.Departure_remark as mark1,
       B.Station_name     as destinationStation,
       B.Arrival_time     as destinationTime,
       B.Arrival_remark   as mark2
from ticketsystem.train_timetable as A,
     ticketsystem.train_timetable as B
where A.Train_number = B.Train_number
  and A.City = '$departurePlace'
  and B.City = '$destination'
  and A.Number_of_stops < B.Number_of_stops
order by departureTime;
";
                        $result1 = getResult($sql);
                        if (mysqli_num_rows($result1)) {
                            while ($row = mysqli_fetch_assoc($result1)) {
                                echo "<tr>";
                                $tmp = $row['Train_number'];
                                echo "<th><a href='javascript:void(0)' onclick=\"sendNumber('$tmp')\">$tmp</a></th>";
                                $tmp = $row['departureStation'];
                                echo "<th>$tmp</th>";
                                $tmp = $row['destinationStation'];
                                echo "<th>$tmp</th>";
                                $tmp = date("H:i", strtotime($row['departureTime']));
                                echo "<th>$tmp</th>";
                                $tmp = date("H:i", strtotime($row['destinationTime']));
                                echo "<th>$tmp</th>";
                                // 处理时间间隔
                                $interval = calInterval($row['departureTime'], $row['mark1'], $row['destinationTime'], $row['mark2']);
                                switch ((int)$row['mark2'] - (int)$row['mark1']) {
                                    case 0:
                                        echo "<th>$interval<br>当日到达</th>";
                                        break;
                                    case 1:
                                        echo "<th>$interval<br>次日到达</th>";
                                        break;
                                    case 2:
                                        echo "<th>$interval<br>两日到达</th>";
                                        break;
                                    case 3:
                                        echo "<th>$interval<br>三日到达</th>";
                                        break;
                                }
                                $Train_number = $row['Train_number'];
                                if ($_COOKIE['departureDate'] == 0)
                                    $departureDate = date("Y-m-d", time());
                                else if ($_COOKIE['departureDate'] == 1)
                                    $departureDate = date("Y-m-d", strtotime("+1 day"));
                                else
                                    $departureDate = date("Y-m-d", strtotime("+2 day"));
                                /* 余票查询站点信息
                                ** echo "<script>console.log('departureDate: $departureDate')</script>";
                                ** echo "<script>console.log('Train_number: $Train_number')</script>";
                                ** echo "<script>console.log('departurePlace: $departurePlace')</script>";
                                ** echo "<script>console.log('destination: $destination')</script>";
                                */
                                $innerSql = getRemainingTicketInquiry($Train_number, $departurePlace, $destination, $departureDate);
                                $innerResult = getResult($innerSql);
                                $usedSeatInfo = array();
                                if (mysqli_num_rows($innerResult))
                                    while ($innerRow = mysqli_fetch_assoc($innerResult)) {
                                        $tmpType = $innerRow['Seat_type'];
                                        $tmpNumber = $innerRow['USE_Number'];
                                        $usedSeatInfo["$tmpType"] = $tmpNumber;
                                    }
                                $innerSql = "
select Seat_type, Seat_number
from ticketsystem.train_seat_info
where Train_number = '$Train_number';
";
                                $innerResult = getResult($innerSql);
                                $totSeatInfo = array();
                                if (mysqli_num_rows($innerResult))
                                    while ($innerRow = mysqli_fetch_assoc($innerResult)) {
                                        $tmpType = $innerRow['Seat_type'];
                                        $tmpNumber = $innerRow['Seat_number'];
                                        $totSeatInfo["$tmpType"] = (int)$tmpNumber;
                                    }
                                makeRemainTicketInfo($totSeatInfo, $usedSeatInfo, "商务座");
                                makeRemainTicketInfo($totSeatInfo, $usedSeatInfo, "一等座");
                                makeRemainTicketInfo($totSeatInfo, $usedSeatInfo, "二等座");
                                makeRemainTicketInfo($totSeatInfo, $usedSeatInfo, "硬座");
                                makeRemainTicketInfo($totSeatInfo, $usedSeatInfo, "硬卧");
                                makeRemainTicketInfo($totSeatInfo, $usedSeatInfo, "软卧");
                                makeRemainTicketInfo($totSeatInfo, $usedSeatInfo, "高级软卧");
                                echo "
<th>
<a class=\"btn btn-primary\" style=\"margin-left: 10px\" href=\"javascript:void(0)\" 
onclick=\" sendPurchaseInfo('$Train_number', '$departureDate', '$departurePlace', '$destination') \">
购买</a>
</th>";
                                echo "</tr>";
                            }
                        }
                    }
                    echo "</table></div>";
                } else {    // 购票页面
                    $pur_trainNumber = $_COOKIE['pur_trainNumber'];
                    $pur_departureDate = $_COOKIE['pur_departureDate'];
                    $pur_departureStation = $_COOKIE['pur_departureStation'];
                    $pur_terminus = $_COOKIE['pur_terminus'];
                    $ID = $_COOKIE['ID'];
                    $sql = "
select Passenger_ID, Passenger_name
from ticketsystem.passenger_info
where User_ID = '$ID';
";
                    $passengerInfo = getResult($sql);
                    if (!mysqli_num_rows($passengerInfo)) ;
                    echo <<<"PURCHASETICKET1"
                <div class="col-md-4 col-md-offset-1 thumbnail"
                     style="background-image: url('/static/img/background.jpg')">
                    <div class="text-center" style="padding: 10px">
                        <h2>您要购买的车次</h2>
                        <div class="list-group">
                            <div class="list-group-item" style='text-align: left'>
                                <p class="list-group-item-text"><span style='font-size: 16px; font-weight: bold'>车次号：</span>$pur_trainNumber</p>
                            </div>
                            <div class="list-group-item" style='text-align: left'>
                                <p class="list-group-item-text"><span style='font-size: 16px; font-weight: bold'>出发日期：</span>$pur_departureDate</p>
                            </div>
                            <div class="list-group-item" style='text-align: left'>
                                <p class="list-group-item-text"><span style='font-size: 16px; font-weight: bold'>出发站：</span>$pur_departureStation</p>
                            </div>
                            <div class="list-group-item" style='text-align: left'>
                                <p class="list-group-item-text"><span style='font-size: 16px; font-weight: bold'>到达站：</span>$pur_terminus</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-md-offset-2 thumbnail"
                     style="background-image: url('/static/img/background.jpg')">
                     <div class="text-center" style="padding: 10px">
                        <h2>您要添加的乘车人</h2>
                        <div class="list-group">                         
PURCHASETICKET1;
                    while ($row = mysqli_fetch_assoc($passengerInfo)) {
                        $P_id = $row['Passenger_ID'];
                        $P_name = $row['Passenger_name'];
                        echo "
                        <div class=\"list-group-item\" style='text-align: left'>
                            <label class=\"checkbox-inline\">
                            <input type='radio' id='$P_id' name='P_id'>&nbsp&nbsp$P_id&nbsp&nbsp$P_name
                            </label>
                        </div>
                        ";
                    }
                    echo <<<"PURCHASETICKET2"
                        </div>
                    </div>
                </div>
                <div class="col-md-12 thumbnail" style="background-image: url('../../static/img/background.jpg')">
                    <table class="table table-striped" style="margin: 10px; width: 99%; height: auto;">
                        <tr>
                            <th>车次</th>
                            <th>出发站</th>
                            <th>到达站</th>
                            <th>出发时间</th>
                            <th>到达时间</th>
                            <th>历时</th>
                            <th>商务座</th>
                            <th>一等座</th>
                            <th>二等座</th>
                            <th>硬座</th>
                            <th>硬卧</th>
                            <th>软卧</th>
                            <th>高级软卧</th>
                        </tr>
PURCHASETICKET2;


                    $sql = "
select A.Train_number     as Train_number,
       A.Station_name     as departureStation,
       A.Departure_time   as departureTime,
       A.Departure_remark as mark1,
       B.Station_name     as destinationStation,
       B.Arrival_time     as destinationTime,
       B.Arrival_remark   as mark2
from ticketsystem.train_timetable as A,
     ticketsystem.train_timetable as B
where A.Train_number = B.Train_number
  and A.Train_number = '$pur_trainNumber'
  and A.City = '$pur_departureStation'
  and B.City = '$pur_terminus'
  and A.Number_of_stops < B.Number_of_stops
order by departureTime;
";
                    $result = getResult($sql);
                    $row = mysqli_fetch_assoc($result);
                    echo "<tr>";
                    $tmp = $row['Train_number'];
                    echo "<th><br>$tmp<br></th>";
                    $pur_departureStation = $row['departureStation'];
                    echo "<th><br>$pur_departureStation<br></th>";
                    $pur_terminus = $row['destinationStation'];
                    echo "<th><br>$pur_terminus<br></th>";
                    $tmp = date("H:i", strtotime($row['departureTime']));
                    echo "<th><br>$tmp<br></th>";
                    $tmp = date("H:i", strtotime($row['destinationTime']));
                    echo "<th><br>$tmp<br></th>";
                    // 处理时间间隔
                    $interval = calInterval($row['departureTime'], $row['mark1'], $row['destinationTime'], $row['mark2']);
                    switch ((int)$row['mark2'] - (int)$row['mark1']) {
                        case 0:
                            echo "<th><br>$interval<br>当日到达</th>";
                            break;
                        case 1:
                            echo "<th><br>$interval<br>次日到达</th>";
                            break;
                        case 2:
                            echo "<th><br>$interval<br>两日到达</th>";
                            break;
                        case 3:
                            echo "<th><br>$interval<br>三日到达</th>";
                            break;
                    }
                    $innerSql = getRemainingTicketInquiry($pur_trainNumber, $pur_departureStation, $pur_terminus, $pur_departureDate);
                    $innerResult = getResult($innerSql);
                    $usedSeatInfo = array();
                    if (mysqli_num_rows($innerResult))
                        while ($innerRow = mysqli_fetch_assoc($innerResult)) {
                            $tmpType = $innerRow['Seat_type'];
                            $tmpNumber = $innerRow['USE_Number'];
                            $usedSeatInfo["$tmpType"] = $tmpNumber;
                        }
                    $innerSql = "
select Seat_type, Seat_number
from ticketsystem.train_seat_info
where Train_number = '$pur_trainNumber';
";
                    $innerResult = getResult($innerSql);
                    $totSeatInfo = array();
                    if (mysqli_num_rows($innerResult))
                        while ($innerRow = mysqli_fetch_assoc($innerResult)) {
                            $tmpType = $innerRow['Seat_type'];
                            $tmpNumber = $innerRow['Seat_number'];
                            $totSeatInfo["$tmpType"] = (int)$tmpNumber;
                        }
                    makePurchaseTicketInfo($totSeatInfo, $usedSeatInfo, "商务座", $pur_trainNumber, $pur_departureDate, $pur_departureStation, $pur_terminus);
                    makePurchaseTicketInfo($totSeatInfo, $usedSeatInfo, "一等座", $pur_trainNumber, $pur_departureDate, $pur_departureStation, $pur_terminus);
                    makePurchaseTicketInfo($totSeatInfo, $usedSeatInfo, "二等座", $pur_trainNumber, $pur_departureDate, $pur_departureStation, $pur_terminus);
                    makePurchaseTicketInfo($totSeatInfo, $usedSeatInfo, "硬座", $pur_trainNumber, $pur_departureDate, $pur_departureStation, $pur_terminus);
                    makePurchaseTicketInfo($totSeatInfo, $usedSeatInfo, "硬卧", $pur_trainNumber, $pur_departureDate, $pur_departureStation, $pur_terminus);
                    makePurchaseTicketInfo($totSeatInfo, $usedSeatInfo, "软卧", $pur_trainNumber, $pur_departureDate, $pur_departureStation, $pur_terminus);
                    makePurchaseTicketInfo($totSeatInfo, $usedSeatInfo, "高级软卧", $pur_trainNumber, $pur_departureDate, $pur_departureStation, $pur_terminus);
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php makeFooter() ?>
</body>
</html>
