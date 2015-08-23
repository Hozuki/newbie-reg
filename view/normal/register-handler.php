<?php

/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/16
 * Time: 16:48
 */

require_once 'register-handler.include.php';

/**
 * 1 = PostRegister, 2 = PostConfirm
 * @var int $rhState
 * @var SubmitResult $submitResult
 */

?>

<html xmlns="http://www.w3.org/1999/html">
<head>
    <?php
    require_once '../template/header.php';
    ?>
    <title>您的信息</title>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1 class="text-center"></h1>
    </div>
    <?php
    if ($submitResult and $submitResult->actionResult == SUBMIT_RESULT_SUCCEEDED) {
    ?>
    <div class="text-center bg-success text-success" id="top-message">
        <h2>欢迎新同学</h2>

        <p>
            <?php
            echo <<<EOX
欢迎<strong>{$submitResult->feedback->studentName}</strong>同学来到机械学院！<br/>
录取通知书编号: {$submitResult->feedback->admissionID}<br/>
            <div class="col-md-12">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <table class="table table-bordered" id="feedback-table">
                        <tbody>
                        <tr>
                            <th>学号</th>
                            <td>{$submitResult->feedback->studentNumber}</td>
                        </tr>
                        <tr>
                            <th>宿舍</th>
                            <td>{$submitResult->feedback->dormNumber}</td>
                        </tr>
                        <tr>
                            <th>班级</th>
                            <td>{$submitResult->feedback->classNumber}</td>
                        </tr>
                        <tr>
                            <th>班主任</th>
                            <td>{$submitResult->feedback->adviserName}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4"></div>
            </div>
            <br/>
            新生辅导员联系方式:<br/>
            李阳(18810682502) 黄晋国(13240366133)
            </p>
        </div>
EOX;
            ?>
            <?php
            } elseif ($submitResult and $submitResult->actionResult == SUBMIT_RESULT_NEED_CONFIRMATION) {
            ?>

        <div class="text-center bg-warning text-warning" id="top-message">
            <h2>请确认</h2>

            <p>
                根据您填写的信息，我们怀疑我们可能录入时发生了错误，或者信息更新不及时。<br/>
                如果您确认您填写的是对的，点击“这是我”将会以您填写的信息为标准。如果不确定，请点击“这不是我”。
            </p>
        </div>
        <div class="col-md-12 panel-heading">
            <div class="col-md-6 col-md-offset-3">
                <?php
                $regSubName = htmlentities($_POST['reg-sname']);
                $regOrigName = htmlentities($submitResult->feedback->studentName);
                $regAid = $submitResult->feedback->admissionID;
                echo <<<EOX
            <table class="table table-bordered" id="feedback-table">
                <tbody>
                <tr>
                    <th>记录的姓名</th>
                    <td>{$regOrigName}</td>
                </tr>
                <tr>
                    <th>填写的姓名</th>
                    <td>{$regSubName}</td>
                </tr>
                <tr>
                    <th>录取通知书编号</th>
                    <td>{$regAid}</td>
                </tr>
                </tbody>
            </table>
EOX;
                ?>
            </div>
        </div>
        <div class="form-group">
            <form method="post" class="form-horizontal">
                <input type="hidden" class="hidden" name="reg-sname"
                       value="<?php echo $regSubName; ?>"/>
                <input type="hidden" class="hidden" name="reg-aid"
                       value="<?php echo $regAid ?>"/>

                <div class="panel-heading">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg" name="confirm-submit" value="yes">
                            这是我
                        </button>
                        <a class="btn btn-link btn-lg" href="register.php">这不是我</a>
                    </div>
                </div>
            </form>
        </div>
        <?php
        } else {
            ?>
            <div class="text-center bg-warning text-warning" id="top-message">
                <h2>呃……</h2>

                <p>
                    出现了点问题，有可能是因为<br/>

                <div>
                    1、您刚才输入的有点不太对；<br/>
                    2、您已经登记过了；<br/>
                    3、我们忘记录入您的信息了；<br/>
                    4、世界的可能性是无穷无尽的 by人渣诚。
                </div>
                </p>
            </div>
            <?php
        }
        ?>
        <div class="panel-heading">
            <div class="col-md-12 text-center form-group">
                <a href="register.php">返回</a>
            </div>
        </div>
    </div>
    <?php
    require_once '../template/footer.php';
    ?>
</body>
</html>
