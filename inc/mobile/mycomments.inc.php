<?php
/**
 * Created by Phpstorm.
 * User: 0xLLLLH
 * Date: 2016.4.6
 * Description：我的评论
 */
global $_W, $_GPC;
$uid=mc_openid2uid($_W['openid']);
$member = mc_fetch(intval($uid), array('avatar','nickname'));
$state_ID=pdo_fetchall("SELECT DISTINCT state_ID FROM ".tablename('jwschool_comments')." WHERE from_WHO=:from_WHO ORDER BY release_TIME DESC ",array(':from_WHO'=>$_W['openid']));
//var_dump($state_ID);
foreach ($state_ID as $v){
    $comments_data[$v['state_ID']]=pdo_fetchall("SELECT id,content,release_TIME FROM ".tablename('jwschool_comments')." WHERE state_ID=:state_ID ORDER BY release_TIME DESC ",array(':state_ID'=>$v['state_ID']));
}
$comments_data['avatar']=$member['avatar'];
$comments_data['nickname']=$member['nickname'];
var_dump($comments_data);//以状态号为键值的评论数据

include $this->template('mycomments');