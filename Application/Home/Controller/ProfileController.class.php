<?php
namespace Home\Controller;
use Think\Controller;

class ProfileController extends CommonController {
    // 框架首页
    public function index() {
		
		/*
		//测试数据
		$this->assign('name',$_SESSION['email']);
		
		// 实例化Data数据模型
		$Data = M('user'); 
        $this->data = $Data->select();
		$result = $Data->select();
		dump($result);
		$this->assign('A',I('session.email'));
		*/
    }
	
	
	public function myinfo() {
		$this -> assign(title,"我的信息");
		$this -> assign(description,"查看、修改个人信息");
		
		$userModel = D('User');
		$user = $userModel-> getUserId($_SESSION[C('USER_AUTH_KEY')]);
		$this->assign('user',$user);
		if (IS_POST && $_POST['id']=$user['id']){
			//进行更新
			if ($userModel->create()) {
			$userModel->save();
			//	dump($userModel->getLastSql());
			$this->redirect('myinfo');
            } 
            else {
			$this-> assign('edit', 1);
			$this-> assign('errors', $userModel->getError());
			$this-> assign('old', I('post.'));
			//  dump($userModel->getLastSql());
            }
		}
		else {
			$this->assign('edit', 0);
			$this->assign('old', $user);
		}
		$this -> display();
	}
	
	public function EditPassword() {
		$userModel = D('User');
		// dump($userModel->getEmailAddress($_POST['forgetemail']));
		if ($userModel-> getUserId($_SESSION[C('USER_AUTH_KEY')]))
		{
			$host = $_SERVER['HTTP_HOST'];
			$key = md5(time()); //生产随机码
			
			//保存随机码
			$userModel-> updateUserKey($_SESSION[C('USER_AUTH_KEY')],$key);
			
			$reset_url = "http://$host/PHP/index.php/Home/Public/ResetPage/id/".$_SESSION[C('USER_AUTH_KEY')]."/key/$key";
			
			if(SendMail($_POST['myemail'],'XD重置密码',"请点击链接重置你的密码:<a href='$reset_url'>$reset_url</a>"))
			{
                $this->success('重置邮件已发送！');
			}
            else {
                $this->error('发送失败');
			}
			
		//	echo ($userModel->getLastSql());
		}
		else
		{
			$this->error('帐号不存在！');
		}


	}
	
}