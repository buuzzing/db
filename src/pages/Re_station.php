<?php
// filename: pages/Re_station.php
include "../function/function.php";
checkIfSetCookie();
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <?php makeCSS("车站查询", "/static/icon/train-platform.png") ?>
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
</script>
<body>
<?php makeHeader() ?>
<div class="container-fluid">
    <div class="row">
        <?php makeNavigationBar(4) ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="row">
                <div class="col-md-12 thumbnail" style="background-image: url('/static/img/background2.jpg')">
                    <div class="text-center">
                        <form class="form-inline" style="margin: 10px 0" method="post"
                              action="../function/func_reStation.php">
                            <div class="form-group">
                                <label for="station">车站名称&nbsp&nbsp</label>
                                <?php
                                echo "<input type=\"text\" class=\"form-control\" id=\"station\" name=\"station\" placeholder=\"车站名称\" ";
                                if (isset($_COOKIE['station'])) {
                                    $station = $_COOKIE['station'];
                                    echo "value='$station'";
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
                <div class="col-md-12 thumbnail" style="background-image: url('../../static/img/background.jpg')">
                    <table class="table table-striped" style="margin: 10px; width: 99%; height: auto;">
                        <tr>
                            <th>车次</th>
                            <th>列车类型</th>
                            <th>始发站</th>
                            <th>本站名称</th>
                            <th>开点</th>
                            <th>终点站</th>
                            <th>终点站到点</th>
                            <th>运行时长</th>
                        </tr>
                        <?php
                        if (isset($_COOKIE['station'])) {
                            $station = $_COOKIE['station'];
                            $sql = "
select A.Train_number      as Train_number,
       Train_type          as type,
       Station_name        as station,
       A.Departure_station as departureStation,
       A.Terminus          as arrivalStation,
       B.Departure_time    as departureTime,
       B.Departure_remark  as mark1,
       A.Arrival_time      as arrivalTime,
       A.Arrival_remark    as mark2
from ticketsystem.train_info as A,
     ticketsystem.train_timetable as B
where A.Train_number = B.Train_number
  and B.City = '$station'
order by departureTime;
";
                            $result = getResult($sql);
                            if (mysqli_num_rows($result)) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    $tmp = $row['Train_number'];
                                    echo "<th><a href='javascript:void(0)' onclick=\"sendNumber('$tmp')\">$tmp</a></th>";
                                    $tmp = $row['type'];
                                    echo "<th>$tmp</th>";
                                    $tmp = $row['departureStation'];
                                    echo "<th>$tmp</th>";
                                    $tmp = $row['station'];
                                    echo "<th>$tmp</th>";
                                    if (isset($row['departureTime'])) {
                                        $tmp = date("H:i", strtotime($row['departureTime']));
                                        echo "<th>$tmp</th>";
                                    } else echo "<th>-</th>";
                                    $tmp = $row['arrivalStation'];
                                    echo "<th>$tmp</th>";
                                    $tmp = date("H:i", strtotime($row['arrivalTime']));
                                    echo "<th>$tmp</th>";
                                    if (isset($row['departureTime'])) {
                                        $interval = calInterval($row['departureTime'], $row['mark1'], $row['arrivalTime'], $row['mark2']);
                                        switch ((int)$row['mark2'] - (int)$row['mark1']) {
                                            case 0:
                                                echo "<th>$interval</th>";
                                                break;
                                            case 1:
                                                echo "<th>$interval&nbsp(+1)</th>";
                                                break;
                                            case 2:
                                                echo "<th>$interval&nbsp(+2)</th>";
                                                break;
                                            case 3:
                                                echo "<th>$interval&nbsp(+3)</th>";
                                                break;
                                        }
                                    } else echo "<th>-</th>";
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
<?php makeFooter(); ?>
</body>
</html>
