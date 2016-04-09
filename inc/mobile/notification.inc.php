<?php
/**
 * Created by Phpstorm.
 * User: 0xLLLLH
 * Date: 2016.4.7
 * Description：消息中心
 */

global $_W, $_GPC;
load()->model('mc');
$comment_data = pdo_fetchall("SELECT ".tablename('jwschool_comments').".release_TIME,".tablename('jwschool_comments').".id".",state_ID," . tablename('jwschool_comments') . ".content,from_WHO FROM "
    . tablename('jwschool_comments') . "," . tablename('jwschool_moments')
    . " WHERE " . tablename('jwschool_moments') . ".id=" . tablename('jwschool_comments') . ".state_ID AND beread=0 AND (to_WHO=:to_WHO OR (to_WHO=-1 AND openid=:openid)) ORDER BY "
    .tablename('jwschool_comments').".release_TIME DESC ", array(':to_WHO' => $_W['openid'],':openid'=>$_W['openid']));
foreach ($comment_data as $k=>$v){
    $uid = mc_openid2uid($v['from_WHO']);
    $member = mc_fetch(intval($uid), array('avatar', 'nickname'));
    $comment_data[$k]['avatar']=$member['avatar'];
    $comment_data[$k]['nickname']=$member['nickname'];
}
//var_dump($comment_data);
include $this->template('notification');