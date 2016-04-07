<?php
/**
 * Created by Phpstorm.
 * User: short
 * Date: 2016.3.31
 * Description：主页/个人主页/我的约游
 */
global $_W, $_GPC;
load()->model('mc');
if (isset($_GPC['op'])) {
    if ($_GPC['op'] == 'search') {
        $keyword = $_GPC['keyword'];
        $state_data = pdo_fetchall("SELECT * FROM " . tablename('jwschool_moments') . " WHERE content LIKE '%" . $keyword . "%' OR tags LIKE '%;" . $keyword . ";%' ORDER BY release_TIME DESC LIMIT 5");
    } else if ($_GPC['op'] == 'homepage') {
        $uid=mc_openid2uid($_GPC['openid']);
        //个人信息member
        $member = mc_fetch(intval($uid),  array('nickname','realname','gender','mobile','qq','birthyear','birthmonth','birthday','resideprovince','residecity','residedist'));
        $state_data = pdo_fetchall("SELECT * FROM " . tablename('jwschool_moments') . "WHERE openid=:openid ORDER BY release_TIME DESC LIMIT 5",array(':openid'=>$_GPC['openid']));
    } else if ($_GPC['op'] == 'mystates') {
        $state_data = pdo_fetchall("SELECT * FROM " . tablename('jwschool_moments') . "WHERE openid=:openid ORDER BY release_TIME DESC LIMIT 5",array(':openid'=>$_GPC['openid']));
    }
} else {
    $state_data = pdo_fetchall("SELECT * FROM " . tablename('jwschool_moments') . " ORDER BY release_TIME DESC LIMIT 5");
}
//var_dump($state_data);
foreach ($state_data as $k => $v) {
    $uid = mc_openid2uid($v['openid']);
    $member = mc_fetch(intval($uid), array('avatar', 'nickname', 'gender', 'birthyear', 'birthmonth', 'birthday'));
    $state_data[$k]['avatar'] = $member['avatar'];
    $state_data[$k]['nickname'] = $member['nickname'];
    $state_data[$k]['gender'] = $member['gender'];
    $state_data[$k]['age'] = $this->getAge($member['birthyear'], $member['birthmonth'], $member['birthday']);
    $pic_URL = trim($v['pic_URL']);
    $pic_arr = explode(';', $pic_URL);
    $state_data[$k]['pic_URL'] = $this->getPicUrlArr($v['pic_URL']);
    $string_tag = trim($v['tags'], ";");
    $state_data[$k][tags] = explode(';', $string_tag);
    $position = explode('-', $v['position']);
    $state_data[$k]['position'] = $position[0] . $position[1];
}
/*echo $state_data['openid'];
echo $uid;*/
/*$url=$this->createMobileUrl('usercenter');
$pagelist=array('个人中心'=>$url);*/
include $this->template('index');