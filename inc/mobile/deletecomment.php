<?php
/**
 * Created by PhpStorm.
 * User: ZT
 * Date: 2016/4/7
 * Time: 19:37
 * Description:ajax删除评论
 */
global $_W, $_GPC;
if ($_W['ispost'] || $_W['isajax']) {
    $cid = $_GPC['cid'];
    $result = pdo_delete('jwschool_comments',array('id'=>$cid));
    echo $result;
}