<?php
namespace Home\Model;

use Think\Model;

class OfficePeriodModel extends Model
{
				
	protected $_validate = array(
    );
	
	protected $_auto = array(
	); 
	
	
	//创建新的一期
	public function createNewPeriod($data,$name) {
		$n = $this->getLatestPeriod()+1;
		
		foreach($data as $value){
			$this->CabilityName=$value['CabilityName'];
			$this->Brand=$value['Brand'];
			$this->Model=$value['Model'];
			$this->Price=$value['Price'];
			$this->Unit=$value['Unit'];
			
			$this->PName=$name;
			$this->PeriodID=$n;
			
			$this->Date=date('Y-m-d');
			
			$this->add();
		}
		
		return true;
	
	}

	//获取当前一期的ID
	public function getLatestPeriod() {
		$result=$this->max('distinct(PeriodID)');
		
		return $result;
	}
	


	//获取当前一期的办公用品信息
	public function getLatestOffice() {
		$n = $this->getLatestPeriod();
		
		$result=$this->where('PeriodID ="'.$n.'"' )->select();
		
		return $result;
	}

		//根据PID获取办公用品信息
	public function getOfficeByPID($pid) {
		return $this->where('PID ="'.$pid.'"')->find();
	}
	
}