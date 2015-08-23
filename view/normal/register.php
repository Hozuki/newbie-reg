<?php
/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/22
 * Time: 01:20
 */
?>

<html>
<head>
    <?php
    require_once '../template/header.php';
    ?>
    <title>新生登记信息填写</title>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1 class="text-center">新生登记信息填写</h1>
        <span id="helpBlock" class="help-block text-center">
            请准确填写下方的表格，再点击“提交”按钮。
        </span>
    </div>

    <div class="col-md-9 col-md-offset-3">
        <form action="register-handler.php" method="post" class="form-horizontal">
            <div class="form-group">
                <label for="reg-sname" class="col-md-2 col-md-offset-1 control-label">姓名:</label>

                <div class="col-md-3">
                    <input type="text" class="form-control" id="reg-sname" name="reg-sname"
                           placeholder="您的姓名" maxlength="16"/>
                    <span class="glyphicon glyphicon-ok form-control-feedback initially-hidden"></span>
                    <span class="glyphicon glyphicon-remove form-control-feedback initially-hidden"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="reg-aid" class="col-md-2 col-md-offset-1 control-label">录取号码:</label>

                <div class="col-md-3">
                    <input type="text" class="form-control" id="reg-aid" name="reg-aid"
                           placeholder="录取通知书编号"/>
                    <span class="glyphicon glyphicon-ok form-control-feedback initially-hidden"></span>
                    <span class="glyphicon glyphicon-remove form-control-feedback initially-hidden"></span>
                </div>
            </div>
            <div class="col-md-4 col-md-offset-2">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" name="first-submit" value="yes">提交</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12 text-center form-group">
        <a href="../index.php">回到上一级</a>
    </div>
</div>
<?php
require_once '../template/footer.php';
?>
</body>
</html>
