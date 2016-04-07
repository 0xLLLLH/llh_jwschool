<?php
/**
 * Created by PhpStorm.
 * User: ZT
 * Date: 2016/4/6
 * Time: 20:50
 * Description：ajax获得约游状态
 */
global $_W, $_GPC;
load()->model('mc');
if ($_W['ispost'] || $_W['isajax']) {
    $last_id = $_GPC['last_id'];
    if (!empty($_GPC['keyword'])) {
        $keyword = $_GPC['keyword'];
        $state_data = pdo_fetchall("SELECT * FROM " . tablename('jwschool_moments') . " WHERE id < " . $last_id . " AND ( content LIKE '%" . $keyword . "%' OR tags LIKE '%;" . $keyword . ";%' ) ORDER BY release_TIME DESC LIMIT 5");
    } else if (!empty($_GPC['openid'])) {
        $state_data = pdo_fetchall("SELECT * FROM " . tablename('jwschool_moments') . "WHERE openid=:openid ORDER BY release_TIME DESC LIMIT 5",array(':openid'=>$_GPC['openid']));
    } else {
        $state_data = pdo_fetchall("SELECT * FROM " . tablename('jwschool_moments') . "WHERE id < " . $last_id . " ORDER BY release_TIME DESC LIMIT 5");
    }
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
        $state_data[$k]['detail_link'] = $this->createMobileUrl('statedetail', array('state_ID' => $v['id']));
    }
    $state_data['size'] = count($state_data);
    $state_data['last_id'] = $state_data[$state_data['size'] - 1]['id'];
    print_r(json_encode($state_data));
}