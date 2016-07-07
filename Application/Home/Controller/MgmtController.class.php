<?php
namespace Home\Controller;
use Think\Controller;

class MgmtController extends CommonController {
    // 框架首页
    public function createuser() {	
	
		$this -> assign(title,"创建用户");
		$this -> assign(description,"新的朋友加入");
		
		if (IS_POST) {
			
			$userModel = D('User');
			$ftuuser = D('Ftu');
			
			// if($this->ldapuser($_POST['username']))
			// {
				// $this->error('haha');
			// }
			// system("powershell -file D:\workspaces\PHP\Action\ad.ps1 0 ".$_POST['username']." @WSX3edc");
			// $this->success('新用户创建邮件已发送！XD1用户已经创建！');
			
			
			
			if ($userModel->getEmailAddress($_POST['email'])) {
				$this->error('帐号已存在！');
			}
			else if ($exftu = $ftuuser->getEmailAddress($_POST['email'])) {
				$this->error("邀请链接已发出！也可以手动复制链接发给他 http://$host/PHP/index.php/Home/Public/ftu/email/".$exftu['email']."/key/".$exftu['ftukey']);
			}
			else {
				$host = $_SERVER['HTTP_HOST'];
				$data['ftukey'] = md5(time()); //生产随机码
				$data['email'] = $_POST['email'];
				$data['username'] = $_POST['username'];
				
				//创建AD用户
				if (!$this->ldapuser($_POST['username']))
				{
					system("powershell -file C:\wamp\www\PHP\Action\ad.ps1 0 ".$_POST['username']." @WSX3edc");
					$msg = "XD1用户已经创建！";
				}
				else {
					$msg = "XD1用户已经存在！";
				}

				//保存数据
				$ftuuser-> add($data);
				
				$ftu_url = "http://$host/PHP/index.php/Home/Public/ftu/email/".$data['email']."/key/".$data['ftukey'] ;
				
				if(SendMail($data['email'],'XD新建用户',"你的XD1帐号已经生成，请点击激活创建您的帐号:<a href='$ftu_url'>$ftu_url</a>"))
				{
					$this->success('新用户创建邮件已发送！'.$msg.'也可以手动复制链接发给他'.$ftu_url);
				}
				else {
					$this->error('邮件发送失败'.$msg.'也可以手动复制链接发给他'.$ftu_url);
				}
					
			}
			
			
		}
		
		else {
			$this->display();
		}
	}
	
	public function ldapuser($username) {
		
		$ldapusername='XD1\\administrator';
		$ldapuserpassword ='@WSX3edc4';
		$ds=ldap_connect("172.16.80.248","389");  // must be a valid LDAP server!
		
		if ($ds) 
		{
			if (ldap_bind($ds, $ldapusername , $ldapuserpassword))
			{

				if(ldap_count_entries($ds, ldap_search($ds, "CN=Users,DC=xd1,DC=com", "samAccountName=".$username)))
				{
					return true;
				}
				else 
				{
					return false;
				}
			}
			else
			{
				$this->error('无法Bind');
			}
			
			// ldap_close($ds);
		}
		else
		{
			$this->error('无法连接域控');
		}
	}
	
	
}
