<?php
namespace Home\Model;

use Think\Model;

class OfficeCabilityModel extends Model
{
				
	protected $_validate = array(
    );
	
	protected $_auto = array(
	); 

	//获取办公类型
	public function getTypeList()
	{
		$condition=array(
				'Disable' => 0,
			);
		$result=$this->where($condition)->order('CabilityID')->select();
		
		return $result;
	}
	
	//根据ID获取办公用品属性
	public function getOfficebyID($CabilityID)
	{
		$result=$this->where('CabilityID ="'.$CabilityID.'" and Disable != 1')->find();
		
		return $result;
	}
	
}