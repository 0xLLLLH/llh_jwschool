<?php
/**
 * Created by Phpstorm.
 * User: 0xLLLLH
 * Date: 2016.3.31
 * Description：个人中心
 */
global  $_W,$_GPC;
load()->model('mc');
$avatar = '';
//echo $_W['openid'].'<br>';
//echo mc_openid2uid($_W['openid']);//通过openid获取uid
if (!empty($_W['member']['uid'])) {
    $member = mc_fetch(intval($_W['member']['uid']), array('avatar','nickname','gender'));//获取uid的avatar字段
    //var_dump($member);
    if (!empty($member)) {
        $avatar = $member['avatar'];
        $nickname = $member['nickname'];
        $gender = $member['gender'];
    }
}
if (empty($avatar)) {
    $fan = mc_fansinfo($_W['openid']); //获取粉丝信息
    if (!empty($fan)) {
        $avatar = $fan['avatar'];
    }
}
if (empty($avatar)) {
    $userinfo = mc_oauth_userinfo();//调用oauth用户授权获取资料并更新会员信息
    //var_dump($userinfo);
    if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['avatar'])) {
        $avatar = $userinfo['avatar'];
        $nickname = $userinfo['nickname'];
        $gender = $userinfo['sex'];
    }
}
/*
if (empty($avatar) && !empty($_W['member']['uid'])) {
    $avatar = mc_require($_W['member']['uid'], array('avatar'));//显示表单让用户填写
}*/
/*if (empty($avatar)) {
    // 提示用户关注公众号。;
    //echo "最终没有获取到头像,follow: {$_W['fans']['follow']}";
} else {
/*    echo <<<IMG
  <img src="$avatar">
IMG;
}*/
include $this->template('usercenter');