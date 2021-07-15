<?php
// filename: pages/home.php
include "../function/function.php";
checkIfSetCookie();
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <?php makeCSS("主页", "/static/icon/home.png"); ?>
</head>

<body>
<?php makeHeader() ?>
<div class="container-fluid">
    <div class="row">
        <?php makeNavigationBar(1) ?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="jumbotron" style="background-image: url('/static/img/background.jpg')">
                <h1>欢迎你，<?php $un = $_COOKIE['username'];
                    echo "$un"; ?></h1>
                <br>
                <div class="row">
                    <div class="col-md-3 col-md-offset-1 thumbnail" style="background-color: rgba(112,243,255,0.4);">
                        <br>
                        <form method="post" action="../function/func_reTicket.php">
                            <div class="form-group">
                                <label for="departurePlace">出发地</label>
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
                                <label for="destination">到达地</label>
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
                            <button type="submit" class="btn btn-lg btn-primary">查询</button>
                        </form>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php makeFooter(); ?>
</body>
</html>
