<?php
/**
 * Created by PhpStorm.
 * User: ZT
 * Date: 2016/4/5
 * Time: 20:36
 */
global $_W, $_GPC;
if ($_W['ispost'] || $_W['isajax']) {
    $to_WHO = $_GPC['to_WHO'];
    $content = $_GPC['content'];
    $state_ID = $_GPC['state_ID'];
    $date = date("Y-m-d H:i:s");
    pdo_query("UPDATE  ".tablename('jwschool_moments')." SET comments_NUM = comments_NUM+1");
    $comment_data = array(
        'release_TIME' => $date,
        'content' => $content,
        'from_WHO' => $_W['openid'],
        'state_ID' => $state_ID
    );
    if (!empty($to_WHO)) {
        $comment_data['to_WHO'] = $to_WHO;
        $to_ID = mc_openid2uid($to_WHO);
        $member_TO = mc_fetch(intval($to_ID), array('avatar', 'nickname'));
        $rt['avatat_TO'] = $member_TO['avatar'];
        $rt['nickname_TO'] = $member_TO['nickname'];
    }
    $member_FROM = mc_fetch(intval($_W['member']['uid']), array('avatar', 'nickname'));
    $result = pdo_insert('jwschool_comments', $comment_data);
    $rt['state_ID'] = pdo_insertid();
    $rt['from_WHO'] = $_W['openid'];
    $rt['content'] = $content;
    $rt['comment_time'] = $date;
    $rt['avatar_FROM'] = $member_FROM['avatar'];
    $rt['nickname_FROM'] = $member_FROM['nickname'];
    print_r(json_encode($rt));
}
