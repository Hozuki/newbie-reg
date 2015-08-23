<?php
/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/17
 * Time: 15:18
 */

// 这个也得是别扭的访问
include_once dirname(__FILE__) . '/../../config.php';

global $_CONFIG;
$basepath = $_CONFIG['siteroot'];

echo <<<LOL
<meta charset="utf-8"/>
<script src="{$basepath}js/jquery-2.1.4.min.js" type="text/javascript"></script>
<script src="{$basepath}js/bootstrap-3.3.5.min.js" type="text/javascript"></script>
<link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
<link href="{$basepath}css/nr.css" rel="stylesheet" type="text/css"/>
<script src="{$basepath}js/nr-common.js" type="application/javascript"></script>
LOL;

?>

<script type="text/javascript">
    var nrconfig = {
        siteroot: '<?php echo $basepath; ?>'
    };
</script>
