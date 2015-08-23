<?php
/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/22
 * Time: 18:06
 */

require_once 'DB.php';
require_once 'util.php';
require_once '../config.php';

/**
 * @var array $_CONFIG
 */
global $_CONFIG;

/**
 * @var int $time
 * @var string $m
 * @var array $data
 */
$time = $_GET['time'];
$time = intval($time);
$m = $_GET['m'];

switch ($m) {
    case 'global':
        $m = 1;
        break;
    case 'new':
        $m = 2;
        break;
    default:
        $m = 0;
        break;
}

$db = new DB();
$db->Init();
switch ($m) {
    case 1:
        $data = $db->QueryGlobalStat();
        break;
    case 2:
        $data = $db->QueryRegStatAfter($time);
        break;
    default:
        break;
}
$db->Uninit();

if ($m > 0) {
    $data = util::ConvertCharsetGbkToUtf8($data, $_CONFIG['flags']['charset-hack']);
}

/**
 * @param array $l2array
 * @param string $indexName
 * @param mixed $valueToFind
 * @return bool|int|string
 */
function __findIndexInL2Array($l2array, $indexName, $valueToFind)
{
    foreach ($l2array as $k => $v) {
        if ($v[$indexName] == $valueToFind) {
            return $k;
        }
    }
    return false;
}

/**
 * @param array $array1
 * @param array $array2
 * @param string $countColumnName
 * @param string $groupColumnName
 * @param string $allText
 * @param string $regText
 * @param string $rateText
 * @param string $title
 */
function __generateComparativeRows($array1, $array2, $countColumnName, $groupColumnName, $allText, $regText, $rateText, $title)
{
    $groupValues = array();
    foreach ($array1 as $k => $v) {
        if (!in_array($v[$groupColumnName], $groupValues)) {
            $groupValues[] = $v[$groupColumnName];
        }
    }
    foreach ($array2 as $k => $v) {
        if (!in_array($v[$groupColumnName], $groupValues)) {
            $groupValues[] = $v[$groupColumnName];
        }
    }
    $r1 = array();
    $r2 = array();
    foreach ($groupValues as $v) {
        $k = __findIndexInL2Array($array1, $groupColumnName, $v);
        $count = $k === false ? 0 : $array1[$k][$countColumnName];
        $r1[] = array($groupColumnName => $v, $countColumnName => $count);
        $k = __findIndexInL2Array($array2, $groupColumnName, $v);
        $count = $k === false ? 0 : $array2[$k][$countColumnName];
        $r2[] = array($groupColumnName => $v, $countColumnName => $count);
    }
    ?>
    <tr>
        <th colspan="6" style="text-align: center; color: maroon;"><?php echo $title; ?></th>
    </tr>
    <?php
    foreach ($r1 as $k => $v) {
        ?>
        <tr>
            <th><?php echo $r1[$k][$groupColumnName];
                echo $allText; ?></th>
            <td>
                <?php echo $r1[$k][$countColumnName]; ?>
            </td>
            <th><?php echo $r2[$k][$groupColumnName];
                echo $regText; ?></th>
            <td>
                <?php echo $r2[$k][$countColumnName]; ?>
            </td>
            <th><?php echo $r1[$k][$groupColumnName];
                echo $rateText ?></th>
            <td>
                <?php echo round($r2[$k][$countColumnName] / $r1[$k][$countColumnName] * 100) . '%'; ?>
            </td>
        </tr>
        <?php
    }
}

switch ($m) {
    case 1:
        ?>
        <tr>
            <th colspan="6" style="text-align: center; color: maroon;">综合</th>
        </tr>
        <tr>
            <th>总计人数</th>
            <td>
                <?php echo $data['allnum'][0]['c']; ?>
            </td>
            <th>总计到达人数</th>
            <td>
                <?php echo $data['regnum'][0]['c']; ?>
            </td>
            <th>总计到达比率</th>
            <td>
                <?php echo round($data['regnum'][0]['c'] / $data['allnum'][0]['c'] * 100) . '%'; ?>
            </td>
        </tr>
        <tr>
            <th>女生人数</th>
            <td>
                <?php echo $data['allfemalenum'][0]['c']; ?>
            </td>
            <th>女生到达人数</th>
            <td>
                <?php echo $data['regfemalenum'][0]['c']; ?>
            </td>
            <th>女生到达比率</th>
            <td>
                <?php echo round($data['regfemalenum'][0]['c'] / $data['allfemalenum'][0]['c'] * 100) . '%'; ?>
            </td>
        </tr>

        <?php
        __generateComparativeRows($data['allclassnum'], $data['regclassnum'], 'c', 'classno', '班人数', '班到达', '班到达比率', '班级');
        __generateComparativeRows($data['allnatnum'], $data['regnatnum'], 'c', 'nat', '同学人数', '同学到达', '同学到达比率', '民族');
        __generateComparativeRows($data['allpolnum'], $data['regpolnum'], 'c', 'pol', '人数', '到达', '到达比率', '政治面貌');
        break;
    case 2:
        // 最新登记的人
        $dataString = json_encode($data);
        echo $dataString;
        break;
    default:
        break;
}
?>
