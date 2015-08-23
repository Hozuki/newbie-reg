<?php

/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/16
 * Time: 19:54
 */

require_once 'model/SubmitInfo.include.php';
require_once 'model/FeedbackInfo.include.php';
// 不知道为什么这里必须这么写，其他地方似乎没问题
require_once dirname(__FILE__) . '/../config.php';

/**
 * @var array $_CONFIG
 * @var string $sql_username
 * @var string $sql_password
 * @var string $sql_host
 * @var string $sql_database
 */
global $_CONFIG;

if ($_CONFIG['flags']['debug']) {
    require_once 'data/sql.conn.debug.php';
} else {
    require_once 'data/sql.conn.release.php';
}

define('DATA_TABLE_STU_COLLECT', 'stu_collect');
define('DATA_TABLE_STU_SUBMIT', 'stu_submit');
define('DATA_TABLE_STU_FEEDBACK', 'stu_feedback');

class DB
{

    /**
     * @var mysqli $connection
     */
    var $connection = null;

    public function Init()
    {
        if (!$this->connection) {
            try {
                $this->connection = new mysqli(SQL_HOST, SQL_USERNAME, SQL_PASSWORD, SQL_DATABASE);
            } catch (Exception $ex) {
            }
            if ($this->connection->connect_errno) {
                echo $this->connection->connect_error;
            }
        }
    }

