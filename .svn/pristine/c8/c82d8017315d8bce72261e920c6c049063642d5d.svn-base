<?php
namespace Home\Model;

use Think\Model;

class BorrowlistModel extends Model
{
	
	//获取常用设备当前状态
	public function getBorrowList()
	{
		$result=$this->where('type <> "Phone / Pad"')->select();
		
		return $result;
	}
	
	//获取常用设备当前需审批的装备
	public function getApproList()
	{
		$result=$this->where('type <> "Phone / Pad" And (approved = 1 OR approved = 3)')->select();
		
		return $result;
	}
	
	//根据类型获取常用设备当前状态
	public function getBorrowListByType($type)
	{
		$result=$this->where('type ="'.$type.'"')->select();
		
		return $result;
	}
	
}