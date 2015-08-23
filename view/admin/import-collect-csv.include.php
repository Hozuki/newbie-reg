<?php
/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/18
 * Time: 23:56
 */

require_once 'cookie-name.inc.php';

__requireLogIn();

define('IMPORT_SUCCESS', 1);
define('IMPORT_FAILED', 2);

$importResult = 0;

require_once '../../logic/DB.php';
require_once '../../logic/util.php';

if ($_FILES['csv-file']) {
    if ($_FILES['csv-file']['error'] == 0) {
        // 文件上传成功
        $csvData = util::LoadCsvData($_FILES['csv-file']['tmp_name']);
        if ($csvData) {
            $db = new DB();
            $db->Init();
            $r = $db->ImportData_Collected($csvData['columnNames'], $csvData['data']);
            $db->Uninit();
            $importResult = $r ? IMPORT_SUCCESS : IMPORT_FAILED;
        } else {
            $importResult = IMPORT_FAILED;
        }
        @unlink($_FILES['csv-file']['tmp_name']);
    } elseif ($_FILES['csv-file']['error'] == 4) {
        // 没有上传文件
    } else {
        // 文件上传失败
        $importResult = IMPORT_FAILED;
        if ($_FILES['csv-file']['tmp_name']) {
            @unlink($_FILES['csv-file']['tmp_name']);
        }
    }
}

?>
