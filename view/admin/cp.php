<?php
/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/18
 * Time: 18:03
 */

require_once '../../logic/data/cp.login.php';
require_once 'cookie-name.inc.php';
require_once '../../logic/util.php';

$upost = $_POST['login-username'];
$ppost = $_POST['login-password'];
if (strcmp($upost, NR_CP_USERNAME) == 0 && strcmp($ppost, NR_CP_PASSWORD) == 0) {
    setcookie(NR_LOGIN_COOKIE_NAME, '1');
    $loginSuccessful = true;
} else {
    if ($_GET['logout']) {
        setcookie(NR_LOGIN_COOKIE_NAME, null);
        header('location: ' . util::GetFileName(__FILE__));
        exit;
    }
}
$hasLoggedIn = $loginSuccessful || !!$_COOKIE[NR_LOGIN_COOKIE_NAME];

?>

<html>
<head>
    <?php
    require_once '../template/header.php';
    ?>
    <title>控制面板</title>
</head>
<body>
<div class="container">
    <?php
    if ($loginSuccessful) {
        ?>
        <p class="text-center bg-success text-success" id="top-message">
            登录成功。
        </p>
        <?php
    }
    ?>
    <?php
    if (!$hasLoggedIn) {
        ?>
        <div class="col-md-4 col-md-offset-4">
            <div class="page-header">
                <h1 class="text-center">
                    登录
                </h1>
                <span class="help-block text-center">
                    登录到控制面板。
                </span>
            </div>

            <form method="post" class="form-horizontal">
                <div class="form-group">
                    <label for="login-username" class="col-md-4 control-label">用户名:</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" id="login-username" name="login-username"
                               placeholder="在这里输入用户名" maxlength="16"/>
                        <span class="glyphicon glyphicon-ok form-control-feedback initially-hidden"></span>
                        <span class="glyphicon glyphicon-remove form-control-feedback initially-hidden"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="login-password" class="col-md-4 control-label">密码:</label>

                    <div class="col-md-6">
                        <input type="password" class="form-control" id="login-password" name="login-password"
                               placeholder="在这里输入密码" maxlength="16"/>
                        <span class="glyphicon glyphicon-ok form-control-feedback initially-hidden"></span>
                        <span class="glyphicon glyphicon-remove form-control-feedback initially-hidden"></span>
                    </div>
                </div>
                <div class="col-md-4 col-md-offset-4">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">登录</button>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }
    ?>
    <?php
    if ($hasLoggedIn) {
        ?>
        <div class="page-header">
            <h1 class="text-center">控制面板</h1>
            <span id="helpBlock" class="help-block text-center">
                享受控制一切的快感吧宇宙人！
            </span>
        </div>
        <div class="panel-group col-md-4 col-md-offset-4">
            <a class="btn btn-block btn-info" id="view-stats" href="main-screen.php"
               target="_blank">路人屏幕</a>
        </div>
        <div class="panel-group col-md-4 col-md-offset-4">
            <a class="btn btn-block btn-info" id="view-stats" href="display-statistics.php"
               target="_blank">查看实时进展</a>
        </div>
        <div class="panel-group col-md-4 col-md-offset-4">
            <a class="btn btn-block btn-default" id="import-csv" href="import-collect-csv.php" target="_blank">导入总表 CSV
                数据</a>
        </div>
        <div class="panel-group col-md-4 col-md-offset-4">
            <a class="btn btn-block btn-default" id="import-csv" href="import-feedback-csv.php" target="_blank">导入反馈表
                CSV
                数据</a>
        </div>
        <div class="panel-group col-md-4 col-md-offset-4">
            <a class="btn btn-block btn-danger" id="reset-register" href="reset-register.php" target="_blank">清除登记记录</a>
        </div>
        <?php
    }
    ?>
    <?php
    if ($hasLoggedIn) {
        ?>
        <div class="col-md-12 text-center form-group">
            <a href="javascript:;" id="logout-control">注销</a>
        </div>
        <?php
    }
    ?>
    <div class="col-md-12 text-center form-group">
        <a href="../index.php">回到主界面</a>
    </div>
</div>
<?php
if ($hasLoggedIn) {
    ?>
    <script type="text/javascript">
        $('#logout-control').on('click', function () {
            window.location.href = window.location.href + '?logout=1';
        });
    </script>
    <?php
}
?>
<?php
require_once '../template/footer.php';
?>
</body>
</html>
