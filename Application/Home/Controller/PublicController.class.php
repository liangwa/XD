<?php
namespace Home\Controller;

use Think\Controller;
use Org\Util\Rbac;


class PublicController extends Controller {
    
	    // 检查用户是否登录
    protected function checkUser() {
        if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
            $this->error('没有登录','/PHP/Home/Public/login');
        }
    }

		
	// 用户登录页面
    public function login() {
        if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
            $this->display();
        }
		else {
            $this->redirect('/Home/index');
		}
		
    }

    public function index() {
        //如果通过认证跳转到首页
        redirect(__MODULE__);
    }

	    // 登录检测
    public function checkLogin() {


        //生成认证条件
        $map = array();
		
        // 支持使用绑定帐号登录
        $map['account']	= $_POST['account'];
        $map["status"]	=	array('gt',0);          
 
        $authInfo = RBAC::authenticate($map);
		
        //使用用户名、密码和状态 的方式进行认证
        if(false == $authInfo) {
            $this->error('帐号不存在或已禁用！');
        }
		else {
            if($authInfo['password'] != $_POST['password']) {
                $this->error('密码错误！');
        }
			
            $_SESSION[C('USER_AUTH_KEY')]	=	$authInfo['id'];
            $_SESSION['email']				=	$authInfo['email'];
            $_SESSION['loginUserName']		=	$authInfo['nickname'];
            $_SESSION['lastLoginTime']		=	$authInfo['last_login_time'];
            $_SESSION['login_count']		=	$authInfo['login_count'];
			  
		
			//保存登录信息
            $User	=	M('user');
            $ip		=	get_client_ip();
            $time	=	time();
            $data = array();
            $data['id']	=	$authInfo['id'];
            $data['last_login_time']	=	$time;
            $data['login_count']	=	array('exp','login_count+1');
            $data['last_login_ip']	=	$ip;
            $User->save($data);
		
		
            // 缓存访问权限
            RBAC::saveAccessList();
			
			$data['status'] = 1;
			$data['info'] = '登录成功！';
			$this->ajaxReturn($data);
        
		//		$this->success('登录成功！',__MODULE__.'/Index/index');

		
		/*
		// 测试信息	
			$this->assign('name',$authInfo['email']);
			$this->assign('A','带我嗨');
			$this->assign('B',$_POST['password']);
			$this->display();
		*/

        }
    }
	
	//重置密码发送邮件
	public function ForgetPassword() {
		
		$userModel = D('User');
		$userA = $userModel->getEmailAddress($_POST['forgetemail']);
		// dump($userModel->getEmailAddress($_POST['forgetemail']));
		if ($userA)
		{
			$host = $_SERVER['HTTP_HOST'];
			$key = md5(time()); //生产随机码
			$userid = $userA['id'];
			
			//保存随机码
			$userModel-> updateUserKey($userid,$key);
			
			$reset_url = "http://$host/PHP/index.php/Home/Public/ResetPage/id/$userid/key/$key";
			
			if(SendMail($_POST['forgetemail'],'XD重置密码',"请点击链接重置你的密码:<a href='$reset_url'>$reset_url</a>"))
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

	//重置密码页面	
	public function ResetPage() {
		$userModel = D('User');
		if (IS_POST && $_SESSION['repwuserid']) {
				if ($userModel-> getUserId($_SESSION['repwuserid'])) {
				
				$userModel-> updatePW($_SESSION['repwuserid'],$_POST['password1']);
				
				//删除时间码
				$userModel-> deleteUserKey($_SESSION['repwuserid']);
				
				unset($_SESSION);
				session_destroy();
			
				/* 测试信息
				$data['status'] = 1;
				$data['info'] = '密码修改成功！';
				$this->ajaxReturn($data);
				*/
				
				$this->success('密码设置成功');
				}
				
				else {
				$this->error('该链接已被使用或者用户不存在');
				}
			}
			
		else{
				//判断页面是否有效
				if ($userModel->getUserKey($_GET['id'],$_GET['key'])) {
					$_SESSION['repwuserid'] = $_GET['id'];
				//	dump($_SESSION['repwuserid']);
					$this -> display();
					
				}
				else {
					
					//测试信息
				//	$this -> display();
				//	dump($_SESSION['repwuserid']);
				
					//页面无效跳转
					$this->error('密码重置链接无效！','/PHP/Home/Public/login',3);
					}
			}
	}
		
	//首次使用
	public function FTU() {
		$userModel = D('User');
		$ftuModel = D('Ftu');
		if (IS_POST && $_SESSION['ftuemail']) {

			//进行注册
			if ($userModel->create() && $userModel->add()) {
			//	dump($userModel->getLastSql());
			
			//	删除缓存表
				$ftuModel-> deleteftuKey($_SESSION['ftuemail']);
				unset($_SESSION);
				session_destroy();
			//	dump($ftuModel->getLastSql());
			
				$this->success('注册成功','/PHP/Home/Public/login',3);
            } 
            else {
                $this->assign('errors', $userModel->getError());
                $this->assign('old', I('post.'));
                $this->display();
			//	dump($userModel->getError());
			//	dump($userModel->getLastSql());
            }
		}
		else
		{
			//判断页面是否有效
			if ($ftuModel->getftuKey($_GET['email'],$_GET['key'])) {
				$_SESSION['ftuemail'] = $_GET['email'];
				$this->assign('ftuemail',$_SESSION['ftuemail']);
			//	dump($_SESSION['ftuid']);
				$this -> display();
			//	dump('页面有效');
			}
			else {
			//	$this -> display();
			//	dump('页面无效');
				$this->error('邀请链接无效！','/PHP/Home/Public/login',2);
			}
		}
	}

}

	
	
