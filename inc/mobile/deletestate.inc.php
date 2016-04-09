<?php
/**
 * Created by PhpStorm.
 * User: ZT
 * Date: 2016/4/8
 * Time: 22:49
 */
global $_W, $_GPC;
if ($_W['ispost'] || $_W['isajax']) {
    $state_ID=$_GPC['state_ID'];
    $result = pdo_delete('jwschool_moments',array('id'=>$state_ID));
    $result = pdo_delete('jwschool_comments',array('state_ID'=>$state_ID));
    echo $result;
}
