<?php
/**
* Created by Phpstorm.
* User: 0xLLLLH
* Date: 2016.4.2
* Description：搜索页
*/

global  $_W,$_GPC;

$tag = pdo_fetchall("SELECT tag FROM ".tablename('jwschool_tag'),array(),'id');
//print_r($tag);
include $this->template('search');