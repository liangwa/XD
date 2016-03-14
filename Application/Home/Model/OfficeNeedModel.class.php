<?php
namespace Home\Model;

use Think\Model;

class OfficeNeedModel extends Model
{
				
	protected $_validate = array(
		array(
			'CabilityID',
			'require',
			'编号必需写',
        ) ,
        array(
            'Count',
            'require',
            '数量必须填写'
        ) ,
    );
	
	protected $_auto = array(
	); 
	
	//根据Userid获取本月的已申请办公用品
	public function getNeedbyUserID($userid) {
		
		$result=0;
		$result=$this->where('UserID ="'.$userid.'" and month(Date) = month("'.date('Y-m-d').'")')->SUM('Count*binary(Price)');
		
		return $result;
	}

}