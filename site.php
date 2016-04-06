<?php
/**
 * 模块微站定义
 *
 * @author 0xLLLLH
 * @url http://bbs.we7.cc/
 */

defined('IN_IA') or exit('Access Denied');

class llh_jwschoolModuleSite extends WeModuleSite
{
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
   /* function PDO_FETCH($field, $table, $condition, $arr)
    {
        $sql = "select ";
        for ($i = 0; $i < count($field); ++$i) {
            if ($i == 0)
                $sql = $sql . $field[$i];
            else
                $sql = $sql . ' ,' . $field[$i];
        }
        $sql.=' from ';
        for ($i = 0; $i < count($table); ++$i) {
            if ($i == 0)
                $sql = $sql . $table[$i];
            else
                $sql = $sql . ' ,' . $table[$i];
        }
        $sql=$sql.' '.$condition;
    }*/
}