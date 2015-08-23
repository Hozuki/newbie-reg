<?php
/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/22
 * Time: 08:03
 */

require_once 'cookie-name.inc.php';

__requireLogIn();

?>

<html>
<head>
    <?php
    require_once '../template/header.php';
    ?>
    <title>路人界面</title>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1 class="text-center">路人界面</h1>
        <span id="helpBlock" class="help-block text-center">
            面向路人的欢迎界面。
        </span>
    </div>
    <div class="form-group">
        <div class="text-center bg-info text-info" id="top-message">
            <div id="welcome-message-container">
                <h2>欢迎</h2>

                <h3 id="welcome-message"></h3>
            </div>
            <div id="error-message-container" style="display: none;">
                <h2>发生了一个错误</h2>

                <p id="error-title"></p>

                <p id="error-message"></p>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-2 col-md-offset-5">
            <a href="javascript:;" class="btn btn-default floating-left" id="btn-prev">上一个</a>
            <a href="javascript:;" class="btn btn-default floating-right" id="btn-next">下一个</a>
        </div>
    </div>
    <div class="col-md-12 text-center form-group margin-top-30">
        <a href="cp.php">返回上一级</a>
    </div>
</div>
<?php
require_once '../template/footer.php';
?>
<script type="text/javascript" src="../../js/main-screen-logic.js"></script>
</body>
</html>
