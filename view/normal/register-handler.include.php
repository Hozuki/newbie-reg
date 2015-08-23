<?php
/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/19
 * Time: 17:17
 */

require_once '../../logic/model/SubmitInfo.include.php';
require_once '../../logic/model/FeedbackInfo.include.php';
require_once '../../logic/model/SubmitResult.include.php';
require_once '../../logic/util.php';
require_once '../../logic/DB.php';

define('REG_HANDLE_STATE_NONE', 0);
define('REG_HANDLE_STATE_POST_REG', 1);
define('REG_HANDLE_STATE_POST_CONFIRM', 2);

/**
 * @var FeedbackInfo $submitResult
 */
$submitResult = null;

if ($_POST['first-submit'] == 'yes') {
    $submitInfo = new SubmitInfo();
    $submitInfo->admissionID = $_POST['reg-aid'];
    $submitInfo->studentName = $_POST['reg-sname'];
    $submitInfo->submitTime = time();

    $db = new DB();
    $db->Init();
    $submitResult = $db->GetFeedbackFromSubmit($submitInfo);
    $db->Uninit();
} elseif ($_POST['confirm-submit'] == 'yes') {
    $submitInfo = new SubmitInfo();
    // 由于我们在页面中用了 htmlentities() 将潜在的攻击字符串编码了，因此这里解码回来
    // 别担心，提交的时候还会有一次 mysqli_real_escape_string()
    $submitInfo->admissionID = html_entity_decode($_POST['reg-aid']);
    $submitInfo->studentName = html_entity_decode($_POST['reg-sname']);
    $submitInfo->submitTime = time();

    $db = new DB();
    $db->Init();
    $submitResult = $db->GetFeedbackFromSubmit($submitInfo, true);
    $db->Uninit();
}

?>
