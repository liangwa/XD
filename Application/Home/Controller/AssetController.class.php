<?php
namespace Home\Controller;
use Think\Controller;

class AssetController extends CommonController {
	
	
	private function titleassgin() {
		$this -> assign(titleA,"自动化首页");
		$this -> assign(titleAlink,__CONTROLLER__."/index");
		
				
		$data = array(
		array('name' => "个人设置", link => __MODULE__."/Profile/myauto"),
		);
		$this -> assign(othertitle,$data);
	}
	
    // 框架首页
    public function index() {
		$this -> assign(title,"XenDesktop自动化");
		$this -> assign(description,"XD VDA自动生成配置");
		$this -> titleassgin();
		
		

    }
	
}
