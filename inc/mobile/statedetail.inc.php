<?php
/**
 * Created by PhpStorm.
 * User: ZT
 * Date: 2016/4/2
 * Time: 23:29
 * Description：约游详情
 */
global $_W, $_GPC;
load()->func('file');
load()->func('tpl');
load()->model('mc');
/*if(empty($_W['openid'])){
    message("请先关注本公众号以完成信息注册！",'','warning');
}*/
if (isset($_GPC['cid'])) {
    echo $_GPC['cid'];
    $result=pdo_update('jwschool_comments', array('beread'=>1), array('id' => $_GPC['cid']));
    //$result= pdo_query("UPDATE  ".tablename('jwschool_comments')." SET beread = 1 WHERE id=:cid",array(':cid',);
    //echo $result;
}
if (isset($_GPC['submit'])) {
    /* echo $_GPC['content'];
     echo $_GPC['date'];
     echo date("Y-m-d H:i:s");
     echo $_GPC['tags'];
     echo $_GPC['location-hide'];*/
    /**
     * 图片上传
     */
    $pic_URL = "";
    if ($_FILES['pic1']['name']) {
        $pic1 = file_upload($_FILES['pic1'], 'image');
        $path1 = tomedia($pic1['path']);
        $pic_URL .= trim($path1);
    }
    if ($_FILES['pic2']['name']) {
        $pic2 = file_upload($_FILES['pic2'], 'image');
        $path2 = tomedia($pic2['path']);
        $pic_URL .= ";";
        $pic_URL .= trim($path2);
    }
    if ($_FILES['pic3']['name']) {
        $pic3 = file_upload($_FILES['pic3'], 'image');
        $path3 = tomedia($pic3['path']);
        $pic_URL .= ";";
        $pic_URL .= trim($path3);
    }
    // echo $pic_URL;
    $tags_arr = explode(' ', $_GPC['tags']);
    $tags = ";";
    foreach ($tags_arr as $value) {
        $tags = $tags . $value . ";";
    }
    $state_data['content'] = $_GPC['content'];
    $state_data['travel_TIME'] = $_GPC['date'];
    $state_data['release_TIME'] = date("Y-m-d H:i:s");
    $state_data['position'] = $_GPC['location-hide'];
    $state_data['openid'] = $_W['openid'];
    $state_data['pic_URL'] = $pic_URL;
    $state_data['tags'] = $tags;
    //var_dump($state_data);
    $result = pdo_insert('jwschool_moments', $state_data);
    $state_ID = pdo_insertid();
    if ($result) {
        message("恭喜，您的约游消息已发送成功", $this->createMobileUrl('statedetail', array('state_ID' => $state_ID)), success);
    } else {
        message("抱歉，出了点小状况！！", $this->createMobileUrl('statedetail'), error);
    }
    //echo $state_ID;
}
if (isset($_GPC['state_ID'])) {
    pdo_query("UPDATE  " . tablename('jwschool_moments') . " SET views = views+1 WHERE id= :state_ID", array(':state_ID' => $_GPC['state_ID']));
    $state_ID = $_GPC['state_ID'];
    $tb_moments = tablename('jwschool_moments');
    $tb_members = tablename('mc_members');
    $state_detail = pdo_fetch("select openid,comments_NUM,content,pic_URL,position,release_TIME,tags,travel_TIME,views from "
        . $tb_moments . " where " . $tb_moments . ".id= :state_ID", array(':state_ID' => $state_ID));
    $uid = mc_openid2uid($state_detail['openid']);
    $member = mc_fetch($uid, array('avatar', 'nickname', 'gender', 'birthyear', 'birthmonth', 'birthday'));

    $string_tag = trim($state_detail['tags'], ";");
    $tags = explode(';', $string_tag);
    $pic_URL = $this->getPicUrlArr($state_detail['pic_URL']);
    //var_dump($member);
    //$pic_URL = explode(';', $state_detail['pic_URL']);
    /* foreach ($pic_URL as $key => $v) {
         $temp = explode('/', $v);
         $pic_NAME = $temp[count($temp) - 1];
         $cnt = 0;
         //var_dump($temp);
         for ($i = 0; $i < 4; ++$i) {
             $cnt += strlen($temp[$i]);
             ++$cnt;
         }
         $thumb_URL = substr_replace($v, "thumb_" . $pic_NAME, strlen($v) - strlen($pic_NAME), strlen($pic_NAME));
         $save_thumb_URL = substr_replace($thumb_URL, "../", 0, $cnt);
         $pic_URL[$key] = $thumb_URL;
         //echo $thumb_URL."<br>";
         // echo $v."<br>";
         //echo $save_thumb_URL . '<br>';
         if (!file_exists($save_thumb_URL)) {//判断缩略图是否已经有缓存
             $this->thumb($v, 200, 200, $save_thumb_URL);//生成缩略图
         }
     }*/
    $travel_date = explode('-', $state_detail['travel_TIME']);
    $state_detail['travel_TIME'] = $travel_date[0] . '年' . $travel_date[1] . '月' . $travel_date[2] . '日';
    $state_detail['pic_URL'] = $pic_URL;
    $state_detail['tags'] = $tags;
    $state_detail['uid'] = $uid;
    $state_detail['avatar'] = $member['avatar'];
    $state_detail['nickname'] = $member['nickname'];
    $state_detail['gender'] = $member['gender'];
    $state_detail['state_id'] = $_GPC['state_ID'];
    $state_detail['age'] = $this->getAge($member['birthyear'], $member['birthmonth'], $member['birthday']);
    //var_dump($state_detail);//状态所有信息；
    /**************************************************************************************************************/

    $comment_data = pdo_fetchall("SELECT * FROM " . tablename('jwschool_comments') . " WHERE state_ID = :state_ID order by release_TIME", array(':state_ID' => $_GPC['state_ID']));
    foreach ($comment_data as $k => $v) {
        if (!empty($v['to_WHO'])) {
            $to_ID = mc_openid2uid($v['to_WHO']);
            $member_TO = mc_fetch(intval($to_ID), array('avatar', 'nickname'));
            $comment_data[$k]['avatar_TO'] = $member_TO['avatar'];
            $comment_data[$k]['nickname_TO'] = $member_TO['nickname'];
        }
        $from_ID = mc_openid2uid($v['from_WHO']);
        $member_FROM = mc_fetch(intval($from_ID), array('avatar', 'nickname'));
        $comment_data[$k]['avatar_FROM'] = $member_FROM['avatar'];
        $comment_data[$k]['nickname_FROM'] = $member_FROM['nickname'];
    }
    //  print_r($comment_data);//评论所有信息

}
include $this->template('statedetail');


