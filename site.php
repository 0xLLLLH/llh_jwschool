<?php
/**
* 模块微站定义
*
* @author 0xLLLLH
* @url http://bbs.we7.cc/
*/

defined('IN_IA') or exit('Access Denied');

class llh_jwschoolModuleSite extends WeModuleSite {
    public function doMobileIndex() {
        //这个操作被定义用来呈现 功能封面
        global  $_W,$_GPC;
        include $this->template('index');
    }

    public function doMobileWeUsercenter() {
        //这个操作被定义用来呈现 微站个人中心导航
        global  $_W,$_GPC;
        include $this->template('usercenter');
    }
}