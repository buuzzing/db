<?php
// filename: function/func_insertPassenger.php
include "function.php";
checkIfSetCookie();

if (!isset($_POST['newPassengerID']))
    echo "
<script>
    alert('请输入乘客身份证号码！');
    window.location.href='../pages/myProfile.php';
</script>";
$newPassengerID = $_POST['newPassengerID'];

if (!isset($_POST['newPassengerName']))
    echo "
<script>
    alert('请输入乘客姓名！');
    window.location.href='../pages/myProfile.php';
</script>";
$newPassengerName = $_POST['newPassengerName'];

if (!isset($_POST['newPassengerPhone']))
    echo "
<script>
    alert('请输入乘客手机号码！');
    window.location.href='../pages/myProfile.php';
</script>";
$newPassengerPhone = $_POST['newPassengerPhone'];

if (!isset($_POST['newPassengerType']))
    echo "
<script>
    alert('请输入乘客身份证号码！');
    window.location.href='../pages/myProfile.php';
</script>";
$newPassengerType = $_POST['newPassengerType'];
$tmp = '';
if ($newPassengerType == 'option1') {
    $newPassengerType = 1;
    $tmp = '普通乘客';
} else {
    $newPassengerType = 2;
    $tmp = '学生';
}

$ID = $_COOKIE['ID'];
$sql = "
insert into ticketsystem.passenger_info
values ('$newPassengerID', '$ID', '$newPassengerName', '$newPassengerPhone', '$newPassengerType');
";
//echo "<h1>$sql</h1>";
getResult($sql);

echo "
<script>
    alert('添加完成，您的新加乘车人信息：'
    +'\\n身份证号码：$newPassengerID'
    +'\\n姓名：$newPassengerName'
    +'\\n联系方式：$newPassengerPhone'
    +'\\n乘客类型：$tmp');
    window.location.href='../pages/myProfile.php';
</script>
";
