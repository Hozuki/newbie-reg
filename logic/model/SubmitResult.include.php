<?php
/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/21
 * Time: 20:55
 */

require_once 'FeedbackInfo.include.php';

define('SUBMIT_RESULT_FAILED', 0);
define('SUBMIT_RESULT_SUCCEEDED', 1);
define('SUBMIT_RESULT_NEED_CONFIRMATION', 2);

class SubmitResult
{

    /**
     * @var FeedbackInfo $feedback
     */
    public $feedback;
    /**
     * @var int $actionResult
     */
    public $actionResult;

    function __construct()
    {
        $this->feedback = new FeedbackInfo();
        $this->actionResult = SUBMIT_RESULT_FAILED;
    }

}

?>
