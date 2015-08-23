<?php

/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/16
 * Time: 20:17
 */

require_once 'model/gender.inc.php';
require_once dirname(__FILE__) . '/../config.php';

class util
{

    /**
     * @param int $gender 性别
     * @return string 性别描述
     */
    public static function GenderName($gender)
    {
        switch ($gender) {
            case GENDER_MALE:
                return '男';
            case GENDER_FEMALE:
                return '女';
            default:
                return '保密';
        }
    }

    /**
     * 计算到当时为止的年龄，到当天才进位
     * @param int $year 出生年
     * @param int $month 出生月
     * @param int $day 出生日
     * @return int 年龄
     */
    public static function CalculateAge($year, $month, $day)
    {
        $dateArray = getdate();
        $cYear = $dateArray['year'];
        $cMonth = $dateArray['mon'];
        $cDay = $dateArray['mday'];
        /**
         * @var int $baseYearDiff
         * @var int $yearDiff
         */
        $baseYearDiff = $cYear - $year;
        // 如果当前的月日小于出生的月日，则需要减去一岁
        if ($cMonth < $month) {
            $yearDiff = $baseYearDiff - 1;
        } else if ($cDay < $day) {
            $yearDiff = $baseYearDiff - 1;
        } else {
            $yearDiff = $baseYearDiff;
        }
        return $yearDiff;
    }

    /**
     * @param array|string $var
     * @param string $inCharset
     * @param string $outCharset
     * @param bool $switch Do you really want to call this function?
     * @return array|string
     */
    public static function ConvertCharset($var, $inCharset, $outCharset, $switch)
    {
        if ($switch) {
            if (is_string($var)) {
                return iconv($inCharset, $outCharset, $var);
            } elseif (is_array($var)) {
                $array = array();
                foreach ($var as $k => $v) {
                    $array[$k] = util::ConvertCharset($v, $inCharset, $outCharset, $switch);
                }
                return $array;
            } else {
                return $var;
            }
        } else {
            return $var;
        }
    }

    /**
     * @param mysqli $connection
     * @param array|string $array
     * @return array|string
     */
    public static function RealEscapeStringRecursive($connection, $array)
    {
        if (!$connection) {
            return $array;
        }
        if (is_string($array)) {
            return $connection->real_escape_string($array);
        } elseif (is_array($array)) {
            $a = array();
            foreach ($array as $k => $v) {
                $a[$k] = $connection->real_escape_string($v);
            }
            return $a;
        } else {
            return util::RealEscapeStringRecursive($connection, strval($array));
        }
    }

    /**
     * @param array|string $var
     * @param bool $switch
     * @return array|string
     */
    public static function ConvertCharsetGbkToUtf8($var, $switch)
    {
        return util::ConvertCharset($var, 'GBK', 'UTF-8', $switch);
    }

    /**
     * @param array|string $var
     * @param bool $switch
     * @return array|string
     */
    public static function ConvertCharsetUtf8ToGbk($var, $switch)
    {
        return util::ConvertCharset($var, 'UTF-8', 'GBK', $switch);
    }

    /**
     * @param string $fullname
     * @return string
     */
    public static function GetFileName($fullname)
    {
        $pos = strrpos($fullname, '/');
        if ($pos === false) {
            $pos = strrpos($fullname, '\\');
        }
        if ($pos !== false) {
            return substr($fullname, $pos + 1);
        } else {
            return $fullname;
        }
    }

    /**
     * 给 DB 类的 Import_XXX() 函数使用
     * 返回 false，或者 array('columnNames','data')
     * @param string $fileName
     * @return array|bool
     */
    public static function LoadCsvData($fileName)
    {
        /**
         * @var array $_CONFIG
         */
        global $_CONFIG;

        if (!$fileName) {
            return false;
        }

        /**
         * @var resource $fp
         */
        $fp = null;
        try {
            $fp = fopen($fileName, 'r');

            /**
             * @var array $columnDescriptions
             * @var array $columnNames
             * @var array $data
             */

            // 输入的格式：
            // 第一行为表标题（显示名称）
            // 第二行为对应的数据库列名
            // 接下来每行都是数据

            $bufferLength = 65536;
            $result = array();

            function explodeCsv($string)
            {
                return explode(',', $string);
            }

            if (true) {
                if (feof($fp)) {
                    fclose($fp);
                    return false;
                }
                fgets($fp, $bufferLength);
                //$columnDescriptions = explodeCsv($ret);

                if (feof($fp)) {
                    fclose($fp);
                    return false;
                }
                $ret = fgets($fp, $bufferLength);
                $columnNames = explodeCsv($ret);

                if (feof($fp)) {
                    fclose($fp);
                    return false;
                }
                $ret = fgets($fp, $bufferLength);
                $data = array();
                do {
                    $data[] = explodeCsv($ret);
                    if (feof($fp)) {
                        break;
                    }
                    $ret = fgets($fp, $bufferLength);
                } while (true);
            }

            $result['columnNames'] = $columnNames;
            $result['data'] = $data;
            $result = util::ConvertCharsetGbkToUtf8($result, $_CONFIG['flags']['csv-is-in-gbk']);
        } catch (Exception $ex) {
            if ($fp) {
                fclose($fp);
            }
            die($ex->getMessage());
        }
        // 阿里云用的是 PHP 5.2，不支持 finally = =
        if ($fp) {
            fclose($fp);
        }
        return $result;
    }

}