    public function Uninit()
    {
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
        }
    }

    /**
     * @return mysqli
     */
    public function GetConnection()
    {
        return $this->connection;
    }

    /**
     * @param SubmitInfo $info
     * @param bool $confirmed 是否已经确认信息无误
     * @return SubmitResult|null
     */
    public function GetFeedbackFromSubmit($info, $confirmed = false)
    {
        if (!$this->connection) {
            return null;
        }
        /**
         * @var FeedbackInfo $si
         * @var SubmitResult $sr
         */
        $sr = new SubmitResult();
        $admissionID = $this->connection->real_escape_string($info->admissionID);
        $query = "SELECT * FROM stu_feedback WHERE aid = '{$admissionID}' LIMIT 1;";
        $result = $this->connection->query($query);
        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $sr->feedback = $this->fillStudentFeedback($row);
                if ($confirmed or strcmp($sr->feedback->studentName, $info->studentName) == 0) {
                    $sr->actionResult = SUBMIT_RESULT_SUCCEEDED;
                } else {
                    $sr->actionResult = SUBMIT_RESULT_NEED_CONFIRMATION;
                }
            }
            $result->free();
        }
        if ($sr->actionResult == SUBMIT_RESULT_SUCCEEDED) {
            // 某些人不停刷新有可能造成多次 POST
            // 不过记录是没法多次 INSERT 的啦
            $this->AddSubmitRecord($info);
        }
        return $sr;
    }

    /**
     * @param SubmitInfo $info
     * @return bool
     */
    public function AddSubmitRecord($info)
    {
        global $_CONFIG;
        if (!$this->connection) {
            return false;
        }
        $admissionID = $this->connection->real_escape_string($info->admissionID);
        $studentName = util::ConvertCharsetUtf8ToGbk($info->studentName, $_CONFIG['flags']['charset-hack']);
        $studentName = $this->connection->real_escape_string($studentName);
        $postTime = $this->connection->real_escape_string($info->submitTime);
        $query = "INSERT INTO stu_commit (aid, sname, stime) VALUES ('{$admissionID}', '{$studentName}', '{$postTime}');";
        return $this->connection->query($query);
    }

    /**
     * @return bool Is it successful?
     */
    public function ClearSubmitData()
    {
        if (!$this->connection) {
            return false;
        }
        $query = 'DELETE FROM stu_commit;';
        $result = $this->connection->query($query);
        return $result;
    }

    /**
     * @param array $columnNames
     * @param array $data
     * @return bool
     */
    public function ImportData_Collected($columnNames, $data)
    {
        return $this->importCsvData(DATA_TABLE_STU_COLLECT, $columnNames, $data);
    }

    /**
     * @param array $columnNames
     * @param array $data
     * @return bool
     */
    public function ImportData_Submitted($columnNames, $data)
    {
        return $this->importCsvData(DATA_TABLE_STU_SUBMIT, $columnNames, $data);
    }

    /**
     * @param array $columnNames
     * @param array $data
     * @return bool
     */
    public function ImportData_Feedback($columnNames, $data)
    {
        return $this->importCsvData(DATA_TABLE_STU_FEEDBACK, $columnNames, $data);
    }

    /**
     * @param int $time
     * @return array|null
     */
    public function QueryRegStatAfter($time)
    {
        if (!$this->connection) {
            return array();
        }
        $query = "SELECT * FROM stu_commit WHERE stime >= {$time};";
        $result = $this->connection->query($query);
        $r = array();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                for ($i = 0; $i < $numRows; $i++) {
                    $r[] = $result->fetch_assoc();
                }
            }
            $result->free();
        }
        return $r;
    }

    /**
     * @return array
     */
    public function QueryGlobalStat()
    {
        // 之后有连接保护，所以这里不进行判断
        // 直接返回 null 或者返回带 null 的数组元素，在 JavaScript 访问时容易出现错误而停止执行
        // 因此如果查询失败则返回空数组

        // 结果结构如下
        // array(
        // [c pol]
        // [1 共产党员]
        // [1 民主人士]
        // [2 群众]
        // )

        // 总计人数
        $queryAllNum = "SELECT COUNT(1) AS c FROM stu_collect;";
        $resultAllNum = $this->runNormalFullQuery($queryAllNum);
        // 总计到的人数
        $queryRegNum = "SELECT COUNT(1) AS c FROM stu_commit;";
        $resultRegNum = $this->runNormalFullQuery($queryRegNum);
        // 每个班级的人数
        $queryAllClassNum = "SELECT COUNT(1) AS c, classno FROM stu_feedback GROUP BY classno;";
        $resultAllClassNum = $this->runNormalFullQuery($queryAllClassNum);
        // 每个班级到的人数
        $queryRegClassNum = "SELECT COUNT(1) AS c, classno FROM stu_commit, stu_feedback WHERE stu_commit.aid = stu_feedback.aid GROUP BY classno;";
        $resultRegClassNum = $this->runNormalFullQuery($queryRegClassNum);
        // 女生人数
        $queryAllFemaleNum = "SELECT COUNT(1) AS c FROM stu_collect WHERE gender = '" . GENDER_FEMALE . "';";
        $resultAllFemaleNum = $this->runNormalFullQuery($queryAllFemaleNum);
        // 女生到的人数
        $queryRegFemaleNum = "SELECT COUNT(1) AS c FROM stu_commit, stu_collect WHERE stu_collect.aid = stu_commit.aid AND stu_collect.gender = '" . GENDER_FEMALE . "';";
        $resultRegFemaleNum = $this->runNormalFullQuery($queryRegFemaleNum);
        // 总计民族分布
        $queryAllNatNum = "SELECT COUNT(1) AS c, nat FROM stu_collect GROUP BY nat;";
        $resultAllNatNum = $this->runNormalFullQuery($queryAllNatNum);
        // 民族到的人数
        $queryRegNatNum = "SELECT COUNT(1) AS c, nat FROM stu_commit, stu_collect WHERE stu_collect.aid = stu_commit.aid GROUP BY nat;";
        $resultRegNatNum = $this->runNormalFullQuery($queryRegNatNum);
        // 总计政治面貌分布
        $queryAllPolNum = "SELECT COUNT(1) AS c, pol FROM stu_collect GROUP BY pol;";
        $resultAllPolNum = $this->runNormalFullQuery($queryAllPolNum);
        // 政治面貌到的人数
        $queryRegPolNum = "SELECT COUNT(1) AS c, pol FROM stu_commit, stu_collect WHERE stu_collect.aid = stu_commit.aid GROUP BY pol;";
        $resultRegPolNum = $this->runNormalFullQuery($queryRegPolNum);
        $result = array(
            'allnum' => $resultAllNum,
            'regnum' => $resultRegNum,
            'allclassnum' => $resultAllClassNum,
            'regclassnum' => $resultRegClassNum,
            'allfemalenum' => $resultAllFemaleNum,
            'regfemalenum' => $resultRegFemaleNum,
            'allnatnum' => $resultAllNatNum,
            'regnatnum' => $resultRegNatNum,
            'allpolnum' => $resultAllPolNum,
            'regpolnum' => $resultRegPolNum
        );
        return $result;
    }

    /**
     * @param string $tableName
     * @param array $columnNames
     * @param array $data
     * @return bool
     */
    private function importCsvData($tableName, $columnNames, $data)
    {
        /**
         * @var array $_CONFIG
         */
        global $_CONFIG;

        if (!$this->connection) {
            return false;
        }
        if (!is_array($columnNames) || !is_array($data) || count($columnNames) <= 0 || count($data) <= 0) {
            return false;
        }

        // 先清空数据
        $query = "DELETE FROM {$tableName};";
        if (!$this->connection->query($query)) {
            return false;
        }
        // 构建查询字符串
        $columnNames2 = array();
        foreach ($columnNames as $value) {
            $columnNames2[] = $this->connection->real_escape_string($value);
        }
        $columnNamesString = '(' . implode(',', $columnNames2) . ')';
        // 为什么阿里云那边会出来换行符？
        $columnNamesString = str_replace('\r', '', $columnNamesString);
        $columnNamesString = str_replace('\n', '', $columnNamesString);
        $dataStringArray = array();
        $str = null;
        foreach ($data as $valueArray) {
            $str = implode('\',\'', $valueArray);
            $dataStringArray[] = '(\'' . $str . '\')';
        }
        $dataString = implode(',', $dataStringArray);
        $dataString = str_replace('\r', '', $dataString);
        $dataString = str_replace('\n', '', $dataString);
        $dataString = str_replace("\r", '', $dataString);
        $dataString = str_replace("\n", '', $dataString);
        $dataString = util::ConvertCharsetUtf8ToGbk($dataString, $_CONFIG['flags']['charset-hack']);
        $query = "INSERT INTO {$tableName} {$columnNamesString} VALUES {$dataString};";
        return $this->connection->query($query);
    }

    /**
     * @param array $a
     * @return FeedbackInfo
     */
    private static function fillStudentFeedback(array $a)
    {
        global $_CONFIG;
        $si = new FeedbackInfo();
        $a = util::ConvertCharsetGbkToUtf8($a, $_CONFIG['flags']['charset-hack']);
        $si->admissionID = trim($a['aid']);
        $si->studentName = trim($a['sname']);
        $si->studentNumber = trim($a['sno']);
        $si->dormNumber = trim($a['dormno']);
        $si->classNumber = trim($a['classno']);
        $si->adviserName = trim($a['advname']);
        return $si;
    }

    /**
     * @param string $query
     * @return array
     */
    private function runNormalFullQuery($query)
    {
        if (!$this->connection) {
            return array();
        }
        $result = $this->connection->query($query);
        $r = array();
        if ($result) {
            $numRows = $result->num_rows;
            if ($numRows > 0) {
                // 直接用 fetch_all() 则返回的数组键是整数而不是列名称
                for ($i = 0; $i < $numRows; $i++) {
                    $r[] = $result->fetch_assoc();
                }
            }
            $result->free();
        }

        return $r;
    }

}

?>
