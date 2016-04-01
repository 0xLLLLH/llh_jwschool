<?php
/**
* Created by Phpstorm.
* User: 0xLLLLH
* Date: 2016.3.31
* Description：主页
*/
global  $_W,$_GPC;
$url=$this->createMobileUrl('usercenter');

$pagelist=array('个人中心'=>$url);
include $this->template('index');