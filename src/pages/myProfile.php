<?php
// filename: pages/myProfile.php
include "../function/function.php";
checkIfSetCookie();
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <?php makeCSS("我的信息", "/static/icon/user.png"); ?>
</head>
<body>
<?php makeHeader() ?>
<div class="container-fluid">
    <div class="row">
        <?php makeNavigationBar(8) ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="row">
                <div class="col-md-4 col-md-offset-4 thumbnail"
                     style="background-image: url('/static/img/background.jpg')">
                    <div class="text-center" style="padding: 10px">
                        <h2>个人信息</h2>
                        <div class="list-group">
                            <?php
                            $User_ID = $_COOKIE['ID'];
                            $sql = "
select User_name, User_gender, User_telephone, User_type
from ticketsystem.user_info
where User_ID = '$User_ID';
";
                            $result = getResult($sql);
                            echo "<div class=\"list-group-item\" style='text-align: left'>"
                                . "<p class=\"list-group-item-text\"><span style='font-size: 16px; font-weight: bold'>ID：</span>$User_ID</p>"
                                . "</div>";
                            if (mysqli_num_rows($result)) {
                                $row = mysqli_fetch_assoc($result);
                                $tmp = $row['User_name'];
                                echo "<div class=\"list-group-item\" style='text-align: left'>"
                                    . "<p class=\"list-group-item-text\"><span style='font-size: 16px; font-weight: bold'>用户名：</span>$tmp</p>"
                                    . "</div>";
                                $tmp = $row['User_gender'];
                                echo "<div class=\"list-group-item\" style='text-align: left'>"
                                    . "<p class=\"list-group-item-text\"><span style='font-size: 16px; font-weight: bold'>性别：</span>$tmp</p>"
                                    . "</div>";
                                $tmp = $row['User_telephone'];
                                echo "<div class=\"list-group-item\" style='text-align: left'>"
                                    . "<p class=\"list-group-item-text\"><span style='font-size: 16px; font-weight: bold'>电话号码：</span>$tmp</p>"
                                    . "</div>";
                                $tmp = $row['User_type'];
                                switch ($tmp) {
                                    case 0:
                                        $tmp = "管理员";
                                        break;
                                    case 1:
                                        $tmp = "普通乘客";
                                        break;
                                    case 2:
                                        $tmp = "学生";
                                        break;
                                }
                                echo "<div class=\"list-group-item\" style='text-align: left'>"
                                    . "<p class=\"list-group-item-text\"><span style='font-size: 16px; font-weight: bold'>用户类型：</span>$tmp</p>"
                                    . "</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-md-offset-2 thumbnail"
                     style="background-image: url('../../static/img/background.jpg')">
                    <div class="text-center" style="padding-left: 20px; padding-right: 20px">
                        <?php
                        if ($User_ID == 'admin') echo "<h2>所有乘车人</h2>";
                        else echo "<h2>我的乘车人</h2>";
                        ?>
                        <table class="table table-striped" style="margin: 10px; width: 99%; height: auto;">
                            <tr>
                                <th>乘客身份证号码</th>
                                <th>乘客姓名</th>
                                <th>乘客电话号码</th>
                                <th>乘客类别</th>
                            </tr>
                            <?php
                            $sql = "
select distinct Passenger_ID, Passenger_name, Passenger_telephone, Passenger_type
from ticketsystem.passenger_info";
                            if ($User_ID != 'admin') $sql .= "\nwhere User_ID = '$User_ID'";
                            $sql .= ";";
                            $result = getResult($sql);
                            if (mysqli_num_rows($result)) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    $tmp = $row['Passenger_ID'];
                                    echo "<th>$tmp</th>";
                                    $tmp = $row['Passenger_name'];
                                    echo "<th>$tmp</th>";
                                    $tmp = $row['Passenger_telephone'];
                                    echo "<th>$tmp</th>";
                                    $tmp = $row['Passenger_type'];
                                    switch ($tmp) {
                                        case 1:
                                            $tmp = '普通乘客';
                                            break;
                                        case 2:
                                            $tmp = '学生';
                                            break;
                                    }
                                    if ($tmp == "学生") echo "<th style='color: green'>";
                                    else echo "<th>";
                                    echo "$tmp</th>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </table>
                    </div>
                </div>
                <div class="col-md-6 col-md-offset-3 thumbnail"
                     style="background-image: url('../../static/img/background.jpg')">
                    <div class="text-center" style="padding-left: 20px; padding-right: 20px">
                        <h2 style="margin-bottom: 10px; padding-bottom: 10px">添加乘车人</h2>
                        <form class="form-inline" style="margin: 10px 0" method="post"
                              action="../function/func_insertPassenger.php">
                            <div class="form-group" style="margin-bottom: 10px">
                                <label for="newPassengerID">&nbsp&nbsp&nbsp&nbsp乘客身份证号码：&nbsp</label>
                                <input type="text" class="form-control" id="newPassengerID" name="newPassengerID"
                                       placeholder="ID">
                            </div>
                            <br>
                            <div class="form-group" style="margin-bottom: 10px">
                                <label for="newPassengerName">&nbsp&nbsp&nbsp&nbsp乘客姓名：&nbsp&nbsp&nbsp&nbsp</label>
                                <input type="text" class="form-control" id="newPassengerName" name="newPassengerName"
                                       placeholder="姓名">
                            </div>
                            <br>
                            <div class="form-group" style="margin-bottom: 10px">
                                <label for="newPassengerPhone">&nbsp&nbsp&nbsp&nbsp乘客电话号码：&nbsp</label>
                                <input type="text" class="form-control" id="newPassengerPhone" name="newPassengerPhone"
                                       placeholder="电话号码">
                            </div>
                            <br>
                            <div class="form-group" style="margin-bottom: 10px">
                                <label> 乘客类型：</label>
                                <label for="newPassengerType">
                                    <input type="radio" class="form-control" id="newPassengerType1"
                                           name="newPassengerType" value="option1">&nbsp&nbsp普通乘客
                                </label>
                                &nbsp&nbsp
                                <label for="newPassengerType">
                                    <input type="radio" class="form-control" id="newPassengerType1"
                                           name="newPassengerType" value="option2">&nbsp&nbsp学生
                                </label>
                            </div>
                            <br>
                            <button class="btn btn-lg btn-primary" type="submit">添加</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
