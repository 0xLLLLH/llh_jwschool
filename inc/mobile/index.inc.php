<?php
<<<<<<< HEAD
=======
/**
 * Created by Phpstorm.
 * User: short
 * Date: 2016.3.31
 * Description：主页/个人主页/我的约游
 */
>>>>>>> refs/remotes/origin/develop
global $_W, $_GPC;
load()->model('mc');
if (isset($_GPC['op'])) {
    if ($_GPC['op'] == 'search') {
        $keyword = $_GPC['keyword'];
        $state_data = pdo_fetchall("SELECT * FROM " . tablename('jwschool_moments') . " WHERE content LIKE '%" . $keyword . "%' OR tags LIKE '%;" . $keyword . ";%' ORDER BY release_TIME DESC LIMIT 10");
    } else if ($_GPC['op'] == 'homepage') {
        $uid = mc_openid2uid($_GPC['openid']);
        //个人信息member
        $home_member = mc_fetch(intval($uid), array('avatar','nickname', 'realname', 'gender', 'mobile', 'qq', 'birthyear', 'birthmonth', 'birthday', 'resideprovince', 'residecity', 'residedist'));
        //var_dump($home_member);
        $nickname = $home_member['nickname'];
        $avatar = $home_member['avatar'];
        $gender = $home_member['gender'];
        $location = $home_member['resideprovince'].$home_member['residecity'].$home_member['residedist'];
        $location = $location?$location:'未知';
        $qq = $home_member['qq']?$home_member['qq']:'未知';
        $mobile = $home_member['mobile'];
        $mobile=substr_replace($mobile,'****',3,4);
        $mobile=$mobile?$mobile:'未知';
        $birth = $home_member['birthyear'].'年'.$home_member['birthmonth'].'月'.$home_member['birthday'].'日';
        $age = $this->getAge($home_member['birthyear'], $home_member['birthmonth'], $home_member['birthday']);
        $state_data = pdo_fetchall("SELECT * FROM " . tablename('jwschool_moments') . "WHERE openid=:openid ORDER BY release_TIME DESC LIMIT 10", array(':openid' => $_GPC['openid']));
    } else if ($_GPC['op'] == 'mystates') {
        if(empty($_W['openid'])){
            message("请先关注本公众号以完成信息注册！",'','warning');
        }
        $state_data = pdo_fetchall("SELECT * FROM " . tablename('jwschool_moments') . "WHERE openid=:openid ORDER BY release_TIME DESC LIMIT 10", array(':openid' => $_GPC['openid']));
    }
} else {
    $state_data = pdo_fetchall("SELECT * FROM " . tablename('jwschool_moments') . " ORDER BY release_TIME DESC LIMIT 12");
}
//var_dump($state_data);
foreach ($state_data as $k => $v) {
    $uid = mc_openid2uid($v['openid']);
    $member = mc_fetch(intval($uid), array('avatar', 'nickname', 'gender', 'birthyear', 'birthmonth', 'birthday'));
    $state_data[$k]['avatar'] = $member['avatar'];
    $state_data[$k]['nickname'] = $member['nickname'];
    $state_data[$k]['gender'] = $member['gender'];
    $state_data[$k]['age'] = $this->getAge($member['birthyear'], $member['birthmonth'], $member['birthday']);
    $pic_URL = trim($v['pic_URL'], ';');
    $pic_arr = explode(';', $pic_URL);
    $state_data[$k]['pic_URL'] = $this->getPicUrlArr($v['pic_URL']);
    $string_tag = trim($v['tags'], ";");
    $state_data[$k][tags] = explode(';', $string_tag);
    $position = explode('-', $v['position']);
    $state_data[$k]['position'] = $position[0] . $position[1];
}
<<<<<<< HEAD

=======
/*echo $state_data['openid'];
echo $uid;*/
/*$url=$this->createMobileUrl('usercenter');
$pagelist=array('个人中心'=>$url);*/
>>>>>>> refs/remotes/origin/develop
include $this->template('index');