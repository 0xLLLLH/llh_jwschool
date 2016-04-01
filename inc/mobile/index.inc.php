<?php
/**
* Created by Phpstorm.
* User: 0xLLLLH
* Date: 2016.3.31
* Description：主页
*/
global  $_W,$_GPC;

echo $_W['openid'];

load()->model('mc');
$avatar = '';

if (!empty($_W['member']['uid'])) {
    $member = mc_fetch(intval($_W['member']['uid']), array('avatar'));//获取uid的avatar字段
    if (!empty($member)) {
        $avatar = $member['avatar'];
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
    if (!is_error($userinfo) && !empty($userinfo) && is_array($userinfo) && !empty($userinfo['avatar'])) {
        $avatar = $userinfo['avatar'];
    }
}
if (empty($avatar) && !empty($_W['member']['uid'])) {
    $avatar = mc_require($_W['member']['uid'], array('avatar'));//显示表单让用户填写
}
if (empty($avatar)) {
    // 提示用户关注公众号。;
    echo "最终没有获取到头像,follow: {$_W['fans']['follow']}";
} else {

    echo <<<IMG
<img src="$avatar">
IMG;
    include $this->template('index');
}