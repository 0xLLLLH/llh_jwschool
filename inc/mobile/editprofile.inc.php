<?php
/**
 * Created by Phpstorm.
 * User: 0xLLLLH
 * Date: 2016.4.1
 * Description：个人详细资料编辑
 */

global $_W, $_GPC;
load()->func('tpl');
load()->model('mc');

if(empty($_W['member']['uid'])){
    message("请先关注本公众号以完成信息注册！",'','warning');
}
if (isset($_GPC['submit'])) {
    $field=$_GPC['op'];//更新的字段
    mc_update(intval($_W['member']['uid']),array($field=>$_GPC[$field]));
    unset($_GPC['op']);//重置操作
}
/**
 * 信息填充
 */
if (!empty($_W['member']['uid'])) {
    $member = mc_fetch(intval($_W['member']['uid']), array('nickname','realname','gender','mobile','qq','birthyear','birthmonth','birthday','resideprovince','residecity','residedist'));
}

$member['birth'] = array(
    'year'  => $member['birthyear'],
    'month' => $member['birthmonth'],
    'day'   => $member['birthday']
);
$member['reside'] = array(
    'province' => $member['resideprovince'],
    'city'     => $member['residecity'],
    'district' => $member['residedist']
);

include $this->template('editprofile');
