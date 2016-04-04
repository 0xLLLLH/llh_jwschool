<?php
/**
 * Created by PhpStorm.
 * User: ZT
 * Date: 2016/4/2
 * Time: 23:29
 */
global $_W, $_GPC;
load()->func('file');
<<<<<<< HEAD
/*if(empty($_W['member']['uid'])){
    message("请先关注本公众号以完成信息注册！",'','warning');
}*/
/**
 * 获取会员信息
 */
if (!empty($_W['member']['uid'])) {
    $member = mc_fetch(intval($_W['member']['uid']), array('avatar','nickname','gender'));//获取uid的avatar字段
    //var_dump($member);
    if (!empty($member)) {
        $avatar = $member['avatar'];
        $nickname = $member['nickname'];
        $gender = $member['gender'];
    }
}
=======
load()->func('tpl');
load()->model('mc');
>>>>>>> refs/remotes/origin/develop
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
    foreach ($pic_URL as $v) {
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
        //echo $thumb_URL."<br>";
        // echo $v."<br>";
        echo $thumb_URL . '<br>';
        echo $save_thumb_URL . '<br>';
        thumb($v, 100, 100, $save_thumb_URL);
    }
}

/** 缩略图生成函数
 * @param $src 源url
 * @param null $width 宽度
 * @param null $height 高度
 * @param null $filename 目标目录
 * @return bool
 */
function thumb($src, $width = 200, $height = 200, $filename = null)
{
    if (!isset($width) && !isset($height))
        return false;
    if (isset($width) && $width <= 0)
        return false;
    if (isset($height) && $height <= 0)
        return false;

    $size = getimagesize($src);
    if (!$size)
        return false;

    list($src_w, $src_h, $src_type) = $size;
    $src_mime = $size['mime'];
    switch ($src_type) {
        case 1 :
            $img_type = 'gif';
            break;
        case 2 :
            $img_type = 'jpeg';
            break;
        case 3 :
            $img_type = 'png';
            break;
        case 15 :
            $img_type = 'wbmp';
            break;
        default :
            return false;
    }

    /* if (!isset($width))
         $width = $src_w * ($height / $src_h);
     if (!isset($height))
         $height = $src_h * ($width / $src_w);*/

    $imagecreatefunc = 'imagecreatefrom' . $img_type;
    $src_img = $imagecreatefunc($src);
    $dest_img = imagecreatetruecolor($width, $height);
    if ($src_h > $src_w) {
        $r = $src_w;
        imagecopyresampled($dest_img, $src_img, 0, 0, 0, ($src_h - $r) / 2, $width, $height, $r, $r);
    } else {
        $r = $src_h;
        imagecopyresampled($dest_img, $src_img, 0, 0, ($src_w - $r) / 2, 0, $width, $height, $r, $r);
    }
    //imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $width, $height, $src_w, $src_h);//新图，原图，新图坐标x,y，原图坐标x,y,新图高宽，原图高宽

    $imagefunc = 'image' . $img_type;
    if ($filename) {
        $imagefunc($dest_img, $filename);
    } else {
        header('Content-Type: ' . $src_mime);
        $imagefunc($dest_img);
    }
    imagedestroy($src_img);
    imagedestroy($dest_img);
    return true;
}

include $this->template('statedetail');


