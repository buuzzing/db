<?php
// filename: pages/myOrders.php
include "../function/function.php";
checkIfSetCookie();
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <?php makeCSS("我的订单", "/static/icon/order.png") ?>
</head>
<script>
    function setCookie(name, value, t) {
        let date = new Date();
        date.setDate(date.getDate() + t);
        document.cookie = name + '=' + value + ';expires=' + date + ';path=/';
    }

    function clrQuery() {
        setCookie('selectMethod', '', -3600);
        setCookie('selectInfo', '', -3600);
        window.location.href = 'myOrders.php';
    }

    function refundTicket(ID) {
        let f = confirm("订单号：" + ID + "\n您确定退票吗？");
        if (f === true) {
            setCookie('refundID', ID, 60);
            window.location.href = '../function/func_refundTicket.php';
        }
    }
</script>
<body>
<?php makeHeader() ?>
<div class="container-fluid">
    <div class="row">
        <?php makeNavigationBar(7) ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 thumbnail"
                     style="background-image: url('/static/img/background2.jpg')">
                    <div class="text-center">
                        <h3 style="padding-bottom: 5px">条件检索（模糊检索）</h3>
                        <form class="form-inline" style="margin: 10px 0" method="post"
                              action="../function/func_reOrders.php">
                            <div class="radio">
                                <label for="byID">
                                    <input type="radio" name="selectMethod" id="byID" value="byID"
                                        <?php
                                        if ((isset($_COOKIE['selectMethod']) && $_COOKIE['selectMethod'] == 'byID') || !isset($_COOKIE['selectMethod']))
                                            echo ' checked';
                                        ?>
                                    >&nbsp按乘客身份证号检索
                                </label>
                            </div>
                            <span>&nbsp&nbsp</span>
                            <div class="radio">
                                <label for="byName">
                                    <input type="radio" name="selectMethod" id="byName" value="byName"
                                        <?php
                                        if (isset($_COOKIE['selectMethod']) && $_COOKIE['selectMethod'] == 'byName')
                                            echo 'checked';
                                        ?>
                                    >&nbsp按乘客姓名检索
                                </label>
                            </div>
                            <span>&nbsp&nbsp</span>
                            <div class="radio">
                                <label for="byPhone">
                                    <input type="radio" name="selectMethod" id="byPhone" value="byPhone"
                                        <?php
                                        if (isset($_COOKIE['selectMethod']) && $_COOKIE['selectMethod'] == 'byPhone')
                                            echo 'checked';
                                        ?>
                                    >&nbsp按乘客手机号码检索
                                </label>
                            </div>
                            <br><br>
                            <div class="form-group" style="padding-bottom: 10px">
                                <label for="selectInfo">
                                    <input type="text" class="form-control" name="selectInfo" id="selectInfo"
                                           placeholder="检索内容"
                                        <?php
                                        if (isset($_COOKIE['selectInfo'])) {
                                            $tmp = $_COOKIE['selectInfo'];
                                            echo " value='$tmp'";
                                        }
                                        ?>
                                    >
                                </label>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary" style="margin-right: 10px">检索</button>
                            <a class="btn btn-primary" style="margin-left: 10px; background-color: orange"
                               href="javascript:void(0)" onclick="clrQuery()">
                                清空条件
                            </a>
                        </form>
                    </div>
                </div>
                <div class="col-md-12 thumbnail" style="background-image: url('../../static/img/background.jpg')">
                    <table class="table table-striped" style="margin: 10px; width: 99%; height: auto;">
                        <tr>
                            <th>订单号</th>
                            <?php
                            if ($_COOKIE['ID'] == 'admin')
                                echo "<th>创建用户ID</th>";
                            ?>
                            <th>车次</th>
                            <th>乘客姓名</th>
                            <th>乘客类型</th>
                            <th>出发日期</th>
                            <th>出发站/发车时间</th>
                            <th>到达站/到达时间</th>
                            <th>历时</th>
                            <th>座位类别</th>
                            <th>车厢号</th>
                            <th>座位号</th>
                            <th>票价</th>
                            <th>订单状态</th>
                            <th>操作</th>
                        </tr>
                        <?php
                        $ID = $_COOKIE['ID'];
                        $selectMethod = $_COOKIE['selectMethod'];
                        $selectInfo = $_COOKIE['selectInfo'];
                        delOrders();    // 删除过期订单信息
                        $sql = "
select A.Order_ID,       # 订单号
       A.Train_number,   # 车次
       D.Passenger_name, # 乘客姓名
       D.Passenger_type, # 乘客类型
       C.Seat_type,      # 座位类别
       A.Number_of_cars, # 车厢号
       A.Number_of_seat, # 座位号
       A.Order_price,    # 票价
       A.Order_status,   # 订单状态
       A.User_ID         # 创建用户的ID
from ticketsystem.order_info as A,
     ticketsystem.train_seat_info as C,
     ticketsystem.passenger_info as D
where A.Train_number = C.Train_number     # 自然连接
  and A.Number_of_cars = C.Number_of_cars # 在C中确定车厢
  and A.User_ID = D.User_ID
  and A.Passenger_ID = D.Passenger_ID
