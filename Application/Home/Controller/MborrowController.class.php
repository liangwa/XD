<?php
namespace Home\Controller;
use Think\Controller;

class MborrowController extends BorrowController {
	
	protected function titleassgin() {
		parent::titleassgin();
	}
	
    // 框架首页
    public function index() {
		$this -> assign(title,"移动设备");
		$this -> assign(description,"查看、借用移动设备");
		$this -> titleassgin();

		
		$CabilityModel = D('Cability');
		$BorrowlistModel = D('Borrowlist');
		
		$this -> assign(typelist,$CabilityModel->getMTypeList());
		
		// parent::index();
		// dump($CabilityModel->getlastsql()); 
		
		if($_GET['type'] && $_GET['type'] != "all")
		{
			$this -> assign(borrowlist,$BorrowlistModel->getMBorrowListByType($_GET['type']));
		}
		else
		{
			$this -> assign(borrowlist,$BorrowlistModel->getMBorrowList());
		}

		// dump($BorrowlistModel->getLastSql());
        $this->display();
    }
	
	//审核页
	public function appro() {
			
		$this -> assign(title,"审核设备");
		$this -> assign(description,"允许、拒绝借出移动设备");
		$this -> titleassgin();
		
		$BorrowlistModel = D('Borrowlist');
		$this -> assign(approlist,$BorrowlistModel->getMApproList());
		
        $this->display();
    }
	
	//编辑设备页
	public function edit() {
			
		$this -> assign(title,"编辑设备");
		$this -> assign(description,"添加，编辑移动设备");
		$this -> titleassgin();
		
		$CabilityModel = D('Cability');
		
		$this -> assign(typelist,$CabilityModel->getMTypeList());
		
		if($_GET['type'] && $_GET['type'] != "all")
		{
			$this -> assign(borrowlist,$CabilityModel->getMDevicesListByType($_GET['type']));
		}
		else
		{
			$this -> assign(borrowlist,$CabilityModel->getMDevicesList());
		}

		// dump($BorrowlistModel->getLastSql());
        $this->display();
    }
	
	//损坏设备页
	public function damaged() {
		parent::damaged();
    }
	
	//编辑保存
	public function save() {
		parent::save();
	}
	
	//添加设备
	public function add() {
		parent::add();
	}
	
	//操作
	public function action(){
		parent::action();
	}
	
	public function history(){
		parent::history();
	}
}
