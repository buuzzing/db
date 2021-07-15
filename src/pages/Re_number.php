<?php
// filename: pages/Re_number.php
include "../function/function.php";
checkIfSetCookie();
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <?php makeCSS("车次查询", "/static/icon/train-number.png") ?>
</head>
<body>
<?php makeHeader() ?>
<div class="container-fluid">
    <div class="row">
        <?php makeNavigationBar(3) ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="row">
                <div class="col-md-12 thumbnail" style="background-image: url('/static/img/background2.jpg')">
                    <div class="text-center">
                        <form class="form-inline" style="margin: 10px 0" method="post"
                              action="../function/func_reNumber.php">
                            <div class="form-group">
                                <label for="number">车次号&nbsp&nbsp</label>
                                <?php
                                echo "<input type=\"text\" class=\"form-control\" id=\"number\" name=\"number\" placeholder=\"车次号\" ";
                                if (isset($_COOKIE['number'])) {
                                    $number = $_COOKIE['number'];
                                    echo "value='$number'";
                                }
                                echo '>';
                                ?>
                                <span>&nbsp&nbsp&nbsp</span>
                                <button type="submit" class="btn btn-primary" style="background-color: orange">查询
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-4 col-md-offset-4 thumbnail"
                     style="background-image: url('/static/img/background.jpg')">
                    <div class="text-center" style="padding: 10px">
                        <div class="list-group">
                            <?php
                            if (isset($_COOKIE['number'])) {
                                $number = $_COOKIE['number'];
                                $sql = "
select Number_of_stops, Station_name, Arrival_time, Arrival_remark, Departure_time, Departure_remark, Run_mileage
from ticketsystem.train_timetable
where Train_number = '$number'
  and (Arrival_time is null or Departure_time is null);
";
                                $result = getResult($sql);
                                $station1 = $station2 = 0;
                                $time1 = $time2 = $mark = 0;
                                $totStation = $mile = 0;
                                if (mysqli_num_rows($result)) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        if (!isset($row['Arrival_time'])) {
                                            $station1 = $row['Station_name'];
                                            $time1 = $row['Departure_time'];
                                        } elseif (!isset($row['Departure_time'])) {
                                            $station2 = $row['Station_name'];
                                            $time2 = $row['Arrival_time'];
                                            $mark = $row['Arrival_remark'];
                                            $mile = $row['Run_mileage'];
                                            $totStation = $row['Number_of_stops'];
                                        }
                                    }
                                    echo "<div class=\"list-group-item\" style='text-align: left'>"
                                        . "<p class=\"list-group-item-text\"><span style='font-size: 16px; font-weight: bold'>发站：</span>$station1</p>"
                                        . "</div>";
                                    echo "<div class=\"list-group-item\" style='text-align: left'>"
                                        . "<p class=\"list-group-item-text\"><span style='font-size: 16px; font-weight: bold'>到站：</span>$station2</p>"
                                        . "</div>";
                                    echo "<div class=\"list-group-item\" style='text-align: left'>"
                                        . "<p class=\"list-group-item-text\"><span style='font-size: 16px; font-weight: bold'>总站数：</span>$totStation</p>"
                                        . "</div>";
                                    echo "<div class=\"list-group-item\" style='text-align: left'>"
                                        . "<p class=\"list-group-item-text\"><span style='font-size: 16px; font-weight: bold'>里程：</span>$mile KM</p>"
                                        . "</div>";
                                    $interval = calInterval($time1, 0, $time2, $mark);
                                    echo "<div class=\"list-group-item\" style='text-align: left'>"
                                        . "<p class=\"list-group-item-text\"><span style='font-size: 16px; font-weight: bold'>历时：</span>$interval</p>"
                                        . "</div>";
                                }
                                $sql = "
select Seat_type
from ticketsystem.train_seat_info
where Train_number = '$number';
";
                                $result = getResult($sql);
                                $seatType = array();
                                if (mysqli_num_rows($result)) {
                                    while ($row = mysqli_fetch_assoc($result))
                                        array_push($seatType, (string)$row['Seat_type']);
                                    echo "<div class=\"list-group-item\" style='text-align: left'>"
                                        . "<p class=\"list-group-item-text\"><span style='font-size: 16px; font-weight: bold'>座位类型：</span>";
                                    if (in_array("商务座", $seatType, true)) echo "商务座\t";
                                    if (in_array("一等座", $seatType, true)) echo "一等座\t";
                                    if (in_array("二等座", $seatType, true)) echo "二等座\t";
                                    if (in_array("硬座", $seatType, true)) echo "硬座\t";
                                    if (in_array("硬卧", $seatType, true)) echo "硬卧\t";
                                    if (in_array("软卧", $seatType, true)) echo "软卧\t";
                                    if (in_array("高级软卧", $seatType, true)) echo "高级软卧\t";
                                    echo "</p></div>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 thumbnail" style="background-image: url('../../static/img/background.jpg')">
                    <table class="table table-striped" style="margin: 10px; width: 99%; height: auto;">
                        <tr>
                            <th>车次</th>
                            <th>#</th>
                            <th>站名</th>
                            <th>到点</th>
                            <th>开点</th>
                            <th>停车时长</th>
                            <th>里程</th>
                            <th>天数</th>
                        </tr>
                        <?php
                        if (isset($_COOKIE['number'])) {
                            $number = $_COOKIE['number'];
                            $sql = "
select Number_of_stops, Station_name, Arrival_time, Arrival_remark, Departure_time, Departure_remark, Run_mileage
from ticketsystem.train_timetable
where Train_number = '$number';
";
                            $result = getResult($sql);
                            if (mysqli_num_rows($result)) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $flag = false;
                                    echo "<tr>";
                                    echo "<th>$number</th>";
                                    $tmp = $row['Number_of_stops'];
                                    echo "<th>$tmp</th>";
                                    $tmp = $row['Station_name'];
                                    echo "<th>$tmp</th>";
                                    $tmp = $row['Arrival_time'];
                                    if ($tmp == null) {
                                        $flag = true;
                                        echo "<th>-</th>";
                                    } else echo "<th>$tmp</th>";
                                    $tmp = $row['Departure_time'];
                                    if ($tmp == null) {
                                        $flag = true;
                                        echo "<th>-</th>";
                                    } else echo "<th>$tmp</th>";
                                    if (!$flag) {
                                        $interval = calInterval($row['Arrival_time'], $row['Arrival_remark'], $row['Departure_time'], $row['Departure_remark']);
                                        echo "<th>$interval</th>";
                                    } else echo "<th>-</th>";
                                    $tmp = $row['Run_mileage'];
                                    echo "<th>$tmp</th>";
                                    $tmp = (int)$row['Arrival_remark'];
                                    switch ($tmp) {
                                        case 0:
                                            echo "<th>当天</th>";
                                            break;
                                        case 1:
                                            echo "<th>第 2 天</th>";
                                            break;
                                        case 2:
                                            echo "<th>第 3 天</th>";
                                            break;
                                    }
                                    echo "</tr>";
                                }
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
