<?php
/**
 * Created by Phpstorm.
 * User: 0xLLLLH
 * Date: 2016.4.2
 * Description：发布新状态
 */

global  $_W,$_GPC;
load()->func('tpl');
if(empty($_W['member']['uid'])){
    message("请先关注本公众号以完成信息注册！",'','warning');
}
$tag = pdo_fetchall("SELECT tag FROM ".tablename('jwschool_tag'),array(),'id');
//print_r($tag);
include $this->template('newstate');
