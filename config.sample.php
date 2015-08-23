<?php
/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/18
 * Time: 23:30
 */

global $_CONFIG;

define('NR_CONFIG_PRESET_INDEX', 0);

$configPresets = array(
    array('debug' => true, 'charset-hack' => false, 'csv-is-in-gbk' => true), // computer for debugging
    array('debug' => false, 'charset-hack' => true, 'csv-is-in-gbk' => true), // server in Aliyun
);

$_CONFIG['flags'] = $configPresets[NR_CONFIG_PRESET_INDEX];

$_CONFIG['siteroot'] = '/';

?>
