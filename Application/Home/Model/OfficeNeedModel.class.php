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

}