<?php
namespace Home\Controller;
use Think\Controller;

class NeedController extends CommonController {
    // 框架首页
    public function index() {
		$this -> assign(title,"办公用品");
		$this -> assign(description,"查看、申请办公用品");
		$data = array(
		array('name' => "申请历史", link => __CONTROLLER__."/history"),
		array('name' => "管理用品", link =>  __CONTROLLER__."/edit"),
		);
		$this -> assign(othertitle,$data);
		

		$OCabilityModel = D('OfficeCability');
		
		$this -> assign(typelist,$OCabilityModel->getTypeList());

			
		$this -> display();
			
		}
	
	public function apply() {
		if(IS_POST) {
			$OfficeModel = D('OfficeNeed');			
			if ($OfficeModel->create()) 
				{
				$OfficeModel-> UserID = $_SESSION[C('USER_AUTH_KEY')];
				$OfficeModel-> Date = date('Y-m-d');
				$OfficeModel-> add();
				
				$data['status'] = 1;
				$data['info'] = "申请成功";
			//	$data['info'] = $OfficeModel->getLastSQL();
				$this->ajaxReturn($data);
				} 
			else {
			//	$data['info'] = $OfficeModel->getLastSQL();
				$data['info'] = $OfficeModel->getError();
				$this->ajaxReturn($data);
			}
		}
		else {
			$this -> error('申请失败'); 
		}
	}
	
	
}
