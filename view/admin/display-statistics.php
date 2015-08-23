<?php
/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/22
 * Time: 19:07
 */

require_once 'cookie-name.inc.php';

__requireLogIn();

?>

<html>
<head>
    <?php
    require_once '../template/header.php';
    ?>
    <title>登记实时统计</title>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1 class="text-center">登记实时统计</h1>
        <span id="helpBlock" class="help-block text-center">
            这里可以看到当前登记的实时统计结果。
        </span>
    </div>

    <div class="text-center bg-warning text-warning" style="display: none;" id="top-message">
        <h2>发生了一个错误</h2>

        <p id="error-title"></p>

        <p id="error-message"></p>
    </div>

    <div class="panel-heading">
        <table class="table table-hover table-bordered" id="stat-table">
            <tbody id="list-content">
            </tbody>
        </table>
    </div>

    <div class="col-md-12 text-center form-group">
        <a href="cp.php">返回上一级</a>
    </div>
</div>
<?php
require_once '../template/footer.php';
?>
<script type="text/javascript" src="../../js/display-statistics-logic.js"></script>
</body>
</html>
