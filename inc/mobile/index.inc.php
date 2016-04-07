<?php
/**
 * Created by Phpstorm.
 * User: 0xLLLLH
 * Date: 2016.3.31
 * Description：主页
 */
global $_W, $_GPC;
load()->model('mc');
$state_data = pdo_fetchall("SELECT * FROM ".tablename('jwschool_moments')."ORDER BY release_TIME DESC LIMIT 0,5");
//var_dump($state_data);
foreach($state_data as $k => $v){
    $uid=mc_openid2uid($v['openid']);
    $member = mc_fetch(intval($uid), array('avatar', 'nickname','gender','birthyear','birthmonth','birthday'));
    $state_data[$k]['avatar']=$member['avatar'];
    $state_data[$k]['nickname']=$member['nickname'];
    $state_data[$k]['gender']=$member['gender'];
    $state_data[$k]['age']=$this->getAge($member['birthyear'],$member['birthmonth'],$member['birthday']);
    $pic_URL=trim($v['pic_URL']);
    $pic_arr=explode(';',$pic_URL);
    $state_data[$k]['pic_URL']=  $this->getPicUrlArr($v['pic_URL']);
    $string_tag = trim($v['tags'], ";");
    $state_data[$k][tags]=explode(';', $string_tag);
    $position=explode('-',$v['position']);
    $state_data[$k]['position']=$position[0].$position[1];
}
/*echo $state_data['openid'];
echo $uid;*/
/*$url=$this->createMobileUrl('usercenter');
$pagelist=array('个人中心'=>$url);*/
include $this->template('index');