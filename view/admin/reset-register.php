<?php
/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/18
 * Time: 22:05
 */

require_once '../../logic/DB.php';
require_once '../../logic/util.php';
require_once 'cookie-name.inc.php';

__requireLogIn();

define('CLEAR_SUCCESS', 1);
define('CLEAR_FAILED', 2);

/**
 * @var int $clearResult
 */

if ($_POST['confirm-result'] === 'yes' || $_GET['confirm-result'] === 'yes') {
    $db = new DB();
    $db->Init();
    $r = $db->ClearSubmitData();
    $clearResult = $r ? CLEAR_SUCCESS : CLEAR_FAILED;
    $db->Uninit();
    header('location: ' . util::GetFileName(__FILE__) . '?cs=' . $clearResult);
    exit;
}

$clearResult = $_GET['cs'];

?>

<html>
<head>
    <?php
    require_once '../template/header.php';
    ?>
    <title>清除登记记录</title>
</head>
<body>
<form class="container">
    <?php
    if ($clearResult) {
        ?>
        <?php
        if ($clearResult == CLEAR_SUCCESS) {
            ?>
            <p class="text-center bg-info" id="top-message">
                清除登记记录成功。
            </p>
            <?php
        } elseif ($clearResult == CLEAR_FAILED) {
            ?>
            <p class="text-center bg-danger" id="top-message">
                清除登记记录失败。
            </p>
            <?php
        } else {
            ?>
            <p class="text-center bg-warning" id="top-message">
                我遇到了超自然现象！
            </p>
            <?php
        }
        ?>
        <?php
    }
    ?>
    <div class="page-header">
        <h1 class="text-center">清除登记记录</h1>
        <span id="helpBlock" class="help-block text-center">
            清空已经登记的数据。
        </span>
    </div>
    <div class="text-center bg-danger text-danger" id="top-message">
        <h2>警告</h2>

        <p>
            此操作会清除<strong>所有</strong>的已经登记的学生的记录！
        </p>

        <p>
            此操作不可逆转！
        </p>
    </div>
    <div class="panel-heading">
        <form method="post" class="form-horizontal">
            <div class="col-md-4 col-md-offset-4">
                <div class="form-group">
                    <input class="hidden" type="hidden" name="confirm-result" value="yes"/>
                    <button type="submit" class="btn btn-danger btn-lg btn-block">
                        我很清楚后果，继续吧
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12 text-center form-group">
        <a href="cp.php">返回上一级</a>
    </div>
    <?php
    require_once '../template/footer.php';
    ?>
</body>
</html>
