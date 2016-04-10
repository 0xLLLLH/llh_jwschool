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
    $state_ID=$_GPC['state_ID'];
    $result = pdo_delete('jwschool_comments',array('id'=>$cid));
    $result = pdo_query("UPDATE  ".tablename('jwschool_moments')." SET comments_NUM = comments_NUM-1 WHERE id=:state_ID",array(':state_ID'=>$state_ID));
    echo $result;
}
