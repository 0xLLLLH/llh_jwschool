<?php
/**
 * Created by PhpStorm.
 * User: ZT
 * Date: 2016/4/2
 * Time: 23:29
 */
global $_W, $_GPC;
load()->func('file');
load()->func('tpl');
load()->model('mc');
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
    $state_data['uid'] = $_W['member']['uid'];
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
    $state_ID = $_GPC['state_ID'];
    $tb_moments = tablename('jwschool_moments');
    $tb_members = tablename('mc_members');
    $state_detail = pdo_fetch("SELECT " . $tb_members . ".uid,nickname, gender, comments_NUM,content,pic_URL," . $tb_moments . ".position,release_TIME,tags,travel_TIME,views FROM " . $tb_members . ","
        . $tb_moments . " WHERE " . $tb_moments . ".uid=" . $tb_members . ".uid and " . $tb_moments . ".id= :state_ID", array(':state_ID' => $state_ID));
    $pic_URL = explode(';', $state_detail['pic_URL']);
    $string_tag = trim($state_detail['tags'], ";");
    $tags = explode(';', $string_tag);
    $state_detail['pic_URL'] = $pic_URL;
    $state_detail['tags'] = $tags;
    //  var_dump($state_detail);//状态所有信息；
}

include $this->template('statedetail');
