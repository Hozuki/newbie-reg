<?php
/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/18
 * Time: 17:21
 */

?>

<html>
<head>
    <?php
    require_once 'template/header.php';
    ?>
    <title>你看到这个说明我没写错</title>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1 class="text-center">请亮明您的身份</h1>
        <span id="helpBlock" class="help-block text-center">
            如果您是外星来客，我们不介意。
        </span>
    </div>
    <div class="panel-group col-md-4 col-md-offset-4">
        <a class="btn btn-block btn-primary" href="normal/register.php">我是新生</a>
    </div>
    <div class="panel-group col-md-4 col-md-offset-4">
        <a class="btn btn-block btn-default" href="admin/cp.php" target="_blank">我不是地球人</a>
    </div>
</div>
<?php
require_once 'template/footer.php';
?>
</body>
</html>
