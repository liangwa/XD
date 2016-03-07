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
}