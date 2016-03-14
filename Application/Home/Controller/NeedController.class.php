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
		array('name' => "创建申请", link =>  __CONTROLLER__."/create"),
		);
		$this -> assign(othertitle,$data);
		
		$OCabilityModel = D('OfficeCability');		
		$this -> assign(typelist,$OCabilityModel->getTypeList());

			
		$this -> display();
			
		}
		
	//创建申请	
	public function create() {
		$this -> assign(title,"创建申请");
		$this -> assign(description,"创建新的申请");
		$data = array(
		array('name' => "申请历史", link => __CONTROLLER__."/history"),
		array('name' => "管理用品", link =>  __CONTROLLER__."/edit"),
		array('name' => "创建申请", link =>  __CONTROLLER__."/create"),
		);
		$this -> assign(othertitle,$data);
			
		$this -> display();
			
		}
	
	public function apply() {
		if(IS_POST) {
			$OCabilityModel = D('OfficeCability');
			$OfficeModel = D('OfficeNeed');			
			$OfficeBalanceModel = M('OfficeBalance');
			
			//获取本次申请信息
			$temp = $OCabilityModel->getOfficebyID($_POST['CabilityID']);
			
			//获取本月已申请信息(总价)
			$pn = $OfficeModel-> getNeedbyUserID($_SESSION[C('USER_AUTH_KEY')]);
			
			
			dump($pn);
			dump($OfficeModel->getlastsql());
			
			//获取欠费信息
			$balance = $OfficeBalanceModel->where('UserID ="'.$_SESSION[C('USER_AUTH_KEY')].'"')->getField('Balances');
			
			// dump($balance);
			
			// dump($temp);
			// dump($temp['cabilityid']);
			
			$bn = 2 - $balance;
			
			//判断欠费信息	 溢价的申请用品超过2件 或者 非足额的用户 都禁止超额申请
			if (($pn == 0 && $bn == 2 && $_POST['Count'] == 1) || ($temp['price']*$_POST['Count'] < $bn))
			{
				//数据库区分大小写，不合适配置
				$adddata['CabilityName'] = $temp['cabilityname'];
				$adddata['Brand'] = $temp['cabilityname'];
				$adddata['Model'] = $temp['model'];
				$adddata['Price'] = $temp['price'];
				$adddata['Unit'] = $temp['unit'];
				
				$adddata['Count'] = $_POST['Count'];
				$adddata['UserID'] = $_SESSION[C('USER_AUTH_KEY')];
				$adddata['Date'] = date('Y-m-d');
				
				// dump ($OCabilityModel->data());
				// dump ($adddata);
				// dump ($OCabilityModel->getOfficebyID($_POST['CabilityID']));
				
				if ($OfficeModel->data($adddata)->add()) 
					{
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
				dump($bn);
				dump($temp['price']*$_POST['Count']+$balance);
				$data['info'] = "亲！你已经欠费停机，请勿超额申请(本月可申请金额为".$bn.")。";
				$this->ajaxReturn($data);
			}
		}
		else {
			$this -> error('申请失败'); 
		}
	}
	
	
	
}
