<?php
/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/18
 * Time: 21:57
 */

require_once 'cookie-name.inc.php';

__requireLogIn();

require_once 'import-collect-csv.include.php';

/**
 * @var int $importResult
 */

?>

<html>
<head>
    <?php
    require_once '../template/header.php';
    ?>
    <title>导入总表 CSV 数据</title>
</head>
<body>
<div class="container">
    <?php
    if ($importResult) {
        ?>
        <?php
        if ($importResult == IMPORT_SUCCESS) {
            ?>
            <p class="text-center bg-info" id="top-message">
                导入总表 CSV 数据成功。
            </p>
            <?php
        } elseif ($importResult == IMPORT_FAILED) {
            ?>
            <p class="text-center bg-danger" id="top-message">
                导入总表 CSV 数据失败。<br/>
                请检查选择的是否是主表 CSV 文件，以及格式是否符合要求。
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
        <h1 class="text-center">导入总表 CSV 数据</h1>
    </div>
    <form method="post" class="form-horizontal" enctype="multipart/form-data">
        <input type="file" name="csv-file" class="hidden" id="file-selector"
               accept="text/csv, application/csv"/>

        <div class="col-md-4 col-md-offset-4">
            <div class="form-group">
                <input type="text" readonly="readonly" class="form-control form-control-static text-center"
                       id="file-name-text"/>
            </div>
        </div>
        <div class="panel-group col-md-4 col-md-offset-4">
            <a class="btn btn-block btn-default" id="select-file-button" href="javascript:;" target="_blank">选择文件
                (*.csv)</a>
        </div>
        <div class="panel-group col-md-4 col-md-offset-4">
            <button type="submit" class="btn btn-block btn-success" id="upload-csv">
                上传
            </button>
        </div>
    </form>
    <div class="col-md-12 text-center form-group">
        <a href="cp.php">返回上一级</a>
    </div>
</div>
<script type="text/javascript">
    (function () {
        var jqUploadCsv = $('#upload-csv');
        var jqSelectFileButton = $('#select-file-button');
        var jqFileSelector = $('#file-selector');
        var jqFileNameText = $('#file-name-text');
        jqUploadCsv.attr('disabled', true);
        jqSelectFileButton.on('click', function () {
            jqFileSelector.click();
        });
        jqFileSelector.on('change', function () {
            //alert(nrutil.getFileName(jqFileSelector[0].value));
            var fileName = nrutil.getFileName(jqFileSelector[0].value);
            jqFileNameText[0].value = fileName;
            jqFileNameText[0].title = fileName;
            // 过滤保证 .csv 后缀名
            jqUploadCsv.attr('disabled', !(fileName.length > 0 && /[\w\W]+\.[Cc][Ss][Vv]$/.test(fileName)));
        });
    })();
</script>
<?php
require_once '../template/footer.php';
?>
</body>
</html>