";
                        if ($ID != 'admin') $sql .= "\nand A.User_ID = '$ID'";
                        if (isset($_COOKIE['selectMethod']))
                            switch ($selectMethod) {
                                case "byID":
                                    $sql .= " and A.Passenger_ID like '%$selectInfo%'";
                                    break;
                                case "byName":
                                    $sql .= " and D.Passenger_name like '%$selectInfo%'";
                                    break;
                                case "byPhone":
                                    $sql .= " and D.Passenger_telephone like '%$selectInfo%'";
                                    break;
                            }
                        $sql .= "\ngroup by A.Order_ID\norder by A.Order_ID desc;";
                        $result = getResult($sql);
                        $sql = "
select A.Order_ID,
       A.Departure_station, # 出发站
       A.Departure_time,    # 出发时间
       B.Departure_remark   # 出发时间标记
from ticketsystem.order_info as A,
     ticketsystem.train_timetable as B
where A.Train_number = B.Train_number
  and B.Station_name = A.Departure_station # 在B中确定发站
group by A.Order_ID
order by A.Order_ID desc;
";
                        $resultDeparture = getResult($sql);
                        $sql = "
select A.Order_ID,
       A.Terminus,      # 到达站
       B.Arrival_time,  # 到达时间
       B.Arrival_remark # 到达时间标记
from ticketsystem.order_info as A,
     ticketsystem.train_timetable as B
where A.Train_number = B.Train_number
  and B.Station_name = A.Terminus # 在B中确定到站
group by A.Order_ID
order by A.Order_ID desc;
";
                        $resultArrive = getResult($sql);
                        //                        echo "<h1>$sql</h1>";
                        if (mysqli_num_rows($result)) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $rowDeparture = mysqli_fetch_assoc($resultDeparture);
                                $rowArrive = mysqli_fetch_assoc($resultArrive);
                                $Order_ID = $row['Order_ID'];
                                $Train_number = $row['Train_number'];
                                $Passenger_name = $row['Passenger_name'];
                                $Passenger_type = $row['Passenger_type'];
                                if ($Passenger_type == 1) $Passenger_type = "普通乘客";
                                else $Passenger_type = "学生";
                                $Departure_station = $rowDeparture['Departure_station'];
                                $Terminus = $rowArrive['Terminus'];
                                $Seat_type = $row['Seat_type'];
                                $Number_of_cars = $row['Number_of_cars'];
                                $Number_of_seat = $row['Number_of_seat'];
                                $Order_price = $row['Order_price'];
                                $Order_status = $row['Order_status'];
                                if ((int)$Order_status == 1) $Order_status = "正常";
                                elseif ((int)$Order_status == 2) $Order_status = "退票";
                                elseif ((int)$Order_status == 3) $Order_status = "已过期";
                                $Departure_time = $rowDeparture['Departure_time'];
                                $Departure_remark = $rowDeparture['Departure_remark'];
                                $Arrival_time = $rowArrive['Arrival_time'];
                                $Arrival_remark = $rowArrive['Arrival_remark'];
                                if ($Order_status != "正常")
                                    echo "<tr style=\"color: gray;\">";
                                else
                                    echo "<tr>";
                                echo "<th>$Order_ID</th>";
                                if ($_COOKIE['ID'] == 'admin') {
                                    $tmp = $row['User_ID'];
                                    echo "<th>$tmp</th>";
                                }
                                echo "<th>$Train_number</th>";
                                echo "<th>$Passenger_name</th>";
                                echo "<th>$Passenger_type</th>";
                                $tmp = date("Y-m-d", strtotime($Departure_time));
                                echo "<th>$tmp</th>";
                                $tmp = date("H:i", strtotime($Departure_time));
                                echo "<th>$Departure_station<br><span";
                                if ($Order_status == "正常")
                                    echo " style='color: blueviolet;'";
                                echo ">$tmp</span></th>";
                                $tmp = date("H:i", strtotime($Arrival_time));
                                $c = (int)$Arrival_remark - (int)$Departure_remark;
                                if ($c) $tmp .= " (+$c)";
                                echo "<th>$Terminus<br><span";
                                if ($Order_status == "正常")
                                    echo " style='color: green;'";
                                echo ">$tmp</span></th>";
                                $interval = calInterval(date("H:i", strtotime($Departure_time)), $Departure_remark, $Arrival_time, $Arrival_remark);
                                echo "<th>$interval</th>";
                                echo "<th>$Seat_type</th>";
                                echo "<th>$Number_of_cars 车厢</th>";
                                echo "<th>$Number_of_seat 号</th>";
                                echo "<th>￥ $Order_price</th>";
                                if ($Order_status == "退票") echo "<th style=\"color: blue;\">";
                                elseif ($Order_status == "正常") echo "<th style=\"color: green;\">";
                                else echo "<th>";
                                echo "$Order_status</th>";
                                echo "<th><a class=\"btn btn-primary\" style=\"margin-left: 10px; background-color: mediumpurple\"
                               href=\"javascript:void(0)\" onclick=\"refundTicket('$Order_ID')\"";
                                if ($Order_status != "正常") echo " disabled";
                                echo ">退票</a>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
