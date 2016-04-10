<?php
/**
 * Created by Phpstorm.
 * User: 0xLLLLH
 * Date: 2016.4.6
 * Description：我的评论
 */
global $_W, $_GPC;
if(empty($_W['openid'])){
    message("请先关注本公众号以完成信息注册！",'','warning');
}
load()->model('mc');
$state_ID=pdo_fetchall("SELECT DISTINCT state_ID FROM ".tablename('jwschool_comments')." WHERE from_WHO=:from_WHO ORDER BY release_TIME DESC ",array(':from_WHO'=>$_W['openid']));
//var_dump($state_ID);
$member = mc_fetch(intval($_W['member']['uid']), array('nickname'));
foreach ($state_ID as $k=>$v){
    $comments_data[$k]['state_ID']=$v['state_ID'];
    $comments_data[$k]['comments']=pdo_fetchall("SELECT id,content,release_TIME FROM ".tablename('jwschool_comments')." WHERE state_ID=:state_ID ORDER BY release_TIME DESC ",array(':state_ID'=>$v['state_ID']));
    $state_DATA = pdo_get('jwschool_moments',array('id'=>$v['state_ID']),array('openid','release_TIME','content','position','travel_TIME','views','comments_NUM'));
    $uid=mc_openid2uid($state_DATA['openid']);
    $comments_data[$k]['release_TIME']=$state_DATA['release_TIME'];
    $content_t=$state_DATA['content'];
    $content=mb_substr($content_t,0,20,'utf-8');
    if(mb_strlen($content_t,'utf-8')>20)
        $content.='...';
    $comments_data[$k]['content']=$content;
    $comments_data[$k]['position']=$state_DATA['position'];
    $comments_data[$k]['travel_TIME']=$state_DATA['travel_TIME'];
    $comments_data[$k]['views']=$state_DATA['views'];
    $comments_data[$k]['comments_NUM']=$state_DATA['comments_NUM'];
    $state_member = mc_fetch(intval($uid), array('avatar','nickname','gender','birthyear','birthmonth','birthday'));
    $age=$this->getAge($state_member['birthyear'],$state_member['birthmonth'],$state_member['birthday']);
    $comments_data[$k]['state_USER']['avatar']=$state_member['avatar'];
    $comments_data[$k]['state_USER']['nickname']=$state_member['nickname'];
    $comments_data[$k]['state_USER']['gender']=$state_member['gender'];
    $comments_data[$k]['state_USER']['age']=$age;
}
include $this->template('mycomments');